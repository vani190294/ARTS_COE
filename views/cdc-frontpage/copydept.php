<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\CDCFrontpage */

$this->title = 'Mapping CDC Vision Mission';
$this->params['breadcrumbs'][] = ['label' => 'Index', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cdcfrontpage-create">
        <h1><?= Html::encode($this->title) ?></h1>

<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="col-md-2">
               <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['name'=>'degree_type','id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---')]) ?>
            </div>

            <?php if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
            {?>
                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?= Yii::$app->user->getDeptId();?>">

            <?php } else { ?>

                  <div class="col-md-2">
           
                    <?= $form->field($model, 'coe_dept_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getDepartmentdetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_dept_id',
                                    'name' => 'coe_dept_id',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>

            <?php } ?> 

            <div class="col-md-2">
                
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'from_regulation_id', 
                                    'name' => 'from_regulation_id', 
                                    'onchange'=>'getregulation();'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label("From Regulation") ?>
               
            </div>

             <div class="col-md-2">
                
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [                     
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'to_regulation_id', 
                                    'name' => 'to_regulation_id', 
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label("To Regulation") ?>
               
            </div>

         

           <div class="col-md-2 form-group"><br>
            <?= Html::submitButton( 'Assign', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-warning']) ?>
        </div>
        </div>
        
    </div>
    <?php ActiveForm::end(); ?>

</div>
</div>

</div>
