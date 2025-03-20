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

$this->title = Yii::t('app', 'Scrutiny Dashboard'); 
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
		  <h1>Welcome to <?php echo $show_text; ?>!!</h1>
		  
		</div>
    	</div>   	
		
     
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <!-- begin DASHBOARD CIRCLE TILES -->

      <?php 

        $userid=Yii::$app->user->getId();

        $item_name = Yii::$app->db->createCommand("SELECT item_name FROM auth_assignment WHERE  user_id='" . $userid . "'")->queryScalar();

        if($item_name=='Scrutiny Access')
        {
      ?>
                <div class="row">
                   <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="circle-tile">
                            
                                <div class="circle-tile-heading blue">
                                    <i class="fa fa-book fa-fw fa-3x"></i>
                                </div>
                            <div class="circle-tile-content blue">
                                <div class="circle-tile-description text-faded">
                                   Mark Entry Page
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <span id="sparklineB"><canvas width="24" height="24" style="display: inline-block; width: 24px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                
                                <?php 

                                    echo Html::a('More Info <i class="fa fa-chevron-circle-right"></i>',['dummy-numbers/valuation-marks-entry'],['class'=>"circle-tile-footer"]);
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12">
                        <div class="circle-tile">
                            
                                <div class="circle-tile-heading blue">
                                    <i class="fa fa-book fa-fw fa-3x"></i>
                                </div>
                            <div class="circle-tile-content blue">
                                <div class="circle-tile-description text-faded">
                                   Mark Entry New Page
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <span id="sparklineB"><canvas width="24" height="24" style="display: inline-block; width: 24px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                
                                <?php 

                                    echo Html::a('More Info <i class="fa fa-chevron-circle-right"></i>',['dummy-numbers/valuation-marks-entry-new'],['class'=>"circle-tile-footer"]);
                                ?>
                            </div>
                        </div>
                    </div>
                  
                    <div class="col-lg-3 col-sm-12">
                        <div class="circle-tile">
                           
                                <div class="circle-tile-heading purple">
                                    <i class="fa fa-bar-chart fa-fw fa-3x"></i>
                                </div>
                            <div class="circle-tile-content purple">
                                <div class="circle-tile-description text-faded">
                                    Marks Entry List
                                </div>
                                <div class="circle-tile-number text-faded">
                                    
                                    <span id="sparklineD"><canvas width="36" height="24" style="display: inline-block; width: 36px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                
                                <?php
                                    echo Html::a('More Info <i class="fa fa-chevron-circle-right"></i>',['dummy-numbers/valuation-marks-entry-details'],['class'=>"circle-tile-footer"]);
                                ?>
                            </div>
                        </div>
                    </div>

                   

                </div>
                 <!-- end DASHBOARD CIRCLE TILES -->

                 <!--div class="row">
                   <div class="col-lg-3 col-sm-12"></div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="circle-tile">
                            
                                <div class="circle-tile-heading blue">
                                    <i class="fa fa-book fa-fw fa-3x"></i>
                                </div>
                            <div class="circle-tile-content blue">
                                <div class="circle-tile-description text-faded">
                                   Revaluation Mark Entry Page
                                </div>
                                <div class="circle-tile-number text-faded">
                                    <span id="sparklineB"><canvas width="24" height="24" style="display: inline-block; width: 24px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                
                                <?php 

                                    //echo Html::a('More Info <i class="fa fa-chevron-circle-right"></i>',['dummy-numbers/reval-marks-uverify'],['class'=>"circle-tile-footer"]);
                                ?>
                            </div>
                        </div>
                    </div>
                  
                    <div class="col-lg-3 col-sm-12">
                        <div class="circle-tile">
                           
                                <div class="circle-tile-heading purple">
                                    <i class="fa fa-bar-chart fa-fw fa-3x"></i>
                                </div>
                            <div class="circle-tile-content purple">
                                <div class="circle-tile-description text-faded">
                                    Revaluation Marks List
                                </div>
                                <div class="circle-tile-number text-faded">
                                    
                                    <span id="sparklineD"><canvas width="36" height="24" style="display: inline-block; width: 36px; height: 24px; vertical-align: top;"></canvas></span>
                                </div>
                                
                                <?php
                                    //echo Html::a('More Info <i class="fa fa-chevron-circle-right"></i>',['dummy-numbers/reval-marks-verify-details'],['class'=>"circle-tile-footer"]);
                                ?>
                            </div>
                        </div>
                    </div>

                   

                </div-->
    <?php }  else
        { ?>

              <div class="row" style="text-align:center">No Access Permission
                </div>
               
     <?php }?>
      

    </section>
    <!-- /.content -->
  </div>
