<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Signup */

$this->title = 'Create User';
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::getAlias("@web"); ?>/css/site.css" />
<div class="row login_background">
 <aside class="main-sidebar">   
<div class="login-box h-100 row align-items-center div_center ">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  
    
    <?= Html::errorSummary($model)?>
    <?php 
        Yii::$app->ShowFlashMessages->showFlashes();
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $show_text = isset($org_name)?$org_name:"Sri Krishna Institutions";
        $org_web = isset($org_web)?$org_web:"http://www.srikrishnaitech.com/";
    ?>  
    <div style="width: 100%" class="login-logo">
        <a href="<?php echo $org_web; ?>" target="_blank"><b><?php echo $show_text ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        

        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
       
        <?= $form
            ->field($model, 'username')
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>
       
        <?= $form
            ->field($model, 'email')
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>
        <?= $form
            ->field($model, 'password')
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
        <?= $form
            ->field($model, 'ConfirmPassword')
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('ConfirmPassword')]) ?>

        <div class="row">
            
            <div class="col-xs-6">
                <?= Html::submitButton( 'Create User', ['class' => 'btn btn-block btn-primary', 'name' => 'signup-button']) ?>

            </div>
            <div class="col-xs-6">
                <?= Html::a( 'Login', ['site/login'], ['class' => 'btn btn-block btn-warning', 'name' => 'login-button'] ) ?>
            </div>
            <!-- /.col -->
        </div>
        <?php ActiveForm::end(); ?>
       
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

</aside>
</div>

