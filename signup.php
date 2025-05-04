<?php
// signup.php
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Collect & sanitize
    $username         = trim($_POST['username'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $pass             = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 2) Validate username
    if (strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters.";
    }

    // 3) Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // 4) Validate password
    if (strlen($pass) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } elseif ($pass !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // 5) Check username/email uniqueness
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email_address = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Username or email already in use.";
        }
    }

    // 6) Insert new user (unverified) and send verification email
    if (empty($errors)) {
        $hash  = password_hash($pass, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(32));

        // Insert with verification token, is_verified defaults to 0
        $stmt = $pdo->prepare("
            INSERT INTO users
                (username, email_address, password_hash, verification_token, is_verified)
            VALUES
                (?, ?, ?, ?, 0)
        ");
        $stmt->execute([$username, $email, $hash, $token]);

        // Build verification link
        $verifyUrl = sprintf(
            'https://%s%s/verify.php?token=%s',
            $_SERVER['HTTP_HOST'],
            dirname($_SERVER['PHP_SELF']),
            $token
        );

        // Send email (simple mail(); for production, use a proper mailer)
        $subject = 'Please verify your MoodMate account';
        $message = "Hi {$username},\n\n"
                 . "Thanks for registering on MoodMate! To activate your account, "
                 . "please click the link below:\n\n"
                 . "{$verifyUrl}\n\n"
                 . "If you did not sign up, you can ignore this message.\n\n"
                 . "— The MoodMate Team";
        $headers = 'From: no-reply@' . $_SERVER['HTTP_HOST'] . "\r\n"
                 . 'Content-Type: text/plain; charset=UTF-8';

        mail($email, $subject, $message, $headers);

        // Redirect to “check your email” screen
        header('Location: signup.php?registered=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register – MoodMate</title>
  <link rel="stylesheet" href="css/style.css">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-…"
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
</head>
<body class="signup-page">

  <!-- Back arrow -->
  <a href="index.php" class="back-btn">
    <i class="fas fa-arrow-left"></i>
  </a>

  <div class="signup-container">
    <h1>Register</h1>
    <p class="subtitle">Create your account</p>

    <!-- If just registered, show verification notice -->
    <?php if (isset($_GET['registered'])): ?>
      <div class="success-box">
        <p>Almost done! Check your email for a verification link to activate your account.</p>
      </div>
    <?php endif; ?>

    <!-- Show validation errors -->
    <?php if (!empty($errors)): ?>
      <div class="error-box">
        <?php foreach ($errors as $e): ?>
          <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="post" action="signup.php" novalidate>
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input
          type="text"
          name="username"
          placeholder="Username"
          required
          value="<?= htmlspecialchars($username ?? '') ?>"
        >
      </div>

      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input
          type="email"
          name="email"
          placeholder="Email address"
          required
          value="<?= htmlspecialchars($email ?? '') ?>"
        >
      </div>

      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input
          type="password"
          name="password"
          placeholder="Password"
          required
        >
      </div>

      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input
          type="password"
          name="confirm_password"
          placeholder="Confirm password"
          required
        >
      </div>

      <p class="terms">
        By registering, you are agreeing to our
        <a href="#">Terms of Use</a> and
        <a href="#">Privacy Policy</a>.
      </p>

      <button type="submit" class="button">
        <div><span>REGISTER</span></div>
      </button>
    </form>

    <p class="login-link">
      Already have an account?
      <a href="signin.php">Login</a>
    </p>
  </div>

</body>
</html>
