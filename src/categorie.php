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
            <button class="btn btn-info " onclick="openModal()">เพิ่มหมวดหมู่</button>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th width="10%">ลำดับ</th>
                        <th>หมวดหมู่</th>
                        <th width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $count_row = 0;
                    $all_category = $conn->query("SELECT * FROM tb_category");
                    foreach ($all_category as $row) :
                    ?>
                        <tr id="row-<?= $row['category_id'] ?>">
                            <td><?= $i++ ?></td>
                            <td><?= $row['category_name'] ?></td>
                            <td>
                                <div class="btn btn-group">
                                    <button class="btn btn-warning btn-xs" onclick="editCategory(<?= $row['category_id'] ?>,<?= $count_row++ ?>)"><i class="mdi mdi-lead-pencil"></i></button>
                                    <button class="btn btn-danger btn-xs" onclick="DeleteCategory(<?= $row['category_id'] ?>)"><i class="mdi mdi-delete-forever"></i></button>
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
<div class="modal fade" id="ModalCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="textcategory">เพิ่มหมวดหมู่</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="FormCategory">
                <input type="hidden" name="id_row" id="id_row">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="">ชื่อหมวดหมู่</label>
                                <input type="text" class="form-control" id="category" placeholder="ชื่อหมวดหมู่">
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

    function editCategory(id, id_row) {
        $('#textcategory').text('แก้ไขหมวดหมู่')
        $('#id_row').val(id_row)
        $('#btn-modal').text('อัพเดต')
        let option = {
            type: 'post',
            url: 'controller/action.php',
            dataType: 'json',
            data: {
                id: id,
                editCategory: 1
            },
            success: function(res) {
                $('#id').val(res.category_id)
                $('#category').val(res.category_name)
                $('#ModalCategory').modal('show')
            }
        }
        $.ajax(option)
    }

    function DeleteCategory(id) {
        let option = {
            type: 'post',
            url: 'controller/action.php',
            data: {
                id: id,
                removeCategory: 1
            },
            success: function(res) {
                if(res == 0){
                    swalAlert('ไม่สามารถลบใด้เนื่องจากมีประเภทนี้อยู่ในสินค้า', 'warning');
                }else{
                    table.row('#row-' + id).remove().draw();
                    swalAlert('บันทึกสำเร็จ', 'success');
                }
            }
        }
        Swal.fire({
            title: 'ต้องการลบหมวดหมู่?',
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

    function addrow(r1, r2) {
        table.row.add([r1, r2]).draw();
    }

    function showMange() {
        $('.showMange').toggleClass('d-none')
    }


    function openModal() {
        $('#id').val('')
        $('#btn-modal').text('บันทึก')
        $('#category').val('')
        $('#ModalCategory').modal('show')
    }

    $('#FormCategory').submit((e) => {
        e.preventDefault()
        let fd = new FormData()
        let id = $('#id').val()
        let category = $('#category').val()
        fd.append('id', id)
        fd.append('category', category)
        fd.append('addcategory', 1)
        let option = {
            type: 'post',
            url: 'controller/action.php',
            dataType: 'json',
            data: fd,
            contentType: false,
            processData: false,
            success: function(res) {
                let id_rows = $('#id_row').val()
                if (res.status == 1) {
                    // var row = table.row(id_rows);

                    // row.node().id = 'row-' + id_rows;

                    // row.data([parseInt(id_rows) + 1, category, '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="editCategory(' + res.row_id + ',' + id_rows + ')"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeleteCategory(' + res.row_id + ')"><i class="mdi mdi-delete-forever"></i></button></div>']);

                    // row.invalidate();

                    // table.draw(false);
                    swalAlert('อัพเดตสำเร็จ', 'success');
                } else {
                    swalAlert('บันทึกสำเร็จ', 'success');
                    // addrow(res.row, category, '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="editCategory(' + res.row_id + ')"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeleteCategory(' + res.row_id + ')"><i class="mdi mdi-delete-forever"></i></button></div>');
                }
                $('#ModalCategory').modal('hide')

            }
        }
        if (category != "") {
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
        setTimeout(()=>{location.reload()},700)
    }
</script>