<?php
include('../config/connect.php');
// require_once __DIR__ . '/vendor/autoload.php';

// $generator = new Picqer\Barcode\BarcodeGeneratorPNG();

// $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
// $fontDirs = $defaultConfig['fontDir'];

// $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
// $fontData = $defaultFontConfig['fontdata'];

// $mpdf = new \Mpdf\Mpdf([
//     'fontDir' => array_merge($fontDirs, [
//         __DIR__ . '/tmp',
//     ]),
//     'fontdata' => $fontData + [ // lowercase letters only in font key
//         'sarabun' => [
//             'R' => 'THSarabunNew.ttf',
//             'I' => 'THSarabunNew Italic.ttf',
//             'B' => 'THSarabunNew Bold.ttf',
//             'BI' => 'THSarabunNew BoldItalic.ttf'
//         ]
//     ],
//     'default_font' => 'sarabun'
// ]);

$product_id = $_GET['id'];
$rowqty = $_GET['row'];
$date = date('d/m/y H:i');

$sql = "SELECT * FROM tb_product WHERE product_id = '$product_id'";
$query = $conn->query($sql);
$row = $query->fetch_array();
$outp = '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Barcode Product</title>
    <style>
        table{
            width:100%;
        }
        .box{
            padding:2px;
            border:1px dashed black;
        }
        img{
            margin:5px 0;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
</head>
<body onload="window.print()">
    <table>
        <tr>
            <td><?php echo $date ?></td>
            <td><?php echo $row['product_name'] ?></td>
        </tr>
    </table>
    <table>
        <?php for ($i = 0; $i < $rowqty; $i++) { ?>
            <tr>
                <td class="box">
                    <center>
                        <div>
                            <?php echo $row['product_name'] ?><br>
                            <svg class="barcode"></svg>
                            <span style="display:block; margin-top:-5px;"><?php echo $row['product_code'] ?></span>
                            <p style="margin-top:-2px;"><?php echo number_format($row['product_price']) ?> บาท</p>
                            
                        </div>
                    </center>
                </td>
                <td class="box">
                    <center>
                        <div>
                            <?php echo $row['product_name'] ?><br>
                            <svg class="barcode"></svg>
                            <span style="display:block; margin-top:-5px;"><?php echo $row['product_code'] ?></span>
                            <p style="margin-top:-2px;"><?php echo number_format($row['product_price']) ?> บาท</p>
                            
                        </div>
                    </center>
                </td>
                <td class="box">
                    <center>
                        <div>
                            <?php echo $row['product_name'] ?><br>
                            <svg class="barcode"></svg>
                            <span style="display:block; margin-top:-5px;"><?php echo $row['product_code'] ?></span>
                            <p style="margin-top:-2px;"><?php echo number_format($row['product_price']) ?> บาท</p>
                            
                        </div>
                    </center>
                </td>
            </tr>
            <script>
                // Generate barcode for each product using the product code
                JsBarcode(document.querySelectorAll('.barcode')[<?php echo $i*3 ?>], '<?php echo $row['product_code'] ?>', {
                    format: 'CODE128',
                    displayValue: false
                });
                JsBarcode(document.querySelectorAll('.barcode')[<?php echo $i*3 + 1 ?>], '<?php echo $row['product_code'] ?>', {
                    format: 'CODE128',
                    displayValue: false
                });
                JsBarcode(document.querySelectorAll('.barcode')[<?php echo $i*3 + 2 ?>], '<?php echo $row['product_code'] ?>', {
                    format: 'CODE128',
                    displayValue: false
                });
            </script>
        <?php } ?>
    </table>
</body>
</html>

<script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
