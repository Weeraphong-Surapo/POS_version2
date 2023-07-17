<?php 
$get_id = explode('.',$_GET['id']);
$id = $get_id[0];
?>
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
        <a href="#" onclick="history.back()" class="btn btn-secondary btn-sm">กลับ</a>
            <button class="btn btn-info " onclick="openModal()">เพิ่มกลุ่มลูกค้า</button>
        </div>
        <hr>
        <div class="table-responsive mt-3">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อลูกค้า</th>
                        <th>วันที่ซื้อ</th>
                        <th width="15%">รายละเอียด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $all_sale = $conn->query("SELECT * FROM tb_sale WHERE customer_id = '$id'");
                    foreach ($all_sale as $row) :
                    ?>
                        <tr id="row-<?= $row['sale_id']; ?>">
                            <td><?= $i++; ?></td>
                            <td><?= $row['by_date']; ?></td>
                            <td><?= $row['by_date'] ?></td>
                            <td>
                                <a onclick="showDetail(<?= $row['sale_id']; ?>)" class="btn btn-primary btn-xs"><i class="mdi mdi-eye"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="textcategory">เพิ่มประเภท</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="FormSubCategory">
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
    var table = $('#myTable').DataTable({});

    function showDetail(id){
        let option = {
            url:'controller/action.php',
            type:'post',
            data:{
                id:id,
                showDetail:1
            },
            success:function(res){
                $('#ModalDetail').modal('show')
            }
        }
        $.ajax(option)
    }
</script>