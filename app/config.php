<?php
// Site settings. Anything secret comes from the environment, never from here.

return [
    // Where cached GitHub and RSS responses are kept. Must be writable by PHP.
    'cache_dir' => dirname(__DIR__) . '/cache',

    'github' => [
        'user' => 'wypiek',
        'api' => getenv('GITHUB_API') ?: 'https://api.github.com/',
        // Repos that live in organisations but belong on the page.
        'extra_repos' => ['meowcorded/meowcord', 'theaxonproject/language'],
        // Optional. Without a token GitHub allows 60 requests an hour, which is
        // plenty: one refresh costs about 5 requests.
        'token' => getenv('GITHUB_TOKEN') ?: null,
        'ttl' => 3600,
    ],

    'blog' => [
        'rss' => 'https://twitter.democrat/blog/rss.xml',
        'ttl' => 900,
    ],

    // How long to wait for GitHub or the blog before giving up and using the cache.
    'http_timeout' => 4,
];
