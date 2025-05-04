<?php
// daily_rewards.php
require 'config.php';
if (empty($_SESSION['user'])) {
    header('Location: signin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Daily Rewards</title>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="css/rewards.css">
</head>
<body>

  <div class="rewards-container">
    <h2>Daily Login Rewards</h2>
    <div id="rewardsGrid" class="rewards-grid">
      <!-- JS will populate the 7 cells here -->
    </div>
    <button id="claimBtn" class="claim-button" disabled>Claim Today’s Reward</button>
    <p id="message" class="message"></p>
  </div>

  <script src="js/rewards.js"></script>
</body>
</html>
