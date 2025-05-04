<?php
// daily_status.php
require 'config.php';

// 1) Ensure user is logged in
if (empty($_SESSION['user'])) {
    http_response_code(401);
    exit(json_encode(['error'=>'Not authenticated']));
}
$uid = $_SESSION['user']['id'];

// 2) Fetch or initialize record
$stmt = $pdo->prepare("SELECT last_claim, streak_day FROM daily_rewards WHERE user_id = ?");
$stmt->execute([$uid]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    // Insert initial record
    $pdo->prepare("INSERT INTO daily_rewards (user_id) VALUES (?)")
        ->execute([$uid]);
    $row = ['last_claim'=>'0000-00-00', 'streak_day'=>0];
}

// 3) Determine if user can claim today
$today    = date('Y-m-d');
$canClaim = ($row['last_claim'] < $today);

// 4) Define your 7 rewards (icons + text)
$rewards = [
  ['icon'=>'fa-coins','text'=>'20 Coins'],
  ['icon'=>'fa-heart','text'=>'15 Lives'],
  ['icon'=>'fa-bolt','text'=>'1 Booster'],
  ['icon'=>'fa-coins','text'=>'25 Coins'],
  ['icon'=>'fa-gem','text'=>'10 Gems'],
  ['icon'=>'fa-coins','text'=>'30 Coins'],
  ['icon'=>'fa-trophy','text'=>'100 Coins + 2h Lives'],
];

// 5) Return JSON
header('Content-Type: application/json');
echo json_encode([
  'streakDay' => (int)$row['streak_day'],
  'canClaim'  => $canClaim,
  'rewards'   => $rewards
]);
