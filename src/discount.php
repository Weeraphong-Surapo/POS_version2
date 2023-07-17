<?php include("../config/connect.php"); ?>
<style>
    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 5px !important;
    }
</style>
<div class="card">
    <div class="card-body">
        <h1 class="text-black">ส่วนลดสินค้า</h1>
        <hr>
        <div class="table-responsive">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th width="10%" style="text-align: center;">ลำดับ</th>
                        <th>ชื่อสินค้า</th>
                        <th style="text-align: right;">ราคาขาย/บาท</th>
                        <th style="text-align: right;">ส่วนลด/บาท</th>
                        <th style="text-align: right;">ราคาหักลด/บาท</th>
                        <th width="15%" style="text-align: center;">แก้ไขส่วนลด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $count_row = 0;
                    $all_product = $conn->query("SELECT * FROM tb_product");
                    foreach ($all_product as $row) :
                    ?>
                        <tr id="row-<?= $row['product_id'] ?>">
                            <td style="text-align: center;"><?= $i++ ?></td>
                            <td><?= $row['product_name'] ?></td>
                            <td style="text-align: right;"><?= number_format($row['product_price'], 2); ?></td>
                            <td style="text-align: right;"><?= $row['product_discount'] != null ? number_format($row['product_discount'], 2) : '0.00'; ?></td>
                            <td style="text-align: right;"><?= number_format($row['product_price'] - $row['product_discount'], 2); ?></td>
                            <td style="text-align: center;">
                                <div class="btn btn-group">
                                    <button class="btn btn-warning btn-xs" onclick="editDiscount(<?= $row['product_id'] ?>,<?= $count_row++ ?>)"><i class="mdi mdi-lead-pencil"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalDiscount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black" id="text-discount">แก้ไชส่วนลด</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="FormDiscount">
                <input type="hidden" name="id_row" id="id_row">
                <input type="hidden" name="" id="id">
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="" class="text-black">รหัสสินค้า</label>
                        <input type="text" name="" id="product_code" class="form-control" disabled>
                    </div>
                    <div class="mb-2">
                        <label for="" class="text-black">สินค้า</label>
                        <input type="text" name="" id="product_name" class="form-control" disabled>
                    </div>
                    <div class="mb-2">
                        <label for="" class="text-black">ราคาสินค้า</label>
                        <input type="text" name="" id="product_price" class="form-control" disabled>
                    </div>
                    <div class="mb-2">
                        <label for="" class="text-black">ลดราคา</label>
                        <input type="number" min="0" name="" id="product_discount" class="form-control" placeholder="ระบุจำนวนลดราคา">
                    </div>
                    <div class="mb-2">
                        <label for="" class="text-black">ราคาหลังมีส่วนลด</label>
                        <input type="number" name="" id="product_after_discount" class="form-control" value="0.00" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
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

    var price_product = 0;
    var value = 0;


    function editDiscount(id, id_row) {
        $('#id_row').val(id_row)
        let option = {
            url: 'controller/action.php',
            type: 'post',
            dataType: 'json',
            data: {
                id: id,
                editDiscount: 1
            },
            success: (res) => {
                price_product = res.product_price;
                $('#id').val(res.product_id);
                $('#product_code').val(res.product_code);
                $('#product_name').val(res.product_name);
                $('#product_discount').val(res.product_discount);
                $('#product_price').val(res.product_price);
                $('#product_after_discount').val(res.product_price - res.product_discount);
                $('#ModalDiscount').modal('show');
            }
        }
        $.ajax(option)
    }

    $('#product_discount').keyup((e) => {
        value = e.target.value;
        $('#product_after_discount').val(price_product - value)
    })

    $('#FormDiscount').submit((e) => {
        e.preventDefault();
        let discount = price_product - value;
        let id = $('#id').val()
        let option = {
            url: 'controller/action.php',
            type: 'post',
            dataType: 'json',
            data: {
                id: id,
                discount_value: $('#product_discount').val(),
                updateDiscount: 1
            },
            success: (res) => {
                let id_rows = $('#id_row').val()

                var row = table.row(id_rows);

                row.node().id = 'row-' + id_rows;
                
                row.data([parseInt(id_rows) + 1, res.product_name, changeStrToNumber(res.product_price), changeStrToNumber(res.product_discount),changeStrToNumber(discount) , '<div class="btn btn-group"><button class="btn btn-warning btn-xs" onclick="editDiscount(' + res.row_id + ',' + id_rows + ')"><i class="mdi mdi-lead-pencil"></i></button></div>']);

                row.invalidate();

                table.draw(false);
                swalAlert('อัพเดตสำเร็จ', 'success');
                $('#ModalDiscount').modal('hide')
            }
        }
        if (discount <= 0) {
            swalAlert('ลดมากกว่าราคาสินค้าไม่ใด้!', 'warning')
        } else {
            $.ajax(option)
        }
    })

    function swalAlert(title, type) {
        Swal.fire(
            title,
            '',
            type
        )
    }

    function changeStrToNumber(num) {
        const options = { style: 'decimal', currency: 'THB',  minimumFractionDigits: 2, };
        const formattedNumber = parseFloat(num).toLocaleString('th-TH', options);
        return formattedNumber;
    }
</script>