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

$this->title="Transfer CONSOLIDATED MARK SHEET";
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
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
           <div class="col-lg-2 col-sm-2">   
                <?php echo $form->field($model, 'student_map_id')->widget(
                    Select2::classname(), [
                    'data' => ConfigUtilities::TransferStudents($student_map_id),                                
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' ----',
                        'id' => 'student_map_id', 
                    ],
					'pluginOptions' => [
                        'autoclose'=>true,
			        ],
                    ])->label('Register Number'); 
                ?>
            </div>
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model, 'result')->widget(
                Select2::classname(), [
                'data'=>['A4'=>'A4','A3'=>'A3'],
                    'options' => [
                        'placeholder' => '-----Select Layout----',
                        'id' => 'layout_type',
                        'name'=>'layout_type',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label("Layout"); 
            ?>
            </div>
            <div class="col-xs-12 col-sm-2 col-sm-2">
                <?php 
                    echo '<label>Date</label>';
                    echo DatePicker::widget([
                        'name' => 'created_at',
                        'value'=>date('m/d/Y'),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ['placeholder' => 'Select Mark Statement Date ...',],
                         'pluginOptions' => [
                            'autoclose'=>true,
                        ],
                    ]);
                 ?>
            </div>
            <div class="col-xs-12 col-lg-2 col-sm-2"> 
                <?php $model->result = 'No'; ?> 
                <?= $form->field($model, 'result')->radioList(array('Yes'=>'Yes' ,'No'=>'No '))->Label("Transcript"); ?>                    
            </div>
            
                <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                    <?= Html::submitButton('Print Consolidate', ['onClick'=>"spinner(); ", 'name'=>'get_marks','class' => 'btn btn-success' ]) ?>
                    <?= Html::a("Reset", Url::toRoute(['mark-entry-master/consolidate-mark-sheet-transfer']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>
            </div>
                    

    <?php ActiveForm::end(); ?>



</div>
</div>
</div>

<?php
require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

if($org_email=='coe@skasc.ac.in')
{
    if(isset($get_console_list) && !empty($get_console_list))
       
    {
      
            if($layout_type=='A4')
            {
                include_once("consolidate-mark-sheet-pg.php");    
            }
            else
            {
                include_once("consolidate_mark_sheet_pdf_a3.php");
            }
            
       
            
        }
        
    }

else if($org_email=='coe@skcet.in' && isset($get_console_list) && !empty($get_console_list))
    {
      
            if($layout_type=='A4')
            {
                include_once("skcet_consolidate-mark-sheet-pg.php");    
            }
            else
            {
                include_once("skcet_consolidate_mark_sheet_pdf_a3_transfer.php");
            }
            
        }
        
else if(isset($get_console_list)&& !empty($get_console_list))
{
	
	
        
            if($layout_type=='A4')
            {
                include_once("consolidate-mark-sheet-pg.php");    
            }
            else
            {
                include_once("skct_consolidate_mark_sheet_pdf_a3_transfer.php");
            }
       
        
        
		}

    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found');
    }
    ?>