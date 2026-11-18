<?php
declare(strict_types=1);

date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

function config(?string $key = null): mixed
{
    static $config = null;
    $config ??= require __DIR__ . '/config.php';
    return $key === null ? $config : ($config[$key] ?? null);
}

require __DIR__ . '/lib/helpers.php';
require __DIR__ . '/lib/cache.php';
require __DIR__ . '/lib/github.php';
require __DIR__ . '/lib/blog.php';
require __DIR__ . '/lib/site.php';

function view(string $name, array $site): void
{
    require __DIR__ . "/views/$name.php";
}

function send_headers(int $status = 200): void
{
    if (headers_sent()) {
        return;
    }
    http_response_code($status);
    header('Content-Type: text/html; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('X-Frame-Options: SAMEORIGIN');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
}
