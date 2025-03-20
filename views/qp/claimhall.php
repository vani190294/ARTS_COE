<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\dialog\Dialog;
use app\models\Categorytype;
use kartik\date\DatePicker;
use app\models\ExamTimetable;


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\AbsentEntry */
/* @var $form ActiveForm */
$this->title= "Hall Invigilation Claim";

$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?php echo $this->title; ?></h1>
<br /><br />
<div class="exam-timetable-absent">
<div class="box box-success">
<div class="box-body">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin(); ?>

<div class="row">
<div class="col-12">
    
    <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id' => 'fh_year','name' => 'fh_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'fh_month', 
                            'name' => 'fh_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
       
       <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_date')->widget(
                    Select2::classname(), [  
                                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select  ----',
                            'id' => 'from_date', 
                            'name' => 'from_date',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('From Date'); ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_date')->widget(
                    Select2::classname(), [  
                                             
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select  ----',
                            'id' => 'to_date', 
                            'name' => 'to_date',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('To Date'); ?>
        </div>
         
   <div class="col-lg-2 col-sm-3"> 
        <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['qp/claimhall']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
    

  </div>
</div>
</div>
</div>
</div>
 <?php ActiveForm::end(); ?>
<div class="exam-timetable-absent">
<div class="box box-primary">
<div class="box-body">
<div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto !important;">
  <?php 
    if(isset($consolidateddata) && !empty($consolidateddata))
    {
        $hallstaffamt=Yii::$app->db->createCommand("SELECT ug_amt FROM coe_val_claim_amt WHERE claim_id=6")->queryScalar();

        $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['qp/claimhall-pdf'], [
                            'class' => 'pull-right btn btn-block btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                    ]);

        $print_excel = Html::a('<i class="fa fa-file"></i> Excel', ['qp/claimhall-excel'], [
                            'class' => 'pull-right btn btn-block btn-warning',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                    ]);
                    
        echo '
        <div class="row" >
        <div class="col-xs-12" ><div class="col-lg-10" ></div> <div class="col-lg-1" > ' . $print_pdf. ' </div><div class="col-lg-1" > ' . $print_excel. ' </div></div></div>';

      
        $html = "";
        $header = "";
        $header1 = "";
        $body ="";
        $footer = "";  

        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

        $header .="<div class='col-xs-12'><table width='100%' style='overflow-x:auto !important;'  align='center' class='table table-striped '>";
        $header .= '<thead><tr>
                    <th align="center" style="border-right:0px !important;">
                        <img class="img-responsive" width="75" height="75" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                    </th>
                    <th colspan=8 align="center" style="border-right:0px !important; border-left:0px !important;">
                        <h4> 
                          <center><b><font size="5px">' . $org_name . '</font></b></center></h4>
                           <h6>   <center> ' . $org_address . '</center>
                           <center class="tag_line"><b>' . $org_tagline . '</b></center> </h6>
                        
                         <h5> <center>Hall Invigilation Claim- '.$_SESSION['get_examyear'].'</center></h5>
                    </th>
                <th align="center" style="border-left:0px !important;">  
                    <img width="75" height="75" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                </th>
            </tr> ';

  

          $header .= "
              <tr>
                  <th>S.No.</th>
                  <th>Name</th>
                  <th>Dept.</th>
                  <th>College Name</th>
                  <th>Account Number</th>                            
                  <th>IFSC Code</th>
                  <th>Bank</th>
                  <th>Branch</th>
                  <th>No. of Duty</th>
                  <th>Total Remuneration(Rs.)</th>
              </tr></thead>
              <tbody>"; 

            $header1 .= "<table width='100%' style='overflow-x:auto !important;'  align='center' class='table table-striped '>
              <tr>
                  <th>S.No.</th>
                  <th>Name</th>
                  <th>Dept.</th>
                  <th>College Name</th>
                  <th>Account Number</th>                            
                  <th>IFSC Code</th>
                  <th>Bank</th>
                  <th>Branch</th>
                  <th>No. of Duty</th>
                  <th>Total Remuneration(Rs.)</th>
              </tr></thead>
              <tbody>"; 
             $sl=1;

             $totalduty=0;
             $duty=0;
            foreach ($consolidateddata as  $value) 
            { 
                if($from_date!='' && $to_date!='')
                {                
                    $hallcnt = Yii::$app->db->createCommand("SELECT faculty_id FROM coe_faculty_hall_arrange WHERE faculty_id=".$value['faculty_id']." AND year=".$year." AND month=".$month." AND (exam_date BETWEEN '".$from_date."' AND '".$to_date."')  group by exam_date,exam_session")->queryAll();

                    $rhscnt = Yii::$app->db->createCommand("SELECT rhs FROM coe_faculty_hall_arrange WHERE rhs=".$value['faculty_id']." AND year=".$year." AND month=".$month." AND (exam_date BETWEEN '".$from_date."' AND '".$to_date."') group by exam_date,exam_session")->queryAll();
                }
                else
                {
                    $hallcnt = Yii::$app->db->createCommand("SELECT faculty_id FROM coe_faculty_hall_arrange WHERE faculty_id=".$value['faculty_id']." AND year=".$year." AND month=".$month." group by exam_date,exam_session")->queryAll();
                    $rhscnt = Yii::$app->db->createCommand("SELECT rhs FROM coe_faculty_hall_arrange WHERE rhs=".$value['faculty_id']." AND year=".$year." AND month=".$month." group by exam_date,exam_session")->queryAll();
                }

               

                $noofduty=count($hallcnt)+count($rhscnt); 
                $dutyamt=$hallstaffamt*$noofduty; 

                $clgcode=(!empty($value['college_code']))?$value['college_code']:"SKCT";    

                $body .='<tr>';
                $body .='<td>'.$sl.'</td>';
                $body .='<td>'.$value['faculty_name'].'</td>';
                $body .='<td>'.$value['faculty_board'].'</td>';
                $body .='<td>'.$clgcode.'</td>';
                $body .='<td>'.$value['bank_accno'].'</td>';
                $body .='<td>'.$value['bank_ifsc'].'</td>';
                $body .='<td>'.ucwords($value['bank_name']).'</td>';
                $body .='<td>'.ucwords($value['bank_branch']).'</td>';
                $body .='<td>'.$noofduty.'</td>';
                $body .='<td>'.$dutyamt.'</td>';
                $body .='</tr>';

                 $totalduty=$totalduty+$noofduty; 
                 $duty=$duty+$dutyamt;

                                        
             $sl++;

            }  

            $body .='<tr><td  align="right" colspan=8><b>Total</b><td  align="left" colspan=1><b> '.$totalduty.'</b><td  align="left" colspan=2><b> '.$duty.'</b></td></tr>';


             $body .='<tr><td align="center" colspan="10"><b>AUR</b></td></tr>';

            $auramt=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=5")->queryOne();
            $sl=1;

            $day=0;
            $amount=0;

            foreach ($aur_faculty as  $value) 
            {   
                if($from_date!='' && $to_date!='')
                {
                  $aur_date = Yii::$app->db->createCommand("SELECT DISTINCT exam_date FROM coe_faculty_hall_arrange WHERE aur=".$value['aur']." AND (exam_date BETWEEN '".$from_date."' AND '".$to_date."') AND year=".$year." AND month=".$month)->queryAll();
                }
                else
                {
                  $aur_date = Yii::$app->db->createCommand("SELECT DISTINCT exam_date FROM coe_faculty_hall_arrange WHERE aur=".$value['aur']." AND year=".$year." AND month=".$month)->queryAll();
                }


               $noofday=0;
               foreach ($aur_date as $dvalue) 
               {
                 
                  $aur_fn = Yii::$app->db->createCommand("SELECT aur FROM coe_faculty_hall_arrange WHERE aur=".$value['aur']." AND year=".$year." AND month=".$month." AND exam_date='".$dvalue['exam_date']."' AND exam_session=36  group by exam_date,exam_session")->queryAll();

                  $aur_an = Yii::$app->db->createCommand("SELECT aur FROM coe_faculty_hall_arrange WHERE aur=".$value['aur']." AND year=".$year." AND month=".$month." AND exam_date='".$dvalue['exam_date']."' AND exam_session=37  group by exam_date,exam_session")->queryAll();

                  if(count($aur_fn)==1 && count($aur_an)==1) 
                  {
                      $noofday=$noofday+1;
                  }
                  else if(count($aur_fn)==1 || count($aur_an)==1) 
                  {
                      $noofday=$noofday+0.5;
                  }

               }

                $tot_ta=0;
                if($noofday<1 && $noofday!=0)
                {
                    $tot_ta=$auramt['ug_amt']+$auramt['ta_amt_half_day'];
                }
                else if($noofday>=1 && $noofday!=0)
                {
                    $n = $noofday;
                    $whole = floor($n);      // 1
                    $fraction = $n - $whole; // .25

                    if($fraction==0)
                    {
                        $tot_ta=$whole*($auramt['pg_amt']+$auramt['ta_amt_full_day']);
                    }
                    else if($fraction>0)
                    {
                        $tot_ta=($whole*($auramt['pg_amt']+$auramt['ta_amt_full_day']))+($auramt['ug_amt']+$auramt['ta_amt_half_day']);
                    }
                }

                $clgcode=(!empty($value['college_code']))?$value['college_code']:"SKCT";    

                $body .='<tr>';
                $body .='<td>'.$sl.'</td>';
                $body .='<td>'.$value['faculty_name'].'</td>';
                $body .='<td>'.$value['faculty_board'].'</td>';
                $body .='<td>'.$clgcode.'</td>';
                $body .='<td>'.$value['bank_accno'].'</td>';
                $body .='<td>'.$value['bank_ifsc'].'</td>';
                $body .='<td>'.ucwords($value['bank_name']).'</td>';
                $body .='<td>'.ucwords($value['bank_branch']).'</td>';
                $body .='<td>'.$noofday.'</td>';
                $body .='<td>'.$tot_ta.'</td>';
                $body .='</tr>';

                $day=$day+$noofday;
                $amount=$amount+$tot_ta;
                       
                                        
             $sl++;

            }                    

             $body .='<tr><td  align="right" colspan=8><b>Total</b><td  align="left" colspan=1><b> '.$day.'</b><td  align="left" colspan=2><b> '.$amount.'</b></td></tr>';   


             $body .='<tr><td align="center" colspan="10"><b>Chief Superintendent</b></td></tr>';

            $chiefamt=Yii::$app->db->createCommand("SELECT * FROM coe_val_claim_amt WHERE claim_id=7")->queryOne();
            $sl=1;

            $day=0;
            $amount=0;

            foreach ($chief_faculty as  $value) 
            {   
               $chief_date = Yii::$app->db->createCommand("SELECT DISTINCT exam_date FROM coe_faculty_hall_arrange WHERE chief=".$value['chief']." AND year=".$year." AND month=".$month)->queryAll();

               $noofday=0;
               foreach ($chief_date as $dvalue) 
               {
                 
                  $chief_fn = Yii::$app->db->createCommand("SELECT chief FROM coe_faculty_hall_arrange WHERE chief=".$value['chief']." AND year=".$year." AND month=".$month." AND exam_date='".$dvalue['exam_date']."' AND exam_session=36  group by exam_date,exam_session")->queryAll();

                  $chief_an = Yii::$app->db->createCommand("SELECT chief FROM coe_faculty_hall_arrange WHERE chief=".$value['chief']." AND year=".$year." AND month=".$month." AND exam_date='".$dvalue['exam_date']."' AND exam_session=37  group by exam_date,exam_session")->queryAll();

                  if(count($chief_fn)==1 && count($chief_an)==1) 
                  {
                      $noofday=$noofday+1;
                  }
                  else if(count($chief_fn)==1 || count($chief_an)==1) 
                  {
                      $noofday=$noofday+0.5;
                  }

               }

                $tot_ta=0;
                if($noofday<1 && $noofday!=0)
                {
                    $tot_ta=$chiefamt['ug_amt']+$chiefamt['ta_amt_half_day'];
                }
                else if($noofday>=1 && $noofday!=0)
                {
                    $n = $noofday;
                    $whole = floor($n);      // 1
                    $fraction = $n - $whole; // .25

                    if($fraction==0)
                    {
                        $tot_ta=$whole*($chiefamt['pg_amt']+$chiefamt['ta_amt_full_day']);
                    }
                    else if($fraction>0)
                    {
                        $tot_ta=($whole*($chiefamt['pg_amt']+$chiefamt['ta_amt_full_day']))+($chiefamt['ug_amt']+$chiefamt['ta_amt_half_day']);
                    }
                }

                $clgcode=(!empty($value['college_code']))?$value['college_code']:"SKCT";    

                $body .='<tr>';
                $body .='<td>'.$sl.'</td>';
                $body .='<td>'.$value['faculty_name'].'</td>';
                $body .='<td>'.$value['faculty_board'].'</td>';
                $body .='<td>'.$clgcode.'</td>';
                $body .='<td>'.$value['bank_accno'].'</td>';
                $body .='<td>'.$value['bank_ifsc'].'</td>';
                $body .='<td>'.ucwords($value['bank_name']).'</td>';
                $body .='<td>'.ucwords($value['bank_branch']).'</td>';
                $body .='<td>'.$noofday.'</td>';
                $body .='<td>'.$tot_ta.'</td>';
                $body .='</tr>';

                $day=$day+$noofday;
                $amount=$amount+$tot_ta;
                       
                                        
             $sl++;

            }          

            $body .='<tr><td  align="right" colspan=8><b>Total</b><td  align="left" colspan=1><b> '.$day.'</b><td  align="left" colspan=2><b> '.$amount.'</b></td></tr>';                        
   
        
        $body .='</tbody></table>';

        $footer .='<table width="100%" style="overflow-x:auto; border:0px !important;">
            
                    <tr height="100px">

                    <td style="height: 50px; text-align: right; margin-right: 5px; border:0px !important;"> 
                    <br>                        
                       <br>
                        <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                      </td>
                    </tr></tbody></table>';


        echo $header.$body;

        if (isset($_SESSION['claimhalldata'])) 
        {
            unset($_SESSION['claimhalldata']);
                                
        }

        $send_results = $header.$body.$footer."</div>";

        $_SESSION['claimhalldata'] = $send_results;

        if (isset($_SESSION['claimhalldataexcel'])) 
        {
            unset($_SESSION['claimhalldataexcel']);
                                
        }
        $_SESSION['claimhalldataexcel'] =$header1.$body;
    }
  ?>    

</div>
</div>
</div>
</div>