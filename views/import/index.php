<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Import;
use kartik\dialog\Dialog;
echo Dialog::widget();

$this->title = "Import";
$this->params['breadcrumbs'][] = $this->title;

?>
<br /><br />
<div class="import-index">
	<div class="box box-primary">
  		<div class="box-body">

<?php 
$model=isset($model)?$model:new Import();
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
 
    <?php 
    $form = ActiveForm::begin([
				'options' => ['enctype' => 'multipart/form-data'],
			]); 
    $checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
?>

<div class="row">
	<div class="col-sm-12 col-xs-3 col-lg-3">
		<h3>Importing for </h3>

		<?php 

		if($checkAccess=='Yes')
		{
			$nominal_status = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_ENABLE_NOMINAL); 
		
			echo $form->field($model, 'file_name')->widget(
	                Select2::classname(), [
	                'data' => [
	                	'degree_import'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." Import",
				    	'programme_import'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." Import",
				    	'subject' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT),
				    	'student'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
				    	'examtimetable'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Time Table', 
				    	'hallimport'=>"Import Halls",
				    	'tc'=>"Import TC Data",
				    	'absentimport'=>"Import ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT),
				    	'papernoupdate' => "Update Paper No",
				    	'partnoupdate' => "Update Part No",
				    	'updateciamarks'=>"ARTS: Update Internal Marks",
				    	'arts_subjectwiseimport'=>"OMR Marks Import",
				    	'fees_import'=>"Arrear Fees Import",
				    	'updateesemarks'=>" ARTS: Update External Marks",
				    	'subjectwiseciaimport'=>"Import Internal Marks",				    	
				    	'reuploadstudentdob'=>"Update ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." DOB",
				    	'nominal_import'=>"Import ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL),
				    	'update_nominal'=>"Update ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL),
				    	'value_nominal_import'=>" ARTS:Value Added Nominal Import",
				    	'value_mark_import'=>" ARTS:Value Added Mark Import",
				    	'value_added_subject_import'=>" ARTS:Value Added Subject Import",
				    	'updateciamarksvalue'=>"ARTS:Value Added Update CIA Marks",
				    	'updateesemarksvalue'=>"ARTS:Value Added Update ESE Marks",
				    	'ciavaluemark'=>"ARTS:Value Added CIA Import",
				    	'student_photos'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Photos"
				    	,	
				    	'valuation_faculty'=>"Import Valuation Faculty",
				    	'valuation_scrutiny'=>"Import Valuation Scrutiny",
				    	'valuation_faculty_allocation'=>"Import Faculty Valuation Allocation",
				    	'addexamtimetable'=>"Additional Credits ExamTimetable", 
				    	'absentadd'=>"Additional Credits Absent",
				    	'updateaddexam'=>"ADC Update ExamTimetable", 
				    	'hall'=>"Hall Invigilation", 
				    
				    	
				    ],
	                'options' => [
					        'placeholder' => 'Select  ...',
					        'onchange' => 'changeFile(this.value); ',
					   ],
	                
	                
	            ])->label(false);
		}
		else
		{
			$nominal_status = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_ENABLE_NOMINAL); 
		
			echo $form->field($model, 'file_name')->widget(
	                Select2::classname(), [
	                'data' => [
	                	'degree_import'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." Import",
				    	'programme_import'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." Import",
				    	'subject' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT),
				    	'student'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
				    	'examtimetable'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Time Table', 
				    	'hallimport'=>"Import Halls",
				    	'dummyimport'=>"Import ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY),
				    	'absentimport'=>"Import ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT),
				    	'papernoupdate' => "Update Paper No",
				    	'partnoupdate' => "Update Part No",
				    	'mandatorymarksimport'=>"Mandatory Marks",
				    	'arts_subjectwiseimport'=>"OMR Mark Import",
				    	'subjectwiseciaimport'=>"Import Internal Marks",
				    	'transapplication'=>"Import Transparency Application",
				    	'revalapplication'=>"Import Revaluation Application",
				    	'fees_import'=>"Arrear Fees Import",
				    	'reuploadstudentdob'=>"Update ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." DOB",
				    	'nominal_import'=>"Import ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL),
				    	'value_nominal_import'=>" ARTS:Value Added Nominal Import",
				    	'value_mark_import'=>" ARTS:Value Added Mark Import",
				    	'value_added_subject_import'=>" ARTS:Value Added Subject Import",
				    	'updateciamarksvalue'=>"ARTS:Value Added Update CIA Marks",
				    	'updateesemarksvalue'=>"ARTS:Value Added Update ESE Marks",
				    	'ciavaluemark'=>"ARTS:Value Added CIA Import",
				    	'student_photos'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Photos"
				    	,	
				    	'valuation_faculty'=>"Import Valuation Faculty",
				    	'valuation_scrutiny'=>"Import Valuation Scrutiny",
				    	'valuation_faculty_allocation'=>"Import Faculty Valuation Allocation",
				    	'addexamtimetable'=>"Additional Credits ExamTimetable",
				    	'absentadd'=>"Additional Credits Absent",
				    	'updateaddexam'=>"ADC Update ExamTimetable",
				    	 'hall'=>"Hall Invigilation", 
				    
				    	
				    	  
				    ],
	                'options' => [
					        'placeholder' => 'Select  ...',
					        'onchange' => 'changeFile(this.value); ',
					   ],
	                
	                
	            ])->label(false);
		}

		 
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
			<button onClick="spinner();" type="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>
</div>

			
<div id='change_student_text' style="margin-top: 25px;" class="row">
    <div class="col-xs-12">                	
        <div class="col-xs-12 col-sm-6 col-lg-6">                        
			<div id="changeColors" class="callout callout-primary callout-import-section">
				<h4><?php echo 'You must have to follow the following instruction at the time of importing data'; ?></h4>
				<ol>
					<li><b><?php echo 'The field with red color are the required field cannot be blank.'; ?></b></li>
				
					<li><?php echo 'Birth date must be less than current date.'; ?></li>
					<li><?php echo 'Import detail must match with application selected language.'; ?></li>
				</ol>
				<h5><?php echo '<h5>Download the sample format of <b>Excel sheet.</b></h5>'; ?> 
					<b>
						<?= Html::a(('Download'), ['download-sample','id'=>'download_sample_id'],['target'=>'_blank','value'=>'1','name'=>'samplefileName','id'=>'download_smple']) ?>									
					</b>
				</h5>
			</div>				
        </div> 
        
    </div>
</div>  

<div id='show_student_text' style="margin-top: 25px;" class="row">
    <div class="col-xs-12">                	
        <div class="col-xs-12 col-sm-6 col-lg-6">                        
			<div id="changeColors_student" class="callout callout-success callout-import-section box box-danger">
				<h4><?php echo 'You must have to follow the following instruction at the time of importing <b> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Photos".'</b> '; ?></h4>
				<ol>
					<li><b><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Image should have the '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Registration Number in CAPITAL LETTER followed by the supported extenstions.'; ?></b></li>
				
					<li><?php echo 'Supported Extensions will be : <b> '.implode(", ",ConfigUtilities::ValidFileExtension())." </b>"; ?></li>
					<li><?php echo 'Import detail must match with application selected language.'; ?></li>
					<li><?php  echo '<h5>Download the sample format of <b>ZIP File</b></h5>'; ?></li>
					<li><b><?= Html::a(('Download'), ['download-sample','id'=>'download_sample_stu_id'],['target'=>'_blank','value'=>'1','style'=>'color: #FFF;','name'=>'samplefileName','id'=>'download_smple_stu_id']) ?></b></li>
				</ol>
				
			</div>				
        </div> 
        
    </div>
</div>


<?php ActiveForm::end(); ?>  
</div>

</div>
</div>

<?php 

if(isset($_SESSION['importResults']) && !empty($_SESSION['importResults'])) { 
	$importResults=$_SESSION['importResults']; 
	if($importResults['result_for']==ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT))
	{
			require("student_result.php");
	} // Result for 
	else if ($importResults['result_for']=='dobupdate') 
	{
		require("stu-dob-update.php");
	}
	else if ($importResults['result_for']==ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)) {
		require("subeject_result.php");
	}
	else if ($importResults['result_for']==ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)) {
		require("exam_result.php");
	}
	else if ($importResults['result_for']="addexamtimetable") {
		require("exam_result.php");
	}
	else if ($importResults['result_for']=="Hall") {
		require("hall_import.php");
	}
	else if ($importResults['result_for']=="photos") {
		require("stu_photo_res.php");
	}
	else if ($importResults['result_for']==ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)) {
		require("programme_results.php");
	}
	else if ($importResults['result_for']==ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)) {
		require("degree_results.php");
	}
	else if ($importResults['result_for']=="Subwise CIA Marks") {
		require("subwise_mark_cia_import_res.php");
	}
	else if ($importResults['result_for']=="Absent Import") {
		require("subwise_absent.php");
	}
	else if ($importResults['result_for']=='Internal_update' || $importResults['result_for']=='Internal_update_engg') {
		require("internal_update_res.php");
	}
	else if ($importResults['result_for']=='result_update' ) {
		require("result_update_res.php");
	}
	else if ($importResults['result_for']=="Subwise Marks") {
		require("subwise_mark_import_res.php");
	}
	else if ($importResults['result_for']=="Arts Subwise Marks") {
		require("subwise_mark_import_res.php");
	}
	else if ($importResults['result_for']=="Mandatory Marks") {
		require("mandatory_mark_res.php");
	}
	else if ($importResults['result_for']=="Marks") {
		require("mark_import_res.php");
	}
	else if ($importResults['result_for']=="Update_paper") {
		require("paper_no_result.php");
	}
	else if ($importResults['result_for']=="Update_part_no") {
		require("update_part_no.php");
	}
	else if ($importResults['result_for']==ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL)) {
		require("nominal_import_res.php");
	}
	else if ($importResults['result_for']=='Nominal_update') {
		require("nominal_update_res.php");
	}
	else if ($importResults['result_for']=='Value_Nominal_Import') {
		require("nominal_import_res.php");
	}
	else if ($importResults['result_for']=="Value_Mark_Import") {
		require("subwise_mark_import_res.php");
	}
	else if ($importResults['result_for']=="CiaValueMark") {
		require("subwise_mark_import_res.php");
	}
	else if ($importResults['result_for']=='Value_added_Subject_Import') {
		require("subject_import_res.php");
	}
	else if ($importResults['result_for']=='fees_pay') {
		require("fees_pay.php");
	}
	else if ($importResults['result_for']=='Valuation Faculty') {
		require("valuation_faculty.php");
	}
	else if ($importResults['result_for']=='Valuation Scrutiny') {
		require("valuation_scrutiny.php");
	}
	else if ($importResults['result_for']=='Faculty Allocation') {
		require("valuation_faculty_allocation.php");
	}
	else if ($importResults['result_for']=='updateaddexam') {
		require("updateaddexam_result.php");
	}
	else if ($importResults['result_for']=='Hall Invigilation') {
		require("hall.php");
	}
	

	
	else
	{
		echo "Nothing Imported";
	}
	unset($_SESSION['importResults']); 
}  ?>

<?php
$this->registerJsFile(
    '@web/style/bower_components/datatables.net/js/jquery.dataTables.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(    
    '@web/style/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

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

