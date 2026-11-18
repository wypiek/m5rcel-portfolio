// The menu bar. Works both ways the Mac did over the years: press, drag to an
// item and release; or click a title once and the menu stays open.
// The chosen item blinks before its action runs, like the real thing.

const reduceMotion = matchMedia("(prefers-reduced-motion: reduce)");
const ITEM = '[role="menuitem"], [role="menuitemradio"]';

export function initMenus({ actions, state }) {
  const bar = document.querySelector(".menubar");
  const roots = [...bar.querySelectorAll(".menu-root")];
  let current = null;
  let dragging = false;
  let busy = false;

  const titleOf = (root) => root.querySelector(".menu-title");
  const menuOf = (root) => root.querySelector(".menu");
  const items = (root) => [...menuOf(root).querySelectorAll(ITEM)].filter((i) => i.getAttribute("aria-disabled") !== "true");

  function refresh(root) {
    const s = state();
    for (const item of root.querySelectorAll("[data-enable]")) {
      const key = item.dataset.enable;
      const on = key === "list" ? s.sortKeys.has(item.dataset.key) : Boolean(s[key]);
      item.setAttribute("aria-disabled", String(!on));
    }
    for (const item of root.querySelectorAll('[data-action="sort"]')) {
      item.setAttribute("aria-checked", String(s.sortKey === item.dataset.key));
    }
  }

  function show(root) {
    if (current === root) return;
    hide();
    refresh(root);
    root.classList.add("is-open");
    menuOf(root).hidden = false;
    titleOf(root).setAttribute("aria-expanded", "true");
    current = root;
  }

  function hide() {
    if (!current) return;
    current.classList.remove("is-open");
    menuOf(current).hidden = true;
    titleOf(current).setAttribute("aria-expanded", "false");
    current = null;
  }

  async function choose(item) {
    if (busy) return;
    if (item.getAttribute("aria-disabled") === "true" || !item.dataset.action) {
      hide();
      return;
    }
    busy = true;
    if (!reduceMotion.matches) {
      for (let i = 0; i < 2; i++) {
        item.classList.add("is-blink-off");
        await wait(45);
        item.classList.remove("is-blink-off");
        item.classList.add("is-blink");
        await wait(45);
        item.classList.remove("is-blink");
      }
    }
    hide();
    busy = false;
    actions[item.dataset.action]?.(item);
  }

  for (const root of roots) {
    const title = titleOf(root);

    title.addEventListener("pointerdown", (e) => {
      if (e.button !== 0) return;
      e.preventDefault();
      if (current === root) {
        hide();
        return;
      }
      show(root);
      dragging = true;
    });

    title.addEventListener("pointerenter", () => { if (current && current !== root) show(root); });

    // Keyboard: Enter or Space on a title opens it and focuses the first item.
    title.addEventListener("click", (e) => {
      if (e.detail !== 0) return;
      if (current === root) hide();
      else {
        show(root);
        items(root)[0]?.focus();
      }
    });

    menuOf(root).addEventListener("click", (e) => {
      const item = e.target.closest(ITEM);
      if (item) choose(item);
    });
  }

  // Release over an item after dragging down from a title.
  document.addEventListener("pointerup", (e) => {
    if (!dragging) return;
    dragging = false;
    const el = document.elementFromPoint(e.clientX, e.clientY);
    const item = el?.closest(ITEM);
    if (item && current?.contains(item)) choose(item);
    else if (!el?.closest(".menu-title")) hide();
  });

  document.addEventListener("pointerdown", (e) => {
    if (current && !e.target.closest(".menubar")) hide();
  });

  bar.addEventListener("keydown", (e) => {
    if (!current) return;
    const list = items(current);
    const at = list.indexOf(document.activeElement);
    const i = roots.indexOf(current);
    const go = (root) => { show(root); (items(root)[0] ?? titleOf(root)).focus(); };

    switch (e.key) {
      case "Escape":
        e.preventDefault();
        titleOf(current).focus();
        hide();
        break;
      case "ArrowDown":
        e.preventDefault();
        list[(at + 1) % list.length]?.focus();
        break;
      case "ArrowUp":
        e.preventDefault();
        list[(at - 1 + list.length) % list.length]?.focus();
        break;
      case "ArrowRight":
        e.preventDefault();
        go(roots[(i + 1) % roots.length]);
        break;
      case "ArrowLeft":
        e.preventDefault();
        go(roots[(i - 1 + roots.length) % roots.length]);
        break;
    }
  });

  return { hide };
}

const wait = (ms) => new Promise((r) => setTimeout(r, ms));
