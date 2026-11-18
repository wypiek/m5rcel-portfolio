<?php
// Everything the page says about Marcel. Stars and dates here are fallbacks:
// when GitHub answers, its numbers replace them (see app/lib/site.php).

return [

    'profile' => [
        'name' => 'Marcel',
        'handle' => 'm5rcel',
        'place' => 'Warsaw, Poland',
        'timezone' => 'Europe/Warsaw',
        'github' => 'wypiek',
        'email_user' => 'contact',
        'email_domain' => 'm5rcel.com',
        'x' => 'm5rceI',
        'discord_id' => '318746314991206400',
        'home' => 'twitter.democrat',
        'organizations' => 5,
        // Used until GitHub has been reached once.
        'fallback_repos' => 148,
        'fallback_followers' => 46,
    ],

    'baking' => [
        ['meowcord', 'a self-hosted Discord clone with message encryption and federation, together with tiago.'],
        ['AXON', 'a statically typed language that compiles to C11.'],
        ['m3', 'a Wayland compositor in Rust with a Material 3 Expressive look.'],
    ],

    'pantry' => [
        'Building with' => 'Rust, Python, TypeScript, JavaScript, CSS, SQLite',
        'Know well' => 'HTML, Linux, Docker, Git',
        'Also shipped' => 'C, C#, Go, PHP, Swift, Kotlin',
        'Daily driver' => 'Linux, Arch-based',
    ],

    // Finder folders in the Projects window. "repo" is owner/name on GitHub.
    'projects' => [
        'Featured' => ['open' => true, 'items' => [
            ['name' => 'meowcord', 'repo' => 'meowcorded/meowcord', 'kind' => 'TypeScript', 'stars' => 78, 'date' => '2026-11-14',
             'desc' => 'Self-hosted Discord clone with feature parity, message encryption and very simple federation between instances. It serves a patched Discord web client, so people get the interface they know without a Discord account. TypeScript on Bun, AGPL-3.0. Built with tiago.'],
            ['name' => 'discord-oss', 'repo' => 'wypiek/discord-oss', 'kind' => 'TypeScript', 'stars' => 11, 'date' => '2026-11-17',
             'desc' => 'Open source recreation of Discord with Equicord built in.'],
            ['name' => '3itter', 'repo' => 'wypiek/3itter', 'kind' => 'JavaScript', 'stars' => 10, 'date' => '2026-11-14',
             'desc' => 'A Twitter clone styled with Material 3 Expressive.'],
            ['name' => 'AXON', 'repo' => 'theaxonproject/language', 'kind' => 'Language', 'stars' => 0, 'date' => '2026-10-28',
             'desc' => 'A statically typed, compiled, general-purpose language. The toolchain lowers AXON to C11 and builds native binaries, and ships an interpreter, REPL, formatter, linter, build system and language server. Generics, traits, pattern matching, Result and Option, pipelines. Version 1.2.0, MIT.'],
        ]],

        'Systems & languages' => ['open' => true, 'items' => [
            ['name' => 'm3', 'repo' => 'wypiek/m3', 'kind' => 'Rust', 'stars' => 0, 'date' => '2026-11-11',
             'desc' => 'A custom Wayland compositor with a Material 3 Expressive theme.'],
            ['name' => 'bwfall', 'repo' => 'wypiek/bwfall', 'kind' => 'C', 'stars' => 6, 'date' => '2026-03-02',
             'desc' => 'Binary waterfall visualizer for Linux, with Arch and Alpine support.'],
            ['name' => 'WinKERNEL', 'repo' => 'wypiek/WinKERNEL', 'kind' => 'C', 'stars' => 1, 'date' => '2026-06-20',
             'desc' => 'A from-scratch kernel modelled on Windows 11.'],
            ['name' => 'PhoenixOS', 'repo' => 'wypiek/PhoenixOS', 'kind' => 'C', 'stars' => 1, 'date' => '2026-03-18',
             'desc' => 'An operating system that looks like Windows 1.0 and 3.0 but feels like Linux.'],
            ['name' => 'Fit', 'repo' => 'wypiek/Fit', 'kind' => 'C', 'stars' => 1, 'date' => '2026-03-15',
             'desc' => "Git's object model, rebuilt as a backup and storage engine."],
            ['name' => 'vorthex', 'repo' => 'wypiek/vorthex', 'kind' => 'Python', 'stars' => 0, 'date' => '2026-04-09',
             'desc' => 'An esoteric language with no keywords and no variables.'],
            ['name' => 'jfl-distribution', 'repo' => 'wypiek/jfl-distribution', 'kind' => 'Shell', 'stars' => 0, 'date' => '2025-11-27',
             'desc' => 'An Arch-based Linux distribution.'],
            ['name' => 'm5rcode', 'repo' => 'wypiek/m5rcode', 'kind' => 'Python', 'stars' => 0, 'date' => '2025-10-17',
             'desc' => 'My first programming language, with its own shell and interpreter. Packaged on the AUR, with an Ubuntu port and an EFI build that boots it as an OS.'],
        ]],

        'Social & chat' => ['open' => false, 'items' => [
            ['name' => 'Chirp', 'repo' => 'wypiek/Chirp', 'kind' => 'Python', 'stars' => 1, 'date' => '2026-07-18',
             'desc' => 'A Twitter-like social platform styled like an Android app.'],
            ['name' => 'Linkora', 'repo' => 'wypiek/Linkora', 'kind' => 'C#', 'stars' => 1, 'date' => '2026-10-28',
             'desc' => 'Windows Live Messenger, ported to Linux and macOS.'],
            ['name' => 'twiq', 'repo' => 'wypiek/twiq', 'kind' => 'JavaScript', 'stars' => 0, 'date' => '2026-09-13',
             'desc' => 'A recreation of Twitter as it was in 2014, with a few ideas of its own.'],
            ['name' => 'twitkey', 'repo' => 'wypiek/twitkey', 'kind' => 'PHP', 'stars' => 0, 'date' => '2026-05-26',
             'desc' => 'A recreation of 2009-era Twitter.'],
            ['name' => 'horizon', 'repo' => 'wypiek/horizon', 'kind' => 'TypeScript', 'stars' => 0, 'date' => '2026-08-13',
             'desc' => 'A clone of X.'],
            ['name' => 'wistlet', 'repo' => 'wypiek/wistlet', 'kind' => 'C#', 'stars' => 0, 'date' => '2026-10-27',
             'desc' => 'An open source recreation of cohost!'],
            ['name' => 'vxfedi', 'repo' => 'wypiek/vxfedi', 'kind' => 'JavaScript', 'stars' => 0, 'date' => '2026-06-21',
             'desc' => 'vxtwitter-style link embeds for the fediverse.'],
            ['name' => 'nexus', 'repo' => 'wypiek/nexus', 'kind' => 'TypeScript', 'stars' => 0, 'date' => '2026-05-25',
             'desc' => 'A self-hosted, open source communication platform.'],
        ]],

        'Web' => ['open' => false, 'items' => [
            ['name' => 'keyhaven', 'repo' => 'wypiek/keyhaven', 'kind' => 'JavaScript', 'stars' => 1, 'date' => '2026-11-04',
             'desc' => 'A password manager with an optional self-hosted sync server.'],
            ['name' => 'mafh.net', 'repo' => 'wypiek/mafh.net', 'kind' => 'C#', 'stars' => 1, 'date' => '2026-07-02',
             'desc' => 'A file hoster written in .NET.'],
            ['name' => 'm3blog', 'repo' => 'wypiek/m3blog', 'kind' => 'Web app', 'stars' => 0, 'date' => '2026-11-05',
             'desc' => 'An easy-to-maintain blog engine with a Material 3 interface.'],
            ['name' => '7blog', 'repo' => 'wypiek/7blog', 'kind' => 'C#', 'stars' => 0, 'date' => '2026-10-25',
             'desc' => 'A blog styled after Windows 7 with 7.css.'],
            ['name' => 'discord.lifestyle', 'repo' => 'wypiek/discord.lifestyle', 'kind' => 'TypeScript', 'stars' => 0, 'date' => '2026-10-22',
             'desc' => 'A clicker game. It also lives at discord.lifestyle.'],
            ['name' => 'opentube', 'repo' => 'wypiek/opentube', 'kind' => 'TypeScript', 'stars' => 0, 'date' => '2026-06-07',
             'desc' => "An open source YouTube clone that looks like it's 2006."],
            ['name' => 'rbxl.eu', 'repo' => 'wypiek/rbxl.eu', 'kind' => 'JavaScript', 'stars' => 0, 'date' => '2026-03-10',
             'desc' => 'A bio hosting platform styled with Material You.'],
        ]],

        'Tools & apps' => ['open' => false, 'items' => [
            ['name' => 'wingit', 'repo' => 'wypiek/wingit', 'kind' => 'PowerShell', 'stars' => 2, 'date' => '2026-07-18',
             'desc' => 'A package manager for Windows that installs straight from GitHub releases.'],
            ['name' => 'TermiTune', 'repo' => 'wypiek/TermiTune', 'kind' => 'Rust', 'stars' => 2, 'date' => '2026-02-27',
             'desc' => 'A terminal music player for your local library.'],
            ['name' => 'mpct', 'repo' => 'wypiek/mpct', 'kind' => 'Rust', 'stars' => 1, 'date' => '2026-07-17',
             'desc' => 'One cross-platform binary for hashing, encoding, config formats, TOTP, QR codes and more.'],
            ['name' => 'spatial', 'repo' => 'wypiek/spatial', 'kind' => 'Swift', 'stars' => 1, 'date' => '2026-04-30',
             'desc' => 'A filesystem visualizer that draws your folders as a node graph.'],
            ['name' => 'rewind', 'repo' => 'wypiek/rewind', 'kind' => 'Rust', 'stars' => 0, 'date' => '2026-10-30',
             'desc' => 'An undo button for a whole project folder.'],
            ['name' => 'brewski', 'repo' => 'wypiek/brewski', 'kind' => 'Rust', 'stars' => 0, 'date' => '2026-05-11',
             'desc' => 'A keyboard-driven terminal UI for Homebrew on macOS.'],
            ['name' => '7get', 'repo' => 'wypiek/7get', 'kind' => 'C++', 'stars' => 0, 'date' => '2026-10-28',
             'desc' => 'A native package manager for Windows 7.'],
            ['name' => '7monitor', 'repo' => 'wypiek/7monitor', 'kind' => 'C++', 'stars' => 0, 'date' => '2026-10-28',
             'desc' => 'A native system monitor for Windows 7.'],
            ['name' => 'loop', 'repo' => 'wypiek/loop', 'kind' => 'Swift', 'stars' => 0, 'date' => '2026-06-21',
             'desc' => 'A native macOS music player with Discord Rich Presence.'],
            ['name' => 'AURA', 'repo' => 'wypiek/AURA', 'kind' => 'Kotlin', 'stars' => 0, 'date' => '2026-05-20',
             'desc' => 'An Android home dashboard in Material You.'],
            ['name' => 'mspace', 'repo' => 'wypiek/mspace', 'kind' => 'C++', 'stars' => 0, 'date' => '2026-01-01',
             'desc' => 'A solar system simulator for Arch-based Linux.'],
        ]],

        'Bots' => ['open' => false, 'items' => [
            ['name' => 'phpelefant', 'repo' => 'wypiek/phpelefant', 'kind' => 'Python', 'stars' => 1, 'date' => '2026-05-21',
             'desc' => 'A Telegram bot starring the PHP elephant.'],
            ['name' => 'nightbite', 'repo' => 'wypiek/nightbite', 'kind' => 'JavaScript', 'stars' => 0, 'date' => '2026-07-03',
             'desc' => 'A Discord moderation bot.'],
            ['name' => 'anthropicutility', 'repo' => 'wypiek/anthropicutility', 'kind' => 'Go', 'stars' => 0, 'date' => '2026-08-09',
             'desc' => 'A Discord bot that posts Anthropic news from an RSS feed.'],
            ['name' => 'rsscord', 'repo' => 'wypiek/rsscord', 'kind' => 'Go', 'stars' => 0, 'date' => '2026-08-10',
             'desc' => 'A minimal Discord bot that posts RSS news.'],
            ['name' => 'bloxscouts', 'repo' => 'wypiek/bloxscouts', 'kind' => 'Go', 'stars' => 0, 'date' => '2026-08-14',
             'desc' => 'A Discord bot that sends games to studio servers.'],
            ['name' => 'dockerize-discord', 'repo' => 'wypiek/dockerize-discord', 'kind' => 'Python', 'stars' => 0, 'date' => '2026-06-17',
             'desc' => 'A Discord bot that gives each server its own container-like environment.'],
            ['name' => 'fmcord', 'repo' => 'wypiek/fmcord', 'kind' => 'TypeScript', 'stars' => 0, 'date' => '2026-06-11',
             'desc' => 'A lightweight Discord music bot.'],
            ['name' => 'Pawsino', 'repo' => 'wypiek/Pawsino', 'kind' => 'Python', 'stars' => 0, 'date' => '2026-03-14',
             'desc' => 'An open source, cat-themed casino bot for Discord.'],
        ]],
    ],

    // Blog: posts come from the RSS feed. These label the Kind column by post
    // slug, and the list below is shown if the feed has never been reachable.
    'blog_kinds' => [
        'fuck-windows' => 'rant',
        'i-want-to-draw-a-cat-for-you-is-probably-one-of-the-funniest-companies-i-ve-ever-seen' => 'story',
        'my-opinion-on-pond-linux' => 'review',
        'hello-world' => 'first post',
    ],
    'blog_fallback' => [
        ['title' => 'FUCK WINDOWS!', 'link' => 'https://twitter.democrat/blog/post/fuck-windows', 'date' => '2026-11-15'],
        ['title' => '"i want to draw a cat for you" is probably one of the funniest companies i\'ve ever seen', 'link' => 'https://twitter.democrat/blog/post/i-want-to-draw-a-cat-for-you-is-probably-one-of-the-funniest-companies-i-ve-ever-seen', 'date' => '2026-11-05'],
        ['title' => 'my opinion on Pond Linux', 'link' => 'https://twitter.democrat/blog/post/my-opinion-on-pond-linux', 'date' => '2026-11-04'],
        ['title' => 'hello, world', 'link' => 'https://twitter.democrat/blog/post/hello-world', 'date' => '2026-11-03'],
    ],

    // Links window. Shown as aliases (italic names), like the Finder did.
    'links' => [
        ['GitHub', 'https://github.com/wypiek', 'globe'],
        ['X @m5rceI', 'https://x.com/m5rceI', 'globe'],
        ['Discord', 'https://discord.com/users/318746314991206400', 'globe'],
        ['Steam', 'https://steamcommunity.com/id/m5rcel', 'globe'],
        ['E-mail', 'mail', 'mail'],
        ['Blog RSS', 'https://twitter.democrat/blog/rss.xml', 'document'],
        ['Neocities', 'https://m5rcel.neocities.org/', 'globe'],
        ['sire.lol', 'https://sire.lol/m5rcel', 'globe'],
    ],

    // Domains window: [name, what it is, sort order].
    'domains' => [
        ['twitter.democrat', 'main home', 1],
        ['m5rcel.com', 'homepage', 2],
        ['m5rcel.neocities.org', 'shrine', 3],
        ['m5rcel.nekoweb.org', 'other home', 4],
        ['m5rcel.みんな', 'other home', 4],
        ['discord.lifestyle', 'clicker game', 5],
        ['noli.click', 'for Noli', 6],
    ],
];
