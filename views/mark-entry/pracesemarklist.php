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
use app\models\Categorytype;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "PRACTICAL ESE MARK LIST OUT OF 100";
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>

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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label("Exam Year") ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('Batch'); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'stu_programme_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label('Programme'); 
                ?>
            </div>
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'name'=>'month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'mark_type')->widget(
                    Select2::classname(), [
                        'data' => ExamTimetable::getExamType(),
                        'options' => [
                            'placeholder' => '-----Select Type ----',
                            'id' => 'exam_type',
                            'class'=>'student_disable',
                            'name'=>'mark_type',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
            
        </div>

        
        
        <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Show Me', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry/pracesemarklist']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>
        
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>






<?php
if(isset($ese_list))
{
     require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    /* 
    *   Already Defined Variables from the above included file
    *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
    *   use these variables for application
    *   use $file_content_available="Yes" for Content Status of the Organisation
    */
    if($file_content_available=="Yes")
        {
            
            $previous_subject_code= "";
            $previous_programme_name= "";
            $previous_reg_number = "";
            $html = "";
            $header = "";
            $body ="";
            $footer = "";
            $print_subjects =1;
            $print_register_number="";
            $new_stu_flag=0;
            $print_stu_data="";
            $countVal = count($subjectsInfo)+1;
           
            //$countStuVal = count($ese_list);
            $stu_print_vals = 0;
            
                $month = $ese_list[0]['month'];
                $degree_name = $ese_list[0]['degree_name'];
                $prg_name = $ese_list[0]['programme_name'];
                $exam_type = $ese_list[0]['exam_type'];
               
               $header .='<table width="100%" class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                  <tr>';
                $header .='<td colspan=2  ALIGN="CENTER">
                        <b>'.$org_name.'</b><br>
                        <span style="font-size:8px !important">
                  '.$org_tagline.'
                    </span></td>
                  </tr>
                  <tr>';
                    $header .='<td colspan=2 ALIGN="CENTER">
                        CONSOLIDATED PRACTICAL ESE 100 MARKS - '.$_POST["mark_year"].' / '.strtoupper($month).' ('.strtoupper($exam_type).')
                    </td>
                  </tr>
                  <tr>';
                    $header .='<td colspan=2 ALIGN="CENTER">
                   '.strtoupper($degree_name).' - '.$prg_name.' 
                
                   </td>
                  </tr>
                    <tr>
                        <td align="center">COURSE CODE </td>';
                        $header .='<td colspan=2 align="center">COURSE NAME</td>
                    </tr>';


                    foreach($subjectsInfo as $rows) 
                    { 
                         $header .='<tr>
                             <td align="center" width=20%>
                                '.$rows["subject_code"].'</td>
                             <td align="left" width=80%>
                                '.strtoupper($rows["subject_name"]).'</td>
                        </tr>';
                    } 

                    $header .='</table>';

                     $body='<table width="100%" class="table" border="1" cellpadding="1" align="center" cellspacing="1">';
                       $body .='<tr>   
                          <td>&nbsp;</td>';
                          foreach($subjectsInfo as $rows) { 
                            $body .='<td align="center">'.$rows["subject_code"].'</td>';
                          } 
                        $body .='</tr>';

                    
                    
                    $prev_num="";

                    foreach($ese_list as $rowsstudent) 
                { 
                         
                         if($prev_num!=$rowsstudent['register_number']) 
                        { 
                            $prev_num=$rowsstudent['register_number'];
                   
                         $body .='</tr>';
                         $body .='<tr>
                         <td align="center">
                            '.$rowsstudent["register_number"].'
                        </td>';
        
                        foreach($subjectsInfo as $subs) 
                        { 
                            $ese="";
                            $ese_1="";
                           
                            
                         foreach($ese_list as $stus) 
                          {
                           
                            if($subs['subject_code']==$stus['subject_code'] && $rowsstudent['register_number']==$stus['register_number'])

                            { 
                                if($stus['ESE_max']==0)
                                {

                                   $ese=0;


                                }
                                else
                                {

                                    
                                   $ese=$stus['ESE'];
                                    

                                    }

                             } 
                            

                     }

                        if($ese=='')
                        {
                            $body .='<td align="center"></td>';
                        }
                        else if($ese<45 && $ese!='-1')
                        {
                            $body .='<td align="center" style="background:yellow; color:red;"><b>'.$ese.'</b></td>';
                        }
                        else if($ese=='-1')
                        {
                            $body .='<td align="center" style="background:yellow; color:red;"><b>ABSENT</b></td>';
                        }
                        else
                        {
                            $body .='<td align="center"><b>'.$ese.'</b></td>';
                        }
                        
                   

                       }

                       
                    }
                    }  


                $body .='</table>';
                if(isset($_SESSION['ese_mark_list1'])){ unset($_SESSION['ese_mark_list1']);}
                $_SESSION['ese_mark_list1'] = $header.$body;

                if(isset($_SESSION['ese_mark_listxl'])){ unset($_SESSION['ese_mark_listxl']);}
                $_SESSION['ese_mark_listxl'] =$body;
                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('prac-ese-mark-list-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('prac-excel-ese-mark-list','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" >'.$header.$body.'</div>
                            </div>
                        </div>
                      </div>'; 
                
               
        }// If no Content found for Institution
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');            
        }
    } 
?>
