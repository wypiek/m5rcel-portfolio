# m5rcel

Personal site for Marcel (m5rcel), built as a System 7 desktop.

Plain HTML, one stylesheet and a few native ES modules. There is no framework,
no bundler and no build step: what's in `public/` is exactly what gets served.

```
public/
  index.html            all the content lives here, readable without JavaScript
  404.html              "Sorry, a system error occurred."
  _headers              Cloudflare Pages response headers
  assets/
    css/system7.css
    js/main.mjs         wires everything together
    js/windows.mjs      window manager (drag, grow, zoom, zoom rectangles)
    js/menus.mjs        menu bar
    js/finder.mjs       list views, sorting, Get Info
    js/desktop.mjs      icons, clocks, desktop patterns, alerts
    icons/*.svg         pixel icons (generated, see below)
    fonts/*.woff2
tools/icons.mjs         draws the icons
wrangler.toml
```

## Run it locally

ES modules don't load from `file://`, so use any static server:

```bash
python3 -m http.server -d public 8000
```

## Deploy to Cloudflare Pages

From the dashboard: Workers & Pages → Create → Pages → connect the Git repo.

- Framework preset: None
- Build command: leave empty
- Build output directory: `public`

Or from the command line:

```bash
npx wrangler pages deploy public --project-name m5rcel
```

Then add your domain under the project's Custom domains tab.

## Editing

- **Projects**: each project is one `<a class="row item">` in `index.html`. The `data-*`
  attributes drive sorting and the Get Info window. Remember to update the item
  count in the window's `finder-info` line.
- **Icons**: edit `tools/icons.mjs` and run `node tools/icons.mjs`.
- **Desktop patterns**: the colour pairs are in `system7.css` (`:root[data-pattern=…]`)
  and mirrored in `PATTERNS` in `desktop.mjs` for the control panel preview.

## Credits

The fonts are ChiKareGo2 and FindersKeepers by Giles Booth, free bitmap-style
recreations of Chicago and Geneva, as distributed with
[system.css](https://github.com/sakofchit/system.css) (MIT). Everything else
was drawn and written for this site.
