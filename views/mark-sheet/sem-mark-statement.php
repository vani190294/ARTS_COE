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


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Semester Mark Statement (NEW CODE)";
$year = isset($_POST['mark_year']) ? $_POST['mark_year'] : date('Y');
?>
<style type="text/css">
    .blink_me {
  animation: blinker 2s linear infinite;
  color: red; text-align: center;
}

@keyframes blinker {  
  50% { opacity: 0; }
}
</style>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
 <div class="blink_me"><h1><b>NEW CODE, So Please Check ALL CONDITIONS "MARK STATEMENT BEFORE PRINT!"</b></h1> </div>
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
                            'options' => [
                                'placeholder' => '-----Select ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . ' ----',
                                'id' => 'stu_programme_selected',
                                'name' => 'bat_map_val',
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
                        <?php
                        echo $form->field($student, 'register_number_from')->widget(Select2::classname(), [
                            'initValueText' => $register_numbers, // set the initial display text
                            'options' => ['placeholder' => 'Search for a register_number ...', 'name' => 'from_reg'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:"All"}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                        ]);
                        ?>
                    </div>

                    <div class="col-sm-2">
                        <?php
                        echo $form->field($student, 'register_number_to')->widget(Select2::classname(), [
                            'initValueText' => $register_numbers, // set the initial display text
                            'options' => ['placeholder' => 'Search for a register_number ...', 'name' => 'to_reg', 'id' => 'to_reg'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:"All"}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                        ]);
                        ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-lg-12">   
               <!--  <div class="col-xs-12 col-sm-2 col-lg-2">   
                   
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
                   </div> -->
                    <div class="col-xs-12 col-sm-2 col-sm-2">
                        <?php 
                            echo '<label>Statement Date</label>';
                            echo DatePicker::widget([
                                'name' => 'print_date',
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
                        <?= $form->field($model, 'year')->textInput(['name'=>'top_margin','placeholder'=>'Default 0'])->label('Top Margin') ?>
                    </div>
                   <!--  <div class="col-xs-12 col-sm-2 col-sm-2">
                        <?= $form->field($model, 'year')->textInput(['placeholder'=>'-5 to push up +5 for to push down','name'=>'bottom_margin'])->label('Bottom Margin') ?>
                    </div> -->

                     <div class="col-xs-12 col-sm-2 col-sm-2">
                        <?= $form->field($model, 'year')->textInput(['placeholder'=>'Default 5.2','name'=>'left_margin'])->label('Left Margin') ?>
                    </div>
                   
                     <div class="col-sm-2"><br>
                        <label class="control-label">
                            <input type="checkbox" name="with_umis">with UMIS
                        </label>

                    </div>
                    
                     
                    <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform"><br/>
                <?= Html::submitButton('Print', ['name' => 'get_marks', 'class' => 'btn btn-success', 'formtarget'=>'_blank']) ?>
                           <?= Html::a("Reset", Url::toRoute(['mark-sheet/sem-mark-statement']), ['onClick' => "spinner();", 'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


