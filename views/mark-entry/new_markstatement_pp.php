<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\CoeBatDegReg;
use app\models\StudentMapping;
use app\models\MandatorySubcatSubjects;
use app\models\Degree;
$this->registerCssFile("@web/css/newmarkstatement_pp.css");
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?> 
<?php
if (isset($mark_statement) && !empty($mark_statement))    
{
    $header=$body=$footer=$footer='';
} 
else 
{
    Yii::$app->ShowFlashMessages->setMsg('Error', 'No data Found');
}
?>
