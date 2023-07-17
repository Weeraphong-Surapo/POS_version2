<?php 
include("../config/connect.php"); 
$get_id = explode('.',$_GET['id']);
$id = $get_id[0];
$name_group = $conn->query("SELECT * FROM tb_group_customer WHERE group_id = '$id'");
$row_name_group = $name_group->fetch_array();
$result = $row_name_group['group_name'];
$result_id = $row_name_group['group_id'];
?>
<style>
    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 5px !important;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
        <div>
        <a href="#" onclick="history.back()" class="btn btn-secondary btn-sm">กลับ</a>
        </div>
        <div>
            <button class="btn btn-success" onclick="openModalExcel()">นำรายชื่อลูกค้า Excel เข้า</button>
            <button class="btn btn-info " onclick="openModal()">เพิ่มลูกค้า</button>
        </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ-สกุล</th>
                        <th>ที่อยู่</th>
                        <th>เบอร์โทร</th>
                        <th>ไลน์</th>
                        <th width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $count_row = 0;
                    $all_customer = $conn->query("SELECT * FROM tb_customer WHERE group_id = '$id'");
                    foreach ($all_customer as $row) :
                    ?>
                        <tr id="row-<?= $row['customer_id'] ?>">
                            <td><?= $i++ ?></td>
                            <td><?= $row['customer_fname'] . ' ' . $row['customer_lname'] ?></td>
                            <td><?= $row['customer_address'] ?></td>
                            <td><?= $row['customer_phone']; ?></td>
                            <td><?= $row['customer_line']; ?></td>
                            <td>
                                <div class="btn btn-group">
                                    <button class="btn btn-warning btn-xs" onclick="editCustomer(<?= $row['customer_id'] ?>,<?= $count_row++?>)"><i class="mdi mdi-lead-pencil"></i></button>
                                    <button class="btn btn-danger btn-xs" onclick="DeleteCustomer(<?= $row['customer_id'] ?>)"><i class="mdi mdi-delete-forever"></i></button>
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
<div class="modal fade" id="ModalCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="textCustomer">เพิ่มลูกค้า</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="FormCustomer">
                <input type="hidden" id="id_row">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="group_customer" id="group_customer" value="<?= $result_id;?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">ชื่อ</label>
                                <input type="text" class="form-control" id="fname" placeholder="ชื่อ *">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">นามสกุล</label>
                                <input type="text" class="form-control" id="lname" placeholder="นามสกุล *">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="">ที่อยู่</label>
                                <textarea name="address" id="address" cols="30" rows="10" class="form-control" placeholder="ระบุที่อยู่"></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">เบอร์โทร</label>
                                <input type="text" class="form-control" id="phone" placeholder="เบอร์โทร *">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">ไลน์ไอดี</label>
                                <input type="text" class="form-control" id="line" placeholder="ไลน์ไอดี *">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="">กลุ่มลูกค้า</label>

                                <input type="text" class="form-control" value="<?= $result?>" id="groupCustomer" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" id="btn-modal">บันทึก</button>
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

    function DeleteCustomer(id) {
        let option = {
            type: 'post',
            url: 'controller/action.php',
            data: {
                id: id,
                DeleteCustomer: 1
            },
            success: function(res) {
                table.row('#row-' + id).remove().draw();
                swalAlert('ลบข้อมูลสำเร็จ', 'success');
            }
        }
        Swal.fire({
            title: 'ต้องการลบลูกค้า?',
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

    function editCustomer(id,id_row){
        $('#textCustomer').text('แก้ไขข้อมูลลูกค้า')
        $('#id_row').val(id_row)
        $('#btn-modal').text('อัพเดต')
        let option = {
            type: 'post',
            dataType: 'json',
            url: 'controller/action.php',
            data: {id:id,editCustomer:1},
            success: function(res) {
                $('#id').val(res.customer_id)
                $('#fname').val(res.customer_fname)
                $('#lname').val(res.customer_lname) 
                $('#address').val(res.customer_address) 
                $('#phone').val(res.customer_phone)
                $('#line').val(res.customer_line)
                $('#ModalCustomer').modal('show')
            }
        }
        $.ajax(option)
    }

    $('#FormCustomer').submit((e) => {
        e.preventDefault()
        let fd = new FormData()
        let id = $('#id').val()
        let fname = $('#fname').val()
        let lname = $('#lname').val()
        let address = $('#address').val()
        let phone = $('#phone').val()
        let line = $('#line').val()
        let group_customer = $('#group_customer').val()
        fd.append('id', id)
        fd.append('fname', fname)
        fd.append('lname', lname)
        fd.append('phone', phone)
        fd.append('address', address)
        fd.append('line', line)
        fd.append('group_customer', group_customer)
        fd.append('addcustomer', 1)
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
                if(res.status == 1){
                    var row = table.row(id_rows);

                    row.node().id = 'row-' + id_rows;

                    row.data([parseInt(id_rows)+1, fname + ' ' + lname,address, phone, line, '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="editCustomer(' + id_rows + ')"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeleteCustomer(' + id_rows + ')"><i class="mdi mdi-delete-forever"></i></button></div>']);

                    row.invalidate();

                    table.draw(false);
                    swalAlert('อัพเดตสำเร็จ', 'success');
                }else{
                    swalAlert('บันทึกสำเร็จ', 'success');
                    addrow(res.row, fname + ' ' + lname,address, phone, line, res.row_id, '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="editCustomer(' + res.row_id + ')"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeleteCustomer(' + res.row_id + ')"><i class="mdi mdi-delete-forever"></i></button></div>');
                }
                $('#ModalCustomer').modal('hide')
            }
        }
        if (fname != "" && lname != "" && line != "") {
            $.ajax(option)
        } else {
            swalAlert('กรุณากรอกข้อมูลให้ครบถ้วน', 'warning')
        }
    })

    function swalAlert(title, type) {
        Swal.fire(
            title,
            '',
            type
        )
    }

    function openModal() {
        $('#id').val('')
        $('#id_row').val()
        $('#btn-modal').text('บันทึก')
        $('#fname').val('')
        $('#lname').val('')
        $('#phone').val('')
        $('#line').val('')
        $('#address').val('')
        $('#ModalCustomer').modal('show')
    }

    function addrow(r1, r2, r3, r4,r5, rowid, r6) {
        var newRowsNodes = table.row.add([r1, r2, r3, r4, r5,r6]).draw().node();
        $(newRowsNodes).attr('id', 'row-' + rowid);
    }

</script>