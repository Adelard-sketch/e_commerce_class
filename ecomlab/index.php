<?php
require_once "core/core.php";

if (isset($_GET['logout'])) {
    logoutCustomer();
    header("Location: index.php");
    exit;
}

$currentUser = $_SESSION['customer_name'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Shoppin</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-body">
	<div class="auth-page home-panel">
		<h1>Welcome to Shoppin</h1>
		<?php require_once "views/layout/header.php"; ?>

		<?php if ($errorMessage): ?>
			<div class="auth-error"><?php echo htmlspecialchars($errorMessage); ?></div>
		<?php endif; ?>

		<?php if ($currentUser) { ?>
			<p class="welcome-message">Welcome, <?php echo htmlspecialchars($currentUser); ?>!</p>
		<?php } ?>
	</div>
</body>
</html>
