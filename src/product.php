<?php
include("../config/connect.php");

$date = date('Y-m-d');

$startTimeStamp = strtotime($date);
?>
<style>
    #myTable_filter {
        display: none !important;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end">
            <button class="btn btn-success" onclick="openModalExcel()">นำรายชื่อสินค้า Excel เข้า</button>
            <button class="btn btn-info " onclick="openModal()">เพิ่มสินค้า</button>
        </div>
        <br>

        <div class="table-responsive">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>รูปภาพ</th>
                        <th>บาร์โค้ด</th>
                        <th>สินค้า</th>
                        <th>ราคา</th>
                        <th>ราคาส่ง</th>
                        <th>ราคาปลีก</th>
                        <th>ต้นทุน</th>
                        <th>สถานะ</th>
                        <th>วันหมดอายุ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $all_product = $conn->query("SELECT * FROM `tb_product`");
                    foreach ($all_product as $row) {
                        if($row['product_exp'] == '' || $row['product_exp'] == NULL){
                            $datetime_product = "";
                        }else{
                            $datetime_product = $row['product_exp'];
                        }
                        $endTimeStamp = strtotime($datetime_product);

                        $timeDiff = abs($endTimeStamp - $startTimeStamp);

                        $numberDays = $timeDiff / 86400;

                        $numberDays = intval($numberDays);
                    ?>
                        <tr id="row-<?= $row['product_id'] ?>">
                            <td><?= $i++; ?></td>
                            <td><img src="assets/upload/<?= $row['product_img']; ?>" alt=""></td>
                            <td><button class="btn btn-secondary btn-xs" onclick="OpenBarcode(<?= $row['product_id'] ?>,'<?= $row['product_code'] ?>')"><i class="mdi mdi-barcode">barcode</i></button></td>
                            <td><?= $row['product_name']; ?></td>
                            <td><?= number_format($row['product_price']); ?></td>
                            <td><?= number_format($row['product_wholesale']); ?></td>
                            <td><?= number_format($row['product_retail']); ?></td>
                            <td><?= number_format($row['product_cost']); ?></td>
                            <td><?php if ($row['product_qty'] > 5) {
                                    echo '<label class="badge badge-success">ปกติ</label>';
                                } else if ($row['product_qty'] <= 5 && $row['product_qty'] >= 1) {
                                    echo '<label class="badge badge-warning">ไกล้หมด</label>';
                                } else {
                                    echo '<label class="badge badge-danger">หมด</label>';
                                } ?>
                            </td>
                            <td>
                                <?php if ($numberDays > 8) {
                                    echo '<label class="badge badge-success">ปกติ</label>';
                                } else if ($numberDays <= 7 && $numberDays >= 1) {
                                    echo '<label class="badge badge-secondary">ไกล้หมดอายุ</label>';
                                } else {
                                    echo '<label class="badge badge-danger">หมดอายุ</label>';
                                } ?>
                            </td>
                            <td>
                                <div class="btn btn-group">
                                    <button class="btn btn-warning btn-xs" onclick="EditProduct(<?= $row['product_id'] ?>)"><i class="mdi mdi-lead-pencil"></i></button>
                                    <button class="btn btn-danger btn-xs" onclick="DeleteProduct(<?= $row['product_id'] ?>)"><i class="mdi mdi-delete-forever"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalImportExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="text-product">นำเข้าสินค้ารูปแบบ Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="formExcel">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-2">
                        <button type="submit" class="btn btn-primary " id="btnAddproduct">บันทึก</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <span class="text-danger text-center">ตัวอย่างไฟล์</span>
                <img src="assets/images/exemple_import.png" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="text-product">เพิ่มสินค้า</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="FormProduct">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">รหัสสินค้า</label>
                                <input type="text" class="form-control" id="code" placeholder="รหัสสินค้า">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">ชื่อสินค้า</label>
                                <input type="text" class="form-control" id="product_name" placeholder="ชื่อสินค้า">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">หมวดหมู่</label>
                                <select name="product_category" id="product_category" class="form-control">
                                    <option value="" disabled selected>เลือกหมวดหมู่</option>
                                    <?php
                                    $all_category = $conn->query("SELECT * FROM `tb_category` WHERE 1");
                                    foreach ($all_category as $row) {
                                        echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                                    };
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">ประเภท</label>
                                <select name="product_sub" id="product_sub" class="form-control" disabled>
                                    <option value="" selected disabled>เลือกประเภท</option>
                                    <?php 
                                    $sub = $conn->query("SELECT * FROM tb_sub_category");
                                    foreach($sub as $row){
                                        echo '<option value="'.$row['sub_id'].'" selected disabled>'.$row['sub_name'].'</option>';
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">ราคาขาย</label>
                                <input type="number" min="1" class="form-control" id="product_price" placeholder="ราคาขาย">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">ต้นทุน</label>
                                <input type="number" min="1" class="form-control" id="product_cost" placeholder="ต้นทุน">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">ราคาปลีก</label>
                                <input type="number" min="1" class="form-control" id="product_wholesale" placeholder="ราคาปลีก">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">ราคาส่ง</label>
                                <input type="number" min="1" class="form-control" id="product_retail" placeholder="ราคาส่ง">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">จำนวน</label>
                                <input type="number" min="1" class="form-control" id="product_qty" placeholder="จำนวน">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">ที่จัดเก็บ</label>
                                <input type="text" class="form-control" id="product_stock" placeholder="ที่จัดเก็บ">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="">วันหมดอายุ</label>
                                <input type="date" class="form-control" id="product_exp" placeholder="วันหมดอายุ">
                            </div>
                        </div>
                        <div class="col-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="">รูปภาพ</label>
                                <input accept="image/*" type='file' id="imgInp" class="form-control" />
                            </div>
                        </div>
                        <div class="col-12">
                            <img id="blah" src="#" alt="your image" class="d-none img-fluid" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalBarcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="text-product"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body justify-content-center">
                <input type="hidden" name="" id="print_id">
                <div class="row">
                    <div class="col-12">
                        <p id="codeBarcode" class="text-black"></p>
                    </div>
                    <div class="col-12">
                        <h4 class="text-center mt-3 text-black">จำนวน</h4>
                        <select name="" id="rowqty" class="form-control">
                            <option value="" selected disabled>เลือกจำนวน</option>
                            <?php
                            $array_page = array(1 => '1 แถว (3)', 2 => '2 แถว (6)', 3 => '3 แถว (9)', 4 => '4 แถว (12)', 5 => '5 แถว (15)', 6 => '6 แถว (18)', 7 => '7 แถว (21)', 8 => '8 แถว (24)', 9 => '9 แถว (27)', 10 => '10 แถว (30)', 11 => '11 แถว (33)');
                            foreach ($array_page as $k => $v) :
                            ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-primary" onclick="printBarcode()">พิมพ์</button>
                </form>
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

    function OpenBarcode(id, code) {
        $('#print_id').val(id)
        $('#codeBarcode').text('รหัสบาร์โค้ด : ' + code)
        $('#ModalBarcode').modal('show');
    }

    function printBarcode() {
        let rowqty = $('#rowqty').val()
        let id = $('#print_id').val()
        const pdfUrl = "controller/barcode.php?id=" + id + "&row=" + rowqty;
        const pdfWindow = window.open(pdfUrl, "_blank");
        pdfWindow.print().focus();
    }


    $('#formExcel').submit((e) => {
        e.preventDefault();
        let fd = new FormData();
        var files = $('#file')[0].files;
        fd.append('file', files[0])
        fd.append('Submit', 1)
        let option = {
            type: 'post',
            url: 'controller/library/excelUpload.php',
            beforeSend: function(xhr) {
                $('#btn-modal').text('กำลังบันทึก...')
            },
            data: fd,
            contentType: false,
            processData: false,
            success: function(res) {
                swalAlert('นำเข้าสินค้าสำเร็จ', 'success');
                setTimeout(() => {
                    location.reload()
                }, 900)
            }
        }
        $.ajax(option)
    })

    function openModal() {
        $('#id').val('')
        $('#code').val('')
        $('#product_name').val('')
        $('#product_category').val('')
        $('#product_sub').val('')
        $('#product_price').val('')
        $('#product_cost').val('')
        $('#product_wholesale').val('')
        $('#product_retail').val('')
        $('#product_stock').val('')
        $('#product_qty').val('')
        $('#product_exp').val('')
        $('#blah').addClass('d-none')
        $('#ModalProduct').modal('show')
    }

    function openModalExcel() {
        $('#ModalImportExcel').modal('show')
    }

    function DeleteProduct(id) {
        let option = {
            type: 'post',
            url: 'controller/action.php',
            data: {
                id: id,
                removeProduct: 1
            },
            success: function(res) {
                table.row('#row-' + id).remove().draw();
                swalAlert('ลบสินค้าสำเร็จ', 'success');
            }
        }
        Swal.fire({
            title: 'ต้องการลบสินค้า?',
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

    function EditProduct(id) {
        let option = {
            type: 'post',
            url: 'controller/action.php',
            dataType: 'json',
            data: {
                id: id,
                editProduct: 1
            },
            success: function(res) {
                $('#id').val(res.product_id)
                $('#code').val(res.product_code)
                $('#product_name').val(res.product_name)
                $('#product_category').val(res.product_category)
                $('#product_sub').val(res.product_sub)
                $('#product_price').val(res.product_price)
                $('#product_cost').val(res.product_cost)
                $('#product_wholesale').val(res.product_wholesale)
                $('#product_retail').val(res.product_retail)
                $('#product_stock').val(res.product_stock)
                $('#product_qty').val(res.product_qty)
                $('#product_exp').val(res.product_exp)
                $('#blah').attr('src','assets/upload/'+res.product_img)
                $('#blah').removeClass('d-none')
                $('#ModalProduct').modal('show')
            }
        }
        $.ajax(option)
    }


    $('#FormProduct').submit((e) => {
        e.preventDefault()
        let fd = new FormData()
        let id = $('#id').val()
        let code = $('#code').val()
        let product_name = $('#product_name').val()
        let product_category = $('#product_category').val()
        let product_sub = $('#product_sub').val()
        let product_price = $('#product_price').val()
        let product_cost = $('#product_cost').val()
        let product_exp = $('#product_exp').val()
        let product_wholesale = $('#product_wholesale').val()
        let product_retail = $('#product_retail').val()
        let product_stock = $('#product_stock').val()
        let product_qty = $('#product_qty').val()
        var files = $('#imgInp')[0].files;

        fd.append('id', id)
        fd.append('code', code)
        fd.append('product_name', product_name)
        fd.append('product_category', product_category)
        fd.append('sub',product_sub)
        fd.append('product_price', product_price)
        fd.append('product_cost', product_cost)
        fd.append('product_wholesale', product_wholesale)
        fd.append('product_retail', product_retail)
        fd.append('product_stock', product_stock)
        fd.append('product_exp', product_exp)
        fd.append('product_qty', product_qty)
        fd.append('file', files[0]);
        fd.append('addProduct', 1)
        let option = {
            type: 'post',
            url: 'controller/action.php',
            beforeSend: function(xhr) {
                $('#btn-modal').text('กำลังบันทึก...')
            },
            data: fd,
            contentType: false,
            processData: false,
            success: function(res) {
                if (res == 0) {
                    $('#ModalProduct').modal('hide')
                    swalAlert('บันทึกสำเร็จ', 'success');
                    setTimeout(() => {
                            location.reload()
                        }, 700)
                    } else if(res == 1){
                    $('#ModalProduct').modal('hide')
                    swalAlert('อัพเดตสำเร็จ', 'success');
                    setTimeout(() => {
                        location.reload()
                    }, 700)
                }else{
                    swalAlert('เกิดข้อผิดพลาด', 'error');
                }
            }
        }
        if (code != '' && product_name != ''  && product_price != '' && product_cost != '' && product_wholesale != '' && product_retail != '') {
            $.ajax(option)
        } else {
            swalAlert('กรุณากรอกข้อมูลให้ครบถ้วน', 'warning')
        }
    })

    function addrow(r1, r2, r3, r4, r5, r6, r7, r8) {
        table.row.add([r1, r2, r3, r4, r5, r6, r7, r8, '<div class="btn btn-group"><button class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button><button class="btn btn-danger btn-xs" onclick="DeleteProduct()"><i class="mdi mdi-delete-forever"></i></button></div>']).draw();
    }

    function swalAlert(title, type) {
        Swal.fire(
            title,
            '',
            type
        )
    }

    imgInp.onchange = evt => {

        const [file] = imgInp.files
        if (file) {
            blah.src = URL.createObjectURL(file)
            $('#blah').removeClass('d-none')
        }
    }

    $('#product_category').change((e)=>{
        let category = e.target.value
        let option = {
            type: 'post',
            url: 'controller/action.php',
            data:{
                category:category,
                findSubCategory:1
            },
            success:function(res){
                $('#product_sub').removeAttr('disabled')
                $('#product_sub').html(res)
            }
        }
        $.ajax(option)
    })
</script>