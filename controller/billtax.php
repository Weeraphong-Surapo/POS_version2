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

$size_page = $conn->query("SELECT * FROM tb_size_page");
$row_size_page = $size_page->fetch_array();
$width = $row_size_page['width'];
$height = $row_size_page['height'];

$discount = 0;
$order_sale = $conn->query("SELECT * FROM tb_sale WHERE sale_id = $id");
$row_order_sale = $order_sale->fetch_array();

$order_product = $conn->query("SELECT * FROM tb_order WHERE sale_id = $id");

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf(
    [
        'format' => [$width, $height],
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
$mpdf->SetDefaultFontSize(13);


// ตั้งค่ากำกับภาษี
// $mpdf->SetWatermarkImage('https://upload.wikimedia.org/wikipedia/commons/thumb/4/40/7-eleven_logo.svg/800px-7-eleven_logo.svg.png', 1, 30);
$mpdf->showWatermarkImage = true;

// สร้าง HTML ของใบเสร็จ
$html = '
<style>
hr {
    
}
</style>
<div>
    <table>
        <tbody>
            <tr>
                <td colspan="2" style="text-align:center;"><img src="../assets/upload_logo/'.$row_shop['shop_img'].'" alt="7-Eleven" width="60"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;"><h1>ใบเสร็จรับเงิน/รายการสินค้า</h1></td>
            </tr>
            <tr>
                <td colspan="2"><hr style="border: 1px dashed black;"></td>
            </tr>
            <tr>
                <th>Qty</th>
                <th style="text-align:start;">สินค้า</th>
                <th>ราคา/หน่วย</th>
                <th>ราคารวม</th>
            </tr>
            ';
            foreach($order_product as $row):
                $product = $conn->query("SELECT * FROM tb_product WHERE product_id = $row[product_id]");
                $row_product = $product->fetch_array();
            $html .= '
            <tr>
                <td style="text-align:center;">x '.$row['product_qty'].'</td>
                <td>'.$row_product['product_name'].'</td>
                <td>@'.number_format($row['product_price'],2).'</td>
                <td>'.number_format($row['product_price'] * $row['product_qty'],2).'</td>
            </tr>';
            endforeach;
            foreach($order_product as $row):
                $product = $conn->query("SELECT * FROM tb_product WHERE product_id = $row[product_id]");
                $row_product = $product->fetch_array();
                if($row['total_discount'] >= 1){
                    $discount += $row['total_discount'] * $row['product_qty'];
                    $html .= '<tr>
                        <td colspan="3">ส่วนลดจาก '.$row_product['product_name'].'</td>
                        <td colspan="2">'.number_format($row['total_discount']* $row['product_qty'],2).'</td>
                    </tr>';
                }
            endforeach;

            $html .= '<tr>        
            <td colspan="4"><hr style="border: 1px dashed black;"></td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td style="text-align:right;">รวมเป็นเงิน:</td>
                <td colspan="2" style="text-align:right;">'.number_format($row_order_sale['product_total_price']- $discount,2)  .' บาท</td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td style="text-align:right;">ภาษีมูลค่าเพิ่ม 7%:</td>
                <td colspan="2" style="text-align:right;">'.number_format($row_order_sale['product_tatal_vat'],2).' บาท</td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td style="text-align:right;">รวมทั้งสิ้น:</td>
                <td colspan="2" style="text-align:right;">'.number_format($row_order_sale['price_sum_vat'] ,2).' บาท</td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td style="text-align:right;">วันที่:</td>
                <td colspan="2" style="text-align:right;">'.$date.' '.$time.'</td>
            </tr>
        </tbody>
    </table>';
$mpdf->WriteHTML($html);
$mpdf->Output('my_custom_pdf.pdf', 'I');
