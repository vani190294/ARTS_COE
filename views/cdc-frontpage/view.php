<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FrontpClg */

$this->title = 'View Front Page Dept.';
$this->params['breadcrumbs'][] = ['label' => 'Index', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frontp-clg-view">


<br>

<div class="box box-success">
<div class="box-body"> 
      <div class="col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-xs-9 col-sm-9 col-lg-9">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-3 col-sm-3 col-lg-3">
       

            <?= Html::a('Back', ['index'], ['class' => 'btn btn-primary pull-right']) ?>
           
        </div>
    </div>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12" style="font-size: 16px;">
        <b>Regulation: <?= $regulationyear;?>
        Degree Type: <?= $checksemdata['degree_type'];?></b>
        Department: <?= $checksemdata['dept_code'];?></b>
        <br><br>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <h3>Vision</h3>
        <p>
            <?= $vision['vision'];?>
        </p>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <h3>Mission</h3>
        <ul>
            <?php 
            foreach ($mission as $key => $value) 
            { ?>
                <li><?= $value['mission'];?></li>

            <?php } ?>
        </ul>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <h3>Program Educational Objectives</h3>
        <ol>
            <?php 
            foreach ($peo_list as $key => $value) 
            { ?>
                <li><?= $value['peo'];?></li>

            <?php } ?>
        </ol>
    </div>

    <?php if(count($pso_list)>0)
    {?>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <h3>Program Specific Objectives</h3>
        <ol>
            <?php 
            foreach ($pso_list as $key => $value) 
            { ?>
                <li><?= $value['pso'];?></li>

            <?php } ?>
        </ol>
    </div>

    <?php }
    if($checksemdata['degree_type']!='UG')
    {
        $po_list = Yii::$app->db->createCommand("SELECT * FROM cur_frontpage_list WHERE cur_fp_id='".$model->cur_fp_id."' AND po!='-'")->queryAll();?>

        <div class="col-xs-12 col-sm-12 col-lg-12">
        <h3>Program Outcomes</h3>
        <ol>
            <?php 
            foreach ($po_list as $value) 
            { ?>
                <li><b><?= $value['po_title'];?>: </b><?= $value['po'];?></li>

            <?php } ?>
        </ol>
    </div>

    <?php } ?>
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

            <?php $jj=1;
            foreach ($peo_list as $key => $value) 
            {
                
                ?>

                <tr>
                    <td>PEO <?= $jj;?></td>

                <?php $checkpeopo =Yii::$app->db->createCommand("SELECT po_tick FROM cur_front_peo_po_mapping WHERE cur_fpl_id='".$value['cur_fpl_id']."'")->queryAll();

                foreach ($checkpeopo as $key => $ppvalue) 
                {
                    if($ppvalue['po_tick']==0)
                    {
                        echo  "<td align=center>-</td>"; 
                    }
                    else
                    {
                        echo  "<td align=center>".$ppvalue['po_tick']."</td>"; 
                    }
                                          
                }
                ?>

                </tr>
            <?php $jj++;}
            ?>

        </table>
    </div>

 <?php if($model->approve_status==0) 
    {?>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-9 col-sm-9 col-lg-9"></div>
            <div class="col-xs-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton('Approve', ['class' => 'btn btn-success pull-right','name'=>'saveelect']); ?>
                </div>
            </div>
    </div>

    <?php } ?>
    
    <?php ActiveForm::end(); ?>
</div>
</div>

   
</div>
