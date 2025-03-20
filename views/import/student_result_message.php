
			<div class="col-sm-12">
				<?php 

				use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\helpers\Html;

$totalError = (count($importResults['dispResults'])-$importResults['totalSuccess']); ?>
				<?php $headerTr = $content = ''; $i = 1; ?>
				
				<?php if(!empty($importResults['totalSuccess'])) : ?>
					<div class="alert alert-success">
						<h4><i class="fa fa-check"></i> <?php 'Success!'; ?></h4>
						<?= "{$importResults['totalSuccess']} ". ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).'  importing successfully.' ?>
					</div>
				<?php endif; ?>
				
				<?php if(!empty($totalError)) : ?>
					<div class="alert alert-danger">
						<h4><i class="fa fa-ban"></i> <?php echo 'Error!'; ?></h4>
						<?= "{$totalError} ". ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).'  importing error.' ?>
					</div>
				<?php endif; ?>
				<?php
					$headerTr.= Html::tag('th', 'Sr No');
					$headerTr.= Html::tag('th', ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH));
					$headerTr.= Html::tag('th', ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE));
					$headerTr.= Html::tag('th', ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME));
					$headerTr.= Html::tag('th', ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION));
					$headerTr.= Html::tag('th', 'Register Number');
					$headerTr.= Html::tag('th', 'Name');
					$headerTr.= Html::tag('th', 'Gender');
					$headerTr.= Html::tag('th', 'DOB');
					$headerTr.= Html::tag('th', 'E-Mail');
					$headerTr.= Html::tag('th', 'Mobile Number');
					//$headerTr.= Html::tag('th', 'Aadhar Number');
					$headerTr.= Html::tag('th', 'Status');
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
						$content.= Html::tag('td', $line['A']); 
						$content.= Html::tag('td', $line['B']); 
						$content.= Html::tag('td', $line['C']); 
						$content.= Html::tag('td', $line['D']); 
						$content.= Html::tag('td', $line['U']); 
						$content.= Html::tag('td', $line['E']); 
						$content.= Html::tag('td', $line['G']);
						$content.= Html::tag('td', $line['F']); 
						$content.= Html::tag('td', $line['Q']); 
						$content.= Html::tag('td', $line['T']); 
					//	$content.= Html::tag('td', $line['AJ']); 
						$content.= Html::tag('td', ($line['type'] == 'E') ? 'ERROR' : 'SUCCESS'); //Status
											
						echo Html::tag('tr', $content, ['class' => ($line['type'] == 'E') ? 'danger' : 'success']); 




						?>	
					<?php } ?> 
					</tbody>
				</table>
			</div>