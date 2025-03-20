
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\Categories */
/* @var $form yii\widgets\ActiveForm */

?>
<div>&nbsp;</div>
<div class="categories-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <?php $form = ActiveForm::begin([
                    'id' => 'categories-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12 categories">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?php echo $form->field($model, 'category_name')->widget(
                    Select2::classname(), [
                        'data' => $model->getCategories(),
                        'disabled'=>!$model->isNewRecord,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY).'----',
                            'name'=>'c_val',                                    
                            'id'=>'category_name',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                     ])->label('Category'); 
                ?>
                    
                <input type="hidden" id="c_list" name="c_list"> 
                <input type="hidden" id="c_list1" name="c_list1">
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 cat_creation">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'category_name')->textInput(['maxlength' => true,'autocomplete'=>'off']) ?>
            </div>

            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'description')->textInput(['maxlength' => true,'autocomplete'=>'off']) ?>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 cat_type_creation">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($categorytype, 'category_type')->textInput(['maxlength' => true,'id'=>'c_type','autocomplete'=>'off']) ?>
            </div>

            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($categorytype, 'description')->textInput(['maxlength' => true,'id'=>'c_desc','autocomplete'=>'off']) ?>
            </div>

            <div class="col-xs-12 col-sm-4 col-lg-4"><br>
                <input type="button" id="new1" value="Add" class="btn btn-success"]) ?>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 new_btn">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group col-xs-12 col-sm-4 col-lg-4">
                <input type="button" id="type" name="c_type" class="btn btn-success" value="New">
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 create_btn">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group col-xs-12 col-sm-4 col-lg-4">
                <?= Html::submitButton($model->isNewRecord ? 'Done' : 'Update', ['onClick'=>"spinner();",'class' => $model->isNewRecord ? 'btn  categories_submit_before btn-success' : 'btn btn-primary','id'=>'submit_before']) ?>

                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
 
    <div class="col-xs-12 col-sm-12 col-lg-12 cat_tbl">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="box-body table-responsive col">
                <div id = "stu_tbl"></div>
            </div>
        </div>
    </div> 

    <div class="col-xs-12 col-sm-12 col-lg-12 update_txt_box">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-12 col-sm-8 col-lg-8">
                <input type="text" id="update_type" name="update_type"> 
                <input type="text" id="update_desc" name="update_desc">
                <input type="hidden" id="update_cat_id" name="update_cat_id">
                <input type="button" value="Update" class="btn btn-primary update_cat_type">
            </div>
        </div>
    </div>
<div>
</div>
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>


