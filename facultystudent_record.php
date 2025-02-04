<?php
// Start the session
session_start();

// Include database connection file
include('db_connect.php');

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");

// Check if the faculty is logged in
if (!isset($_SESSION['faculty_id'])) {
    // If not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

// Check if logout is requested
if (isset($_GET['logout'])) {
    // Destroy the session to log out
    session_destroy();
    header("Location: index.php");
    exit();
}

// Retrieve faculty details
$faculty_id = $conn->real_escape_string($_SESSION['faculty_id']); // Sanitize input
$sql = "SELECT * FROM facultydet WHERE rollno = '$faculty_id'"; // Use quotes around the value
$result = $conn->query($sql);

if ($result === false) {
    // Output the error if the query fails
    echo "Error: " . $conn->error;
    exit();
}

$faculty = $result->fetch_assoc();
if (!$faculty) {
    echo "No faculty found with the given ID.";
    exit();
}

// Fetch the faculty's section
$faculty_section = $faculty['section'];

// Fetch students from the same section
$sql_students = "SELECT * FROM studentdet WHERE section = '$faculty_section'";
$result_students = $conn->query($sql_students);

if ($result_students === false) {
    // Output the error if the query fails
    echo "Error: " . $conn->error;
    exit();
}
?>

<!-- Rest of your HTML and PHP code here -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- Bootstrap 5.0.2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #e74c3c;
            color: white;
            position: fixed;
            padding-top: 10px;
            transition: all 0.3s;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding-right: 10px;
            z-index: 999; /* Ensure it stays above content */
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            text-align: left;
            width: 100%;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .menu-text {
            display: inline-block;
        }
        .sidebar.collapsed .menu-text {
            display: none;
        }
        .main-content {
            flex: 1;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }
        .header {
            background-color: #ffffff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 10;
            position: relative;
        }
        .content {
            padding: 20px;
            flex: 1;
        }
        .footer {
            background-color: #2980b9;
            color: white;
            padding: 10px;
            text-align: center;
            width: 100%;
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
                top: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.collapsed {
                left: 0;
            }
        }

        /* Active link styling */
        .sidebar a.active {
            background-color: #c0392b; /* Red background for active item */
            color: white; /* White text color */
            font-weight: bold; /* Bold text */
        }

        .sidebar a.active:hover {
            background-color: #c0392b; /* Darker red on hover for active item */
        }
    /* Gradient button */
    .btn-gradient {
    background: linear-gradient(to right, #00c6ff, #0072ff); /* Sky Blue to Blue gradient */
    border: none;
    color: white;
}


        .table-responsive {
            overflow-x: auto !important;
            max-width: 100% !important;
            white-space: nowrap !important;
            display: block !important;
        }
   
    </style>
</head>
<body>
 <!-- Sidebar -->
 <div class="sidebar" id="sidebar"><br>
      <h4 class="text-center w-100"><b>Faculty Panel</b></h4><br><br>
      <a href="facultydashboard.php" ><i class="fas fa-tachometer-alt fa-2x"></i> <span class="menu-text"><b>Dashboard</b></span></a>
      <a href="facultystudent_record.php" class="active"><i class="fas fa-user-graduate fa-2x"></i> <span class="menu-text"><b>Student Records</b></span></a>
  </div>

  <!-- Main Content Section -->
  <div class="main-content" id="mainContent">
      <!-- Header -->
      <div class="header d-flex align-items-center">
          <div class="d-flex align-items-center gap-2">
              <button class="btn btn-danger" onclick="toggleSidebar()">&#9776;</button>&nbsp
              <span class="navbar-brand mb-0 h5"><b>Student Records</b></span>
          </div>
          <!-- Logout Button -->
          <a href="javascript:void(0);" id="logoutBtn">
              <i class="fas fa-sign-out-alt"></i>
              <span class="menu-text">Logout</span>
          </a>
      </div>

      <!-- Navigation Section -->
      <div class="content">
          <!-- Add Student Button -->
          <button class="btn btn-gradient mb-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">
              <i class="fas fa-user-plus"></i> Add Student
          </button><br><br>


        <div class="container mt-4">
            <div class="table-responsive">
                <table id="studentTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>RollNo</th>
                            <th>DOB</th>
                            <th>Section</th>
                            <th>Attendance</th>
                            <th>Subject1</th>
                            <th>Subject2</th>
                            <th>Subject3</th>
                            <th>Subject4</th>
                            <th>Subject5</th>
                            <th>Total</th>
                            <th>Avg</th>
                            <th>Percentage</th>
                        </tr>
                        </thead>
            <tbody>
                <?php
                if ($result_students->num_rows > 0) {
                    $counter = 1;
                    while ($student = $result_students->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $counter++ . "</td>";
                        echo "<td>" . $student['name'] . "</td>";
                        echo "<td>" . $student['rollno'] . "</td>";
                        echo "<td>" . $student['dob'] . "</td>";
                        echo "<td>" . $student['section'] . "</td>";
                        echo "<td>" . $student['attendance'] . "</td>";
                        echo "<td>" . $student['s1'] . "</td>";
                        echo "<td>" . $student['s2'] . "</td>";
                        echo "<td>" . $student['s3'] . "</td>";
                        echo "<td>" . $student['s4'] . "</td>";
                        echo "<td>" . $student['s5'] . "</td>";
                        echo "<td>" . $student['total'] . "</td>";
                        echo "<td>" . $student['avg'] . "</td>";
                        echo "<td>" . $student['percentage'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='14' class='text-center'>No students found in this section</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

      </div>

      <!-- Footer -->
      <div class="footer">
          <p><b>&copy; 2025 Faculty Dashboard</b></p>
      </div>
  </div>

  <!-- Modal for Add Student -->
  <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="addStudentModalLabel">Add Student Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form action="add_student.php" method="POST">
                      <div class="mb-3">
                          <label for="studentName" class="form-label">Name</label>
                          <input type="text" class="form-control" id="studentName" name="name" required>
                      </div>
                      <div class="mb-3">
                          <label for="rollno" class="form-label">RollNo</label>
                          <input type="text" class="form-control" id="rollno" name="rollno" required>
                      </div>
                      <div class="mb-3">
                          <label for="dob" class="form-label">Date of Birth</label>
                          <input type="date" class="form-control" id="dob" name="dob" required>
                      </div>
                      <div class="mb-3">
                          <label for="section" class="form-label">Section</label>
                          <input type="text" class="form-control" id="section" name="section" required>
                      </div>
                      <div class="mb-3">
                          <label for="attendance" class="form-label">Attendance</label>
                          <input type="number" class="form-control" id="attendance" name="attendance" required>
                      </div>
                      <div class="mb-3">
                          <label for="subject1" class="form-label">Subject 1 Mark</label>
                          <input type="number" class="form-control" id="subject1" name="subject1" required>
                      </div>
                      <div class="mb-3">
                          <label for="subject2" class="form-label">Subject 2 Mark</label>
                          <input type="number" class="form-control" id="subject2" name="subject2" required>
                      </div>
                      <div class="mb-3">
                          <label for="subject3" class="form-label">Subject 3 Mark</label>
                          <input type="number" class="form-control" id="subject3" name="subject3" required>
                      </div>
                      <div class="mb-3">
                          <label for="subject4" class="form-label">Subject 4 Mark</label>
                          <input type="number" class="form-control" id="subject4" name="subject4" required>
                      </div>
                      <div class="mb-3">
                          <label for="subject5" class="form-label">Subject 5 Mark</label>
                          <input type="number" class="form-control" id="subject5" name="subject5" required>
                      </div>
                      <button type="submit" class="btn btn-primary">Save Student</button>
                  </form>
              </div>
          </div>
      </div>
  </div>
  <script>
    function toggleSidebar() {
        var sidebar = document.getElementById("sidebar");
        var mainContent = document.getElementById("mainContent");
        sidebar.classList.toggle("collapsed");
        
        // Apply the changes for mobile view
        if (sidebar.classList.contains("collapsed")) {
            sidebar.style.left = "-250px";
            mainContent.style.marginLeft = "0"; // Allow content to occupy full width
        } else {
            sidebar.style.left = "0";
            mainContent.style.marginLeft = "250px"; // Restore margin for sidebar space
        }
    }

 
    </script>
  <script>
      document.getElementById("logoutBtn").addEventListener("click", function() {
          Swal.fire({
              title: 'Are you sure you want to logout?',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, logout!',
              cancelButtonText: 'No, cancel',
          }).then((result) => {
              if (result.isConfirmed) {
                  // Redirect to logout.php to destroy session
                  window.location.href = "logout.php";
              }
          });
      });
  </script>

<script>
    document.querySelector("form").addEventListener("submit", function(event) {
        let subject1 = document.getElementById('subject1').value;
        let subject2 = document.getElementById('subject2').value;
        let subject3 = document.getElementById('subject3').value;
        let subject4 = document.getElementById('subject4').value;
        let subject5 = document.getElementById('subject5').value;
        
        // Check if marks are valid
        if (subject1 < 0 || subject1 > 100 || subject2 < 0 || subject2 > 100 || subject3 < 0 || subject3 > 100 || subject4 < 0 || subject4 > 100 || subject5 < 0 || subject5 > 100) {
            event.preventDefault(); // Prevent form submission
            alert("Marks should be between 0 and 100.");
        }
    });
</script>
<!-- jQuery (Required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function() {
        $('#studentTable').DataTable({
    "responsive": true,
    "autoWidth": true,
    "scrollX": true
    });

    });
</script>

</body>
</html>