<
<?php 
// Start the session
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");

// Check if logout is requested
if (isset($_GET['logout'])) {
    // Destroy the session to log out
    session_unset();  // Unset all session variables
    session_destroy();  // Destroy the session
    header("Location: studentlogin.php");  // Redirect to student login page
    exit();
}


// Include database connection file
include("db_connect.php");

// Check if the session variables are set
if (!isset($_SESSION['student_id']) || !isset($_SESSION['dob'])) {
    // Redirect to login page if session variables are not set
    header("Location: studentlogin.php");
    exit();
}

// Get the student ID and dob from session
$student_id = $_SESSION['student_id'];
$dob = $_SESSION['dob'];

// Prepared statement to fetch student details based on rollno and dob
$query = "SELECT * FROM studentdet WHERE rollno = ? AND dob = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $student_id, $dob);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch the student's details
$student_details = mysqli_fetch_assoc($result);

// Get the section from student's details
$section = $student_details['section'];

// Now, we need to fetch the faculty advisor's name based on the section from facultydet table
$query_faculty = "SELECT name FROM facultydet WHERE section = ? LIMIT 1";
$stmt_faculty = mysqli_prepare($conn, $query_faculty);
mysqli_stmt_bind_param($stmt_faculty, "s", $section);
mysqli_stmt_execute($stmt_faculty);
$result_faculty = mysqli_stmt_get_result($stmt_faculty);

// Fetch the faculty details
$faculty_details = mysqli_fetch_assoc($result_faculty);
$faculty_name = $faculty_details ? $faculty_details['name'] : "Not Assigned";

// Close the statement and database connection
mysqli_stmt_close($stmt);
mysqli_stmt_close($stmt_faculty);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s;
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
            flex: 1;
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 15px;
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

        .card-hover {
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .card-hover h5 {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .card-hover h2 {
            font-size: 2rem;
            font-weight: bold;
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

    </style>
</head>
<body>
  <!-- Sidebar -->
<div class="sidebar" id="sidebar"><br>
    <h4 class="text-center w-100"><b>Student Panel</b></h4><br><br>
    <a href="studentlogin.php" class="active"><i class="fas fa-tachometer-alt fa-2x"></i> <span class="menu-text"><b>Dashboard</b></span></a>
    <a href="marks.php"><i class="fas fa-user-graduate fa-2x"></i> <span class="menu-text"><b>Exam Performance</b></span></a>
</div>

       <!-- Main Content Section -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <div class="header d-flex align-items-center">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-danger" onclick="toggleSidebar()">&#9776;</button>&nbsp
                <span class="navbar-brand mb-0 h5"><b>Dashboard</b></span>
            </div>
            <!-- Logout Button -->
            <a href="javascript:void(0);" id="logoutBtn">
            <i class="fas fa-sign-out-alt"></i>
            <span class="menu-text">Logout</span>
        </a>
    
    </div>

      <!-- Page Content -->
<div class="content">
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3 card-hover" style="background-color: #2980b9; color: white;">
                <h5>Student Name</h5>
                <h2><?php echo $student_details['name']; ?></h2>
            </div>
        </div><br>
        <div class="col-md-4">
            <div class="card p-3 card-hover" style="background-color: #f39c12; color: white;">
                <h5>Student ID</h5>
                <h2><?php echo $student_details['rollno']; ?></h2>
            </div>
        </div><br>
        <div class="col-md-4">
            <div class="card p-3 card-hover" style="background-color: #45b39d; color: white;">
                <h5>DOB</h5>
                <h2><?php echo $student_details['dob']; ?></h2>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3 card-hover" style="background-color: #8e44ad; color: white;">
                <h5>Section</h5>
                <h2><?php echo $student_details['section']; ?></h2>
            </div>
        </div><br>
        <div class="col-md-4">
            <div class="card p-3 card-hover" style="background-color: #16a085; color: white;">
                <h5>Class Advisor</h5>
                <h2><?php echo $faculty_name; ?></h2>
            </div>
        </div><br>
        <div class="col-md-4">
            <div class="card p-3 card-hover" style="background-color: #f1c40f; color: white;">
                <h5>Attendance Percentage</h5>
                <h2><?php echo $student_details['attendance']; ?>%</h2>
            </div>
        </div>
    </div>
</div>

        <!-- Footer -->
        <div class="footer">
            <p><b>&copy; 2025 Student Dashboard</b></p>
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

    function logout() {
        // Add your logout functionality here
        alert("Logged out successfully!");
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
</body>
</html>
