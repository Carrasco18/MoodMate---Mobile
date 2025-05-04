<?php
// signin.php
require 'config.php';
$errors = [];
$remember = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Simple validation
    if ($username === '' || $password === '') {
        $errors[] = "Both fields are required.";
    } else {
        // Fetch user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login success
            $_SESSION['user'] = [
                'id'       => $user['id'],
                'username' => $user['username'],
                'role'     => $user['role']
            ];

            // Optionally set a “remember me” cookie
            if ($remember) {
                setcookie('remember_user', $user['id'], time() + 60*60*24*30, "/");
            }

            // Redirect
            if ($user['role'] === 'admin') {
                header("Location: admin_home.php");
            } else {
                header("Location: user_home.php");
            }
            exit;
        } else {
            $errors[] = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Welcome Back – MoodMate</title>
  <!-- Rubik & Font Awesome -->
  <link
    href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="signin-page">

  <!-- Optional back button -->
  <a href="index.php" class="back-btn">
    <i class="fas fa-arrow-left"></i>
  </a>

  <div class="signup-container">
    <h1>Welcome Back</h1>
    <p class="subtitle">Login to your account</p>

    <?php if($errors): ?>
      <ul class="error">
        <?php foreach($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form method="post" action="signin.php" novalidate>
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
        <i class="fas fa-lock"></i>
        <input
          type="password"
          name="password"
          placeholder="Password"
          required
        >
      </div>

      <div class="form-extra">
        <label>
          <input
            type="checkbox"
            name="remember"
            <?= $remember ? 'checked' : '' ?>
          > Remember me
        </label>
        <a href="#">Forgot Password?</a>
      </div>

      <!-- Your custom “LOGIN” button -->
      <button type="submit" class="button">
        <div><span>LOGIN</span></div>
      </button>
    </form>

    <p class="login-link">
      Don’t have an account?
      <a href="signup.php">Sign up</a>
    </p>
  </div>

</body>
</html>