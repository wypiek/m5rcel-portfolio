<?php
// Get Info (filled in by finder.js) and the Desktop Patterns control panel.
// Both only make sense with JavaScript, so they start hidden.

window_start('win-info', 'Info', ['hidden' => true]);
?>
<div class="info">
  <div class="info-head"><img src="<?= e(icon('app')) ?>" width="32" height="32" alt=""><span class="name" data-field="name"></span></div>
  <dl class="pairs">
    <dt>Kind:</dt><dd data-field="kind"></dd>
    <dt>Stars:</dt><dd data-field="stars"></dd>
    <dt>Where:</dt><dd data-field="where"></dd>
    <dt>Modified:</dt><dd data-field="date"></dd>
  </dl>
  <div>
    <div class="info-label">Comments:</div>
    <div class="info-comment" data-field="desc"></div>
  </div>
  <div class="info-actions"><a class="btn default" data-field="link" href="#" target="_blank" rel="noopener">Open on GitHub</a></div>
</div>
<?php window_end();

window_start('win-patterns', 'Desktop Patterns', ['hidden' => true]);
?>
<div class="patterns">
  <div class="swatch" id="pattern-swatch"></div>
  <div class="patterns-controls">
    <div class="stepper">
      <button class="arrow-btn prev" type="button" id="pattern-prev" aria-label="Previous pattern"></button>
      <output id="pattern-name" aria-live="polite">Auto</output>
      <button class="arrow-btn next" type="button" id="pattern-next" aria-label="Next pattern"></button>
    </div>
    <button class="btn default" type="button" id="pattern-set">Set Desktop</button>
  </div>
</div>
<p class="tip patterns-tip">Auto follows your light or dark setting.</p>
<?php window_end();
