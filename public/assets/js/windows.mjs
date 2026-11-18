// A small window manager in the spirit of the System 7 Finder.
//
// Wide screens: windows are absolutely placed on the desktop, dragged by their
// title bars (an outline follows the pointer, the window jumps on release),
// resized from the grow box and zoomed with the zoom box.
// Narrow screens: the same windows stack in one column, newest first.

const desktopQuery = matchMedia("(min-width: 760px)");
const reduceMotion = matchMedia("(prefers-reduced-motion: reduce)");

const ICON_COLUMN = 112; // room kept free for the desktop icons on the right
const geometry = new Map(); // window -> { x, y, w, h? } in desktop coordinates
const listeners = new Set();
let z = 10;

export const isDesktop = () => desktopQuery.matches;
export const onChange = (fn) => listeners.add(fn);
const changed = () => listeners.forEach((fn) => fn());

export const allWindows = () => [...document.querySelectorAll(".window")];
export const openWindows = () => allWindows().filter((w) => !w.hidden);
export const activeWindow = () => document.querySelector(".window.is-active:not([hidden])");

const desktop = () => document.getElementById("desktop");

function desktopSize() {
  const r = desktop().getBoundingClientRect();
  return { W: r.width, H: r.height, left: r.left, top: r.top };
}

function topmost() {
  return openWindows().sort((a, b) => (+b.style.zIndex || 0) - (+a.style.zIndex || 0))[0] ?? null;
}

/* ---- Layout ------------------------------------------------------------ */

// Where each window sits the first time it opens, and after Clean Up.
function layout(id, W, H) {
  const right = W - ICON_COLUMN;
  const wide = W >= 1180;
  const aboutY = Math.max(300, H - 318);
  const showAbout = wide && H >= 640;

  switch (id) {
    case "win-readme":
      return { x: 20, y: 16, w: Math.min(470, right - 40), h: Math.min(500, showAbout ? aboutY - 32 : H - 40) };
    case "win-projects":
      return wide
        ? { x: 516, y: 44, w: Math.min(660, right - 536), h: Math.min(480, H - 100) }
        : { x: Math.max(64, right - 600), y: 70, w: Math.min(600, right - 84), h: Math.min(440, H - 110) };
    case "win-about":
      return { x: 40, y: aboutY, w: 466, startsOpen: showAbout };
    case "win-hd":       return { x: right - 440, y: 24, w: 420, h: 300 };
    case "win-blog":     return { x: right - 640, y: 120, w: 620, h: 210 };
    case "win-links":    return { x: right - 500, y: 88, w: 470, h: 270 };
    case "win-domains":  return { x: right - 470, y: 150, w: 440, h: 300 };
    case "win-trash":    return { x: right - 340, y: H - 250, w: 320, h: 190 };
    case "win-info":     return { x: Math.round(W / 2 - 170), y: 96, w: 340 };
    case "win-patterns": return { x: right - 330, y: 60, w: 300 };
    default:             return { x: 60, y: 60, w: 420, h: 300 };
  }
}

function clamp(g, W, H) {
  const out = { ...g };
  out.w = Math.min(out.w, W - 16);
  out.x = Math.min(Math.max(8, out.x), W - out.w - 8);
  out.y = Math.max(6, Math.min(out.y, H - 60));
  if (out.h) out.h = Math.max(120, Math.min(out.h, H - out.y - 10));
  return out;
}

function place(win) {
  const s = win.style;
  if (!isDesktop()) {
    s.left = s.top = s.width = s.height = "";
    return;
  }
  const g = geometry.get(win);
  if (!g) return;
  s.left = `${g.x}px`;
  s.top = `${g.y}px`;
  s.width = `${g.w}px`;
  s.height = g.h ? `${g.h}px` : "";
}

function ensureGeometry(win) {
  if (geometry.has(win)) return;
  const { W, H } = desktopSize();
  geometry.set(win, clamp(layout(win.id, W, H), W, H));
}

/* ---- Focus, open, close ----------------------------------------------- */

export function focus(win) {
  if (!win || win.hidden) return;
  for (const w of allWindows()) w.classList.toggle("is-active", w === win);
  win.style.zIndex = String(++z);
  win.style.order = String(-z);
  changed();
}

export async function open(id, from) {
  const win = document.getElementById(id);
  if (!win) return;
  if (!win.hidden) {
    focus(win);
    if (!isDesktop()) scrollTo(win);
    return;
  }
  ensureGeometry(win);
  place(win);
  if (isDesktop() && from && !reduceMotion.matches) {
    win.style.visibility = "hidden";
    win.hidden = false;
    const target = win.getBoundingClientRect();
    await zoomRects(from.getBoundingClientRect(), target);
    win.style.visibility = "";
  } else {
    win.hidden = false;
  }
  focus(win);
  if (!isDesktop()) scrollTo(win);
}

export async function close(win) {
  if (!win || win.hidden) return;
  const icon = iconFor(win.id);
  if (isDesktop() && icon && !reduceMotion.matches) {
    const from = win.getBoundingClientRect();
    win.hidden = true;
    zoomRects(from, icon.getBoundingClientRect());
  } else {
    win.hidden = true;
  }
  win.classList.remove("is-active");
  const next = topmost();
  if (next) focus(next);
  else changed();
}

export function closeAllExcept(keep) {
  for (const w of openWindows()) if (w !== keep) w.hidden = true;
  if (keep) focus(keep);
  else changed();
}

// The icon a window would zoom back into: the desktop one if there is one.
function iconFor(id) {
  const icons = [...document.querySelectorAll(`.icon[data-opens="${id}"]`)];
  return icons.find((i) => i.closest(".desk-icons")) ?? icons.find((i) => !i.closest(".window[hidden]")) ?? null;
}

function scrollTo(win) {
  win.scrollIntoView({ behavior: reduceMotion.matches ? "auto" : "smooth", block: "start" });
}

export function cleanUp() {
  const { W, H } = desktopSize();
  for (const win of allWindows()) {
    geometry.set(win, clamp(layout(win.id, W, H), W, H));
    delete win.dataset.zoomed;
    place(win);
  }
}

/* ---- Zoom rectangles --------------------------------------------------- */

function frame(el, r) {
  el.style.left = `${r.left}px`;
  el.style.top = `${r.top}px`;
  el.style.width = `${r.width}px`;
  el.style.height = `${r.height}px`;
}

function zoomRects(a, b) {
  const steps = 9;
  const trail = [];
  const lerp = (p, q, t) => p + (q - p) * t;
  return new Promise((resolve) => {
    let i = 1;
    const tick = () => {
      if (i > steps) {
        trail.forEach((g) => g.remove());
        resolve();
        return;
      }
      const t = i / steps;
      const g = document.createElement("div");
      g.className = "ghost";
      frame(g, {
        left: lerp(a.left, b.left, t),
        top: lerp(a.top, b.top, t),
        width: lerp(a.width, b.width, t),
        height: lerp(a.height, b.height, t),
      });
      document.body.append(g);
      trail.push(g);
      if (trail.length > 3) trail.shift().remove();
      i++;
      requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  });
}

/* ---- Dragging and growing --------------------------------------------- */

function track(win, e, mode) {
  if (!isDesktop() || e.button !== 0) return;
  e.preventDefault();
  focus(win);

  const handle = e.currentTarget;
  const start = win.getBoundingClientRect();
  const { W, H, left: dl, top: dt } = desktopSize();
  const ghost = document.createElement("div");
  ghost.className = "ghost";
  frame(ghost, start);
  document.body.append(ghost);
  handle.setPointerCapture(e.pointerId);

  let next = start;
  let moved = false;

  const move = (ev) => {
    const dx = ev.clientX - e.clientX;
    const dy = ev.clientY - e.clientY;
    if (Math.abs(dx) + Math.abs(dy) > 2) moved = true;
    if (mode === "move") {
      next = {
        left: Math.min(Math.max(start.left + dx, dl - start.width + 48), dl + W - 48),
        top: Math.min(Math.max(start.top + dy, dt), dt + H - 22),
        width: start.width,
        height: start.height,
      };
    } else {
      next = {
        left: start.left,
        top: start.top,
        width: Math.max(220, Math.min(start.width + dx, dl + W - start.left)),
        height: Math.max(120, Math.min(start.height + dy, dt + H - start.top)),
      };
    }
    frame(ghost, next);
  };

  const end = () => {
    handle.removeEventListener("pointermove", move);
    handle.removeEventListener("pointerup", end);
    handle.removeEventListener("pointercancel", end);
    ghost.remove();
    if (!moved) return;
    const g = geometry.get(win);
    if (mode === "move") {
      g.x = Math.round(next.left - dl);
      g.y = Math.round(next.top - dt);
    } else {
      g.w = Math.round(next.width);
      g.h = Math.round(next.height);
      delete win.dataset.zoomed;
    }
    place(win);
  };

  handle.addEventListener("pointermove", move);
  handle.addEventListener("pointerup", end);
  handle.addEventListener("pointercancel", end);
}

function toggleZoom(win) {
  const g = geometry.get(win);
  if (!g) return;
  if (win.dataset.zoomed) {
    geometry.set(win, JSON.parse(win.dataset.zoomed));
    delete win.dataset.zoomed;
  } else {
    const { W, H } = desktopSize();
    win.dataset.zoomed = JSON.stringify(g);
    geometry.set(win, { x: 8, y: 8, w: W - ICON_COLUMN - 16, h: H - 18 });
  }
  place(win);
}

/* ---- Setup ------------------------------------------------------------- */

function applyMode() {
  document.documentElement.classList.toggle("wm", isDesktop());
  for (const win of allWindows()) {
    if (isDesktop()) ensureGeometry(win);
    place(win);
  }
}

export function initWindows() {
  applyMode();
  const { W, H } = desktopSize();

  for (const win of allWindows()) {
    const startsOpen = win.dataset.default === "open" && (win.id !== "win-about" || !isDesktop() || layout(win.id, W, H).startsOpen);
    win.hidden = !startsOpen;

    win.addEventListener("pointerdown", () => { if (!win.classList.contains("is-active")) focus(win); }, true);
    win.addEventListener("focusin", () => { if (!win.classList.contains("is-active")) focus(win); });

    const bar = win.querySelector(".titlebar");
    bar.addEventListener("pointerdown", (e) => { if (!e.target.closest(".box")) track(win, e, "move"); });
    win.querySelector(".box.close")?.addEventListener("click", () => close(win));
    win.querySelector(".box.zoom")?.addEventListener("click", () => toggleZoom(win));
    win.querySelector(".grow")?.addEventListener("pointerdown", (e) => track(win, e, "grow"));
  }

  // Stack order on first load: the Read Me ends up in front.
  for (const id of ["win-about", "win-projects", "win-readme"]) focus(document.getElementById(id));

  desktopQuery.addEventListener("change", applyMode);
  addEventListener("resize", () => {
    if (!isDesktop()) return;
    const { W: w, H: h } = desktopSize();
    for (const [win, g] of geometry) {
      geometry.set(win, clamp(g, w, h));
      place(win);
    }
  });
}
