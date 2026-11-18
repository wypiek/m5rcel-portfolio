// Draws the pixel icons used on the site and writes them to public/assets/icons.
// Run with `node tools/icons.mjs` after changing anything here. The output is
// committed, so the site itself needs no build step.

import { mkdirSync, writeFileSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";

const OUT = join(dirname(fileURLToPath(import.meta.url)), "../public/assets/icons");

// System 7's 8-bit icon palette, more or less.
const C = {
  k: "#000000", w: "#ffffff", l: "#eeeeee", g: "#cccccc", G: "#999999",
  d: "#666666", p: "#ccccff", P: "#9999ff", q: "#6666cc",
  c: "#99ccff", u: "#3366cc", n: "#00aa00", Y: "#ffff66",
  t: "#ffcc99", o: "#cc9966", b: "#996633", B: "#663300", r: "#dd0000",
};

class Grid {
  constructor(size) {
    this.size = size;
    this.px = Array.from({ length: size }, () => Array(size).fill(null));
  }
  dot(x, y, c) {
    if (x >= 0 && y >= 0 && x < this.size && y < this.size) this.px[y][x] = c;
    return this;
  }
  rect(x, y, w, h, c) {
    for (let j = y; j < y + h; j++) for (let i = x; i < x + w; i++) this.dot(i, j, c);
    return this;
  }
  line(x0, y0, x1, y1, c) {
    const dx = Math.abs(x1 - x0), dy = -Math.abs(y1 - y0);
    const sx = x0 < x1 ? 1 : -1, sy = y0 < y1 ? 1 : -1;
    let err = dx + dy;
    for (;;) {
      this.dot(x0, y0, c);
      if (x0 === x1 && y0 === y1) break;
      const e2 = 2 * err;
      if (e2 >= dy) { err += dy; x0 += sx; }
      if (e2 <= dx) { err += dx; y0 += sy; }
    }
    return this;
  }
  // Fill every pixel whose centre passes the test, then outline the shape in k.
  shape(test, fill, edge = "k") {
    const inside = (x, y) => x >= 0 && y >= 0 && x < this.size && y < this.size && test(x + 0.5, y + 0.5);
    for (let y = 0; y < this.size; y++)
      for (let x = 0; x < this.size; x++) {
        if (!inside(x, y)) continue;
        const rim = !inside(x - 1, y) || !inside(x + 1, y) || !inside(x, y - 1) || !inside(x, y + 1);
        this.dot(x, y, rim ? edge : typeof fill === "function" ? fill(x, y) : fill);
      }
    return this;
  }
  scaled(f) {
    const g = new Grid(this.size * f);
    for (let y = 0; y < this.size; y++)
      for (let x = 0; x < this.size; x++) if (this.px[y][x]) g.rect(x * f, y * f, f, f, this.px[y][x]);
    return g;
  }
  svg() {
    // One <rect> per horizontal run of the same colour keeps the files small.
    const rects = [];
    this.px.forEach((row, y) => {
      for (let x = 0; x < row.length; ) {
        const c = row[x];
        let run = 1;
        while (x + run < row.length && row[x + run] === c) run++;
        if (c) rects.push(`<rect x="${x}" y="${y}" width="${run}" height="1" fill="${C[c]}"/>`);
        x += run;
      }
    });
    const s = this.size;
    return `<svg xmlns="http://www.w3.org/2000/svg" width="${s}" height="${s}" viewBox="0 0 ${s} ${s}" shape-rendering="crispEdges">${rects.join("")}</svg>\n`;
  }
}

const icons = {};

/* ---- 32 x 32 ---------------------------------------------------------- */

icons.folder = new Grid(32)
  .rect(3, 5, 7, 1, "k").dot(2, 6, "k").dot(10, 6, "k").rect(3, 6, 7, 1, "w")
  .rect(1, 7, 1, 21, "k").rect(30, 7, 1, 21, "k").rect(1, 28, 30, 1, "k").rect(11, 7, 19, 1, "k")
  .rect(2, 7, 9, 1, "p").rect(2, 8, 28, 20, "p")
  .rect(2, 8, 27, 1, "w").rect(2, 8, 1, 19, "w")
  .rect(2, 27, 28, 1, "P").rect(29, 8, 1, 20, "P");

function documentPage(g, lines) {
  g.rect(5, 1, 16, 1, "k").rect(5, 1, 1, 30, "k").rect(5, 30, 22, 1, "k").rect(26, 7, 1, 24, "k")
    .rect(6, 2, 14, 5, "w").rect(6, 7, 20, 23, "w")
    .rect(20, 1, 1, 6, "k").rect(20, 6, 6, 1, "k");
  for (let y = 2; y <= 6; y++) g.dot(y + 19, y, "k");
  g.dot(21, 3, "g").rect(21, 4, 2, 1, "g").rect(21, 5, 3, 1, "g");
  lines.forEach(([y, w]) => g.rect(8, y, w, 1, "G"));
  return g;
}
icons.document = documentPage(new Grid(32), [[10, 15], [13, 15], [16, 12], [19, 15], [22, 9], [25, 13]]);

// The Read Me gets a bold heading line so it reads differently from a plain doc.
icons.readme = documentPage(new Grid(32), [[13, 15], [16, 12], [19, 15], [22, 9], [25, 13]])
  .rect(8, 9, 10, 2, "k");

icons.disk = new Grid(32)
  .rect(4, 9, 24, 1, "k").dot(3, 10, "k").dot(28, 10, "k").rect(4, 10, 24, 1, "w")
  .rect(2, 11, 28, 1, "k").rect(2, 11, 1, 14, "k").rect(29, 11, 1, 14, "k").rect(2, 24, 28, 1, "k")
  .rect(3, 12, 26, 12, "g").rect(3, 12, 26, 1, "w").rect(3, 12, 1, 11, "w")
  .rect(3, 23, 26, 1, "G").rect(28, 13, 1, 11, "G")
  .rect(6, 18, 3, 2, "n").rect(6, 20, 3, 1, "d")
  .rect(14, 16, 12, 1, "d").rect(14, 17, 12, 1, "w").rect(14, 19, 12, 1, "d").rect(14, 20, 12, 1, "w")
  .rect(4, 25, 4, 1, "k").rect(24, 25, 4, 1, "k");

function trash(g, full) {
  const lift = full ? 3 : 0;
  g.rect(13, 2 - lift + 1, 6, 1, "k").rect(12, 3 - lift + 1, 1, 2, "k").rect(19, 3 - lift + 1, 1, 2, "k");
  const ly = 5 - lift + 1;
  g.rect(6, ly, 20, 1, "k").rect(6, ly + 1, 1, 2, "k").rect(25, ly + 1, 1, 2, "k").rect(6, ly + 3, 20, 1, "k")
    .rect(7, ly + 1, 18, 2, "g").rect(7, ly + 1, 18, 1, "w");
  if (full) {
    g.rect(9, 6, 7, 3, "w").rect(9, 6, 7, 1, "k").rect(9, 6, 1, 3, "k").rect(15, 6, 1, 3, "k")
      .rect(16, 7, 6, 2, "c").rect(16, 7, 6, 1, "k").rect(21, 7, 1, 2, "k").dot(12, 7, "G");
  }
  g.rect(8, 9, 1, 20, "k").rect(23, 9, 1, 20, "k").rect(8, 9, 16, 1, "k").rect(8, 29, 16, 1, "k")
    .rect(9, 10, 14, 19, "g");
  for (const x of [11, 14, 17, 20]) g.rect(x, 12, 1, 15, "d").rect(x + 1, 12, 1, 15, "w");
  return g;
}
icons.trash = trash(new Grid(32), false);
icons["trash-full"] = trash(new Grid(32), true);

// Generic System 7 application: a diamond.
icons.app = new Grid(32).shape(
  (x, y) => Math.abs(x - 16) + Math.abs(y - 16) <= 14,
  (x, y) => (x + y > 33 ? "P" : x + y < 25 ? "w" : "p"),
);
icons.app.rect(12, 15, 8, 2, "q").rect(15, 12, 2, 8, "q");

icons.globe = new Grid(32).shape(
  (x, y) => Math.hypot(x - 16, y - 16) <= 13.6,
  (x, y) => {
    const dx = x + 0.5 - 16, dy = y + 0.5 - 16;
    const meridian = Math.abs(Math.hypot(dx / 6.5, dy / 13.6) - 1) < 0.09;
    if (meridian || x === 15 || y === 15 || y === 9 || y === 22) return "u";
    return dx + dy < -6 ? "w" : "c";
  },
);

icons.mail = new Grid(32)
  .rect(2, 8, 28, 1, "k").rect(2, 8, 1, 17, "k").rect(29, 8, 1, 17, "k").rect(2, 24, 28, 1, "k")
  .rect(3, 9, 26, 15, "w").rect(3, 23, 26, 1, "g")
  .line(3, 9, 15, 18, "k").line(28, 9, 16, 18, "k")
  .line(3, 23, 12, 16, "G").line(28, 23, 19, 16, "G");

icons.panel = new Grid(32)
  .rect(3, 3, 26, 1, "k").rect(3, 3, 1, 26, "k").rect(28, 3, 1, 26, "k").rect(3, 28, 26, 1, "k")
  .rect(4, 4, 24, 24, "g").rect(4, 4, 24, 1, "w").rect(4, 4, 1, 24, "w").rect(4, 27, 24, 1, "G").rect(27, 4, 1, 24, "G")
  .rect(7, 7, 18, 7, "k").rect(8, 8, 16, 5, "P");
for (let y = 8; y < 13; y++) for (let x = 8; x < 24; x++) if ((x + y) % 2) icons.panel.dot(x, y, "p");
for (const [y, k] of [[18, 10], [23, 19]]) {
  icons.panel.rect(7, y, 18, 1, "d").rect(7, y + 1, 18, 1, "w").rect(k, y - 2, 3, 5, "k").rect(k + 1, y - 1, 1, 3, "p");
}

icons.caution = new Grid(32).shape(
  (x, y) => y >= 3 && y <= 29 && Math.abs(x - 16) <= (y - 3) * 0.55 + 0.6,
  "Y",
);
icons.caution.rect(15, 11, 2, 11, "k").rect(15, 24, 2, 2, "k");

icons.bomb = new Grid(32)
  .shape((x, y) => Math.hypot(x - 14, y - 19) <= 10.5, (x, y) => (Math.hypot(x - 10.5, y - 15.5) < 3 ? "G" : "k"))
  .rect(19, 8, 4, 4, "k").line(22, 8, 24, 5, "k").line(24, 5, 26, 4, "k")
  .dot(27, 2, "r").dot(29, 4, "r").dot(28, 5, "Y").dot(26, 2, "Y").dot(29, 2, "Y");

/* ---- 16 x 16 ---------------------------------------------------------- */

icons["folder-16"] = new Grid(16)
  .rect(2, 2, 4, 1, "k").dot(1, 3, "k").dot(6, 3, "k").rect(2, 3, 4, 1, "w")
  .rect(0, 4, 1, 10, "k").rect(15, 4, 1, 10, "k").rect(0, 13, 16, 1, "k").rect(7, 4, 8, 1, "k")
  .rect(1, 4, 6, 1, "p").rect(1, 5, 14, 8, "p").rect(1, 5, 13, 1, "w").rect(1, 5, 1, 7, "w")
  .rect(1, 12, 14, 1, "P").rect(14, 5, 1, 8, "P");

icons["document-16"] = new Grid(16)
  .rect(2, 0, 8, 1, "k").rect(2, 0, 1, 16, "k").rect(2, 15, 12, 1, "k").rect(13, 4, 1, 12, "k")
  .rect(3, 1, 6, 14, "w").rect(9, 4, 4, 11, "w")
  .rect(9, 1, 1, 3, "k").rect(10, 3, 3, 1, "k").dot(10, 1, "k").dot(11, 2, "k").dot(12, 3, "k").dot(10, 2, "g")
  .rect(4, 6, 7, 1, "G").rect(4, 8, 7, 1, "G").rect(4, 10, 5, 1, "G").rect(4, 12, 7, 1, "G");

icons["app-16"] = new Grid(16).shape(
  (x, y) => Math.abs(x - 8) + Math.abs(y - 8) <= 7.2,
  (x, y) => (x + y > 17 ? "P" : x + y < 13 ? "w" : "p"),
);
icons["app-16"].rect(6, 7, 4, 2, "q").rect(7, 6, 2, 4, "q");

icons["globe-16"] = new Grid(16).shape(
  (x, y) => Math.hypot(x - 8, y - 8) <= 7.2,
  (x, y) => (x === 7 || y === 7 || Math.abs(Math.hypot((x + 0.5 - 8) / 3.4, (y + 0.5 - 8) / 7.2) - 1) < 0.16 ? "u" : x + y < 9 ? "w" : "c"),
);

icons["panel-16"] = new Grid(16)
  .rect(1, 1, 14, 1, "k").rect(1, 1, 1, 14, "k").rect(14, 1, 1, 14, "k").rect(1, 14, 14, 1, "k")
  .rect(2, 2, 12, 12, "g").rect(2, 2, 12, 1, "w").rect(2, 2, 1, 12, "w")
  .rect(4, 4, 8, 3, "P").rect(4, 9, 8, 1, "d").rect(4, 11, 8, 1, "d").rect(6, 8, 2, 3, "k").rect(9, 10, 2, 3, "k");

// The menu-bar loaf. "wypiek" is Polish for a baked good, so it stands in for the Apple.
icons.bread = new Grid(16).shape(
  (x, y) => y < 13 && ((x - 8) / 7.3) ** 2 + ((y - 9.6) / 6.2) ** 2 <= 1,
  (x, y) => (y >= 11 ? "b" : ((x - 6) / 4) ** 2 + ((y - 5.5) / 1.6) ** 2 <= 1 ? "t" : "o"),
  "B",
);
for (const x of [4, 7, 10]) icons.bread.dot(x + 1, 6, "B").dot(x + 2, 7, "B").dot(x + 2, 8, "B").dot(x + 3, 9, "B");
icons["bread-32"] = icons.bread.scaled(2);

mkdirSync(OUT, { recursive: true });
for (const [name, grid] of Object.entries(icons)) writeFileSync(join(OUT, `${name}.svg`), grid.svg());
console.log(`wrote ${Object.keys(icons).length} icons to ${OUT}`);
