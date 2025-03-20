<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\dialog\Dialog;
echo Dialog::widget();

$form = ActiveForm::begin(); ?>
            <section class="content">
                  <div class="row">
                    <div class="col-xs-12">
                    <div class="box">
                <div class="box-body">
                <div class="display_stu_res" style="visibility: hidden;">

                </div>
                <div class="display_stu_res_count" style="visibility: hidden;">

                </div>

                <table style="font-weight: bold;" class="table table-striped table-bordered table-hover" >
                    <?php 
                        $get_data =  "SELECT register_number FROM coe_absent_entry as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.exam_subject_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and A.absent_student_reg=D.coe_student_mapping_id JOIN coe_student as E ON E.coe_student_id=D.student_rel_id WHERE exam_type='".$model->exam_type."' AND absent_term='".$model->absent_term."' AND B.subject_id='".$model->exam_subject_id."' and exam_year='".$_POST['AbsentEntry']['exam_year']."' and exam_month='".$_POST['AbsentEntry']['exam_month']."' ";
                        $ab_get_data = Yii::$app->db->createCommand($get_data)->queryAll();
                        if(!empty($ab_get_data))
                        {
                            echo "<tr style='background: blue; color: #FFF;'><td colspan=5>";
                            foreach ($ab_get_data as $key => $value) 
                            {
                               echo $value['register_number'].", ";
                            }
                            echo "</td></tr>";
                        }
                        
                        $count_foreach = count($send_result); 
                        foreach ($send_result as $key => $values) {
                            if($count_foreach==count($send_result))
                            {
                                ?>
                                <tr>
                                    <td><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME); ?></td>
                                    <td class="highlight"><?php echo $values['degree_name']; ?></td>
                                    <td><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code"; ?></td>
                                    <td class="highlight"><?php echo $values['subject_code']; ?></td>
                                    <td><?php echo "Semester"; ?></td>
                                    <td class="highlight"><?php echo $values['semester']; ?></td>
                                    
                                </tr>
                                <tr>
                                    
                                    <td><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Name"; ?></td>
                                    <td class="highlight"><?php echo $values['subject_name']; ?></td>
                                    <td><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)." Type"; ?></td>
                                    <td class="highlight"><?php echo $values['description']; ?></td> 
                                    <td><?php echo "Actions"; ?></td>                       
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Actions to be Perform">

                                          <?= Html::submitButton('Update', ['class' => 'btn btn-group-lg btn-group btn-success','name'=>'update','data-confirm' => 'Are you sure you want to Update this records <br /> This can not be Un-Done once the values were changed Until you <b>CONTACT THE SUPPORT TEAM?</b> Please re-check your Submission and Click <b>OK</b> to proceed.']) ?>
                                          <!--<?= Html::a("Reset", Url::toRoute(['exam-timetable/absent']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>-->
                                        </div>

                                    </td>
                                </tr>                               
                               
                                <?php
                                $count_foreach++;
                            }
                            else
                            {
                                break;
                            }
                        }
                    ?>
                   
                </table> <br />
                
                <table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="practical_edit" class="table table-bordered table-responsive bulk_edit_table table-hover"  >
                <thead class="thead-inverse">
                    <tr class="table-danger">
                        <th>Sno</th>
                        <th>Reg No</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>               
                    </thead> 
                    <tbody>       
                    <?php   $i=1; 
                    
                    foreach ($send_result as $key => $value) 
                    {   
                            $stu_id = $value['coe_student_mapping_id'];
                            $reg_num = $value['register_number'];
                            $form_name = "ab[$stu_id]";  
                            $check_data = "SELECT * FROM coe_absent_entry as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.exam_subject_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id WHERE absent_student_reg='".$stu_id."' AND exam_type='".$model->exam_type."' AND absent_term='".$model->absent_term."' AND B.subject_id='".$model->exam_subject_id."' and exam_year='".$_POST['AbsentEntry']['exam_year']."' and exam_month='".$_POST['AbsentEntry']['exam_month']."' ";

                            $available_data = Yii::$app->db->createCommand($check_data)->queryAll();
                            $status = count($available_data)>0?"checked=true":"";
                            $ab_status = $status==""?"Present":"<b style='color: #f00;' >".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)."</b>";
                        ?>
                        <tr>
                            <td valign="top"><?php echo $i; ?></td>
                            <td valign="top"><input type="hidden" name="reg_nu_sem_ab_<?php echo $stu_id;?>" id="reg_nu_sem_ab_<?php echo $stu_id;?>" value="<?php echo $reg_num; ?>" ><?php echo $reg_num; ?></td>
                            <td valign="top"><?php echo $value['name']; ?></td>
                            
                            <td>
                                <label class="control-label" for="absent-name_<?php echo $stu_id;?>"  >
                                    <?php echo $ab_status; ?>
                                </label>
                                
                            </td>
                            <td>
                                <input onclick="changeLable(this.id)"  type="checkbox" name="<?php echo $form_name; ?>" id="<?php echo $form_name; ?>" <?php echo $status; ?> >
                            </td>
                        </tr>
                    <?php $i++; 
                    }   // End the foreach to finish of the student records display  ?>
                    
                </tbody>
               
                    </table>
                    <input type="hidden" name="exam_month"  value="<?php echo $model->exam_month; ?>" />
                    <input type="hidden" name="totalCount"  value="<?php echo $i-1; ?>" />
                    <input type="hidden" name="exam_type"  value="<?php echo $model->exam_type; ?>" />
                    <input type="hidden" name="absent_term"  value="<?php echo $model->absent_term;  ?>" />
                    <input type="hidden" name="exam_subject_id"  value="<?php echo $model->exam_subject_id;  ?>" />
                    <input type="hidden" name="course_batch_id"  value="<?php echo $_POST['AbsentEntry']['course_batch_id'];  ?>" />
                    <input type="hidden" name="exam_month"  value="<?php echo $_POST['AbsentEntry']['exam_month'] ?>" />
                    <input type="hidden" name="entry_type"  value="Practical" />
                    <input type="hidden" name="exam_year"  value="<?php echo $_POST['AbsentEntry']['exam_year'] ?>" />
                    <input type="hidden" name="exam_semester_id"  value="<?php echo $_POST['AbsentEntry']['exam_semester_id'] ?>" />

                    <input type="hidden" name="exam_absent_status"  value="1" />
                   
                </div>
            </div>
        </div>
    </div>
</section>
 <?php ActiveForm::end(); ?>

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
    $('#practical_edit').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false,
       'scrollY': '400',
       "scrollX": true,
       "responsive": "true",
       "pageLength": "200",
       language: {
            searchPlaceholder: "Register Number to filter"
        }
       
    })
  })
JS
);


?>