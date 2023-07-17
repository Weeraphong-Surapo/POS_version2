<?php include("../config/connect.php"); ?>
<style>
    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 5px !important;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h1 class="text-black">ตำแหน่งงาน</h1>
            <button class="btn btn-info " onclick="openModal()">เพิ่มตำแหน่ง</button>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th width="15%">ลำดับ</th>
                        <th>ตำแหน่ง</th>
                        <th class="text-center" width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $count_row = 0;
                    $all_position = $conn->query("SELECT * FROM tb_position");
                    foreach ($all_position as $row) :
                    ?>
                        <tr id="row-<?= $row['position_id'] ?>">
                            <td><?= $i++ ?></td>
                            <td><?= $row['position_name'] ?></td>
                            <td class="d-flex justify-content-center">
                                <div class="btn btn-group">
                                    <button class="btn btn-warning btn-xs" onclick="EditPosition(<?= $row['position_id'] ?>,<?= $count_row++ ?>)"><i class="mdi mdi-lead-pencil"></i></button>
                                    <button class="btn btn-danger btn-xs" onclick="DeletePosition(<?= $row['position_id'] ?>,'<?= $row['position_name'] ?>')"><i class="mdi mdi-delete-forever"></i></button>
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
<div class="modal fade" id="ModalPosition" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="textPosition">เพิ่มลูกค้า</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="FormPosition">
                <input type="hidden" id="id_row">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label for="">ชื่อตำแหน่ง</label>
                            <input type="text" name="position" id="position" placeholder="ระบุตำแหน่ง" class="form-control">
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

    function DeletePosition(id) {
        let option = {
            type: 'post',
            url: 'controller/action.php',
            data: {
                id: id,
                DeletePosition: 1
            },
            success: function(res) {
                table.row('#row-' + id).remove().draw();
                swalAlert('ลบตำแหน่งสำเร็จ', 'success');
            }
        }
        Swal.fire({
            title: 'ลบตำแหน่ง?',
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

    function EditPosition(id,id_row){
        $('#textPosition').text('แก้ไขตำแหน่ง')
        $('#id_row').val(id_row)
        $('#btn-modal').text('อัพเดต')
        let option = {
            type: 'post',
            dataType: 'json',
            url: 'controller/action.php',
            data: {id:id,EditPosition:1},
            success: function(res) {
                $('#position').val(res.position_name)
                $('#id').val(res.position_id)
                $('#ModalPosition').modal('show')
            }
        }
        $.ajax(option)
    }

    $('#FormPosition').submit((e) => {
        e.preventDefault()
        let fd = new FormData()
        let id = $('#id').val()
        let position = $('#position').val()
        fd.append('id', id)
        fd.append('position', position)

        fd.append('addPosition', 1)
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

                    row.data([parseInt(id_rows)+1,position, '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="EditPosition(' + res.row_id + ','+id_rows+')"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeletePosition(' + res.row_id + ')"><i class="mdi mdi-delete-forever"></i></button></div>']);

                    row.invalidate();

                    table.draw(false);
                    swalAlert('อัพเดตสำเร็จ', 'success');
                }else{
                    swalAlert('บันทึกสำเร็จ', 'success');
                    addrow(res.row, position, '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="EditPosition(' + res.row_id + ','+id_rows+')"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeletePosition(' + res.row_id + ')"><i class="mdi mdi-delete-forever"></i></button></div>',res.row_id);
                }
                $('#ModalPosition').modal('hide')
            }
        }
        if (position != "") {
            $.ajax(option)
        } else {
            swalAlert('กรุณากรอกข้อมูลให้ครบถ้วน', 'warning')
        }
    })


    function openModal(){
        $('#textPosition').text('เพิ่มตำแหน่ง')
        $('#position').val('')
        $('#id').val('')
        $('#id_row').val('')
        $('#ModalPosition').modal('show')
    }

    function swalAlert(title, type) {
        Swal.fire(
            title,
            '',
            type
        )
    }

    function addrow(r1, r2, r3, rowid) {
        var newRowsNodes = table.row.add([r1, r2, r3]).draw().node();
        $(newRowsNodes).attr('id', 'row-' + rowid);
    }

</script>