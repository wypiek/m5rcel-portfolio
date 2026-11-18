// Desktop behaviour: selecting and opening icons, the clocks, desktop
// patterns, alert boxes, and the Special menu's Restart and Shut Down.

import * as wm from "./windows.js";
import { openInfo, toggleFolder } from "./finder.js";

const ICONS = new URL("../icons/", import.meta.url);
const iconUrl = (name) => new URL(`${name}.svg`, ICONS).href;

/* ---- Selection --------------------------------------------------------- */

let selected = null;
let lastPointer = "mouse";

export const selection = () => (selected?.isConnected && !selected.closest("[hidden]") ? selected : null);

function select(el) {
  if (selected === el) return;
  selected?.classList.remove("is-selected");
  selected = el;
  el?.classList.add("is-selected");
}

// Double-click (or a tap, or Enter) opens. Returns false when the browser
// should carry on and follow the link itself.
export function activate(el) {
  if (el.dataset.opens) {
    wm.open(el.dataset.opens, el);
    return true;
  }
  if (el.matches(".row.folder")) {
    toggleFolder(el);
    return true;
  }
  if (el.matches(".row.item[data-where]")) {
    openInfo(el);
    return true;
  }
  return false;
}

function initSelection() {
  document.addEventListener("pointerdown", (e) => { lastPointer = e.pointerType; }, true);

  document.addEventListener("click", (e) => {
    if (e.target.closest(".menubar, .box, .twisty, .btn, .list-head, .dialog-layer")) return;
    const el = e.target.closest(".icon, .row");
    if (!el) {
      if (e.target.closest(".window-body, #desktop")) select(null);
      return;
    }
    const direct = e.detail === 0 || lastPointer !== "mouse";
    if (direct || e.detail === 2) {
      select(el);
      if (activate(el) || e.detail > 2) e.preventDefault();
      return;
    }
    // First click only selects, even on links.
    e.preventDefault();
    select(el);
  });

  // Open-window icons get the grey dither; the mask needs an absolute URL.
  for (const icon of document.querySelectorAll(".icon[data-opens]")) {
    const img = icon.querySelector("img");
    icon.style.setProperty("--icon", `url("${img.src}")`);
  }
  const markOpen = () => {
    for (const icon of document.querySelectorAll(".icon[data-opens]")) {
      icon.classList.toggle("is-open", !document.getElementById(icon.dataset.opens)?.hidden);
    }
  };
  wm.onChange(markOpen);
  markOpen();
}

/* ---- Clocks ------------------------------------------------------------ */

function initClocks() {
  const menuClock = document.getElementById("clock");
  const warsaw = document.getElementById("warsaw-time");
  const local = new Intl.DateTimeFormat(undefined, { hour: "numeric", minute: "2-digit" });
  const pl = new Intl.DateTimeFormat("en-GB", { hour: "2-digit", minute: "2-digit", timeZone: "Europe/Warsaw" });

  const tick = () => {
    const now = new Date();
    menuClock.textContent = local.format(now);
    menuClock.dateTime = now.toISOString();
    warsaw.textContent = pl.format(now);
  };
  tick();
  document.getElementById("warsaw-line").hidden = false;
  setInterval(tick, 10_000);
}

/* ---- Desktop patterns -------------------------------------------------- */

const PATTERNS = [
  { id: null, name: "Auto" },
  { id: "periwinkle", name: "Periwinkle", a: "#a3a3d0", b: "#9191c2" },
  { id: "gray", name: "Gray", a: "#b2b2b2", b: "#959595" },
  { id: "toast", name: "Toast", a: "#d9b17b", b: "#c89b62", size: "4px" },
  { id: "mint", name: "Mint", a: "#a9d3c0", b: "#96c4af" },
  { id: "night", name: "Night", a: "#33335a", b: "#2a2a4a" },
];
const STORE_KEY = "m5rcel:pattern";

function storedPattern() {
  try { return localStorage.getItem(STORE_KEY); } catch { return null; }
}

function applyPattern(id) {
  const root = document.documentElement;
  if (id) root.dataset.pattern = id;
  else delete root.dataset.pattern;
  try {
    if (id) localStorage.setItem(STORE_KEY, id);
    else localStorage.removeItem(STORE_KEY);
  } catch { /* private mode: the choice just won't stick */ }
}

function initPatterns() {
  const stored = storedPattern();
  if (PATTERNS.some((p) => p.id === stored)) applyPattern(stored);

  const swatch = document.getElementById("pattern-swatch");
  const label = document.getElementById("pattern-name");
  let index = Math.max(0, PATTERNS.findIndex((p) => p.id === (document.documentElement.dataset.pattern ?? null)));

  const preview = () => {
    let p = PATTERNS[index];
    if (!p.id) p = matchMedia("(prefers-color-scheme: dark)").matches ? PATTERNS[5] : PATTERNS[1];
    swatch.style.setProperty("--sw-a", p.a);
    swatch.style.setProperty("--sw-b", p.b);
    swatch.style.setProperty("--sw-size", p.size ?? "2px");
    label.value = PATTERNS[index].name;
  };
  const step = (d) => { index = (index + d + PATTERNS.length) % PATTERNS.length; preview(); };

  document.getElementById("pattern-prev").addEventListener("click", () => step(-1));
  document.getElementById("pattern-next").addEventListener("click", () => step(1));
  document.getElementById("pattern-set").addEventListener("click", () => applyPattern(PATTERNS[index].id));
  preview();
}

/* ---- Alert boxes ------------------------------------------------------- */

export function alertBox({ icon = "caution", lines, buttons = [{ label: "OK", value: true }], black = false }) {
  return new Promise((resolve) => {
    const back = document.activeElement;
    const layer = document.createElement("div");
    layer.className = `dialog-layer${black ? " is-black" : ""}`;

    const box = document.createElement("div");
    box.className = "dialog";
    box.setAttribute("role", "alertdialog");
    box.setAttribute("aria-modal", "true");

    const img = new Image(32, 32);
    img.src = iconUrl(icon);
    img.alt = "";

    const text = document.createElement("div");
    text.className = "dialog-text";
    text.id = `alert-${Date.now()}`;
    for (const line of lines) text.append(Object.assign(document.createElement("p"), { textContent: line }));
    box.setAttribute("aria-describedby", text.id);

    const actions = document.createElement("div");
    actions.className = "dialog-actions";
    const finish = (value) => {
      layer.remove();
      document.removeEventListener("keydown", onKey, true);
      back?.focus?.();
      resolve(value);
    };
    const made = buttons.map((b, i) => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = `btn${i === buttons.length - 1 ? " default" : ""}`;
      btn.textContent = b.label;
      btn.addEventListener("click", () => finish(b.value));
      actions.append(btn);
      return btn;
    });
    const fallback = buttons.find((b) => b.value === false) ?? buttons[buttons.length - 1];
    const onKey = (e) => {
      if (e.key === "Escape") { e.preventDefault(); finish(fallback.value); }
      if (e.key === "Tab") {
        e.preventDefault();
        const at = made.indexOf(document.activeElement);
        made[(at + (e.shiftKey ? -1 : 1) + made.length) % made.length].focus();
      }
    };
    document.addEventListener("keydown", onKey, true);

    box.append(img, text, actions);
    layer.append(box);
    document.body.append(layer);
    made[made.length - 1].focus();
  });
}

/* ---- Special menu ------------------------------------------------------ */

function blackout() {
  const layer = document.createElement("div");
  layer.className = "dialog-layer is-black";
  document.body.append(layer);
  return layer;
}

export function restart() {
  blackout();
  setTimeout(() => location.reload(), 700);
}

export async function shutDown() {
  const layer = blackout();
  await new Promise((r) => setTimeout(r, 500));
  layer.remove();
  await alertBox({
    icon: "bread-32",
    lines: ["It's now safe to close this tab."],
    buttons: [{ label: "Restart", value: true }],
    black: true,
  });
  restart();
}

export async function emptyTrash() {
  const sure = await alertBox({
    lines: ["The Trash contains 1 item, which uses 20 GB of disk space.", "Are you sure you want to permanently remove it?"],
    buttons: [{ label: "Cancel", value: false }, { label: "OK", value: true }],
  });
  if (!sure) return;
  await alertBox({ lines: ["“Windows 11” could not be removed, because it is installing updates (12%)."] });
}

/* ---- E-mail ------------------------------------------------------------ */

export const email = () => {
  const a = document.querySelector("[data-mail]");
  return `${a.dataset.mail}@${a.dataset.domain}`;
};

function initMail() {
  // Assembled here so the address isn't sitting in the HTML for scrapers.
  for (const a of document.querySelectorAll("[data-mail]")) a.href = `mailto:${a.dataset.mail}@${a.dataset.domain}`;
}

export async function copyEmail() {
  const address = email();
  try {
    await navigator.clipboard.writeText(address);
    await alertBox({ icon: "mail", lines: [`Copied ${address} to the Clipboard.`] });
  } catch {
    await alertBox({ icon: "mail", lines: [`The Clipboard isn't available here. The address is ${address}.`] });
  }
}

export function initDesktop() {
  initSelection();
  initClocks();
  initPatterns();
  initMail();
}
