<?php
session_start();
include('../config/connect.php');
$data = [];


if (isset($_POST['addcategory'])) {
    if (!empty($_POST['id'])) {
        $update = $conn->query("UPDATE tb_category SET category_name = '$_POST[category]' WHERE category_id = '$_POST[id]'");
        $check_row = $conn->query("SELECT * FROM tb_category");
        $count = $check_row->num_rows;
        $data = array(
            'row' => $count,
            'row_id' => $_POST['id'],
            'status' => 1
        );
    } else {
        $query = $conn->query("INSERT INTO `tb_category`(`category_name`) VALUES ('$_REQUEST[category]')");
        $row_id = $conn->insert_id;
        $check_row = $conn->query("SELECT * FROM tb_category");
        $count = $check_row->num_rows;
        $data = array(
            'row' => $count,
            'row_id' => $row_id,
            'status' => 0
        );
    }
    echo json_encode($data);
}

if (isset($_POST['removeCategory'])) {
    $check_category = $conn->query("SELECT * FROM tb_product WHERE product_category = '$_REQUEST[id]'");
    if($check_category->num_rows >= 1){
        echo 0;
    }else{
        echo 1;
        $query = $conn->query("DELETE FROM tb_category WHERE category_id = '$_REQUEST[id]'");
    }
}

if (isset($_POST['removeProduct'])) {
    $query = $conn->query("DELETE FROM tb_product WHERE product_id = '$_REQUEST[id]'");
}

if (isset($_POST['addProduct'])) {
    $category = $_REQUEST['product_category'];
    if (!empty($_POST['id'])) {
        echo 1;
        if (!empty($_FILES['file']['name'])) {
            $file_img = rand(10000000, 99999999) . '-' . $_FILES['file']['name'];
            $location = '../assets/upload/';
            $nameFile =  array('png', 'jpg', 'pdf', 'webp', 'jpeg');
            $nameFileImg = pathinfo($file_img, PATHINFO_EXTENSION);
            if (!in_array($nameFileImg, $nameFile)) {
                echo "ภาพไม่ถูก";
            } else {
                move_uploaded_file($_FILES['file']["tmp_name"], $location . $file_img);
                $sql = "UPDATE `tb_product` SET 
                `product_code` = '$_REQUEST[code]',
                `product_name` = '$_REQUEST[product_name]',
                `product_category` = $category,
                `product_sub` = $_REQUEST[sub],
                `product_price` = '$_REQUEST[product_price]',
                `product_img` = '$file_img',
                `product_cost` = '$_REQUEST[product_cost]',
                `product_wholesale` = '$_REQUEST[product_wholesale]',
                `product_retail` = '$_REQUEST[product_retail]',
                `product_qty` = '$_REQUEST[product_qty]',
                `product_stock` = '$_REQUEST[product_stock]',
                `product_exp` = '$_REQUEST[product_exp]'
                WHERE product_id = '$_POST[id]'";
            }
        } else {
            $sql = "UPDATE `tb_product` SET 
            `product_code` = '$_REQUEST[code]',
            `product_name` = '$_REQUEST[product_name]',
            `product_category` = $category,
            `product_sub` = $_REQUEST[sub],
            `product_price` = '$_REQUEST[product_price]',
            `product_cost` = '$_REQUEST[product_cost]',
            `product_wholesale` = '$_REQUEST[product_wholesale]',
            `product_retail` = '$_REQUEST[product_retail]',
            `product_qty` = '$_REQUEST[product_qty]',
            `product_stock` = '$_REQUEST[product_stock]',
            `product_exp` = '$_REQUEST[product_exp]'
            WHERE product_id = '$_POST[id]'";
        }
    } else {
        echo 0 ;
        if (!empty($_FILES['file']['name'])) {
            $file_img = rand(10000000, 99999999) . '-' . $_FILES['file']['name'];
            $location = '../assets/upload/';
            $nameFile =  array('png', 'jpg', 'pdf', 'webp', 'jpeg');
            $nameFileImg = pathinfo($file_img, PATHINFO_EXTENSION);
            if (!in_array($nameFileImg, $nameFile)) {
                echo "ภาพไม่ถูก";
            } else {
                move_uploaded_file($_FILES['file']["tmp_name"], $location . $file_img);
                $sql = "INSERT INTO `tb_product`(`product_code`,`product_name`, `product_category`,`product_sub`,`product_price`, `product_img`, `product_cost`, `product_wholesale`, `product_retail`, `product_qty`, `product_stock`, `product_exp`) 
                        VALUES ('$_REQUEST[code]','$_REQUEST[product_name]',$category,$_REQUEST[sub],'$_REQUEST[product_price]','$file_img','$_REQUEST[product_cost]','$_REQUEST[product_wholesale]','$_REQUEST[product_retail]','$_REQUEST[product_qty]','$_REQUEST[product_stock]','$_REQUEST[product_exp]')";
            }
        } else {
            $sql = "INSERT INTO `tb_product`(`product_code`,`product_name`, `product_category`,`product_sub`,`product_price`,`product_cost`, `product_wholesale`, `product_retail`, `product_qty`, `product_stock`, `product_exp`) 
                            VALUES ('$_REQUEST[code]','$_REQUEST[product_name]',$category,'$_REQUEST[sub]','$_REQUEST[product_price]','$_REQUEST[product_cost]','$_REQUEST[product_wholesale]','$_REQUEST[product_retail]','$_REQUEST[product_qty]','$_REQUEST[product_stock]','$_REQUEST[product_exp]')";
        }
    }
    $query = $conn->query($sql);
    if(!$query){
        echo $sql;
    }
}

if (isset($_POST['editProduct'])) {
    $query = $conn->query("SELECT * FROM tb_product WHERE product_id = '$_POST[id]'");
    $row = $query->fetch_array();
    echo json_encode($row);
}

if (isset($_POST['addsubcategory'])) {
    if (!empty($_POST['id'])) {
        $update = $conn->query("UPDATE tb_sub_category SET category_id = '$_POST[category]', sub_name = '$_POST[subcategory]' WHERE sub_id = '$_POST[id]'");
        $category = $conn->query("SELECT * FROM tb_category WHERE category_id = '$_POST[category]'");
        $row_category = $category->fetch_array();
        $check_row = $conn->query("SELECT * FROM tb_sub_category");
        $count = $check_row->num_rows;
        $data = array(
            'category' => $row_category['category_name'],
            'row' => $count,
            'status' => 1
        );
    } else {
        $query = $conn->query("INSERT INTO tb_sub_category(category_id,sub_name) VALUES('$_POST[category]','$_POST[subcategory]')");
        $row_id = $conn->insert_id;
        $category = $conn->query("SELECT * FROM tb_category WHERE category_id = '$_POST[category]'");
        $row_category = $category->fetch_array();
        $check_row = $conn->query("SELECT * FROM tb_sub_category");
        $count = $check_row->num_rows;
        $row = $check_row->fetch_array();
        $data = array(
            'category' => $row_category['category_name'],
            'row' => $count,
            'row_id' => $row_id,
            'status' => 0
        );
    }
    echo json_encode($data);
}

if (isset($_POST['removeSubCategory'])) {
    $check_sub = $conn->query("SELECT * FROM tb_product WHERE product_sub = '$_REQUEST[id]'");
    if($check_sub->num_rows >= 1){
        echo 1;
    }else{
        $query = $conn->query("DELETE FROM tb_sub_category WHERE sub_id = '$_REQUEST[id]'");
    }
}

if (isset($_POST['editSubCate'])) {
    $query = $conn->query("SELECT * FROM tb_sub_category WHERE sub_id = '$_POST[id]'");
    $row = $query->fetch_array();
    echo json_encode($row);
}

if (isset($_POST['DeleteCustomer'])) {
    $delteCustomer = $conn->query("DELETE FROM tb_customer WHERE customer_id = '$_POST[id]'");
}

if (isset($_POST['addcustomer'])) {
    if (!empty($_POST['id'])) {
        $update = $conn->query("UPDATE tb_customer SET customer_fname = '$_POST[fname]' , customer_lname = '$_POST[lname]', customer_phone = '$_POST[phone]', customer_line = '$_POST[line]', customer_address = '$_POST[address]' WHERE customer_id = '$_POST[id]'");
        $check_row = $conn->query("SELECT * FROM tb_customer");
        $count = $check_row->num_rows;
        $data = array(
            'row' => $count,
            'status' => 1
        );
    } else {
        $date = date('d-m-y H:i');
        $card = rand(1000, 9999) . '-' . $date;
        $query = $conn->query("INSERT INTO tb_customer(card_number,customer_fname,customer_lname,customer_phone,customer_line,customer_address,group_id,created_at) 
                               VALUES('$card','$_POST[fname]','$_POST[lname]','$_POST[phone]','$_POST[line]','$_POST[address]','$_POST[group_customer]','$date')");
        $row_id = $conn->insert_id;
        $check_row = $conn->query("SELECT * FROM tb_customer");
        $count = $check_row->num_rows;
        $data = array(
            'row' => $count,
            'row_id' => $row_id,
            'status' => 0
        );
    }
    echo json_encode($data);
}

if (isset($_POST['editCustomer'])) {
    $query = $conn->query("SELECT * FROM tb_customer WHERE customer_id = '$_POST[id]'");
    $row = $query->fetch_array();
    echo json_encode($row);
}

if (isset($_POST['showDetail'])) {
    $order_sale = $conn->query("SELECT * FROM tb_sale WHERE sale_id = '$_POST[id]'");
    $row_order_sale = $order_sale->fetch_array();

    $customer = $conn->query("SELECT customer_fname,customer_lname,customer_phone,customer_address FROM tb_customer WHERE customer_id = '$row_order_sale[customer_id]'");
    if ($customer->num_rows >= 1) {
        $row_customer = $customer->fetch_array();
        $customer_fname = $row_customer['customer_fname'] . ' ' . $row_customer['customer_lname'];
        $customer_address = $row_customer['customer_address'];
        $customer_phone = $row_customer['customer_phone'];
    } else {
        $customer_fname = 'ไม่มีข้อมูล';
        $customer_address = 'ไม่มีข้อมูล';
        $customer_phone = 'ไม่มีข้อมูล';
    }

    $tax_rate = 0.07;
    $discount = 0;
    $order_product = $conn->query("SELECT * FROM tb_order WHERE sale_id = '$_POST[id]'");
    $outp = '
    <style>
    table{
        width:100%;
        font-size: 14px;
    }
    th.show, td.show {
        color:black;
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
      }
      span{
        color:black;
        border-bottom: 1px dotted black;
      }

    </style>';
    $outp .= '<table style="margin-bottom:3px;">
    <tr>
    <td style="text-align:center;">วันที่ซื้อ &nbsp;<span>' . date_format(date_create($row_order_sale['by_date']), "d/m/Y H:i") . '</span></td>
    <td style="text-align:center;">รหัสการขาย &nbsp;<span>' . $row_order_sale['sale_code'] . '</span></td>
    </tr>
    </table>';
    $outp .= '<p>ชื่อผู้ซื้อ <span>&nbsp;' . $customer_fname  . '</span></p>';
    $outp .= '<p>ที่อยู่ <span>&nbsp;' . $customer_address . ' </span>&nbsp;&nbsp; เบอร์โทร <span>&nbsp;' . $customer_phone . '</span></p>';
    $outp .= '<div class="box"></div>';
    $outp .= '<table>
    <thead>
        <tr>
            <th class="show" style="text-align:center;">ลำดับ</th>
            <th class="show">รายการ</th>
            <th class="show" style="text-align:center;">จำนวน</th>
            <th class="show" style="text-align:right;">ราคา/หน่วย</th>
            <th class="show" style="text-align:right;">จำนวนเงิน/บาท</th>
        </tr>
    </thead>
    <tbody>';
    $i = 1;
    foreach ($order_product as $row) {
        $product = $conn->query("SELECT * FROM tb_product WHERE product_id = $row[product_id]");
        $row_product = $product->fetch_array();
        $outp .= '<tr>
        <td class="show" style="text-align:center;">' . $i++ . '</td>
        <td class="show" style="text-align:left;">' . $row_product['product_name'] . '</td>
        <td class="show" style="text-align:center;"> ' . $row['product_qty'] . '</td>
        <td class="show" style="text-align:right;">' . number_format($row['product_price'], 2) . '</td>
        <td class="show" style="text-align:right;">' . number_format($row['product_total_price'], 2) . '</td>
    </tr>';
    }
    foreach ($order_product as $row) :
        $product = $conn->query("SELECT * FROM tb_product WHERE product_id = $row[product_id]");
        $row_product = $product->fetch_array();
        if ($row['total_discount'] >= 1) {
            $discount += $row_product['product_discount'] * $row['product_qty'];
            $outp .= '<tr>
                <td class="show" colspan="4" style="text-align:right;">ส่วนลดจาก ' . $row_product['product_name'] . '</td>
                <td class="show" colspan="1" style="text-align:right;">' . number_format($row['total_discount'] * $row['product_qty'], 2) . '</td>
            </tr>';
        }
    endforeach;
    $outp .= '<tr>
        <td class="show" colspan="4" style="text-align:right;" >มูลค่ารวมก่อนเสียถาษี</td>
        <td class="show" style="text-align:right;">' . number_format($row_order_sale['product_total_price'] - $discount, 2) . '</td>
    </tr>';
    $outp .= '<tr>
        <td class="show" colspan="4" style="text-align:right;">ภาษีมูลค่าเพิ่ม (VAT ' . ($tax_rate * 100) . '%)</td>
        <td class="show" style="text-align:right;" >' . number_format($row_order_sale['product_tatal_vat'], 2) . '</td>
    </tr>';
    $outp .= '<tr>
        <td class="show" colspan="4" rowspan="2" style="text-align:right;">ยอดรวม</td>
        <td class="show" style="text-align:right;">' . number_format($row_order_sale['price_sum_vat'], 2) . '</td>
    </tr>';
    $outp .= '</tbody></table>';
    echo $outp;
}

if (isset($_POST['addGroupCustomer'])) {
    $res = 0;
    if (!empty($_POST['id'])) {
        $res = 1;
        $query = $conn->query("UPDATE tb_group_customer SET group_name = '$_POST[groupCustomer]' WHERE group_id = '$_POST[id]'");
        $check_row = $conn->query("SELECT * FROM tb_group_customer");
        $count = $check_row->num_rows;
        $data = array(
            'row' => $count,
            'row_id' => $_POST['id'],
            'status' => $res
        );
    } else {
        $res = 0;
        $query = $conn->query("INSERT INTO tb_group_customer(`group_name`) VALUES('$_POST[groupCustomer]')");
        $row_id = $conn->insert_id;
        $check_row = $conn->query("SELECT * FROM tb_group_customer");
        $count = $check_row->num_rows;
        $data = array(
            'row' => $count,
            'row_id' => $row_id,
            'status' => $res
        );
    }
    echo json_encode($data);
}

if (isset($_POST['DeleteGroupCustomer'])) {
    $check_group = $conn->query("SELECT * FROM tb_customer WHERE group_id = '$_POST[id]'");
    if($check_group->num_rows >= 1){
        echo 0;
    }else{
        echo 1;
        $query = $conn->query("DELETE FROM tb_group_customer WHERE group_id = '$_POST[id]'");
    }
}

if (isset($_POST['deleteStory'])) {
    $query = $conn->query("DELETE FROM tb_sale WHERE sale_id = '$_POST[id]'");
}

if (isset($_POST['editGroupCustomer'])) {
    $query = $conn->query("SELECT * FROM tb_group_customer WHERE group_id = '$_POST[id]'");
    $row = $query->fetch_array();
    echo json_encode($row);
}

if (isset($_POST['editCategory'])) {
    $query = $conn->query("SELECT * FROM tb_category WHERE category_id = '$_POST[id]'");
    $row = $query->fetch_array();
    echo json_encode($row);
}

if (isset($_POST['editDiscount'])) {
    $query = $conn->query("SELECT product_id,product_code,product_name,product_price,product_discount FROM tb_product WHERE product_id = '$_POST[id]'");
    $row = $query->fetch_array();
    echo json_encode($row);
}

if (isset($_POST['updateDiscount'])) {
    $query = $conn->query("UPDATE tb_product SET product_discount = '$_POST[discount_value]' WHERE product_id = '$_POST[id]'");
    $query = $conn->query("SELECT product_id,product_name,product_price,product_discount FROM tb_product WHERE product_id = '$_POST[id]'");
    $row = $query->fetch_array();
    $data = array(
        'row_id' => $_POST['id'],
        'product_name' => $row['product_name'],
        'product_price' => $row['product_price'],
        'product_discount' => $row['product_discount'],
    );
    echo json_encode($data);
}

if (isset($_POST['login'])) {
    $pass = md5($_POST['password']);
    $check_user = $conn->query("SELECT * FROM tb_employee WHERE username = '$_POST[username]'");
    if ($check_user->num_rows >= 1) {
        $check_password = $conn->query("SELECT * FROM tb_employee WHERE username = '$_POST[username]' AND password = '$pass'");
        if ($check_password->num_rows >= 1) {
            $user = $check_password->fetch_array();
            if ($user['type'] == 999) {
                $_SESSION['login'] = true;
                $_SESSION['type'] = 999;
                $_SESSION['user_type'] = 'admin';
                $_SESSION['employee_id'] = $user['employee_id'];
            } else {
                $_SESSION['login'] = true;
                $_SESSION['type'] = 1;
                $_SESSION['user_type'] = 'employee';
                $_SESSION['employee_id'] = $user['employee_id'];
            }
            echo 3;
        } else {
            echo 1;
        }
    } else {
        echo 0;
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
}

if (isset($_POST['DeletePosition'])) {
    $delete = $conn->query("DELETE FROM tb_position WHERE position_id = '$_POST[id]'");
}

if (isset($_POST['EditPosition'])) {
    $query = $conn->query("SELECT * FROM tb_position WHERE position_id = '$_POST[id]'");
    $row = $query->fetch_array();
    echo json_encode($row);
}

if (isset($_POST['addPosition'])) {
    if (!empty($_POST['id'])) {
        $sql = "UPDATE tb_position SET position_name = '$_POST[position]' WHERE position_id = '$_POST[id]'";
        $conn->query($sql);
        $check_row = $conn->query("SELECT * FROM tb_position");
        $count = $check_row->num_rows;
        $data = array(
            'row' => $count,
            'row_id' => $_POST['id'],
            'status' => 1
        );
    } else {
        $sql = "INSERT INTO tb_position(position_name) VALUES('$_POST[position]')";
        $conn->query($sql);
        $row_id = $conn->insert_id;
        $check_row = $conn->query("SELECT * FROM tb_position");
        $count = $check_row->num_rows;
        $data = array(
            'row' => $count,
            'row_id' => $row_id,
            'status' => 0
        );
    }

    echo json_encode($data);
}

if (isset($_POST['addEmployee'])) {
    $pass = md5($_POST['password']);
    $date = date('d/m/Y H:i:s');
    if (!empty($_POST['id'])) {
        if (!empty($_FILES['file']['name'])) {
            $file_img = rand(10000000, 99999999) . '-' . $_FILES['file']['name'];
            $location = '../assets/upload_img_customer/';
            $nameFile =  array('png', 'jpg', 'pdf', 'webp', 'jpeg');
            $nameFileImg = pathinfo($file_img, PATHINFO_EXTENSION);
            if (!in_array($nameFileImg, $nameFile)) {
                echo "ภาพไม่ถูก";
            } else {
                move_uploaded_file($_FILES['file']["tmp_name"], $location . $file_img);
                $sql = "UPDATE `tb_employee` SET
                `username`='$_POST[username]',
                `password`='$pass',
                `fname`='$_POST[fname]',
                `lname`='$_POST[lname]',
                `address`='$_POST[address]',
                `phone`='$_POST[phone]',
                `line`='$_POST[line]',
                `user_img`='$file_img',
                `position_id`='$_POST[position]'
                 WHERE employee_id = '$_POST[id]'";
                $conn->query($sql);

                $check_row = $conn->query("SELECT * FROM tb_employee WHERE position_id = '$_POST[position]'");
                $count = $check_row->num_rows;
                $postion = $conn->query("SELECT * FROM tb_position WHERE position_id = '$_POST[position]'");
                $row_position = $postion->fetch_array();
                $data = array(
                    'row' => $count,
                    'row_id' => $_POST['id'],
                    'position' => $row_position['position_name'],
                    'created_at' => $_POST['created'],
                    'img' => '<img src="assets/upload_img_customer/' . $file_img . '" alt="">',
                    'status' => 1
                );
            }
        } else {
            $sql = "UPDATE `tb_employee` SET
                `username`='$_POST[username]',
                `password`='$pass',
                `fname`='$_POST[fname]',
                `lname`='$_POST[lname]',
                `address`='$_POST[address]',
                `phone`='$_POST[phone]',
                `line`='$_POST[line]',
                `position_id`='$_POST[position]'
                 WHERE employee_id = '$_POST[id]'";
            $conn->query($sql);

            $check_row = $conn->query("SELECT * FROM tb_employee WHERE position_id = '$_POST[position]'");
            $count = $check_row->num_rows;
            $postion = $conn->query("SELECT * FROM tb_position WHERE position_id = '$_POST[position]'");
            $row_position = $postion->fetch_array();
            $data = array(
                'row' => $count,
                'row_id' => $_POST['id'],
                'position' => $row_position['position_name'],
                'created_at' => $_POST['created'],
                'img' => '<img src="assets/upload_img_customer/' . $_POST['old_img'] . '" alt="">',
                'status' => 1
            );
        }
    } else {
        if (!empty($_FILES['file']['name'])) {
            $file_img = rand(10000000, 99999999) . '-' . $_FILES['file']['name'];
            $location = '../assets/upload_img_customer/';
            $nameFile =  array('png', 'jpg', 'pdf', 'webp', 'jpeg');
            $nameFileImg = pathinfo($file_img, PATHINFO_EXTENSION);
            if (!in_array($nameFileImg, $nameFile)) {
                echo "ภาพไม่ถูก";
            } else {
                move_uploaded_file($_FILES['file']["tmp_name"], $location . $file_img);
                $sql = "INSERT INTO `tb_employee`(`username`, `password`, `fname`, `lname`, `address`, `phone`, `line`, `user_img`, `position_id`, `type`, `created_at`) 
                VALUES ('$_POST[username]','$pass','$_POST[fname]','$_POST[lname]','$_POST[address]','$_POST[phone]','$_POST[line]','$file_img','$_POST[position]',1,'$date')";
                $new_employee = $conn->query($sql);
                $row_id = $conn->insert_id;
                $check_row = $conn->query("SELECT * FROM tb_employee WHERE position_id = '$_POST[position]'");
                $count = $check_row->num_rows;
                $postion = $conn->query("SELECT * FROM tb_position WHERE position_id = '$_POST[position]'");
                $row_position = $postion->fetch_array();
                $data = array(
                    'row' => $count,
                    'row_id' => $row_id,
                    'position' => $row_position['position_name'],
                    'created_at' => $date,
                    'img' => '<img src="assets/upload_img_customer/' . $file_img . '" alt="">',
                    'status' => 0
                );
            }
        } else {
            $sql = "INSERT INTO `tb_employee`(`username`, `password`, `fname`, `lname`, `address`, `phone`, `line`, `position_id`, `type`, `created_at`) 
            VALUES ('$_POST[username]','$pass','$_POST[fname]','$_POST[lname]','$_POST[address]','$_POST[phone]','$_POST[line]','$_POST[position]',1,'$date')";
            $new_employee = $conn->query($sql);
            $row_id = $conn->insert_id;
            $check_row = $conn->query("SELECT * FROM tb_employee WHERE position_id = '$_POST[position]'");
            $count = $check_row->num_rows;
            $postion = $conn->query("SELECT * FROM tb_position WHERE position_id = '$_POST[position]'");
            $row_position = $postion->fetch_array();
            $data = array(
                'row' => $count,
                'row_id' => $row_id,
                'position' => $row_position['position_name'],
                'created_at' => $date,
                'img' => '<img src="assets/upload_img_customer/manager.png" alt="">',
                'status' => 0
            );
        }
    }
    echo json_encode($data);
}

if (isset($_POST['DeleteEmployee'])) {
    $conn->query("DELETE FROM tb_employee WHERE employee_id = '$_POST[id]'");
}

if (isset($_POST['showEmployee'])) {
    $query = $conn->query("SELECT * FROM tb_employee WHERE employee_id = '$_POST[id]'");
    $row = $query->fetch_array();
    echo json_encode($row);
}

if (isset($_POST['EditEmployee'])) {
    $query = $conn->query("SELECT * FROM tb_employee WHERE employee_id = '$_POST[id]'");
    $row = $query->fetch_array();
    echo json_encode($row);
}

if (isset($_POST['updateProfile'])) {
    if (!empty($_FILES['file']['name'])) {
        $file_img = rand(10000000, 99999999) . '-' . $_FILES['file']['name'];
        $location = '../assets/upload_img_customer/';
        $nameFile =  array('png', 'jpg', 'pdf', 'webp', 'jpeg');
        $nameFileImg = pathinfo($file_img, PATHINFO_EXTENSION);
        if (!in_array($nameFileImg, $nameFile)) {
            echo "ภาพไม่ถูก";
        } else {
            move_uploaded_file($_FILES['file']["tmp_name"], $location . $file_img);
            $sql = "UPDATE `tb_employee` SET
                `username`='$_POST[username]',
                `fname`='$_POST[fname]',
                `lname`='$_POST[lname]',
                `address`='$_POST[address]',
                `phone`='$_POST[phone]',
                `line`='$_POST[line]',
                `user_img`='$file_img'
                 WHERE employee_id = '$_POST[id]'";
            $conn->query($sql);
        }
    } else {
        $sql = "UPDATE `tb_employee` SET
                `username`='$_POST[username]',
                `fname`='$_POST[fname]',
                `lname`='$_POST[lname]',
                `address`='$_POST[address]',
                `phone`='$_POST[phone]',
                `line`='$_POST[line]'
                WHERE employee_id = '$_POST[id]'";
        if ($conn->query($sql)) {
            echo 'success';
        } else {
            echo $sql;
        }
    }
}

if (isset($_POST['checkPass'])) {
    $pass = (string)md5($_POST['password']);
    $sql = "SELECT * FROM tb_employee WHERE employee_id = '$_SESSION[employee_id]'";
    $query = $conn->query($sql);
    $row = $query->fetch_array();
    if ($pass == $row['password']) {
        echo 1;
    } else {
        echo $pass;
    }
}

if (isset($_POST['updatePass'])) {
    $pass = md5($_POST['new_pass']);
    $query = $conn->query("UPDATE tb_employee SET password = '$pass' WHERE employee_id = '$_SESSION[employee_id]'");
    if ($query) {
        echo 'success';
    }
}

if (isset($_POST['updateShop'])) {
    if (!empty($_FILES['file']['name'])) {
        $file_img = rand(10000000, 99999999) . '-' . $_FILES['file']['name'];
        $location = '../assets/upload_logo/';
        $nameFile =  array('png', 'jpg', 'pdf', 'webp', 'jpeg');
        $nameFileImg = pathinfo($file_img, PATHINFO_EXTENSION);
        if (!in_array($nameFileImg, $nameFile)) {
            echo "ภาพไม่ถูก";
        } else {
            move_uploaded_file($_FILES['file']["tmp_name"], $location . $file_img);
            $query = $conn->query("UPDATE tb_shop SET 
            shop_name = '$_POST[shop_name]',
            shop_address = '$_POST[shop_address]',
            shop_phone = '$_POST[shop_phone]',
            shop_img = '$file_img',
            line_notify = '$_POST[shop_notify]'
            ");
        }
    } else {
        $query = $conn->query("UPDATE tb_shop SET 
        shop_name = '$_POST[shop_name]',
        shop_address = '$_POST[shop_address]',
        shop_phone = '$_POST[shop_phone]',
        line_notify = '$_POST[shop_notify]'
        ");
    }
}

if(isset($_POST['findSubCategory'])){
    $query = $conn->query("SELECT * FROM tb_sub_category WHERE category_id = '$_POST[category]'");
    $outp = '';
    if($query->num_rows >=1){
        foreach($query as $row){
            $outp .= '<option value="'.$row['sub_id'].'">'.$row['sub_name'].'</option>';
        }
    }else{
        $outp .= '<option value="">ไม่พบประเภท</option>';
    }

    echo $outp;
}