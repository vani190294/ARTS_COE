<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT).' Generation';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 




<div style="padding-left: 1%;" class="student-view">

    <h1><?= Html::encode($this->title) ?></h1>

</div>

<section class="content reports_contente">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-body">
          <div class="box-group" id="accordion"> 

            <div class="panel  box box-solid box-primary"> 

              <div class="box-header  with-border" role="tab" >
                <div class="row">
                  <div class="col-md-10">
                    <h4 class="padding box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        <?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT); ?>
                      </a>                              
                    </h4>
                  </div>                        
                </div> 
              </div>
              <div id="collapseOne" class="panel-collapse collapse in">
                <div class="box-body">
                  <div class="col-md-2">
                        <?= Html::a("Application Print", Url::toRoute(['student/student-exam-application']), ['class' => 'btn btn-block btn-success ']) ?>
                      
                  </div>  
                  <div class="col-md-2">
                        <?= Html::a("Bio Data", Url::toRoute(['student/student-bio-data']), ['class' => 'btn btn-block btn-success ']) ?>
                      
                  </div> 
                  <div class="col-md-2">
                        <?= Html::a("OnlineNominalExport", Url::toRoute(['nominal/onlinenominal']), ['class' => 'btn btn-block btn-success ']) ?>
                      
                  </div>
                   <div class="col-md-2">
                        <?= Html::a("Verify DOB", Url::toRoute(['student/dob-verify']), ['class' => 'btn btn-block btn-success ']) ?>
                      
                  </div>
                  <div class="col-md-2">
                        <?= Html::a("Verify TC", Url::toRoute(['student/verify-tc-details']), ['class' => 'btn btn-block btn-success ']) ?>
                      
                  </div>
                  <div class="col-md-2">
                        <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Status Info ", Url::toRoute(['student/stu-status-list']), ['class' => 'btn btn-block btn-success ']) ?>
                      
                  </div>              
                </div>
              </div>
             
            </div>

            <div class="panel box box-solid box-primary"> 

              <div class="box-header with-border">
                <div class="row">
                  <div class="col-md-10">
                    <h4 class="padding box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        <?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM); ?>
                      </a>                            
                    </h4>
                  </div>                       
                </div>
              </div>

              <div id="collapseTwo" class="panel-collapse collapse">
                <div class="box-body">
                
                  <div class="col-md-2">
                        <?= Html::a("External Score Card", Url::toRoute(['exam-timetable/external-format']), ['class' => 'btn btn-block btn-success']) ?>
                      
                  </div>                  
                </div>
              </div>
             
            </div>

            <div class="panel box box-solid box-primary"> 

              <div class="box-header with-border">
                <div class="row">
                  <div class="col-md-10">
                    <h4 class="padding box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                        <?php echo "Galley"; ?>
                      </a>                            
                    </h4>
                  </div>                       
                </div>
              </div>
              
              <div id="collapseThree" class="panel-collapse collapse">


                <div class="box box-info active box-solid">   

                  <div class="box-header col-md-3 with-border">
                    <h6 class="box-title"><?php echo "Main ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT); ?></h6>    
                  </div>
                  <div style="clear: both"></div>
                  <div class="box-body">
                    <div class="col-md-2">
                       <?= Html::a("Galley Arrangement", Url::toRoute(['hall-allocate/galley-arrangement-reports']), ['class' => 'btn btn-block btn-success']) ?>
                    </div>
                    <div class="col-md-2">
                       <?= Html::a("Attendence Sheet", Url::toRoute(['hall-allocate/attendance-sheet']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>
                    <div class="col-md-2">
                       <?= Html::a("OnlineAttendenceSheet", Url::toRoute(['hall-allocate/online-attendace-sheet']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>
                    <div class="col-md-2">
                       <?= Html::a("GoogleAttendenceSheet", Url::toRoute(['hall-allocate/google-sheet']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>
                   
                    <div class="col-md-2">
                      <?= Html::a("Hall Ticket Internet Copy", Url::toRoute(['mark-entry/hallticketexport']), ['class' => 'btn btn-block btn-success']) ?>
                    </div>
                    <div class="col-md-2">
                      <?= Html::a("QP Distribution", Url::toRoute(['hall-allocate/qpdistribution']), ['class' => 'btn btn-block btn-success']) ?>
                    </div>
                    
                    <div class="col-md-2">
                       <?= Html::a("Hall Tickets!!", Url::toRoute(['hall-allocate/hall-ticket']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>

                  </div>
                </div>
                   <br /> 

                <div class="box box-info active box-solid"> 
                               
                  <div class="box-header col-md-3 with-border">
                    <h5 class="box-title"><?php echo "Analysis ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT); ?></h5>    
                  </div>
                  <div style="clear: both"></div>
                  <div class="box-body"> 
                    <div class="col-md-2">
                       <?= Html::a("Hall Vs " .ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), Url::toRoute(['hall-allocate/hallvsstudent']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div> 
                    <div class="col-md-2">
                       <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." WISE REPORTS", Url::toRoute(['hall-allocate/programmeexamreports']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>     
                    <div class="col-md-2">
                       <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Wise", Url::toRoute(['hall-allocate/subjectwisereports']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>
                    <div class="col-md-2">
                       <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Date Wise", Url::toRoute(['hall-allocate/datewisereports']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>                                 
                  
                    <div class="col-md-2">
                       <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT) ." List", Url::toRoute(['hall-allocate/hallvs-absent-student']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div> 
                    <div class="col-md-2">
                       <?= Html::a("Datewise ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT), Url::toRoute(['exam-timetable/consolidate-absent-list']), ['class' => 'btn btn-block btn-warning']) ?> 
                    </div>
                    <br /><br />
                    <div class="col-md-2">
                       <?= Html::a("Datewise Answer Packets!!", Url::toRoute(['hall-allocate/answer-packets']), ['class' => 'btn btn-block btn-warning']) ?> 
                    </div>
                    
                    <div class="col-md-2">
                       <?= Html::a("Print Register Numbers", Url::toRoute(['hall-allocate/print-register-numbers']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>         
                  </div>
                </div>
                
             
            </div>
          </div>

            <div class="panel box box-solid box-primary"> 

              <div class="box-header with-border">
                <div class="row">
                  <div class="col-md-10">
                    <h4 class="padding box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                        <?php echo "Marks"; ?>
                      </a>                            
                    </h4>
                  </div>                       
                </div>
              </div>
              
              <div id="collapseFour" class="panel-collapse collapse">

                <div class="box box-info active box-solid">                
                  <div class="box-header col-md-3 with-border">
                    <h5 class="box-title"><?php echo "Main ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT); ?></h5>    
                  </div>

                  <div style="clear: both"></div>

                  <div class="box-body">
                    <div class="col-md-2">
                       <?= Html::a("OMR EXPORT", Url::toRoute(['mark-entry-master/omr-export']), ['class' => 'btn btn-block btn-success']) ?>
                    </div>
                    <div class="col-md-2">
                       <?= Html::a("Notice Board Marks", Url::toRoute(['mark-entry-master/noticeboard']), ['class' => 'btn btn-block btn-success']) ?>
                    </div>
                    <div class="col-md-2">
                       <?= Html::a("My Class Room Report ", Url::toRoute(['mark-entry/myclassroom']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>
                    <div class="col-md-2">
                       <?= Html::a("University ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT), Url::toRoute(['mark-entry/universityreport']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>
                    
                    <div class="col-md-2">
                      <?= Html::a("Mark Statement", Url::toRoute(['mark-entry/mark-statement']), ['class' => 'btn btn-block btn-info']) ?>
                    </div>
                    
                    <div class="col-md-2">
                      <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Mark View", Url::toRoute(['mark-entry/studentmark-view']), ['class' => 'btn btn-block btn-warning']) ?>
                    </div>
                    <br /><br />
                    <div class="col-md-2">
                      <?= Html::a("Final Internet Copy", Url::toRoute(['mark-entry/internet-copy']), ['class' => 'btn btn-block btn-warning']) ?>
                    </div>
                   
                      <div class="col-md-2">
                       <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)."s Internet!!", Url::toRoute(['mark-entry-master/subjectinformation']), ['class' => 'btn btn-block btn-success']) ?> 
                      </div>
                      
                    <div class="col-md-2">
                        <?= Html::a("Withheld Reports", Url::toRoute(['mark-entry-master/withheld-reports']), ['class' => 'btn btn-block btn-success ']) ?>
                      
                    </div> <br /><br />
                  <div class="col-md-2">
                        <?= Html::a("Moderation Reports", Url::toRoute(['mark-entry-master/moderation-reports']), ['class' => 'btn btn-block btn-success ']) ?>
                      
                  </div> 
                  <div class="col-md-2">
                        <?= Html::a("Revaluation Reports", Url::toRoute(['mark-entry-master/revaluation-reports']), ['class' => 'btn btn-block btn-success ']) ?>
                      
                  </div>
                  <div class="col-md-2">
                        <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)." Reports", Url::toRoute(['mark-entry-master/absent-reports']), ['class' => 'btn btn-block btn-success ']) ?>
                  </div> 
                 <div class="col-md-2">
                        <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Arrear Count", Url::toRoute(['mark-entry-master/student-arrear-export']), ['class' => 'btn btn-block btn-success ']) ?>
                  </div>
                  <div class="col-md-2">
                        <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Count Reports", Url::toRoute(['subjects-mapping/subject-count-report']), ['class' => 'btn btn-block btn-success ']) ?>
                  </div>
                  <div class="col-md-2">
                        <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Count Reports", Url::toRoute(['subjects-mapping/student-count-report']), ['class' => 'btn btn-block btn-success ']) ?>
                  </div> 
                  
                  <br /> <br />
                  <div class="col-md-2">
                      <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Arrear Count", Url::toRoute(['mark-entry/arrearreport']), ['class' => 'btn btn-block btn-success']) ?>
                    </div>
                    <div class="col-md-3">
                      <?= Html::a("ADDITIONAL  CREDITS REPORTS", Url::toRoute(['mark-entry/sub-add']), ['class' => 'btn btn-block btn-primary']) ?>
                    </div>
                    <div class="col-md-3">
                      <?= Html::a("FULL ADDITIONAL  CREDITS REPORTS", Url::toRoute(['mark-entry/add']), ['class' => 'btn btn-block btn-primary']) ?>
                    </div>
                    </div>
                </div>
                   

                <div class="box box-info active box-solid"> 
                               
                  <div class="box-header col-md-3 with-border">
                    <h5 class="box-title"><?php echo "Analysis ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT); ?></h5>    
                  </div>

                  <div style="clear: both"></div>

                  <div class="box-body">
                    <div class="col-md-2">
                       <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Information!!", Url::toRoute(['mark-entry/subjectinformation']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>  
                   
                   
                      <div class="col-md-2">                    
                        <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." Vs Result Marks", Url::toRoute(['mark-entry-master/programmeanalysis']), ['class' => 'btn btn-block btn-success']) ?> 
                      </div>
                      <div class="col-md-2">
                         <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Vs Result Marks!!", Url::toRoute(['mark-entry-master/courseanalysis']), ['class' => 'btn btn-block btn-success']) ?> 
                      </div>
                    
                    <div class="col-md-2">
                       <?= Html::a("Elective Count!!", Url::toRoute(['mark-entry/elective-count']), ['class' => 'btn btn-block btn-primary']) ?> 
                    </div>
                    <br /><br />
                  
                    <div class="col-md-2">
                       <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." Wise Arrear", Url::toRoute(['mark-entry/programmewisearrear']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>     
                    
                    <div class="col-md-2">
                       <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Wise Arrear!!", 
                       Url::toRoute(['mark-entry/coursewisearrear']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div> 

                     <div class="col-md-2">
                       <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Wise Arrear", Url::toRoute(['mark-entry/studentwisearrear']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div> 
                    
                    <div class="col-md-2">
                       <?= Html::a("Mark Percent", Url::toRoute(['mark-entry/mark-percent']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div> 
                   
                    <div class="col-md-2">
                       <?= Html::a("Consolidate CIA", Url::toRoute(['mark-entry/ciamarklist']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div> <br /><br />
                    <div class="col-md-2">
                       <?= Html::a("Consolidate ESE", Url::toRoute(['mark-entry/esemarklist']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div> 

                    <div class="col-md-4">
                       <?= Html::a("Consolidate Mark Statement", Url::toRoute(['mark-entry-master/consolidate-mark-sheet']), ['class' => 'btn btn-block btn-info']) ?> 
                    </div> 
                    <div class="col-md-2">
                       <?= Html::a("Arrear Internet Copy", Url::toRoute(['mark-entry-master/studentwisearrear']), ['class' => 'btn btn-block btn-warning']) ?> 
                    </div> 
                       <div class="col-md-2">
                         <?= Html::a("Pending Status", Url::toRoute(['degree/pending-status']), ['class' => 'btn btn-block btn-danger']) ?> 
                      </div>
                    <br /><br />
                   
                    <div class="col-md-2">
                       <?= Html::a("Verify Min & Max", Url::toRoute(['batch/course-marks-info']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>
                  
                    <div class="col-md-2">
                       <?= Html::a("Arrear Appeared Count", Url::toRoute(['mark-entry-master/arrear-count-overall']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>
                    <div class="col-md-3">
                       <?= Html::a("Batch Wise Arrear Report", Url::toRoute(['mark-entry-master/arrear-report']), ['class' => 'btn btn-block btn-primary']) ?> 
                    </div>
                     <div class="col-md-3">
                       <?= Html::a("Subject wise arrear report", Url::toRoute(['mark-entry-master/subjectarrear-report']), ['class' => 'btn btn-block btn-primary']) ?> 
                    </div>
                    
                    <div class="col-md-2">
                       <?= Html::a("Student List", Url::toRoute(['student/list-print']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>
                      </br></br>
                    <div class="col-md-2">
                       <?= Html::a("TOPPERS PART WISE", Url::toRoute(['subjects/toppers-list']), ['class' => 'btn btn-block btn-danger']) ?> 
                    </div>
                    <div class="col-md-2">
                       <?= Html::a("TOPPERS LIST", Url::toRoute(['categorytype/toppers-list']), ['class' => 'btn btn-block btn-danger']) ?> 
                    </div>
                    
                     <div class="col-md-2">
                       <?= Html::a("STUDENTS ARREAR FEES", Url::toRoute(['categorytype/student-arrear-count-with-fee']), ['class' => 'btn btn-block btn-warning']) ?> 
                    </div>
                    <div class="col-md-2">
                       <?= Html::a("Full Arrear List!!", Url::toRoute(['mark-entry/programmewisearrearnominal']), ['class' => 'btn btn-block btn-success']) ?> 
                    </div>
                    <div class="col-md-2">                    
                        <?= Html::a("Female report!!", Url::toRoute(['mark-entry-master/caste']), ['class' => 'btn btn-block btn-success']) ?> 
                      </div>
                  </div>
                </div> 
              </div>    
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
</section>

