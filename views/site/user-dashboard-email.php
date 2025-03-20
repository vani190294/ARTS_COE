<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\User;
use app\models\Degree;
use app\models\Batch;
use app\models\Programme;
use app\models\Student;

$this->title = Yii::t('app', 'Admin Dashboard'); 
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Main content -->

<?php Yii::$app->ShowFlashMessages->showFlashes();?>  


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    	<div class="intro_text">
    		<div class="type-js headline">
		  <h1 class="text-js">Welcome to Sri Krishna Institutions!!</h1>
		  
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
                            <a href="#">
                                <div class="circle-tile-heading dark-blue">
                                    <i class="fa fa-user-secret fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content dark-blue">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "Active ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_USER); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo User::find()->count(); ?>
                                    <span id="sparklineA"><canvas width="29" height="24" style="display: inline-block; width: 29px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading green">
                                    <i class="fa fa-graduation-cap fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content green">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "Active ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo Degree::find()->count(); ?>
                                </div>
                                <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading orange">
                                    <i class="fa fa-university fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content orange">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "Active ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo Batch::find()->count(); ?>
                                </div>
                                <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading blue">
                                    <i class="fa fa-book fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content blue">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "Active ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo Programme::find()->count(); ?>
                                    <span id="sparklineB"><canvas width="24" height="24" style="display: inline-block; width: 24px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading red">
                                    <i class="fa fa-users fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content red">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "Total ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <?php echo Student::find()->where(['student_status'=>'Active'])->count() ?>
                                    <span id="sparklineC"><canvas width="34" height="24" style="display: inline-block; width: 34px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading purple">
                                    <i class="fa fa-bar-chart fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content purple">
                                <div class="circle-tile-description text-faded">
                                    <?php echo "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT); ?>
                                </div>
                                <div class="circle-tile-number text-faded">
                                    
                                    <span id="sparklineD"><canvas width="36" height="24" style="display: inline-block; width: 36px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end DASHBOARD CIRCLE TILES -->
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">

          <!-- quick email widget -->
          <div class="box box-info">
            <div class="box-header">
              <i class="fa fa-envelope"></i>

              <h3 class="box-title">Quick Email</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body">
              <form action="#" method="post">
                <div class="form-group">
                  <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subject" placeholder="Subject">
                </div>
                <div>
                  <textarea class="textarea" placeholder="Message"
                            style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>
              </form>
            </div>
            <div class="box-footer clearfix">
              <button type="button" class="pull-right btn btn-default" id="sendEmail">Send
                <i class="fa fa-arrow-circle-right"></i></button>
            </div>
          </div>

        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">

          
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Get Document</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                  <div class="chart-responsive">
                    <canvas id="pieChart" height="150"></canvas>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
                
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
           
          </div>
          <!-- /.box -->


        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
