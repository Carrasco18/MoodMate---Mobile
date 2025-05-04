<?php
require 'config.php';

$token = $_GET['token'] ?? '';
if ($token) {
    // Find user with this token
    $stmt = $pdo->prepare("
      SELECT id, is_verified 
      FROM users 
      WHERE verification_token = ?
    ");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['is_verified']) {
            $msg = "Your account is already verified. You can log in now.";
        } else {
            // Activate account
            $stmt = $pdo->prepare("
              UPDATE users 
              SET is_verified = 1, verification_token = NULL 
              WHERE id = ?
            ");
            $stmt->execute([$user['id']]);
            $msg = "Thank you! Your email has been verified. You can now log in.";
        }
    } else {
        $msg = "Invalid or expired verification link.";
    }
} else {
    $msg = "No verification token provided.";
}
?>
<?php include 'templates/header.php'; ?>

<h1>Email Verification</h1>
<p><?= htmlspecialchars($msg) ?></p>
<p><a href="signin.php">Go to Log in</a></p>

<?php include 'templates/footer.php'; ?>
