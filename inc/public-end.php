<div class="qs-modal" data-qs-video-modal hidden>
    <div class="qs-modal__backdrop" data-qs-video-close></div>
    <div class="qs-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="qs-video-title">
        <div class="qs-modal__bar">
            <h2 id="qs-video-title">How it works</h2>
            <button class="qs-modal__close" type="button" data-qs-video-close aria-label="Close">&times;</button>
        </div>
        <video class="qs-modal__video" controls playsinline preload="none">
            <source src="assets/video/how-it-works.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>
<script src="assets/js/qs-public.js?v=<?php echo is_file(__DIR__ . '/../assets/js/qs-public.js') ? filemtime(__DIR__ . '/../assets/js/qs-public.js') : time(); ?>"></script>
<?php
if (!empty($pageScripts)) {
    foreach ((array) $pageScripts as $qsPageScript) {
        $qsPageScriptRel = ltrim((string) $qsPageScript, '/');
        $qsPageScriptFile = __DIR__ . '/../' . $qsPageScriptRel;
        $qsPageScriptVer = is_file($qsPageScriptFile) ? filemtime($qsPageScriptFile) : time();
        echo '<script src="' . htmlspecialchars($qsPageScriptRel) . '?v=' . $qsPageScriptVer . '"></script>' . "\n";
    }
}
?>
<?php include __DIR__ . '/cookie-banner.php'; ?>
</body>
</html>
