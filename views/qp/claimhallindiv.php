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
$this->title= "Hall Invigilation Day Wise Claim";

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
                            'id' => 'clm_month', 
                            'name' => 'fh_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
       <?php 
        if(Yii::$app->user->getId()==11 || Yii::$app->user->getId()==1)
            {?>

               <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'hall_date', 
                            'name' => 'hall_date',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Session ----', 
                            'id' => 'hall_session', 
                            'name' => 'hall_session',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]); ?>
        </div>


          <?php }?>
         
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

 <div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto !important;">
<?php 
    if(isset($consolidateddata) && !empty($consolidateddata))
    {
        $hallstaffamt=Yii::$app->db->createCommand("SELECT ug_amt FROM coe_val_claim_amt WHERE claim_id=8")->queryScalar();

        $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['qp/claimhall-pdf'], [
                            'class' => 'pull-right btn btn-block btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                    ]);
                    
        echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > </div><div class="col-lg-8" ></div> <div class="col-lg-2" > ' . $print_pdf. ' </div></div></div></div></div>';

      
        $html = "";
        $header = "";
        $body ="";
        $footer = "";  

        $exmdate='';
        if($hall_date!='')
        {
          
          $ses=($sesion==36)?'FN':'AN';
           $exmdate=$hall_date.' & '.$ses;
           $hall_date=date("Y-m-d",strtotime($hall_date));
        }
        else
        {
          $hall_date1=date("d-m-Y");
          $exmdate=$hall_date1.' & '.$sesionname;
          $hall_date=date("Y-m-d");
        }
      //echo $exmdate; exit;
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

        $header .="<table width='100%' style='overflow-x:auto !important;'  align='center' class='table table-striped '>";
        $header .= '<thead><tr>
                    <th align="center" style="border-right:0px !important;">
                        <img class="img-responsive" width="75" height="75" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                    </th>
                    <th colspan=8 align="center" style="border-right:0px !important; border-left:0px !important;">
                        <h4> 
                          <center><b><font size="5px">' . $org_name . '</font></b></center></h4>
                           <h6>   <center> ' . $org_address . '</center>
                           <center class="tag_line"><b>' . $org_tagline . '</b></center> </h6>
                        
                         <h5> <center>Hall Invigilation Day Wise Claim- '.$_SESSION['get_examyear'].'</center></h5>

                         <h5> <center>Exam Date: '.$exmdate.'</center></h5>
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
                  <th>Total Remuneration(Rs.)</th>
                  <th>Signature</th>
              </tr></thead>
              <tbody>"; 
             $sl=1;
             $totalduty=0;
             $duty=0;
            foreach ($consolidateddata as  $value) 
            { 

                $hallcnt = Yii::$app->db->createCommand("SELECT faculty_id FROM coe_faculty_hall_arrange WHERE faculty_id=".$value['faculty_id']." AND year=".$year." AND month=".$month." AND exam_date='".$hall_date."' AND exam_session=".$sesion." group by exam_date,exam_session")->queryAll();

                $rhscnt = Yii::$app->db->createCommand("SELECT rhs FROM coe_faculty_hall_arrange WHERE rhs=".$value['faculty_id']." AND year=".$year." AND month=".$month." AND exam_date='".$hall_date."' AND exam_session=".$sesion."  group by exam_date,exam_session")->queryAll();

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
                //$body .='<td>'.$noofduty.'</td>';
                if($value['bank_accno']=='')
                {
                  $body .='<td>Pls fill Bank Details</td>';
                }
                else
                {
                  $body .='<td>'.$dutyamt.'</td>';
                  $duty=$duty+$dutyamt;
                }
                
                $body .='<td></td>';
                $body .='</tr>';

                $claim_date=$value['exam_date'];

                $val_faculty_id=$value['faculty_id'];
                 $totalduty=$totalduty+$noofduty; 
                 

                $checkclaim = Yii::$app->db->createCommand("SELECT count(*) FROM coe_val_faculty_claim WHERE claim_type=7 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar();

                $login_user_id=Yii::$app->user->getId();

                if($checkclaim == 0 && (strtotime($claim_date)==strtotime(date("Y-m-d")) || ($login_user_id==1 || $login_user_id==11)))
                {
                    $login_user_id=Yii::$app->user->getId(); //exit;
                    Yii::$app->db->createCommand('INSERT into coe_val_faculty_claim(claim_type, val_faculty_id, exam_year, exam_month, claim_date, total_script, total_script_amount, tada_amt, total_claim, created_at, created_by) values(7,"'.$val_faculty_id.'","'.$year.'", "'.$month.'","'.date("Y-m-d",strtotime($claim_date)).'" ,"'.$noofduty.'", "0", "0", "'.$dutyamt.'","'.date('Y-m-d H:i:s').'","'.$login_user_id.'") ')->execute();
                }
                else if($checkclaim != 0)
                {
                    $remun_id = Yii::$app->db->createCommand("SELECT remun_id FROM coe_val_faculty_claim WHERE claim_type=7 AND exam_month='" . $month . "' AND exam_year='" . $year . "' AND claim_date='".date("Y-m-d",strtotime($claim_date))."' AND val_faculty_id=".$val_faculty_id)->queryScalar(); //exit;
                    $login_user_id=Yii::$app->user->getId();
                     Yii::$app->db->createCommand('UPDATE coe_val_faculty_claim SET total_script='.$noofduty.', total_claim='.($dutyamt).', updated_at="'.date('Y-m-d H:i:s').'", updated_by='.$login_user_id.' WHERE remun_id='.$remun_id)->execute();
                }
                                        
             $sl++;

            } 
            
              $body .='<tr><td  align="right" colspan=8><b>Total</b></td><td  align="left" colspan=2><b> '.$duty.'</b></td></tr>';

                                     
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

        $send_results = $header.$body.$footer;

        $_SESSION['claimhalldata'] = $send_results;
    }
?>    

</div>
