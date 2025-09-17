<?php 
include 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // ✅ Current logged-in user
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Information System</title>
  <!-- ✅ Bootstrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">SIS Dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="students.php">Students</a></li>
          <li class="nav-item"><a class="nav-link" href="courses.php">Courses</a></li>
          <li class="nav-item"><a class="nav-link" href="subjects.php">Subjects</a></li>
          <li class="nav-item"><a class="nav-link" href="enrollments.php">Enrollments</a></li>
          <li class="nav-item"><a class="nav-link" href="grades.php">Grades</a></li>
        </ul>

        <!-- ✅ Profile + Logout -->
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

  <!-- Dashboard Content -->
  <div class="container mt-4">
    <h2 class="mb-4">📊 Dashboard Overview</h2>
    <div class="row g-3">
      <!-- Students -->
      <div class="col-md-3">
        <div class="card text-bg-primary shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Students</h5>
            <?php
              $result = $conn->query("SELECT COUNT(*) AS total FROM students WHERE user_id = $user_id");
              $row = $result->fetch_assoc();
              echo "<h3>" . $row['total'] . "</h3>";
            ?>
          </div>
        </div>
      </div>
      <!-- Courses -->
      <div class="col-md-3">
        <div class="card text-bg-success shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Courses</h5>
            <?php
              $result = $conn->query("SELECT COUNT(*) AS total FROM courses WHERE user_id = $user_id");
              $row = $result->fetch_assoc();
              echo "<h3>" . $row['total'] . "</h3>";
            ?>
          </div>
        </div>
      </div>
      <!-- Subjects -->
      <div class="col-md-3">
        <div class="card text-bg-warning shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Subjects</h5>
            <?php
              $result = $conn->query("SELECT COUNT(*) AS total FROM subjects WHERE user_id = $user_id");
              $row = $result->fetch_assoc();
              echo "<h3>" . $row['total'] . "</h3>";
            ?>
          </div>
        </div>
      </div>
      <!-- Enrollments -->
      <div class="col-md-3">
        <div class="card text-bg-danger shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Enrollments</h5>
            <?php
              $result = $conn->query("SELECT COUNT(*) AS total FROM enrollments WHERE user_id = $user_id");
              $row = $result->fetch_assoc();
              echo "<h3>" . $row['total'] . "</h3>";
            ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Enrollments -->
    <div class="card mt-4 shadow">
      <div class="card-header bg-dark text-white">
        Recent Enrollments
      </div>
      <div class="card-body">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>Student</th>
              <th>Subject</th>
              <th>Semester</th>
              <th>School Year</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sql = "SELECT s.first_name, s.last_name, sub.subject_name, e.semester, e.school_year
                      FROM enrollments e
                      JOIN students s ON e.student_id = s.student_id
                      JOIN subjects sub ON e.subject_id = sub.subject_id
                      WHERE e.user_id = $user_id
                      ORDER BY e.enrollment_id DESC LIMIT 5";
              $result = $conn->query($sql); 
              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>".$row['first_name']." ".$row['last_name']."</td>
                          <td>".$row['subject_name']."</td>
                          <td>".$row['semester']."</td>
                          <td>".$row['school_year']."</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='4' class='text-center'>No recent enrollments</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</body>
</html>
