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


$this->title = "CDC Update/Delete";
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="curriculum-subject-index">
    <h1><?php echo $this->title; ?></h1>

    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 

    <div>&nbsp;</div>
<div class="box box-success">
<div class="box-body">
    

    <?php $form = ActiveForm::begin(); ?>

            <div class="col-xs-12 col-sm-12 col-lg-12">
                

               
                
                <div class="col-md-2">
                    
                        <?= $form->field($model, 'subject_code')->widget(
                                Select2::classname(), [ 
                                    'data' => ['1'=>'Update','2'=>'Delete'],                   
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'updatedeletecdc',
                                        'name'=>'updateoption',
                                        'onchange'=>'updatedeletecdcdata()'
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ])->label('Update Option') ?>
                   
                </div>

                <div class="col-md-2" style="display:none;" id="updatecdcdiv">
                    
                        <?= $form->field($model, 'special_subject')->widget(
                                Select2::classname(), [ 
                                    'data' => ['1'=>'Update Course Code','2'=>'Update Course Name','3'=>'Update LTP','4'=>'Update Assigned Course Sem to Sem'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'updatecdcoptiontype',
                                        'name'=>'updatecdcoptiontype',
                                        'onchange'=>'updatecdcltpdata()'
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ])->label('Update Option Type') ?>
                   
                </div>

                <div class="col-md-2" style="display:none;" id="deletecdcdiv">
                    
                        <?= $form->field($model, 'special_subject')->widget(
                                Select2::classname(), [ 
                                    'data' => ['1'=>'Delete Assigned Course','2'=>'Delete All'],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'name'=>'deletecdcoptiontype',
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ])->label('Delete Option Type') ?>
                   
                </div>

                 <div class="col-md-2">
                    
                        <?= $form->field($model, 'coe_regulation_id')->widget(
                                Select2::classname(), [  
                                    'data' => $model->getRegulationDetails(),                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'coe_regulation_id',
                                        'name' => 'coe_regulation_id',
                                        'onchange'=>'getLTPdetails();getregulationallsubjects()'
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]) ?>
                   
                </div>

                 <div class="col-md-2">
                    
                        <?= $form->field($model, 'special_subject')->widget(
                                Select2::classname(), [                    
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'cdcsubject_code',
                                        'name' => 'cdcsubject_code',
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ])->label('Course Code'); ?>
                   
                </div>

                

            </div>

            <div class="col-xs-12 col-sm-12 col-lg-12">
                <div class="col-md-2 form-group"><br>
                        <?= Html::Button('Show', ['class' =>  'btn btn-success', 'onClick'=>'cdcupdatedelete();spinner();']) ?>
                         <?= Html::a("Reset", Url::toRoute(['curriculum-subject/cdc-update-delete']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
                    </div>

            </div>

        <div>&nbsp;</div>
                   

        <div class="col-xs-12 col-sm-12 col-lg-12" id="curriculumdataview" style="display: none;" >
             
            <div class="col-md-2" id="updateltpdiv">
           
                        <?= $form->field($model, 'special_subject')->widget(
                            Select2::classname(), [                     
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_ltp_id', 
                                    'name'=>'ltp_new',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label('New LTP'); ?>
            </div>

            <div id="curriculumdata"></div>

            

            <div class="col-md-2 form-group"><br>
                        <?= Html::SubmitButton('Save', ['class' =>  'btn btn-success']) ?>
                         <?= Html::a("Cancel", Url::toRoute(['curriculum-subject/cdc-update-delete']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
            </div>
        </div>

   

     <?php ActiveForm::end(); ?>                   
    
</div>
</div>
</div>