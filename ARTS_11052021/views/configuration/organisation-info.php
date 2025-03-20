<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();

$this->title = "Institute Information";


if(isset($file_content) && !empty($file_content))
{
    require(Yii::getAlias('@webroot/includes/institute_info.php'));
    $org_name = stripcslashes($institute_info['org_name']);
    $org_email=stripcslashes($institute_info['org_email']);
    $org_phone=stripcslashes($institute_info['org_phone']);
    $org_web=stripcslashes($institute_info['org_web']);
    $org_address=stripcslashes($institute_info['org_address']);
    $org_tagline=stripcslashes($institute_info['org_tagline']);
    $lock_status = date_create($institute_info['update_details']); 
    $current_date = date_create(date("Y-m-d"));
    $difference=date_diff($current_date,$lock_status);
    $lock_period = abs($difference->format("%R%a")); 
    $status = $lock_period>=180?false:true;
}
else
{
    $org_name = ""; 
    $org_email=""; 
    $org_phone=""; 
    $org_web=""; 
    $org_address="";
    $org_tagline="";
    $status=false; 
}

?>


<div class="subjects-form">
<div class="box box-success">
<div class="box-body"> 
   
     <?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div>&nbsp;</div>

    <?php 
    $condition = $model->isNewRecord?true:false;
    $form = ActiveForm::begin(); ?>
    <div class="row">
                <div class="col-xs-12">
    
        
            <div class="col-xs-12 col-lg-4 col-sm-4">
                <?= $form->field($model, 'org_name')->textInput(['maxlength' => true,'placeholder'=>'Eg: Sri Krishna Institutions ','id'=>'org_name','required'=>'required','value'=>$org_name,'disabled'=>$status]) ?>
            </div> 

            <div class="col-xs-12 col-lg-4 col-sm-4">
                <?= $form->field($model, 'org_email')->textInput(['type'=>'email','maxlength' => true,'placeholder'=>'Ex : info@srikrishna.ac.in','id'=>'org_email','value'=>$org_email,'disabled'=>$status]) ?>
            </div> 

            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'org_phone')->textInput(['maxlength'=>12,'placeholder'=>'Eg: 0422-2607359','id'=>'org_phone','value'=>$org_phone,'required'=>'required','disabled'=>$status]) ?>
            </div>
        
</div>
</div>
      <div class="row">
                <div class="col-xs-12">  
            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'org_web')->input('url',['maxlength' => true,'placeholder'=>'Ex : http://www.srikrishna.ac.in/','class'=>'form-control checkthisclass','id'=>'org_web','value'=>$org_web,'disabled'=>$status]) ?>
            </div>

            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'org_address')->textArea(['maxlength' => true,'placeholder'=>'Enter the Institute Address','style'=>'resize: none;','value'=>$org_address,'id'=>'org_address','required'=>'required','disabled'=>$status]) ?>
            </div>   
             <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'org_tagline')->textInput(['maxlength' => true,'placeholder'=>'An Autonomous college Affiliated to Bharathiyar University Accredited by NAAC with "A" Grade An ISO 9001:2008 Certified Institution','style'=>'resize: none;','required'=>'required','id'=>'org_tagline','value'=>$org_tagline,'disabled'=>$status]) ?>
            </div>       
    
</div>
</div>
        <div class="row">
                <div class="col-xs-12">
                
                	
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                    
                    	<div class="btn-group" role="group" aria-label="Actions to be Perform">
                            <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['onClick'=>"spinner();",'onmouseover'=>'validateThisForm();','class' => $model->isNewRecord ? 'btn btn-group btn-group-lg btn-success' : 'btn btn-group btn-group-lg btn-block btn-primary', 'data-confirm' => 'Are you sure you want to Update this Settings <br /> This will Effect in the <b>'.Yii::$app->params['app_name'].'</b>? <br /> You can update the <b>Institute Information twice in a Year</b> <br /> <b>Please Ensure your data submission is correct.?</b> ']) ?>
                            <?= Html::a("Reset Data", Url::toRoute(['']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning','style'=>'color: #fff;', 'data-confirm' => 'Are you sure you want Clear?']) ?>
                            
                        </div>
                    
                    
                        <div class="form-group">                           

                            
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-lg-2">
                        <div class="form-group">
                            
                        </div>
                    </div>
                </div>                
            </div>
    <?php ActiveForm::end(); ?>


</div>
</div>
</div>