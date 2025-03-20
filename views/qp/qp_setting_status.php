<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Categorytype;
use yii\db\Query;
use app\models\QpSetting;
use app\models\Batch;

echo Dialog::widget();
$this->title = 'QP Setting List';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  

    <div class="col-xs-12 col-sm-12 col-lg-12">  

      
       <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'qp_year','name'=>'qp_year']) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [
                        'data' => $model->getMonth(),                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'qpassign_month',
                            'name' => 'qpassign_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <!--div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'exam_type')->widget(
                Select2::classname(), [
                    'data' => $model->ExamType,
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).' ----',
                        'name' => 'qpexam_type',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div-->

         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'updated_at')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'fromdate', 
                            'name' => 'fromdate',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('From Date');?>
        </div>

         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'updated_at')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'todate', 
                            'name' => 'todate',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('To Date'); ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <br>
            <label class="control-label">
            <input type="checkbox" name="qpstatus"  value=1>
            With Subject Code
            </label>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2"><br>
            <?= Html::submitButton('Show' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'qpsettingassign' ]) ?>          
            
            <?= Html::a("Reset", Url::toRoute(['qp/qpsettingstatus']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>


   
    <div class="col-lg-12 col-sm-12">

        <?php 
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $monthname = Categorytype::findOne($dmonth);
        //$exam_typename = Categorytype::findOne($exam_type);      
       
             if(!empty($subjectdata))
            {
                 

                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['qp/qpsetting-pdf'], [
                            'class' => 'pull-right btn btn-block btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                    ]);
                    $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['qp/qpsettingstatus-excel'], [
                                'class' => 'pull-right btn btn-block btn-warning',
                                'target' => '_blank',
                                'data-toggle' => 'tooltip',
                                'title' => 'Will open the generated PDF file in a new window'
                    ]);

                     echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf. " ".$print_excel . ' </div></div></div></div></div>';
                $html = "";
                $header = "";
                $body ="";
                $footer = "";                
                
                if($status=='on')
                {
                    $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                    $header .= '<tr>
                                <td align="center">
                                    <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                </td>
                                <td colspan=3 align="center">
                                    <h3> 
                                      <center><b><font size="5px">' . $org_name . '</font></b></center>
                                        <center> <font size="3px">' . $org_address . '</font></center>
                                        <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                    </h3>
                                     <h4>QUESTION PAPER SETTERS LIST '.strtoupper($monthname['category_type']).' - '.$dyear.'</h4>
                                    <h4>'.$_SESSION['headingdate'].'</h4>
                                </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                        </tr></table> ';

                    $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>
                            <th>NAME OF THE QUESTION PAPER  SETTERS</th>
                            <th>COURSE CODE</th>
                            <th>COURSE NAME</th>
                            <th>NAME OF THE BANK</th>
                            <th>BRANCH</th>
                            <th>IFSC CODE</th>
                            <th>ACCOUNT NUMBER</th>
                            <th>AMOUNT</th>
                        </tr>
                        <tbody>"; 

                    $sl=1; $totalscript=0; $tempid='';

                    $qpamount = Yii::$app->db->createCommand("SELECT ug_amt FROM coe_val_claim_amt WHERE claim_id=3")->queryScalar();
                    foreach ($subjectdata as  $value) 
                    { 
                        $valuation_faculty1 = Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['faculty1_id']."'")->queryone();

                       

                        if($_SESSION['claimdate']!='')
                        {
                            $f1cnt = Yii::$app->db->createCommand("SELECT num_question_set FROM coe_qp_setting WHERE subject_code='".$value['subject_code']."' AND year=".$year." AND month=".$month." AND faculty1_id=".$value['faculty1_id'].$_SESSION['claimdate'])->queryScalar();
                        }
                        else
                        {   
                            $f1cnt = Yii::$app->db->createCommand("SELECT num_question_set FROM coe_qp_setting WHERE subject_code='".$value['subject_code']."' AND year=".$year." AND month=".$month." AND faculty1_id=".$value['faculty1_id'])->queryScalar();
                        }
                        

                        $totalscript=$totalscript+($f1cnt);
                        $total_renum=$qpamount*$f1cnt;

                        if($tempid==$value['faculty1_id'])
                        {
                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td></td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td></td>';
                            $body .='<td></td>';
                            $body .='<td></td>';
                            $body .='<td></td>';
                            $body .='<td>'.$total_renum.'</td>';
                            $body .='</tr>';
                        }
                        else
                        {
                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$valuation_faculty1['faculty_name'].'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$valuation_faculty1['bank_name'].'</td>';
                            $body .='<td>'.$valuation_faculty1['bank_branch'].'</td>';
                            $body .='<td>'.$valuation_faculty1['bank_ifsc'].'</td>';
                            $body .='<td>'.$valuation_faculty1['bank_accno'].'</td>';
                            $body .='<td>'.$total_renum.'</td>';
                            $body .='</tr>';
                        }

                       

                        $tempid=$value['faculty1_id'];
                                                
                     $sl++;

                    }

                    $body .='
            
                    <tr height="100px">

                    <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="5">Total QP : '.$totalscript.'
                      </td>

                       <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="4">Total Amount : '.($qpamount*$totalscript).'
                      </td>
                    </tr>';
                
                    $footer .='
                
                        <tr height="100px"  >

                        <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="9"><br>                        
                           <br>
                            <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                          </td>
                        </tr>';

                    echo  $header.$body."</tbody></table>";
                }
                else if($status=='off')
                {
                    $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                    $header .= '<tr>
                                <td align="center">
                                    <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                </td>
                                <td colspan=3 align="center">
                                    <h3> 
                                      <center><b><font size="5px">' . $org_name . '</font></b></center>
                                        <center> <font size="3px">' . $org_address . '</font></center>
                                        <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                    </h3>
                                     <h4>QUESTION PAPER SETTERS LIST '.strtoupper($monthname['category_type']).' - '.$dyear.'</h4>
                                    <h4>'.$_SESSION['headingdate'].'</h4>
                                </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                        </tr></table> ';

                    $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>
                            <th>NAME OF THE QUESTION PAPER  SETTERS</th>
                            <th>NAME OF THE BANK</th>
                            <th>BRANCH</th>
                            <th>IFSC CODE</th>
                            <th>ACCOUNT NUMBER</th>
                            <th>AMOUNT</th>
                        </tr>
                        <tbody>"; 

                    $sl=1; $totalscript=0; $tempid='';

                    $qpamount = Yii::$app->db->createCommand("SELECT ug_amt FROM coe_val_claim_amt WHERE claim_id=3")->queryScalar();
                    foreach ($subjectdata as  $value) 
                    { 
                        $valuation_faculty1 = Yii::$app->db->createCommand("SELECT * FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['faculty1_id']."'")->queryone();

                        if($_SESSION['claimdate']!='')
                        {
                            $f1cnt = Yii::$app->db->createCommand("SELECT sum(num_question_set) FROM coe_qp_setting WHERE year=".$year." AND month=".$month." AND faculty1_id=".$value['faculty1_id'].$_SESSION['claimdate'])->queryScalar();
                        }
                        else
                        {   
                            $f1cnt = Yii::$app->db->createCommand("SELECT sum(num_question_set) FROM coe_qp_setting WHERE year=".$year." AND month=".$month." AND faculty1_id=".$value['faculty1_id'])->queryScalar();
                        }
                        

                        $totalscript=$totalscript+($f1cnt);
                        $total_renum=$qpamount*$f1cnt;

                       
                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        $body .='<td>'.$valuation_faculty1['faculty_name'].'</td>';
                        $body .='<td>'.$valuation_faculty1['bank_name'].'</td>';
                        $body .='<td>'.$valuation_faculty1['bank_branch'].'</td>';
                        $body .='<td>'.$valuation_faculty1['bank_ifsc'].'</td>';
                        $body .='<td>'.$valuation_faculty1['bank_accno'].'</td>';
                        $body .='<td>'.$total_renum.'</td>';
                        $body .='</tr>';
                        
                        $tempid=$value['faculty1_id'];
                                                
                     $sl++;

                    }

                    $body .='
            
                    <tr height="100px">

                    <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="4">Total Script : '.$totalscript.'
                      </td>

                       <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="3">Total Amount : '.($qpamount*$totalscript).'
                      </td>
                    </tr>';
                
                    $footer .='
            
                    <tr height="100px">

                    <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="7"><br>                        
                       <br>
                        <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                      </td>
                    </tr>';

                    echo  $header.$body."</tbody></table>";
                }


                if (isset($_SESSION['get_qpsetting'])) 
                {
                    unset($_SESSION['get_qpsetting']);
                                        
                }

                $send_results = $header.$body.$footer."</tbody></table>";

                $_SESSION['get_qpsetting'] = $send_results;
                 
            
            } 

            ?>
            
    </div>


    <?php ActiveForm::end(); ?>
</div>
</div>
</div>

 