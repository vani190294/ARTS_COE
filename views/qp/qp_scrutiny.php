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
$this->title = 'QP Scrutiny Assign';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  
<?php if(empty($subjectdata))
        {?>
    <div class="col-xs-12 col-sm-12 col-lg-12">  

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
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>
     
        <?php if(Yii::$app->user->getId()==1 || Yii::$app->user->getId()==11)
            {?>
        <div class="col-lg-2 col-sm-2"><br>
            <label for="assign_update">
             <input type="checkbox" id="assign_update" name="assign_update">
             For Update(click)</label>
        </div>
        <?php }?>

        <div class="col-xs-12 col-sm-2 col-lg-2"><br>
            <?= Html::submitButton('Show' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'qpsettingassign' ]) ?>          
            
            <?= Html::a("Reset", Url::toRoute(['qp/qpscrutiny']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>
<?php }?>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="display:none">
            <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [
                        'data' => $model->getMonth(),
                    ]) ?>
        </div>
    </div>



     <input type="hidden" name="qpfinshed" value='<?php echo $qpfinsh; ?>'>
         <input type="hidden" name="year" value='<?php echo $dyear; ?>'>
         <input type="hidden" name="month" value='<?php echo $dmonth; ?>'>
        <input type="hidden" name="batch" value='<?php echo $batch; ?>'>
        <input type="hidden" name="exam_type" value='<?php echo $exam_type; ?>'>
   
    <div class="col-lg-12 col-sm-12">

        <?php 
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $monthname = Categorytype::findOne($dmonth);
        $exam_typename = Categorytype::findOne($exam_type);
        $batch = Batch::findOne($batch);

        if(!empty($subjectdata))
        {   
             $html = "";
                $header = "";
                $body ="";
                $footer = "";

             if($exam_typename['category_type']!='Arrear')
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
                                    <h4>  QP Scrutiny - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                    <h4>  Batch - '.$batch['batch_name'].' Semester - '.$semester.' </h4>
                                </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                        </tr><tbody> ';
                 $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Faculty1</th>
                            <th>Faculty2</th>
                            <th>Scrutiny</th>                            
                            <th>Scrutiny Date</th>
                            <th>Scrutiny Session</th>
                        </tr>
                        <tbody>";
                    $sl=1; 
                    foreach ($subjectdata as  $value) 
                    {    
                        $InsertID = $value['coe_qp_id'];            
                       
                        $scrutiny='';
                        foreach ($valuation_faculty as $value1) 
                        {
                            $clgcode=($value1['college_code']!='')?"(".$value1['college_code'].")":"";
                            
                            if($value1['coe_val_faculty_id']!=$value['faculty1_id'] && $value1['coe_val_faculty_id']!=$value['faculty2_id'])
                            {
                                if($value1['coe_val_faculty_id']==$value['qp_scrutiny_id'])
                                {
                                    $scrutiny.='<option value="'.$value1['coe_val_faculty_id'].'" selected>'.$value1['faculty_name'].$clgcode.'</option>';
                                }
                                else
                                {
                                    $scrutiny.='<option value="'.$value1['coe_val_faculty_id'].'">'.$value1['faculty_name'].$clgcode.'</option>';
                                }
                            }
                        }               

                       
                        $f2='';

                        $bgcolor='';
                        $read='';
                        if($value['qp_scrutiny_id']!=0 && $assign_update==0 && $value['status']==1){  $read=' disabled="true"'; $bgcolor='style="background-color: #ececef;"';}
                    
                        $f1clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";

                        $body .='<tr>';
                        $body .='<td '.$bgcolor.'>'.$sl.'</td>';
                        $body .='<td '.$bgcolor.'>'.$value['subject_code'].'</td>';
                        $body .='<td '.$bgcolor.'>'.$value['subject_name'].'</td>';

                         if(!empty($value['faculty2_id']))
                            { 
                                $valuation_faculty2 = Yii::$app->db->createCommand("SELECT faculty_name,college_code FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['faculty2_id']."'")->queryone();

                                $clgcode1=($valuation_faculty2['college_code']!='')?"(".$valuation_faculty2['college_code'].")":"";

                                $bgcolor1=($value['choosen_qp']==$value['faculty1_id'])?"":$bgcolor;

                                if($value['faculty1_id']==$value['choosen_qp'])
                                {
                                    $body .='<td '.$bgcolor1.'> <label>                      
                                     <input type="radio" name="f'.$InsertID.'" value="'.$value['faculty1_id'].'" id="f1radio_'.$InsertID.'" checked="checked" onclick="scrutinyupdate('.$InsertID.');"> 
                                     '.$value['faculty1'].$f1clgcode.'</label>
                                    </td>';
                                }
                                else
                                {
                                    $body .='<td '.$bgcolor1.'> <label>                      
                                     <input type="radio" name="f'.$InsertID.'" value="'.$value['faculty1_id'].'" id="f1radio_'.$InsertID.'" onclick="scrutinyupdate('.$InsertID.');"> 
                                     '.$value['faculty1'].$f1clgcode.'</label>
                                    </td>';
                                }

                                 $bgcolor1=($value['choosen_qp']==$value['faculty2_id'])?"":$bgcolor;
                                if($value['faculty2_id']==$value['choosen_qp'])
                                {
                                     $body .='<td '.$bgcolor1.'><label>
                                    <input type="radio" name="f'.$InsertID.'" value="'.$value['faculty2_id'].'" id="f2radio_'.$InsertID.'" checked="checked" onclick="scrutinyupdate('.$InsertID.');"> '.$valuation_faculty2['faculty_name'].$clgcode1.'</label></td>';
                                }
                                else
                                {
                                    $body .='<td '.$bgcolor1.'><label>
                                    <input type="radio" name="f'.$InsertID.'" value="'.$value['faculty2_id'].'" id="f2radio_'.$InsertID.'" '.$read.' onclick="scrutinyupdate('.$InsertID.');"> '.$valuation_faculty2['faculty_name'].$clgcode1.'</label></td>';
                                }
                                

                               
                                
                            }
                            else
                            {
                                $body .='<td '.$bgcolor.'>'.$value['faculty1'].$f1clgcode.'<input type="hidden" id="f1'.$InsertID.'" value='.$value['faculty1_id'].'></td>';
                                $body.='<td '.$bgcolor.'></td>';
                            }

                        
                       
                        $body .='<td '.$bgcolor.'><select class="f2" id="scrutiny_'.$InsertID.'" onchange="scrutinyupdate('.$InsertID.');" '.$read.'>
                                        <option value="">Select Scrutiny</option>'.$scrutiny.'
                                    </select></td>'; 

                        if($value['qp_scrutiny_id']!=0 && $assign_update==1)
                        {
                            if(!empty($value['qp_scrutiny_date']))
                            {
                                $body.='<td '.$bgcolor.'><input type="date" id="scrutiny_date'.$InsertID.'" value="'.$value['qp_scrutiny_date'].'" onchange="scrutinyupdate('.$InsertID.');"></td>';
                            }
                            else
                            {
                                $body.='<td '.$bgcolor.'><input type="date" id="scrutiny_date'.$InsertID.'" onchange="scrutinyupdate('.$InsertID.');"></td>';
                            }
                        }
                        else if($value['qp_scrutiny_id']==0)
                        {
                            $body.='<td '.$bgcolor.'><input type="date" id="scrutiny_date'.$InsertID.'" onchange="scrutinyupdate('.$InsertID.');"></td>';
                        }
                        else
                        {
                            $body.='<td '.$bgcolor.'>'.date('d-m-Y',strtotime($value['qp_scrutiny_date'])).'</td>';
                        }
                        

                        $fs11=''; $fs12='';

                        if($value['qp_scrutiny_session']=='FN'){ $fs11= "selected";}
                        if($value['qp_scrutiny_session']=='AN'){ $fs12= "selected";}

                        $scrutinysession='<select class="fs1" id="scrutiny_session'.$InsertID.'" onchange="scrutinyupdate('.$InsertID.');" '.$read.'>
                                    <option value="">Select Session</option>
                                    <option value="FN" '.$fs11.'>FN</option>
                                    <option value="AN" '.$fs12.'>AN</option>
                                </select>';

                        $body.='<td '.$bgcolor.'>'.$scrutinysession.'</td>';
                        
                        $body .='</tr>';
                                                
                     $sl++;
                }

                echo $send_results = $header.$body."</tbody></table>";

            }
            else
            {

                $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                $header .= '<tr>
                                <td align="center">
                                    <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                </td>
                                <td colspan=7 align="center">
                                    <h3> 
                                      <center><b><font size="5px">' . $org_name . '</font></b></center>
                                        <center> <font size="3px">' . $org_address . '</font></center>
                                        <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                    </h3>
                                    <h4>  QP Scrutiny - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                    <h4>  Batch - '.$batch['batch_name'].' </h4>
                                </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                        </tr><tbody> ';
                 $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Semester</th>
                            <th>Faculty1</th>
                            <th>Faculty2</th>
                            <th>Scrutiny</th>                            
                            <th>Scrutiny Date</th>
                            <th>Scrutiny Session</th>
                        </tr>
                        <tbody>";
                    $sl=1; 
                    foreach ($subjectdata as  $value) 
                    {    
                        $InsertID = $value['coe_qp_id'];            
                       
                        $scrutiny='';
                        foreach ($valuation_faculty as $value1) 
                        {
                            $clgcode=($value1['college_code']!='')?"(".$value1['college_code'].")":"";
                            
                            if($value1['coe_val_faculty_id']!=$value['faculty1_id'] && $value1['coe_val_faculty_id']!=$value['faculty2_id'])
                            {
                                if($value1['coe_val_faculty_id']==$value['qp_scrutiny_id'])
                                {
                                    $scrutiny.='<option value="'.$value1['coe_val_faculty_id'].'" selected>'.$value1['faculty_name'].$clgcode.'</option>';
                                }
                                else
                                {
                                    $scrutiny.='<option value="'.$value1['coe_val_faculty_id'].'">'.$value1['faculty_name'].$clgcode.'</option>';
                                }
                            }
                        }               

                       
                        $f2='';

                        $bgcolor='';
                        $read='';
                        if($value['qp_scrutiny_id']!=0 && $assign_update==0 && $value['status']==1)
                            {  $read=' disabled="true"'; $bgcolor='style="background-color: #ececef;"';}


                         $qry='select B.semester from coe_subjects as A JOIN coe_subjects_mapping as B ON A.coe_subjects_id=B.subject_id where A.subject_code="'.$value['subject_code'].'"';

                        $subjectsem = Yii::$app->db->createCommand($qry)->queryAll(); 

                        $subsem=''; $temp='';
                        foreach ($subjectsem as $subvalue) 
                        {
                            if($subvalue['semester']!=$temp && $subvalue['semester']!=$semester)
                            {
                                $subsem.=$subvalue['semester'].',';
                            }                        
                            $temp=$subvalue['semester'];
                        }

                        $subsem=rtrim($subsem,",");
                    
                        $f1clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";

                        $body .='<tr>';
                        $body .='<td '.$bgcolor.'>'.$sl.'</td>';
                        $body .='<td '.$bgcolor.'>'.$value['subject_code'].'</td>';
                        $body .='<td '.$bgcolor.'>'.$value['subject_name'].'</td>';
                        $body .='<td '.$bgcolor.'>'.$subsem.'</td>';
                         if(!empty($value['faculty2_id']))
                            { 
                                $valuation_faculty2 = Yii::$app->db->createCommand("SELECT faculty_name,college_code FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['faculty2_id']."'")->queryone();

                                $clgcode1=($valuation_faculty2['college_code']!='')?"(".$valuation_faculty2['college_code'].")":"";

                                $bgcolor1=($value['choosen_qp']==$value['faculty1_id'])?"":$bgcolor;

                                if($value['faculty1_id']==$value['choosen_qp'])
                                {
                                    $body .='<td '.$bgcolor1.'> <label>                      
                                     <input type="radio" name="f'.$InsertID.'" value="'.$value['faculty1_id'].'" id="f1radio_'.$InsertID.'" checked="checked" onclick="scrutinyupdate('.$InsertID.');"> 
                                     '.$value['faculty1'].$f1clgcode.'</label>
                                    </td>';
                                }
                                else
                                {
                                    $body .='<td '.$bgcolor1.'> <label>                      
                                     <input type="radio" name="f'.$InsertID.'" value="'.$value['faculty1_id'].'" id="f1radio_'.$InsertID.'" onclick="scrutinyupdate('.$InsertID.');"> 
                                     '.$value['faculty1'].$f1clgcode.'</label>
                                    </td>';
                                }

                                 $bgcolor1=($value['choosen_qp']==$value['faculty2_id'])?"":$bgcolor;
                                if($value['faculty2_id']==$value['choosen_qp'])
                                {
                                     $body .='<td '.$bgcolor1.'><label>
                                    <input type="radio" name="f'.$InsertID.'" value="'.$value['faculty2_id'].'" id="f2radio_'.$InsertID.'" checked="checked" onclick="scrutinyupdate('.$InsertID.');"> '.$valuation_faculty2['faculty_name'].$clgcode1.'</label></td>';
                                }
                                else
                                {
                                    $body .='<td '.$bgcolor1.'><label>
                                    <input type="radio" name="f'.$InsertID.'" value="'.$value['faculty2_id'].'" id="f2radio_'.$InsertID.'" '.$read.' onclick="scrutinyupdate('.$InsertID.');"> '.$valuation_faculty2['faculty_name'].$clgcode1.'</label></td>';
                                }
                                

                               
                                
                            }
                            else
                            {
                                $body .='<td '.$bgcolor.'>'.$value['faculty1'].$f1clgcode.'<input type="hidden" id="f1'.$InsertID.'" value='.$value['faculty1_id'].'></td>';
                                $body.='<td '.$bgcolor.'></td>';
                            }

                        
                       
                        $body .='<td '.$bgcolor.'><select class="f2" id="scrutiny_'.$InsertID.'" onchange="scrutinyupdate('.$InsertID.');" '.$read.'>
                                        <option value="">Select Scrutiny</option>'.$scrutiny.'
                                    </select></td>'; 

                        if($value['qp_scrutiny_id']!=0 && $assign_update==1)
                        {
                            if(!empty($value['qp_scrutiny_date']))
                            {
                                $body.='<td '.$bgcolor.'><input type="date" id="scrutiny_date'.$InsertID.'" value="'.$value['qp_scrutiny_date'].'" onchange="scrutinyupdate('.$InsertID.');"></td>';
                            }
                            else
                            {
                                $body.='<td '.$bgcolor.'><input type="date" id="scrutiny_date'.$InsertID.'" onchange="scrutinyupdate('.$InsertID.');"></td>';
                            }
                        }
                        else if($value['qp_scrutiny_id']==0)
                        {
                            $body.='<td '.$bgcolor.'><input type="date" id="scrutiny_date'.$InsertID.'" onchange="scrutinyupdate('.$InsertID.');"></td>';
                        }
                        else
                        {
                            $body.='<td '.$bgcolor.'>'.date('d-m-Y',strtotime($value['qp_scrutiny_date'])).'</td>';
                        }
                        

                        $fs11=''; $fs12='';

                        if($value['qp_scrutiny_session']=='FN'){ $fs11= "selected";}
                        if($value['qp_scrutiny_session']=='AN'){ $fs12= "selected";}

                        $scrutinysession='<select class="fs1" id="scrutiny_session'.$InsertID.'" onchange="scrutinyupdate('.$InsertID.');" '.$read.'>
                                    <option value="">Select Session</option>
                                    <option value="FN" '.$fs11.'>FN</option>
                                    <option value="AN" '.$fs12.'>AN</option>
                                </select>';

                        $body.='<td '.$bgcolor.'>'.$scrutinysession.'</td>';
                        
                        $body .='</tr>';
                                                
                     $sl++;
                }

                echo $send_results = $header.$body."</tbody></table>";
            }
        

      if($batchfinsheddata!=count($subjectdata) || (Yii::$app->user->getId()==1 || Yii::$app->user->getId()==11))
        {
        ?>
        

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-8 col-sm-8 col-lg-8"></div>
            <div class="col-xs-4 col-sm-4 col-lg-4">
                <?= Html::submitButton('PDF' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'qpfinshed','data-confirm' => 'Are you sure you want to submit data?' ]) ?> 
                  <?= Html::a("Back", Url::toRoute(['qp/qpscrutiny']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>      
            </div>             
        </div>



            <?php }

            } 
            else if(!empty($qpfinsheddata))
            {
                 

                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['qp/qpsetting-pdf'], [
                            'class' => 'pull-right btn btn-block btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                    ]);
                    $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['qp/qpscrutiny-excel'], [
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
                
                if($exam_typename['category_type']!='Arrear')
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
                                     <h4>  QP Scrutiny Assign - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
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
                            <th>Faculty</th>
                            <th>Scrutiny</th>
                        </tr>
                        <tbody>"; 

                    $sl=1;
                    foreach ($qpfinsheddata as  $value) 
                    {                        
                        $clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";                     
                        $scrutinyclg=($value['scrutinyclg']!='')?"(".$value['scrutinyclg'].")":"";

                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        $body .='<td>'.$value['subject_code'].'</td>';
                        $body .='<td>'.$value['subject_name'].'</td>';
                        $body .='<td>'.$value['faculty1'].$clgcode.'</td>';  
                        $body .='<td>'.$value['scrutiny'].$scrutinyclg.'</td>';                      
                        $body .='</tr>';
                                                
                     $sl++;

                    }

                    $footer .='
                
                        <tr height="100px"  >

                        <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="4"><br>                        
                           <br>
                            <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                          </td>
                        </tr>';

                    echo  $header.$body."</tbody></table>";
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
                                     <h4>  QP Scrutiny Assign - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                      <h4>  Batch - '.$batch['batch_name'].' </h4>
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
                            <th>Faculty</th>
                            <th>Scrutiny</th>
                            <th>Scrutiny Date</th>
                            <th>Scrutiny Session</th>
                        </tr>
                        <tbody>"; 

                    $sl=1;
                    foreach ($qpfinsheddata as  $value) 
                    { 
                        $f2='';
                         $clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";

                     
                         $scrutinyclg=($value['scrutinyclg']!='')?"(".$value['scrutinyclg'].")":"";
                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        $body .='<td>'.$value['subject_code'].'</td>';
                        $body .='<td>'.$value['subject_name'].'</td>';
                        $body .='<td>'.$value['semester'].'</td>';
                        $body .='<td>'.$value['faculty1'].$clgcode.'</td>';  
                        $body .='<td>'.$value['scrutiny'].$scrutinyclg.'</td>';   
                        $body .='<td>'.date("d-m-Y",strtotime($value['qp_scrutiny_date'])).'</td>';
                        $body .='<td>'.$value['qp_scrutiny_session'].'</td>';                   
                        $body .='</tr>';
                                                
                     $sl++;

                    }

                    $footer .='
                
                        <tr height="100px"  >

                        <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="6"><br>                        
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

 