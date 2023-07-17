<?php include("../config/connect.php"); ?>
<style>
    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 5px !important;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>รหัสการขาย</th>
                        <th>พนักงานขาย</th>
                        <th>วันที่ขาย</th>
                        <th width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $all_sale = $conn->query("SELECT * FROM tb_sale ORDER BY sale_id DESC ");
                    foreach ($all_sale as $row):
                        $employees = $conn->query("SELECT * FROM tb_employee WHERE employee_id = '$row[employee_id]'");
                        $row_employee = $employees->fetch_array();
                        $emp_name = $row_employee['fname'] . ' ' . $row_employee['lname'];
                        ?>
                        <tr id="row-<?= $row['sale_id'] ?>">
                            <td>
                                <?= $i++ ?>
                            </td>
                            <td>
                                <?= $row['sale_code']++ ?>
                            </td>
                            <td>
                                <?= $emp_name ?>
                            </td>
                            <td>
                                <?= $row['by_date']; ?>
                            </td>
                            <td>
                                <button class="btn btn-primary" onclick="showDetail(<?= $row['sale_id'] ?>)"><i
                                        class="mdi mdi-eye"></i></button>
                                <button class="btn btn-danger" onclick="DeleteStory(<?= $row['sale_id'] ?>)"><i
                                        class="mdi mdi-delete-forever"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black" id="textcategory">รายละเอียดการขาย</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="resultDetail"></div>
            </div>
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

    function DeleteStory(id) {
        let option = {
            url: 'controller/action.php',
            type: 'post',
            data: {
                id: id,
                deleteStory: 1
            },
            success: function (res) {
                table.row('#row-' + id).remove().draw();
                swalAlert('ลบข้อมูลสำเร็จ', 'success');
            }
        }
        Swal.fire({
            title: 'ต้องการลบประวัติ?',
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

    function showDetail(id) {
        let option = {
            url: 'controller/action.php',
            type: 'post',
            data: {
                id: id,
                showDetail: 1
            },
            success: (res) => {
                $('#resultDetail').html(res)
                $('#ModalDetail').modal('show')
            }
        }
        $.ajax(option)
    }

    function swalAlert(title, type) {
        Swal.fire(
            title,
            '',
            type
        )
    }
</script>