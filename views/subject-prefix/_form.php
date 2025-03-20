<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\SubjectPrefix */
/* @var $form yii\widgets\ActiveForm */
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<div class="subject-prefix-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-3">
        
            <?= $form->field($model, 'coe_dept_id')->widget(
                    Select2::classname(), [  
                        'data' => $model->getDepartmentdetails(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select----',
                            'id' => 'coe_dept_id', 
                            'value'=>$model->coe_dept_id,
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
       
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'prefix_name')->textInput(['maxlength' => true,'Autocomplete'=>"off"]) ?>
    </div>

     <div class="col-md-2">
    <div class="form-group"><br>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a("Cancel", Url::toRoute(['subject-prefix/index']), ['onClick'=>"spinner();",'class' => 'pull-right btn btn-warning']) ?>
    </div>
     </div>
    <?php ActiveForm::end(); ?>

</div>
