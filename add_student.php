<?php
// Include the database connection file
include('db_connect.php');

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $rollno = $conn->real_escape_string($_POST['rollno']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $section = $conn->real_escape_string($_POST['section']);
    $attendance = $conn->real_escape_string($_POST['attendance']);
    $subject1 = $conn->real_escape_string($_POST['subject1']);
    $subject2 = $conn->real_escape_string($_POST['subject2']);
    $subject3 = $conn->real_escape_string($_POST['subject3']);
    $subject4 = $conn->real_escape_string($_POST['subject4']);
    $subject5 = $conn->real_escape_string($_POST['subject5']);

    // Calculate the total, average, and percentage
    $total = $subject1 + $subject2 + $subject3 + $subject4 + $subject5;
    $average = $total / 5;
    $percentage = ($total / 500) * 100;

    // Insert data into database
    $sql = "INSERT INTO studentdet (name,rollno, dob, section, attendance, s1, s2, s3, s4, s5, total, avg, percentage) 
            VALUES ('$name','$rollno', '$dob', '$section', '$attendance', '$subject1', '$subject2', '$subject3', '$subject4', '$subject5', '$total', '$average', '$percentage')";

    if ($conn->query($sql) === TRUE) {
        // Display SweetAlert after successful insertion
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'The student $name has been added.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
