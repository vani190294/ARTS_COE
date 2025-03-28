<?php
namespace app\controllers;

use Yii;
use app\models\Categorytype;
use app\models\CategorytypeSearch;
use app\models\MarkEntry;
use app\models\SubjectsMapping;
use app\models\Subjects;
use app\models\StudentMapping;
use app\models\Student;
use app\models\MarkEntryMaster;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\db\Query;
use kartik\mpdf\Pdf;
/**
 * CategorytypeController implements the CRUD actions for Categorytype model.
 */
class CategorytypeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Categorytype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorytypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categorytype model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /**
     * Displays a single Categorytype model.
     * @param integer $id
     * @return mixed
     */
    public function actionToppersList()
    {
        $model = new MarkEntry();
        Yii::$app->ShowFlashMessages->setMsg('Welcome', 'Welcome to Toppers List ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT));
        return $this->render('toppers-list', [
            'model' => $model,
        ]);
    }
    public function actionStudentArrearCountWithFee()
    {
        $model = new MarkEntry();
        $markentrymaster = new MarkEntryMaster();
        Yii::$app->ShowFlashMessages->setMsg('Welcome', 'Welcome to Student Arrear Count Fee ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT));
        return $this->render('student-arrear-count-with-fee', [
            'model' => $model,
        ]);
    }
    public function actionCountSingleAttempt()
    {
        $model = new MarkEntry();
        $markentrymaster = new MarkEntryMaster();
        Yii::$app->ShowFlashMessages->setMsg('Welcome', 'Welcome to Single Attempt Count ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_REPORT));
        return $this->render('count-single-attempt', [
            'model' => $model,
        ]);
    }
    public function actionMarkPercentPdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $content = $_SESSION['singlAttemprPass'];
        //unset($_SESSION['student_application_date']);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'Single Attempt Pass Count.pdf',
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => ' @media all{
                        table{border: none; font-family:"Roboto, sans-serif"; width:100%; font-size: 12.5px; }
                    }',
            'options' => ['title' => 'Single Attempt Pass Count'],
            'methods' => [
                'SetHeader' => ["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name],
                'SetFooter' => ['Single Attempt Pass Count' . ' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
            ]
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }
    public function actionExcelMarkpercent()
    {        
        $content = $_SESSION['singlAttemprPass'];            
        $fileName = "Single Attempt Pass Count " . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    public function actionToppersListPdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $content = $_SESSION['singlAttemprPass'];
        //unset($_SESSION['student_application_date']);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'Toppers List.pdf',
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => ' @media all{
                        table{border-collapse: collapse; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width:100%; font-size: 12px !important;  }
                        table td{
                            border: 1px solid #000;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: left;
                        }
                        table th{
                           border: 1px solid #000;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: left;
                           
                        }
                    }   
                ', 
            'options' => ['title' => 'Toppers List'],
            'methods' => [
                'SetHeader' => ["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name],
                'SetFooter' => ['Toppers List' . ' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
            ]
        ]);
        $pdf->marginTop = "10";
        $pdf->marginLeft = "7";
        $pdf->marginRight = "7";
        $pdf->marginBottom = "7";
        $pdf->marginHeader = "4";
        $pdf->marginFooter = "4";
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }
    public function actionExcelTopperslist()
    {        
        $content = $_SESSION['singlAttemprPass'];            
        $fileName = "Toppers List" . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    public function actionViewInternals()
    {
        $markEntry = new MarkEntry();
        $markentrymaster = new MarkEntryMaster();
        if (Yii::$app->request->post()) 
        {
            $stu_map_ids = $_POST['reg_number'];
            $sub_map_id = $_POST['sub_val'];
            $semester = $_POST['exam_semester'];
            $month = $_POST['month'];
            $year = $_POST['MarkEntry']['year'];
            $term = $_POST['MarkEntry']['term'];
            $mark_type = $_POST['MarkEntry']['mark_type'];
            $category_type_id = Categorytype::find()->where(['description'=>'ESE'])->one();
            $category_dum_type_id = Categorytype::find()->where(['description'=>'ESE(Dummy)'])->one();
            $category_type_cia_id = Categorytype::find()->where(['description'=>'CIA'])->one();
            $bat_map_val = $_POST['bat_map_val'];
            $attendance_percentage = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT_STATUS);
            $created_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId();
            $status_check = 0;

            $checkResStat = ConfigUtilities::getResultPublishStatus($year,$month);
            if($checkResStat==1)
            {
                Yii::$app->ShowFlashMessages->setMsg('ERROR','UNABLE TO UPDATE THE MARKS RESULTS ALREADY PUBLISHED');
                return $this->redirect(['view-internals']);
            }
            for ($i=0; $i <count($stu_map_ids) ; $i++) 
            { 
                $MarkEntry = new MarkEntry();
                $MarkEntryMaster = new MarkEntryMaster();
                if(isset($_POST['reg_number'][$i]) && isset($_POST['ese_marks'][$i]) && $_POST['ese_marks'][$i]!='')
                {
                    $mark_entry_check = MarkEntry::find()->where(['student_map_id' => $_POST['reg_number'][$i], 'subject_map_id' => $sub_map_id, 'category_type_id' => $category_type_cia_id['coe_category_type_id'], 'year' => $year,'month'=>$month,'term'=>$term])->one();
                    $check_resu_status = MarkEntryMaster::findOne(['year'=>$year,'month'=>$month]);
                    if(!empty($mark_entry_check) && $check_resu_status->status_id==0)
                    {
                       $update_mark_entry = Yii::$app->db->createCommand('UPDATE coe_mark_entry set category_type_id_marks="'.$_POST['ese_marks'][$i].'",updated_by="'.$updateBy.'",updated_at="'.$created_at.'" where subject_map_id="'.$sub_map_id.'" and student_map_id="'.$_POST['reg_number'][$i].'" and category_type_id="'.$category_type_cia_id['coe_category_type_id'].'" and year="'.$year.'"')->execute();
                       $status_check = 1;
                    } 
                    $mark_master_entry_check = MarkEntryMaster::find()->where(['student_map_id' => $_POST['reg_number'][$i], 'subject_map_id' => $sub_map_id, 'year' => $year,'month'=>$month,'term'=>$term,'mark_type'=>$mark_type])->one();
                    if(!empty($mark_master_entry_check) && $check_resu_status->status_id==0 )
                    {
                        $getStuMarks = MarkEntry::find()->where(['student_map_id' => $_POST['reg_number'][$i], 'subject_map_id' => $sub_map_id, 'year' => $year,'month'=>$month,'term'=>$term,'mark_type'=>$mark_type])->andWhere(['IN','category_type_id',[$category_dum_type_id,$category_type_id]])->one();

                        $stu_result_data = ConfigUtilities::StudentResult($_POST['reg_number'][$i],$sub_map_id,$_POST['ese_marks'][$i],$getStuMarks['category_type_id_marks'],$year,$month);
                        $pas_status = $stu_result_data['result']=='Pass' || $stu_result_data['result']=='pass' || $stu_result_data['result']=='PASS' ? 'YES' : 'NO';
                        $year_of_passing = $pas_status=='YES' ? $month.'-'.$year : '';
                        
                       $update_mark_entry_master = Yii::$app->db->createCommand('UPDATE coe_mark_entry_master set CIA="'.$_POST['ese_marks'][$i].'",ESE="'.$stu_result_data['ese_marks'].'",total="'.$stu_result_data['total_marks'].'",result="'.$stu_result_data['result'].'",grade_point="'.$stu_result_data['grade_point'].'",grade_name="'.$stu_result_data['grade_name'].'",year_of_passing="'.$year_of_passing.'",updated_by="'.$updateBy.'",updated_at="'.$created_at.'" where subject_map_id="'.$sub_map_id.'" and student_map_id="'.$_POST['reg_number'][$i].'" and mark_type="'.$mark_type.'" and month="'.$month.'" and term="'.$term.'" and year="'.$year.'"')->execute();
                       $status_check = 2;
                    }
                    if($check_resu_status->status_id==1)
                    {
                        $status_check = 3;
                    }
                    
                }
            }// For Loop
            if($status_check==1)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success','Internal Marks Updated Successfully!!!');
            }
            else if($status_check==2)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success','Internal & External Marks Updated Successfully!!!');
            }
            else if($status_check==3)
            {
               Yii::$app->ShowFlashMessages->setMsg('ERROR','UNABLE TO UPDATE THE MARKS RESULTS ALREADY PUBLISHED');
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('ERROR','NOTHING HAS UPDATED');
            }   
            return $this->redirect(['view-internals']);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Arrear Internal Mode Mark Entry');
            return $this->render('view-internals', [
                    'markEntry' => $markEntry,                    
                    'markentrymaster' => $markentrymaster,
               ]);
        }
        
    }
    /**
     * Creates a new Categorytype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdateOldGrade()
    {
        $markEntry = new MarkEntry();
        $markentrymaster = new MarkEntryMaster();
        
        if (Yii::$app->request->post()) 
        {
            if(isset($_POST['grade_change']))
            {
                
                $updated_at = date("Y-m-d H:i:s");
                $updated_by = Yii::$app->user->getId();
                $total_subs = count($_POST['grade_change']);
                $count=0;
                for ($i=0; $i <$total_subs; $i++) 
                { 
                    $stu_map_id = $_POST['reg_number'][$i];
                    $sub_map_id = $_POST['sub_map_id'][$i];
                    $year = $_POST['year'][$i];
                    $month = $_POST['month'][$i];
                    $mark_type = $_POST['mark_type'][$i];
                    $term = $_POST['term'][$i];
                    $grade_change = $_POST['grade_change'][$i];
                    $checkData = MarkEntryMaster::find()->where(['year'=>$year,'month'=>$month,'student_map_id'=>$stu_map_id,'subject_map_id'=>$sub_map_id,'mark_type'=>$mark_type,'term'=>$term])->one();
                    $connection = Yii::$app->db;
                    if(!empty($checkData))
                    {
                        $command = $connection->createCommand('UPDATE coe_mark_entry_master SET grade_name="'.$grade_change.'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_mark_entry_master_id="'.$checkData->coe_mark_entry_master_id.'" ');
                        if($command->execute())
                        {
                            $count = $count+1;
                        }
                    }
                }
                if($count!=0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('SUCCESS',$_POST['register_number'].' '.$_POST['subject_code'].' UPDATED Successfully');
                        return $this->redirect(['update-old-grade']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('ERROR','NO DATA FOUND');
                        return $this->redirect(['update-old-grade']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('ERROR','UNABLE TO UPDATE NO DATA FOUND');
                return $this->redirect(['update-old-grade']);
            }

        }

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Grade Upadation');
        return $this->render('update-old-grade', [
                'markEntry' => $markEntry,                    
                'markentrymaster' => $markentrymaster,
           ]);
    }
    /**
     * Creates a new Categorytype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInternalArrearMarkEntry()
    {
        $markEntry = new MarkEntry();
        $markentrymaster = new MarkEntryMaster();
        
        if (Yii::$app->request->post()) 
        {
            $stu_map_ids = $_POST['reg_number'];
            $sub_map_id = $_POST['sub_val'];
            $semester = $_POST['exam_semester'];
            $month = $_POST['month'];
            $year = $_POST['MarkEntry']['year'];
            $term = $_POST['MarkEntry']['term'];
            $mark_type = $_POST['MarkEntry']['mark_type'];
            $category_type_id = Categorytype::find()->where(['description'=>'ESE'])->one();
            $category_type_cia_id = Categorytype::find()->where(['description'=>'CIA'])->one();
            $bat_map_val = $_POST['bat_map_val'];
            $attendance_percentage = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT_STATUS);
            $created_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId();
            $insertData = [];

            for ($i=0; $i <count($stu_map_ids) ; $i++) 
            { 
                $MarkEntry = new MarkEntry();
                $MarkEntryMaster = new MarkEntryMaster();

                if(isset($_POST['reg_number'][$i]))
                {
                    $GETsUBInfo = SubjectsMapping::findOne($sub_map_id);
                    $subjectDetails = Subjects::findOne($GETsUBInfo->subject_id);
                    $MarkEntry->student_map_id = $_POST['reg_number'][$i];
                    $MarkEntry->subject_map_id = $sub_map_id;
                    $MarkEntry->category_type_id = $category_type_cia_id['coe_category_type_id'];
                    $MarkEntry->category_type_id_marks = $_POST['ese_marks'][$i];
                    $MarkEntry->year = $year;
                    $MarkEntry->month = $month;
                    $MarkEntry->term = $term;
                    $MarkEntry->mark_type = $mark_type;
                    $MarkEntry->status_id = 0;
                    $MarkEntry->attendance_percentage = $attendance_percentage;
                    $MarkEntry->attendance_remarks = 'Allowed';
                    $MarkEntry->created_at = $created_at;
                    $MarkEntry->created_by = $updateBy;
                    $MarkEntry->updated_at = $created_at;
                    $MarkEntry->updated_by = $updateBy;

                    $mark_entry_check = MarkEntry::find()->where(['student_map_id' => $_POST['reg_number'][$i], 'subject_map_id' => $sub_map_id, 'category_type_id' => $category_type_cia_id['coe_category_type_id'], 'year' => $year,'month'=>$month,'term'=>$term])->one();
                    
                    if(empty($mark_entry_check))
                    {
                        $MarkEntry->save(false);
                    }                    
                    unset($MarkEntry);

                    $stu_result_data = ConfigUtilities::StudentResult($_POST['reg_number'][$i],$sub_map_id,$_POST['ese_marks'][$i],0,$year,$month);

                    $pas_status = $stu_result_data['result']=='Pass' || $stu_result_data['result']=='pass' || $stu_result_data['result']=='PASS' ? 'YES' : 'NO';
                    $year_of_passing = $pas_status=='YES' ? $month.'-'.$year : '';
                    
                    $check_attempt = Yii::$app->db->createCommand('SELECT count(*) FROM coe_mark_entry_master WHERE subject_map_id="'.$sub_map_id.'" AND student_map_id="'.$_POST['reg_number'][$i].'" AND result not like "%pass%"')->queryScalar();
                    
                    $change_cia_val = isset($check_attempt) && !empty($check_attempt) ? $check_attempt +1:1;
                    if($subjectDetails->ESE_max==0)
                    {
                        $MarkEntryMaster->student_map_id = $_POST['reg_number'][$i];
                        $MarkEntryMaster->subject_map_id = $sub_map_id;
                        $MarkEntryMaster->CIA = $_POST['ese_marks'][$i];
                        $MarkEntryMaster->ESE = 0;
                        $MarkEntryMaster->total = $_POST['ese_marks'][$i];
                        $MarkEntryMaster->result = $stu_result_data['result'];
                        $MarkEntryMaster->grade_point =$stu_result_data['grade_point'];
                        $MarkEntryMaster->grade_name = $stu_result_data['grade_name'];
                        $MarkEntryMaster->year = $year;
                        $MarkEntryMaster->month = $month;
                        $MarkEntryMaster->term = $term;
                        $MarkEntryMaster->mark_type = $mark_type;
                        $MarkEntryMaster->status_id = 0;
                        $MarkEntryMaster->year_of_passing = $year_of_passing;
                        $MarkEntryMaster->attempt = $change_cia_val;
                        $MarkEntryMaster->created_by = $updateBy;
                        $MarkEntryMaster->created_at = $created_at;
                        $MarkEntryMaster->updated_by = $updateBy;
                        $MarkEntryMaster->updated_at = $created_at;
                        $mark_master_entry_check = MarkEntryMaster::find()->where(['student_map_id' => $_POST['reg_number'][$i], 'subject_map_id' => $sub_map_id, 'year' => $year,'month'=>$month,'term'=>$term])->one();
                        if(empty($mark_master_entry_check))
                        {
                            $MarkEntryMaster->save(false);
                        }                    
                        
                        unset($MarkEntryMaster);
                    }
                    $insertData[] = ['stu_id'=>$_POST['reg_number'][$i],'dummy_marks'=>$_POST['ese_marks'][$i]];
                    

                    
                }
            }
            if(!empty($insertData))
            {
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                    /* 
                    *   Already Defined Variables from the above included file
                    *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
                    *   use these variables for application
                    *   use $file_content_available="Yes" for Content Status of the Organisation
                    */         
                $get_month_name = Categorytype::findOne($month);
                $sub_caode = SubjectsMapping::findOne($sub_map_id);
                $subDetails = Subjects::findOne($sub_caode->subject_id);
                  $header = $footer = $final_html = $body = '';
                  $header = '<table width="100%" >
                   
                    <tr>                            
                      <td> 
                        <img witdth="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=2 align="center"> 
                          <center><b>'.$org_name.'</b></center>
                          <center> '.$org_address.'</center>
                          <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                     </td>
                      <td>  
                        <img witdth="100" height="100" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>
                    <tr>
                    <td align="center" colspan=4><h5>AUTONOMOUS END SEMESTER EXAMINATIONS '.strtoupper($get_month_name['description']).' '.$year.' </h5>
                    </td><
                    /tr>
                    <tr><td align="center" colspan=4><h5>STATEMENT OF MARKS</h5></td></tr>
                    <tr>
                        <td align="left"  colspan=2>
                            '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE").' : '.$subDetails['subject_code'].'
                        </td>
                        <td align="right" colspan=2>
                            DATE OF VALUATION : '.date("d/m/Y").'
                        </td> 
                    </tr>
                    <tr>
                        <td align="left" colspan=4> 
                            '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE").' : ('.$subDetails['subject_code'].') '.$subDetails['subject_name'].'
                        </td>
                    </tr>
                    <tr class="table-danger">
                        <th width="30px;">S.NO</th>  
                        <th>'.strtoupper('Register Number').'</th>
                        <th>'.strtoupper("Marks").'</th>
                        <th>'.strtoupper("Marks In Words").'</th>
                    </tr>  
                    <tbody>     

                            ';
                  $increment = 1;
                  $footer .='<tr height=45px class ="alternative_border">
                                <td align="left" colspan=2>
                                    Name of the Examiner <br /><br />
                                    &nbsp;&nbsp;&nbsp;<br />

                                </td>
                                <td align="right" colspan=2>
                                    Name of the Chief Examiner / Controller <br /><br />
                                    &nbsp;&nbsp;&nbsp;&nbsp; <br />
                                </td> 
                            </tr>
                            <tr>
                                <td  height="65px" align="left" colspan=2>
                                   Signature With Date <br /><br /><br />
                                </td>
                                <td  height="65px" align="right" colspan=2>
                                    Signature With Date <br /><br /><br />
                                </td> 
                            </tr></tbody></table>';
                  $Num_30_nums = 0;
                  foreach ($insertData as $value) 
                  {
                        $stuMap = StudentMapping::findOne($value["stu_id"]);
                        $stuDetails = Student::findOne($stuMap["student_rel_id"]);
                        $split_number = str_split($value["dummy_marks"]);
                        $print_text = $this->valueReplaceNumber($split_number);
                        $body .='<tr height="10px"><td>'.$increment.'</td><td>'.$stuDetails["register_number"].'</td><td>'.$value["dummy_marks"].'</td><td><b style="color: #000">'.$print_text.'</b></td></tr>';
                        $increment++;
                        if($increment%31==0)
                        {
                            $Num_30_nums =1;
                            $html = $header.$body.$footer;
                            $final_html .=$html;
                            $html = $body = '';
                        }
                  }
                  
                  $html = $header.$body.$footer;     
                  
                  $final_html .=$html;               
                  $content = $final_html; 

                  $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => "Internal Mark Entry.pdf",                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_PORTRAIT,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,         
                    'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: left;  font-family:"Roboto, sans-serif"; width:100%; font-size: 15px; } 
                        
                        table td{
                            border: 1px solid #000;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: center;
                        }
                        table th{
                            border: 1px solid #000;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: center;
                        }
                    }   
                ',            
                                   
                    'options' => ['title' => 'Internal Marks For '.strtoupper($get_month_name['description']).' '.$year],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                        'SetFooter'=>['INTERNAL MARKS FOR '.strtoupper($get_month_name['description']).' '.$year.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                    ],
                    
                ]);
                
                $pdf->marginLeft="8";
                $pdf->marginRight="8";
                $pdf->marginBottom="8";
                $pdf->marginFooter="4";
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
                return $pdf->render(); 
            }

             Yii::$app->ShowFlashMessages->setMsg('Success','Successfully Updated');
            return $this->redirect(['internal-arrear-mark-entry']);

        }

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Arrear Internal Mode Mark Entry');
        return $this->render('internal-arrear-mark-entry', [
                'markEntry' => $markEntry,                    
                'markentrymaster' => $markentrymaster,
           ]);
    }
    /**
     * Creates a new Categorytype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Categorytype();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->created_at = new \yii\db\Expression('NOW()');
            $model->created_by = Yii::$app->user->getId();
            $model->updated_at = new \yii\db\Expression('NOW()');
            $model->updated_by = Yii::$app->user->getId();

            if($model->save())
            {
                return $this->redirect(['view', 'id' => $model->coe_category_type_id]);
            }
            else
            {
                return $this->render('create', ['model' => $model]);
            }
        } 
        else 
        {
            return $this->render('create', ['model' => $model]);
        }
    }

    /**
     * Updates an existing Categorytype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coe_category_type_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Categorytype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categorytype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categorytype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categorytype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function valueReplaceNumber($array_data)
    {
        $array= array('0'=>'ZERO','1'=>'ONE','2'=>'TWO','3'=>'THREE','4'=>'FOUR','5'=>'FIVE','6'=>'SIX','7'=>'SEVEN','8'=>'EIGHT','9'=>'NINE','10'=>'TEN');  
        $return_string='';
        for($i=0;$i<count($array_data);$i++)
        {
            $return_string .=$array[$array_data[$i]]." ";
        }
        return !empty($return_string)?$return_string:'No Data Found';
           
    }

    public function actionStuArrearCountFeePdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $content = $_SESSION['singlAttemprPass'];
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
                'filename' => 'STUDENT ARREAR COUNT WITH FEES.pdf',                
                'format' => Pdf::FORMAT_LEGAL,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,                  
                'cssInline' => ' @media all{
                        table{border-collapse: collapse; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width:100%; font-size: 20px !important;  }

                        table td table{border: none !important;}
                        table td{
                            border: 1px solid #000;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: left;
                            padding: 10px;
                        }
                        table td.reduce_qp_height 
                        {
                            
                          padding: 5px !important;
                        }
                        table th{
                           border: 1px solid #000;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: left;
                           
                        }
                    }   
                ', 
                'options' => ['title' => 'STUDENT ARREAR COUNT WITH FEES DETAILS'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['STUDENT ARREAR COUNT WITH FEES INFORMATION'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ]
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        
        return $pdf->render();
    }
    public function actionStuArrearCountFeeExcel()
    {
        $content = $_SESSION['singlAttemprPass'];
        $fileName = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT) . ' COUNT ARREAR .xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
}
