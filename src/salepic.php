<?php include('../config/connect.php') ?>
<style>
    .main-cart {
        height: 670px;
    }


    .cc-box-cart {
        overflow-y: scroll;
        max-height: 550px;
        height: 100%;
    }

    .cc-box-cart {
        overflow-y: scroll;
        max-height: 409px;
        height: 100%;
    }

    .pagination {
        display: inline-block;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .pagination li {
        display: inline-block;
        /* margin-top: 1px; */
        /* margin-bottom: 1px; */
    }

    .pagination li button {
        text-decoration: none;
        color: #337ab7;
        padding: 5px 10px;
        border: 1px solid #ddd;
    }

    .pagination li.active button {
        background-color: #337ab7;
        color: white;
        border: 1px solid #337
    }

    .pagination button {
        text-decoration: none;
        color: #337ab7;
        padding: 5px 10px;
        border: 1px solid #ddd;
    }

    .pagination button.active {
        background-color: #337ab7;
        color: white;
        border: 1px solid #337
    }

    .box-product {
        cursor: pointer;
    }

    .box-product:hover {
        transition: all .3s ease;
        box-shadow: 0px 1px 3px black;
    }

    .box-product:active {
        cursor: pointer;
        box-shadow: 0px 1px 10px black;
    }

    .form-control {
        border-left: 3px solid #337ab7 !important;
    }

    .show-center {
        text-align: center;
    }
</style>
<div id="app">
    <div class="card p-2">
        <div class="row">
            <div class="col-lg-7 col-12 col-md-7">
                <div class="row mb-2">
                    <div class="col-3">
                        <select class="form-control" v-model="searchCategory">
                            <option value="" selected>ทั้งหมด</option>
                            <option :value="category.category_id" v-for="(category,index) in AllCategory" :key="index">{{ category.category_name }}</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control" placeholder="ค้นหาสินค้า" id="cc-search">
                    </div>
                    <div class="col-5 d-flex">
                        <input type="text" class="form-control" :value="custoemr != '' ? custoemr : 'เลือกลูกค้า'" disabled>
                        <button type="button" class="btn btn-info" @click="openCustomer()" style="padding: 16px;margin-left: 5px;">ลูกค้า</button>
                        <button type="button" class="btn btn-secondary" @click="clearCustomer" style="padding: 16px;margin-left: 5px;"><i class="mdi mdi-refresh"></i></button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div v-for="product in currentPageProducts" :key="product.id">
                        </div>
                        <div v-if="totalPages > 1">
                            <ul class="pagination">
                                <button v-if="currentPage !== 1" @click="currentPage -= 1">ก่อนหน้า</button>
                                <button v-for="page in endPage - startPage + 1" :key="page" :class="{ active: currentPage === startPage + page - 1 }" @click="currentPage = startPage + page - 1">{{ startPage + page - 1 }}</button>
                                <button v-if="currentPage !== totalPages" @click="currentPage += 1">ถัดไป</button>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <form action="" class="d-flex justify-content-between align-items-center">
                            <select name="" id="" class="form-control" @change="onChangePrice" :disabled="cartProducts.length >= 1">
                                <!-- <option value="" selected disabled>เลือกราคาขาย</option> -->
                                <option value="product_price" selected>ราคาปกติ ( เริ่มต้น )</option>
                                <option value="product_wholesale">ราคาส่ง </option>
                                <option value="product_retail">ราคาปลีก </option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div v-if="searchCategory.length == 0 || searchCategory.length > 0" class="col-lg-3 mb-2 col-md-4 h-100 cc-show" v-for="(product,index) in currentPageProducts" :key="index">
                        <div class="card rounded box-product " @click="addToCart(product)">
                            <img :src="'assets/upload/' + product.product_img" alt="" class="card-img-top" width="60px" height="90px">
                            <div class="card-footer d-flex justify-content-center">
                                <span class="text-primary">{{ product.product_name }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-5 col-12 col-md-5 main-cart" style="border: 1px solid;">
                <form action="" method="post" @submit="findItem" class="d-flex justify-content-between align-items-center border-bottom p-1">
                    <input type="text" v-model="searchValue" class="form-control " placeholder="รหัสสินค้า หรือ สแกน Barcode">
                    <button class="btn btn-success" style="padding: 16px;margin-left: 5px;"><i class="mdi mdi-magnify"></i></button>
                </form>
                <span class="text-danger" v-if="error == 'ไม่พบสินค้า'">* {{ error }}</span>
                <span v-else class="text-success">* {{ error }}</span>
                <div class="table-responsive cc-box-cart">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th class="show-center">ชื่อสินค้า</th>
                                <th class="show-center" width="10%">จำนวน</th>
                                <th class="show-right">ราคารวม/บาท</th>
                                <th class="show-center">ลบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in cartProducts" :key="index">
                                <td class="show-center">{{ item.product_name }}</td>
                                <td class="show-center" width="20%">
                                    <button class="btn text-black" @click="minusProduct(item)">-</button>
                                    <span class="text-primary">{{ item.product_qty }}</span>
                                    <button class="btn text-black" @click="plusProduct(item)">+</button>
                                </td>
                                <td class="show-right">{{ new Intl.NumberFormat().format(item.product_price * item.product_qty - item.product_discount * item.product_qty) }}</td>
                                <td class="show-center"><button class="btn btn-danger" @click="removeProduct(item)"><i class="mdi mdi-close"></i></button></td>
                            </tr>
                            <tr v-if="checkvat">
                                <td colspan="2" class="show-right">ภาษีมูลค่าเพิ่ม (VAT 7 %)</td>
                                <td colspan="1" class="show-right">{{ new Intl.NumberFormat().format(vatProduct) }}</td>
                                <td colspan="1" class="show-right"></td>
                            </tr>
                            <tr v-if="checkvat">
                                <td colspan="2" class="show-right">รวมราคา VAT </td>
                                <td colspan="1" class="show-right">{{ new Intl.NumberFormat().format(pricesumVat) }}</td>
                                <td colspan="1" class="show-right"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    <div class="d-flex justify-content-between">
                        <h4 class="text-black">ราคารวม</h4>
                        <button class="btn btn-primary" @click="Checkvat">{{ checkvat == false ? 'เพิ่ม VAT 7 %' : 'ปิด VAT' }}</button>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <h2 v-if="checkvat" class="text-danger">฿ {{ new Intl.NumberFormat().format(pricesumVat) }}</h2>
                        <h2 v-else class="text-danger">฿ {{ new Intl.NumberFormat().format(totalPriceProduct) }}</h2>
                        <p class="text-danger">{{ countCart }} รายการ</p>
                    </div>
                    <button class="btn btn-info w-100 p-3 mt-1" style="border-radius: 20px;" @click="chageMoney">ชำระเงิน</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalSalepic" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="textcategory"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-7 col-md-7">
                            <div class="card">
                                <div class="card-header alert alert-primary text-center">รายละเอียดการชำระเงิน</div>
                                <div class="row p-2" style="text-align: right;">
                                    <div class="col-5 mb-2">จำนวนเงิน</div>
                                    <div class="col-7 mb-2"><input type="text" disabled :value="checkvat ? pricesumVat : totalPriceProduct" style="text-align: right;" class="form-control"></div>
                                    <div class="col-5 mb-2">รับเงิน</div>
                                    <div class="col-7 mb-2"><input type="text" v-model="currentValue" disabled style="text-align: right;" class="form-control"></div>
                                    <div class="col-5 mb-2">ทอนเงิน</div>
                                    <div class="col-7 mb-2"><input type="text" disabled :value="changeMo" style="text-align: right;" class="form-control"></div>
                                    <div class="col-5 mb-2">การรับเงิน</div>
                                    <div class="col-7 mb-2">
                                        <select name="" id="" class="form-control" @change="onChange($event)">
                                            <option value="cash">เงินสด</option>
                                            <option value="payment">โอนเงิน/พร้อมเพย์</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5">
                            <table style="width: 100%;">
                                <tr>
                                    <td><button class="btn btn-secondary" @click="addnum(1)">1</button></td>
                                    <td><button class="btn btn-secondary" @click="addnum(2)">2</button></td>
                                    <td><button class="btn btn-secondary" @click="addnum(3)">3</button></td>
                                </tr>
                                <tr>
                                    <td><button class="btn btn-secondary" @click="addnum(4)">4</button></td>
                                    <td><button class="btn btn-secondary" @click="addnum(5)">5</button></td>
                                    <td><button class="btn btn-secondary" @click="addnum(6)">6</button></td>
                                </tr>
                                <tr>
                                    <td><button class="btn btn-secondary" @click="addnum(7)">7</button></td>
                                    <td><button class="btn btn-secondary" @click="addnum(8)">8</button></td>
                                    <td><button class="btn btn-secondary" @click="addnum(9)">9</button></td>
                                </tr>
                                <tr>
                                    <td><button class="btn btn-secondary" @click="addnum(0)">0</button></td>
                                    <td><button class="btn btn-warning" @click="deleteNumber()"><i class="mdi mdi-keyboard-backspace" style="font-size: 1px;"></i></button></td>
                                    <td><button class="btn btn-danger" @click="clearnum()">C</button></td>
                                </tr>
                            </table>
                            <div class="my-2">
                                <input type="text" v-model="currentValue" class="form-control" placeholder="ช่องระบุจำนวนเงิน" style="text-align: right;">
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button class="btn btn-success" @click="saleOrder()">ตกลง</button>
                                <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">ยกเลิก</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalPrint" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="textcategory">ปริ้นใบเสร็จ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <th>สินค้า</th>
                            <th style="text-align:right">จำนวน</th>
                        </tr>

                        <tr v-for="printCart in logCart">
                            <td>{{ printCart.product_name }}</td>
                            <td style="text-align:right">{{ printCart.product_qty }}</td>
                        </tr>

                        <tr>
                            <td>รวมเงินทั้งสิ้น</td>
                            <td style="text-align:right">{{ new Intl.NumberFormat().format(logvatsum) }}</td>
                        </tr>
                        <tr>
                            <td>รับเงิน</td>
                            <td style="text-align:right">{{ new Intl.NumberFormat().format(logGetMo) }}</td>
                        </tr>
                        <tr>
                            <td>เงินทอน</td>
                            <td style="text-align:right">{{ new Intl.NumberFormat().format(logChange) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-info" @click="printBill()"><i class="mdi mdi-printer"></i>ใบเสร็จย่อ</button>
                    <button class="btn btn-primary" @click="printBillFull()"><i class="mdi mdi-printer"></i>ใบเสร็จเต็ม</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalCustomer" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="textcategory">เลือกลูกค้า</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">เลือก</th>
                                        <th class="text-center">ชื่อ</th>
                                        <th class="text-center">ที่อยู่</th>
                                        <th class="text-center">กลุ่มลูกค้า</th>
                                    </tr>
                                <tbody>
                                    <?php
                                    $customer = $conn->query("SELECT * FROM tb_customer");
                                    foreach ($customer as $row) :
                                        $group = $conn->query("SELECT * FROM tb_group_customer WHERE group_id = '$row[group_id]'");
                                        $row_group = $group->fetch_array();
                                    ?>
                                        <tr>
                                            <td><button class="btn btn-xs btn-success" @click="selectCustomer('<?= $row['customer_fname'] ?>','<?= $row['customer_lname'] ?>',<?= $row['customer_id'] ?>)">เลือก</button></td>
                                            <td><?= $row['customer_fname'] . ' ' . $row['customer_lname'] ?></td>
                                            <td><?= $row['customer_address'] ?></td>
                                            <td><?= $row_group['group_name'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<script src="assets/vue/salepic.js"></script>
<script>
    $('#cc-search').keyup((e) => {
        const show = document.querySelectorAll(".cc-show")
        if (e.target.value != '') {
            show.forEach(s => {
                if (s.textContent.toLocaleLowerCase().startsWith(` ${e.target.value}`)) {
                    s.classList.add("d-block")
                } else {
                    s.classList.add("d-none")
                }
            })
        } else {
            $('.cc-show').removeClass("d-none")
        }
    })
</script>