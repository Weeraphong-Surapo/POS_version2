<?php
include('../config/connect.php');

// เรียกใช้ MPDF library
require_once __DIR__ . '/vendor/autoload.php';

$id = $_GET['id'];

date_default_timezone_set("Asia/Bangkok");
$date = date('d/m/Y');
$time = date('H:i');

$query = $conn->query("SELECT * FROM tb_shop");
$row_shop = $query->fetch_array();

$discount = 0;
$order_sale = $conn->query("SELECT * FROM tb_sale WHERE sale_id = $id");
$row_order_sale = $order_sale->fetch_array();

$order_product = $conn->query("SELECT * FROM tb_order WHERE sale_id = $id");

$customer = $conn->query("SELECT * FROM tb_customer WHERE customer_id = '$row_order_sale[customer_id]'");
if($customer->num_rows >= 1){
    $row_custoemr = $customer->fetch_array();
    $name_customer = $row_custoemr['customer_fname']. ' ' . $row_custoemr['customer_lname'];
    $address_customer = $row_custoemr['customer_address'];
    $phone_customer = $row_custoemr['customer_phone'];
}else{
    $name_customer =  'ไม่มีข้อมูล';
    $address_customer =  'ไม่มีข้อมูล';
    $phone_customer = 'ไม่มีข้อมูล';
}

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf(
    [
        'fontDir' => array_merge($fontDirs, [
            __DIR__ . '/tmp',
        ]),
        'fontdata' => $fontData + [ // lowercase letters only in font key
            'thsarabun' => [
                'R' => 'THSarabunNew.ttf',
                'I' => 'THSarabunNew Italic.ttf',
                'B' => 'THSarabunNew Bold.ttf',
                'BI' => 'THSarabunNew BoldItalic.ttf'
            ]
        ],
        'default_font' => 'thsarabun'
    ]
);
// $mpdf->SetDefaultFontSize(13);

$items = array(
    array("Item 1", 10.00, 2, 20.00),
    array("Item 2", 20.00, 1, 20.00),
);
$sub_total = 40.00;
$tax_rate = 0.07;
$tax_amount = 2.80;
$total = 42.80;

// Start the HTML content for the bill
$html = '<style>
    table{
        width:100%;
        font-size: 14px;
    }
    th, td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
      }
      table#header tr td{
        border: 0;
      }
      p{
        margin:1px 0px;
      }
      span{
        border-bottom: 1px dotted black;
      }
      .box{
        margin:8px 0px;
      }
</style>';
$html .= '<html><body>';
$html .= '<table id="header">
      <tr>
        <td id="ht"><img src="../assets/upload_logo/'.$row_shop['shop_img'].'" alt="7-Eleven" width="60"></td>
        <td id="ht" style="text-align:right;"><h1 style="text-align:right;">ใบเสร็จรับเงิน/ใบกำกับภาษี</h1></td>
      </tr>
</table>';
$html .= '<p>วันที่ '.$date.' '.$time.'</p>';
$html .= '<div class="box"></div>';
$html .= '<p>ชื่อผู้ขาย <span>&nbsp;'.$row_shop['shop_name'].'</span></p>';
$html .= '<p>ที่อยู่ <span>&nbsp;'.$row_shop['shop_address'].'</span>&nbsp;&nbsp; เบอร์โทร <span>&nbsp;'.$row_shop['shop_phone'].'</span></p>';
$html .= '<div class="box"></div>';
$html .= '<p>ชื่อผู้ซื้ิอ <span>&nbsp;'.$name_customer.'</span></p>';
$html .= '<p>ที่อยู่ <span>&nbsp;'.$address_customer.' </span>&nbsp;&nbsp; เบอร์โทร <span>&nbsp;'.$phone_customer.'</span></p>';
$html .= '<div class="box"></div>';
$html .= '<table >
<thead>
    <tr>
        <th style="text-align:center;">ลำดับ</th>
        <th>รายการ</th>
        <th style="text-align:center;">จำนวน</th>
        <th style="text-align:right;">ราคา/หน่วย</th>
        <th style="text-align:right;">จำนวนเงิน/บาท</th>
    </tr>
</thead>
<tbody>';
$i = 1;
foreach($order_product as $row):
    $product = $conn->query("SELECT * FROM tb_product WHERE product_id = $row[product_id]");
    $row_product = $product->fetch_array();
$html .= '
<tr>
    <td style="text-align:center;">'.$i++.'</td>
    <td style="text-align:left;">'.$row_product['product_name'].'</td>
    <td style="text-align:center;"> '.$row['product_qty'].'</td>
    <td style="text-align:right;">'.number_format($row['product_price'],2).'</td>
    <td style="text-align:right;">'.number_format($row['product_price'] * $row['product_qty'],2).'</td>
</tr>';
endforeach;
foreach($order_product as $row):
    $product = $conn->query("SELECT * FROM tb_product WHERE product_id = $row[product_id]");
    $row_product = $product->fetch_array();
    if($row['total_discount'] >= 1){
        $discount += $row['total_discount'] * $row['product_qty'];
        $html .= '<tr>
            <td colspan="4" style="text-align:right;">ส่วนลดจาก '.$row_product['product_name'].'</td>
            <td colspan="1" style="text-align:right;">'.number_format($row['total_discount'] * $row['product_qty'],2).'</td>
        </tr>';
    }
endforeach;

$html .= '<tr>
    <td colspan="4" style="text-align:right;" >มูลค่ารวมก่อนเสียถาษี</td>
    <td style="text-align:right;">'.number_format($row_order_sale['product_total_price']- $discount,2).'</td>
</tr>';
$html .= '<tr>
    <td colspan="4" style="text-align:right;">ภาษีมูลค่าเพิ่ม (VAT '.($tax_rate * 100).'%):</td>
    <td style="text-align:right;" >'.number_format($row_order_sale['product_tatal_vat'],2).'</td>
</tr>';
$html .= '<tr>
    <td colspan="4" rowspan="2" style="text-align:right;">ยอดรวม</td>
    <td style="text-align:right;">'.number_format($row_order_sale['price_sum_vat'],2).'</td>
</tr>';
$html .= '</tbody></table>';
$html .= '</body></html>';


// echo $html;
$mpdf->WriteHTML($html);
$mpdf->Output();
// $mpdf->Output('my_custom_pdf.pdf', 'I');
