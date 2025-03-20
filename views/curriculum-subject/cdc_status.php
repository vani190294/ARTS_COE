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


$this->title = "CDC Status Report";
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="curriculum-subject-index">
    <h1><?php echo $this->title; ?></h1>


    <div>&nbsp;</div>
<div class="box box-success">
<div class="box-body">
    

    <?php $form = ActiveForm::begin(); ?>

            <div class="col-xs-12 col-sm-12 col-lg-12">
                
<!-- 
                <div class="col-md-2">
                       <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['name' => 'degree_type','id' => 'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---')]) ?>
                </div> -->

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
                

                <div class="col-md-2 form-group"><br>
                        <?= Html::Button('Show', ['id'=>'cdc_statusreport','class' =>  'btn btn-success', 'onClick'=>'cdcstatusreport();spinner();']) ?>
                         <?= Html::a("Reset", Url::toRoute(['curriculum-subject/cdc-status']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
                    </div>

            </div>

        <div>&nbsp;</div>
                   

        <div class="col-xs-12 col-sm-12 col-lg-12" id="curriculumdataview" style="display: none;" >
             
            <div id="curriculumdata"></div>

        </div>

   

     <?php ActiveForm::end(); ?>                   
    
</div>
</div>
</div>