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
            <button class="btn btn-info " onclick="openModal()">เพิ่มประเภท</button>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th width="10%">ลำดับ</th>
                        <th>หมวดหมู่</th>
                        <th>ประเภท</th>
                        <th width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $count_row = 0;
                    $all_category = $conn->query("SELECT * FROM tb_sub_category");
                    foreach ($all_category as $row) :
                        $category = $conn->query("SELECT* FROM tb_category WHERE category_id = '$row[category_id]'");
                        $row_category = $category->fetch_array();
                    ?>
                        <tr id="row-<?= $row['sub_id'] ?>">
                            <td><?= $i++ ?></td>
                            <td><?= $row_category['category_name'] ?></td>
                            <td><?= $row['sub_name'] ?></td>
                            <td>
                                <div class="btn btn-group">
                                    <button class="btn btn-warning btn-xs" onclick="editSubCategory(<?= $row['sub_id'] ?>,<?= $count_row++ ?>)"><i class="mdi mdi-lead-pencil"></i></button>
                                    <button class="btn btn-danger btn-xs" onclick="DeleteSubCategory(<?= $row['sub_id'] ?>)"><i class="mdi mdi-delete-forever"></i></button>
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
<div class="modal fade" id="ModalSubCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
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
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="">หมวดหมู่</label>
                                <select name="" id="category" class="form-control">
                                    <option value="" disabled selected>เลือกหมวดหมู่</option>
                                    <?php
                                    $all_category = $conn->query("SELECT * FROM tb_category");
                                    foreach ($all_category as $row) :
                                        echo '<option value=' . $row['category_id'] . '>' . $row['category_name'] . '</option>';
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="">ชื่อประเภท</label>
                                <input type="text" class="form-control" id="subcategory" placeholder="ชื่อประเภท">
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

    function editSubCategory(id, id_row) {
        $('#textcategory').text('แก้ไขประเภท')
        $('#id_row').val(id_row)
        $('#btn-modal').text('อัพเดต')
        let option = {
            type: 'post',
            url: 'controller/action.php',
            dataType: 'json',
            data: {
                id: id,
                editSubCate: 1
            },
            success: function(res) {
                $('#id').val(res.sub_id)
                $('#category').val(res.category_id)
                $('#subcategory').val(res.sub_name)
                $('#ModalSubCategory').modal('show')
            }
        }
        $.ajax(option)
    }

    function DeleteSubCategory(id) {
        let option = {
            type: 'post',
            url: 'controller/action.php',
            data: {
                id: id,
                removeSubCategory: 1
            },
            success: function(res) {
                if(res == 1){
                    swalAlert('ไม่สามรถลบใด้เนื่องจากมีประเภทนี้ในสินค้าอยู่','warning')
                }else{
                    table.row('#row-' + id).remove().draw();
                    swalAlert('ลบสำเร็จ', 'success');
                }
            }
        }
        Swal.fire({
            title: 'ต้องการลบประเภท?',
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

    function addrow(r1, r2, r3, rowid, r4) {
        var newRowsNodes = table.row.add([r1, r2, r3, r4]).draw().node();
        $(newRowsNodes).attr('id', 'row-' + rowid);
    }


    function openModalExcel() {

    }

    function openModal() {
        $('#textcategory').text('เพิ่มประเภท')
        $('#id').val('')
        $('#id_row').val('')
        $('#btn-modal').text('บันทึก')
        $('#category').val('')
        $('#subcategory').val('')
        $('#ModalSubCategory').modal('show')
    }

    $('#FormSubCategory').submit((e) => {
        e.preventDefault()
        let fd = new FormData()
        let id = $('#id').val()
        let category = $('#category').val()
        let subcategory = $('#subcategory').val()
        fd.append('id', id)
        fd.append('category', category)
        fd.append('subcategory', subcategory)
        fd.append('addsubcategory', 1)
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
                if (res.status == 1) {
                    var row = table.row(id_rows);

                    row.node().id = 'row-' + res.row;

                    row.data([parseInt(id_rows) + 1, res.category, subcategory, '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="editSubCategory(' + id + ',' + id_rows + ')"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeleteSubCategory(' + id + ')"><i class="mdi mdi-delete-forever"></i></button></div>']);

                    row.invalidate();

                    table.draw(false);
                    swalAlert('อัพเดตสำเร็จ', 'success');
                } else {
                    addrow(res.row, res.category, subcategory, res.row_id, '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="editSubCategory(' + res.row_id + ',' + res.row + ')"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeleteSubCategory(' + res.row_id + ')"><i class="mdi mdi-delete-forever"></i></button></div>');
                    swalAlert('บันทึกสำเร็จ', 'success');
                }
                $('#ModalSubCategory').modal('hide')
            }
        }
        if (category != "" && subcategory != "") {
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