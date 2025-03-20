<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use app\models\HallAllocate;
echo Dialog::widget();
use app\models\Categorytype; 


/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Evaluator Mark Entry";
$this->params['breadcrumbs'][] = $this->title;

 $this->registerJs("$(document).ready(function() { $(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; } }); });");
?>
<h1><?php echo $this->title; ?></h1>

<div>&nbsp;</div>
<div class="exam-timetable-form">
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 

$form = ActiveForm::begin(); ?>
<div>&nbsp;</div>
<div class="row">
<div class="col-xs-12 col-sm-12 col-lg-12">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
         
         


                <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'exam_year','name'=>'exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month', 
                            'name'=>'exam_month',
                            'onchange'=>'valuator_exam_month1();'                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>


        <div class="col-xs-12 col-sm-3 col-lg-3">
           <?php echo $form->field($factallModel,'coe_val_faculty_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Faculty----',      
                        'id'=>'v_val_faculty_all_id',   
                        'name'=>'v_val_faculty_all_id',
                                        
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label("Assigned Packets");  
            ?>  
        </div> 

         
        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <?= Html::Button('Show' , ['id' =>'eval_dummy', 'class' => 'btn btn-primary','onClick'=>'checkvaluateentry();']) ?>
            <?= Html::a("Reset", Url::toRoute(['mark-entry/valuator-markentry']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    
 </div> <!-- Row Closed -->

    <div class="col-xs-12 col-sm-12 col-lg-12">

        <div class="row"  id='hide_bar_code_data1'>

            <div  class="col-xs-12" id='valuator_entry'></div>

       </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">

        <?php 

        if(!empty($check_verify))
        {?>

            <div class="col-xs-12 col-sm-12 col-lg-12">
                <div class="col-xs-3 col-sm-10 col-lg-10">
                </div>    
                <div class="col-xs-3 col-sm-2 col-lg-2">
                    <?php 
                       
                        echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/mark-entry/verifydetails-pdf2'], [
                        'class'=>'pull-right btn btn-primary', 
                        'target'=>'_blank', 
                        'data-toggle'=>'tooltip', 
                        'title'=>'Will open the generated PDF file in a new window'
                        ]);
                    ?>
                </div>
            </div>

        <?php

            $monthName = Categorytype::findOne($_POST['exam_month']); 

            $yearName = Categorytype::findOne($_POST['exam_year']); 

            $vdata2=''; $vpdata1=''; $vpdata='';  $vdata1=''; $vdata=''; $data_header=$data_header1=$data_header2=$data_footer=$head='';
             $valuation_status = Yii::$app->db->createCommand("SELECT A.*,B.faculty_name,A.subject_code FROM coe_valuation_faculty_allocate A JOIN coe_valuation_faculty B ON B.coe_val_faculty_id=A.coe_val_faculty_id WHERE A.val_faculty_all_id='" . $val_faculty_all_id . "' ")->queryone();
        //print_r( $valuation_status);exit;

           $subcodecount = Yii::$app->db->createCommand("SELECT DISTINCT C.subject_code,C.subject_name FROM coe_exam_timetable A JOIN coe_subjects_mapping B ON B.coe_subjects_mapping_id=A.subject_mapping_id JOIN coe_subjects C ON C.coe_subjects_id=B.subject_id WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "' AND A.qp_code='".$valuation_status['subject_code']."' ORDER BY A.exam_date")->queryAll();

            //echo "SELECT DISTINCT C.subject_code,C.subject_name FROM coe_exam_timetable A JOIN coe_subjects_mapping B ON B.coe_subjects_mapping_id=A.subject_mapping_id JOIN coe_subjects C ON C.coe_subjects_id=B.subject_id WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "' AND A.qp_code='".$valuation_status['subject_code']."' ORDER BY A.exam_date"; exit;


           //print_r( $subcodecount);exit;

           $scruname = Yii::$app->db->createCommand("SELECT name FROM coe_valuation_scrutiny WHERE coe_scrutiny_id='" . $valuation_status['coe_scrutiny_id'] . "'")->queryScalar();
            $scrutiny_name=$scruname;

            $subjectinfo='';
            if(count($subcodecount)==1)
            {
                $subjectinfo='SUBJECT CODE <b>'.$subcodecount[0]['subject_code'].'</b><br>SUBJECT NAME <b>'.$subcodecount[0]['subject_name'].'</b><br>';
            }
            
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $data_header.='<table width="100%" border="0" ><tbody>
                        <tr>
                    <th colspan=2 align="center">   
                        <img class="img-responsive"  width="75" height="75" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                    </th>
                    <th colspan=5 align="center"> 
                        <center><b><font size="4px">'.$org_name.'</font></b></center>
                        <center>'.$org_address.'</center>
                        
                        <center>'.$org_tagline.'</center>
                         <center><b>SCORE CARD - '.strtoupper($monthName['category_type']).' '.$yearName.'</b></center>
                    </th>
                    <th colspan=2 align="center">  
                        <img width="75" height="75" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                    </th>
                </tr>
                 <tr>
                    <td align="left" colspan=5 style="font-size:10px;line-height: 14px;">
                    NAME OF THE EXAMINER: <b>'.strtoupper($valuation_status['faculty_name']).'</b><br>
                    VALUATION DATE & SESSION: <br><b>'.date("d-m-Y",strtotime($valuation_status['valuation_date'])).' & '.$valuation_status['valuation_session'].'</b>
                    </td>
                    <td align="right" colspan=4  style="font-size:10px;line-height: 14px;">
                    SUBJECT NAME: <b>'.$subcodecount[0]['subject_name'].'</b><br>
                     SUBJECT CODE: <b>'.$subcodecount[0]['subject_code'].'</b><br>
                    QP CODE: <b>'.$valuation_status['subject_code'].'</b><br>
                    COVER NUMBER: <b>'.$valuation_status['subject_pack_i'].'</b></th>
                </tr></table>';

                $data_footer ='<table width="100%" class="table table-responsive table-striped" align="center" border="0" ><tbody align="center">
                <tr height=45px class ="alternative_border">
                                    <td align="left" colspan=2>
                                        Name of the Examiner <br /><br />
                                        <b>'.strtoupper($valuation_status['faculty_name']).'</b> <br />

                                    </td>
                                    <td align="right" colspan=2>
                                        Name of the Chief Examiner / Controller <br /><br />
                                        <br />
                                    </td> 
                                </tr>
                                <tr>
                                    <td height="65px" align="left" colspan=2>
                                       Signature With Date <br /><br /><br />
                                    </td>
                                    <td  height="65px" align="right" colspan=2>
                                        Signature With Date <br /><br /><br />
                                    </td> 
                                </tr></tbody></table>';

                $data_header1='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="1"  class="table table-bordered table-responsive table-hover" >
                        <thead class="thead-inverse">
                           <tr class="table-danger">
                           <th style="vertical-align: middle;text-align:center">S.NO.</th>
                            <th style="vertical-align: middle;text-align:center">REGISTER NUMBER</th>
                            <th style="vertical-align: middle;text-align:center">MARKS</th>
                           <th style="vertical-align: middle;text-align:center">MARK IN WORDS</th>
                        </tr></thead>
                         ';
               


                  $head='<table width="100%" > <tbody><tr class ="alternative_border">
                                <td  class="vani"  align="left" ><b>
                                     SCORE CARD NO :
                                </b></td>
                                <td align="right"><b>
                                    CE 19(01)</b><br />
                                </td>
                                
                                </tr>
                                 </tbody></table>';

                $sno=1;
                 $vpdata.="<tbody align='center'>";

                foreach($check_verify as $value)
                {
                      $array = array('0' => 'ZERO', '1' => 'ONE', '2' => 'TWO', '3' => 'THREE', '4' => 'FOUR', '5' => 'FIVE', '6' => 'SIX', '7' => 'SEVEN', '8' => 'EIGHT', '9' => 'NINE', '10' => 'TEN');
        
                $return_string = '';

                $split_number = str_split($value["mark_100"]);

                for ($i = 0; $i < count($split_number); $i++) {
                    $return_string .= $array[$split_number[$i]] . " ";
                }
                    if($value['mark_100']=='-1')
                    {
                        $vpdata.="<tr>";
                        $vpdata.="<td style='vertical-align: middle;text-align:center'>".$sno."</td>";
                        $vpdata.="<td style='vertical-align: middle;text-align:center'>".$value['dummy_number']."</td>";
                        $vpdata.="<td style='vertical-align: middle;text-align:center'>AB</td>";                       
                        $vpdata.="<td style='vertical-align: middle;text-align:center'>ABSENT</td>";
                        $vpdata.="</tr> ";
                    }
                    else
                    {
                        $vpdata.="<tr>";
                        $vpdata.="<td style='vertical-align: middle;text-align:center'>".$sno."</td>";
                        $vpdata.="<td style='vertical-align: middle;text-align:center'>".$value['dummy_number']."</td>";
                        $vpdata.="<td style='vertical-align: middle;text-align:center'>".$value['mark_100']."</td>";
                        $split_number = str_split($value["mark_100"]);
                        
                        $print_text = $return_string;
                        $vpdata.="<td style='vertical-align: middle;text-align:center'>".$print_text."</td>";

                        $vpdata.="</tr> ";
                    }
                    
                    $sno++;
                }

                 $vpdata.="</tbody> </table>";                
            
          $vdata=$head.$data_header.$data_header1.$vpdata.$data_footer;

           $_SESSION['verifydetails'] = '';
            if (isset($_SESSION['verifydetails'])) 
            {
                unset($_SESSION['verifydetails']);
                
            }

           echo $_SESSION['verifydetails'] = $vdata;

        




}
        ?>

    </div>


</div>
</div>

<?php ActiveForm::end(); ?>


</div>

