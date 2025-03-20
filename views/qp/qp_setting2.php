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
echo Dialog::widget();
$this->title = 'QP Setting';
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
     
   
    
    	<div class="col-xs-12 col-sm-3 col-lg-3"><br>
 			<?= Html::submitButton('Show' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'qpsettingassign' ]) ?>          
            
            <?= Html::a("Reset", Url::toRoute(['qp/qpsetting']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
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

        if(!empty($subjectdata))
        {?>
        

            <?php   
             $html = "";
                $header = "";
                $body ="";
                $footer = "";

                $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                $header .= '<tr>
                                <td align="center">
                                    <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                </td>
                                <td colspan=2 align="center">
                                    <h3> 
                                      <center><b><font size="5px">' . $org_name . '</font></b></center>
                                        <center> <font size="3px">' . $org_address . '</font></center>
                                        <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                    </h3>
                                    <h4>  QP Setting - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                        </tr><tbody> ';
                 $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>
                            <th>Subject Code</th>
                            <th>Faculty1</th>
                            <th>Faculty2</th>
                        </tr>
                        <tbody>";
            $sl=1; 
            foreach ($subjectdata as  $value) 
            {    
                $check_inserted = QpSetting::find()->where(['qp_type'=>1, 'year'=>$dyear,'month'=>$dmonth,'subject_id'=>$value['coe_subjects_id'],'subject_code'=>$value['subject_code']])->one();
                if(empty($check_inserted))
                {
                    $created_at = date("Y-m-d H:i:s");
                    $updateBy = Yii::$app->user->getId();
                    $model1 = new QpSetting();
                    $model1->batch_id = $value['coe_batch_id'];
                    $model1->qp_type = 1;
                    $model1->year = $dyear;
                    $model1->month = $dmonth;
                    $model1->exam_type = $exam_type;
                    $model1->subject_code = $value['subject_code'];
                    $model1->subject_id = $value['coe_subjects_id'];                    
                    $model1->created_at = $created_at;
                    $model1->created_by = $updateBy;
                    $model1->save(false);

                     $InsertID = Yii::$app->db->getLastInsertID();
                }
                else
                {
                     $InsertID = $check_inserted['coe_qp_id'];
                }

               
                $faculty1='';
                foreach ($valuation_faculty as $value1) 
                {
                    $clgcode=($value1['college_code']!='')?"(".$value1['college_code'].")":"";
                    if($value1['coe_val_faculty_id']==$check_inserted['faculty1_id'])
                    {
                        $faculty1.='<option value="'.$value1['coe_val_faculty_id'].'" selected>'.$value1['faculty_name'].$clgcode.'</option>';
                    }
                    else
                    {
                        $faculty1.='<option value="'.$value1['coe_val_faculty_id'].'">'.$value1['faculty_name'].$clgcode.'</option>';
                    }
                    
                }
                $faculty2='';
                foreach ($valuation_faculty as $value1) 
                {
                     $clgcode=($value1['college_code']!='')?"(".$value1['college_code'].")":"";
                     if($value1['coe_val_faculty_id']==$check_inserted['faculty2_id'])
                    {
                        $faculty2.='<option value="'.$value1['coe_val_faculty_id'].'" selected>'.$value1['faculty_name'].$clgcode.'</option>';
                    }
                    else
                    {
                       $faculty2.='<option value="'.$value1['coe_val_faculty_id'].'">'.$value1['faculty_name'].$clgcode.'</option>';
                    }
                  
                }

                $fs11=''; $fs12='';
                $fs21=''; $fs22='';
                if($check_inserted['faculty1_session']==1){ $fs11= "selected";}
                if($check_inserted['faculty1_session']==2){ $fs12= "selected";}
                if($check_inserted['faculty2_session']==1){ $fs21= "selected";}
                if($check_inserted['faculty2_session']==2){ $fs22= "selected";}

                $faculty1session='<select class="fs1" id="fs1_'.$InsertID.'" onchange="fs1update('.$InsertID.');">
                                    <option value="">Select Faculty1 Session</option>
                                    <option value="1" '.$fs11.'>Half Day</option>
                                    <option value="2" '.$fs12.'>Full Day</option>
                                </select>';

                $faculty2session='<select class="fs2" id="fs2_'.$InsertID.'" onchange="fs2update('.$InsertID.');">
                                    <option value="">Select Faculty2 Session</option>
                                    <option value="1"  '.$fs21.'>Half Day</option>
                                    <option value="2"  '.$fs22.'>Full Day</option>
                                </select>';


                $body .='<tr>';
                $body .='<td>'.$sl.'</td>';
                $body .='<td>'.$value['subject_code'].'<input type="hidden" id="subject_id'.$InsertID.'" value='.$value['coe_subjects_id'].'></td>';
                $body .='<td>
                            <select class="f1" id="f1_'.$InsertID.'" onchange="faculty1update('.$InsertID.');">
                                <option value="">Select Faculty1</option>'.$faculty1.'
                            </select>
                        </td>';
                $body .='<td>'.$faculty1session.'</td>'; 
                $body .='<td><select class="f2" id="f2_'.$InsertID.'" onchange="faculty2update('.$InsertID.');">
                                <option value="">Select Faculty2</option>'.$faculty2.'
                            </select></td>'; 
                $body .='<td>'.$faculty2session.'</td>';  
                $body .='</tr>';
                                        
             $sl++;
        }

      echo $send_results = $header.$body."</tbody></table>";
        


        ?>
        

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-8 col-sm-8 col-lg-8"></div>
            <div class="col-xs-4 col-sm-4 col-lg-4">
                <?= Html::submitButton('Finish' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'qpfinshed','data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once the values were Submitted.' ]) ?> 
                  <?= Html::a("Reset", Url::toRoute(['qp/qpsetting']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>      
            </div>             
        </div>



            <?php

            } 
            else if(!empty($qpfinsheddata))
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
                $html = "";
                $header = "";
                $body ="";
                $footer = "";                
                

                $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                $header .= '<tr>
                                <td colspan=2 align="center">
                                    <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                </td>
                                <td colspan=3 align="center">
                                    <h3> 
                                      <center><b><font size="5px">' . $org_name . '</font></b></center>
                                        <center> <font size="3px">' . $org_address . '</font></center>
                                        <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                    </h3>
                                     <h4>  QP Setting - '.$monthname['category_type'].' - '.$dyear.' '.$exam_typename['category_type'].' Examinations </h4>
                                </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                        </tr></table> ';

                $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>
                            <th>Subject Code</th>
                            <th>Faculty1</th>
                            <th>Faculty1 Session</th>
                            <th>Faculty2</th>
                            <th>Faculty2 Session</th>
                        </tr>
                        <tbody>"; 

                $sl=1;
                foreach ($qpfinsheddata as  $value) 
                { 
                    $faculty1session=''; $f2= $faculty2session='';                    
               
                    if($value['faculty1_session']==1){ $faculty1session= "Half Day";}
                    if($value['faculty1_session']==2){ $faculty1session= "Full Day";}

                     $clgcode=($value['college_code']!='')?"(".$value['college_code'].")":"";

                    if(!empty($value['faculty2_id']))
                    { 
                        $valuation_faculty2 = Yii::$app->db->createCommand("SELECT faculty_name,college_code FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['faculty2_id']."'")->queryone();

                        $clgcode1=($valuation_faculty2['college_code']!='')?"(".$valuation_faculty2['college_code'].")":"";

                        $f2=$valuation_faculty2['faculty_name'].$clgcode1;
                    }
                    if($value['faculty2_session']==1){ $faculty2session= "Half Day";}
                    if($value['faculty2_session']==2){ $faculty2session= "Full Day";}

                    $body .='<tr>';
                    $body .='<td>'.$sl.'</td>';
                    $body .='<td>'.$value['subject_code'].'</td>';
                    $body .='<td>'.$value['faculty1'].$clgcode.'</td>';
                    $body .='<td>'.$faculty1session.'</td>'; 
                    $body .='<td>'.$f2.'</td>'; 
                    $body .='<td>'.$faculty2session.'</td>';  
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
                     
                if (isset($_SESSION['get_print_qpassign'])) 
                {
                    unset($_SESSION['get_print_qpassign']);
                                        
                }

                $send_results = $header.$body.$footer."</tbody></table>";

                $_SESSION['get_print_qpassign'] = $send_results;
                 
            ?>



            <?php

            } 

            ?>
            
    </div>


    <?php ActiveForm::end(); ?>
</div>
</div>
</div>

 public function actionQpsetting1()
    {
        $subjectdata=array_filter(['']);
        $model = new QpSetting();
        if (Yii::$app->request->post()) 
        { 
           $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

            if($_POST['qpfinshed']==1)
            {
                $created_at = date("Y-m-d H:i:s");
                $updateBy = Yii::$app->user->getId();

                if($_POST['exam_type']==28)
                {
                    $month=($_POST['month']=='29')?'30':'29';
                    $year=($_POST['month']==29)?($_POST['year']-1):$_POST['year'];
                }
                else
                {
                     $month=$_POST['month'];
                     $year=$_POST['year'];
                }

                $dmonth=$_POST['month'];
                $dyear=$_POST['year'];               
                $batch_id=$_POST['batch'];
                $exam_type =$_POST['exam_type'];

                $update = Yii::$app->db->createCommand('UPDATE coe_qp_setting SET status=1, updated_at="'.$created_at.'", updated_by="'.$updateBy.'" WHERE batch_id="'.$batch_id.'" AND qp_type=1 AND exam_type ="'.$exam_type.'" AND faculty1_id!=""')->execute();

                if($update)
                {

                    $batchdata = Yii::$app->db->createCommand("select count(*) from coe_qp_setting where year='".$dyear."' AND  month='".$dmonth."' AND  batch_id='".$batch_id."' AND exam_type ='".$exam_type."' AND qp_type=1")->queryScalar();

                    $batchfinsheddata = Yii::$app->db->createCommand("select count(*) from coe_qp_setting where year='".$dyear."' AND  month='".$dmonth."' AND exam_type ='".$exam_type."' AND batch_id='".$batch_id."' AND status=1 AND qp_type=1")->queryScalar();

                    if($batchfinsheddata==$batchdata && $batchfinsheddata!=0) // finish qp
                    {
                        $qpfinshed = new Query();
                         $qpfinshed->select('A.*, C.faculty_name as faculty1,C.college_code')
                                ->from('coe_qp_setting A')
                                ->join('JOIN', 'coe_valuation_faculty C', 'C.coe_val_faculty_id=A.faculty1_id')
                                ->where(['A.exam_type' => $exam_type, 'A.qp_type' => 1, 'A.status' => 1, 'A.year' => $dyear, 'A.month' => $dmonth, 'A.batch_id' => $batch_id]);
                        $qpfinsheddata = $qpfinshed->createCommand()->queryAll();   

                         Yii::$app->ShowFlashMessages->setMsg('Success','QP Faculty assigneds Successfully');
                        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to QP Setting');

                         return $this->render('qp_setting', [
                        'model' => $model,
                        'subjectdata' =>'',
                        'qpfinsheddata'=>$qpfinsheddata,
                        'qpfinsh'=>'',
                        'year'=>$year,
                        'month'=>$month,
                        'batch'=>$batch_id,
                        'exam_type'=>$exam_type,
                            'dyear'=>$dyear,
                            'dmonth'=>$dmonth,
                         ]);
                    }
                    else // not finish qp
                    {

                        $pract_id = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where coe_category_type_id!='137' AND category_type like '%Theory%'")->queryAll();
                       //print_r($pract_id);exit();
                        foreach ($pract_id as $key => $value) {
                           $pracIds[$value['coe_category_type_id']]=$value['coe_category_type_id'];
                        }


                        $pquery = new Query();
                        $pquery->select('coe_bat_deg_reg_id')->from('coe_bat_deg_reg A')->where(['coe_batch_id' => $batch_id]);
                        $pgmdata = $pquery->createCommand()->queryone();
                       
                        if(!empty($pgmdata))
                        {

                            $sem_count = ConfigUtilities::SemCaluclation($year,$month,$pgmdata['coe_bat_deg_reg_id']);

                            $query = new Query();
                            if($exam_type==27)
                            {
                                $query->select('DISTINCT (D.subject_code),A.coe_batch_id,F.batch_name,D.subject_name,D.coe_subjects_id')
                                    ->from('coe_bat_deg_reg A')
                                    ->join('JOIN', 'coe_programme B', 'A.coe_programme_id=B.coe_programme_id')
                                    ->join('JOIN', 'coe_degree C', 'A.coe_degree_id=C.coe_degree_id')
                                    ->join('JOIN', 'coe_subjects_mapping E', 'A.coe_bat_deg_reg_id=E.batch_mapping_id')
                                    ->join('JOIN', 'coe_subjects D', 'E.subject_id=D.coe_subjects_id')
                                    ->join('JOIN', 'coe_category_type G', 'G.coe_category_type_id=E.paper_type_id')
                                    ->join('JOIN', 'coe_category_type H', 'H.coe_category_type_id=E.subject_type_id')
                                    ->join('JOIN', 'coe_category_type I', 'I.coe_category_type_id=E.course_type_id')
                                     ->join('JOIN', 'coe_category_type j', 'j.coe_category_type_id=E.type_id')
                                    ->join('JOIN', 'coe_batch F', 'A.coe_batch_id=F.coe_batch_id')
                                    ->where(['A.coe_batch_id' => $batch_id, 'E.semester' => $sem_count])
                                    ->andWhere(['IN', 'paper_type_id', $pracIds])->orderBy('subject_code');
                                    // echo $query->createCommand()->getrawsql(); exit;
                                $subjectdata = $query->createCommand()->queryAll();
                            }
                            else
                            {
                                $pracIds1='';
                                foreach ($pract_id as $key => $value) {
                                   $pracIds1.=$value['coe_category_type_id'].',';
                                }

                                $practid='';
                                if(!empty($pract_id))
                                {
                                    $pracIds1=rtrim($pracIds1,',');

                                    $practid='AND paper_type_id IN ('.$pracIds1.')';
                                }

                                $qry='select DISTINCT (D.subject_code),I.coe_batch_id,I.batch_name,D.subject_name,D.coe_subjects_id 
                                    from coe_mark_entry_master as A 
                                    JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                                    JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                                    JOIN coe_subjects as D ON D.coe_subjects_id=C.subject_id 
                                    JOIN coe_student as E ON E.coe_student_id=B.student_rel_id 
                                    JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                                    JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id JOIN coe_programme as H ON H.coe_programme_id=F.coe_programme_id 
                                    JOIN coe_batch as I ON I.coe_batch_id=F.coe_batch_id
                                where year_of_passing="" and subject_map_id NOT IN(select subject_map_id from coe_mark_entry_master
                                where student_map_id=A.student_map_id and result like "%Pass%") and 
                                F.coe_batch_id="'.$batch_id.'" and I.coe_batch_id="'.$batch_id.'" AND A.month="'.$month.'" AND A.year="'.$year.'"'.$practid.' AND status_category_type_id NOT IN('.$det_disc_type.') group by D.subject_code';
                               $subjectdata = Yii::$app->db->createCommand($qry)->queryAll();                       
                               
                            }                                   
                           
                        }

                         $valuation_faculty = Yii::$app->db->createCommand("SELECT coe_val_faculty_id,faculty_name,college_code FROM coe_valuation_faculty ORDER BY coe_val_faculty_id")->queryAll();

                          Yii::$app->ShowFlashMessages->setMsg('Warning','Some QP Faculty not assigned please check');

                        return $this->render('qp_setting', [
                        'model' => $model,
                        'subjectdata' => $subjectdata,
                        'valuation_faculty'=>$valuation_faculty,
                        'qpfinsh'=>1,
                        'year'=>$year,
                        'month'=>$month,
                        'batch'=>$batch_id,
                        'exam_type'=>$exam_type,
                            'dyear'=>$dyear,
                            'dmonth'=>$dmonth,
                    ]);
                    }
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "No Finish Status Update! Please Check");
                    return $this->redirect(['qp/qp_setting']);
                }
            }
            else
            { 
                 if($_POST['qpexam_type']==28)
                {
                    $month=($_POST['qpassign_month']=='29')?'30':'29';
                    $year=($_POST['qpassign_month']==29)?($_POST['qp_year']-1):$_POST['qp_year'];
                }
                else
                {
                     $month=$_POST['qpassign_month'];
                     $year=$_POST['qp_year'];
                }

                $dmonth=$_POST['qpassign_month'];
                $dyear=$_POST['qp_year'];
                $batch_id=$_POST['bat_val'];
                $exam_type =$_POST['qpexam_type'];

                $batchdata = Yii::$app->db->createCommand("select count(*) from coe_qp_setting where year='".$dyear."' AND  month='".$dmonth."' AND  batch_id='".$batch_id."' AND exam_type ='".$exam_type."' AND qp_type=1")->queryScalar();

                $batchfinsheddata = Yii::$app->db->createCommand("select count(*) from coe_qp_setting where year='".$dyear."' AND  month='".$dmonth."' AND  batch_id='".$batch_id."' AND exam_type ='".$exam_type."' AND  status=1 AND qp_type=1")->queryScalar();

                if($batchfinsheddata==$batchdata && $batchfinsheddata!=0) // finish qp
                {
                        $qpfinshed = new Query();
                         $qpfinshed->select('A.*, C.faculty_name as faculty1,C.college_code')
                                ->from('coe_qp_setting A')
                                ->join('JOIN', 'coe_valuation_faculty C', 'C.coe_val_faculty_id=A.faculty1_id')
                                ->where(['A.exam_type' => $exam_type, 'A.qp_type' => 1, 'A.status' => 1, 'A.year' => $dyear, 'A.month' => $dmonth, 'A.batch_id' => $batch_id]);
                        $qpfinsheddata = $qpfinshed->createCommand()->queryAll();   

                        $_SESSION['qpfinsheddata'] = $send_results;

                        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to QP Setting');

                         return $this->render('qp_setting', [
                        'model' => $model,
                        'subjectdata' =>'',
                        'qpfinsheddata'=>$qpfinsheddata,
                        'qpfinsh'=>'',
                        'year'=>$year,
                        'month'=>$month,
                        'batch'=>$batch_id,
                        'exam_type'=>$exam_type,
                            'dyear'=>$dyear,
                            'dmonth'=>$dmonth,
                         ]);
                }
                else // not finish qp or new
                {
                    $pract_id = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where coe_category_type_id!='137' AND category_type like '%Theory%'")->queryAll();
                   //print_r($pract_id);exit();
                    foreach ($pract_id as $key => $value) {
                       $pracIds[$value['coe_category_type_id']]=$value['coe_category_type_id'];
                    }


                    $pquery = new Query();
                    $pquery->select('coe_bat_deg_reg_id')->from('coe_bat_deg_reg A')->where(['coe_batch_id' => $batch_id]);
                    $pgmdata = $pquery->createCommand()->queryone();
                   
                    if(!empty($pgmdata))
                    {

                      $sem_count = ConfigUtilities::SemCaluclation($year,$month,$pgmdata['coe_bat_deg_reg_id']);

                        $query = new Query();

                        if($exam_type==27)
                        {
                            $query->select('DISTINCT (D.subject_code),A.coe_batch_id,F.batch_name,D.subject_name,D.coe_subjects_id')
                                ->from('coe_bat_deg_reg A')
                                ->join('JOIN', 'coe_programme B', 'A.coe_programme_id=B.coe_programme_id')
                                ->join('JOIN', 'coe_degree C', 'A.coe_degree_id=C.coe_degree_id')
                                ->join('JOIN', 'coe_subjects_mapping E', 'A.coe_bat_deg_reg_id=E.batch_mapping_id')
                                ->join('JOIN', 'coe_subjects D', 'E.subject_id=D.coe_subjects_id')
                                ->join('JOIN', 'coe_category_type G', 'G.coe_category_type_id=E.paper_type_id')
                                ->join('JOIN', 'coe_category_type H', 'H.coe_category_type_id=E.subject_type_id')
                                ->join('JOIN', 'coe_category_type I', 'I.coe_category_type_id=E.course_type_id')
                                 ->join('JOIN', 'coe_category_type j', 'j.coe_category_type_id=E.type_id')
                                ->join('JOIN', 'coe_batch F', 'A.coe_batch_id=F.coe_batch_id')
                                ->where(['A.coe_batch_id' => $batch_id, 'E.semester' => $sem_count])
                                ->andWhere(['IN', 'paper_type_id', $pracIds])->orderBy('subject_code');
                                // echo $query->createCommand()->getrawsql(); exit;
                            $subjectdata = $query->createCommand()->queryAll();
                        }
                        else
                        {
                            $pracIds1='';
                            foreach ($pract_id as $key => $value) {
                               $pracIds1.=$value['coe_category_type_id'].',';
                            }

                            $practid='';
                            if(!empty($pract_id))
                            {
                                $pracIds1=rtrim($pracIds1,',');

                                $practid='AND paper_type_id IN ('.$pracIds1.')';
                            }

                            $qry='select DISTINCT (D.subject_code),I.coe_batch_id,I.batch_name,D.subject_name,D.coe_subjects_id 
                                from coe_mark_entry_master as A 
                                JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                                JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                                JOIN coe_subjects as D ON D.coe_subjects_id=C.subject_id 
                                JOIN coe_student as E ON E.coe_student_id=B.student_rel_id 
                                JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                                JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id JOIN coe_programme as H ON H.coe_programme_id=F.coe_programme_id 
                                JOIN coe_batch as I ON I.coe_batch_id=F.coe_batch_id
                            where year_of_passing="" and subject_map_id NOT IN(select subject_map_id from coe_mark_entry_master
                            where student_map_id=A.student_map_id and result like "%Pass%") and 
                            F.coe_batch_id="'.$batch_id.'" and I.coe_batch_id="'.$batch_id.'" AND A.month="'.$month.'" AND A.year="'.$year.'"'.$practid.' AND status_category_type_id NOT IN('.$det_disc_type.') group by D.subject_code';//exit;
                           $subjectdata = Yii::$app->db->createCommand($qry)->queryAll();                       
                           
                        }
                                                         
                       
                    }

                   $valuation_faculty = Yii::$app->db->createCommand("SELECT coe_val_faculty_id,faculty_name,college_code FROM coe_valuation_faculty ORDER BY coe_val_faculty_id")->queryAll();
                     
                    Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to QP Setting');

                    if (!empty($subjectdata)) {
                        return $this->render('qp_setting', [
                            'model' => $model,
                            'subjectdata' => $subjectdata,
                            'valuation_faculty'=>$valuation_faculty,
                            'qpfinsh'=>1,
                            'year'=>$year,
                            'month'=>$month,
                            'batch'=>$batch_id,
                            'exam_type'=>$exam_type,
                            'dyear'=>$dyear,
                            'dmonth'=>$dmonth,
                        ]);
                    } else {
                        Yii::$app->ShowFlashMessages->setMsg('Error', "No data Found");
                        return $this->redirect(['qp/qp_setting']);
                    }
                }
            }
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to QP Setting');
            return $this->render('qp_setting', [
                'model' => $model,
                'subjectdata' => $subjectdata,
                'qpfinsh'=>'',
                'dyear'=>'',
                'dmonth'=>'',
                'batch'=>'',
                'exam_type'=>'',
            ]);
        }
       
    }