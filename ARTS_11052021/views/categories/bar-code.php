<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use Picqer\Barcode\BarcodeGeneratorHTML;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

echo Dialog::widget();

$generator = new BarcodeGeneratorHTML();

echo "<h1 style='font-family: MRV Code39extMA; font-size: 40px;' >".$generator->getBarcode('1254789', $generator::TYPE_CODE_128)."<h1>";
exit;
?>
