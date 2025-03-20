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
use app\models\MarkEntryMaster;
use yii\db\Query;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Batch Wise Analysis";
$this->params['breadcrumbs'][] = ['label' => "Batch Wise Analysis", 'url' => ['create']];
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
            
            <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => ConfigUtilities::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
            </div>   
        <br />  
            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Download', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry/regular-count-overall']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>
        
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>


<?php
if(isset($total_appearred))
{
     require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    /* 
    *   Already Defined Variables from the above included file
    *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
    *   use these variables for application
    *   use $file_content_available="Yes" for Content Status of the Organisation
    */

    $month=$_POST['MarkEntry']['month'];
            $y=$_POST['mark_year'];
    if($file_content_available=="Yes")
        {
            if(isset($_SESSION['regular-count-overall'])){ unset($_SESSION['regular-count-overall']);}
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
            $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
            
            //$countStuVal = count($studentwiseregular);
            $stu_print_vals = 0;
                $month = Categorytype::findOne($_POST['MarkEntry']['month']);
               $header .="<table border=1 align='center' class='table table-striped '>";
                    $header .= '<tr>
                    <td style="border: none;" colspan=9>
                    <table width="100%" align="center" border="0">                    
                   
                    <tr>
                        <td colspan=10><center> <font size="3px"> Branch Result Anlysis</font> <b> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)).' YEAR '.$_POST['mark_year'].' AND '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)).' MONTH '.$month['description'].'</b> </center></td>
                    </tr>
                    <tr>
                        <td colspan=10><center> <font size="3px">'.$org_address.'</font> Phone : <b>'.$org_phone.'</b> </center></td>
                    </tr>
                     <tr>
                        <td colspan=10><center class="tag_line"><b>'.$org_tagline.'</b></center></td>
                    </tr>
                    </table></td></tr>';
                    $header .="
                    <tr>
                      <th align='center'>SNO</th>
                     
                       <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH))." </th>
                        <th align='center'>Degree Code </th>
                       <th align='center'>programme Code </th>
                       
                      
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." CODE -".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." NAME</th>
                        
                    
                      <th align='center'>No.of Candidates Appeared[ Current Semester Only] </th>
                      <th align='center'>No. of Candidates all Cleared[ Current Semester Only]</th>
                      <th align='center'>Pass Percentage   [Subject Wise][ Current Semester Only]</th>
                     
                      <th align='center'>Pass Percentage  [Branch wise][ Current Semester Only]</th>
                      <th align='center'>Overall Pass Percentage[ Current Semester Only]</th>
                    </tr>";
                    $i=1;
                   $footer .='</table>';
                  $temp_prog=''; $Overall_percentcount=1;
                  foreach($total_appearred as $rows) 
                  { 


                     $getAppeared = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) as appeared FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" ')->queryScalar();




                     $getPassCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id)  as pass FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result like "%pass%" and  A.student_map_id NOT IN (select student_map_id FROM coe_mark_entry_master where  year="'.$rows["year"].'" and month ="'.$rows["month"].'" and mark_type=27  and result  in ("fail","absent"))')->queryScalar();

        $pass_percent_subject = round((( $getPassCount/$getAppeared)*100),2);


 /*foreach ($programme as $key => $value) 

 {

  $getPassCountbranch = Yii::$app->db->createCommand('select count(DISTINCT student_map_id)  as pass FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  join coe_bat_deg_reg as f on f.coe_bat_deg_reg_id=B.batch_mapping_id  where  A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27  and  f.coe_programme_id="'.$value['coe_programme_id'].'" and  A.result like "%pass%"')->queryScalar();


  $getAppearedbranch = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) as appeared FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  JOIN 
          coe_bat_deg_reg as f on f.coe_bat_deg_reg_id=B.batch_mapping_id
       where  A.year ="'.$rows["year"].'" and f.coe_programme_id="'.$value['coe_programme_id'].'" and A.month ="'.$rows["month"].'"')->queryScalar();




            $pass_percent_branch = ($getPassCountbranch/$getAppearedbranch *100);


            $gpa_result_send = empty( $pass_percent_branch )?"-":round( $pass_percent_branch ,2);
*/
 
                $getAppearedwhole = Yii::$app->db->createCommand('select count(DISTINCT student_map_id)  as pass FROM coe_mark_entry_master as A JOIN coe_student_mapping S on S.coe_student_mapping_id=A.student_map_id JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  join coe_bat_deg_reg as f on f.coe_bat_deg_reg_id=B.batch_mapping_id  join coe_batch as CB on CB.coe_batch_id=f.coe_batch_id where  A.year="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27  and f.coe_programme_id!=15  and CB.batch_name!=2021 ')->queryScalar();
 

            

                /*  $getAppearedwhole = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) as appeared FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  join  
                  coe_bat_deg_reg as f on f.coe_bat_deg_reg_id=B.batch_mapping_id   
                  where  A.year ="'.$rows["year"].'"and A.month ="'.$rows["month"].'" and A.mark_type=27 and  f.coe_programme_id!=15  and  B.semester="'.$rows["semester"].'"')->queryScalar();*/

                $getPassCountwhole = Yii::$app->db->createCommand('select count(DISTINCT student_map_id)  as pass FROM coe_mark_entry_master as A JOIN coe_student_mapping S on S.coe_student_mapping_id=A.student_map_id JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  join coe_bat_deg_reg as f on f.coe_bat_deg_reg_id=B.batch_mapping_id  join coe_batch as CB on CB.coe_batch_id=f.coe_batch_id where  A.year="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27  and f.coe_programme_id!=15  and CB.batch_name!=2021 and A.result like "%pass%" and A.student_map_id NOT IN (select student_map_id FROM coe_mark_entry_master where  year="'.$rows["year"].'" and month ="'.$rows["month"].'" and mark_type=27  and result  in ("fail","absent"))')->queryScalar();


             /* $getPassCountwhole = Yii::$app->db->createCommand('select count(DISTINCT student_map_id)  as pass FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id join  
                  coe_bat_deg_reg as f on f.coe_bat_deg_reg_id=B.batch_mapping_id  where  A.year ="'.$rows["year"].'"  and A.month ="'.$rows["month"].'" and A.mark_type=27  and f.coe_programme_id!=15 and  A.result like "%pass%" and  B.semester="'.$rows["semester"].'" and ' )->queryScalar();*/


             $pass_percent_whole = round((( $getPassCountwhole/$getAppearedwhole)*100),2);

            

           // print_r( $getApapearedwhole);exit;



           //print_r(  $pass_percent_subject );exit;



                    //print_r( $getAppeared);exit;

               //$getPassCountbranch = Yii::$app->db->createCommand('select count(DISTINCT student_map_id)  as pass FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  join coe_bat_deg_reg as f on f.coe_bat_deg_reg_id=B.batch_mapping_id  where  A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27  and  f.coe_programme_id="'.$rows['coe_programme_id'].'" and  A.result like "%pass%" and  B.semester="'.$rows["semester"].'"')->queryScalar();

             $getPassCountbranch = Yii::$app->db->createCommand('select count(DISTINCT student_map_id)  as pass FROM coe_mark_entry_master as A JOIN coe_student_mapping S on S.coe_student_mapping_id=A.student_map_id JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  join coe_bat_deg_reg as f on f.coe_bat_deg_reg_id=B.batch_mapping_id  join coe_batch as CB on CB.coe_batch_id=f.coe_batch_id where  A.year="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27  and  f.coe_programme_id="'.$rows["coe_programme_id"].'" and  A.result like "%pass%" and CB.batch_name!=2021 and A.student_map_id NOT IN (select student_map_id FROM coe_mark_entry_master M JOIN coe_subjects_mapping as N ON N.coe_subjects_mapping_id=M.subject_map_id  join coe_bat_deg_reg as V on V.coe_bat_deg_reg_id=N.batch_mapping_id where M.year="'.$rows["year"].'" and M.month ="'.$rows["month"].'" and M.mark_type=27  and M.result like "%fail%" and V.coe_programme_id="'.$rows["coe_programme_id"].'" ) ')->queryScalar();


      $getAppearedbranch = Yii::$app->db->createCommand('select count(DISTINCT student_map_id)  as pass FROM coe_mark_entry_master as A JOIN coe_student_mapping S on S.coe_student_mapping_id=A.student_map_id JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  join coe_bat_deg_reg as f on f.coe_bat_deg_reg_id=B.batch_mapping_id  join coe_batch as CB on CB.coe_batch_id=f.coe_batch_id where  A.year="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27  and  f.coe_programme_id="'.$rows["coe_programme_id"].'" and CB.batch_name!=2021 ')->queryScalar();






            $pass_percent_branch = round((( $getPassCountbranch/$getAppearedbranch)*100),2);
                         
                         $body .='<tr>
                                <td align="center">'.$i.'</td>
                               
                                <td align="center">'.$rows["batch_name"].'</td>
                                <td align="center">'.$rows["degree_code"].'</td>
                                <td align="center">'.$rows["programme_name"].'</td>
                                <td align="center">'.$rows["subject_code"].'-'.$rows["subject_name"].'</td>
                                  <td align="center">'.$getAppeared.'</td>
                                  <td align="center">'.$getPassCount.'</td>
                                   <td align="center">'.  $pass_percent_subject.'</td>';
                                if($temp_prog!=$rows["programme_name"])    
                              {
                                 $body .= '
                                     <td align="center">'.  $pass_percent_branch.'</td>';
                                }
                                else
                                {
                                   $body .= '
                                     <td align="center"></td>';
                                }
                                    
                             $i++; 
                   
                   
                 $temp_prog=$rows["programme_name"];            
                $Overall_percentcount=$Overall_percentcount+1;
                }
                 $body .='<td align="center" rowspan="'.$Overall_percentcount.'">'.$pass_percent_whole.'</td>;
                                  
                                    
                                
                            </tr>';
         
                
                $html = $header .$body.$footer; 
                $print_stu_data .= $html;

                
                $_SESSION['regular-count-overall'] = $print_stu_data;

                
              echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('batch-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('batch-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" >'.$print_stu_data.'</div>
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
