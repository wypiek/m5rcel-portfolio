// Finder list views: disclosure triangles, sorting by column, and Get Info.

import * as wm from "./windows.mjs";

const collator = new Intl.Collator("en", { sensitivity: "base", numeric: true });

const compare = {
  name: (a, b) => collator.compare(a.dataset.name, b.dataset.name),
  stars: (a, b) => (+b.dataset.stars || 0) - (+a.dataset.stars || 0) || compare.date(a, b),
  kind: (a, b) => collator.compare(a.dataset.kind, b.dataset.kind) || compare.name(a, b),
  date: (a, b) => (b.dataset.date ?? "").localeCompare(a.dataset.date ?? "") || compare.name(a, b),
};

export function sortList(list, key) {
  if (!compare[key]) return;
  list.dataset.sort = key;
  for (const btn of list.querySelectorAll(".list-head [data-sort]")) {
    btn.classList.toggle("is-sorted", btn.dataset.sort === key);
  }
  for (const box of [list, ...list.querySelectorAll(".group")]) {
    const rows = [...box.children].filter((el) => el.matches(".row.item"));
    rows.sort(compare[key]).forEach((row) => box.append(row));
  }
}

export function toggleFolder(row, open = row.getAttribute("aria-expanded") !== "true") {
  const twisty = row.querySelector(".twisty");
  const group = row.nextElementSibling;
  row.setAttribute("aria-expanded", String(open));
  twisty?.setAttribute("aria-expanded", String(open));
  if (group?.classList.contains("group")) group.hidden = !open;
}

export function openInfo(row) {
  const win = document.getElementById("win-info");
  const set = (field, text) => { win.querySelector(`[data-field="${field}"]`).textContent = text; };
  const name = row.dataset.name;
  const stars = +row.dataset.stars || 0;

  win.querySelector(".title").textContent = `${name} Info`;
  set("name", name);
  set("kind", row.dataset.kind === "Language" ? "programming language" : `${row.dataset.kind} project`);
  set("stars", stars === 1 ? "1 star" : `${stars} stars`);
  set("where", `github.com/${row.dataset.where}`);
  set("date", row.querySelector(".c-date")?.textContent ?? "");
  set("desc", row.dataset.desc ?? "");
  win.querySelector('[data-field="link"]').href = row.href;
  wm.open("win-info", row);
}

export function initFinder() {
  for (const row of document.querySelectorAll(".row.folder")) {
    toggleFolder(row, row.getAttribute("aria-expanded") === "true");
    row.querySelector(".twisty").addEventListener("click", (e) => {
      e.stopPropagation();
      toggleFolder(row);
    });
  }

  for (const list of document.querySelectorAll(".list")) {
    const key = list.dataset.sort;
    if (key && key !== "none") sortList(list, key);
    for (const btn of list.querySelectorAll(".list-head [data-sort]")) {
      btn.addEventListener("click", () => sortList(list, btn.dataset.sort));
    }
  }
}
