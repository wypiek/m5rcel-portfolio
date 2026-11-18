<?php
/** @var array $site */
$s = $site['stats'];
window_start('win-projects', 'Projects', [
    'open' => true,
    'scroll' => true,
    'info' => [items($s['items']), $s['repos'] . ' repos on GitHub', stars($s['stars']) . ' earned'],
]);
?>
<div class="list" data-sort="stars" role="tree" aria-label="Projects">
  <div class="list-head">
    <button type="button" data-sort="name">Name</button>
    <button type="button" class="num" data-sort="stars">Stars</button>
    <button type="button" data-sort="kind">Kind</button>
    <button type="button" class="c-date" data-sort="date">Last Modified</button>
  </div>
<?php foreach ($site['projects'] as $folder => $group):
    $open = $group['open'] ? 'true' : 'false'; ?>

  <div class="row folder" role="treeitem" aria-expanded="<?= $open ?>">
    <span class="c-name"><button class="twisty" type="button" aria-expanded="<?= $open ?>" aria-label="<?= e($folder) ?>"></button><?= small_icon('folder-16') ?><span class="label"><?= e($folder) ?></span></span>
    <span class="c-num">–</span><span>folder</span><span class="c-date">–</span>
  </div>
  <div class="group" role="group">
<?php foreach ($group['items'] as $p): ?>
    <a class="row item" role="treeitem" href="https://github.com/<?= e($p['repo']) ?>" target="_blank" rel="noopener"
       data-name="<?= e($p['name']) ?>" data-stars="<?= (int) $p['stars'] ?>" data-kind="<?= e($p['kind']) ?>" data-date="<?= e($p['date']) ?>"
       data-where="<?= e($p['repo']) ?>" data-desc="<?= e($p['desc']) ?>">
      <span class="c-name"><?= small_icon('app-16') ?><span class="label"><?= e($p['name']) ?></span></span><span class="c-num"><?= (int) $p['stars'] ?></span><span><?= e($p['kind']) ?></span><span class="c-date"><?= e(fmt_date($p['date'])) ?></span>
    </a>
<?php endforeach ?>
  </div>
<?php endforeach ?>
</div>
<?php window_end();
