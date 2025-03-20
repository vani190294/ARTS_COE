<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\ChangePassword */

$this->title = 'Change Password User By Admin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to change password:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-change']); ?>
             <?php Yii::$app->ShowFlashMessages->showFlashes();?>  
                 <div class="col-xs-12 col-sm-4 col-lg-4">
                    <?= $form->field($usermodel, 'username')->widget(
                            Select2::classname(), [  
                                'data' => $usermodel->getUser(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select User ----',
                                    'id' => 'usernameid',                            
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>
                <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'newPassword')->passwordInput() ?>
                 </div>
                <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'retypePassword')->passwordInput() ?>
                 </div>
                <div class="form-group">
                    <br>
                    <?= Html::submitButton('Changeuser', ['class' => 'btn btn-primary', 'name' => 'change-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
