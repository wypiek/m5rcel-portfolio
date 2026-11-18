<?php
// The smaller Finder windows: the disk, Blog, Links, Domains and Trash.
/** @var array $site */
$p = $site['profile'];

/* ---- m5rcel HD ---- */
window_start('win-hd', $p['handle'] . ' HD', ['scroll' => true, 'info' => ['7 items', $site['stats']['repos'] . ' repos on disk', 'plenty available']]);
?>
<ul class="icon-view">
  <li><?= icon_opens('win-readme', 'readme', 'Read Me') ?></li>
  <li><?= icon_opens('win-projects', 'folder', 'Projects') ?></li>
  <li><?= icon_opens('win-blog', 'folder', 'Blog') ?></li>
  <li><?= icon_opens('win-links', 'folder', 'Links') ?></li>
  <li><?= icon_opens('win-domains', 'folder', 'Domains') ?></li>
  <li><?= icon_opens('win-about', 'app', 'About This ' . $p['name']) ?></li>
  <li><?= icon_opens('win-patterns', 'panel', 'Desktop Patterns') ?></li>
</ul>
<?php window_end();

/* ---- Blog ---- */
window_start('win-blog', 'Blog', ['scroll' => true, 'info' => [items(count($site['posts'])), 'handwritten PHP', $p['home'] . '/blog']]);
?>
<div class="list is-narrow" data-sort="date" aria-label="Blog posts">
  <div class="list-head">
    <button type="button" data-sort="name">Name</button>
    <button type="button" data-sort="kind">Kind</button>
    <button type="button" data-sort="date">Date</button>
  </div>
<?php foreach ($site['posts'] as $post): ?>
  <a class="row item" href="<?= e($post['link']) ?>" target="_blank" rel="noopener" data-name="<?= e($post['title']) ?>" data-kind="<?= e($post['kind']) ?>" data-date="<?= e($post['date']) ?>">
    <span class="c-name"><?= small_icon('document-16') ?><span class="label"><?= e($post['title']) ?></span></span><span><?= e($post['kind']) ?></span><span><?= e(fmt_date($post['date'])) ?></span>
  </a>
<?php endforeach ?>
</div>
<?php window_end();

/* ---- Links ---- */
window_start('win-links', 'Links', ['scroll' => true, 'info' => [items(count($site['links'])), 'all aliases', '']]);
?>
<ul class="icon-view">
<?php foreach ($site['links'] as [$label, $href, $img]): ?>
  <li><?= $href === 'mail'
        ? icon_link('#', $img, $label, true, ' data-mail="' . e($p['email_user']) . '" data-domain="' . e($p['email_domain']) . '"')
        : icon_link($href, $img, $label, true) ?></li>
<?php endforeach ?>
</ul>
<?php window_end();

/* ---- Domains ---- */
window_start('win-domains', 'Domains', ['scroll' => true, 'info' => [items(count($site['domains'])), '', 'home is ' . $p['home']]]);
?>
<div class="list is-narrow is-two" data-sort="none" aria-label="Domains">
  <div class="list-head">
    <button type="button" data-sort="name">Name</button>
    <button type="button" data-sort="kind">Kind</button>
  </div>
<?php foreach ($site['domains'] as [$domain, $kind, $order]): ?>
  <a class="row item" href="https://<?= e($domain) ?>/" target="_blank" rel="noopener" data-name="<?= e($domain) ?>" data-kind="<?= (int) $order ?> <?= e($kind) ?>">
    <span class="c-name"><?= small_icon('globe-16') ?><span class="label"><?= e($domain) ?></span></span><span><?= e($kind) ?></span>
  </a>
<?php endforeach ?>
</div>
<?php window_end();

/* ---- Trash ---- */
window_start('win-trash', 'Trash', ['scroll' => true, 'info' => ['1 item', '', '20 GB in Trash']]);
?>
<ul class="icon-view">
  <li><?= icon_link('https://' . $p['home'] . '/blog/post/fuck-windows', 'app', 'Windows 11') ?></li>
</ul>
<?php window_end();
