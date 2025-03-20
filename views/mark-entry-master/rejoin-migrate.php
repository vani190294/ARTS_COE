<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\ExamTimetable;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use kartik\date\DatePicker;

$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(),'register_number','register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number); 
$student_map_id= '';


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Rejoin Migrate";

?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
           <div class="col-lg-3 col-sm-3">   
                <?php echo $form->field($model, 'register_number')->widget(
                    Select2::classname(), [
                    'data' => ConfigUtilities::RejoinStudents(),                                
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' ----',
                        'onChange'=>'getPreRegNum(this.value);',
                        'id' => 'reg_num', 
                        'name'=>'reg_num',
                    ],
					'pluginOptions' => [
                        'autoclose'=>true,
			        ],
                    ])->label('Register Number'); 
                ?>
            </div>
            <div class="col-xs-12 col-sm-3 col-lg-3">                           
                <?= $form->field($model, 'register_number_from')->textInput(['id'=>'prev_num','name'=>'prev_num','readonly'=>'readonly'])->label('Previous Register Number') ?>
            </div>            
                 <div class="col-lg-4 col-sm-4"> <br />
                <?= Html::Button('Show '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), ['value'=>'Submit','name'=>"show_subjects" ,'onclick'=>"showSubjectsOfDetain($('#reg_num').val(),$('#prev_num').val());",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                <?= Html::a("Reset", Url::toRoute(['student/rejoin-migrate']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-lg-12 elective_waiver_sub">

            <div class="col-xs-12 col-sm-12 col-lg-12">                
                <div id = "elective_waiver_sub_in"></div>
            </div>
            
            <div id="electgive_sub_wai" class="form-group col-lg-12 col-sm-12 ">

                <?= Html::submitButton('Migrate' , ['class' => 'btn btn-group btn-group-sm btn-primary  pull-right ']) ?>
            </div>
        </div>  

    <?php ActiveForm::end(); ?> 
</div>
</div>
</div>
<?php 
if(isset($_SESSION['importResults']) && !empty($_SESSION['importResults'])) 
{
    ?>
    <div class="box box-success">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-list-ul"></i> <?php echo 'REJOIN MIGRATE RESULTS'; ?></h3>

        <div class="pull-right box-tools">
            <button type="button" class="btn btn-success btn-sm" data-widget="remove" data-toggle="tooltip"
                    title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
    </div>
    <div class="box-body">
        <div class="row">
        <section class="content">
            <section class="col-lg-12 connectedSortable">
    <?php
    $importResults=$_SESSION['importResults']; 
    $totalError = (count($importResults['dispResults'])-$importResults['totalSuccess']); 
    $headerTr = $content = ''; $i = 1;
    if(!empty($importResults['totalSuccess']))
    {
        ?>
        <div class="alert alert-success">
            <h4><i class="fa fa-check"></i> Success! </h4>
            <?= "{$importResults['totalSuccess']} ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Migrated successfully." ?>
        </div>
        <?php
    }
    else if(!empty($totalError))
    {
        ?>
        <div class="alert alert-danger">
            <h4><i class="fa fa-ban"></i> Error! ?></h4>
            <?= "{$totalError} ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." importing error." ?>
        </div>
        <?php
    }
    $headerTr.= Html::tag('th', 'Sr No');
    $headerTr.= Html::tag('th', ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code");
    $headerTr.= Html::tag('th', "Year");
    $headerTr.= Html::tag('th', 'Status');
    $headerTr.= Html::tag('th', 'Message');
    ?>
    <table style="overflow-x:auto;"  class="table table-bordered table-responsive bulk_edit_table table-hover" id="hall_import_results" >
        <thead>
            <?php echo Html::tag('tr', $headerTr, ['class' => 'active']) ?>
        </thead>
        <tbody>
        
        <?php 

        foreach($importResults['dispResults'] as $line) 
        {                       
            $content = '';
            $content.= Html::tag('td', $i++);
            $content.= Html::tag('td', isset($line['sub_code'])?$line['sub_code']:"");
            $content.= Html::tag('td', isset($line['year'])?$line['year']:""); 
            $content.= Html::tag('td', ($line['type'] == 'E') ? 'ERROR' : 'SUCCESS'); //Status
            $content.= Html::tag('td', $line['message']);  //Message
                                
            echo Html::tag('tr', $content, ['class' => ($line['type'] == 'E') ? 'danger' : 'success']); 
            ?>  
        <?php } ?> 
        </tbody>
    </table>
    </section>
    </section>

        </div>
    </div><!--./box-body-->
</div><!--./box-->
    <?php

}
?>
