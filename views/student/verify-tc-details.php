<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\dialog\Dialog;
use kartik\widgets\Select2;
use yii\helpers\Url;
use app\models\Batch;
use app\models\CoeBatDegReg;
use app\models\Degree;
use app\models\Programme;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

$formatter = \Yii::$app->formatter;
echo Dialog::widget();
$this->title = "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." TC Verification";
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if(isset($model->stu_section_name))
{
    $this->registerJs("$(document).ready(function() { $('.student_disable').attr('disabled',true)});");
}
$batch_id = isset($model->stu_batch_id)?$model->stu_batch_id:"";
$section_name = isset($model->stu_section_name)?$model->stu_section_name:"";
$degree_batch_mapping_id = isset($model->stu_programme_id)?$model->stu_programme_id:"";

?>
<h1><?php echo "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." TC Verification"; ?></h1>
<br /><br />
<div id="student_update_edit_page" class="configuration-form">
    <div class="box box-primary">
        <div class="box-body">          
               
            <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
            <?php 
                        $condition = $model->isNewRecord?true:false;
                        $form = ActiveForm::begin(); ?>
            
            <div class="row">
                <div  class="col-xs-12">                 
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'stu_batch_id')->widget(
                                Select2::classname(), [
                                'data' => ConfigUtilities::getBatchDetails(),
                                
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_batch_id_selected', 
                                    'value'=> $batch_id,    
                                    'class'=>'form-control student_disable',                              
                                ],

                               
                                
                            ]); 
                        ?>
                        </div>                  
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'stu_programme_id')->widget(
                                Select2::classname(), [
                                    'data'=>ConfigUtilities::getDegreedetails(),                                    
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_programme_selected',
                                    'value'=>$degree_batch_mapping_id,
                                    'class'=>'form-control student_disable',                                   
                                    
                                ],
                                                               
                            ]); 
                            ?>
                        </div>                  
                     
                
                   <div class="col-xs-12 col-sm-3 col-lg-3">                 
                        
                            <div class="col-xs-12 col-sm-6 col-lg-6"><br />
                                <?= Html::submitButton('Get', ['onClick'=>"spinner();",'name'=>'get_students','class' => 'btn btn-block btn-success' ]) ?>
                                <br />
                            </div> 
                            <div class="col-xs-12 col-sm-6 col-lg-6"><br />
                            <?= Html::a("Reset", Url::toRoute(['student/verify-tc-details']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
                            <br />
                        </div> 
                      </div>  
                    </div>
                </div>


                <?php 
                if(isset($stu_data) && !empty($stu_data))
                {   $head = '';
                    $body = '';
                   ?>   
                    
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-lg-8">
                            &nbsp;
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('student-bio-export-excel'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-block btn-info', 'style'=>'color:#fff'));
                                
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/student/student-bio-export-pdf'], [
                                'class'=>'pull-right btn btn-block btn-primary', 
                                'target'=>'_blank', 
                                'data-toggle'=>'tooltip', 
                                'title'=>'Will open the generated PDF file in a new window'
                                ]);
                            ?>
                        </div>
                    </div>
                  <div class="row">
                    <div class="col-xs-12">
                    <div class="box">
                        
                        <!-- /.box-header -->
                        <div class="box-body">

                        <?php 

                        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                        
                        $head .='<table style="background: #FFF;" class="main_table"  width="100%" >
                                <tr class="table-danger">
                                    <th align="center" colspan="10" ><br /><br /><h3>
                                        Batch:'.$deg['batch_name'].'  &amp; Degree : '.$deg['degree_code'].' '.$deg['programme_name'].' </h3> <br /><br />
                                    </th> 
                                </tr>              
                                <tr>
                                    <th align="center">S No</th>       
                                    <th align="center" >Reg No</th>                        
                                    <th align="center">Student Name</th>
                                    <th align="center">Father Name</th>
                                    <th align="center">Nationality</th>  
                                    <th align="center">Religion</th> 
                                   
                                    <th align="center">DOB</th> 
                                    <th align="center">DOB IN WORDS</th> 
                                    <th align="center">Admission Date</th> 
                                    <th align="center">Signature</th>
                                </tr> 
                                ';
                    $sn = 1;
                    foreach ($stu_data as $key => $value) 
                    {
                        $body .= "<tr>
                            <td valign='middle' align='center' width='70' height='30px'>".$sn."</td>
                            <td valign='middle' align='center' width='125' height='30px'>".$value['register_number']."</td>
                            <td valign='middle'  width='125' height='30px'>".$value['name']."</td>
                            <td valign='middle'   width='125' height='30px'>".$value['guardian_name']."</td>
                            <td valign='middle' align='center' width='125' height='30px'>".$value['nationality']." </td>
                            <td valign='middle' align='center' width='125' height='30px'>".$value['religion']."</td>
                           
                            <td valign='middle' width='170' align='center' height='30px'>".date('d-m-Y',strtotime($value['dob']))."</td>
                            <td valign='middle' width='170' align='center' height='30px'>".date('d-M-Y',strtotime($value['dob']))."</td> 
                            <td valign='middle' width='170' align='center' height='30px'>".date('d-m-Y',strtotime($value['admission_date']))."</td>
                            <td valign='middle'  align='center' width='170' height='30px'>&nbsp;</td>
                            </tr>

                            <tr>
                            <td valign='middle' align='center' width='70' height='30px'>&nbsp;</td>
                            <td valign='middle' width='125' align='center' height='30px'>".$value['register_number']."</td>
                            <td valign='middle'   width='125' height='30px'>&nbsp;</td>
                            <td valign='middle'  width='125' height='30px'>&nbsp;</td>
                            <td valign='middle' align='center' width='125' height='30px'>&nbsp;</td>
                            <td valign='middle'  align='center' width='125' height='30px'>&nbsp;</td>
                            <td valign='middle' align='center' width='125' height='30px'>&nbsp;</td>
                             
                            
                            <td valign='middle'  align='center' width='170' height='30px'>&nbsp;</td> 
                            <td valign='middle' align='center' width='170' height='30px'>&nbsp;</td>
                            <td valign='middle'  align='center' width='170' height='30px'>&nbsp;</td> 
                           
                            </tr>

                            ";
                            $sn++;
                    }   // End the foreach to finish of the student records display 
                    
                    
                    $body .= "</table>";
                    echo $head.$body;
                    if(isset($_SESSION['stu_bio_data']))
                    {
                        unset($_SESSION['stu_bio_data']);
                    }
                    $_SESSION['stu_bio_data'] = $head.$body;

?>

                </div>
            </div>
        </div>
    </div>
</section>
 <?php  } // If not empty of Students Data Display Records to update ?>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
