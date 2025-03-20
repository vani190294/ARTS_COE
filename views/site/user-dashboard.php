<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\User;
use app\models\Degree;
use app\models\Subjects;
use app\models\Batch;
use app\models\Programme;
use app\models\Student;
use yii\bootstrap\ActiveForm;
use kartik\dialog\Dialog;
use scotthuangzl\googlechart\GoogleChart;

echo Dialog::widget();

$this->title = Yii::t('app', 'Admin Dashboard'); 
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Main content -->

<?php 
    Yii::$app->ShowFlashMessages->showFlashes();
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $show_text = isset($org_name)?$org_name:"Sri Krishna Institutions";
?>  


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    	<div class="intro_text">
    		<div class="type-js headline">
		  <h1 class="text-js">Welcome to <?php echo $show_text; ?>!!</h1>
		  
		</div>
    	</div>   	
		
     
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <!-- begin DASHBOARD CIRCLE TILES -->
                <div class="row">
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="index.php?r=rbac/user">
                                <div class="circle-tile-heading dark-blue">
                                    <i class="fa fa-user-secret fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content dark-blue">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "NO OF ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_USER); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo User::find()->count(); ?>
                                    <span id="sparklineA"><canvas width="29" height="24" style="display: inline-block; width: 29px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                <a href="index.php?r=rbac/user" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="index.php?r=degree/index">
                                <div class="circle-tile-heading green">
                                    <i class="fa fa-graduation-cap fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content green">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "NO OF ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo Degree::find()->count(); ?>
                                </div>
                                <a href="index.php?r=degree/index" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="index.php?r=batch/index">
                                <div class="circle-tile-heading orange">
                                    <i class="fa fa-university fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content orange">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "NO OF ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo Batch::find()->count(); ?>
                                </div>
                                <a href="index.php?r=batch/index" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="index.php?r=programme/index">
                                <div class="circle-tile-heading blue">
                                    <i class="fa fa-book fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content blue">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "NO OF ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo Programme::find()->count(); ?>
                                    <span id="sparklineB"><canvas width="24" height="24" style="display: inline-block; width: 24px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                <a href="index.php?r=programme/index" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="index.php?r=student/index">
                                <div class="circle-tile-heading red">
                                    <i class="fa fa-users fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content red">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "NO OF ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo Student::find()->where(['student_status'=>'Active'])->count() ?>
                                    <span id="sparklineC"><canvas width="34" height="24" style="display: inline-block; width: 34px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                <a href="index.php?r=student/index" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="index.php?r=reports/index">
                                <div class="circle-tile-heading purple">
                                    <i class="fa fa-bar-chart fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content purple">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "PRINT ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    
                                    <span id="sparklineD"><canvas width="36" height="24" style="display: inline-block; width: 36px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                
                                <?php
                                    echo Html::a('More Info <i class="fa fa-chevron-circle-right"></i>',['reports/index'],['class'=>"circle-tile-footer"]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end DASHBOARD CIRCLE TILES -->
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">

          <!-- quick email widget -->
          <div class="box box-info">
            <div class="box-header"> 
            <div class="col-md-2">
              <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)." Entry", Url::toRoute(['exam-timetable/absent']), ['class' => 'btn btn-block btn-danger']) ?>
            </div>
            <div class="col-md-2">
              <?= Html::a("Mark Statement !!", Url::toRoute(['mark-entry/mark-statement']), ['class' => 'btn btn-block btn-info']) ?>
            </div>

            <div class="col-md-2">
              <?= Html::a(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Mark View !!", Url::toRoute(['mark-entry/studentmark-view']), ['class' => 'btn btn-block btn-warning']) ?>
            </div>
            <div class="col-md-3">
              <?= Html::a("Consolidate Marksheet", Url::toRoute(['mark-entry-master/consolidate-mark-sheet']), ['class' => 'btn btn-block btn-primary']) ?> 
              </div>
            <?php 
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            /* 
              *   Already Defined Variables from the above included file
              *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
              *   use these variables for application
              *   use $file_content_available="Yes" for Content Status of the Organisation
            */
            if($file_content_available="Yes" && $org_email=='coe@skcet.ac.in')
            {
               ?>
               <div class="col-md-2">
                 <?= Html::a("Pending Status", Url::toRoute(['degree/pending-status']), ['class' => 'btn btn-block btn-danger']) ?> 
              </div>
               <?php 
            }
            else
            {
              ?>
              <div class="col-md-3">
               <?= Html::a("Pending Status", Url::toRoute(['degree/pending-status']), ['class' => 'btn btn-block btn-danger']) ?> 
              </div> 
              <?php
            }
            ?>
            
            </div>
           
          </div>
        </section>
        
      </div>
      <!-- /.row (main row) -->
      

    </section>
    <!-- /.content -->
  </div>
