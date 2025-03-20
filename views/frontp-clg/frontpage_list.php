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

$this->title = 'CDC Vision Mission';
$this->params['breadcrumbs'][] = ['label' => 'Index', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cdcfrontpage-create">

<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <h1><?= Html::encode($this->title) ?></h1>

    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">

        <div class="col-xs-10 col-sm-10 col-lg-10">
                    <div class="form-group">
                       <label class="control-label">Vision</label>
                       <textarea rows="5" class="form-control" required name="vision"></textarea>
                    </div>
        </div>

        <?php $cnts = explode(",", $cnts);
        $label = array('0' =>'Mission' , '1' =>'Program Outcomes');
        $labelss = array('0' =>'mission' , '1' =>'po');
        for ($i=0; $i <count($cnts) ; $i++) 
        { 
            ?>
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <?php for ($j=1; $j <=$cnts[$i] ; $j++) 
                {
                    $array=$labelss[$i].'[]';
                    $arraytitle=$labelss[$i].'title[]';
                    ?>
                <div class="col-xs-10 col-sm-10 col-lg-10">
                    <?php if($i==1){?>
                     <div class="form-group">
                       <label class="control-label"><?= $label[$i];?> <?= $j;?> Title</label>
                       <input type="text" class="form-control" required name="<?= $arraytitle;?>">
                    </div>
                    <?php }else{ ?>
                        <input type="hidden" class="form-control" required name="<?= $arraytitle;?>">
                    <?php } ?>
                    <div class="form-group">
                       <label class="control-label"><?= $label[$i];?> <?= $j;?></label>
                       <textarea rows="5" class="form-control" required name="<?= $array;?>"></textarea>
                    </div>
                </div>

                <?php } ?>
            </div>
       <?php } ?>

    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
       <div class="form-group pull-right ">
                <br>
                <?= Html::submitButton('Save', ['class' =>'btn btn-success']) ?>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
</div>

</div>
