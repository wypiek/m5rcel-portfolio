<?php
/** @var array $site */
$apple = [
    ['win-blog', 'folder-16', 'Blog'],
    ['win-patterns', 'panel-16', 'Desktop Patterns'],
    ['win-domains', 'folder-16', 'Domains'],
    ['win-links', 'folder-16', 'Links'],
    ['win-projects', 'folder-16', 'Projects'],
    ['win-readme', 'document-16', 'Read Me'],
];
$item = fn (string $label, string $attrs = '') => '<li><button role="menuitem" type="button" ' . $attrs . '>' . $label . '</button></li>';
$off = fn (string $label) => $item(e($label), 'aria-disabled="true"');
$sep = '<li role="separator"></li>';
?>
<nav class="menubar" aria-label="Menu bar">
  <ul class="menus" role="menubar">
    <li class="menu-root">
      <button class="menu-title" type="button" aria-haspopup="true" aria-expanded="false" aria-label="Bakery menu"><?= small_icon('bread') ?></button>
      <ul class="menu" role="menu" hidden>
        <?= $item('About This ' . e($site['profile']['name']) . '…', 'data-action="open" data-target="win-about"') ?>
        <?= $sep ?>
<?php foreach ($apple as [$win, $img, $label]): ?>
        <?= $item('<img class="menu-icon" src="' . e(icon($img)) . '" width="16" height="16" alt="">' . e($label), 'data-action="open" data-target="' . e($win) . '"') ?>
<?php endforeach ?>
      </ul>
    </li>
    <li class="menu-root">
      <button class="menu-title" type="button" aria-haspopup="true" aria-expanded="false">File</button>
      <ul class="menu" role="menu" hidden>
        <?= $item('Open', 'data-action="open-selection" data-enable="selection"') ?>
        <?= $item('Close Window', 'data-action="close-window" data-enable="window"') ?>
        <?= $sep ?>
        <?= $item('Get Info', 'data-action="get-info" data-enable="project"') ?>
        <?= $sep ?>
        <?= $off('Page Setup…') ?>
        <?= $off('Print…') ?>
      </ul>
    </li>
    <li class="menu-root menu-edit">
      <button class="menu-title" type="button" aria-haspopup="true" aria-expanded="false">Edit</button>
      <ul class="menu" role="menu" hidden>
        <?= $off('Undo') ?>
        <?= $sep ?>
        <?= $off('Cut') ?><?= $off('Copy') ?><?= $off('Paste') ?><?= $off('Clear') ?>
        <?= $sep ?>
        <?= $item('Copy E-mail Address', 'data-action="copy-email"') ?>
      </ul>
    </li>
    <li class="menu-root">
      <button class="menu-title" type="button" aria-haspopup="true" aria-expanded="false">View</button>
      <ul class="menu" role="menu" hidden>
<?php foreach (['name' => 'Name', 'stars' => 'Stars', 'kind' => 'Kind', 'date' => 'Date'] as $key => $label): ?>
        <li><button role="menuitemradio" type="button" data-action="sort" data-key="<?= $key ?>" data-enable="list">by <?= $label ?></button></li>
<?php endforeach ?>
      </ul>
    </li>
    <li class="menu-root">
      <button class="menu-title" type="button" aria-haspopup="true" aria-expanded="false">Special</button>
      <ul class="menu" role="menu" hidden>
        <?= $item('Clean Up Windows', 'data-action="clean-up" data-enable="desktop"') ?>
        <?= $item('Empty Trash…', 'data-action="empty-trash"') ?>
        <?= $sep ?>
        <?= $item('Restart', 'data-action="restart"') ?>
        <?= $item('Shut Down', 'data-action="shut-down"') ?>
      </ul>
    </li>
  </ul>
  <ul class="menus-right" role="menubar">
    <li class="clock" aria-label="Local time"><time id="clock">&nbsp;</time></li>
    <li class="menu-root">
      <button class="menu-title" type="button" aria-haspopup="true" aria-expanded="false" aria-label="Application menu"><?= small_icon('app-16') ?></button>
      <ul class="menu" role="menu" hidden>
        <?= $off('Hide Finder') ?>
        <?= $item('Hide Others', 'data-action="hide-others" data-enable="window"') ?>
        <?= $item('Show All', 'data-action="show-all"') ?>
        <?= $sep ?>
        <li><button role="menuitemradio" type="button" aria-checked="true"><img class="menu-icon" src="<?= e(icon('app-16')) ?>" width="16" height="16" alt="">Finder</button></li>
      </ul>
    </li>
  </ul>
</nav>
