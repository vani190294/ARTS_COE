<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\MarkEntry;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="T&P Migrate to Temp  ";

?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

	<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'stu_programme_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">                
                <?= $form->field($model, 'year')->textInput(['id'=>'exam_year','name'=>'year','value'=>date("Y")]) ?>
            </div>

            <div class="col-lg-2 col-sm-2">
	            <?php echo $form->field($model,'month')->widget(
	                Select2::classname(), [
	                    'options' => [
	                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
	                        'id'=>'exam_month',
	                        'name'=>'month',
                            'onchange' => 'gettpsubjects(this.id,this.value);',
	                    ],
	                    'pluginOptions' => [
	                        'allowClear' => true,
	                    ],
	                ]) 
	                ?>
        	</div> 

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'mark_subject_code',
                            'name'=>'mark_subject_code',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
            </div>

        <!--div class="col-xs-12 col-sm-2 col-lg-2">
            <br>
            <label>
            <input type="checkbox" id="moderation" name="moderation" class="btn btn-success" value="1">With Moderation</label>
        </div-->
            
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
       		<br />
            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">

                <input type="Submit" id="tpmigrate" name="tpmigrate" class="btn btn-success" value="Migrate" onclick="spinner();">
                
                <?= Html::a("Reset", Url::toRoute(['mark-entry/tpmigrate']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                <!-- <?= Html::Button('Delete', ['onClick'=>"deletemigrate();",'class' => 'btn btn-success' ]) ?>  -->
            </div>
        
       	</div>
        <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>
    

    <?php ActiveForm::end(); ?>

	

    </div>
	</div>
	</div>

<?php
if(isset($verify_tempmark_data) && !empty($verify_tempmark_data))
{
     require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

     $body=$header=''; 

                    require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 

                   $header .="<table width='100%' style='overflow-x:auto;' border='1' class='table table-striped '>";
                    $header .= '<tr>
                                <td align="center" style=" border-left:0px !important; border-top:0px !important; border-right:0px !important;">
                                    <img class="img-responsive" width="75" height="75" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                </td>
                                <td colspan=11 align="center" style="border-top:0px !important; border-left:0px !important; border-right:0px !important;">
                                    <h3> 
                                      <center><b><font size="5px">' . $org_name . '</font></b></center>
                                    </h3>
                                    <h4>
                                        <center> <font size="3px">' . $org_address . '</font></center>
                                        <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                    </h4>
                                    <h4> Temp Result </h4>
                                   
                                                                     
                                </td>
                            <td align="center" style="border-top:0px !important; border-left:0px !important; border-right:0px !important;">  
                                <img width="75" height="75" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                        </tr></table> ';

                    $body .= "<table width='100%' style='overflow-x:auto;'  border='1' align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>
                            <th>Register Number</th>
                            <th>Subject Code</th>
                            <th>CIA</th>
                            <th>ESE</th>                            
                            <th>Total</th>
                            <th>Result</th>
                            <th>Grade Point</th>
                            <th>Grade Name</th>
                        </tr>
                        <tbody>"; 
                       $sl=1;
                    foreach ($verify_tempmark_data as  $value) 
                    { 

                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        $body .='<td>'.$value['register_number'].'</td>';
                        $body .='<td>'.$value['subject_code'].'</td>';
                        $body .='<td>'.$value['CIA'].'</td>';
                        $body .='<td>'.$value['ESE'].'</td>';
                        $body .='<td>'.$value['total'].'</td>';
                        $body .='<td>'.$value['result'].'</td>';
                        $body .='<td>'.$value['grade_point'].'</td>';
                        $body .='<td>'.$value['grade_name'].'</td>';
                        $body .='</tr>';
                         $sl++;
                    }


      echo  $content_1=$header.$body."</tbody></table>";


         if(isset($_SESSION['tpmigrate']))
        {
            unset($_SESSION['tpmigrate']);
        }
        $_SESSION['tpmigrate']=$content_1;



}
?>