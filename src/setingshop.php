<?php
include("../config/connect.php");
session_start();
$query = $conn->query("SELECT * FROM tb_shop");
$row_shop = $query->fetch_array();
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
            <img id="blah" src="assets/upload_logo/<?= $row_shop['shop_img'] ?>" alt="รูปภาพร้าน" class="rounded-circle img-fluid" style="width:70px; height:70px;" />
        </div>
        <center>
            <input type="hidden" name="" id="old_img" value="<?= $row_shop['shop_img'] ?>">
            <input accept="image/*" type='file' id="imgInp" disabled />
        </center>
        <hr>
        <div class="row mb-3">
            <div class="col-2 d-flex justify-content-end align-items-center">
                <label for="">ชื่อร้าน : </label>
            </div>
            <div class="col-10">
                <input type="text" name="" id="shop_name" value="<?= $row_shop['shop_name'] ?>" class="form-control" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-2 d-flex justify-content-end align-items-center">
                <label for="">ที่อยู่ร้าน : </label>
            </div>
            <div class="col-10">
            <textarea name="" id="shop_address" cols="30" rows="3" class="form-control" disabled><?= $row_shop['shop_address'] ?></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-2 d-flex justify-content-end align-items-center">
            <label for="">เบอร์โทร : </label>
        </div>
        <div class="col-10">
                <input type="text" name="" id="shop_phone" value="<?= $row_shop['shop_phone'] ?>" class="form-control" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-2 d-flex justify-content-end align-items-center">
                <label for="">ไลน์แจ้งเตือน : </label>
            </div>
            <div class="col-10">
                <input type="text" name="" id="shop_notify" class="form-control" value="<?= $row_shop['line_notify'] ?>" disabled>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button class="btn btn-warning text-shop" style="margin-right: 10px;" onclick="editShop()">แก้ไขร้าน</button>
            <button class="btn btn-success d-none saveShop" style="margin-right: 10px;" onclick="saveShop()">บันทึก</button>
        </div>
    </div>
</div>



<script>

    function saveShop(){
        let fd = new FormData()
        let shop_name = $('#shop_name').val()
        let shop_phone = $('#shop_phone').val()
        let shop_address = $('#shop_address').val()
        let shop_notify = $('#shop_notify').val()
        var files = $('#imgInp')[0].files;

        fd.append('shop_name',shop_name)
        fd.append('shop_phone',shop_phone)
        fd.append('shop_address',shop_address)
        fd.append('shop_notify',shop_notify)
        fd.append('file', files[0]);
        fd.append('updateShop',1)
        let option = {
            url: 'controller/action.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success:function(res){
                swalAlert('อัพเดตเรียบร้อย','success')
                $('#shop_notify').attr('disabled','')
                $('#shop_name').attr('disabled','')
                $('#shop_phone').attr('disabled','')
                $('#shop_address').attr('disabled','')
                $('#imgInp').attr('disabled','')
                $('.saveShop').addClass('d-none')
                $('.text-shop').text('แก้ไข')
                $('.text-shop').removeClass('btn-danger')
                $('.text-shop').addClass('btn-warning')
            }
        }
        $.ajax(option)
    }

    function editShop(){
        if($('.text-shop').hasClass('btn-warning')){
            enableInput()
        }else{
            disabledInput()
        }
    }

    function enableInput() {
        $('.text-shop').text('ยกเลิก')
        $('.change-pass').addClass('d-none')
        $('.text-shop').removeClass('btn-warning')
        $('.text-shop').addClass('btn-danger')
        $('.saveShop').removeClass('d-none')
        $('#shop_notify').removeAttr('disabled')
        $('#shop_name').removeAttr('disabled')
        $('#shop_phone').removeAttr('disabled')
        $('#shop_address').removeAttr('disabled')
        $('#imgInp').removeAttr('disabled')
    }

    function disabledInput() {
        $('.text-shop').text('แก้ไขร้าน')
        $('.change-pass').removeClass('d-none')
        $('.text-shop').removeClass('btn-danger')
        $('.text-shop').addClass('btn-warning')
        $('.saveShop').addClass('d-none')
        $('#shop_notify').attr('disabled','')
        $('#shop_name').attr('disabled','')
        $('#shop_phone').attr('disabled','')
        $('#shop_address').attr('disabled','')
        $('#imgInp').attr('disabled','')
        $('#blah').attr('src','assets/upload/<?= $row_shop['shop_img'] ?>')
        $('#shop_address').val('<?= $row_shop['shop_address']?>')
        $('#shop_notify').val('<?= $row_shop['line_notify']?>')
        $('#shop_name').val('<?= $row_shop['shop_name']?>')
        $('#shop_phone').val('<?= $row_shop['shop_phone']?>')
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