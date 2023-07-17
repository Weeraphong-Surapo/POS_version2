<?php 
    session_start();
    include('config/connect.php');
      $employee = $conn->query("SELECT * FROM tb_employee WHERE employee_id = '$_SESSION[employee_id]'");
      if($employee->num_rows < 1){
        echo '<script>window.location.href="src/login.php"</script>';
      }
      $row_employee = $employee->fetch_array();
      $fullname = $row_employee['fname'].' '.$row_employee['lname'];
      $img = $row_employee['user_img'];
  
      $querys = $conn->query("SELECT * FROM tb_shop");
      $logo_shop = $querys->fetch_array();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ระบบขายสินค้า POS</title>
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.1.2/dist/axios.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <!-- <script src="assets/js/jquery-3.3.1.js"></script> -->
  <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css">

  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
  <!-- plugins:css -->
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css" />
  <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="assets/images/favicon.png" />


  <script>
    $(document).ready(function() {
      $('#tableExport').DataTable({
        dom: 'Bfrtip',
        buttons: [
          'copy', 'excel', 'print'
        ]
      });
    });
  </script>
</head>

<body>
<div id="element">
  <div class="container-scroller">