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
$this->params['breadcrumbs'][] = ['label' => 'Cdcfrontpages', 'url' => ['index']];
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
        $label = array('0' =>'Mission' , '1' =>'PROGRAM EDUCTIONAL OBJECTIVES' ,'2' =>'PROGRAM SPECIFIC OUTCOMES','3' =>'PROGRAM OUTCOMES');
        $labelss = array('0' =>'mission' , '1' =>'peo' ,'2' =>'pso' ,'3' =>'po');

        if($degree_type=='UG')
        {
        for ($i=0; $i <(count($cnts)-1) ; $i++) 
        { 
            ?>
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <?php for ($j=1; $j <=$cnts[$i] ; $j++) 
                {
                    $array=$labelss[$i].'[]';
                    ?>
                <div class="col-xs-10 col-sm-10 col-lg-10">
                    <div class="form-group">
                       <label class="control-label"><?= $label[$i];?> <?= $j;?></label>
                       <textarea rows="5" class="form-control" required name="<?= $array;?>"></textarea>
                    </div>
                </div>

                <?php } ?>
            </div>
       <?php } ?>

   <?php } else { 

    for ($i=0; $i <count($cnts) ; $i++) 
        { 
            if($labelss[$i]=='po')
            {?>
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <?php for ($j=1; $j <=$cnts[$i] ; $j++) 
                {
                    $array=$labelss[$i].'[]';
                    $potitle=$labelss[$i].'title[]';
                    ?>
                <div class="col-xs-10 col-sm-10 col-lg-10">
                    <div class="form-group">
                       <label class="control-label"><?= $label[$i];?> <?= $j;?> Title</label>
                       <input type="text" name="<?= $potitle;?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                       <label class="control-label"><?= $label[$i];?> <?= $j;?> Content</label>
                       <textarea rows="5" class="form-control" required name="<?= $array;?>"></textarea>
                    </div>
                </div>

                <?php } ?>
            </div>
            <?php } else { ?>
                <div class="col-xs-12 col-sm-12 col-lg-12">
                    <?php for ($j=1; $j <=$cnts[$i] ; $j++) 
                    {
                        $array=$labelss[$i].'[]';
                        ?>
                    <div class="col-xs-10 col-sm-10 col-lg-10">
                        <div class="form-group">
                           <label class="control-label"><?= $label[$i];?> <?= $j;?></label>
                           <textarea rows="5" class="form-control" required name="<?= $array;?>"></textarea>
                        </div>
                    </div>

                    <?php } ?>
                </div>
       <?php } ?>

       <?php } ?>
    <?php } ?>

    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <h3>PEO/PO Mapping</h3>
        <table width="100%" border="1">
         <tr> 
         <td>PEO/PO</td>
           <?php 
                for ($k=1; $k <=$po_count ; $k++) 
                {?>
                    <th>PO<?= $k;?></th>
                <?php } ?>
        </tr>
        <?php 
            for ($j=1; $j <=$cnts[1] ; $j++) 
            {

                $arrayname='PEO_PO'.$j.'[]';?>
                <tr>
                    <td>PEO <?= $j;?></td>

                <?php 
                for ($k=1; $k <=$po_count ; $k++) 
                {?>

                    <td><select class="form-control"  name="<?= $arrayname;?>">
                        <option value="0">0</option>
                        <option value="1">1</option>
                         <option value="2">2</option>
                          <option value="3">3</option>
                        </select> 
                     </td>
                <?php } ?>
                </tr>
        <?php } ?>
           
        </table>
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
