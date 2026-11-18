<?php
declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';
send_headers(404);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>m5rcel – page not found</title>
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="robots" content="noindex">
<link rel="icon" href="/assets/icons/bread.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/css/system7.css">
</head>
<body>
<main class="error-screen">
  <div class="dialog" role="alertdialog" aria-labelledby="err">
    <img src="/assets/icons/bomb.svg" width="32" height="32" alt="">
    <div class="dialog-text">
      <p id="err">Sorry, a system error occurred.</p>
      <p>"<?= e(rawurldecode(strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/')) ?>" not found<br>ID = 404</p>
    </div>
    <div class="dialog-actions">
      <a class="btn default" href="/">Restart</a>
    </div>
  </div>
</main>
</body>
</html>
