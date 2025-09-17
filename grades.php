<?php
include("config.php");
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Add Grade
if (isset($_POST['add_grade'])) {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $grade      = $_POST['grade'];

    $conn->query("INSERT INTO grades (student_id, subject_id, grade, user_id) 
                  VALUES ('$student_id','$subject_id','$grade','$user_id')");
    header("Location: grades.php");
    exit;
}

// Delete Grade
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM grades WHERE grade_id=$id AND user_id=$user_id");
    header("Location: grades.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Grades</title>
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
          <li class="nav-item"><a class="nav-link" href="enrollments.php">Enrollments</a></li>
          <li class="nav-item"><a class="nav-link active" href="grades.php">Grades</a></li>
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
    <h2 class="mb-4">📑 Manage Grades</h2>

    <!-- Add Grade Form -->
    <form method="POST" class="row g-3 mb-4">
      <div class="col-md-4">
        <select name="student_id" class="form-select" required>
          <option value="">-- Select Student --</option>
          <?php
          $students = $conn->query("SELECT * FROM students WHERE user_id='$user_id'");
          while ($s = $students->fetch_assoc()) {
              echo "<option value='{$s['student_id']}'>{$s['first_name']} {$s['last_name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-4">
        <select name="subject_id" class="form-select" required>
          <option value="">-- Select Subject --</option>
          <?php
          $subjects = $conn->query("SELECT * FROM subjects WHERE user_id='$user_id'");
          while ($sub = $subjects->fetch_assoc()) {
              echo "<option value='{$sub['subject_id']}'>{$sub['subject_name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-2">
        <input type="text" name="grade" class="form-control" placeholder="Grade (e.g. 95)" required>
      </div>
      <div class="col-md-2">
        <button type="submit" name="add_grade" class="btn btn-primary w-100">Add</button>
      </div>
    </form>

    <!-- Grades Table -->
    <div class="card">
      <div class="card-header bg-dark text-white">Grade List</div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Student</th>
              <th>Subject</th>
              <th>Grade</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT g.grade_id, s.first_name, s.last_name, sub.subject_name, g.grade
                    FROM grades g
                    JOIN students s ON g.student_id = s.student_id
                    JOIN subjects sub ON g.subject_id = sub.subject_id
                    WHERE g.user_id='$user_id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['grade_id']}</td>
                        <td>{$row['first_name']} {$row['last_name']}</td>
                        <td>{$row['subject_name']}</td>
                        <td>{$row['grade']}</td>
                        <td>
                          <a href='grades.php?delete={$row['grade_id']}'
                             class='btn btn-danger btn-sm'
                             onclick=\"return confirm('Delete this grade?')\">Delete</a>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='5' class='text-center'>No grades recorded</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</body>
</html>
