<?php
// Latest posts from the blog's RSS feed. Cached for 15 minutes.

declare(strict_types=1);

function blog_fetch(): ?array
{
    $xml = http_get(config('blog')['rss']);
    if ($xml === null) {
        return null;
    }

    $prev = libxml_use_internal_errors(true);
    $rss = simplexml_load_string($xml, SimpleXMLElement::class, LIBXML_NONET | LIBXML_NOCDATA);
    libxml_use_internal_errors($prev);
    if (!$rss || !isset($rss->channel->item)) {
        return null;
    }

    $posts = [];
    foreach ($rss->channel->item as $item) {
        $link = safe_url(trim((string) $item->link));
        $title = trim((string) $item->title);
        $time = strtotime((string) $item->pubDate);
        if ($link === null || $title === '') {
            continue;
        }
        $posts[] = ['title' => $title, 'link' => $link, 'date' => $time ? gmdate('Y-m-d', $time) : null];
    }
    return $posts ?: null;
}

function blog_posts(bool $force = false): ?array
{
    return cache_remember('blog', (int) config('blog')['ttl'], 'blog_fetch', $force);
}
