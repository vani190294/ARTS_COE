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
use kartik\date\DatePicker;



echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Hall Invigilation Report";
$this->params['breadcrumbs'][] = ['label' => "Hall Invigilation Duty Report ", 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
$month= isset($_POST['exam_month'])?$_POST['exam_month']:'';
$exam_date=isset($model->exam_date)?date('d-m-Y',strtotime($model->exam_date)):"";
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
                            'name'=>'exam_date3' ,                          
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
          

           
            <br />
            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['hall-allocate/hall-duty']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
</div>
</div>
</div>

<?php

if(isset($check_faculty))
{
     /*$exam_type_g = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='".$month."'")->queryAll();
     $exsession = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='".$session."'")->queryAll();
     if($session==36)
     {
        $time="09:30AM to 12:30PM";


     }
     else
     {
         $time="01:30PM to 4:30PM";

     }*/
     $exam_date1=date("d-m-Y",strtotime($date));
     $year=$_POST['HallAllocate']['year'];
     $month=$_POST['exam_month3'];
     //print_r($year);exit;
       $data='';
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

    $data ='<table width="100%" id="checkAllFeet" class="table table-responsive table-striped" align="center" border="1"><tbody align="center" >';     
     $data.='<tr>
                     <td  colspan=6 align="right"><b>
                    CE 05(01)</b>
                </td>
        </tr> ';               
    $data.='<tr>
                <td>
                    <img class="img-responsive"   height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td  colspan=5 align="center"> 
                              <center><b><font size="4px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font> Phone : <b>'.$org_phone.'</b> </center><br /><center class="tag_line"><b>'.$org_tagline.'</b></center>
                </td></tr>
                <tr>
                <td  colspan=6 align="left"><b>Date:'.strtoupper($exam_date1).'</b></td>
                
                </tr>
                <tr> 
                <td colspan=6 align="left"><b>Chief:</b></td>
              </tr>';

            $data.=
            '<tr >
                <th width="2px"align="left">S.No</th>
                <th width="20px"align="left">NAME</th>     
                <th  width="25px" align="left">DEPARTMENT</th>
                <th width="10px" align="left">HALL NO</th>
                <th width="5px" align="left">SESSION</th>
                <th  align="left">SIGNATURE</th>
            </tr>';
              $sn=1;
              $i=1;

            foreach($check_faculty as $value)
            {
                $check_faculty= Yii::$app->db->createCommand('SELECT B.name,B.department,C.hall_name,A.exam_session  from coe_hall_invigilator as A join coe_faculty_hall as B on B.   
                    faculty_id=A.faculty_id join coe_hall_master as C on C.coe_hall_master_id=A.    hall_id Where A.faculty_id="'.$value['faculty_id'].'"')->queryAll();
              // print_r($check_faculty);exit;
            $exsession = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='".$check_faculty[0]['exam_session']."'")->queryAll();
            
                $data.='<tr>';
                $data.='<td align="left">'.$sn.'</td>';
                $data.='<td align="left">'.$check_faculty[0]['name'].'</td>';
                $data.='<td align="left">'.$check_faculty[0]['department'].'</td>';
                $data.='<td align="left">'.$exsession[0]['category_type'].'</td>';
                $data.='<td align="left">'.$check_faculty[0]['hall_name'].'</td>';
                $data.='<td align="left"></td>';
                  $sn++;



            }
            $data.='</tbody>';
           $data.='</table>';
             $data.='<br><br><br><br><br><br><table>';
           $data .='<tr>
                    <th align="left"> CHIEF</th>
                    <th align="right"> COE</th>
                    </tr>';
            
    $data.='</table>';
    if(isset($_SESSION['hall_duty']))
    {
        unset($_SESSION['hall_duty']);
    }
    $_SESSION['hall_duty'] = $data;
    echo '<div class="box box-primary">
            <div class="box-body">
                <div class="row" >';
    echo '<div class="col-xs-12">';
    echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('hall-count-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
    echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('hall-count-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
    echo '<div class="col-xs-12" >'.$data.'</div>
                </div>
            </div>
          </div>'; 
}
?>

