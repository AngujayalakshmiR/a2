<?php 
// Start the session
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");

// Check if the student is logged in
// Check if the faculty is logged in
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

// Get student details
$student_id = $_SESSION['student_id'];
$dob = $_SESSION['dob'];

$query = "SELECT * FROM studentdet WHERE rollno = ? AND dob = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $student_id, $dob);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student_details = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Performance</title>
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
            flex-direction: column;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #e74c3c;
            color: white;
            position: fixed;
            padding-top: 10px;
            z-index: 999;
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
            margin-top: auto;
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

        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.collapsed {
                left: 0;
            }
        }

        /* For cards container spacing */
        .card-columns {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

    </style>
</head>
<body>
<div class="sidebar" id="sidebar">
    <h4 class="text-center w-100"><b>Student Panel</b></h4><br><br>
    <a href="studentdashboard.php"><i class="fas fa-tachometer-alt fa-2x"></i> <span class="menu-text"><b>Dashboard</b></span></a>
    <a href="marks.php" class="active"><i class="fas fa-user-graduate fa-2x"></i> <span class="menu-text"><b>Exam Performance</b></span></a>
</div>

<div class="main-content" id="mainContent">
    <div class="header d-flex align-items-center">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-danger" onclick="toggleSidebar()">&#9776;</button>&nbsp
            <span class="navbar-brand mb-0 h5"><b>Exam Performance</b></span>
        </div>
        <!-- Logout Button -->
        <a href="javascript:void(0);" id="logoutBtn">
            <i class="fas fa-sign-out-alt"></i>
            <span class="menu-text">Logout</span>
        </a>
    </div>

    <div class="content">
        <div class="card-columns">
            <?php 
                $colors = ['#e74c3c', '#3498db', '#2ecc71', '#f1c40f', '#9b59b6'];
                for ($i = 1; $i <= 5; $i++) {
                    echo "<div class='card p-3 card-hover' style='background-color: {$colors[$i-1]};color:white;'>
                            <h5>Subject $i</h5>
                            <h2>{$student_details['s' . $i]}</h2>
                          </div>";
                }
            ?>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card p-3 card-hover" style="background-color: #1abc9c;color:white;">
                    <h5>Total</h5>
                    <h2><?php echo $student_details['total']; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 card-hover" style="background-color: #e67e22;color:white;">
                    <h5>Average</h5>
                    <h2><?php echo $student_details['avg']; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 card-hover" style="background-color: #34495e;color:white;">
                    <h5>Percentage</h5>
                    <h2><?php echo $student_details['percentage']; ?>%</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p><b>&copy; 2025 Student Dashboard</b></p>
    </div>
</div>

<script>
    function toggleSidebar() {
        var sidebar = document.getElementById("sidebar");
        var mainContent = document.getElementById("mainContent");
        sidebar.classList.toggle("collapsed");

        if (sidebar.classList.contains("collapsed")) {
            sidebar.style.left = "-250px";
            mainContent.style.marginLeft = "0";
        } else {
            sidebar.style.left = "0";
            mainContent.style.marginLeft = "250px";
        }
    }

    document.getElementById("logoutBtn").addEventListener("click", function() {
        Swal.fire({
            title: 'Are you sure you want to logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php";
            }
        });
    });
</script>

</body>
</html>
