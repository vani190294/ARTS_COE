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
$this->title = 'QP Scrutiny Claim Report';
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
       

        <div class="col-xs-12 col-sm-2 col-lg-2"><br>
            <?= Html::submitButton('Show' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'qpclaimreport' ]) ?>          
            
            <?= Html::a("Reset", Url::toRoute(['qp/qpscrutinyclaimreport']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>


   
    <div class="col-lg-12 col-sm-12">

        <?php 
       
             if(!empty($qpfinsheddata))
            {
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                $monthname = Categorytype::findOne($month);
               // $exam_typename = Categorytype::findOne($exam_type);      
       

                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['qp/qpsetting-pdf'], [
                            'class' => 'pull-right btn btn-block btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                    ]);
                    $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['qp/qpscrutinyclaimreport-excel'], [
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
                
               

                    $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                    $header .= '<tr>
                                <td align="center">
                                    <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                </td>
                                <td colspan=8 align="center">
                                    <h3> 
                                      <center><b><font size="5px">' . $org_name . '</font></b></center>
                                    </h3>
                                    <h4>
                                        <center> <font size="3px">' . $org_address . '</font></center>
                                        <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                    </h4>
                                    <h4> '.$monthname['category_type'].' - '.$year.' End Semester Regular/Arrear Examinations </h4>
                                     <h4>  Question Paper Scrutiny Claim</h4>                                    
                                </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                        </tr></table> ';

                    $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Dept.</th>
                            <th>College Name</th>
                            <th>Account Number</th>                            
                            <th>IFSC Code</th>
                            <th>Bank</th>
                            <th>Branch</th>
                            <th>No. of Script</th>
                            <th>Total Remuneration(Rs.)</th>
                        </tr>
                        <tbody>"; 

                    $sl=1;
                    foreach ($qpfinsheddata as  $value) 
                    { 
                        $scrugry = Yii::$app->db->createCommand("SELECT qp_scrutiny_date,count(*) as Scriptcunt FROM coe_qp_setting WHERE qp_scrutiny_id=".$value['coe_val_faculty_id']." Group BY qp_scrutiny_date Order By qp_scrutiny_date ASC")->queryAll();   

                        $scrucnt =Yii::$app->db->createCommand("SELECT count(*) FROM coe_qp_setting WHERE qp_scrutiny_id=".$value['coe_val_faculty_id'])->queryScalar();  
                         $sq=''; 
                        foreach ($scrugry as $subvalue) 
                        {
                            
                            $sq.=$subvalue['qp_scrutiny_date'].' ('.$subvalue['Scriptcunt'].")<br>";
                                                   
                        }     

                        $qpamount = Yii::$app->db->createCommand("SELECT ug_amt,ta_amt_half_day,ta_amt_full_day FROM coe_val_claim_amt WHERE claim_id=5")->queryone();

                        $extra_amt=0;
                        if($scrucnt<=3)
                        {
                            $extra_amt=$qpamount['ta_amt_half_day'];
                        }
                        else if($scrucnt<=5)
                        {
                            $extra_amt=$qpamount['ta_amt_full_day'];
                        }
                        else if($scrucnt>5)
                        {
                            $ext_cnt=$scrucnt/5;
                            $intpart = floor( $ext_cnt );    // results in 3
                            $fraction = $ext_cnt - $intpart; // results in 0.75
                            if($fraction>0)
                            {
                                $extra_amt=$qpamount['ta_amt_full_day']*$intpart+$qpamount['ta_amt_half_day'];
                            }
                            else
                            {
                                $extra_amt=$qpamount['ta_amt_full_day']*$intpart;
                            }
                        }

                        if($value['faculty_mode']='EXTERNAL')
                        {
                            $total_renum=($qpamount['ug_amt']*($scrucnt))+$extra_amt;
                        }
                        else
                        {
                            $total_renum=($qpamount['ug_amt']*($scrucnt));
                        }
                        

                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        $body .='<td>'.$value['faculty_name'].'</td>';
                        $body .='<td>'.$value['faculty_board'].'</td>';
                        $body .='<td>'.$value['college_code'].'</td>';
                        $body .='<td>'.$value['bank_accno'].'</td>';
                        $body .='<td>'.$value['bank_ifsc'].'</td>';
                        $body .='<td>'.ucwords($value['bank_name']).'</td>';
                        $body .='<td>'.ucwords($value['bank_branch']).'</td>';
                        $body .='<td>'.$scrucnt."<br>".$sq.'</td>';
                        $body .='<td>'.$total_renum.'</td>';
                        $body .='</tr>';
                                                
                     $sl++;

                    }                    

                    echo  $header.$body."</tbody></table>";
                
                     
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

 