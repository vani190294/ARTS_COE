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
use app\models\HallAllocate;
error_reporting(0);
$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(),'register_number','register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number); 

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="IMPORT ESE MARKS";
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>
<input type="hidden" value="All" name="section" id='stu_section_select' class='form-control student_disable' />
<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            
            <!--div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div-->

 
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year','name'=>'year',]) ?>
            </div>
           
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'data' => HallAllocate::getMonth(),   
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id'=>'exam_month',
                            'name'=>'month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                    ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'term')->widget(
                    Select2::classname(), [
                        'data' => ExamTimetable::getExamTerm(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Term ----',
                            'id' => 'exam_term',
                            'class'=>'student_disable',
                            'name'=>'term',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'mark_type')->widget(
                    Select2::classname(), [
                        'data' => ExamTimetable::getExamType(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Type ----',
                            'id' => 'exam_type',
                            'class'=>'student_disable',
                            'name'=>'mark_type',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
        
        <div class="col-lg-4 col-sm-4">
        <br />
            <div class="form-group">
                <div class="input-group input-file" name="uploaded_file">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-choose" type="button">Choose</button>
                    </span>
                    <input type="text" class="form-control" placeholder='Choose a file...' />
                    <span class="input-group-btn">
                         <button class="btn btn-warning btn-reset" type="button">Reset</button>
                    </span>
                </div>
            </div>
            <!-- COMPONENT END -->
            
        </div>
        <div class="col-lg-4 col-sm-4">
        <br />
            <?= Html::submitButton('Import Excel', ['onclick'=>'bs_input_file();','class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['reports/import-ese-marks']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            <!-- COMPONENT END -->
            
        </div>
        </div>
        
    <?php ActiveForm::end(); ?>
</div>
<?php 
$importResults=$_SESSION['importResults1'];
if(isset($importResults))
{
    ?>
        
        
        <div class="row">
        <section class="content">
            <section class="col-lg-12 connectedSortable">

              <?php if(!empty($importResults['dispResults']))
              {

                $totalError = (count($importResults['dispResults'])-$importResults['totalSuccess']); ?>
                <?php $headerTr = $content = ''; $i = 1; ?>
                
                <?php if(!empty($importResults['totalSuccess'])) : ?>
                    <div class="alert alert-success" style="padding-top: 10px;">
                        <h4><i class="fa fa-check"></i> <?php 'Success!'; ?></h4>
                        <?= "{$importResults['totalSuccess']} Marks imported successfully." ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($totalError)) : ?>
                    <div class="alert alert-danger">
                        <h4><i class="fa fa-ban"></i> <?php echo 'Error!'; ?></h4>
                        <?= "{$totalError}  Marks importing error." ?>
                    </div>
                <?php endif; ?>
              <!-- tools box -->
              
              <!-- /. tools -->
           <?php }?>
              <?php if(!empty($importResults['dispResults']))
              {
                    $headerTr.= Html::tag('th', 'Sr No');
                    $headerTr.= Html::tag('th', 'Subject Code');
                    $headerTr.= Html::tag('th', 'Register Number');
                    $headerTr.= Html::tag('th', 'Total Mark');
                    $headerTr.= Html::tag('th', 'Grade Point');
                    $headerTr.= Html::tag('th', 'Grade Name');
                    $headerTr.= Html::tag('th', 'Status');
                    $headerTr.= Html::tag('th', 'Message');
                ?>
                <table style="overflow-x:auto;"  class="table table-bordered table-responsive bulk_edit_table table-hover" id="hall_import_results" >
                    <thead>
                        <?php echo Html::tag('tr', $headerTr, ['class' => 'active']) ?>
                    </thead>
                    <tbody>
                    
                    <?php 

                    foreach($importResults['dispResults'] as $line) {                       
                        $content = '';
                        $content.= Html::tag('td', $i++);
                        $content.= Html::tag('td', isset($line['A'])?$line['A']:"");
                        $content.= Html::tag('td', isset($line['B'])?$line['B']:""); 
                        $content.= Html::tag('td', isset($line['C'])?$line['C']:""); 
                        $content.= Html::tag('td', isset($line['D'])?$line['D']:""); 
                        $content.= Html::tag('td', ($line['grade_point'] == '0') ? 'ERROR' : $line['grade_point']); //Status
                        $content.= Html::tag('td', ($line['type'] == 'E') ? 'ERROR' : 'SUCCESS'); //Status
                        $content.= Html::tag('td', $line['message']);  //Message
                                            
                        echo Html::tag('tr', $content, ['class' => ($line['type'] == 'E') ? 'danger' : 'success']); 
                        ?>  
                    <?php } ?> 
                    </tbody>
                    </table>

                    <?php 
                }
                    ?>

                 <?php if($importResults['dispResults1']!='')
              {
                    
                ?>
                <div class="alert alert-success" style="padding-top: 10px;">
                        <h4><i class="fa fa-check"></i> <?php 'Success!'; ?></h4>
                        <?= $importResults['dispResults1']; ?>
                </div>
                <?php 
                }
                ?>

        </section>
    </section>

        </div>
 
   

    <?php 
    if (isset($_SESSION['importResults1'])) {
                unset($_SESSION['importResults1']);
            }
    $_SESSION['importResults1']='';
}
?>

</div>
</div>

