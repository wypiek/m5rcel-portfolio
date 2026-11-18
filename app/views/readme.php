<?php
/** @var array $site */
$p = $site['profile'];
$gh = 'https://github.com/' . rawurlencode($p['github']);
window_start('win-readme', 'Read Me', ['open' => true, 'scroll' => true, 'long' => true]);
?>
<article class="doc">
  <header class="doc-head">
    <img class="pfp" src="<?= e($p['pfp']) ?>" width="112" height="112" alt="<?= e($p['handle']) ?>'s profile picture: a grumpy cartoon girl with floppy dog ears, hugging her knees">
    <h1>Hi, I'm <?= e($p['name']) ?>.</h1>
  </header>
  <p>Online I go by <strong><?= e($p['handle']) ?></strong>. I'm a full-stack and systems developer from Poland, and I like websites, APIs, Linux, self-hosting, Minecraft, Roblox and old internet pages.</p>
  <p>I build things from scratch: chat platforms, Twitter recreations, a programming language, package managers, a couple of kernels and plenty of small tools. Most of it lives on GitHub as <a href="<?= e($gh) ?>" target="_blank" rel="noopener"><?= e($p['github']) ?></a>, and some of it runs on my own servers.</p>
  <p class="tip" id="warsaw-line">It's <time id="warsaw-time"><?= e((new DateTime('now', new DateTimeZone($p['timezone'])))->format('H:i')) ?></time> in Warsaw right now.</p>

  <h2>Currently baking</h2>
  <ul>
<?php foreach ($site['baking'] as [$name, $what]): ?>
    <li><strong><?= e($name) ?></strong>, <?= e($what) ?></li>
<?php endforeach ?>
  </ul>

  <h2>House recipe</h2>
  <blockquote class="recipe">
    <p>Start simple. Make it work. Keep it light. Give it character.</p>
    <p>And if it breaks, call it an experimental batch.</p>
  </blockquote>

  <h2>In the pantry</h2>
  <dl class="pairs">
<?php foreach ($site['pantry'] as $label => $value): ?>
    <dt><?= e($label) ?></dt><dd><?= e($value) ?></dd>
<?php endforeach ?>
  </dl>

  <h2>Say hi</h2>
  <p>E-mail <a href="#" data-mail="<?= e($p['email_user']) ?>" data-domain="<?= e($p['email_domain']) ?>"><?= e($p['email_user']) ?> [at] <?= e($p['email_domain']) ?></a>, or find me on <a href="https://x.com/<?= e($p['x']) ?>" target="_blank" rel="noopener">X as @<?= e($p['x']) ?></a> and <a href="https://discord.com/users/<?= e($p['discord_id']) ?>" target="_blank" rel="noopener">Discord as <?= e($p['discord']) ?></a>. My main home on the web is <a href="https://<?= e($p['home']) ?>/" target="_blank" rel="noopener"><?= e($p['home']) ?></a>, where the blog lives too.</p>

  <p class="tip"><span class="tip-pointer">Double-click icons to open them, and drag windows around by their title bars.</span><span class="tip-touch">Tap an icon to open its window.</span></p>
</article>
<?php window_end();
