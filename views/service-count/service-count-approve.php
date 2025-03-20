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
/* @var $model app\models\CreditDistribution */

$this->title = 'Approve Service Course Request From Other Dept.';
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

    	<?php  if($deptdata[0]['degree_type']=='UG' && $fromdept!='S&H')
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
		    			<th  style="text-align: center;">Approve</th>
		    		</tr>
		    	</thead>
		    	 <tbody>
		    		<?php 
		    		$sl=1; $oec_count=0; $notapproved=0;
		    		foreach ($deptdata as $value) 
		    		{
		    			$servicedata = Yii::$app->db->createCommand("SELECT * FROM cur_service_count_details WHERE to_dept_id='".$coe_dept_id."' AND degree_type='".$value['degree_type']."' AND coe_dept_id=".$value['coe_dept_id']." AND coe_regulation_id=".$value['coe_regulation_id'])->queryOne();
		    			if(!empty($servicedata)) 
                            { 
		    			?>
		    			<tr>
		    			<td  style="text-align: center;"><?= $sl;?><input type="hidden" name="coe_dept_ids[]" value="<?= $value['coe_dept_id'];?>"></td>
		    			<td  style="text-align: center;"><?= $value['dept_code'];?></td>
		    			<?php 
		    				
		    			$svanme='stream'.$value['coe_dept_id'].'value[]';
		    			
		    			$checkservicecount = Yii::$app->db->createCommand("SELECT count(*) FROM cur_electivetodept WHERE coe_dept_id='".$coe_dept_id."' AND degree_type='".$value['degree_type']."' AND coe_dept_ids=".$value['coe_dept_id']." AND coe_regulation_id=".$value['coe_regulation_id'])->queryScalar();

                            $deptcheck=0;
                            
                            	$svalue=explode(",", $servicedata['service_count']);
                              	for ($i=0; $i <(count($svalue)-2) ; $i++) 
                              	{?>
		    					<td  style="text-align: center;">
		    						<?= $svalue[$i];?>
		    					</td>
		    				<?php $deptcheck=$deptcheck+$svalue[$i];

		    				 }


		    				if($deptcheck==$checkservicecount) 
		    					{

		    				if($servicedata['approve_status']==1)
		    					{?>

		    					<td style="text-align: center;color:green;">Approved</td>
		    				<?php } else { 
		    					$notapproved++;
		    					?>

		    					<td  style="text-align: center;"><input type="checkbox" name="approved[]" value="<?= $servicedata['cur_scd_id'];?>"></td>	

		    				<?php }


		    			} else { ?>
		    					<td style="text-align: center;color:red;">Count Mis Match</td>
		    				<?php }
		    			 ?>		    			
		    			</tr>
		    			
		    			<?php 
		    			} ?>
		    		<?php $sl++;
		    			//$total_aicte_norms=$total_aicte_norms+$value['aicte_norms'];
		    		} ?>
		    		
		    	</tbody> 
		    </table>
		    <input type="hidden" id="total_stream" value="<?= count($streamdata);?>">
		    </div>
			
	<?php }?>


    	<?php  if($deptdata[0]['degree_type']=='UG' && $fromdept=='S&H')
    	{?>
    		<div class="col-md-12">
		    <table width="100%" class="table">
		    	<thead>
		    		<tr>
		    			<th  style="text-align: center;">S.No.</th>
		    			<th  style="text-align: center;">Dept. / Stream</th>
		    			<?php foreach ($streamdatash as $value)  		    		
		    			{ ?>
		    				<th  style="text-align: center;"><?= $value['stream_name'];?></th>
		    			<?php } ?>
		    			<th  style="text-align: center;">Approve</th>
		    		</tr>
		    	</thead>
		    	 <tbody>
		    		<?php 
		    		$sl=1; $oec_count=0; $notapproved=0;
		    		foreach ($deptdata as $value) 
		    		{
		    			$servicedata = Yii::$app->db->createCommand("SELECT * FROM cur_service_count_details WHERE to_dept_id='8' AND degree_type='".$value['degree_type']."' AND coe_dept_id=".$value['coe_dept_id']." AND coe_regulation_id=".$value['coe_regulation_id'])->queryOne();
		    			if(!empty($servicedata)) 
                            { 
		    			?>
		    			<tr>
		    			<td  style="text-align: center;"><?= $sl;?><input type="hidden" name="coe_dept_ids[]" value="<?= $value['coe_dept_id'];?>"></td>
		    			<td  style="text-align: center;"><?= $value['dept_code'];?></td>
		    			<?php 
		    				
		    			$svanme='stream'.$value['coe_dept_id'].'value[]';
		    			
		    			$checkservicecount = Yii::$app->db->createCommand("SELECT count(*) FROM cur_servicesubtodept A JOIN cur_curriculum_subject B ON B.coe_cur_id=A.coe_cur_subid WHERE subject_type_id NOT IN (106) AND A.coe_dept_id='8' AND A.degree_type='".$value['degree_type']."' AND A.coe_dept_ids=".$value['coe_dept_id']." AND A.coe_regulation_id=".$value['coe_regulation_id'])->queryScalar();

                            $deptcheck=0;
                            
                            	$svalue=explode(",", $servicedata['service_count']);
                            	
                            	$start=count($svalue)-1; //exit;

                            	krsort($svalue);
                            	$n=(count($svalue))-4; //exit;
                            	//
                            	//print_r($svalue); exit;
                            	
                              	for ($i=0; $i<$n ; $i++) 
                              	{?>
		    					<td  style="text-align: center;">
		    						<?= $svalue[$start];?>
		    					</td>
		    				<?php $deptcheck=$deptcheck+$svalue[$start];
		    				$start--;
		    				 }


		    				if($deptcheck==$checkservicecount) 
		    					{

		    				if($servicedata['approve_status']==1)
		    					{?>

		    					<td style="text-align: center;color:green;">Approved</td>
		    				<?php } else { 
		    					$notapproved++;
		    					?>

		    					<td  style="text-align: center;"><input type="checkbox" name="approved[]" value="<?= $servicedata['cur_scd_id'];?>"></td>	

		    				<?php }


		    			} else { ?>
		    					<td style="text-align: center;color:red;">Count Mis Match  S&H Assigned: <?= $checkservicecount;?></td>
		    				<?php }
		    			 ?>		    			
		    			</tr>
		    			
		    			<?php 
		    			} ?>
		    		<?php $sl++;
		    			//$total_aicte_norms=$total_aicte_norms+$value['aicte_norms'];
		    		} ?>
		    		
		    	</tbody> 
		    </table>
		    <input type="hidden" id="total_stream" value="<?= count($streamdatash);?>">
		    </div>
			
	<?php }?>

	</div>
	<div class="col-xs-12 col-sm-12 col-lg-12">
		
        <div class="form-group pull-right">
            <br>

            <?php if($notapproved>0){?>
            <?= Html::submitButton('Submit', ['class' =>'btn btn-primary','id'=>'finishcredit']) ?>
        <?php }?>
            <?= Html::a("Back", Url::toRoute(['service-count/approve-status']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
    

</div>
