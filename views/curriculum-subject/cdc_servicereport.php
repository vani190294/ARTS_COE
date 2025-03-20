<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use app\models\HallAllocate;
echo Dialog::widget();



$this->title = "CDC S&H Report";
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="curriculum-subject-index">
    <h1><?php echo $this->title; ?></h1>


    <div>&nbsp;</div>
<div class="box box-success">
<div class="box-body">
    

    <?php $form = ActiveForm::begin(); ?>

            <div class="col-xs-12 col-sm-12 col-lg-12">
                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?= Yii::$app->user->getDeptId();?>">

                <div class="col-md-2">
                       <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['name' => 'degree_type','id' => 'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'checksem2();']) ?>
                </div>

                <div class="col-md-3">
                    
                        <?= $form->field($model, 'coe_regulation_id')->widget(
                                Select2::classname(), [  
                                    'data' => $model->getRegulationDetails(),                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'coe_regulation_id',
                                        'name' => 'coe_regulation_id',
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]) ?>
                   
                </div>
                

                <div class="col-md-2">

                    <?= $form->field($model, 'semester')->widget(
                            Select2::classname(), [                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'semester',
                                     'name' => 'semester',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                   
            </div>

                <div class="col-md-2 form-group"><br>
                        <?= Html::Button('Show', ['id'=>'cdc_finalreport','class' =>  'btn btn-success', 'onClick'=>'cdcshreport();']) ?>
                         <?= Html::a("Reset", Url::toRoute(['curriculum-subject/cdc-servicereport']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
                    </div>

            </div>


        <div  class="col-xs-12 col-sm-12 col-lg-12" id="curpdf" style="display: none;"> <br /><br />
            <?php 
            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/curriculum-subject/cdcshreport-pdf'], [
                'class'=>'pull-right btn btn-block btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
            ]); 
            ?>
            <div class="col-lg-10" ></div>
            <div class="col-lg-1 pull-right" > <?= $print_pdf; ?> </div>
        </div>
        <div>&nbsp;</div>
                   

        <div class="col-xs-12 col-sm-12 col-lg-12" id="curriculumdataview" style="display: none;" >
             
            <div id="curriculumdata"></div>

            
            <div class="form-group pull-right " id="approveservicecurriculum">
                    <?= Html::submitButton('Approve', ['class' =>  'btn btn-success','data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once the values were Submitted.']) ?>
                    
                </div>
        </div>

   

     <?php ActiveForm::end(); ?>                   
    
</div>
</div>
</div>