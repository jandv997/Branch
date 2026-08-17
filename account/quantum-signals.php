<?php
session_start();
if (!isset($_SESSION['id'])) {
	header("location:index");
	exit;
}
header("Location: overview-core?tab=signals");
exit;
