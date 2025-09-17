<?php
include("config.php");
session_start();

// ✅ Session check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // logged-in user

// ✅ Add Course (Prepared Statement)
if (isset($_POST['add_course'])) {
    $course_name = trim($_POST['course_name']);
    $description = trim($_POST['description']);

    if (!empty($course_name)) {
        $stmt = $conn->prepare("INSERT INTO courses (course_name, description, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $course_name, $description, $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = "✅ Course added successfully!";
    } else {
        $_SESSION['message'] = "⚠️ Course name is required!";
    }
    header("Location: courses.php");
    exit;
}

// ✅ Delete Course (Prepared Statement + Ownership Check)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "🗑 Course deleted successfully!";
    header("Location: courses.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- ✅ Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">SIS Dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="students.php">Students</a></li>
          <li class="nav-item"><a class="nav-link active" href="courses.php">Courses</a></li>
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
    <h2 class="mb-4">📚 Manage Courses</h2>

    <!-- ✅ Display Messages -->
    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php 
          echo $_SESSION['message']; 
          unset($_SESSION['message']); 
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Add Course Form -->
    <form method="POST" class="row g-3 mb-4">
      <div class="col-md-5">
        <input type="text" name="course_name" class="form-control" placeholder="Course Name" required>
      </div>
      <div class="col-md-5">
        <input type="text" name="description" class="form-control" placeholder="Description">
      </div>
      <div class="col-md-2">
        <button type="submit" name="add_course" class="btn btn-success w-100">Add</button>
      </div>
    </form>

    <!-- Courses Table -->
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white">Course List</div>
      <div class="card-body">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Course Name</th>
              <th>Description</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $conn->prepare("SELECT * FROM courses WHERE user_id = ? ORDER BY course_id DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['course_id']}</td>
                        <td>{$row['course_name']}</td>
                        <td>{$row['description']}</td>
                        <td>
                          <a href='courses.php?delete={$row['course_id']}'
                             class='btn btn-danger btn-sm'
                             onclick=\"return confirm('Are you sure you want to delete this course?')\">Delete</a>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='4' class='text-center'>No courses found</td></tr>";
            }

            $stmt->close();
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
