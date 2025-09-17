<?php
include("config.php");
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Add Subject
if (isset($_POST['add_subject'])) {
    $subject_name = $_POST['subject_name'];
    $course_id    = $_POST['course_id'];

    $conn->query("INSERT INTO subjects (subject_name, course_id, user_id) 
                  VALUES ('$subject_name','$course_id','$user_id')");
    header("Location: subjects.php");
    exit;
}

// Delete Subject
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM subjects WHERE subject_id=$id AND user_id=$user_id");
    header("Location: subjects.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Subjects</title>
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
          <li class="nav-item"><a class="nav-link active" href="subjects.php">Subjects</a></li>
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
    <h2 class="mb-4">📖 Manage Subjects</h2>

    <!-- Add Subject Form -->
    <form method="POST" class="row g-3 mb-4">
      <div class="col-md-5">
        <input type="text" name="subject_name" class="form-control" placeholder="Subject Name" required>
      </div>
      <div class="col-md-5">
        <select name="course_id" class="form-select" required>
          <option value="">-- Select Course --</option>
          <?php
          // ✅ Only show courses of logged-in user
          $courses = $conn->query("SELECT * FROM courses WHERE user_id='$user_id'");
          while ($c = $courses->fetch_assoc()) {
              echo "<option value='{$c['course_id']}'>{$c['course_name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" name="add_subject" class="btn btn-warning w-100">Add</button>
      </div>
    </form>

    <!-- Subjects Table -->
    <div class="card">
      <div class="card-header bg-dark text-white">Subject List</div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Subject Name</th>
              <th>Course</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // ✅ Only show subjects of logged-in user
            $sql = "SELECT s.subject_id, s.subject_name, c.course_name 
                    FROM subjects s 
                    JOIN courses c ON s.course_id = c.course_id 
                    WHERE s.user_id='$user_id' AND c.user_id='$user_id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['subject_id']}</td>
                        <td>{$row['subject_name']}</td>
                        <td>{$row['course_name']}</td>
                        <td>
                          <a href='subjects.php?delete={$row['subject_id']}'
                             class='btn btn-danger btn-sm'
                             onclick=\"return confirm('Delete this subject?')\">Delete</a>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='4' class='text-center'>No subjects found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
