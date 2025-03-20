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

$this->title = 'Credit Distributions Form Update';
$this->params['breadcrumbs'][] = ['label' => 'Credit Distributions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-distribution-create">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;font-size: 16px;">
    	<input type="hidden" id="regulation_year" name="regulation_year" value=" <?php echo $deptdata['regulation_year'];?>">   
    	<b><?php echo $regulation_year;?>
    	Degree Type: <?php echo $deptdata['degree_type'];?> 
    	Department: <?php echo $deptdata['dept_code'];?></b>
    	<br><br>
    	<input type="hidden" id="degree_type" name="degree_type" value="<?php echo $deptdata['degree_type'];?>">
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;">

    	<?php  if($deptdata['degree_type']=='UG'){?>
	    <table width="100%" class="table">
	    	<thead>
	    		<tr>
	    			<th  style="text-align: center;">S.No.</th>
	    			<th  style="text-align: center;">Stream</th>
	    			<?php for ($i=1; $i <=8 ; $i++) 
	    			{ ?>
	    				<th  style="text-align: center;">Sem <?= $i;?></th>
	    			<?php } ?>
	    			<th  style="text-align: center;">Total Credit</th>
	    			<th  style="text-align: center;">AICTE Model Curriculum</th>
	    		</tr>
	    	</thead>
	    	<tbody>
	    		<?php 
	    		$sl=1; $total_credit=$total_aicte_norms=0;
	    		foreach ($streamdata as $value) 
	    		{?>
	    			<tr>
	    			<td  style="text-align: center;"><?= $sl;?><input type="hidden" name="cur_dist_id[]" value="<?= $value['cur_dist_id'];?>"></td>
	    			<td  style="text-align: center;"><?= $value['stream_name'];?></td>

	    			<?php for ($s=1; $s <=8 ; $s++) 
	    			{ 
	    				$name="sem".$sl.$s;

	    				$nameq="sem".$s;
                        $values=$value[$nameq];
	    				?>
	    				<td style="text-align: center;"><input style="width: 50%;" type="text" id="<?php echo "sem".$sl.$s;?>" name="<?= $name;?>" onkeyup="sumcreadit(8);"  value="<?= $values?>"></td>
	    			<?php } ?>
	    			<td  style="text-align: center;"><input style="width: 50%;" type="text" id="<?php echo "credit".$sl;?>" name="stream_credit[]" readonly="readonly" value="<?= $value['total_credit'];?>"></td>
	    			<td  style="text-align: center;"><input style="width: 50%;" type="text" id="<?php echo "aicte_norms".$sl;?>" name="aicte_norms[]" value="<?= $value['aicte_norms'];?>" onkeyup="sumaictenorms(8);"></td>
	    			
	    			</tr>
	    		<?php $sl++;
	    			$total_aicte_norms=$total_aicte_norms+$value['aicte_norms'];
	    			 $total_credit=$total_credit+$value['total_credit'];
	    		} ?>
	    		<tr>
	    			<td colspan="2" align="right"><b>Total</b></td>

	    			<?php
	    			
	    			 for ($s=1; $s <=8 ; $s++) 	    			
	    			{ 
	    				$name="semcredit".$s.'[]';
	    				$idd="semcredit".$s;
	    				$semtot=0;
                        $name1="sem".$s;
                        foreach ($streamdata as $value) 
                        {
                            $semtot=$semtot+$value[$name1];
                        }
	    				?>
	    				<td style="text-align: center;">
	    					<input style="width: 50%;" type="text" id="<?= $idd;?>" name="<?= $name;?>" readonly="readonly" value="<?= $semtot;?>">
	    				</td>
	    			<?php } ?>

	    			<td><input style="width: 50%;" type="text" readonly="readonly" id="overtotal_credit" value="<?= $total_credit; ?>"></td>
	    			<td><input style="width: 50%;" type="text" readonly="readonly" id="overaicte_norms" value="<?= $total_aicte_norms; ?>"></td>
	    		</tr>
	    	</tbody>
	    </table>

		<?php }?>

		<?php  if($deptdata['degree_type']=='MBA'){?>
			<table width="100%" class="table">
	    	<thead>
	    		<tr>
	    			<th  style="text-align: center;">S.No.</th>
	    			<th  style="text-align: center;">Stream</th>
	    			<?php for ($i=1; $i <=4 ; $i++) 
	    			{ ?>
	    				<th  style="text-align: center;">Sem <?= $i;?></th>
	    			<?php } ?>
	    			<th  style="text-align: center;">Total Credit</th>
	    			<th  style="text-align: center;">AICTE Model Curriculum</th>
	    		</tr>
	    	</thead>
	    	<tbody>
	    		<?php 
	    		$sl=1; $total_credit=$total_aicte_norms=0;
	    		foreach ($streamdata as $value) 
	    		{?>
	    			<tr>
	    			<td  style="text-align: center;"><?= $sl;?><input type="hidden" name="cur_dist_id[]" value="<?= $value['cur_dist_id'];?>"></td>
	    			<td  style="text-align: center;"><?= $value['stream_name'];?></td>

	    			<?php for ($s=1; $s <=4 ; $s++) 
	    			{ 
	    				$name="sem".$sl.$s;

	    				$nameq="sem".$s;
                        $values=$value[$nameq];
	    				?>
	    				<td style="text-align: center;"><input style="width: 50%;" type="text" id="<?php echo "sem".$sl.$s;?>" name="<?= $name;?>" onkeyup="sumcreadit(4);"  value="<?= $values?>"></td>
	    			<?php } ?>
	    			<td  style="text-align: center;"><input style="width: 50%;" type="text" id="<?php echo "credit".$sl;?>" name="stream_credit[]" readonly="readonly" value="<?= $value['total_credit'];?>"></td>
	    			<td  style="text-align: center;"><input style="width: 50%;" type="text" id="<?php echo "aicte_norms".$sl;?>" name="aicte_norms[]" value="<?= $value['aicte_norms'];?>" onkeyup="sumaictenorms(4);"></td>
	    			
	    			</tr>
	    		<?php $sl++;
	    			$total_aicte_norms=$total_aicte_norms+$value['aicte_norms'];
	    			 $total_credit=$total_credit+$value['total_credit'];
	    		} ?>
	    		<tr>
	    			<td colspan="2" align="right"><b>Total</b></td>

	    			<?php
	    			
	    			 for ($s=1; $s <=4 ; $s++) 	    			
	    			{ 
	    				$name="semcredit".$s.'[]';
	    				$idd="semcredit".$s;
	    				$semtot=0;
                        $name1="sem".$s;
                        foreach ($streamdata as $value) 
                        {
                            $semtot=$semtot+$value[$name1];
                        }
	    				?>
	    				<td style="text-align: center;">
	    					<input style="width: 50%;" type="text" id="<?= $idd;?>" name="<?= $name;?>" readonly="readonly" value="<?= $semtot;?>">
	    				</td>
	    			<?php } ?>

	    			<td><input style="width: 50%;" type="text" readonly="readonly" id="overtotal_credit" value="<?= $total_credit; ?>"></td>
	    			<td><input style="width: 50%;" type="text" readonly="readonly" id="overaicte_norms" value="<?= $total_aicte_norms; ?>"></td>
	    		</tr>
	    	</tbody>
	    </table>
		<?php }?>

		<?php  if($deptdata['degree_type']=='PG'){?>
			<table width="100%" class="table">
	    	<thead>
	    		<tr>
	    			<th  style="text-align: center;">S.No.</th>
	    			<th  style="text-align: center;">Stream</th>
	    			<?php for ($i=1; $i <=4 ; $i++) 
	    			{ ?>
	    				<th  style="text-align: center;">Sem <?= $i;?></th>
	    			<?php } ?>
	    			<th  style="text-align: center;">Total Credit</th>
	    			<th  style="text-align: center;">AICTE Model Curriculum</th>
	    		</tr>
	    	</thead>
	    	<tbody>
	    		<?php 
	    		$sl=1; $total_credit=$total_aicte_norms=0;
	    		foreach ($streamdata as $value) 
	    		{?>
	    			<tr>
	    			<td  style="text-align: center;"><?= $sl;?><input type="hidden" name="cur_dist_id[]" value="<?= $value['cur_dist_id'];?>"></td>
	    			<td  style="text-align: center;"><?= $value['stream_name'];?></td>

	    			<?php for ($s=1; $s <=4 ; $s++) 
	    			{ 
	    				$name="sem".$sl.$s;

	    				$nameq="sem".$s;
                        $values=$value[$nameq];
	    				?>
	    				<td style="text-align: center;"><input style="width: 50%;" type="text" id="<?php echo "sem".$sl.$s;?>" name="<?= $name;?>" onkeyup="sumcreadit(4);"  value="<?= $values?>"></td>
	    			<?php } ?>
	    			<td  style="text-align: center;"><input style="width: 50%;" type="text" id="<?php echo "credit".$sl;?>" name="stream_credit[]" readonly="readonly" value="<?= $value['total_credit'];?>"></td>
	    			<td  style="text-align: center;"><input style="width: 50%;" type="text" id="<?php echo "aicte_norms".$sl;?>" name="aicte_norms[]" value="<?= $value['aicte_norms'];?>" onkeyup="sumaictenorms(4);"></td>
	    			
	    			</tr>
	    		<?php $sl++;
	    			$total_aicte_norms=$total_aicte_norms+$value['aicte_norms'];
	    			 $total_credit=$total_credit+$value['total_credit'];
	    		} ?>
	    		<tr>
	    			<td colspan="2" align="right"><b>Total</b></td>

	    			<?php
	    			
	    			 for ($s=1; $s <=4 ; $s++) 	    			
	    			{ 
	    				$name="semcredit".$s.'[]';
	    				$idd="semcredit".$s;
	    				$semtot=0;
                        $name1="sem".$s;
                        foreach ($streamdata as $value) 
                        {
                            $semtot=$semtot+$value[$name1];
                        }
	    				?>
	    				<td style="text-align: center;">
	    					<input style="width: 50%;" type="text" id="<?= $idd;?>" name="<?= $name;?>" readonly="readonly" value="<?= $semtot;?>">
	    				</td>
	    			<?php } ?>

	    			<td><input style="width: 50%;" type="text" readonly="readonly" id="overtotal_credit" value="<?= $total_credit; ?>"></td>
	    			<td><input style="width: 50%;" type="text" readonly="readonly" id="overaicte_norms" value="<?= $total_aicte_norms; ?>"></td>
	    		</tr>
	    	</tbody>
	    </table>
		<?php }?>
	</div>
	<div class="col-xs-12 col-sm-12 col-lg-12">
		<input type="hidden" id="total_stream" value="<?= count($streamdata);?>">
        <div class="form-group pull-right">
            <br>
            <?= Html::submitButton('Finish', ['class' =>'btn btn-primary', 'id'=>'finishcredit']) ?>

            <?= Html::a("Cancel", Url::toRoute(['credit-distribution-sem/index']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
    

</div>
