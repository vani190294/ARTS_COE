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
/* @var $model app\models\CreditDistribution */

$this->title = 'Service Course Request to Other Dept. (Enter No. of Courses)';
//$this->params['breadcrumbs'][] = ['label' => 'Credit Distributions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

 $batch_name = Yii::$app->db->createCommand("SELECT B.batch_name FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE A.coe_regulation_id=". $deptdata[0]['coe_regulation_id'])->queryScalar();

?>
<div class="credit-distribution-create">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;font-size: 16px;">
    	<input type="hidden" id="regulation_year" name="regulation_year" value=" <?php echo $deptdata[0]['regulation_year'];?>">    	
    	<b>Regulation: <?php echo $deptdata[0]['regulation_year'];?>
    	Batch: <?php echo $batch_name;?>
    	Degree Type: <?php echo $deptdata[0]['degree_type'];?> 
    	Department: <?php echo $fromdept;?></b>
    	<br><br>
    	<input type="hidden" id="degree_type" name="degree_type" value="<?php echo $deptdata[0]['degree_type'];?>">
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center; overflow-x: auto;	">

    	<?php  if($deptdata[0]['degree_type']=='UG')
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
		    		$sl=1; $readonly='';
		    		foreach ($deptall as $value) 
		    		{?>
		    			<tr>
		    			<td  style="text-align: center;"><?= $sl;?><input type="hidden" name="coe_dept_ids[]" value="<?= $value['coe_dept_id'];?>"></td>
		    			<td  style="text-align: center;"><?= $value['dept_code'];?></td>
		    			<?php foreach ($streamdata as $value1)  		    		
		    			{ 
		    				

		    				$sname='stream'.$value['coe_dept_id'].'name[]';
		    				$svalue='stream'.$value['coe_dept_id'].'value[]';?>

		    				<?php if(($value1['stream_name']=='HSMC' || $value1['stream_name']=='BSC' || $value1['stream_name']=='IKS') && $value['coe_dept_id']!=8)
		    				{
		    					?>
		    					<td  style="text-align: center;">
		    					<input type="hidden" name="<?= $sname;?>" value="<?= $value1['stream_name'];?>">
		    					<input type="hidden" name="<?= $svalue;?>" value="0"></td>
		    			
		    			<?php }else { 

		    				if(($value1['stream_name']!='HSMC' && $value1['stream_name']!='BSC' && $value1['stream_name']!='IKS') && $value['coe_dept_id']=='8')
		    				{ ?>
		    					<td  style="text-align: center;">
		    					<input type="hidden" name="<?= $sname;?>" value="<?= $value1['stream_name'];?>">
		    					<input type="hidden" name="<?= $svalue;?>" value="0"></td>
		    			
		    				<?php } else {  ?>		    			
		    				<td  style="text-align: center;">
		    					<input type="hidden" name="<?= $sname;?>" value="<?= $value1['stream_name'];?>">
		    					<input type="number" name="<?= $svalue;?>" value="0"></td>
		    			
		    			<?php } }?>		    			
		    			<?php } ?>		    					    			
		    			</tr>
		    		
		    		<?php $sl++;
		    			//$total_aicte_norms=$total_aicte_norms+$value['aicte_norms'];
		    		} ?>
		    		
		    	</tbody> 
		    </table>
		    <input type="hidden" id="total_stream" value="<?= count($streamdata);?>">
			</div>
			
	<?php }?>

	</div>
	<div class="col-xs-12 col-sm-12 col-lg-12">
		
        <div class="form-group pull-right">
            <br>
            <?= Html::submitButton('Save', ['class' =>'btn btn-primary','id'=>'finishcredit']) ?>

            <?= Html::a("Cancel", Url::toRoute(['service-count/index']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
    

</div>
