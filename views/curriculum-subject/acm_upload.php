<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Import;
use kartik\dialog\Dialog;
use kartik\date\DatePicker;
echo Dialog::widget();

$this->title = "ACM Create";
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>
<br /><br />
<div class="import-index">
	<div class="box box-primary">
  		<div class="box-body">

<?php Yii::$app->ShowFlashMessages->showFlashes();?> 

    <?php 
    $form = ActiveForm::begin([
				'options' => ['enctype' => 'multipart/form-data'],
			]); 
   $userid=Yii::$app->user->getId();
?>

<div class="row">

	 <div class="col-xs-12 col-sm-12 col-lg-12">


            <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id' => 'degree_type','name'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'checksem2();']) ?>
            </div>
            
            <div class="col-md-2">
                
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id', 
                                    'name' => 'coe_regulation_id', 
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>

            <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
                echo '<label>Date<span style="color:#F00;">*</span> </label>';
                echo DatePicker::widget([
                    'name' => 'acm_date',
                    'type' => DatePicker::TYPE_INPUT,
                    'id'=>'acm_date',  
                   
                    'options' => [
                         'required'=>'required',
                        'placeholder' => '-- Select Date ...',
                        'autocomplete' => 'OFF',
                    ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy',
                    ],
                                       
                ]);
            ?>
        </div>

        <div class="col-md-2">

                    <?= $form->field($model, 'semester')->widget(
                            Select2::classname(), [                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [                                    
                                    'placeholder' => '-----Select----',
                                    'id' => 'semester'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                   
            </div>
            
         <div class="col-sm-12 col-xs-4 col-lg-4">
            <Label>Regulation File Upload</Label>
            <div class="form-group">
                <div class="input-group input-file" name="uploaded_acmminfile">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-choose" type="button">Choose</button>
                    </span>
                    <input type="text" class="form-control" name="uploaded_acmminfile" placeholder='Choose a file...' />
                    <span class="input-group-btn">
                         <button class="btn btn-warning btn-reset" type="button">Reset</button>
                    </span>
                </div>
            </div>
            
        </div> 


        <div class="col-sm-12 col-xs-2 col-lg-2">
            <Label> &nbsp; </Label>
            <div class="form-group">
                <button onClick="spinner();" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

	 </div>
	 <div class="col-xs-12 col-sm-12 col-lg-12">
	    
		
	</div>
</div>
<!-- 
			
<div id='change_student_text1' style="margin-top: 25px;" class="row">
    <div class="col-xs-12">                	
        <div class="col-xs-12 col-sm-6 col-lg-6">                        
			<div id="changeColors1" class="callout callout-primary callout-import-section" style="background-color: #2173BC; color:#FFF !important">
				<h4><?php //echo 'You must have to follow the following instruction'; ?></h4>
				<ol>					
					<li><h4>File Can be Uploaded Only Once</h4></li>
					<li><b>Upload File Size Maximum 2 MB</b></li>				
					<li>PDF File Only can upload</li>
				</ol>
				
			</div>				
        </div> 
        
    </div>
</div>   -->


<?php ActiveForm::end(); ?>  
</div>

</div>
</div>
