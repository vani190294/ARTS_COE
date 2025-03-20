<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\dialog\Dialog;
use app\models\Categorytype;
use kartik\date\DatePicker;
use app\models\ExamTimetable;


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\AbsentEntry */
/* @var $form ActiveForm */
$this->title= " Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Time Table Format";

$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$batch_id = isset($model->batch_id)?$model->batch_id:"";
$month = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='".$examTimetable->exam_month."'")->queryScalar();
?>
<h1><?php echo "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Timetable"; ?></h1>
<br /><br />
<div class="exam-timetable-absent">
<div class="box box-success">
<div class="box-body">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin(); ?>

<div class="row">
<div class="col-12">
    <div class="col-lg-2 col-sm-2">
        <?php echo $form->field($examTimetable,'coe_batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getBatchDetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',   
                        'id' => 'stu_batch_id_selected', 
                        'value'=> 'bat_val',                     
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>    
        </div>
    <div class="col-lg-2 col-sm-2">
       
 <?php echo $form->field($model,'degree_type')->widget(
                  Select2::classname(), [  
                        'data' => $model->getDegreeType(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Degree ----',
                            'id' => 'degree_type', 
                           ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])
                ?>

   </div>
   <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($examTimetable,'exam_month')->widget(
                  Select2::classname(), [  
                        'data' => ConfigUtilities::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month', 
                           ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])
                ?>
        </div> 
       <div class="col-lg-2 col-sm-2">
          <?= $form->field($examTimetable, 'exam_year')->textInput(['class'=>'form-control student_disable','value'=>date('Y')]) ?>
        </div> 
        <div class="col-lg-2 col-sm-2">
          <?= $form->field($examTimetable, 'semester')->textInput(['class'=>'form-control student_disable']) ?>
        </div> 
         <div class="col-lg-2 col-sm-2">
            <?= $form->field($examTimetable, 'qp_code')->radioList(ExamTimetable::getExamSession())->label('Exam Session (Optional)'); ?>
           
        </div>
   <div class="col-lg-2 col-sm-3"> 
        <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable/new-export-exam-timetable']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
    

  </div>
</div>

 <?php ActiveForm::end(); ?>
<?php 
    if(isset($new_export_exam_time) && !empty($new_export_exam_time))
    {
        
        include('new-export-exam-timetable-pdf.php');
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found');
    }
?>    

</div>
</div>
</div><!-- exam-timetable-absent -->

