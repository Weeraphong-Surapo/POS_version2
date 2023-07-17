<?php

require_once 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

if (isset($_POST['code'])) {
  $generator = new BarcodeGeneratorPNG();
  $barcode = $generator->getBarcode($_POST['code'], $generator::TYPE_CODE_128);
  $base64 = base64_encode($barcode);
  echo $base64;
}

?>
