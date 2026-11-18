<?php
// Small template helpers.

declare(strict_types=1);

/** Escape for HTML text and attribute values. Use it on everything. */
function e(string|int|float|null $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** "2026-11-14" -> "Nov 14, 2026", or an en dash when unknown. */
function fmt_date(?string $ymd): string
{
    if (!$ymd || !($t = strtotime($ymd))) {
        return '–';
    }
    return date('M j, Y', $t);
}

function items(int $n): string
{
    return $n === 1 ? '1 item' : "$n items";
}

function stars(int $n): string
{
    return $n === 1 ? '1 star' : "$n stars";
}

function icon(string $name): string
{
    return "assets/icons/$name.svg";
}

/** Only ever print http(s) links that came from outside (RSS, GitHub). */
function safe_url(?string $url): ?string
{
    return is_string($url) && preg_match('~^https?://[^\s"<>]+$~iu', $url) ? $url : null;
}

/* ---- Window chrome ----------------------------------------------------- */

/** @var list<array> */
$GLOBALS['__windows'] = [];

/**
 * Opens a System 7 window. Pair every call with window_end().
 *
 * Options: open (shown on first load), hidden (hidden even without JS),
 * scroll (scroll bars + grow box), long (no height cap on phones),
 * zoom (zoom box; defaults to scroll), info (three strings for the Finder line).
 */
function window_start(string $id, string $title, array $o = []): void
{
    $o += ['open' => false, 'hidden' => false, 'scroll' => false, 'long' => false, 'info' => null];
    $o['zoom'] ??= $o['scroll'];
    $GLOBALS['__windows'][] = $o;

    $class = 'window' . ($o['scroll'] ? ' has-scroll' : '') . ($o['long'] ? ' is-long' : '');
    $t = "t-$id";
    echo '<section class="', $class, '" id="', e($id), '"',
        $o['open'] ? ' data-default="open"' : '',
        $o['hidden'] ? ' hidden' : '',
        ' aria-labelledby="', e($t), '">', "\n";
    echo '<header class="titlebar">',
        '<button class="box close" type="button" aria-label="Close ', e($title), '"></button>',
        '<h2 class="title" id="', e($t), '">', e($title), '</h2>',
        $o['zoom'] ? '<button class="box zoom" type="button" aria-label="Zoom ' . e($title) . '"></button>' : '',
        "</header>\n";
    if ($o['info']) {
        echo '<div class="finder-info">';
        foreach ($o['info'] as $part) {
            echo '<span>', $part === '' ? '&nbsp;' : e($part), '</span>';
        }
        echo "</div>\n";
    }
    echo '<div class="window-body">', "\n";
}

function window_end(): void
{
    $o = array_pop($GLOBALS['__windows']);
    echo "</div>\n";
    if ($o['scroll'] ?? false) {
        echo '<span class="grow" aria-hidden="true"></span>', "\n";
    }
    echo "</section>\n";
}

/** A desktop or icon-view icon that opens one of our windows. */
function icon_opens(string $window, string $img, string $label): string
{
    return '<button class="icon" type="button" data-opens="' . e($window) . '">'
        . '<span class="glyph"><img src="' . e(icon($img)) . '" width="32" height="32" alt=""></span>'
        . '<span class="label">' . e($label) . '</span></button>';
}

/** An icon that is a link somewhere else. */
function icon_link(string $href, string $img, string $label, bool $alias = false, string $extra = ''): string
{
    $external = str_starts_with($href, 'http') ? ' target="_blank" rel="noopener"' : '';
    return '<a class="icon' . ($alias ? ' is-alias' : '') . '" href="' . e($href) . '"' . $external . $extra . '>'
        . '<span class="glyph"><img src="' . e(icon($img)) . '" width="32" height="32" alt=""></span>'
        . '<span class="label">' . e($label) . '</span></a>';
}

function small_icon(string $name): string
{
    return '<img src="' . e(icon($name)) . '" width="16" height="16" alt="">';
}
