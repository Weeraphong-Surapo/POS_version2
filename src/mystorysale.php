<?php 
session_start();
include("../config/connect.php"); 
?>
<style>
    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 5px !important;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="table-responsive mt-3">
            <table class="table table-hover table-bordered" id="myTable">
                <thead class="bg-info text-white">
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">รหัสรายการ</th>
                        <th class="text-center">ชื่อลูกค้า</th>
                        <th class="text-center">พนักงานขาย</th>
                        <th class="text-center">วันที่ซื้อ</th>
                        <th width="15%" class="text-center">รายละเอียด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $emp_sale = $conn->query("SELECT * FROM tb_sale WHERE employee_id = '$_SESSION[employee_id]'");
                    foreach ($emp_sale as $row) :
                        $customer = $conn->query("SELECT * FROM tb_customer WHERE customer_id = '$row[customer_id]'");
                        if ($customer->num_rows >= 1) {
                            $row_customer = $customer->fetch_array();
                            $name = $row_customer['customer_fname'] . ' ' . $row_customer['customer_lname'];
                        } else {
                            $name = 'ไม่ใช่สมาชิก';
                        }

                        $employee = $conn->query("SELECT * FROM tb_employee WHERE employee_id = '$row[employee_id]'");
                        if ($employee->num_rows >= 1) {
                            $row_employee = $employee->fetch_array();
                            $emp_name = $row_employee['fname'] . ' ' . $row_employee['lname'];
                        } else {
                            $emp_name = '';
                        }
                    ?>
                        <tr>
                            <td class="text-center"><?= $i++; ?></td>
                            <td><?= $row['sale_code']; ?></td>
                            <td><?= $name; ?></td>
                            <td><?= $emp_name ?></td>
                            <td><?= $row['by_date']; ?></td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-primary btn-xs" onclick="showDetail(<?= $row['sale_id'] ?>)"><i class="mdi mdi-eye"></i></button>
                                    <button class="btn btn-danger" onclick="DeleteStory(<?= $row['sale_id'] ?>)"><i class="mdi mdi-delete-forever"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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

<div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
            success: function(res) {
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