<?php 
  session_start();
  include('../config/connect.php');
  date_default_timezone_set("Asia/Bangkok");
  $res = json_decode(file_get_contents("php://input"));
  $data = array();

  if($res->action == "getAllProduct"){
    $query = $conn->query("SELECT * FROM tb_product");
    foreach($query as $row){
        $data[] = $row;
    }
    echo json_encode($data);
  }

  if($res->action == "getAllCategory"){
    $query = $conn->query("SELECT * FROM tb_category");
    foreach($query as $row){
        $data[] = $row;
    }
    echo json_encode($data);
  }

  if($res->action == "SaleProduct"){
    $months = date('m');
    $year = date('Y')+543;
    $date = date('d-m-y H:i:s');
    $day = date('d');
    $price_toal_product = 0;
    $price_cost = 0;
    $price_discount = 0;
    $code = '#' . rand(111111,999999);
    $sale_product = $conn->query("INSERT INTO `tb_sale`(`sale_code`, `customer_id`, `employee_id` , `count_cart`, `get_money`, `change_money`, `product_total_price` , `product_tatal_vat` , `price_sum_vat` , `by_date`, `by_month`, `by_year`) 
                                  VALUES ('$code','$res->customer_id','$_SESSION[employee_id]','$res->countcart','$res->getMo','$res->changeMo','$res->totalProduct' ,'$res->vatproduct','$res->totelPriceVat','$date','$months','$year')");
    $print_id = $conn->insert_id;
    foreach($res->Cart as $k => $v){
      $price_toal_product = ($v->product_price * $v->product_qty) - ($v->product_discount * $v->product_qty);
      $price_cost = $v->product_cost * $v->product_qty;
      $price_discount = $v->product_discount * $v->product_qty;
      $conn->query("INSERT INTO `tb_order`(`product_id`, `sale_id`, `product_qty`,`product_price`, `product_total_price`, `product_vat`, `total_discount`, `total_cost`, `total_sum_vat`, `day`) 
                    VALUES ('$v->product_id','$print_id','$v->product_qty','$v->product_price','$price_toal_product','$res->vatproduct','$price_discount','$price_cost','$res->totelPriceVat', '$day')");
    }
    $data = array('print_id'=>$print_id);
    echo json_encode($data);

    $token = $conn->query("SELECT line_notify FROM tb_shop");
    $row_token = $token->fetch_array();
    $sToken = "$row_token[line_notify]";
    $sMessage = "มีการขายสินค้า ".$day."/".$months."/".$year."\n";
    $sMessage .= "รหัสการขาย  : " . $code . "\n";
    foreach($res->Cart as $k => $v){
      $query = $conn->query("SELECT product_name FROM tb_product WHERE product_id = '$v->product_id'");
      $row_product = $query->fetch_array();
      $sMessage .= "สินค้า : " . $row_product['product_name'] ." x".$v->product_qty."\n";
    }
    $sMessage .= "ราคารวมสินค้า  : " . number_format($res->totalProduct) . "\n";
    $sMessage .= "ภาษีมูลค่าเพิ่ม 7 %  : " . number_format($res->vatproduct) . "\n";
    $sMessage .= "ราคาหลังเสียภาษี  : " . number_format($res->totelPriceVat) . "\n";
    $sMessage .= "รับเงิน  : " . number_format($res->getMo) . "\n";
    $sMessage .= "ทอนเงิน : " . number_format($res->changeMo) . "\n";

    $data = array(
        'message' => $sMessage,
    );


    $chOne = curl_init();
    curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
    curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($chOne, CURLOPT_POST, 1);
    curl_setopt($chOne, CURLOPT_POSTFIELDS, $data);
    $headers = array('Content-type: multipart/form-data', 'Authorization: Bearer ' . $sToken . '',);
    curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($chOne);
  }

  if($res->action == "getAllCustomer"){
    $query = $conn->query("SELECT * FROM tb_customer");
    foreach($query as $row){
      $data[] = $row;
    }
    echo json_encode($data);
  }

  if($res->action == "savesize"){
    $cut_width = explode('m',$res->width);
    $cut_height = explode('m',$res->height);
    $height = $cut_height[0];
    $width = $cut_width[0];
    $conn->query("UPDATE tb_size_page SET width = '$width' , height = '$height'");
  }
?>