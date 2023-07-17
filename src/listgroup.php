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
                        <th>กลุ่มลูกค้า</th>
                        <th>จำนวน/คน</th>
                        <th width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $all_tb_group_customer = $conn->query("SELECT * FROM tb_group_customer");
                    foreach ($all_tb_group_customer as $row) :
                        $count_customer = $conn->query("SELECT count(customer_id) as count_customer FROM tb_customer WHERE group_id = '$row[group_id]'");
                        $result = $count_customer->fetch_array();
                        $count_result = $result['count_customer'];
                    ?>
                        <tr id="row-<?= $row['group_id'] ?>">
                            <td><?= $i++ ?></td>
                            <td><?= $row['group_name'] ?></td>
                            <td><?= $count_result; ?></td>
                            <td>
                                <div class="btn btn-group">
                                    <button class="btn btn-primary btn-xs" onclick="onLoadUrl(<?= $row['group_id'] ?>)"><i class="mdi mdi-eye"></i></button>
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

    function onLoadUrl(id) {
        window.location.href = "#/listcustomer.php?id=" + id;
    }
</script>