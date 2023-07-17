<?php include("../config/connect.php"); ?>
<style>
    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 5px !important;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end">
            <button class="btn btn-info " onclick="openModal()">เพิ่มกลุ่มลูกค้า</button>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>กลุ่มลูกค้า</th>
                        <th width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $count_row = 0;
                    $all_tb_group_customer = $conn->query("SELECT * FROM tb_group_customer");
                    foreach ($all_tb_group_customer as $row) :
                    ?>
                        <tr id="row-<?= $row['group_id'] ?>">
                            <td><?= $i++ ?></td>
                            <td><?= $row['group_name'] ?></td>
                            <td>
                                <div class="btn btn-group">
                                    <button class="btn btn-warning btn-xs" onclick="editGroupCustomer(<?= $row['group_id'] ?>, <?= $count_row++ ?>)"><i class="mdi mdi-lead-pencil"></i></button>
                                    <button class="btn btn-danger btn-xs" onclick="DeleteGroupCustomer(<?= $row['group_id'] ?>)"><i class="mdi mdi-delete-forever"></i></button>
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
<div class="modal fade" id="ModalGroupCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="textcategory">เพิ่มประเภท</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="FormSubCategory">
                <input type="hidden" id="id_row">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="">กลุ่มลูกค้า</label>
                        <input type="text" name="" id="groupCustomer" class="form-control" placeholder="ระบุกลุ่มลูกค้า">
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

    function editGroupCustomer(id, id_row) {
        $('#id_row').val(id_row)
        $('#btn-modal').text('อัพเดต')
        let option = {
            type: 'post',
            url: 'controller/action.php',
            dataType: 'json',
            data: {
                id: id,
                editGroupCustomer: 1
            },
            success: function(res) {
                $('#id').val(res.group_id)
                $('#groupCustomer').val(res.group_name)
                $('#ModalGroupCustomer').modal('show')
            }
        }
        $.ajax(option)
    }

    function DeleteGroupCustomer(id) {
        let option = {
            type: 'post',
            url: 'controller/action.php',
            data: {
                id: id,
                DeleteGroupCustomer: 1
            },
            success: function(res) {
                if(res == 0){
                    swalAlert('ไม่สามารถลบใด้เนื่องจากมีลูกค้าอยู่', 'warning');
                }else{
                    table.row('#row-' + id).remove().draw();
                    swalAlert('บันทึกสำเร็จ', 'success');
                }
            }
        }
        Swal.fire({
            title: 'ต้องการลบกลุ่มลูกค้า?',
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

    function addrow(r1, r2, rowid, r3) {
        var newRowsNodes = table.row.add([r1, r2, r3]).draw().node();
        $(newRowsNodes).attr('id', 'row-' + rowid);
    }


    function openModal() {
        $('#id').val('')
        $('#id_row').val('')
        $('#groupCustomer').val('')
        $('#btn-modal').text('บันทึก')
        $('#ModalGroupCustomer').modal('show')
    }

    $('#FormSubCategory').submit((e) => {
        e.preventDefault()
        let fd = new FormData()
        let id = $('#id').val()
        let groupCustomer = $('#groupCustomer').val()
        fd.append('id', id)
        fd.append('groupCustomer', groupCustomer)
        fd.append('addGroupCustomer', 1)
        let option = {
            type: 'post',
            url: 'controller/action.php',
            dataType: 'json',
            beforeSend: function(xhr) {
                $('#btn-modal').text('กำลังบันทึก...')
            },
            data: fd,
            contentType: false,
            processData: false,
            success: function(res) {
                let id_rows = $('#id_row').val()
                if (res.status == 0) {
                    swalAlert('บันทึกสำเร็จ', 'success');
                    addrow(res.row, groupCustomer, res.row_id, '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="editGroupCustomer(' + res.row_id + ',' + id_rows + ')"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeleteGroupCustomer(' + res.row_id + ')"><i class="mdi mdi-delete-forever"></i></button></div>');
                } else {
                    var row = table.row(id_rows);

                    row.node().id = 'row-' + res.row_id;

                    row.data([parseInt(id_rows) + 1, groupCustomer, '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="editGroupCustomer(' + res.row_id + ',' + id_rows + ')"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeleteGroupCustomer(' + res.row_id + ')"><i class="mdi mdi-delete-forever"></i></button></div>']);

                    row.invalidate();

                    table.draw(false);
                    swalAlert('อัพเดตสำเร็จ', 'success');
                }
                $('#ModalGroupCustomer').modal('hide')
            }
        }
        if (groupCustomer != "") {
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
</script>