<?php
require 'config.php';

// Only allow admins
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit("Access denied. Admins only.");
}

include 'templates/header.php';
?>
  <h1>Admin Dashboard</h1>
  <p>Here you can manage the site.</p>
<?php include 'templates/footer.php'; ?>
