<?php
// Live numbers from GitHub: repo count, followers, and per-repo stars and
// last push dates. Cached for an hour; about five API requests per refresh.

declare(strict_types=1);

function github_api(string $path): mixed
{
    $headers = ['Accept: application/vnd.github+json', 'X-GitHub-Api-Version: 2022-11-28'];
    if ($token = config('github')['token']) {
        $headers[] = "Authorization: Bearer $token";
    }
    return http_json(rtrim(config('github')['api'], '/') . '/' . ltrim($path, '/'), $headers);
}

/** Fetches everything at once. Returns null if any request fails. */
function github_fetch(): ?array
{
    $cfg = config('github');
    $user = github_api('users/' . rawurlencode($cfg['user']));
    if (!is_array($user) || !isset($user['public_repos'])) {
        return null;
    }

    $repos = [];
    for ($page = 1; $page <= 5; $page++) {
        $list = github_api(sprintf('users/%s/repos?type=owner&per_page=100&page=%d', rawurlencode($cfg['user']), $page));
        if (!is_array($list)) {
            return null;
        }
        array_push($repos, ...$list);
        if (count($list) < 100) {
            break;
        }
    }

    // Stars earned: everything on your own non-fork repos, plus the org repos.
    $earned = 0;
    $byName = [];
    foreach ($repos as $r) {
        if (!is_array($r) || empty($r['full_name'])) {
            continue;
        }
        $byName[strtolower($r['full_name'])] = github_slim($r);
        if (empty($r['fork'])) {
            $earned += (int) ($r['stargazers_count'] ?? 0);
        }
    }
    foreach ($cfg['extra_repos'] as $full) {
        $r = github_api('repos/' . $full);
        if (is_array($r) && !empty($r['full_name'])) {
            $byName[strtolower($r['full_name'])] = github_slim($r);
            $earned += (int) ($r['stargazers_count'] ?? 0);
        }
    }

    return [
        'repos_count' => (int) $user['public_repos'],
        'followers' => (int) ($user['followers'] ?? 0),
        'stars_earned' => $earned,
        'repos' => $byName,
    ];
}

function github_slim(array $r): array
{
    return [
        'stars' => (int) ($r['stargazers_count'] ?? 0),
        'date' => isset($r['pushed_at']) ? substr((string) $r['pushed_at'], 0, 10) : null,
    ];
}

function github_stats(bool $force = false): ?array
{
    return cache_remember('github', (int) config('github')['ttl'], 'github_fetch', $force);
}
