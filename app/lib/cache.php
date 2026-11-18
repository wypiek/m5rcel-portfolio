<?php
// A tiny file cache plus an HTTP GET that never takes the page down with it.

declare(strict_types=1);

/**
 * Returns the cached value for $key, refreshing it with $fetch once it is
 * older than $ttl seconds.
 *
 * - Only one request refreshes at a time; everyone else gets the old value.
 * - If $fetch fails (returns null), the old value is kept and the next try is
 *   put off for five minutes, so a dead upstream doesn't slow every page view.
 * - Returns null only if nothing has ever been fetched successfully.
 */
function cache_remember(string $key, int $ttl, callable $fetch, bool $force = false): mixed
{
    $dir = config('cache_dir');
    $file = "$dir/$key.json";
    $entry = is_file($file) ? json_decode((string) file_get_contents($file), true) : null;
    $fresh = is_array($entry) && time() - ($entry['at'] ?? 0) < $ttl;

    if ($fresh && !$force) {
        return $entry['value'] ?? null;
    }
    if (!is_dir($dir) && !@mkdir($dir, 0775, true)) {
        return $entry['value'] ?? null;
    }

    $lock = @fopen("$file.lock", 'c');
    if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
        return $entry['value'] ?? null; // someone else is refreshing
    }

    try {
        $value = $fetch();
        if ($value !== null) {
            $entry = ['at' => time(), 'value' => $value];
        } else {
            $retry = 300;
            $entry = ['at' => time() - $ttl + $retry, 'value' => $entry['value'] ?? null];
        }
        $tmp = "$file." . getmypid() . '.tmp';
        if (file_put_contents($tmp, json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) !== false) {
            rename($tmp, $file);
        }
    } finally {
        flock($lock, LOCK_UN);
        fclose($lock);
    }

    return $entry['value'] ?? null;
}

/** GET a URL. Returns the body on a 200, otherwise null. */
function http_get(string $url, array $headers = []): ?string
{
    $timeout = (int) config('http_timeout');
    $headers[] = 'User-Agent: m5rcel-site (+https://github.com/wypiek/m5rcel-portfolio)';

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
        ]);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        return $status === 200 && is_string($body) ? $body : null;
    }

    $ctx = stream_context_create(['http' => [
        'method' => 'GET',
        'header' => implode("\r\n", $headers),
        'timeout' => $timeout,
        'ignore_errors' => true,
    ]]);
    $body = @file_get_contents($url, false, $ctx);
    $status = isset($http_response_header[0]) && preg_match('~\s(\d{3})\s~', $http_response_header[0], $m) ? (int) $m[1] : 0;
    return $status === 200 && is_string($body) ? $body : null;
}

function http_json(string $url, array $headers = []): mixed
{
    $body = http_get($url, $headers);
    return $body === null ? null : json_decode($body, true);
}
