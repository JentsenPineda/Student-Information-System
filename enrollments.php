<?php
include("config.php");
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Add Enrollment
if (isset($_POST['add_enrollment'])) {
    $student_id  = $_POST['student_id'];
    $subject_id  = $_POST['subject_id'];
    $semester    = $_POST['semester'];
    $school_year = $_POST['school_year'];

    $conn->query("INSERT INTO enrollments (student_id, subject_id, semester, school_year, user_id) 
                  VALUES ('$student_id','$subject_id','$semester','$school_year','$user_id')");
    header("Location: enrollments.php");
    exit;
}

// Delete Enrollment
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM enrollments WHERE enrollment_id=$id AND user_id=$user_id");
    header("Location: enrollments.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Enrollments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">SIS Dashboard</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="students.php">Students</a></li>
          <li class="nav-item"><a class="nav-link" href="courses.php">Courses</a></li>
          <li class="nav-item"><a class="nav-link" href="subjects.php">Subjects</a></li>
          <li class="nav-item"><a class="nav-link active" href="enrollments.php">Enrollments</a></li>
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
    <h2 class="mb-4">📝 Manage Enrollments</h2>

    <!-- Add Enrollment Form -->
    <form method="POST" class="row g-3 mb-4">
      <div class="col-md-3">
        <select name="student_id" class="form-select" required>
          <option value="">-- Select Student --</option>
          <?php
          // ✅ Only show students of this user
          $students = $conn->query("SELECT * FROM students WHERE user_id='$user_id'");
          while ($s = $students->fetch_assoc()) {
              echo "<option value='{$s['student_id']}'>{$s['first_name']} {$s['last_name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-3">
        <select name="subject_id" class="form-select" required>
          <option value="">-- Select Subject --</option>
          <?php
          // ✅ Only show subjects of this user
          $subjects = $conn->query("SELECT * FROM subjects WHERE user_id='$user_id'");
          while ($sub = $subjects->fetch_assoc()) {
              echo "<option value='{$sub['subject_id']}'>{$sub['subject_name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-2">
        <input type="text" name="semester" class="form-control" placeholder="Semester (e.g. 1st)" required>
      </div>
      <div class="col-md-2">
        <input type="text" name="school_year" class="form-control" placeholder="e.g. 2024-2025" required>
      </div>
      <div class="col-md-2">
        <button type="submit" name="add_enrollment" class="btn btn-success w-100">Enroll</button>
      </div>
    </form>

    <!-- Enrollments Table -->
    <div class="card">
      <div class="card-header bg-dark text-white">Enrollment List</div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Student</th>
              <th>Subject</th>
              <th>Semester</th>
              <th>School Year</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // ✅ Only show enrollments belonging to this user
            $sql = "SELECT e.enrollment_id, s.first_name, s.last_name, sub.subject_name, 
                           e.semester, e.school_year
                    FROM enrollments e
                    JOIN students s ON e.student_id = s.student_id
                    JOIN subjects sub ON e.subject_id = sub.subject_id
                    WHERE e.user_id='$user_id' AND s.user_id='$user_id' AND sub.user_id='$user_id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['enrollment_id']}</td>
                        <td>{$row['first_name']} {$row['last_name']}</td>
                        <td>{$row['subject_name']}</td>
                        <td>{$row['semester']}</td>
                        <td>{$row['school_year']}</td>
                        <td>
                          <a href='enrollments.php?delete={$row['enrollment_id']}'
                             class='btn btn-danger btn-sm'
                             onclick=\"return confirm('Delete this enrollment?')\">Delete</a>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='6' class='text-center'>No enrollments found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
