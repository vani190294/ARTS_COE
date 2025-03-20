<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\db\Query;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\ServiceCount */

$this->title = 'Service Course Request to Other Dept.';
$this->params['breadcrumbs'][] = ['label' => 'Service Counts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$batch_name = Yii::$app->db->createCommand("SELECT B.batch_name FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE A.coe_regulation_id=". $deptdata[0]['coe_regulation_id'])->queryScalar();
?>
<div class="service-count-view">

     <h1><?= Html::encode($this->title) ?></h1>

<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;font-size: 16px;">
        <b>Regulation: <?php echo $deptdata[0]['regulation_year'];?>
        Batch: <?php echo $batch_name;?>
        Degree Type: <?php echo $deptdata[0]['degree_type'];?> 
        Department: <?php echo $fromdept;?></b>
        <br><br>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;">

        <?php if($deptdata[0]['degree_type']=='UG')
        {?>
         <div class="col-md-12">
        <table width="100%" class="table">
            <thead>
                <tr>
                    <th  style="text-align: center;">S.No.</th>
                   <th  style="text-align: center;">Dept. / Stream</th>
                        <?php foreach ($streamdata as $value)                   
                        { ?>
                            <th  style="text-align: center;"><?= $value['stream_name'];?></th>
                        <?php } ?>
                </tr>
            </thead>
           <tbody>
                    <?php 
                    $sl=1; $oec_count=0;
                    foreach ($deptall as $value) 
                    {?>
                        <tr>
                        <td  style="text-align: center;"><?= $sl;?></td>
                        <td  style="text-align: center;"><?= $value['dept_code'];?></td>
                        <?php 
                            $servicedata = Yii::$app->db->createCommand("SELECT cur_sc_id, service_type, service_count, oec_count FROM cur_service_count_details WHERE coe_dept_id='".$coe_dept_id."' AND degree_type='".$value['degree_type']."' AND to_dept_id=".$value['coe_dept_id']." AND coe_regulation_id=".$deptdata[0]['coe_regulation_id'])->queryOne();
                            
                            if(!empty($servicedata)) 
                            { 
                               $oec_count=$servicedata['oec_count'];
                                $svalue=explode(",", $servicedata['service_count']);

                              for ($i=0; $i <count($svalue) ; $i++) { 
                              ?>
                                <td  style="text-align: center;"><?= $svalue[$i];?></td>
                                <?php }?>  
                             <?php }?>              
                        </tr>
                    
                    <?php $sl++;
                        //$total_aicte_norms=$total_aicte_norms+$value['aicte_norms'];
                    } ?>
                    
                </tbody> 
        </table>
        </div>
            
        <?php }
        ?>
       
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group pull-right">
            
            <?= Html::a("Back", Url::toRoute(['service-count/index']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>

</div>
