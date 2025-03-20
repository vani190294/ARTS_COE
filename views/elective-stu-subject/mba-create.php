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
echo Dialog::widget();


/* @var $this yii\web\View */
/* @var $model app\models\ElectiveStuSubject */

$this->title = 'MBA Elective Course Student Registration';
$this->params['breadcrumbs'][] = ['label' => 'Elective Course Student Registration', 'url' => ['mba-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-stu-subject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="elective-stu-subject-form">

<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">

        <div class="col-xs-12 col-sm-12 col-lg-12">

        <div class="col-md-2">
                
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id', 
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>


            <div class="col-md-2">

                    <?= $form->field($model, 'semester')->widget(
                            Select2::classname(), [ 
                                'data' => ['1'=>'1','2'=>'2','3'=>'3','4'=>'4'],                     
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'semester',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                   
            </div>

            <div class="col-sm-12 col-xs-5 col-lg-5">
            <label>Select the file</label>
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

            <div class="col-xs-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton('Upload', ['class' => 'btn btn-success']) ?>

                     <?= Html::a("Reset", Url::toRoute(['elective-stu-subject/mba-create']), ['onClick' => "spinner();", 'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>
            </div>
            
        </div>

         <div class="col-xs-12">                 
                <div class="col-xs-12 col-sm-6 col-lg-6">                        
                    <div id="changeColors1" class="callout callout-primary callout-import-section" style="background-color:#2173BC; color:#FFF !important;">
                        <h4><?php echo 'You must have to follow the following instruction at the time of importing data'; ?></h4>
                        <h5><?php echo '<h5>Download the sample format of <b>Excel sheet.</b></h5>'; ?> 
                            <b>
                                <?= Html::a(('Download'), ['download-sample','id'=>'download_sample_id'],['target'=>'_blank','value'=>'1','name'=>'samplefileName','id'=>'download_smple']) ?>                                  
                            </b>
                        </h5>
                    </div>              
                </div> 
        </div> 
    
    <?php ActiveForm::end(); ?>
</div>
</div>

</div>


</div>
