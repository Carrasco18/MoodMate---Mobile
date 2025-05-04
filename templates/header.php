<?php
  // We assume session_start() and $pdo exist via config.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Auth Demo</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
  <nav>
    <?php if (!empty($_SESSION['user'])): ?>
      <a href="user_home.php">Home</a>
      <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="admin_home.php">Admin Panel</a>
      <?php endif; ?>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="signup.php">Sign Up</a>
      <a href="signin.php">Sign In</a>
    <?php endif; ?>
  </nav>
  <main>
