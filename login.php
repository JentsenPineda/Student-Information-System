<?php
session_start();
include("config.php");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // hash check

    $result = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$password'");

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Student Information System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fb;
    }
    .login-container {
      display: flex;
      height: 100vh;
    }
    .login-left {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #fff;
      padding: 50px;
    }
    .login-box {
      width: 100%;
      max-width: 400px;
    }
    .login-box h3 {
      font-weight: 700;
      margin-bottom: 10px;
    }
    .login-box p {
      color: #6c757d;
      margin-bottom: 30px;
    }
    .form-control {
      border-radius: 10px;
      padding: 12px;
    }
    .btn-login {
      background: #5a4fcf;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
    }
    .btn-login:hover {
      background: #463bb3;
    }
    .login-right {
      flex: 1;
      background: linear-gradient(135deg, #5a4fcf, #836dff);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 50px;
    }
    .login-right h2 {
      font-weight: 700;
    }
  </style>
</head>
<body>

<div class="login-container">
  <!-- Left side (form) -->
  <div class="login-left">
    <div class="login-box">
      <h3>Login</h3>
      <p>Access your Student Information System account</p>

      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="POST">
        <div class="mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
          <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="showPassword">
            <label class="form-check-label text-muted" for="showPassword">Show Password</label>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <input type="checkbox" id="remember"> <label for="remember" class="text-muted">Remember me</label>
          </div>
          <a href="#" class="text-decoration-none">Forgot password?</a>
        </div>
        <button type="submit" name="login" class="btn btn-login w-100">Login</button>
      </form>

      <div class="text-center mt-3">
        <small class="text-muted">Not registered yet? <a href="register.php" class="text-decoration-none">Create an Account</a></small>
      </div>
    </div>
  </div>

  <!-- Right side (info/illustration) -->
  <div class="login-right">
    <div>
      <h2>Where Student Data<br>Meets Efficiency.</h2>
      <p class="mt-3">Seamless access and management of student records, anytime and anywhere.</p>
    </div>
  </div>
</div>

<script>
  const showPassword = document.getElementById('showPassword');
  const passwordField = document.getElementById('password');

  showPassword.addEventListener('change', function () {
    if (this.checked) {
      passwordField.type = 'text';
    } else {
      passwordField.type = 'password';
    }
  });
</script>

</body>
</html>
