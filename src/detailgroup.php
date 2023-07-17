<?php
include("../config/connect.php");
$get_id = explode('.', $_GET['id']);
$id = $get_id[0];
$position = $conn->query("SELECT * FROM tb_position WHERE position_id = '$id'");
$resutl_position = $position->fetch_array();
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
        <div class="d-flex justify-content-between">
            <a href="#" onclick="history.back()" class="btn btn-secondary btn-sm">กลับ</a>
            <button class="btn btn-info " onclick="openModal()">เพิ่มพนักงาน</button>
        </div>
        <hr>
        <div class="table-responsive mt-3">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th width="10%">ลำดับ</th>
                        <th>รูปภาพ</th>
                        <th>ชื่อพนักงาน</th>
                        <th>ตำแหน่ง</th>
                        <th>สร้างวันที่</th>
                        <th width="15%">รายละเอียด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $count_row = 0;
                    $all_sale = $conn->query("SELECT * FROM tb_employee WHERE position_id = '$id'");
                    foreach ($all_sale as $row) :
                    ?>
                        <tr id="row-<?= $row['employee_id']; ?>">
                            <td><?= $i++; ?></td>
                            <td><img src="assets/upload_img_customer/<?= $row['user_img'] ?>" alt=""></td>
                            <td><?= $row['fname'] . ' ' . $row['lname']; ?></td>
                            <td><?= $resutl_position['position_name'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" onclick="showEmployee(<?= $row['employee_id']; ?>)" class="btn btn-primary btn-xs"><i class="mdi mdi-eye"></i></button>
                                    <button type="button" onclick="EditEmployee(<?= $row['employee_id']; ?>,<?= $count_row++ ?>)" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button>
                                    <button type="button" onclick="DeleteEmployee(<?= $row['employee_id']; ?>)" class="btn btn-danger btn-xs"><i class="mdi mdi-delete-forever"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalEmployee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black" id="textEmployee">เพิ่มพนักงาน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="FormEmployee">
                <input type="hidden" name="old_img" id="old_img">
                <input type="hidden" name="created_at" id="created_at">
                <input type="hidden" name="id_row" id="id_row">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-12 d-flex justify-content-center">
                            <img id="blah" src="#" alt="รูปภาพพนักงาน" class=" rounded-circle img-fluid" style="width:70px; height:70px;" />
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-2">
                                <lable class="" for="">ชื่อ</lable>
                                <input type="text" name="" id="fname" class="form-control" placeholder="ชื่อพนักงาน">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-2">
                                <lable class="" for="">นามสกุล</lable>
                                <input type="text" name="" id="lname" class="form-control" placeholder="นามสกุลพนักงาน">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-2">
                                <lable class="" for="">เบอร์โทร</lable>
                                <input type="text" name="" id="phone" class="form-control" placeholder="เบอร์โทรติดต่อ">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
                            <div class="mb-2">
                                <lable class="" for="">ไลน์ติต่อ</lable>
                                <input type="text" name="" id="line" class="form-control" placeholder="ไลน์ติดต่อ">
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 col-md-12">
                            <div class="mb-2">
                                <lable class="" for="">ที่อยู่</lable>
                                <textarea name="" id="address" class="form-control" cols="30" rows="3" placeholder="ที่อยู่ปัจจุบัน"></textarea>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="mb-2">
                                <lable class="" for="">ตำแหน่ง</lable>
                                <input type="hidden" name="position" id="positions" value="<?= $resutl_position['position_id'] ?>">
                                <select name="" id="" class="form-control" disabled>
                                    <option value="<?= $resutl_position['position_id'] ?>" selected><?= $resutl_position['position_name'] ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="mb-2">
                                <lable class="" for="">รูปภาพ</lable>
                                <input accept="image/*" type='file' id="imgInp" class="form-control" />
                            </div>
                        </div>
                        <div style="display: block;border: 1px solid #d4acec;width: 100%;margin: 8px;"></div>
                        <div class="col-md-12 col-lg-12 col-12">
                            <div class="mb-2">
                                <lable class="" for="">ชื่อผู้ใช้งาน</lable>
                                <input type="text" name="" id="username" class="form-control" placeholder="ชื่อเข้าใช้งานระบบ *">
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-12">
                            <div class="mb-2">
                                <lable class="" for="">รหัสผ่าน</lable>
                                <input type="password" name="" id="password" class="form-control" placeholder="*********">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" id="btn-modal">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var table = $('#myTable').DataTable({
        "language": {
            "sProcessing": "กำลังดำเนินการ...",
            "sLengthMenu": "แสดง_MENU_ แถว",
            "sZeroRecords": "ไม่พบข้อมูล",
            "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
            "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 แถว",
            "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
            "sInfoPostFix": "",
            "sSearch": "ค้นหา:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "เิริ่มต้น",
                "sPrevious": "ก่อนหน้า",
                "sNext": "ถัดไป",
                "sLast": "สุดท้าย"
            }
        }
    });

    function showEmployee(id) {
        $('#textEmployee').text('ข้อมูลพนักงาน')
        $('.modal-footer').addClass('d-none')
        let option = {
            url: 'controller/action.php',
            type: 'post',
            dataType: 'json',
            data: {
                id: id,
                showEmployee: 1
            },
            success: function(res) {
                $('#id').val(res.employee_id)
                $('#fname').val(res.fname)
                $('#lname').val(res.lname)
                $('#phone').val(res.phone)
                $('#line').val(res.line)
                $('#address').val(res.address)
                $('#username').val(res.username)
                $('#password').val(res.password)
                $('#positions').val(res.position_id)
                $('#positions').val(res.position_id)
                $('#id').attr('disabled', '')
                $('#fname').attr('disabled', '')
                $('#lname').attr('disabled', '')
                $('#phone').attr('disabled', '')
                $('#line').attr('disabled', '')
                $('#address').attr('disabled', '')
                $('#username').attr('disabled', '')
                $('#password').attr('disabled', '')
                $('#imgInp').attr('disabled', '')
                $('#blah').attr('src', 'assets/upload_img_customer/' + res.user_img)
                $('#ModalEmployee').modal('show')
            }
        }
        $.ajax(option)
    }

    function DeleteEmployee(id) {
        let option = {
            type: 'post',
            url: 'controller/action.php',
            data: {
                id: id,
                DeleteEmployee: 1
            },
            success: function(res) {
                table.row('#row-' + id).remove().draw();
                swalAlert('ลบข้อมูลสำเร็จ', 'success');
            }
        }
        Swal.fire({
            title: 'ลบข้อมูลพนักงาน?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax(option)
            }
        })
    }


    function EditEmployee(id, id_row) {
        $('#textEmployee').text('แก้ไขข้อมูลพนักงาน')
        $('#id_row').val(id_row)
        $('#btn-modal').text('อัพเดต')
        $('.modal-footer').removeClass('d-none')
        let option = {
            type: 'post',
            dataType: 'json',
            url: 'controller/action.php',
            data: {
                id: id,
                EditEmployee: 1
            },
            success: function(res) {
                $('#id').removeAttr('disabled')
                $('#fname').removeAttr('disabled')
                $('#lname').removeAttr('disabled')
                $('#phone').removeAttr('disabled')
                $('#line').removeAttr('disabled')
                $('#address').removeAttr('disabled')
                $('#username').removeAttr('disabled')
                $('#password').removeAttr('disabled')
                $('#imgInp').removeAttr('disabled')
                $('#id').val(res.employee_id)
                $('#fname').val(res.fname)
                $('#lname').val(res.lname)
                $('#phone').val(res.phone)
                $('#line').val(res.line)
                $('#address').val(res.address)
                $('#username').val(res.username)
                $('#password').val(res.password)
                $('#created_at').val(res.created_at)
                $('#old_img').val(res.user_img)
                $('#blah').attr('src', 'assets/upload_img_customer/' + res.user_img)
                $('#ModalEmployee').modal('show')
            }
        }
        $.ajax(option)
    }

    $('#FormEmployee').submit((e) => {
        e.preventDefault()
        let fd = new FormData()
        let id = $('#id').val()
        let fname = $('#fname').val()
        let lname = $('#lname').val()
        let phone = $('#phone').val()
        let line = $('#line').val()
        let old_img = $('#old_img').val()
        let created = $('#created_at').val()
        let address = $('#address').val()
        let username = $('#username').val()
        let password = $('#password').val()
        let position = $('#positions').val()
        var files = $('#imgInp')[0].files;

        fd.append('id', id)
        fd.append('fname', fname)
        fd.append('lname', lname)
        fd.append('phone', phone)
        fd.append('line', line)
        fd.append('address', address)
        fd.append('created', created)
        fd.append('username', username)
        fd.append('password', password)
        fd.append('position', position)
        fd.append('old_img', old_img)
        fd.append('file', files[0]);

        fd.append('addEmployee', 1)
        let option = {
            type: 'post',
            dataType: 'json',
            url: 'controller/action.php',
            beforeSend: function(xhr) {
                $('#btn-modal').text('กำลังบันทึก...')
            },
            data: fd,
            contentType: false,
            processData: false,
            success: function(res) {
                let id_rows = $('#id_row').val()
                if (res.status == 1) {
                    var row = table.row(id_rows);

                    row.node().id = 'row-' + id_rows;
                    row.data([parseInt(id_rows) + 1, res.img, fname + ' ' + lname, res.position, res.created_at, '<div class="btn-group"><button type="button" onclick="showEmployee(' + res.row_id + ')" class="btn btn-primary btn-xs"><i class="mdi mdi-eye"></i></button><button type="button" onclick="EditEmployee(' + res.row_id + ',' + id_rows + ')" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button><button type="button" onclick="DeleteEmployee(' + res.row_id + ')" class="btn btn-danger btn-xs"><i class="mdi mdi-delete-forever"></i></button></div>']);

                    row.invalidate();

                    table.draw(false);
                    swalAlert('อัพเดตสำเร็จ', 'success');
                } else {
                    swalAlert('บันทึกสำเร็จ', 'success');
                    addrow(res.row, res.img, fname + ' ' + lname, res.position, res.created_at, '<div class="btn-group"><button type="button" onclick="showEmployee(' + res.row_id + ')" class="btn btn-primary btn-xs"><i class="mdi mdi-eye"></i></button><button type="button" onclick="EditEmployee(' + res.row_id + ',' + id_rows + ')" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button><button type="button" onclick="DeleteEmployee(' + res.row_id + ')" class="btn btn-danger btn-xs"><i class="mdi mdi-delete-forever"></i></button></div>', res.row_id);
                }
                $('#ModalEmployee').modal('hide')
            }
        }
        if (fname != "" && lname != "" && phone != "" && address != "" && username != "" && password != "") {
            $.ajax(option)
        } else {
            swalAlert('กรุณากรอกข้อมูลให้ครบถ้วน', 'warning')
        }
    })

    function addrow(r1, r2, r3, r4, r5, r6, rowid) {
        var newRowsNodes = table.row.add([r1, r2, r3, r4, r5, r6]).draw().node();
        $(newRowsNodes).attr('id', 'row-' + rowid);
    }

    function openModal() {
        $('#fname').val('')
        $('#lname').val('')
        $('#phone').val('')
        $('#line').val('')
        $('#address').val('')
        $('#username').val('')
        $('#password').val('')
        $('#position').val('')
        $('#imgInp').val('');
        $('#id').val('')
        $('#btn-modal').text('บันทึก')
        $('#textEmployee').text('เพิ่มพนักงาน')
        $('.modal-footer').removeClass('d-none')
        $('#ModalEmployee').modal('show')
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