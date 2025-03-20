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

$this->title = "ESE MARK LIST  OUT OF 100";
$this->params['breadcrumbs'][] = ['label' => 'ESE MarkList', 'url' => ['create']];
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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year") ?>
            </div>

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
                    'data'=>ConfigUtilities::getDegreedetails(),
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
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
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
                <?php echo $form->field($model,'term')->widget(
                    Select2::classname(), [
                        'data' => ExamTimetable::getExamTerm(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Term ----',
                            'id' => 'exam_term',
                            'class'=>'student_disable',
                            'name'=>'term',
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
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Type ----',
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

                <?= Html::a("Reset", Url::toRoute(['mark-entry/esemarklist1']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
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
            $countVal = count($subjectsInfo);
            
            //$countStuVal = count($ese_list);
            $stu_print_vals = 0;
            
                foreach ($ese_list as $get_names) {
                    $month = $get_names['month'];
                    $degree_name = $get_names['degree_name'];
                    $prg_name = $get_names['programme_name'];
                    $exam_type = $get_names['exam_type'];
                    break;
                }
               
               $header .='<div class="box-body table-responsive"><table class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                  <tr>';
                $header .='<td colspan='.($countVal+1).'  ALIGN="CENTER">
                        '.$org_name.'</td>
                  </tr>
                  <tr>';
                    $header .='<td colspan='.($countVal+1).' ALIGN="CENTER">
                  '.$org_tagline.'
                    </td>
                  </tr> 
                  <tr>';
                    $header .='<td colspan='.($countVal+1).' ALIGN="CENTER">
                        CONSOLIDATED ESE MARKS FOR THE END SEMESTER EXAMINATIONS '.$_POST["mark_year"].' / '.$month.'
                    </td>
                  </tr>
                  <tr>';
                    $header .='<td colspan='.($countVal+1).' ALIGN="CENTER">
                   '.$degree_name.' - '.$prg_name.' 
                
                   </td>
                  </tr>
                    <tr>
                        <td align="center">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </td>';
                        $header .='<td colspan='.($countVal-1).' align="center">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME</td>
                        <td align="center"><b>MAXIMUM</b></td>
                    </tr>';

                  foreach($subjectsInfo as $rows) 
                  { 
                         $header .='<tr>
                             <td align="center">
                                '.$rows["subject_code"].'</td>
                             <td colspan='.($countVal-1).' align="left">
                                '.$rows["subject_name"].'</td>
                             <td align="center">'.$rows["ESE_max"].'</td>
                        </tr>';
                    } 

                       $header .='<tr>   
                          <td>&nbsp;</td>';
                          foreach($subjectsInfo as $rows) { 
                            $header .='<td align="center">'.$rows["subject_code"].'</td>';
                          } 
                        $header .='</tr>';

                    
                    
                    $prev_num="";

                    foreach($ese_list as $rowsstudent) 
                { 
                         
                         if($prev_num!=$rowsstudent['register_number']) 
                        { 
                            $prev_num=$rowsstudent['register_number'];
                   
                         $header .='</tr>';
                         $header .='<tr>
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

                     $header .='<td align="center">'.$ese.'</td>';
                   

                       }

                       
                    }
                    }  


                $header .='</table></div>';
                if(isset($_SESSION['ese_mark_list1'])){ unset($_SESSION['ese_mark_list1']);}
                $_SESSION['ese_mark_list1'] = $header;

                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('ese-mark-list1-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('excel-ese-mark-list1','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" >'.$header.'</div>
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
