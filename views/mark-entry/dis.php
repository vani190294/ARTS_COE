<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use kartik\date\DatePicker;
use app\models\ExamTimetable;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;

$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(), 'register_number', 'register_number');
$register_numbers = empty($student->register_number) ? $numbers : Student::findOne($student->register_number);

$batch_id = isset($_POST['bat_val']) ? $_POST['bat_val'] : '';
$batch_map_id = isset($_POST['bat_map_val']) ? $_POST['bat_map_val'] : '';
$sem = isset($_POST['exam_semester']) ? $_POST['exam_semester'] : '';
$from_reg_no = isset($_POST['from_reg']) ? $_POST['from_reg'] : '';
$to_reg_no = isset($_POST['to_reg']) ? $_POST['to_reg'] : '';

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Mark Statement";
$year = isset($_POST['mark_year']) ? $_POST['mark_year'] : date('Y');
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
    <div class="box box-success">
        <div class="box-body"> 
            <div>&nbsp;</div>

            <?php Yii::$app->ShowFlashMessages->showFlashes(); ?>

            <?php
            $form = ActiveForm::begin([
                        'id' => 'mark-entry-form',
                        'fieldConfig' => [
                            'template' => "{label}{input}{error}",
                        ],
            ]);
            ?>

            <div class="row">
                <div class="col-xs-12 col-lg-12 col-sm-12">
                    <div class="col-sm-2">
                        <?php
                        echo $form->field($model, 'stu_batch_id')->widget(
                                Select2::classname(), [
                            'data' => ConfigUtilities::getBatchDetails(),
                            'options' => [
                                'placeholder' => '-----Select ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH) . ' ----',
                                'id' => 'stu_batch_id_selected',
                                'name' => 'bat_val',
                                'value' => $batch_id,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH));
                        ?>
                    </div>

                    <div class="col-sm-2">
                        <?php
                        echo $form->field($model, 'stu_programme_id')->widget(
                                Select2::classname(), [
                            'data' => ConfigUtilities::getDegreedetails(),
                            'options' => [
                                'placeholder' => '-----Select ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . ' ----',
                                'id' => 'stu_programme_selected',
                                'name' => 'bat_map_val',
                                'value' => $batch_map_id,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME));
                        ?>
                    </div>

                    <div class="col-sm-2">
                        <?= $form->field($model, 'year')->textInput(['value' => date('Y')]) ?>

                    </div>
                    <div class="col-sm-2">
                        <?php echo $form->field($model,'month')->widget(
                            Select2::classname(), [
                                'options' => [
                                    'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                                    'id'=>'exam_month',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ]) 
                    ?>

                    </div>
                    <!-- </div>
                
                    <div class="col-xs-12 col-lg-12 col-sm-12"> -->
                  

                     <div class="col-sm-2">
                        <?= $form->field($student, 'register_number')->textInput(['id=>reg']) ?>

                   
                </div>
            </div>
                <div class="col-xs-12 col-sm-12 col-lg-12">   
                <div class="col-xs-12 col-sm-2 col-lg-2">   
                   
                        <?php
                        $model->credit_type = 'CBCS';
                        echo $form->field($model, 'credit_type')->widget(
                                Select2::classname(), [
                            'data' => $model->getCreditsystem(),
                            'options' => [
                                'placeholder' => '-----Select ----',
                                'id' => 'deg_credit_type',
                                'name' => 'deg_credit_type',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])->label('Mark Statement Type');
                        ?>      
                   </div>
                    <div class="col-xs-12 col-sm-2 col-sm-2">
                        <?php 
                            echo '<label>Statement Date</label>';
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
                    <div class="col-xs-12 col-sm-2 col-sm-2">
                        <?= $form->field($model, 'year')->textInput(['name'=>'top_margin','placeholder'=>'-5 to push up +5 for to push down'])->label('Top Margin') ?>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-sm-2">
                        <?= $form->field($model, 'year')->textInput(['placeholder'=>'-5 to push up +5 for to push down','name'=>'bottom_margin'])->label('Bottom Margin') ?>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-sm-2">
                        <?= $form->field($model, 'year')->textInput(['placeholder'=>'Default 23 Subjects','name'=>'count_of_subs'])->label('Page Break') ?>
                    </div>
                    <?php
                    echo '<img class="img-responsive" id="imageresource" src="'.Yii::getAlias("@web").'/images/cbcs-pg.jpg" alt="Image">'; 
                    ?>
                      
                    <!-- Creates the bootstrap modal where the image will appear -->
                    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="myModalLabel">Image preview</h4>
                          </div>
                          <div class="modal-body">
                            <img id="imagepreview" src="" >
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform"><br/>
                <?= Html::submitButton('Print', ['onClick' => "spinner(); validateMarkStatement();", 'name' => 'get_marks', 'class' => 'btn btn-success']) ?>
<?= Html::a("Reset", Url::toRoute(['mark-entry/dis']), ['onClick' => "spinner();", 'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                    </div>
                </div>
<?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
if(isset($mark_statement_type) && !empty($mark_statement_type))
{
    if($mark_statement_type=='CBCS')
    {
        if($print_trimester==1)
        {
            include_once("trimester_markstatement.php");
        }
        else if($deg_info->degree_type=='PG')
        {
            if($deg_info->degree_code=='Ph.D')
            {
                include_once("trimester_markstatement_phd.php");
            }
            else
            {
                include_once("trimester_markstatement.php");
            }
            
        }
        else
        {
            
            include_once("new_markstatement_working.php");
         //include_once("new_markstatement_working_2016arrear.php");
        }
    }
} 

    



?>       

