# m5rcel

Personal site for Marcel (m5rcel), built as a System 7 desktop.

Plain PHP 8 templates, one stylesheet and a few native ES modules. No framework,
no Composer, no build step. The server pulls live numbers from GitHub and the
latest posts from the blog's RSS feed, and caches both in plain JSON files.

```
public/               web root, the only folder the web server exposes
  index.php           the page
  404.php             "Sorry, a system error occurred."
  .htaccess           Apache settings (nginx uses deploy/nginx.conf)
  assets/             css, js, icons, fonts
app/
  config.php          cache times, GitHub user, RSS feed
  data.php            everything the page says: profile, projects, links, domains
  lib/                cache + HTTP, GitHub, RSS, page assembly, helpers
  views/              one template per window
bin/refresh.php       refreshes the caches (optional cron job)
cache/                GitHub and RSS cache files, must be writable by PHP
deploy/               nginx and Apache configs
tools/icons.php       draws the pixel icons
Dockerfile, docker-compose.yml
```

## Requirements

PHP 8.1 or newer with the `curl` (or `allow_url_fopen`), `json`, `mbstring`
and `simplexml` extensions. On Debian or Ubuntu:

```bash
sudo apt install php-fpm php-curl php-xml php-mbstring
```

## Run it locally

```bash
php -S localhost:8000 -t public
```

## Put it on a VPS

### nginx + PHP-FPM

```bash
sudo git clone https://github.com/wypiek/m5rcel-portfolio /var/www/m5rcel
sudo chown -R www-data:www-data /var/www/m5rcel/cache
sudo cp /var/www/m5rcel/deploy/nginx.conf /etc/nginx/sites-available/m5rcel
```

Edit `server_name` and the `fastcgi_pass` socket (check `ls /run/php/`) in that
file, then:

```bash
sudo ln -s /etc/nginx/sites-available/m5rcel /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
sudo certbot --nginx -d example.com
```

### Apache

Same clone and `chown`, then use `deploy/apache.conf`:

```bash
sudo cp /var/www/m5rcel/deploy/apache.conf /etc/apache2/sites-available/m5rcel.conf
sudo a2enmod headers && sudo a2ensite m5rcel && sudo systemctl reload apache2
sudo certbot --apache -d example.com
```

### Docker

```bash
docker compose up -d --build
```

It listens on `127.0.0.1:8080`, so put nginx, Caddy or Traefik in front of it.
(The Dockerfile hasn't been test-built yet.)

### Updating

```bash
cd /var/www/m5rcel && sudo -u www-data git pull
```

There's nothing to build or restart: PHP picks up the new files, and the
stylesheet and script URLs carry their modification time, so browsers fetch the
new versions.

## GitHub numbers and the blog list

Stars, last-push dates, repo count and followers come from the GitHub API and
are cached for an hour. The blog list comes from
`https://twitter.democrat/blog/rss.xml` and is cached for 15 minutes. If either
one is unreachable, the page keeps showing the last good copy, or the numbers
in `app/data.php` if it has never fetched one. A failed fetch waits 5 minutes
before trying again, so a GitHub outage never slows the page down.

The page refreshes the caches on its own when they go stale. If you'd rather
no visitor ever waits on GitHub, refresh them from cron instead:

```
*/30 * * * *  www-data  php /var/www/m5rcel/bin/refresh.php >/dev/null
```

Without a token, GitHub allows 60 requests an hour, and a refresh uses about 5.
To raise the limit, set `GITHUB_TOKEN` (a fine-grained token with no
permissions is enough) in the nginx `fastcgi_param`, the Apache `SetEnv` or the
compose environment.

## Editing

- **Projects, links, domains, the Read Me lists:** `app/data.php`. Item counts
  and the About This Marcel chart work themselves out.
- **Wording on the page:** `app/views/`.
- **Icons:** edit `tools/icons.php`, then run `php tools/icons.php`.
- **Desktop patterns:** the colour pairs are in `system7.css`
  (`:root[data-pattern=…]`) and mirrored in `PATTERNS` in `assets/js/desktop.js`
  for the control panel preview.

## Credits

The fonts are ChiKareGo2 and FindersKeepers by Giles Booth, free bitmap-style
recreations of Chicago and Geneva, as distributed with
[system.css](https://github.com/sakofchit/system.css) (MIT). Everything else
was drawn and written for this site.
