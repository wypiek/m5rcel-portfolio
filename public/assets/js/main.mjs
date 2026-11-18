import * as wm from "./windows.mjs";
import { initMenus } from "./menus.mjs";
import { initFinder, sortList, openInfo } from "./finder.mjs";
import { initDesktop, selection, activate, alertBox, copyEmail, emptyTrash, restart, shutDown } from "./desktop.mjs";

document.documentElement.classList.add("js");

initFinder();
wm.initWindows();
initDesktop();

const activeList = () => wm.activeWindow()?.querySelector(".list");

initMenus({
  state() {
    const sel = selection();
    const list = activeList();
    return {
      selection: Boolean(sel),
      window: Boolean(wm.activeWindow()),
      project: Boolean(sel?.matches(".row.item[data-where]")),
      desktop: wm.isDesktop(),
      sortKey: list?.dataset.sort,
      sortKeys: new Set([...(list?.querySelectorAll(".list-head [data-sort]") ?? [])].map((b) => b.dataset.sort)),
    };
  },
  actions: {
    open: (item) => wm.open(item.dataset.target),
    "open-selection": () => {
      const sel = selection();
      if (sel && !activate(sel)) sel.click();
    },
    "close-window": () => wm.close(wm.activeWindow()),
    "get-info": () => openInfo(selection()),
    "copy-email": copyEmail,
    sort: (item) => { const list = activeList(); if (list) sortList(list, item.dataset.key); },
    "clean-up": wm.cleanUp,
    "empty-trash": emptyTrash,
    restart,
    "shut-down": shutDown,
    "hide-others": () => wm.closeAllExcept(wm.activeWindow()),
    "show-all": () => { for (const id of ["win-about", "win-projects", "win-readme"]) wm.open(id); },
  },
});

// Exposed for poking around in the console. Hi.
window.m5rcel = { alertBox, wm };
