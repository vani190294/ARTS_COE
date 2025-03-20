<?php 
use yii\helpers\Html;

if(!empty($importResults)) { ?>

<div class="box box-info">
	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-list-ul"></i> <?php echo 'Subjects Import Results'; ?></h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<?php $totalError = (count($importResults['dispResults'])-$importResults['totalSuccess']); ?>
				<?php $headerTr = $content = ''; $i = 1; ?>
				
				<?php if(!empty($importResults['totalSuccess'])) : ?>
					<div class="alert alert-success">
						<h4><i class="fa fa-check"></i> <?php 'Success!'; ?></h4>
						<?= "{$importResults['totalSuccess']}". ' Subjects importing successfully.' ?>
					</div>
				<?php endif; ?>
				
				<?php if(!empty($totalError)) : ?>
					<div class="alert alert-danger">
						<h4><i class="fa fa-ban"></i> <?php echo 'Error!'; ?></h4>
						<?= "{$totalError}". ' Subjects importing error.' ?>
					</div>
				<?php endif; ?>
				<?php
					$headerTr.= Html::tag('th', 'Sr No');
					$headerTr.= Html::tag('th', 'Subject No');
					$headerTr.= Html::tag('th', 'Subject Name');
					$headerTr.= Html::tag('th', 'Course');
					$headerTr.= Html::tag('th', 'Batch');
					$headerTr.= Html::tag('th', 'Sr No');
					$headerTr.= Html::tag('th', 'Subject No');
					$headerTr.= Html::tag('th', 'Subject Name');
					$headerTr.= Html::tag('th', 'Course');
					$headerTr.= Html::tag('th', 'Batch');
					$headerTr.= Html::tag('th', 'Course');
					$headerTr.= Html::tag('th', 'Batch');
				?>
				<table class="table table-striped table-bordered" id = "fixHeader">
					<thead>
						<?php echo Html::tag('tr', $headerTr, ['class' => 'active']) ?>
					</thead>
					<tbody>
					
					<?php 

					foreach($importResults['dispResults'] as $line) {						
						$content = '';
						$content.= Html::tag('td', $i++);
						$content.= Html::tag('td', $line['D']); 
						$content.= Html::tag('td', $line['E']); 
						$content.= Html::tag('td', $line['N']); 
						$content.= Html::tag('td', $line['H']); 
						$content.= Html::tag('td', $line['I']); 
						$content.= Html::tag('td', $line['J']); 
						$content.= Html::tag('td', $line['C']); 
						$content.= Html::tag('td', $line['B']); 
						$content.= Html::tag('td', $line['K'].'-'.$line['L'].'-'.$line['M']); 
						$content.= Html::tag('td', ($line['type'] == 'E') ? 'ERROR' : 'SUCCESS'); //Status
						$content.= Html::tag('td', ($line['type'] == 'E')); //Remarks/Message						
						echo Html::tag('tr', $content, ['class' => ($line['type'] == 'E') ? 'danger' : 'success']); 
						?>	
					<?php } ?> 
					</tbody>
				</table>
			</div>
		</div>
	</div><!--./box-body-->
</div><!--./box-->
<?php }  ?>

<?php
$ckBoxCheckScript = <<< JS
    var table = $('#fixHeader').DataTable( {
        scrollY:        "450px",
        scrollX:        true,
        scrollCollapse: false,
        paging:         false,
        bSort: 			false,
        bInfo: 			false,
    } );  
JS;
$this->registerJs($ckBoxCheckScript, yii\web\View::POS_READY);

?>
