<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use app\models\HallAllocate;
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

$this->title="NAD  REPORT";

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
        ]); 
    ?>

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
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'batch_year','name'=>'withdraw_year']) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'consolidate_month',   
                            'name' => 'consolidate_month',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <br />
       <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Show Me', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['batch/reval-grade-report']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>
   


    <div class="col-xs-12 col-sm-12 col-lg-12 reval_batch_report">
        
        <div id = "reval_batch_report_ex" >                
           
        </div>
      
    </div>

<?php ActiveForm::end(); ?>

</div>
</div>
</div>


<?php
if(isset($nad))
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
            
            //$countStuVal = count($coursewisearrear);
            $stu_print_vals = 0;
                
              $header .= '<table border=1  width="100%" class="table table-striped table-responsive table-hover table-bordered"  align="center">';
                    $header .= '<tr>
                    <td style="border: none;" colspan=8>
                    <table width="100%" align="center" border="0">                    
                    <tr>
                     </tr>';
                    $header .="
                    <tr height=40px>
                    
                      <th align='center'>ORG_NAME</th>
                      <th align='center'>ACADEMIC_COURSE_ID</th>
                      <th align='center'>COURSE_NAME</th>
                      <th align='center'>STREAM</th>
                      <th align='center'>SESSION</th>
                      <th align='center'>REGN_NO</th>
                      <th align='center'>RROLL</th>
                       <th align='center'>CNAME</th>
                       <th align='center'>GENDER</th>
                       <th align='center'>DOB</th>
                       <th align='center'>FNAME</th>
                       <th align='center'>MNAME</th>
                       <th align='center'>PHOTO</th>
                       <th align='center'>MRKS_REC_STATUS</th>
                       <th align='center'>RESULT</th>
                       <th align='center'>YEAR</th>
                       <th align='center'>MONTH</th>
                       <th align='center'>PERCENT</th>
                       <th align='center'>DOI</th>
                       <th align='center'>CERT_NO</th>
                       <th align='center'>SEM</th>
                       <th align='center'>EXAM_TYPE</th>
                       <th align='center'>TOT</th>
                       <th align='center'>TOT_MIN</th>
                        <th align='center'>TOT_MRKS</th>
                        <th align='center'>TOT_ESE_MAX</th>
                        <th align='center'>TOT_ESE_MIN</th>
                        <th align='center'>TOT_ESE_MRKS</th>
                        <th align='center'>TOT_INT_MAX</th>
                        <th align='center'>TOT_INT_MIN</th>
                        <th align='center'>TOT_INT_MRKS</th>
                        <th align='center'>TOT_CE_MAX</th>
                        <th align='center'>TOT_CE_MIN</th>
                        <th align='center'>TOT_CE_MRKS</th>
                        <th align='center'>TOT_VV_MAX</th>
                        <th align='center'>TOT_VV_MIN</th>
                        <th align='center'>TOT_VV_MRKS</th>
                         <th align='center'>TOT_CREDIT</th>
                         <th align='center'>TOT_CREDIT_POINTS</th>
                        <th align='center'>TOT_GRADE_POINTS</th>
                        <th align='center'>PREV_TOT_MRKS</th>
                        <th align='center'>GRAND_TOT_MAX</th>
                        <th align='center'>GRAND_TOT_MIN</th>
                        <th align='center'>GRAND_TOT_MRKS</th>
                        <th align='center'>GRAND_TOT_CREDIT</th>
                        <th align='center'>CGPA</th>
                        <th align='center'>REMARKS</th>
                        <th align='center'>SGPA</th>
                        <th align='center'>ABC_ACCOUNT_ID</th>
                        <th align='center'>TERM_TYPE</th>
                        <th align='center'>TOT_GRADE</th>";

                        

                  foreach ($nad as $value) 
                  {
                      
                  
                   

                         $total_subs_max_check = Yii::$app->db->createCommand("select   distinct count(B.subject_map_id) as max from coe_subjects_mapping as A JOIN coe_mark_entry_master as B ON B.subject_map_id=A.coe_subjects_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=A.subject_id where batch_mapping_id='".$value['batch_mapping_id']."' and B.year='".$value["year"]."' and B.month='".$value["month"]."' group by B.student_map_id")->queryAll();

                         $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($total_subs_max_check));

            foreach($it as $v) 
            {
                    $a[] = $v;
            }
            $total_subs_max = max($a);
        }
            //print_r($total_subs_max);exit;
            //print_r($total_subs_max);;exit;
        
               
                
               
              
              
               
               
                
               
               
               
                
               
            


             for ($s=1; $s <= $total_subs_max; $s++) 
            { 
                   $header .='<th>SUB'.$s.'NM</th>
                               <th>SUB'.$s.'</th>
                               <th>SUB'.$s.'MAX</th>
                               <th>SUB'.$s.'MIN</th>
                               <th>SUB'.$s.'_TH_MAX</th> 
                               <th>SUB'.$s.'_VV_MRKS</th>
                               <th>SUB'.$s.'_PR_CE_MRKS</th>
                               <th>SUB'.$s.'_TH_MIN </th>
                               <th>SUB'.$s.'_PR_MAX </th>
                                <th>SUB'.$s.'_PR_MIN </th>
                                <th>SUB'.$s.'_CE_MAX </th>
                                <th>SUB'.$s.'_CE_MIN </th>
                                <th>SUB'.$s.'_TH_MRKS </th>
                                <th>SUB'.$s.'_PR_MRKS </th>
                                 <th>SUB'.$s.'_CE_MRKS </th>
                                <th>SUB'.$s.'_TOT </th>
                                <th>SUB'.$s.'_GRADE </th>
                                <th>SUB'.$s.'_GRADE_POINTS </th>
                                <th>SUB'.$s.'_CREDIT </th>
                                <th>SUB'.$s.'_CREDIT_POINTS </th>
                                <th>SUB'.$s.'_REMARKS </th>
                                 <th>SUB'.$s.'_VV_MIN </th>
                                  <th>SUB'.$s.'_VV_MAX </th>
                                <th>SUB'.$s.'_TH_CE_MRKS</th>
                                 <th>SUB'.$s.'_CREDIT_ELIGIBILITY</th>







                        </th>';
            }
                '</tr>';                   // print_r($total_subs_max_check);exit;
                       
                    

                    
                    
                    $i=1;
                    $footer .='</table>';
                  foreach($nad as $rows) 
                  { 
                    
                    if($i%45==0 && $i!=1)
                    {
                        $html = $header .$body.$footer; 
                        $print_stu_data .= $html;
                        $html = "";
                        $body ="";
                        $i=1;
                    }
                     
           
                    $sem_verify = ConfigUtilities::SemCaluclation($rows['year'],$rows['month'],$rows['course_batch_mapping_id']);
                     $cgpa_calc = ConfigUtilities::getCgpaCaluclation($rows['year'],$rows['month'],$rows['course_batch_mapping_id'],$rows['student_map_id'],$sem_verify);
                    $_SESSION['get_excel_cgpa']=$cgpa_calc;
                    //print_r( $cgpa_calc);exit;

                      $stusublist = Yii::$app->db->createCommand("select C.subject_name,C.subject_code,B.grade_name,B.grade_point,C.credit_points,B.result,B.subject_map_id,C.CIA_max,C.ESE_max from coe_subjects_mapping as A JOIN coe_mark_entry_master as B ON B.subject_map_id=A.coe_subjects_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=A.subject_id where batch_mapping_id='".$rows['batch_mapping_id']."' and B.year='".$rows["year"]."' and B.month='".$rows["month"]."' and B.student_map_id='".$rows['student_map_id']."' and semester='".$rows['semester']."' ")->queryAll();



                     if($rows['month_name']=="Oct/Nov")
                      {



                 // /$month="Nov/Dec";
                        $month="NOV";
                      }
                      else
                      {

                    //$month=$rows["month_name"];
                        $month="APRIL";


                         }


                          $get_total = 'SELECT  sum(A.grade_point*D.credit_points) as credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$rows['year'].'"  and month="'.$rows['month'].'"  and student_map_id="'.$rows['student_map_id'].'"';
                         
                        $totalcredits = Yii::$app->db->createCommand($get_total)->queryScalar();
                        $date=date_create($rows['dob']);
                       $dob= date_format($date,"d-m-Y ");
                      //print_r($dob);exit;
                        $sem = ConfigUtilities::getSemesterRoman($rows['semester']);  
                      

                        $body .='<tr height=40px>
                                        
                                        <td align="center">'.$org_name.'</td>
                                        <td align="center">'.$rows["programme_code"].'</td>
                                       <td align="center">'.$rows["degree_code"].'-'.$rows["programme_name"].'</td>
                                        <td align="center">CHOICE BASED CREDIT SYSTEM</td>
                                        <td align="center"></td>
                                        <td align="center">BATCH 2021-2025 (Regulations 2021)</td>
                                        <td align="center">'.$rows["register_number"].'</td>
                                         <td align="center">'.$rows["register_number"].'</td>
                                          <td align="center">'.$rows["name"].'</td>
                                          <td align="center">'.$rows["gender"].'</td>
                                           <td align="center">'.$dob.'</td>
                                           <td align="center"></td>
                                           <td align="center">O</td>
                                           <td align="center">'.$rows["year"].'</td>
                                            <td align="center">'.$month.'</td>
                                            <td align="center">'.$sem.'</td>
                                            <td align="center">'.$cgpa_calc["final_cgpa"].'</td>
                                            <td align="center">'.$cgpa_calc["part_3_gpa"].'</td>
                                            <td align="center">'.$cgpa_calc["part_3_earned"].'</td>
                                            <td align="center"'.$rows["abc_number_id"].'></td>
                                            <td align="center">'.$totalcredits.'</td>';

                                           
                        foreach ($stusublist as  $value1) 
                           {
                             
                             //print_r($stusublist);exit;
                              $get_cgpa_grades = 'SELECT A.grade_point*D.credit_points as totalcredits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D





                              .coe_subjects_id=B.subject_id WHERE year="'.$rows['year'].'"  and month="'.$rows['month'].'"  and student_map_id="'.$rows['student_map_id'].'" and subject_map_id="'.$value1['subject_map_id'].'"';
                               $credits = Yii::$app->db->createCommand($get_cgpa_grades)->queryScalar();

                               if($value1['grade_name'] =='S')
                               {

                               $grade_name=0;
                               $result=0;
                               }

                               else
                               {

                       $grade_name=$value1["grade_name"];
                         $result=$value1["result"];
                               }

                               if($value1['grade_name']=='S' && $value1['result']=="Pass")
                               {
                                  $grade_point="Completed";

                               }

                               elseif ($value1['grade_name']=='S' && $value1['result']=="Fail") 
                               {
                                   $grade_point="Not Completed";
                               }

                               else
                               {
                                  $grade_point=$value1["grade_point"];

                               }




                        

                             $body .='
                                         <td align="center">'.$value1["subject_name"].'</td>
                                            <td align="center">'.$value1["subject_code"].'</td>
                                            
                                            <td align="center">'.$grade_name.'</td>
                                            <td align="center">'.$grade_point.'</td>
                                             <td align="center">'.$value1["credit_points"].'</td>
                                             <td align="center">'.$credits.'</td>
                                             <td align="center">'.$result.'</td>
                                             ';

                                          }
                         
                        $i++;

                    }
                     $body .='</tr>';


                

                
                $html = $header .$body.$footer; 
                $print_stu_data .= $html;

                if(isset($_SESSION['nad'])){ unset($_SESSION['nad']);}
                $_SESSION['nad'] = $print_stu_data;
                $_SESSION['get_excel_query']= $nad;
                   $_SESSION['get_excel_cgpa']=$cgpa_calc;
                     $_SESSION['get_excel_total']=$totalcredits;
                      $_SESSION['get_excel_subsmax']=$total_subs_max;
                       $_SESSION['get_excel_stusublist']=$stusublist;
                        $_SESSION['sem']=$sem_verify;
                     // Unset($_SESSION['get_excel_cgpa']);

                


                 


                // $_SESSION['org'] = $org_name;
                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('nad-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('nad-web-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" style="overflow-x: auto; overflow-y: auto; height: 500px !important;">'.$print_stu_data.'</div>
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
