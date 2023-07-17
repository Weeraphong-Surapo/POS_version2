<?php
include("../config/connect.php");
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
$query = $conn->query("SELECT * FROM tb_employee WHERE employee_id = '$_SESSION[employee_id]'");
$row_employee = $query->fetch_array();
?>

<style>
    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 5px !important;
    }

    .form-control {
        border-left: 3px solid #337ab7 !important;
    }

    img {
        object-position: top;
        object-fit: cover;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-center">
            <img id="blah" src="assets/upload_img_customer/<?= $row_employee['user_img'] ?>" alt="รูปภาพพนักงาน" class="rounded-circle img-fluid" style="width:70px; height:70px;" />
        </div>
        <center>
            <input type="hidden" name="" id="old_img" value="<?= $row_employee['user_img'] ?>">
            <input accept="image/*" type='file' id="imgInp" disabled />
        </center>
        <hr>
        <div class="row mb-3">
            <div class="col-2 d-flex justify-content-end align-items-center ">
                <label for="">ชื่อผู้ใช้งาน : </label>
            </div>
            <div class="col-10">
                <input type="text" name="" id="username" value="<?= $row_employee['username'] ?>" class="form-control" disabled>
            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-2 d-flex justify-content-end align-items-center">
                <label for="">ชื่อ : </label>
            </div>
            <div class="col-10">
                <input type="text" name="" id="fname" value="<?= $row_employee['fname'] ?>" class="form-control" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-2 d-flex justify-content-end align-items-center">
                <label for="">นามสกุล : </label>
            </div>
            <div class="col-10">
                <input type="text" name="" id="lname" value="<?= $row_employee['lname'] ?>" class="form-control" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-2 d-flex justify-content-end align-items-center">
                <label for="">ที่อยู่ : </label>
            </div>
            <div class="col-10">
                <textarea name="" id="address" cols="30" rows="3" class="form-control" disabled><?= $row_employee['address'] ?></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-2 d-flex justify-content-end align-items-center">
                <label for="">เบอร์โทร : </label>
            </div>
            <div class="col-10">
                <input type="text" name="" id="phone" value="<?= $row_employee['phone'] ?>" class="form-control" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-2 d-flex justify-content-end align-items-center">
                <label for="">ไอดีไลน์ : </label>
            </div>
            <div class="col-10">
                <input type="text" name="" id="line" value="<?= $row_employee['line'] ?>" class="form-control" disabled>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button class="btn btn-warning text-profile" style="margin-right: 10px;" onclick="editProfile()">แก้ไขข้อมูลส่วนตัว</button>
            <button class="btn btn-success d-none saveProfile" style="margin-right: 10px;" onclick="saveProfile(<?= $row_employee['employee_id'] ?>)">บันทึก</button>
            <button class="btn btn-primary change-pass" onclick="changePass(<?= $row_employee['employee_id'] ?>)">เปลี่ยนรหัสผ่าน</button>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalPass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black" id="textEmployee">เปลี่ยนรหัสผ่าน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                    <label for="">รหัสผ่าน : </label>

                <div class="mb-2 box-old-pass">
                    <input type="text" name="" id="password" value="" placeholder="ระบุรหัสผ่านเดิม" class="form-control" required>
                </div>
                <div class="mv-2 box-new-pass d-none">
                    <input type="text" name="" id="new_pass" value="" placeholder="ระบุรหัสผ่านใหม่" class="form-control mb-3" required>
                    <input type="text" name="" id="c_pass" value="" placeholder="ยืนยันรหัสผ่าน" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-primary check-pass" id="btn-modal">ตรวจสอบ</button>
                <button type="submit" class="btn btn-primary update-pass d-none" id="btn-modal">เปลียนรหัสผ่าน</button>
            </div>
        </div>
    </div>
</div>

<script>
    function changePass(id) {
        $('#password').val('')
        $('#new_pass').val('')
        $('#c_pass').val('')
        $('#ModalPass').modal('show')
    }

    function saveProfile(id) {
        let fd = new FormData()
        let fname = $('#fname').val()
        let lname = $('#lname').val()
        let phone = $('#phone').val()
        let line = $('#line').val()
        let old_img = $('#old_img').val()
        let address = $('#address').val()
        let username = $('#username').val()
        var files = $('#imgInp')[0].files;

        fd.append('id', id)
        fd.append('fname', fname)
        fd.append('lname', lname)
        fd.append('phone', phone)
        fd.append('line', line)
        fd.append('address', address)
        fd.append('username', username)
        fd.append('old_img', old_img)
        fd.append('file', files[0]);
        fd.append('updateProfile', 1);
        let option = {
            url: 'controller/action.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: (res) => {
                swalAlert('อัพข้อมูลส่วนตัวสำเร็จ', 'success')
                disabledInput()
            }
        }
        $.ajax(option)
    }

    $('.check-pass').click(() => {
        let password = $('#password').val()
        let option = {
            url: 'controller/action.php',
            type: 'post',
            data: {
                password: password,
                checkPass: 1
            },
            success: (res) => {
                if (res == 1) {
                    $('.box-old-pass').addClass('d-none')
                    $('.box-new-pass').removeClass('d-none')
                    $('.check-pass').addClass('d-none')
                    $('.update-pass').removeClass('d-none')
                    
                } else {
                    swalAlert('รหัสผ่านไม่ถูกต้อง!', 'error')
                    $('.box-old-pass').removeClass('d-none')
                    $('.box-new-pass').addClass('d-none')
                    $('.check-pass').removeClass('d-none')
                    $('.update-pass').addClass('d-none')
                }
            }
        }
        $.ajax(option)
    })

    $('.update-pass').click(() => {
        let new_pass = $('#new_pass').val()
        let c_pass = $('#c_pass').val()
        let option = {
            url: 'controller/action.php',
            type: 'post',
            data: {
                new_pass: new_pass,
                updatePass: 1
            },
            success: function(res) {
                swalAlert('อัพเดตรหัสผ่านสำเร็จ', 'success')
                $('#ModalPass').modal('hide')
                $('.box-old-pass').removeClass('d-none')
                    $('.box-new-pass').addClass('d-none')
                    $('.check-pass').removeClass('d-none')
                    $('.update-pass').addClass('d-none')

            }
        }
        if (new_pass != c_pass) {
            swalAlert('รหัสผ่านไม่ตรงกัน', 'warning')
            $('#c_pass').val('')
            $('#new_pass').val('')
        } else {
            $.ajax(option)
        }
    })

    function editProfile(id) {
        if ($('.text-profile').hasClass('btn-warning')) {
            enableInput()
        } else {
            disabledInput()
        }
    }

    function enableInput() {
        $('.text-profile').text('ยกเลิก')
        $('.change-pass').addClass('d-none')
        $('.text-profile').removeClass('btn-warning')
        $('.text-profile').addClass('btn-danger')
        $('.saveProfile').removeClass('d-none')
        $('#phone').removeAttr('disabled')
        $('.check-pass').removeAttr('disabled')
        $('#line').removeAttr('disabled')
        $('#address').removeAttr('disabled')
        $('#fname').removeAttr('disabled')
        $('#lname').removeAttr('disabled')
        $('#imgInp').removeAttr('disabled')
        $('#username').removeAttr('disabled')
    }

    function disabledInput() {
        $('.text-profile').text('แก้ไข')
        $('.change-pass').removeClass('d-none')
        $('.text-profile').removeClass('btn-danger')
        $('.text-profile').addClass('btn-warning')
        $('.saveProfile').addClass('d-none')
        $('#phone').attr('disabled', '')
        $('#line').attr('disabled', '')
        $('#address').attr('disabled', '')
        $('#fname').attr('disabled', '')
        $('#lname').attr('disabled', '')
        $('#imgInp').attr('disabled', '')
        $('#username').attr('disabled', '')
    }

    function swalAlert(title, type) {
        Swal.fire(
            title,
            '',
            type
        )
    }

    imgInp.onchange = evt => {

        const [file] = imgInp.files
        if (file) {
            blah.src = URL.createObjectURL(file)
            $('#blah').removeClass('d-none')
        }
    }
</script>