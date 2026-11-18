<?php /** @var array $site */ ?>
<ul class="desk-icons" aria-label="Desktop">
  <li><?= icon_opens('win-hd', 'disk', $site['profile']['handle'] . ' HD') ?></li>
  <li><?= icon_opens('win-readme', 'readme', 'Read Me') ?></li>
  <li><?= icon_opens('win-projects', 'folder', 'Projects') ?></li>
  <li><?= icon_opens('win-blog', 'folder', 'Blog') ?></li>
  <li><?= icon_opens('win-links', 'folder', 'Links') ?></li>
  <li><?= icon_opens('win-domains', 'folder', 'Domains') ?></li>
  <li class="trash-slot"><?= icon_opens('win-trash', 'trash-full', 'Trash') ?></li>
</ul>
