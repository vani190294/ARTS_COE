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
$this->title = 'QP Setting Received';
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

        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'exam_type')->widget(
                Select2::classname(), [
                    'data' => $model->ExamType,
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).' ----',
                        'name' => 'qpexam_type',
                        'id' => 'qpexam_type',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>
     
       

         <div class="col-lg-2 col-sm-2">
            <label for="slot">Set</label>
             <select id="slot" name="slot" class="form-control"> <!--  onchange="qpsettingcheckslot();" -->
                 <option value="">Select</option>
                 <option value="1">Set 1</option>
                 <option value="2">Set 2</option>
             </select>
             
        </div>    


        <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

        <div class="col-xs-12 col-sm-2 col-lg-2"><br>
            <?= Html::submitButton('Show' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'qpsettingassign' ]) ?>          
            
            <?= Html::a("Reset", Url::toRoute(['qp/qpsetting']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">

        <?php 
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $monthname = Categorytype::findOne($dmonth);
        $exam_typename = Categorytype::findOne($exam_type);
        $batch = Batch::findOne($batch);
        $batchname='';
        if(!empty($batch))
        {
            $batchname='<h4>  Batch - '.$batch['batch_name'].' </h4>';
        }

        $pquery = new Query();
                $pquery->select('coe_bat_deg_reg_id')->from('coe_bat_deg_reg A')->where(['coe_batch_id' => $batch]);
                $pgmdata = $pquery->createCommand()->queryone();

        $sem_count = ConfigUtilities::SemCaluclation($dyear,$dmonth,$pgmdata['coe_bat_deg_reg_id']);

            if(!empty($qpfinsheddata))
            {                 

                $html = "";
                $header = "";
                $body ="";
                $footer = "";                
                
                if($exam_typename['category_type']!='Arrear')
                {
                    if($slot==1)
                    {

                        $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                        $header .= '<tr>
                                    <td align="center">
                                        <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                                    <td colspan=4 align="center">
                                        <h3> 
                                          <center><b><font size="5px">' . $org_name . '</font></b></center>
                                            <center> <font size="3px">' . $org_address . '</font></center>
                                            <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                        </h3>
                                         <h4>  QP Setting Received - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                         <h4>  Batch - '.$batch['batch_name'].' Semester - '.$semester.' </h4>
                                    </td>
                                <td align="center">  
                                    <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                </td>
                            </tr></table> ';

                        $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                            <tr>
                                <th>S.No.</th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Faculty1</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                            <tbody>"; 

                        $sl=1;
                        foreach ($qpfinsheddata as  $value) 
                        { 
                       
                        
                         $f2='';

                        if($value['faculty2_id']=='NO' || $value['faculty1_id']=='NO' )
                        {
                             $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>NO</td>';
                            $body .='<td></td>';
                            $body .='</tr>';
                        
                            $sl++;
                        }
                        else
                        {
                            $clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";

                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$value['faculty1'].$clgcode.'</td>'; 

                            if($value['qp_setting_date']!="0000-00-00") 
                            {
                              $body .='<td>'.date("d-m-Y",strtotime($value['qp_setting_date'])).'</td>';  
                            }
                            else
                            {
                                $body .='<td>-</td>';
                            }
                            
                                         

                        if($value['qpstatus1']==1)
                        {
                            $body.='<td><label><input type="checkbox" id="qp_settingstatus'.$value['coe_qp_id'].'" onClick="qp_settingstatus('.$value['coe_qp_id'].');" checked>QP Received</label></td>';
                        }
                        else 
                        {
                            $body.='<td><label><input type="checkbox" id="qp_settingstatus'.$value['coe_qp_id'].'" onClick="qp_settingstatus('.$value['coe_qp_id'].');">QP Received</label></td>';
                        }

                        $body .='</tr>';
                        
                        $sl++;
                        }                  
                        

                        }

                        $footer .='
                
                        <tr height="100px"  >

                        <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="7"><br>                        
                           <br>
                            <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                          </td>
                        </tr>';

                        echo  $header.$body."</tbody></table>";
                         
                        if (isset($_SESSION['get_qpsetting'])) 
                        {
                            unset($_SESSION['get_qpsetting']);
                                                
                        }

                        $send_results = $header.$body.$footer."</tbody></table>";

                        $_SESSION['get_qpsetting'] = $send_results;
                    }
                    else if($slot==2)
                    {

                        $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                        $header .= '<tr>
                                    <td align="center">
                                        <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                                    <td colspan=4 align="center">
                                        <h3> 
                                          <center><b><font size="5px">' . $org_name . '</font></b></center>
                                            <center> <font size="3px">' . $org_address . '</font></center>
                                            <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                        </h3>
                                         <h4>  QP Setting Received - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                         <h4>  Batch - '.$batch['batch_name'].' Semester - '.$semester.' </h4>
                                    </td>
                                <td align="center">  
                                    <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                </td>
                            </tr></table> ';

                        $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                            <tr>
                                <th>S.No.</th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Faculty2</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                            <tbody>"; 

                        $sl=1;
                        foreach ($qpfinsheddata as  $value) 
                        { 
                       
                        
                         $f2='';

                        if($value['faculty2_id']=='NO' || $value['faculty1_id']=='NO' )
                        {
                             $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>NO</td>';
                            $body .='<td></td>';
                            $body .='</tr>';
                        }
                        else if(!empty($value['faculty2_id']))
                        { 
                            $valuation_faculty2 = Yii::$app->db->createCommand("SELECT faculty_name,college_code FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['faculty2_id']."'")->queryone();

                            $clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";

                            $clgcode1=($valuation_faculty2['college_code']!='')?"(".$valuation_faculty2['college_code'].")":"";

                            $f2=$valuation_faculty2['faculty_name'].$clgcode1;
                             $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$f2.'</td>'; 
                            if($value['qp_setting_date1']!="0000-00-00") 
                            {
                              $body .='<td>'.date("d-m-Y",strtotime($value['qp_setting_date1'])).'</td>';  
                            }
                            else
                            {
                                $body .='<td>-</td>';
                            }                     
                        
                                       
                        if($value['qpstatus2']==1)
                        {
                            $body.='<td><label><input type="checkbox" id="qp_settingstatus1'.$value['coe_qp_id'].'" onClick="qp_settingstatus1('.$value['coe_qp_id'].');" checked>QP Received</label></td>';
                        }
                        else 
                        {
                            $body.='<td><label><input type="checkbox" id="qp_settingstatus1'.$value['coe_qp_id'].'" onClick="qp_settingstatus1('.$value['coe_qp_id'].');">QP Received</label></td>';
                        } 
                        $body .='</tr>';
                        }                      
                     $sl++;

                        }

                        $footer .='
                
                        <tr height="100px"  >

                        <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="5"><br>                        
                           <br>
                            <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                          </td>
                        </tr>';

                        echo  $header.$body."</tbody></table>";
                         
                        if (isset($_SESSION['get_qpsetting'])) 
                        {
                            unset($_SESSION['get_qpsetting']);
                                                
                        }

                        $send_results = $header.$body.$footer."</tbody></table>";

                        $_SESSION['get_qpsetting'] = $send_results;
                    }
                    else
                    {

                        $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['qp/qpsetting-pdf'], [
                                    'class' => 'pull-right btn btn-block btn-primary',
                                    'target' => '_blank',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Will open the generated PDF file in a new window'
                            ]);
                            $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['qp/qpsetting-excel'], [
                                        'class' => 'pull-right btn btn-block btn-warning',
                                        'target' => '_blank',
                                        'data-toggle' => 'tooltip',
                                        'title' => 'Will open the generated PDF file in a new window'
                            ]);

                     echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf. " ".$print_excel . ' </div></div></div></div></div>';

                        $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                        $header .= '<tr>
                                    <td align="center">
                                        <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                                    <td colspan=4 align="center">
                                        <h3> 
                                          <center><b><font size="5px">' . $org_name . '</font></b></center>
                                            <center> <font size="3px">' . $org_address . '</font></center>
                                            <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                        </h3>
                                         <h4>  QP Setting Received - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                         <h4>  Batch - '.$batch['batch_name'].' Semester - '.$semester.' </h4>
                                    </td>
                                <td align="center">  
                                    <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                </td>
                            </tr></table> ';

                        $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                            <tr>
                                <th>S.No.</th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Faculty1</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Faculty2</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                            <tbody>"; 

                        $sl=1;
                        foreach ($qpfinsheddata as  $value) 
                        { 
                       
                        
                         $f2='';

                        if($value['faculty2_id']=='NO' || $value['faculty1_id']=='NO' )
                        {
                             $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>NO</td>';
                            $body .='<td></td>';
                            $body .='<td></td>';
                            $body .='<td>NO</td>';
                            $body .='<td></td>';
                            $body .='<td></td>';
                        }
                        else if(!empty($value['faculty2_id']))
                        { 
                            $valuation_faculty2 = Yii::$app->db->createCommand("SELECT faculty_name,college_code FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['faculty2_id']."'")->queryone();

                            $clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";

                            $clgcode1=($valuation_faculty2['college_code']!='')?"(".$valuation_faculty2['college_code'].")":"";

                            $f2=$valuation_faculty2['faculty_name'].$clgcode1;
                             $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$value['faculty1'].$clgcode.'</td>';   
                            if($value['qp_setting_date']!="0000-00-00") 
                            {
                              $body .='<td>'.date("d-m-Y",strtotime($value['qp_setting_date'])).'</td>';  
                            }
                            else
                            {
                                $body .='<td>-</td>';
                            }

                            if($value['qpstatus1']==1)
                            {
                                $body.='<td>Received</td>';
                            }
                            else 
                            {
                                $body.='<td>Not Received</td>';
                            } 

                            $body .='<td>'.$f2.'</td>'; 
                            if($value['qp_setting_date1']!="0000-00-00") 
                            {
                              $body .='<td>'.date("d-m-Y",strtotime($value['qp_setting_date1'])).'</td>';  
                            }
                            else
                            {
                                $body .='<td>-</td>';
                            }

                            if($value['qpstatus2']==1)
                            {
                                $body.='<td>Received</td>';
                            }
                            else 
                            {
                                $body.='<td>Not Received</td>';
                            } 
                        }
                        else
                        {
                            $clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";

                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$value['faculty1'].$clgcode.'</td>';  
                            if($value['qp_setting_date']!="0000-00-00") 
                            {
                              $body .='<td>'.date("d-m-Y",strtotime($value['qp_setting_date'])).'</td>';  
                            }
                            else
                            {
                                $body .='<td>-</td>';
                            }
                            if($value['qpstatus1']==1)
                            {
                                $body.='<td>Received</td>';
                            }
                            else 
                            {
                                $body.='<td>Not Received</td>';
                            } 
                            $body .='<td>'.$f2.'</td>';
                            $body .='<td></td>';
                            $body .='<td></td>'; 
                        }                     
                        $body .='</tr>';
                                                
                        $sl++;

                        }

                        $footer .='
                
                        <tr height="100px"  >

                        <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="9"><br>                        
                           <br>
                            <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                          </td>
                        </tr>';

                        echo  $header.$body."</tbody></table>";
                         
                        if (isset($_SESSION['get_qpsetting'])) 
                        {
                            unset($_SESSION['get_qpsetting']);
                                                
                        }

                        $send_results = $header.$body.$footer."</tbody></table>";

                        $_SESSION['get_qpsetting'] = $send_results;
                    }
                }
                else
                {

                    if($slot==1)
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
                                         <h4>  QP Setting Received - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                         '.$batchname.'
                                    </td>
                                <td align="center">  
                                    <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                </td>
                            </tr></table> ';

                        $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                            <tr>
                                <th>S.No.</th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Semester</th>
                                <th>Faculty1</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                            <tbody>"; 

                        $sl=1;
                        foreach ($qpfinsheddata as  $value) 
                        { 
                       
                        $clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";
                         $qry='select B.semester from coe_subjects as A JOIN coe_subjects_mapping as B ON A.coe_subjects_id=B.subject_id where A.subject_code="'.$value['subject_code'].'" AND B.semester!="'.$sem_count.'"';

                        $subjectsem = Yii::$app->db->createCommand($qry)->queryAll(); 

                        $subsem=''; $temp='';
                        foreach ($subjectsem as $subvalue) 
                        {
                            if($subvalue['semester']!=$temp)
                            {
                                $subsem.=$subvalue['semester'].',';
                            }                        
                            $temp=$subvalue['semester'];
                        }

                        $subsem=rtrim($subsem,",");

                         $f2='';

                        if($value['faculty1_id']=='NO' || $value['faculty2_id']=='NO')
                        {

                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$subsem.'</td>';
                            $body .='<td>NO</td>';  
                            $body .='<td></td>'; 
                            $body .='<td></td>';                   
                            $body .='</tr>';
                        }
                        else
                        {

                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$subsem.'</td>';
                            $body .='<td>'.$value['faculty1'].$clgcode.'</td>';                     
                            if($value['qp_setting_date']!="0000-00-00") 
                            {
                              $body .='<td>'.date("d-m-Y",strtotime($value['qp_setting_date'])).'</td>';  
                            }
                            else
                            {
                                $body .='<td>-</td>';
                            }

                            if($value['qpstatus1']==1)
                            {
                                $body.='<td><label><input type="checkbox" id="qp_settingstatus'.$value['coe_qp_id'].'" onClick="qp_settingstatus('.$value['coe_qp_id'].');" checked>QP Received</label></td>';
                            }
                            else 
                            {
                                $body.='<td><label><input type="checkbox" id="qp_settingstatus'.$value['coe_qp_id'].'" onClick="qp_settingstatus('.$value['coe_qp_id'].');">QP Received</label></td>';
                            } 
                            $body .='</tr>';
                        }

                        
                       
                                                
                     $sl++;

                        }

                        $footer .='
                
                        <tr height="100px"  >

                        <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="7"><br>                        
                           <br>
                            <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                          </td>
                        </tr>';

                        echo  $header.$body."</tbody></table>";
                         
                        if (isset($_SESSION['get_qpsetting'])) 
                        {
                            unset($_SESSION['get_qpsetting']);
                                                
                        }

                        $send_results = $header.$body.$footer."</tbody></table>";

                        $_SESSION['get_qpsetting'] = $send_results;
                    }
                    else if($slot==2)
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
                                         <h4>  QP Setting Received - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                         '.$batchname.'
                                    </td>
                                <td align="center">  
                                    <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                </td>
                            </tr></table> ';

                        $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                            <tr>
                                <th>S.No.</th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Semester</th>
                                <th>Faculty2</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                            <tbody>"; 

                        $sl=1;
                        foreach ($qpfinsheddata as  $value) 
                        { 
                       
                        $clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";
                         $qry='select B.semester from coe_subjects as A JOIN coe_subjects_mapping as B ON A.coe_subjects_id=B.subject_id where A.subject_code="'.$value['subject_code'].'" AND B.semester!="'.$sem_count.'"';

                        $subjectsem = Yii::$app->db->createCommand($qry)->queryAll(); 

                        $subsem=''; $temp='';
                        foreach ($subjectsem as $subvalue) 
                        {
                            if($subvalue['semester']!=$temp)
                            {
                                $subsem.=$subvalue['semester'].',';
                            }                        
                            $temp=$subvalue['semester'];
                        }

                        $subsem=rtrim($subsem,",");

                         $f2='';

                        if($value['faculty1_id']=='NO' || $value['faculty2_id']=='NO')
                        {

                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$subsem.'</td>';
                            $body .='<td>NO</td>';                     
                            $body .='<td></td>'; 
                             $body .='<td></td>';                  
                            $body .='</tr>';
                        }
                        else if(!empty($value['faculty2_id']))
                        { 
                            $valuation_faculty2 = Yii::$app->db->createCommand("SELECT faculty_name,college_code FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['faculty2_id']."'")->queryone();

                            $clgcode1=($valuation_faculty2['college_code']!='')?"(".$valuation_faculty2['college_code'].")":"";

                            $f2=$valuation_faculty2['faculty_name'].$clgcode1;

                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$subsem.'</td>';                   
                            $body .='<td>'.$f2.'</td>';  
                            if($value['qp_setting_date1']!="0000-00-00") 
                            {
                              $body .='<td>'.date("d-m-Y",strtotime($value['qp_setting_date1'])).'</td>';  
                            }
                            else
                            {
                                $body .='<td>-</td>';
                            }
                            if($value['qpstatus2']==1)
                            {
                                $body.='<td><label><input type="checkbox" id="qp_settingstatus1'.$value['coe_qp_id'].'" onClick="qp_settingstatus1('.$value['coe_qp_id'].');" checked>QP Received</label></td>';
                            }
                            else 
                            {
                                $body.='<td><label><input type="checkbox" id="qp_settingstatus1'.$value['coe_qp_id'].'" onClick="qp_settingstatus1('.$value['coe_qp_id'].');">QP Received</label></td>';
                            } 
                            $body .='</tr>';
                        }                        

                        
                       
                                                
                     $sl++;

                        }

                        $footer .='
                
                        <tr height="100px"  >

                        <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="7"><br>                        
                           <br>
                            <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                          </td>
                        </tr>';

                        echo  $header.$body."</tbody></table>";
                         
                        if (isset($_SESSION['get_qpsetting'])) 
                        {
                            unset($_SESSION['get_qpsetting']);
                                                
                        }

                        $send_results = $header.$body.$footer."</tbody></table>";

                        $_SESSION['get_qpsetting'] = $send_results;
                    }
                    else
                    {

                        $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                        $header .= '<tr>
                                    <td align="center">
                                        <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                                    <td colspan=6 align="center">
                                        <h3> 
                                          <center><b><font size="5px">' . $org_name . '</font></b></center>
                                            <center> <font size="3px">' . $org_address . '</font></center>
                                            <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                        </h3>
                                         <h4>  QP Setting Received - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                         '.$batchname.'
                                    </td>
                                <td align="center">  
                                    <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                </td>
                            </tr></table> ';

                        $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                            <tr>
                                <th>S.No.</th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Semester</th>
                                <th>Faculty1</th>
                                <th>Date</th>
                                 <th>Status</th>
                                <th>Faculty2</th>
                                <th>Date</th>
                                 <th>Status</th>
                            </tr>
                            <tbody>"; 

                        $sl=1;
                        foreach ($qpfinsheddata as  $value) 
                        { 
                       
                        $clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";
                         $qry='select B.semester from coe_subjects as A JOIN coe_subjects_mapping as B ON A.coe_subjects_id=B.subject_id where A.subject_code="'.$value['subject_code'].'" AND B.semester!="'.$sem_count.'"';

                        $subjectsem = Yii::$app->db->createCommand($qry)->queryAll(); 

                        $subsem=''; $temp='';
                        foreach ($subjectsem as $subvalue) 
                        {
                            if($subvalue['semester']!=$temp)
                            {
                                $subsem.=$subvalue['semester'].',';
                            }                        
                            $temp=$subvalue['semester'];
                        }

                        $subsem=rtrim($subsem,",");

                         $f2='';

                        if($value['faculty1_id']=='NO' || $value['faculty2_id']=='NO')
                        {

                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$subsem.'</td>';
                            $body .='<td>NO</td>';                     
                            $body .='<td></td>';
                            $body .='<td></td>';
                            $body .='<td>NO</td>';
                            $body .='<td></td>';    
                            $body .='<td></td>';                
                            $body .='</tr>';
                        }
                        else if(!empty($value['faculty2_id']))
                        { 
                            $valuation_faculty2 = Yii::$app->db->createCommand("SELECT faculty_name,college_code FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['faculty2_id']."'")->queryone();

                            $clgcode1=($valuation_faculty2['college_code']!='')?"(".$valuation_faculty2['college_code'].")":"";

                            $f2=$valuation_faculty2['faculty_name'].$clgcode1;

                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$subsem.'</td>';
                            $body .='<td>'.$value['faculty1'].$clgcode.'</td>';     
                            if($value['qp_setting_date']!="0000-00-00") 
                            {
                              $body .='<td>'.date("d-m-Y",strtotime($value['qp_setting_date'])).'</td>';  
                            }
                            else
                            {
                                $body .='<td>-</td>';
                            }
                             if($value['qpstatus1']==1)
                            {
                                $body.='<td>Received</td>';
                            }
                            else 
                            {
                                $body.='<td>Not Received</td>';
                            } 
                            $body .='<td>'.$f2.'</td>';      
                            if($value['qp_setting_date1']!="0000-00-00") 
                            {
                              $body .='<td>'.date("d-m-Y",strtotime($value['qp_setting_date1'])).'</td>';  
                            }
                            else
                            {
                                $body .='<td>-</td>';
                            }
                             if($value['qpstatus2']==1)
                            {
                                $body.='<td>Received</td>';
                            }
                            else 
                            {
                                $body.='<td>Not Received</td>';
                            } 
                            $body .='</tr>';
                        }
                        else
                        {

                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            $body .='<td>'.$value['subject_code'].'</td>';
                            $body .='<td>'.$value['subject_name'].'</td>';
                            $body .='<td>'.$subsem.'</td>';
                            $body .='<td>'.$value['faculty1'].$clgcode.'</td>';      
                            if($value['qp_setting_date']!="0000-00-00") 
                            {
                              $body .='<td>'.date("d-m-Y",strtotime($value['qp_setting_date'])).'</td>';  
                            }
                            else
                            {
                                $body .='<td>-</td>';
                            }
                             if($value['qpstatus1']==1)
                            {
                                $body.='<td>Received</td>';
                            }
                            else 
                            {
                                $body.='<td>Not Received</td>';
                            } 
                            $body .='<td>'.$f2.'</td>';  
                            $body .='<td></td>'; 
                            $body .='<td></td>';           
                            $body .='</tr>';
                        }

                        
                       
                                                
                     $sl++;

                        }

                        $footer .='
                
                        <tr height="100px"  >

                        <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="10"><br>                        
                           <br>
                            <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                          </td>
                        </tr>';

                        echo  $header.$body."</tbody></table>";
                         
                        if (isset($_SESSION['get_qpsetting'])) 
                        {
                            unset($_SESSION['get_qpsetting']);
                                                
                        }

                        $send_results = $header.$body.$footer."</tbody></table>";

                        $_SESSION['get_qpsetting'] = $send_results;

                    }
                }
            } 
           ?>
            
    </div>


    <?php ActiveForm::end(); ?>
</div>
</div>
</div>

