<?php
include('../../config/connect.php');
require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');


if(isset($_POST['Submit'])){

  $mimes = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  if(in_array($_FILES["file"]["type"],$mimes)){


    $uploadFilePath = 'uploads/'.basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);


    $Reader = new SpreadsheetReader($uploadFilePath);

    $totalSheet = count($Reader->sheets());


    for($i=0;$i<$totalSheet;$i++){

      $Reader->ChangeSheet($i);

      foreach ($Reader as $Row)
      {

        $product_code = isset($Row[0]) ? $Row[0] : '';
        $product_name = isset($Row[1]) ? $Row[1] : '';
        $product_price = isset($Row[2]) ? $Row[2] : '';
        $product_wholesale = isset($Row[3]) ? $Row[3] : '';
        $product_retail = isset($Row[4]) ? $Row[4] : '';
        $product_cost = isset($Row[5]) ? $Row[5] : '';
        $product_qty = isset($Row[6]) ? $Row[6] : '';

        $query = "INSERT INTO `tb_product`(`product_code`, `product_name`,`product_price`,`product_wholesale`, `product_retail`, `product_qty`,`product_cost`) 
        VALUES ('$product_code','$product_name','$product_price','$product_wholesale','$product_retail','$product_qty','$product_cost')";
        
        $conn->query($query);
       }

    }

  }else { 

    die("<br/>Sorry, File type is not allowed. Only Excel file."); 

  }

}
?>