<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;


$this->title = "Import";
$this->params['breadcrumbs'][] = $this->title;

?>
<br /><br />


<div class="import-index">
	<div class="box box-primary">
  		<div class="box-body">
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 

    <?php 
    $form = ActiveForm::begin([
				'options' => ['enctype' => 'multipart/form-data'],
			]); 
?>


<div class="row">
	<div class="col-sm-12 col-xs-3 col-lg-3">
		<h3>Importing for </h3>
        <?php 
				echo Select2::widget([
				    'name' => 'file_name',
				    'data' => [
				    	'subject' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT),
				    	'student'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
				    	'examtimetable'=>'Exam Time Table', 
				    ],
				    'options' => [
				        'placeholder' => 'Select  ...',
				        'onchange' => 'changeFile(this.value);',
				    ],
				]);

        ?>
    </div> 
    <div class="col-sm-12 col-xs-5 col-lg-5">
    <h3>Select the file</h3>
		<div class="form-group">
			<div class="input-group input-file" name="uploaded_file">
				<span class="input-group-btn">
	        		<button class="btn btn-default btn-choose" type="button">Choose</button>
	    		</span>
	    		<input type="text" class="form-control" placeholder='Choose a file...' />
	    		<span class="input-group-btn">
	       			 <button class="btn btn-warning btn-reset" type="button">Reset</button>
	    		</span>
			</div>
		</div>
		<!-- COMPONENT END -->
		
	</div>
	<div class="col-sm-12 col-xs-3 col-lg-3">
		<h3> &nbsp; </h3>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>
</div>

			
<div style="margin-top: 25px;" class="row">
    <div class="col-xs-12">                	
        <div class="col-xs-12 col-sm-6 col-lg-6">                        
			<div id="changeColors" class="callout callout-primary callout-import-section">
				<h4><?php echo 'You must have to follow the following instruction at the time of importing data'; ?></h4>
				<ol>
					<li><b><?php echo 'The field with red color are the required field cannot be blank.'; ?></b></li>
					<li><?php echo 'ID is auto generated.'; ?></li>
					<li><?php echo 'Birth date must be less than current date.'; ?></li>
					<li><?php echo 'Import detail must match with application selected language.'; ?></li>
				</ol>
				<h5><?php echo 'Download the sample format of Excel sheet.'; ?> 
					<b>
						<?= Html::a(('Download'), ['download-sample','id'=>'download_sample_id'],['target'=>'_blank','value'=>'1','name'=>'samplefileName','id'=>'download_smple']) ?>									
					</b>
				</h5>
			</div>				
        </div> 
        
    </div>
</div>  
<?php ActiveForm::end(); ?>  
</div>

</div>
</div>
