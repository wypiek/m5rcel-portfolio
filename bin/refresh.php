<?php
// Refreshes the GitHub and blog caches. The page refreshes them on its own
// when they go stale, so this is optional; run it from cron if you'd rather
// visitors never wait on GitHub:
//
//   */30 * * * *  www-data  php /var/www/m5rcel/bin/refresh.php >/dev/null
//
// Run it as the same user PHP runs as, so the cache files stay writable.

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require dirname(__DIR__) . '/app/bootstrap.php';

$gh = github_stats(force: true);
$posts = blog_posts(force: true);

printf(
    "github: %s\nblog:   %s\n",
    $gh ? sprintf('%d repos, %d followers, %d stars earned', $gh['repos_count'], $gh['followers'], $gh['stars_earned']) : 'unreachable, keeping the old cache',
    $posts ? sprintf('%d posts', count($posts)) : 'unreachable, keeping the old cache',
);
exit($gh && $posts ? 0 : 1);
