<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT).' Generation';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 

<?php
require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
if($org_email=='coe@skasc.ac.in')
{
  include('arts_reports.php');
}
else
{
  include('engineering-reports.php');
}

?>
  

