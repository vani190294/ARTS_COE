<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\dialog\Dialog;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\mpdf\Pdf;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;

$this->registerCssFile("@web/style/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    'media' => 'print',
], 'css-print-theme');



$formatter = \Yii::$app->formatter;
echo Dialog::widget();
$this->title = "Activity Marks Report";
$this->params['breadcrumbs'][] = ['label' =>'Activity Marks Report', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if(isset($model->stu_section_name))
{
    $this->registerJs("$(document).ready(function() { $('.student_disable').attr('disabled',true)});");
}
$batch_id = isset($model->stu_batch_id)?$model->stu_batch_id:"";
$section_name = isset($model->stu_section_name)?$model->stu_section_name:"";
$degree_batch_mapping_id = isset($model->stu_programme_id)?$model->stu_programme_id:"";

?>
<h1><?php echo "Activity Marks Report"; ?></h1>
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
                            <?php echo $form->field($model, 'batch')->widget(
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
                            <?php echo $form->field($model, 'programme')->widget(
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
                            <?= Html::a("Reset", Url::toRoute(['student/student-bio-data']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
                            <br />
                        </div> 
                      </div>  
                    </div>
                </div>


              
                    

              
           <?php ActiveForm::end(); ?>
        
<?php
if(isset($stu_data))
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
            
            //$countStuVal = count($cia_list);
            $stu_print_vals = 0;
            
                foreach ($stu_data as $get_names) 
                {                    
                    $degree_name = $get_names['degree_name'];
                    $prg_name = $get_names['programme_name'];                    
                    break;
                }
              
               $header .='<div class="box-body table-responsive"><table class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                  <tr>';
                $header .='<td colspan=2  ALIGN="CENTER">
                        '.$org_name.'</td>
                  </tr>
                  <tr>';
                    $header .='<td colspan=2 ALIGN="CENTER">
                  '.$org_tagline.'
                    </td>
                  </tr> 
                  <tr>';
                    $header .='<td colspan=2 ALIGN="CENTER">
                        CONSOLIDATED ACTIVITY POINTS 
                    </td>
                  </tr>
                  <tr>';
                    $header .='<td colspan=2 ALIGN="CENTER">
                   '.$degree_name.' - '.$prg_name.' 
                
                   </td>
                  </tr>
                    <tr>
                        <td align="center">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </td>';
                      $header .='<td align="center">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME</td>
                        
                    </tr>';

                  foreach($subjectsInfo as $rows) 
                  { 
                         $header .='<tr>
                             <td align="center">
                                '.$rows["subject_code"].'</td>
                             <td align="left">
                                '.$rows["subject_name"].'</td>

                            
                        </tr>';
                    } 

                     
                       $header .='
                        </table>
                    <table class="table" border="1" cellpadding="1" align="center" cellspacing="1">

                       <tr>   
                          <td align="center">Register Number</td>';
                          foreach($subjectsInfo as $rows) { 
                            $header .='<td align="center">'.$rows["subject_code"].'</td>';
                          }
                          $header .='<td align="center">TOTAL</td>';

                        $header .='</tr>';


                    
                    
                    $prev_num="";

                  
                    foreach($stu_data as $rowsstudent) 
                    { 
                          $total_duration= Yii::$app->db->createCommand('SELECT  sum(A.duration) as total FROM coe_activity_marks as A join coe_add_points as B on B.subject_code=A.subject_code  WHERE register_number="'.$rowsstudent['coe_student_mapping_id'].'"')->queryScalar();
                          //print_r($total_duration);exit;
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
                            $cia="";
                            
                          foreach($stu_data as $stus) 
                          {
                           
                            if($subs['subject_code']==$stus['subject_code'] && $rowsstudent['register_number']==$stus['register_number'])
                                  {
                                     //echo $stus['register_number']." -- ".$rowsstudent['register_number']."<br />";
                                     
                                             $cia=$stus['duration'];
                                        
                                      
                                  }
                            
                          }

                

                  $header .='<td align="center">'.$cia.'</td>';

                    
                    
                            }
                            $header .='<td align="center">'.$total_duration.'</td>'; 
                        }



                      }




                $header .='</table></div>';
                if(isset($_SESSION['cia_mark_list'])){ unset($_SESSION['cia_mark_list']);}
                $_SESSION['cia_mark_list'] = $header;

                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('activity-mark-list-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('excel-activity-mark-list','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
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

<?php 

$this->registerJsFile(
    '@web/style/bower_components/datatables.net/js/jquery.dataTables.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(    
    '@web/style/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);


$this->registerJs(<<<JS
    $(function () {
    $('#student_bulk_edit').DataTable({
      'paging'      : true,
      "dom": '<lf<t>ip>',
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
       'scrollY': '400',
       "scrollX": true,
       'buttons': [
        'copy', 'excel', 'pdf'
    ],
       "responsive": true,
       "pageLength": "60",
       "language" : {
            searchPlaceholder : "Register Number to filter"
        },
    })
  })
  $('#student_bulk_edit').removeClass( 'display' ).addClass('table table-striped table-bordered');
JS
);


?>

<!-- "sPaginationType": "full_numbers",
       'dom': 'Bfrtip',  "dom": '<"top"flp<"clear">>rt<"bottom"i<"clear">>', -->