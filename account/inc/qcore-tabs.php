<?php
if (!isset($qcoreTab)) {
	$qcoreTab = 'overview';
}
?>
<nav class="qs-qcore-tabs" aria-label="Q-Core">
	<a class="qs-qcore-tab<?php echo $qcoreTab === 'overview' ? ' is-active' : ''; ?>" href="overview-core">Overview</a>
	<a class="qs-qcore-tab<?php echo $qcoreTab === 'cex' ? ' is-active' : ''; ?>" href="overview-core?tab=cex">CEX Live</a>
	<a class="qs-qcore-tab<?php echo $qcoreTab === 'dex' ? ' is-active' : ''; ?>" href="overview-core?tab=dex">DEX Live</a>
	<a class="qs-qcore-tab<?php echo $qcoreTab === 'futures' ? ' is-active' : ''; ?>" href="overview-core?tab=futures">Futures Live</a>
	<a class="qs-qcore-tab<?php echo $qcoreTab === 'signals' ? ' is-active' : ''; ?>" href="overview-core?tab=signals">Quantum Signals</a>
</nav>
