<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\models\LTP;
use app\models\Categorytype;
use app\models\Regulation;
/* @var $this yii\web\View */
/* @var $model app\models\LTP */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ltp-form">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
     
    <div class="col-lg-12 col-md-12 col-sm-12">
         <div class="col-md-3">
                    
                <?= $form->field($model, 'coe_regulation_id')->widget(
                        Select2::classname(), [  
                            'data' => $model->getRegulationDetails(),                      
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => [
                                'placeholder' => '-----Select----',
                                'id' => 'coe_regulation_id', 
                                'value'=>$model->coe_regulation_id,
                            ],
                           'pluginOptions' => [
                               'allowClear' => true,
                            ],
                        ]) ?>
           
        </div>
         <div class="col-md-2" id="projectid">
            <br>
            <label><input type="checkbox" name="project" id="project" onclick="ltphr();" <?php if($model->subject_type_id==123){ echo "checked"; }?>> Click If Project</label>
        </div>
        <div class="col-md-1">
            <?= $form->field($model, 'L')->textInput(['autocomplete'=>"none",'onkeyup'=>'ltphr();','value'=>0]) ?>
        </div>
        <div class="col-md-1">
            <?= $form->field($model, 'T')->textInput(['autocomplete'=>"none",'onkeyup'=>'ltphr();','value'=>0]) ?>
        </div>
        <div class="col-md-1">
            <?= $form->field($model, 'P')->textInput(['autocomplete'=>"none",'onkeyup'=>'ltphr();','value'=>0]) ?>
        </div>
         <div class="col-md-2">
            <?= $form->field($model, 'contact_hrsperweek')->textInput(['autocomplete'=>"none",'readonly'=>'readonly']) ?>
        </div>

       <div class="col-md-2">
            <?= $form->field($model, 'credit_point')->textInput(['autocomplete'=>"none",'readonly'=>'readonly']) ?>
        </div>
    
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12" >

        <div class="col-md-2" id="internalmode" <?php if($model->subject_type_id==123){ ?>style="display: none;"<?php }?>>
            
            <label>Internal/MC/Other Cource</label>
            <select class="form-control" id='int_mode_paper'  name='int_mode_paper' onchange="ltphr();">
                <option value="0">Select</option>
                <option value="105" <?php if($model->subject_type_id==105){ echo "selected"; }?>>Internal Mode</option>
                <option value="106" <?php if($model->subject_type_id==106){ echo "selected"; }?>>MC</option>
                <option value="122" <?php if($model->subject_type_id==122){ echo "selected"; }?>>AC</option>
            </select>
        </div>


       
        <?php if($model->isNewRecord==1){?>
             <input type="hidden" id="subject_type_id"  name="subject_type_id">
         <input type="hidden" id="subject_category_type_id"  name="subject_category_type_id">
         <div class="col-md-2">
            <?= $form->field($model, 'subject_type_id')->textInput(['autocomplete'=>"none",'readonly'=>'readonly']) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'subject_category_type_id')->textInput(['autocomplete'=>"none",'readonly'=>'readonly']) ?>
        </div>


        <?php }else
        {?>
         <input type="hidden" id="subject_type_id"  name="subject_type_id" value="<?php echo $model->subject_type_id;?>">
         <input type="hidden" id="subject_category_type_id"  name="subject_category_type_id" value="<?php echo $model->subject_category_type_id;?>">

        <div class="col-md-2">
            <?php $subtype=Categorytype::findone($model->subject_type_id);?>
            <?= $form->field($model, 'subject_type_id')->textInput(['autocomplete'=>"none",'readonly'=>'readonly','value'=>$subtype['category_type']]) ?>
        </div>

        <div class="col-md-2">
           <?php $subctype=Categorytype::findone($model->subject_category_type_id);?>
            <?= $form->field($model, 'subject_category_type_id')->textInput(['autocomplete'=>"none",'readonly'=>'readonly','value'=>$subctype['category_type']]) ?>
        </div>

        <?php }?>

       

       <div class="col-md-2">
            <?= $form->field($model, 'internal_mark')->textInput(['autocomplete'=>"none",'readonly'=>'readonly']) ?>
        </div>

          <div class="col-md-2">
            <?= $form->field($model, 'external_mark')->textInput(['autocomplete'=>"none",'readonly'=>'readonly']) ?>
        </div>

        

        <div class="col-md-2 form-group">
            <br>
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

            <?= Html::a("Cancel", Url::toRoute(['lTP/index']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
