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

$this->title = "Hall Invigilation Faculty Duty Report";
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
                            'id' => 'exam_month', 
                            'name' => 'exam_month',                            
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
     $exam_type_g = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='".$month."'")->queryAll();
     $year=$_POST['HallAllocate']['year'];
    
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $check_faculty_1=Yii::$app->db->createCommand('SELECT distinct A.faculty_id  from coe_hall_invigilator as A join coe_faculty_hall as B on B.faculty_id=A.faculty_id Where A.month='.$month.'  and A.year='.$year.'  order by B.faculty_id')->queryAll();

     $check_count= Yii::$app->db->createCommand('SELECT count(A.faculty_id) as count from coe_hall_invigilator as A join coe_faculty_hall as B on B.faculty_id=A.faculty_id Where A.month='.$month.'  and A.year='.$year.' order by B.faculty_id')->queryOne();
     //print_r($check_faculty_1);exit;
    
    
 $data=''; 
    foreach($check_faculty_1 as $fac)
    {
        $check_faculty_2= Yii::$app->db->createCommand('SELECT  B.name,B.department,A.exam_date,A.exam_session,A.slot,B.uniqueid  from coe_hall_invigilator as A join coe_faculty_hall as B on B.faculty_id=A.faculty_id Where A.month='.$month.'  and A.year='.$year.' and B.faculty_id='.$fac['faculty_id'].' order by B.faculty_id')->queryAll();
       
        $data.='<table width="100%" id="checkAllFeet" class="table table-responsive table-striped" align="center" border="1"><tbody align="center" >'; 
        $data.='<tr>
                     <td  colspan=10 align="right"><b>
                    CE 05(01)</b>
                </td>
        </tr> ';                       
           
        $data.='<tr>
              
                <td align="left"> 
                            <img class="img-responsive" width="80" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                        </td>
                <td colspan=3> 
                    <center><b><h3>'.$org_name.'</h3></b></center>
                    <center>'.$org_address.'</center>
                    <center>'.$org_tagline.'</center> 
                  
                </td></tr>
                <tr><td colspan=4>
                  <center><b><h5>END SEMESTER EXAMAINATIONS-INVIGILATION ORDER('.$exam_type_g[0]['category_type'].' -'.$year.')</b></h5></center>
                  </tr></td>
                <tr> 
                <td colspan=4 align="left"><b>Name Of The Faculty: '.$check_faculty_2[0]['name'].'</b> <b>FACULTY ID: '.$check_faculty_2[0]['uniqueid'].'</b></td><tr>
                <tr><td colspan=4   align="left"><b>Department: '.$check_faculty_2[0]['department'].'</b></td></tr>
                <tr><td colspan=4  align="left"><b>Timing *:  <b>FN:10.00A.M-01.00P.M<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AN:1.30P.M-4.30P.M.</td></tr>';
        $data.='<tr>
                <td width="40" align="left"><b>S.No</b></td>
                <td  align="left"><b>Date Of Invigilation</b></td>
                <td  align="left"><b>Slot</b></td>     
                <td  align="left"><b>Session</b></td>
                </tr>';
                $i=1;

        foreach($check_faculty_2 as $val)
        {
            
       
        $exsession = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='".$val['exam_session']."'")->queryAll();
        $exam_date1=date("d-m-Y",strtotime($val['exam_date']));
        
               
        $data.='<tr>';
                $data.='<td width="40" align="left">'. $i.'</td>';
                $data.='<td  align="left">'.$exam_date1.'</td>';
                $data.='<td align="left">'.$val['slot'].'</td>';
                $data.='<td align="left">'.$exsession[0]['category_type'].'</td>';
                $data.='</tr>';
                $data.='</tbody>';
                
       
         $i++;
        
       
    }
     $data.='</table>';


      $data.='<br><div><b>*Invigilators Should Report 30Minutes Before The Examinations</b></div>';
      $data.='<br><br><br><br><br><br>';
     $data .='<table><tr>
              <th align="left"> DATE:</th>
              <th align="right"> Principal</th>
              </tr>';
      $data.='</table><br><br><br>';


}

            
     
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

