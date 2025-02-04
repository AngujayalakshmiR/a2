<!-- <?php
//include("connection.php");
//$sql="SELECT * FROM crud_operation";
//$result=mysqli_query($conn,$sql);
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>CRUD Operations</title>
    <style>
    
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 40px; /* Adjust the value as needed */
    }
    

    /* Custom CSS for the DataTable search bar */
.dataTables_wrapper .dataTables_filter input {
    border: 2px solid #007BFF; /* Blue border */
    border-radius: 20px; /* Rounded corners */
    padding: 5px 15px; /* Padding inside the search input */
    font-size: 16px; /* Font size */
    color: #333; /* Text color */
    background-color: #f0f0f0; /* Light grey background */
    margin-left: 10px; /* Space between label and input */
}

/* Adjust the search bar placement and spacing */
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 40px; /* Space below the search bar */
    margin-right: 10px; /* Adjust alignment */
    text-align: right; /* Align search bar to the right */
}
/* Custom CSS for DataTable pagination buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 5px 10px; /* Padding inside buttons */
    margin: 2px; /* Space between buttons */
    border-radius: 5px; /* Rounded corners */
    background-color: #007BFF; /* Blue background */
    color: #fff !important; /* White text color */
    border: 1px solid #007BFF; /* Border color */
}

/* Hover effect for the pagination buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #0056b3; /* Darker blue on hover */
    border-color: #0056b3; /* Border color on hover */
}

/* Active button styling */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #28a745 !important; /* Green background for active page */
    color: #fff !important; /* White text color */
    border: 1px solid #28a745; /* Border color */
}

/* Disabled button styling */
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    background-color: #e9ecef; /* Light grey background */
    color: #6c757d !important; /* Grey text color */
    border-color: #e9ecef; /* Border color */
}



.modal-header {
    background-color: #1a73e8;
    color: white;
}

.modal-footer .btn-primary {
    background-color: #34a853;
    border-color: #34a853;
}

.modal-footer .btn-secondary {
    background-color: #f5f5f5;
    color: #202124;
    border-color: #dadce0;
}

</style>
</head>
<body>
    <br>
    <div class="container">
    <h1 style="text-align:center;color:red;"><b>CRUD Operations</b></h1>

    <button type="button" class="btn btn-dark float-end" data-bs-toggle="modal" data-bs-target="#adduser">AddUser</button>

  <!-- Datatable starts-->
<div class="container table-responsive py-5" > 
<table class="table table-bordered table-hover"   id="user">
  <thead class="thead" style="background-color:black;color:white;">
    <tr>
      <th scope="col">S.no</th>
      <th scope="col">Name</th>
      <th scope="col">Roll No</th>
      <th scope="col">Year</th>
      <th scope="col">Semester</th>
      <th scope="col">Department</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
       
    <tr>
      <td scope="row"></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
     <td><button type="button" class="btn btn-primary btnuseredit" data-id="" style="margin-right:10px;">Edit</button>

     <button type="button"  value="" class="btn btn-danger btnuserdelete" style="margin-right:10px;">Delete</button>
    
    </td>
    </tr>
                   
  </tbody>
</table>

</div>
<!-- Edit User Modal -->
<div class="modal fade" id="edituser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="edituserform">
        <div class="modal-body">
          <input type="hidden" name="user_id" id="edit_user_id">
          <input type="text" class="form-control" name="name" id="edit_name" placeholder="Enter Name" required><br>
          <input type="text" class="form-control" name="rollno" id="edit_rollno" placeholder="Enter Roll No" required><br>
          <input type="text" class="form-control" name="year" id="edit_year" placeholder="Enter Year" required><br>
          <input type="text" class="form-control" name="sem" id="edit_sem" placeholder="Enter Semester" required><br>
          <input type="text" class="form-control" name="dept" id="edit_dept" placeholder="Enter Department" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Add User Modal -->
<div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addnewuser"><center>
      <div class="modal-body" >
        
        <input type="text" style="border-color:blue;padding:6px;" name="name" placeholder="enter Name" required><br><br>
        <input type="text" style="border-color:blue;padding:6px;" name="rollno" placeholder="enter Roll No" required><br><br>

        <input type="text" style="border-color:blue;padding:6px;" name="year" placeholder="enter Year" required><br><br>

        <input type="text" style="border-color:blue;padding:6px;" name="sem" placeholder="enter Semester" required><br><br>

        <input type="text" style="border-color:blue;padding:6px;" name="dept" placeholder="enter Department" required>
      </div></center>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!--script for datatable-->
<script>
  $(document).ready(function() {
    $('#user').DataTable();
});


//script for addUser Data
$(document).on('submit', '#addnewuser', function (e) {
      e.preventDefault();
      var formData = new FormData(this);
      console.log(formData);
      formData.append("save_newuser", true);
      $.ajax({
        type: "POST",
        url: "backend.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {

          var res = jQuery.parseJSON(response);
          console.log(res)
          if (res.status == 200) {
            $('#adduser').modal('hide');
            $('#addnewuser')[0].reset();
           $('#user').load(location.href + " #user");
           swal("Good job!", "New user is successfully added!", "success");

          }
          else if (res.status == 500) {
            $('#adduser').modal('hide');
            $('#addnewuser')[0].reset();
            console.error("Error:", res.message);
            alert("Something Went wrong.! try again")
          }
        }
      });

    });


// script for deleting table
$(document).on('click', '.btnuserdelete', function (e) {
      e.preventDefault();

      if (confirm('Are you sure you want to delete this data?')) {
        var user_id = $(this).val();
        $.ajax({
          type: "POST",
          url: "backend.php",
          data: {
            'delete_user': true,
            'user_id': user_id
          },
          success: function (response) {

            var res = jQuery.parseJSON(response);
            if (res.status == 500) {
              alert(res.message);
            }
            else {
              $('#user').load(location.href + " #user");
              swal("Successfully deleted","", "success");
            }
          }
        });
      }
    });



    // Script to open the Edit Modal and populate the fields
$(document).on('click', '.btnuseredit', function () {
    var user_id = $(this).data('id');
    
    $.ajax({
        type: "GET",
        url: "backend.php", // Create this file to fetch the data
        data: {user_id: user_id},
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 200) {
                $('#edit_user_id').val(res.data.ID);
                $('#edit_name').val(res.data.Name);
                $('#edit_rollno').val(res.data.RollNo);
                $('#edit_year').val(res.data.Year);
                $('#edit_sem').val(res.data.Semester);
                $('#edit_dept').val(res.data.Depatment);
                $('#edituser').modal('show');
            } else {
                alert(res.message);
            }
        }
    });
});

// Script to update user data
$(document).on('submit', '#edituserform', function (e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    formData.append("update_user", true);

    $.ajax({
        type: "POST",
        url: "backend.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 200) {
                $('#edituser').modal('hide');
                $('#edituserform')[0].reset();
                $('#user').load(location.href + " #user");
                alert(res.message);
            } else {
                alert(res.message);
            }
        }
    });
});


</script>
</body>

</html>
