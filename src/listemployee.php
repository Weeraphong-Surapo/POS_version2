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
            <h1 class="text-black">รายชื่อพนักงาน</h1>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th width="15%">ลำดับ</th>
                        <th>ตำแหน่ง</th>
                        <th class="text-center">จำนวน/คน</th>
                        <th class="text-center" width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $count_row = 0;
                    $all_position = $conn->query("SELECT * FROM tb_position");
                    foreach ($all_position as $row) :
                        $count_employee = $conn->query("SELECT * FROM tb_employee WHERE position_id = '$row[position_id]'");
                        $result_count = $count_employee->num_rows;
                    ?>
                        <tr id="row-<?= $row['position_id'] ?>">
                            <td><?= $i++ ?></td>
                            <td><?= $row['position_name'] ?></td>
                            <td class="text-center"><?= $result_count ?></td>
                            <td class="d-flex justify-content-center">
                                <div class="btn btn-group">
                                    <button class="btn btn-primary btn-xs" onclick="onLoadUrl(<?= $row['position_id'] ?>)"><i class="mdi mdi-eye"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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

    function onLoadUrl(id){
        window.location.href="#/detailgroup.php?id="+id;
    }   

</script>