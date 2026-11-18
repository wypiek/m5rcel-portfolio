<?php
// Puts the page together: the static data with live GitHub and blog numbers
// laid over it wherever they're available.

declare(strict_types=1);

function load_site(): array
{
    $data = require dirname(__DIR__) . '/data.php';
    $gh = github_stats();
    $live = $gh['repos'] ?? [];

    // Projects: swap in live stars and dates.
    $count = 0;
    $fallbackStars = 0;
    $all = [];
    foreach ($data['projects'] as $folder => &$group) {
        foreach ($group['items'] as &$p) {
            $fallbackStars += $p['stars'];
            if ($r = $live[strtolower($p['repo'])] ?? null) {
                $p['stars'] = $r['stars'];
                $p['date'] = $r['date'] ?? $p['date'];
            }
            $all[] = $p;
            $count++;
        }
        unset($p);
    }
    unset($group);

    // Most starred, for About This Marcel.
    usort($all, fn ($a, $b) => $b['stars'] <=> $a['stars'] ?: strcmp($b['date'] ?? '', $a['date'] ?? ''));
    $top = array_slice($all, 0, 6);

    // Blog: the feed if we've ever reached it, otherwise the built-in list.
    $posts = blog_posts() ?? $data['blog_fallback'];
    foreach ($posts as &$post) {
        $slug = basename((string) parse_url($post['link'], PHP_URL_PATH));
        $post['kind'] = $data['blog_kinds'][$slug] ?? 'post';
    }
    unset($post);

    $profile = $data['profile'];

    return $data + [
        'stats' => [
            'live' => $gh !== null,
            'repos' => $gh['repos_count'] ?? $profile['fallback_repos'],
            'followers' => $gh['followers'] ?? $profile['fallback_followers'],
            'stars' => $gh['stars_earned'] ?? $fallbackStars,
            'orgs' => $profile['organizations'],
            'items' => $count + count($data['projects']),
        ],
        'top' => $top,
        'posts' => $posts,
    ];
}
