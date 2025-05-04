<?php
// user_home.php
require 'config.php';
if (empty($_SESSION['user'])) {
    header('Location: signin.php');
    exit;
}

// 1. Fetch fresh user data from DB
$stmt = $pdo->prepare("
    SELECT username,
           xp,
           level,
           coins,
           notification_count
      FROM users
     WHERE id = ?
");
$stmt->execute([$_SESSION['user']['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // no such user? force sign‑in
    header('Location: signin.php');
    exit;
}

// 2. Prepare and sanitize values
$usernameEscaped    = htmlspecialchars($user['username']);
$initial            = strtoupper($usernameEscaped[0]);
$xpValue            = (int)$user['xp'];
$level              = min((int)$user['level'], 30);
$coins              = number_format((int)$user['coins']);
$notification_count = (int)$user['notification_count'];

// 3. Compute XP needed and percentage for next level
if ($level < 30) {
    $xpNeeded = 100 + ($level - 1) * 50;
} else {
    $xpNeeded = 100 + 29 * 50;
}
$xpPercent = min(100, ($xpValue / $xpNeeded) * 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Welcome, <?= $usernameEscaped ?> – MoodMate</title>
  <link rel="stylesheet" href="css/user_home.css">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-…"
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
</head>
<body class="user-home">

  <?php include 'templates/header.php'; ?>

  <!-- Welcome Modal -->
  <div id="welcomeModal" class="modal" data-username="<?= $usernameEscaped ?>">
    <div class="modal-content">
      <span class="close-button">&times;</span>
      <h2 id="greetingText"></h2>
      <p id="encourageText"></p>
      <button id="modalCloseBtn" class="btn-primary">Continue</button>
    </div>
  </div>

  <main class="home-content">

    <!-- ===== Top Bar ===== -->
    <div class="top-bar">
      <!-- Avatar -->
      <a href="#" class="avatar" title="Your Profile">
        <?= $initial ?>
      </a>

      <!-- XP Progress & Level -->
      <div class="user-stats">
        <div class="xp-bar">
          <div
            class="xp-fill"
            style="width: <?= round($xpPercent) ?>%;"
          ></div>
        </div>
        <div class="xp-text">
          <?= number_format($xpValue) ?> / <?= number_format($xpNeeded) ?> XP
        </div>
        <div class="level">
          Level <?= $level ?>
        </div>
      </div>

      <!-- Notifications & Coins -->
      <div class="top-actions">
        <button class="icon-btn" aria-label="Notifications">
          <i class="fas fa-bell"></i>
          <?php if ($notification_count > 0): ?>
            <span class="badge"><?= $notification_count ?></span>
          <?php endif; ?>
        </button>
        <div class="coins">
          <i class="fas fa-coins"></i>
          <span><?= $coins ?></span>
        </div>
      </div>
    </div>

    <!-- ... rest of home page content ... -->

  </main>

  <?php include 'templates/footer.php'; ?>
  <script src="js/user_home.js"></script>
</body>
</html>
