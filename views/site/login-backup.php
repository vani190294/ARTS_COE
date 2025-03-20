<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='fa fa-user-secret form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::getAlias("@web"); ?>/css/site.css" />
<div class="row login_background">
    
<div class="login-box h-100 row align-items-center div_center ">
    <?php 
        Yii::$app->ShowFlashMessages->showFlashes();
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $show_text = isset($org_name)?$org_name:"Sri Krishna Institutions";
    ?>  
    <div style="width: 100%" class="login-logo">
        <a href="<?php echo $org_web; ?>" target="_blank"><b><?php echo $show_text ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
        

        <div class="row">
            <div class="col-xs-6">
                <?= Html::submitButton('Sign in', ['class' => 'btn btn-success btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <div class="col-xs-6">
                <?= Html::a('Create User',  ['signup'],['class'=>"btn btn-primary btn-block btn-flat text-center"]) ?>
                <?php //$form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>

        
        <!-- /.social-auth-links -->

        

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->


</div>
