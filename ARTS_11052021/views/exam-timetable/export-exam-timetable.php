<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\dialog\Dialog;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\AbsentEntry */
/* @var $form ActiveForm */
$this->title= "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Time Table";

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
            <?= $form->field($examTimetable, 'exam_year')->textInput(['class'=>'form-control student_disable','value'=>date('Y')]) ?>
        </div>
    <div class="col-lg-2 col-sm-2">
        <?php echo $form->field($model,'batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getBatchDetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',   
                        'id' => 'stu_batch_id_selected', 
                        'value'=> $batch_id,                     
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
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
        <div class="col-lg-3 col-sm-3"> 
        <br />
           

            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable/export-exam-timetable']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
    

  </div>
</div>

 <?php ActiveForm::end(); ?>
<?php 
    if(isset($export_exam_time) && !empty($export_exam_time))
    {
        
        include('export-exam-timetable-pdf.php');
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found');
    }
?>    

</div>
</div>
</div><!-- exam-timetable-absent -->

