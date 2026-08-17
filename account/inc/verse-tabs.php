<?php
if (!isset($verseTab)) {
	$verseTab = 'purchase';
}
if (!function_exists('qs_verse_planet')) {
	function qs_verse_planet() {
		return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="5.2"/><ellipse cx="12" cy="12" rx="11" ry="3.6" transform="rotate(-22 12 12)"/></svg>';
	}
}
?>
<nav class="qs-verse-tabs" aria-label="Quantum Verse">
	<a class="qs-verse-tab<?php echo $verseTab === 'purchase' ? ' is-active' : ''; ?>" href="marketplace">Make Purchase</a>
	<a class="qs-verse-tab<?php echo $verseTab === 'active' ? ' is-active' : ''; ?>" href="active-purchase">Active Purchases</a>
	<a class="qs-verse-tab<?php echo $verseTab === 'expired' ? ' is-active' : ''; ?>" href="expire-purchase">Expired Purchases</a>
</nav>
