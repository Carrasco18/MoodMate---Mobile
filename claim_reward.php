<?php
// claim_reward.php
require 'config.php';

if (empty($_SESSION['user'])) {
    http_response_code(401);
    exit(json_encode(['error'=>'Not authenticated']));
}
$uid = $_SESSION['user']['id'];

// Fetch current state
$stmt = $pdo->prepare("SELECT last_claim, streak_day FROM daily_rewards WHERE user_id = ?");
$stmt->execute([$uid]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// If no row, something’s wrong
if (!$row) {
    http_response_code(400);
    exit(json_encode(['error'=>'No record found']));
}

$today = date('Y-m-d');
if ($row['last_claim'] >= $today) {
    // Already claimed today
    echo json_encode(['success'=>false,'message'=>'Already claimed today']);
    exit;
}

// Calculate new streak (max 7)
$newStreak = min($row['streak_day'] + 1, 7);

// TODO: integrate real awarding logic here, e.g. add to user’s balance

// Update DB
$stmt = $pdo->prepare("
    UPDATE daily_rewards
       SET last_claim = ?, streak_day = ?
     WHERE user_id = ?
");
$stmt->execute([$today, $newStreak, $uid]);

echo json_encode(['success'=>true,'newStreak'=>$newStreak]);
