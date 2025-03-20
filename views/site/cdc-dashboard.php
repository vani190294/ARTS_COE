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

$this->title = Yii::t('app', 'CDC Dashboard'); 
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
          <h1>Welcome to <?php echo $show_text; ?>!!</h1>
          
        </div>
        </div>      
        
     
    </section>

    <!-- Main content -->
    <section class="content">
      

        <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center; color: red;">
            <h2><b>Please Note:</b></h2>
            <h2>Everytime Login Please <b>Ctrl+F5</b> Refresh page then use </h2>
            <h1>Regulation and Batch based curriculum and Syllabus added Please Enter Carefully  </h1>
            <h2>Further Syllabus (Update or Create) After Curriculum Submit by HOD Login </h2>
            <h2>Further Syllabus Mapping After Syllabus Submit by HOD Login </h2>

            <h2><b>Please Fill Service Request and Approve other department Service Request Before Approve Syllabus</b> </h2>

            <!-- <h2>Please Update EEC Count (Count Total = Self + from Other Dept.) in Setting </h2> -->
            
            <!-- <h2><b>Matrix updated:</b> While Create matrix to Other Dept. PO value automatically came (if already created means) <br>you can fill PSO only,</h2>
            <h2>2021 batch SYLLABUS same as 2022 batch <br>
            <b>SYLLABUS Mapping option Added Please Check</b>
            <br>
            <b>Prerequisties Mapping option Added Please Check</b>
            <h2>If 2022 batch SYLLABUS is differ from 2021 means you can enter SYLLABUS in 2022 batch</h2> -->
        </div>
      

    </section>
    <!-- /.content -->
  </div>
