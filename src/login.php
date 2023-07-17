<?php
include("../config/connect.php");
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
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <img src="../assets/upload_logo/<?= $logo_shop['shop_img'] ?>" style="width: 60px;">
                                <span class="text-black"><?= $logo_shop['shop_name'] ?></span>
                            </div>
                            <form class="pt-3" id="FormLogin">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" id="username" placeholder="ชื่อผู้ใช้">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" id="password" placeholder="รหัสผ่าน">
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">เข้าสู่ระบบ</button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/misc.js"></script>
    <!-- endinject -->
</body>

</html>
<script>
    $('#FormLogin').submit((e) => {
        e.preventDefault()
        let username = $('#username').val()
        let password = $('#password').val()
        let option = {
            url: '../controller/action.php',
            type: 'post',
            data: {
                username: username,
                password: password,
                login: 1
            },
            success: (res) => {
                if (res == 0) {
                    Swal.fire(
                        'ไม่พบชื่อผู้ใช้งานนี้!',
                        '',
                        'error'
                    )
                } else if (res == 1) {
                    Swal.fire(
                        'รหัสผ่านไม่ถูกต้อง!',
                        '',
                        'error'
                    )
                    $('#password').val('')
                } else {
                    Swal.fire(
                        'เข้าสู่ระบบสำเร็จ!',
                        '',
                        'success'
                    )
                    setTimeout(() => {
                        window.location.href = "..#/"
                    }, 800)
                }
            }
        }
        if (username != "" && password != "") {
            $.ajax(option)
        } else {
            Swal.fire(
                'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน!',
                '',
                'warning'
            )
        }
    })
</script>