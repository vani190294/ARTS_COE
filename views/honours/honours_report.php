<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use app\models\ExamTimetable;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\Honours */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Honours/Minours Report';
$this->params['breadcrumbs'][] = ['label' => 'Honours', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="honours-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="honours-form">

    <div class="box box-success">
        <div class="box-body"> 
            <?php Yii::$app->ShowFlashMessages->showFlashes();?>
            <div>&nbsp;</div>
            <?php $form = ActiveForm::begin(); ?>
            <div class="col-xs-12 col-sm-12 col-lg-12">

                <div class="col-xs-12 col-sm-12 col-lg-12">
               
                

                    <div class="col-lg-2 col-sm-2">
                        <?php echo $form->field($model,'stu_batch_id')->widget(
                            Select2::classname(), [
                                'data' => ConfigUtilities::getBatchDetails(),
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_batch_id_selected',
                                    'name'=>'bat_val',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label('Batch'); 
                        ?>
                    </div>
                   
                    <div class="col-lg-2 col-sm-2">
                                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label("Exam Year") ?>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-lg-2">
                    <?= $form->field($model, 'month')->widget(
                            Select2::classname(), [  
                                'data' => $galley->getMonth(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select Month ----',
                                    'id' => 'mark_month',  
                                    'name' => 'mark_month',                          
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                    </div>

                     <div class="col-lg-2 col-sm-2">
                        <?php echo $form->field($model, 'stu_programme_id')->widget(
                            Select2::classname(), [
                            'data'=>ConfigUtilities::getDegreedetails(),

                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_programme_selected',
                                    'name'=>'bat_map_val',
                                    //'value'=> $programme,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label('Programme'); 
                        ?>
                    </div>

                   <!--  <div class="col-md-2">

                            <?= $form->field($model1, 'honours_type')->widget(
                                    Select2::classname(), [   
                                        'data' => $model1->getHonourstypeDetails(),                      
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '-----Select----',
                                            'id' => 'honours_type',
                                        ],
                                       'pluginOptions' => [
                                           'allowClear' => true,
                                        ],
                                    ]) ?>
                           
                    </div> -->
                  
                    <div class="col-xs-3 col-sm-3 col-lg-3">
                        <div class="form-group">
                            <br>
                            <?= Html::Button('Show', ['class' => 'btn btn-success','id'=>'savehonour','onClick'=>"spinner();gethonoursminorsreport()",]) ?>
                             <?= Html::a("reset", Url::toRoute(['honours/honours-report']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
                        </div>
                    </div>


                </div>

             
            
            <?php ActiveForm::end(); ?>
            </div>

            <div class="col-xs-12 col-sm-12 col-lg-12 subject_information_tbl">               

                <div id = "sub_info_tbl"></div>
            </div>
        </div>

    </div>
</div>
