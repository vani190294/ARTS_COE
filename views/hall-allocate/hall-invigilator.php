<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use app\models\Batch;
use app\models\CoeBatDegReg;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\Categorytype;
use app\models\HallAllocate;


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Hall Invigilation";
$this->params['breadcrumbs'][] = ['label' => "Hall Invigilation ", 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
$month= isset($_POST['exam_month'])?$_POST['exam_month']:'';
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
        ]); 
    ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
             <div class="col-xs-12 col-sm-2 col-lg-2">
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y')]) ?>
         </div>

         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month3', 
                            'name' => 'exam_month3',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
            </div>
              <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($exam, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'exam_date3',
                            'name' => 'exam_date3',                             
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('From Date') ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($exam, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'semester',
                            'name' => 'semester',                              
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('To Date') ?>
        </div>
       <div class="col-xs-12 col-sm-2 col-lg-2">
                <?php echo $form->field($exam, 'created_by')->widget(
                    Select2::classname(), [
                    'data'=>['S1','S2','S3'],
                        'options' => [
                            'placeholder' => '-----Select SLOT ----',
                            'id' => 'slot',
                            'name'=>'slot',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label("Slot"); 
                ?>
            </div>
            <br />
            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn btn-success','name'=>'submit' ]) ?>
                 <?= Html::submitButton("View", ['class' => 'btn btn-group btn-group-lg btn-warning ','name'=>'view']) ?>

                <?= Html::a("Reset", Url::toRoute(['hall-allocate/hall-invigilator']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
<?php

       if(isset($_POST['view']))
              {

                $year=$_POST['HallAllocate']['year'];
                $month=$_POST['exam_month3'];
                $from_date=$_POST['exam_date3'];
                $to_date=$_POST['semester'];
                $from_date1= date("Y-m-d",strtotime($from_date));
                $to_date1=date("Y-m-d",strtotime($to_date));
                $slot=$_POST['slot'];
                if($slot==0)
                {
                  $s="S1";

                }
                else if($slot==1)
                {
                   $s="S2";

                }
                else
                {
                  $s="S3";
                 
                }
             
              $check_faculty= Yii::$app->db->createCommand("SELECT A.faculty_id,B.name,A.exam_date,A.exam_session,A.slot,C.hall_name  from coe_hall_invigilator as A  join coe_faculty_hall as B on B.faculty_id=A.faculty_id join coe_hall_master as C on C.coe_hall_master_id=A.hall_id  where A.exam_date   BETWEEN '".$from_date1."' and '".$to_date1."'and  A.month='".$month."'  and A.year='".$year."' and A.slot='".$s."' ")->queryAll();
              $html = $header_1 = $body = $header = $footer = '';
           
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            if($file_content_available=="Yes")
            {

            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');
            }
            $month_name = Categorytype::findOne($month);
            echo '
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                // echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('pending-count-report-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('pending-count-report-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '
                            </div>
                        </div>
                      ';
$header .='<table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" id = "student_import_results">';            
            
            $header .= '              
                    <tr>
                      <td align="center"> 
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=5 align="center"> 
                          <center><b><font size="6px">'.$org_name.'</font></b></center>
                          <center> <font size="3px">'.$org_address.'</font></center>
                          <center> Phone : <b>'.$org_phone.'</b></center>
                          <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                     </td>
                     </tr>

                     <tr>
                     <td colspan=6> <h2><b>
                              HALL INVIGILATION '.strtoupper($month_name->description).' '.$year.' </b></h2></td>
                     
                    
                    </tr>';
          
            $header .='<tr>
                <th width="50px" >SNO</th>
                <th width="100px" >FACULTY NAME</th>
                <th width="100px" >HALL NAME</th>
                <th width="100px" >EXAM DATE</th>
                <th width="100px" >EXAM SESSION</th>
               
                </tr>
            ';
            
            $header .='</tr></thead>'; 

            $body .="<tbody>";
            
                $sno=1;
                    foreach ($check_faculty as $values) 
                    {   
                        
                        $session_name = Categorytype::findOne($values['exam_session']);

                        $body .="<tr><td width='20px' >".$sno."</td>";
                        $body .= "<td width='25px' >".$values['name']."</td>";
                        $body .= "<td width='20px' >".$values['hall_name']."</td>";
                        $body .= "<td width='25px' >".date("d-m-Y", strtotime($values['exam_date']))."</td>";
                        $body .= "<td width='10px' >".$session_name->description."</td>";
                        
                        
                        $sno++;
                        
                    }
                $body .='</tbody></table>';
                $html = $header.$body;
                $html_1 = $html;
                if(isset($_SESSION['PENDING_status']))
                {
                    unset($_SESSION['PENDING_status']);
                }
                $_SESSION['PENDING_status'] = $html_1;
                echo $html;
                 
        }
             
                


               
      ?>
</div>
</div>
</div>
</div>


