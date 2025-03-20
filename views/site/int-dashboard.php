<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\User;
use app\models\Degree;
use app\models\Subjects;
use app\models\Batch;
use app\models\Programme;
use app\models\Student;
use yii\bootstrap\ActiveForm;
use kartik\dialog\Dialog;
use scotthuangzl\googlechart\GoogleChart;

echo Dialog::widget();

$this->title = Yii::t('app', 'Internal Module Dashboard'); 
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Main content -->

<?php 
    Yii::$app->ShowFlashMessages->showFlashes();
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $show_text = isset($org_name)?$org_name:"Sri Krishna Institutions";
?>  


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="intro_text">
            <div class="type-js headline">
          <h1>Welcome to SKCT Internal Module!!</h1>
          
        </div>
        </div>      
        
     
    </section>

    <!-- Main content -->
    <section class="content">
      

        <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center; color: red;">
            <h2><b>Please Note:</b></h2>
            <h2>Everytime Login Please <b>Ctrl+F5</b> Refresh page then use </h2>
            <h2>Everytime Exam time table Import Please Download Sample Format Verify format then Import </h2>
        </div>
      

    </section>
    <!-- /.content -->
  </div>
