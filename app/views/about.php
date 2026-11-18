<?php
/** @var array $site */
$s = $site['stats'];
$max = max(1, $site['top'][0]['stars'] ?? 1);
window_start('win-about', 'About This ' . $site['profile']['name'], ['open' => true]);
?>
<div class="about">
  <div class="about-top">
    <div class="about-who"><img src="<?= e(icon('bread-32')) ?>" width="32" height="32" alt=""><span><?= e($site['profile']['handle']) ?></span></div>
    <div class="about-sys">Developer Software 7.0<br><?= e($site['profile']['place']) ?></div>
  </div>
  <div class="about-mem">
    <span>Repositories: <b><?= (int) $s['repos'] ?></b></span><span>Stars earned: <b><?= (int) $s['stars'] ?></b></span>
    <span>Followers: <b><?= (int) $s['followers'] ?></b></span><span>Organizations: <b><?= (int) $s['orgs'] ?></b></span>
  </div>
  <ul class="about-apps" aria-label="Most starred projects">
<?php foreach ($site['top'] as $p):
    $pct = round($p['stars'] / $max * 100, 1); ?>
    <li><?= small_icon('app-16') ?><span><?= e($p['name']) ?></span><span class="n"><?= e(stars((int) $p['stars'])) ?></span><span class="meter"><span style="width: <?= $pct ?>%"></span></span></li>
<?php endforeach ?>
  </ul>
</div>
<?php window_end();
