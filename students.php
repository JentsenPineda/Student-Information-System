<?php
session_start();
include("config.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // logged-in user's ID

// Add Student
if (isset($_POST['add_student'])) {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'];

    $conn->query("INSERT INTO students (first_name, last_name, email, phone, user_id) 
                  VALUES ('$first_name','$last_name','$email','$phone','$user_id')");
    header("Location: students.php"); // refresh
    exit;
}

// Delete Student (only if belongs to this user)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM students WHERE student_id=$id AND user_id=$user_id");
    header("Location: students.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Students</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">SIS Dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="students.php">Students</a></li>
          <li class="nav-item"><a class="nav-link" href="courses.php">Courses</a></li>
          <li class="nav-item"><a class="nav-link" href="subjects.php">Subjects</a></li>
          <li class="nav-item"><a class="nav-link" href="enrollments.php">Enrollments</a></li>
          <li class="nav-item"><a class="nav-link" href="grades.php">Grades</a></li>
        </ul>

        <ul class="navbar-nav ms-3">
          <li class="nav-item">
            <span class="navbar-text text-white me-3">
              👤 <?php echo $_SESSION['username']; ?>
            </span>
          </li>
          <li class="nav-item">
            <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <h2 class="mb-4">👨‍🎓 Manage Students</h2>

    <!-- Add Student Form -->
    <form method="POST" class="row g-3 mb-4">
      <div class="col-md-3">
        <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
      </div>
      <div class="col-md-3">
        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
      </div>
      <div class="col-md-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="col-md-2">
        <input type="text" name="phone" class="form-control" placeholder="Phone">
      </div>
      <div class="col-md-1">
        <button type="submit" name="add_student" class="btn btn-primary w-100">Add</button>
      </div>
    </form>

    <!-- Students Table -->
    <div class="card shadow">
      <div class="card-header bg-dark text-white">Student List</div>
      <div class="card-body">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = $conn->query("SELECT * FROM students WHERE user_id = $user_id");
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['student_id']}</td>
                        <td>{$row['first_name']} {$row['last_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td class='text-center'>
                          <a href='students.php?delete={$row['student_id']}' 
                             class='btn btn-danger btn-sm'
                             onclick=\"return confirm('Are you sure you want to delete this student?')\">Delete</a>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='5' class='text-center'>No students found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</body>
</html>
