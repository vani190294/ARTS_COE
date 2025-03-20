<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\Degree */
/* @var $form yii\widgets\ActiveForm */


?>
<div>&nbsp;</div>
<div class="degree-form">
<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<div>&nbsp;</div>
    <?php $form = ActiveForm::begin([
                    'id' => 'degree-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-4 col-lg-4">
  <?= $form->field($model, 'degree_code')->textInput(['maxlength' => true,'id'=>'degree_code']) ?>
        </div>

        <div class="col-xs-12 col-sm-4 col-lg-4 d_name">
            <?= $form->field($model, 'degree_name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-sm-4 col-lg-4 d_type">
  <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['prompt' => Yii::t('app', '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' Type ---')]) ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 yrs_sem">
        <div class="col-xs-12 col-sm-4 col-lg-4">
            <?= $form->field($model, 'degree_total_years')->textInput() ?>
        </div>

        <div class="col-xs-12 col-sm-4 col-lg-4">
            <?= $form->field($model, 'degree_total_semesters')->textInput() ?>
        </div>
    </div>
            
    <div>&nbsp;</div>

    <div class="col-xs-12 col-sm-12 col-lg-12 deg_sub_btn">
        <div class="col-xs-12 col-sm-4 col-lg-4">
            <input type="button" id="deg_sub" name="deg_sub" class="btn btn-success" value="Submit">
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 deg_btn">
        <div class="form-group col-xs-12 col-sm-4 col-lg-4">
            <?= Html::submitButton($model->isNewRecord ? 'Done' : 'Update', ['onClick'=>"spinner();",'id' => $model->isNewRecord ? 'deg_done' : 'display_degree','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

            <?= Html::a("Reset", Url::toRoute(['degree/index']), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

  <div>&nbsp;</div>

    <div class="col-xs-12 col-sm-12 col-lg-12 deg_back_btn">
        <div class="col-xs-12 col-sm-4 col-lg-4">
            <input type="button" id="<?php echo $model->isNewRecord?'deg_back':'deg_back_degree'; ?>" name="deg_back" class="btn btn-success" value="<?php echo $model->isNewRecord?'Back':'Update'; ?>">
        </div>
    </div>

  <div>&nbsp;</div>

    <div class="col-xs-12 col-sm-12 col-lg-12 deg_tbl">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div id = "stu_tbl"></div>
        </div>
    </div> 

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

<?php 


if(!$model->isNewRecord)
{
    $this->registerJs(<<<JS
    var degree=$('#degree_code').val();
   $.ajax({
        url: base_url+'?r=ajaxrequest/getdegreevalue',
            type:'POST',
            data:{deg_name:degree},
            success:function(data)
            {
            if(data!=0)
                {
                    $('.deg_back_btn').show();
                    $('.deg_tbl').show();
                    $('#stu_tbl').show();
                    $('#stu_tbl').html(data); 
                    $('#deg_sub').hide();
                }   
            else
                {
                    $('.d_name').show();
                    $('.d_type').show();
                    $('.yrs_sem').show();
                    $('.deg_btn').show();
                    $('#deg_sub').hide();
                }
            }
    });
JS
);

}

?>