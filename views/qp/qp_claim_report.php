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
$this->title = 'QP Consolidate Claim Report';
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
            
            <?= Html::a("Reset", Url::toRoute(['qp/qpclaimreport']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
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
                    $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['qp/qpclaimreport-excel'], [
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
                                     <h4>  Question Paper Setting Claim</h4>                                    
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
                            <th>Total Script</th>
                            <th>Total Remuneration(Rs.)</th>
                        </tr>
                        <tbody>"; 

                    $sl=1;
                    foreach ($qpfinsheddata as  $value) 
                    {                        

                        $f1cnt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_qp_setting WHERE  year=".$year." AND month=".$month." AND faculty1_id=".$value['coe_val_faculty_id'])->queryScalar();

                        $f2cnt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_qp_setting WHERE  year=".$year." AND month=".$month." AND faculty2_id=".$value['coe_val_faculty_id'])->queryScalar();

                        $qpamount = Yii::$app->db->createCommand("SELECT ug_amt FROM coe_val_claim_amt WHERE claim_id=4")->queryScalar();

                        $totalscript=($f1cnt+$f2cnt);
                        $total_renum=$qpamount*$totalscript;

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
                        $body .='<td>'.$totalscript.'</td>';
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

 