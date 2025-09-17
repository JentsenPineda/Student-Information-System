<?php
session_start();
include("config.php");

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $role = "student"; // default role

    // check if username already exists
    $check = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($check->num_rows > 0) {
        $error = "Username already taken!";
    } else {
        $conn->query("INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')");
        $_SESSION['success'] = "Account created successfully! You can now login.";
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f3f4f6;<?php
session_start();
include("config.php");

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $role = "student"; // default role

    // check if username already exists
    $check = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($check->num_rows > 0) {
        $error = "Username already taken!";
    } else {
        $conn->query("INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')");
        $_SESSION['success'] = "Account created successfully! You can now login.";
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f3f4f6;
    }
    .register-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .card {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0px 6px 20px rgba(0,0,0,0.1);
    }
    .left-panel {
      background: white;
      padding: 40px;
      width: 400px;
    }
    .right-panel {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: white;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: center;
    }
    .btn-purple {
      background: #6a11cb;
      border: none;
    }
    .btn-purple:hover {
      background: #2575fc;
    }
    .password-wrapper {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      cursor: pointer;
      color: gray;
    }
  </style>
</head>
<body>

<div class="register-container">
  <div class="card d-flex flex-row">
    <!-- Left: Registration Form -->
    <div class="left-panel">
      <h3 class="mb-3 text-center">📝 Create Account</h3>

      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="POST">
        <div class="mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3 password-wrapper">
          <input type="password" id="password" name="password" class="form-control" placeholder="Password (min 8 characters)" required>
          <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>
        <button type="submit" name="register" class="btn btn-purple w-100 text-white">Register</button>
      </form>

      <p class="text-center mt-3">
        Already have an account? <a href="login.php">Login</a>
      </p>
    </div>

    <!-- Right: Info / Branding -->
    <div class="right-panel">
      <h2>Empowering Students<br>Through Technology.</h2>
      <p class="mt-3">Manage student records, grades, and courses efficiently with our system.</p>
    </div>
  </div>
</div>

<script>
function togglePassword() {
  const password = document.getElementById("password");
  password.type = password.type === "password" ? "text" : "password";
}
</script>

</body>
</html>

    }
    .register-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .card {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0px 6px 20px rgba(0,0,0,0.1);
    }
    .left-panel {
      background: white;
      padding: 40px;
      width: 400px;
    }
    .right-panel {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: white;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: center;
    }
    .btn-purple {
      background: #6a11cb;
      border: none;
    }
    .btn-purple:hover {
      background: #2575fc;
    }
  </style>
</head>
<body>

<div class="register-container">
  <div class="card d-flex flex-row">
    <!-- Left: Registration Form -->
    <div class="left-panel">
      <h3 class="mb-3 text-center">📝 Create Account</h3>

      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="POST">
        <div class="mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password (min 8 characters)" required>
        </div>
        <button type="submit" name="register" class="btn btn-purple w-100 text-white">Register</button>
      </form>

      <p class="text-center mt-3">
        Already have an account? <a href="login.php">Login</a>
      </p>
    </div>

    <!-- Right: Info / Branding -->
    <div class="right-panel">
      <h2>Empowering Students<br>Through Technology.</h2>
      <p class="mt-3">Manage student records, grades, and courses efficiently with our system.</p>
    </div>
  </div>
</div>

</body>
</html>
