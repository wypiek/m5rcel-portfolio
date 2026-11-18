<?php
declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$site = load_site();
$p = $site['profile'];
send_headers();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= e($p['handle']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="description" content="<?= e($p['name']) ?> (<?= e($p['handle']) ?>) is a full-stack and systems developer from Poland. meowcord, AXON, 3itter and around <?= (int) floor($site['stats']['repos'] / 10) * 10 ?> other projects.">
<meta property="og:title" content="<?= e($p['handle']) ?>">
<meta property="og:description" content="Full-stack and systems developer from Poland. Small batches, clean code, crispy edges.">
<meta property="og:type" content="website">
<meta name="theme-color" content="#ffffff">
<link rel="icon" href="assets/icons/bread.svg" type="image/svg+xml">
<link rel="preload" href="assets/fonts/ChiKareGo2.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="assets/fonts/FindersKeepers.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="assets/css/system7.css?v=<?= filemtime(__DIR__ . '/assets/css/system7.css') ?>">
<script type="module" src="assets/js/main.js?v=<?= filemtime(__DIR__ . '/assets/js/main.js') ?>"></script>
</head>
<body>

<span class="corner tl"></span><span class="corner tr"></span><span class="corner bl"></span><span class="corner br"></span>

<?php view('menubar', $site) ?>

<main class="desktop" id="desktop">
  <h1 class="visually-hidden"><?= e($p['handle']) ?>, full-stack and systems developer from Poland</h1>

<?php view('desktop-icons', $site) ?>

  <div class="windows" id="windows">
<?php
view('readme', $site);
view('projects', $site);
view('about', $site);
view('finder', $site);
view('panels', $site);
?>
  </div>
</main>

<noscript><style>.tip-pointer, .tip-touch { display: none; }</style></noscript>

</body>
</html>
