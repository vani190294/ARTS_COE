<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\db\Query;
use app\models\PracticalExamTimetable;
use app\models\PracticalExamTimetableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\PracticalEntry;
use app\models\MarkEntry;
use app\models\StuInfo;
use app\models\UpdateTracker;
use app\models\SubInfo;
use app\models\AbsentEntry;
use app\models\Student;
use app\models\StudentMapping;
use app\models\MarkEntryMaster;
use app\models\SubjectsMapping;
use app\models\Subjects;
use app\models\Categorytype;
use kartik\mpdf\Pdf;
use app\models\PracStuPerBatch;
use app\models\PracStuPerBatchSearch;
/**
 * PracticalExamTimetableController implements the CRUD actions for PracticalExamTimetable model.
 */
class PracticalExamTimetableNewController extends Controller
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
     * Lists all PracticalExamTimetable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PracticalExamTimetableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(isset($_POST['delete'])  && !empty($_POST['finalString']))
        {
          $finalString = $_POST['finalString'];
          $exam_id = split('[\^]', $finalString);
          $del_ids =[];
          $exam_dates='';
          for($i=0;$i<count($exam_id)-1;$i++)
          {     
            if(!empty($exam_id[$i]))
            {
                $exam_date = PracticalExamTimetable::findOne($exam_id[$i]);                
                $hall_allo_check = PracticalEntry::find()->where(['student_map_id'=>$exam_date['student_map_id'],'subject_map_id'=>$exam_date['subject_map_id'],'year'=>$exam_date['exam_year'],'month'=>$exam_date['exam_month']])->all();                
                $absent_check = AbsentEntry::find()->where(['absent_student_reg'=>$exam_date['student_map_id'],'exam_subject_id'=>$exam_date['subject_map_id']])->all();
                if(empty($absent_check) && empty($hall_allo_check))
                {
                    $del_ids[$exam_id[$i]] = $exam_id[$i];                    
                }          
                $stuInfo = StuInfo::findOne(['stu_map_id'=>$exam_date['student_map_id']]);
                $exam_dates[] = !empty($stuInfo) ? $stuInfo['reg_num'] :'';      
            }            
          }
          $del_ids = array_filter($del_ids);
          $exam_dates = array_unique(array_filter($exam_dates));
          $exam_dates = implode(', ', $exam_dates); 
          $exam_dates = trim($exam_dates,', ');
          if(!empty($del_ids))
          {             
             $query = (new Query)
                        ->createCommand()
                        ->delete('coe_prac_exam_ttable', ['IN','coe_prac_exam_ttable_id',$del_ids])
                        ->execute();
            Yii::$app->ShowFlashMessages->setMsg('Success','Selected Practical '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM). ' Dates <b>'.$exam_dates.'</b> Deleted Successfully!!');
          }
          else
          {
                Yii::$app->ShowFlashMessages->setMsg('Error','Unable to Delete '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM). ' <b>'.$exam_dates.'</b> Mark Entry / Absent Entry Available ');
          }
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PracticalExamTimetable model.
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
     * Creates a new PracticalExamTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        if ($model->load(Yii::$app->request->post())) 
        {           
            $created_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId();
            //print_r($_POST['reg_number']); exit;
            if(isset($_POST['reg_number']) && count($_POST['reg_number'])>0 && !empty($model->exam_session) && !empty($_POST['exam_date']))
            {
                $checkRegNums = array_filter(['']);
                for ($abse=0; $abse <count($_POST['reg_number']) ; $abse++) 
                { 
                    $checkRegNums[$_POST['reg_number'][$abse]] = $_POST['reg_number'][$abse];  
                }                
                $checkExam = PracticalExamTimetable::find()->where(['exam_year'=>$model->exam_year,'exam_month'=>$model->exam_month,'subject_map_id'=>$model->subject_map_id])->andWhere(['IN','student_map_id',$checkRegNums])->all();               
                if(count($checkExam)>=3)
                {
                    Yii::$app->ShowFlashMessages->setMsg('ERROR',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' CONDUCTION ALREADY COMPLETED CONTACT COE FOR MORE HELP!!');
                    return $this->redirect(['create']);
                }

                //print_r($_POST['reg_number']); exit();
                $rand=date("YmdHis").rand(10,1000);
                for ($i=0; $i <count($_POST['reg_number']) ; $i++) 
                { 
                    $batch_mapping_id = $model->batch_mapping_id;
                    $exam_year = $model->exam_year;
                    $exam_month = $model->exam_month;
                    $exam_session = $model->exam_session;
                    $subject_map_id = $model->subject_map_id;
                    $exam_date = date('Y-m-d',strtotime($_POST['exam_date']));
                    $check_data = PracticalExamTimetable::find()->where(['exam_year'=>$exam_year,'exam_month'=>$exam_month,'student_map_id'=>$_POST['reg_number'][$i],'exam_date'=>$exam_date,'mark_type'=>$model->mark_type])->all();
                    $check_data_stu = PracticalExamTimetable::find()->where(['exam_year'=>$exam_year,'exam_month'=>$exam_month,'student_map_id'=>$_POST['reg_number'][$i],'exam_date'=>$exam_date,'mark_type'=>$model->mark_type,'exam_session'=>$exam_session])->all();
                    
                    $STUmAP = StudentMapping::findOne($_POST['reg_number'][$i]);
                    $stuData = Student::findOne($STUmAP['student_rel_id']);
                    $getMaxExams = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_MAX_PRAC_EXAM_CONDUTION);
                    
                    if(count($check_data)<$getMaxExams && empty($check_data_stu))
                    {
                        $check_same_student = PracticalExamTimetable::find()->where(['exam_year'=>$exam_year,'exam_month'=>$exam_month,'student_map_id'=>$_POST['reg_number'][$i],'exam_date'=>$exam_date,'exam_session'=>$exam_session])->all();
                        if(empty($check_same_student))
                        {

                            $save_model = new PracticalExamTimetable();
                            $save_model->batch_mapping_id = $batch_mapping_id;
                            $save_model->student_map_id = $_POST['reg_number'][$i];
                            $save_model->subject_map_id = $subject_map_id;
                            $save_model->exam_year = $exam_year;
                            $save_model->exam_month = $exam_month;
                            $save_model->mark_type = $model->mark_type;
                            $save_model->term = 34;
                            $save_model->exam_date = $exam_date;
                            $save_model->exam_session = $exam_session;
                            $save_model->unique_prac_id = $rand;
                            $save_model->semester = $model->semester;
                            $save_model->created_at = $created_at;
                            $save_model->created_by = $updateBy;
                            $save_model->updated_at = $created_at;
                            $save_model->updated_by = $updateBy;
                            $save_model->save(false);
                        }
                        else
                        {
                            Yii::$app->ShowFlashMessages->setMsg('ERROR','SESSION ISSUE UNABLE TO CREATE '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' <b>'.$stuData['register_number'].'</b> HAVE ANOTHER EXAM CONTACT COE!!');
                            return $this->redirect(['create']);
                        }

                    }
                    else 
                    {
                        Yii::$app->ShowFlashMessages->setMsg('ERROR','MULTIPLE ' .ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' FOR <b>'.$stuData['register_number'] .'</b> UNABLE TO CREATE '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' CONTACT COE!!');
                        return $this->redirect(['create']);
                    }
                    
                }
                if($i>0)
                {
                    if($model->mark_type==28)
                    {

                      $query = new Query();
                      $query->select('C.name,C.register_number,A.exam_year as year ,A.exam_date,H.category_type as exam_session,E.category_type as month,G.subject_code,G.subject_name,internal_examiner_name as examiner_name')
                                ->from('coe_prac_exam_ttable as A')
                                ->join('JOIN','coe_subjects_mapping as F','F.coe_subjects_mapping_id=A.subject_map_id')
                                ->join('JOIN','coe_subjects as G','G.coe_subjects_id=F.subject_id')
                                ->join('JOIN','coe_student_mapping as B','B.course_batch_mapping_id=F.batch_mapping_id and B.coe_student_mapping_id=A.student_map_id')
                                ->join('JOIN','coe_student as C','C.coe_student_id=B.student_rel_id')
                                ->join('JOIN','coe_category_type as E','E.coe_category_type_id=A.exam_month')
                                ->join('JOIN','coe_category_type as H','H.coe_category_type_id=A.exam_session')
                                ->join('JOIN','coe_bat_deg_reg as I','I.coe_bat_deg_reg_id=B.course_batch_mapping_id and I.coe_bat_deg_reg_id=F.batch_mapping_id')
                                ->join('JOIN','coe_batch as J','J.coe_batch_id=I.coe_batch_id');
                      $query->where(['B.course_batch_mapping_id'=>$batch_mapping_id,'A.exam_year'=>$exam_year,'A.exam_month'=>$exam_month,'A.exam_date'=>$exam_date,'A.exam_session'=>$exam_session,'A.subject_map_id'=>$subject_map_id,'F.semester'=>$model->semester,'A.semester'=>$model->semester,'mark_type'=>$model->mark_type])
                            ->andWhere(['IN','A.student_map_id',$_POST['reg_number']]);
                      if($_POST['MarkEntry']['section']!=='All' && $_POST['MarkEntry']['section']!=='')
                      {
                          $query->andWhere(['=','B.section_name',$_POST['MarkEntry']['section']]);
                      }
                      $query->groupBy('C.register_number,A.exam_session');
                    }
                    else
                    {
                      $query = new Query();
                      $query->select('C.name,C.register_number,A.exam_year as year ,A.exam_date,H.category_type as exam_session,E.category_type as month,G.subject_code,G.subject_name,internal_examiner_name as examiner_name')
                                ->from('coe_prac_exam_ttable as A')
                                ->join('JOIN','coe_subjects_mapping as F','F.coe_subjects_mapping_id=A.subject_map_id')
                                ->join('JOIN','coe_subjects as G','G.coe_subjects_id=F.subject_id')
                                ->join('JOIN','coe_student_mapping as B','B.course_batch_mapping_id=F.batch_mapping_id and B.coe_student_mapping_id=A.student_map_id')
                                ->join('JOIN','coe_student as C','C.coe_student_id=B.student_rel_id')
                                ->join('JOIN','coe_category_type as E','E.coe_category_type_id=A.exam_month')
                                ->join('JOIN','coe_category_type as H','H.coe_category_type_id=A.exam_session')
                                ->join('JOIN','coe_bat_deg_reg as I','I.coe_bat_deg_reg_id=B.course_batch_mapping_id and I.coe_bat_deg_reg_id=F.batch_mapping_id')
                                ->join('JOIN','coe_batch as J','J.coe_batch_id=I.coe_batch_id');
                      $query->where(['B.course_batch_mapping_id'=>$batch_mapping_id,'A.exam_year'=>$exam_year,'A.exam_month'=>$exam_month,'A.exam_date'=>$exam_date,'A.exam_session'=>$exam_session,'A.subject_map_id'=>$subject_map_id,'F.semester'=>$model->semester,'A.semester'=>$model->semester,'mark_type'=>$model->mark_type])
                       ->andWhere(['IN','A.student_map_id',$_POST['reg_number']]);
                            //->andWhere(['BETWEEN','C.register_number',$_POST['from_reg'],$_POST['to_reg']]);
                      if($_POST['MarkEntry']['section']!=='All' && $_POST['MarkEntry']['section']!=='')
                      {
                          $query->andWhere(['=','B.section_name',$_POST['MarkEntry']['section']]);
                      }
                      $query->groupBy('C.register_number,A.exam_session');
                    }
                    $print_for = 'Practical';
                    //echo $query->createCommand()->getrawsql();exit;
                    $get_data = $query->createCommand()->queryAll();

                    if(isset($get_data) && !empty($get_data))
                    {
                        Yii::$app->ShowFlashMessages->setMsg('SUCCESS','PRACTICAL EXAM CREATED SUCCESSFULLY!! ');
                        Yii::$app->ShowFlashMessages->setMsg('WARNING','ALLOCATE INTERNAL AND EXTERNAL FACUTLY THEN GENERATE ATTENDACE SHEET ');
                       return $this->redirect(['create']);
                }
                else
                {
                     Yii::$app->ShowFlashMessages->setMsg('error',"Not Found Please Check" );
                    return $this->redirect(['create']);
                }

                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('FAILURE','UNABLE TO CREATE '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' CONTACT COE');
                        return $this->redirect(['create']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('ERROR','UNABLE TO CREATE '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' CONTACT COE!');
                return $this->redirect(['create']);
            }
            
            
        } else {
            Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to Practical Exam Timetable');
            return $this->render('create', [
                'model' => $model,
                'student'=>$student,
                'markEntry'=>$markEntry,
                'MarkEntryMaster'=>$MarkEntryMaster,
            ]);
        }
    }

    public function actionAttendanceSheetPractical()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        if ($model->load(Yii::$app->request->post())) 
        {           
            $batch_mapping_id = $model->batch_mapping_id;
            $exam_year = $model->exam_year;
            $exam_month = $model->exam_month;
            $exam_session = $model->exam_session;
            $unique_prac_id=$_POST['PracticalExamTimetable']['unique_prac_id'];
            $exam_date = date('Y-m-d',strtotime($_POST['exam_date']));   
            $query = new Query();
            $query->select('C.name,C.register_number,A.exam_year as year ,A.exam_date,H.category_type as exam_session,E.category_type as month,G.subject_code,G.subject_name,EF.faculty_name as external_examiner_name,IF.faculty_name as internal_examiner_name,EF.college_code,EF.phone_no, internal_examiner2, external_examiner2')
                      ->from('coe_prac_exam_ttable as A')
                      ->join('JOIN','coe_subjects_mapping as F','F.coe_subjects_mapping_id=A.subject_map_id')
                      ->join('JOIN','coe_subjects as G','G.coe_subjects_id=F.subject_id')
                      ->join('JOIN','coe_student_mapping as B','B.course_batch_mapping_id=F.batch_mapping_id and B.coe_student_mapping_id=A.student_map_id')
                      ->join('JOIN','coe_student as C','C.coe_student_id=B.student_rel_id')
                      ->join('JOIN','coe_category_type as E','E.coe_category_type_id=A.exam_month')
                      ->join('JOIN','coe_category_type as H','H.coe_category_type_id=A.exam_session')
                      ->join('JOIN','coe_bat_deg_reg as I','I.coe_bat_deg_reg_id=B.course_batch_mapping_id and I.coe_bat_deg_reg_id=F.batch_mapping_id')
                      ->join('JOIN','coe_batch as J','J.coe_batch_id=I.coe_batch_id')
                       ->join('JOIN', 'coe_valuation_faculty as IF', 'IF.coe_val_faculty_id=A.internal_examiner_name')
                ->join('JOIN', 'coe_valuation_faculty as EF', 'EF.coe_val_faculty_id=A.external_examiner_name');
            $query->where(['B.course_batch_mapping_id'=>$batch_mapping_id,'A.exam_year'=>$exam_year,'A.exam_month'=>$exam_month,'A.exam_date'=>$exam_date,'A.exam_session'=>$exam_session,'A.unique_prac_id'=>$unique_prac_id,'F.semester'=>$model->semester,'A.semester'=>$model->semester,'mark_type'=>$model->mark_type]);

           if($_POST['MarkEntry']['section']!='All' && !empty($_POST['MarkEntry']['section']))
            {
                $query->andWhere(['=','B.section_name',$_POST['MarkEntry']['section']]);
            }
            $query->groupBy('C.register_number');
            $print_for = 'Practical';

            //echo $query->createCommand()->getrawsql();  exit;
            $get_data = $query->createCommand()->queryAll();  

            if(isset($get_data) && !empty($get_data))
            {
                return $this->render('attendance-sheet-practical', [
                    'model' => $model,
                    'student'=>$student,
                    'get_data'=>$get_data,
                    'markEntry'=>$markEntry,
                    'MarkEntryMaster'=>$MarkEntryMaster,
                ]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('ERROR','NO DATA FOUND PLEASE CHECK FACULTY ALLOTED');
                return $this->redirect(['attendance-sheet-practical']);
            }   
            
        } else {
            Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to Re Print the Attendance Sheet Practical');
            return $this->render('attendance-sheet-practical', [
                'model' => $model,
                'student'=>$student,
                'markEntry'=>$markEntry,
                'MarkEntryMaster'=>$MarkEntryMaster,
            ]);
        }
    }

    public function actionExternalExaminerReport()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to External Examiner Report');
        return $this->render('external-examiner-report', [
            'model' => $model,
            'student'=>$student,
            'markEntry'=>$markEntry,
            'MarkEntryMaster'=>$MarkEntryMaster,
        ]);
        
    }

    public function actionExternalExaminerVenueReport()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to External Examiner venue');
        return $this->render('external-examiner-venuereport', [
            'model' => $model,
            'student'=>$student,
            'markEntry'=>$markEntry,
            'MarkEntryMaster'=>$MarkEntryMaster,
        ]);
        
    }

    public function actionSquadReport()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to Practical Exam Squad Report');
        return $this->render('squadreport', [
            'model' => $model,
            'student'=>$student,
            'markEntry'=>$markEntry,
            'MarkEntryMaster'=>$MarkEntryMaster,
        ]);
        
    }

    public function actionExternalExaminerReportPdf(){
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $content = $_SESSION['export_eaminer_repo'];        
        //unset($_SESSION['student_application_date']);
            $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE,
                'filename' => 'Practical External Examiners List.pdf',                
                'format' => Pdf::FORMAT_LEGAL,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse: collapse; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width:100%;   }

                        table td table{border: none !important;}
                        table td{
                          
                            font-size: 15px !important; 
                            padding: 1px 0 !important; 
                            text-align: center;
                        }
                        table th{
                            font-size: 11px !important; 
                            text-align: left;
                            padding: 1px 0 !important; 
                           
                        }
                    }   
                ', 
                'options' => ['title' => 'Practical External Examiners List'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['Practical External Examiners List'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO} of {nb}'],
                ]
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }

    public function actionExcelExternalExaminer()
    {
        $content = $_SESSION['export_eaminer_repo'];
        $fileName ='Practical External Examiners List'.date('Y-m-d-H-i-s').'.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    public function actionAttendanceSheetPracticalPdf(){
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $content = $_SESSION['Exam_attendance_sheet_practical'];        
        //unset($_SESSION['student_application_date']);
            $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE,
                'filename' => 'Attendance Sheet Practical.pdf',                
                'format' => Pdf::FORMAT_LEGAL,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse: collapse; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width:100%;   }

                        table td table{border: none !important;}
                        table td{
                            font-size: 13px !important; 
                            padding: 1px 0 !important; 
                            text-align: left;
                        }
                        table th{
                            font-size: 11px !important; 
                            text-align: left;
                            padding: 1px 0 !important; 
                           
                        }
                    }   
                ', 
                'options' => ['title' => 'Attendance Sheet'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['Attendance Sheet Practical'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ]
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }

    public function actionExcelAttendancePracticalSheet()
    {
        $content = $_SESSION['Exam_attendance_sheet_practical'];
        $fileName ='Attendance-Sheet-'.date('Y-m-d-H-i-s').'.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    public function actionReportExaminerPracticalPdf(){
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $content = $_SESSION['reval_course_marks'];        
        //unset($_SESSION['student_application_date']);
            $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE,
                'filename' => 'Practical Exams Report.pdf',                
                'format' => Pdf::FORMAT_LEGAL,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse: collapse; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width:100%;   }

                        table td table{border: none !important;}
                        table td{
                            font-size: 13px !important; 
                            padding: 1px 0 !important; 
                            text-align: left;
                        }
                        table th{
                            font-size: 11px !important; 
                            text-align: left;
                            padding: 1px 0 !important; 
                           
                        }
                    }   
                ', 
                'options' => ['title' => 'Practical Exams Report'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['Practical Exams Report'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ]
            ]);
         $pdf->marginTop = "7";
          $pdf->marginLeft = "5.5";
          $pdf->marginRight = "3";
          $pdf->marginBottom = "0";
          $pdf->marginHeader = "3";
          $pdf->marginFooter = "0";
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }

    public function actionExcelReportExaminerSheet()
    {
        $content = $_SESSION['reval_course_marks'];
        $fileName ='Practical-Exams-Report'.date('Y-m-d-H-i-s').'.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    /**
     * Creates a new PracticalExamTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMarkEntry()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        if ($model->load(Yii::$app->request->post())) 
        {
            $created_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId(); 
            
            if(isset($_POST['reg_number']) && count($_POST['reg_number'])>0 && !empty($model->exam_session) && !empty($model->exam_date))
            {                   
                $chief_exam_name = '';
                $mark_type = $model->mark_type;
                $reguLar = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Regular%'")->queryScalar();
                $get_cat_entry_type = Categorytype::find()->where(['description'=>'Practical Entry'])->orWhere(['category_type'=>'Practical Entry'])->one();
                $absent_entry_type = $get_cat_entry_type['coe_category_type_id'];

                $totalSuccess = '';
                $subject_map_id = $model->subject_map_id;// as subject_id
                $year = $model->exam_year;
                $section_name = $_POST['MarkEntry']['section']!='All'?$_POST['MarkEntry']['section']:'';
                $term = 34;
                $stu_map_id_in = array_filter(['']);
                $totalSuccess = 0;
                $month = $model->exam_month;
                $exam_session=$model->exam_session;
                
                $count_of_reg_num = count($_POST['reg_number']);
                $created_at = date("Y-m-d H:i:s");
                $updateBy = Yii::$app->user->getId(); 
                $subject_map_id_de =SubjectsMapping::findOne($subject_map_id); 
                $subjMax = Subjects::findOne($subject_map_id_de->subject_id);

                $external_examiner_name=$_POST['external_examiner_name'];
                if(isset($section_name) && !empty($section_name))
                {
                  $getExternalEntry = Yii::$app->db->createCommand('select A.* FROM coe_prac_exam_ttable  as A join coe_student_mapping as B On A.student_map_id=B.coe_student_mapping_id   where A.external_examiner_name="'.$external_examiner_name.'" and A.exam_year="'.$year.'" and A.exam_month="'.$month.'" and A.subject_map_id="'.$subject_map_id.'" and A.exam_date="'.$model->exam_date.'" and B.section_name="'.$section_name.'" and A.exam_session="'.$exam_session.'"')->QueryOne(); 
                  
                }
                else
                {

                  $getExternalEntry = PracticalExamTimetable::find()->where(['batch_mapping_id'=>$subject_map_id_de['batch_mapping_id'],'external_examiner_name'=>$external_examiner_name,'subject_map_id'=>$subject_map_id,'exam_year'=>$year,'exam_month'=>$month,'mark_type'=>$mark_type,'exam_session'=>$model->exam_session,'exam_date'=>$model->exam_date])->one();  
                }


                $getExternalExaminer = PracticalExamTimetable::find()->where(['batch_mapping_id'=>$subject_map_id_de['batch_mapping_id'],'external_examiner_name'=>$external_examiner_name,'subject_map_id'=>$subject_map_id,'exam_year'=>$year,'exam_month'=>$month,'mark_type'=>$mark_type])->one();
                
                $examiner_name = $getExternalEntry['internal_examiner_name'];
                $chief_exam_name =  $getExternalEntry['external_examiner_name'];
                //print_r($chief_exam_name);exit;

                for($i=0; $i < count($_POST['reg_number']) ; $i++) 
                { 
                    if(!empty($_POST['reg_number'][$i]) && (!empty($_POST['ese_marks'][$i])  || $_POST['ese_marks'][$i]<=0 ) && !empty($getExternalEntry) && !empty($getExternalExaminer))
                    {  

                        $student_map_id = $_POST['reg_number'][$i];
                        $check_inserted = PracticalEntry::find()->where(['year'=>$year,'month'=>$month,'student_map_id'=>$_POST['reg_number'][$i],'subject_map_id'=>$subject_map_id,'mark_type'=>$mark_type])->one();

                        if(empty($check_inserted) && ( $_POST['ese_marks'][$i]=='-1' || $_POST['ese_marks'][$i]<0) )
                        {
                            $getAbsentList = AbsentEntry::find()->where(['exam_type'=>$mark_type,'absent_term'=>$term,'exam_subject_id'=>$subject_map_id,'exam_month'=>$month,'exam_year'=>$year,'absent_student_reg'=>$_POST['reg_number'][$i]])->all();
                            $absentInsert = new AbsentEntry();
                            $absentInsert->absent_student_reg = $student_map_id;
                            $absentInsert->exam_type = $mark_type;
                            $absentInsert->absent_term = $term;
                            $absentInsert->exam_subject_id = $subject_map_id;
                            $absentInsert->exam_absent_status = $absent_entry_type;
                            $absentInsert->exam_month = $month;
                            $absentInsert->exam_year = $year;
                            $absentInsert->exam_date = $getExternalExaminer['exam_date'];
                            $absentInsert->exam_session = $getExternalExaminer['exam_session'];
                            $absentInsert->created_by = $updateBy;
                            $absentInsert->updated_by = $updateBy;
                            $absentInsert->created_at = $created_at;
                            $absentInsert->updated_at = $created_at;
                            if(empty($getAbsentList))
                            {
                               $absentInsert->save(false);
                            }
                        }
                        if(empty($check_inserted))
                        {
                            $INSERT_ESE_MARKS = $_POST['ese_marks'][$i];

                            $INSERT_ESE_MARKS1 = ( $_POST['ese_marks'][$i]=='-1' || $_POST['ese_marks'][$i]<=0) ?0:$_POST['ese_marks'][$i];

                            $converted_ese = (($subjMax->ESE_max*$INSERT_ESE_MARKS)/100);

                            $getDetails = PracticalExamTimetable::find()->where(['student_map_id'=>$_POST['reg_number'][$i],'subject_map_id'=>$subject_map_id,'exam_year'=>$year,'exam_month'=>$month,'mark_type'=>$mark_type])->one();

                            if(!empty($getDetails))
                            {
                              $connection = Yii::$app->db;
                              $command1 = $connection->createCommand('UPDATE coe_prac_exam_ttable SET out_of_100="'.$INSERT_ESE_MARKS.'",ESE="'.$converted_ese.'",updated_by="'.$updateBy.'",updated_at="'.$created_at.'" WHERE coe_prac_exam_ttable_id="'.$getDetails['coe_prac_exam_ttable_id'].'"');
                              $res = $command1->execute();
                            }

                            $model_save = new PracticalEntry();
                            $model_save->student_map_id = $_POST['reg_number'][$i];
                            $model_save->subject_map_id = $subject_map_id;
                            $model_save->out_of_100 = $INSERT_ESE_MARKS;
                            $model_save->ESE =$converted_ese;
                            $model_save->year = $year;
                            $model_save->month = $month;
                            $model_save->term = $term;
                            $model_save->mark_type = $mark_type;
                            $model_save->chief_exam_name = $chief_exam_name;
                            $model_save->examiner_name = $examiner_name;
                            $model_save->created_at = $created_at;
                            $model_save->created_by = $updateBy;
                            $model_save->updated_at = $created_at;
                            $model_save->updated_by = $updateBy;

                            if($model_save->save(false))
                            {
                                $totalSuccess+=1;
                                $dispResults[] = ['type' => 'S',  'message' => 'Success'];    
                            }
                            else
                            {
                                $dispResults[] = ['type' => 'E',  'message' => 'Error'];
                            }
                            unset($model_save);
                            $model_save = new PracticalEntry();
                            Yii::$app->ShowFlashMessages->setMsg('Success','Practical Marks Inserted Successfully!!!');
                        }
                        else
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Error','Unable to Insert the Marks');
                        }
                        $stu_map_id_in[$_POST['reg_number'][$i]] = $_POST['reg_number'][$i];
                    }//Not Empty of the Register Number
                   
                } // For Loop
                
                if($totalSuccess>0)
                {

                      $getSubsInfo = new Query();              
                      $getSubsInfo->select(['A.register_number','F.subject_name','F.subject_code','out_of_100','chief_exam_name']);
                      $getSubsInfo->from('coe_subjects_mapping as E')                
                      ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                      ->join('JOIN', 'coe_practical_entry as B', 'B.subject_map_id=E.coe_subjects_mapping_id')
                      ->join('JOIN', 'coe_student_mapping as C', 'C.coe_student_mapping_id=B.student_map_id')
                      ->join('JOIN', 'coe_student as A', 'A.coe_student_id=C.student_rel_id')
                      ->Where(['chief_exam_name'=>$external_examiner_name,'B.subject_map_id'=>$subject_map_id,'year'=>$year,'month'=>$month,'term'=>$term,'mark_type'=>$mark_type])
                      ->andWhere(['IN','C.coe_student_mapping_id',$stu_map_id_in])
                      ->groupBy('register_number')
                      ->orderBy('register_number');                    
                      $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
                      $chief_exam_name = '';
                      
                      if(!empty($getSubsInfoDet))
                      {
                        $examiner_name=Yii::$app->db->createCommand("SELECT faculty_name FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$getExternalEntry['internal_examiner_name']."'")->queryScalar();

                        $chief_exam_name=Yii::$app->db->createCommand("SELECT faculty_name FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$getExternalEntry['external_examiner_name']."'")->queryScalar();

                        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                        $table='';
                        $get_month_name=Categorytype::findOne($month);
                        $header = $footer = $final_html = $body = '';
                        $header = '<table width="100%" class="table table-bordered table-responsive bulk_edit_table table-hover"  >
                            <tr>
                                    
                                      <td> 
                                        <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                                      </td>

                                      <td colspan=2 align="center"> 
                                          <center><b>'.$org_name.'</b></center>
                                          <center> '.$org_address.'</center>
                                          
                                          <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                                     </td>
                                      <td align="center">  
                                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                                      </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                    <td align="center" colspan=4><h5>PRACTICAL MARK ENTRY FOR EXAMINATIONS '.$year.' - '.strtoupper($get_month_name['description']).'</h5>
                                    </td></tr>
                                    <tr>
                                    <td align="center" colspan=4><h5>MARKS VERIFICATION AND APPROVAL FROM EXAMINER</h5></td></tr>
                                    <tr>                                        
                                        <td align="right" colspan=4>
                                            DATE OF VALUATION : '.date("d/m/Y").'
                                        </td> 
                                    </tr>
                                    <tr>
                                        <td height="15px" align="left" colspan=4> <b>
                                            '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE").' : ('.$subjMax->subject_code.') '.$subjMax->subject_name.'</b>
                                        </td>
                                    </tr>
                                    <tr class="table-danger">
                                        <th>SNO</th>  
                                        <th>REGISTER NUMBER</th>
                                        <th>'.strtoupper("Marks Out of 100").'</th>
                                        <th>'.strtoupper("Marks In Words").'</th>
                                    </tr>               
                                    
                                    ';
                          $footer .='<tr class ="alternative_border">
                                <td align="left" colspan=2>
                                    NAME OF THE INTERNAL EXAMINER <br /><br />
                                    '.$examiner_name.' <br />
                                </td>
                                <td align="right" colspan=2>
                                    NAME OF THE EXTERNAL EXAMINER <br /><br />
                                    '.$chief_exam_name.' <br />
                                </td>
                                
                            </tr>
                            <tr>
                                <td align="left" colspan=2>
                                   Signature With Date <br /><br /><br />
                                </td>
                                <td align="right" colspan=2>
                                    Signature With Date <br /><br /><br />
                                </td> 
                            </tr></table>';

                          $increment = 1;
                          $Num_30_nums = 0;
                            foreach ($getSubsInfoDet as $value)
                            {
                                if(isset($value["out_of_100"]) && $value["out_of_100"]>=0)
                                {
                                    $split_number = str_split($value["out_of_100"]);
                                    $print_text = $this->valueReplaceNumber($split_number);
                                }
                                else
                                {
                                    $print_text = 'ABSENT';
                                }
                                
                                
                               $body .='<tr><td>'.$increment.'</td><td>'.$value["register_number"].'</td><td>'.$value["out_of_100"].'</td><td>'.$print_text.'</td></tr>';
                                $increment++;
                                if($increment%31==0)
                                {
                                    $Num_30_nums =1;
                                    $html = $header.$body.$footer;
                                    $final_html .=$html;
                                    $html = $body = '';
                                }
                            }
                            if($body!='')
                              {
                                $html = $header.$body.$footer;     
                              }                  
                          $final_html .=$html;               
                          $content = $final_html;


                            $pdf = new Pdf([                   
                                'mode' => Pdf::MODE_CORE,                 
                                'filename' => 'PRACTICAL EXAM MARK VERIFICATION.pdf',                
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
                                           
                                'options' => ['title' => strtoupper('PRACTICAL').' MARK VERIFICATION'],
                                'methods' => [ 
                                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                                    'SetFooter'=>[strtoupper('PRACTICAL').' MARK VERIFICATION '.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                                ],
                                
                            ]);
                            
                            $pdf->marginLeft="8";
                            $pdf->marginRight="8";
                            $pdf->marginBottom="8";
                            $pdf->marginFooter="8";
                            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                            $headers = Yii::$app->response->headers;
                            $headers->add('Content-Type', 'application/pdf');
                            return $pdf->render(); 
                    } // Successfull data Available
                    else
                    {
                         Yii::$app->ShowFlashMessages->setMsg('Error','NO DATA FOUND');
                         return $this->redirect(['mark-entry']);
                    }
                } // Successful insertion
                else
                {
                         Yii::$app->ShowFlashMessages->setMsg('Error','NOTHING INSERTED');
                       return $this->redirect(['mark-entry']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('ERROR','KINDLY RESUBMIT THE FORM!!');
                return $this->redirect(['mark-entry']);
            }
            
            
        } else {
            Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to Practical Mark Entry');
            return $this->render('mark-entry', [
                'model' => $model,
                'student'=>$student,
                'markEntry'=>$markEntry,
                'MarkEntryMaster'=>$MarkEntryMaster,
            ]);
        }
    }

    public function actionEditMarkEntrysingle()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();

        Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to Practical Mark Entry');
              return $this->render('edit-mark-entry_single', [
                  'model' => $model,
                  'student'=>$student,
                  'markEntry'=>$markEntry,
                  'MarkEntryMaster'=>$MarkEntryMaster,
              ]);
    }
    /**
     * Creates a new PracticalExamTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEditMarkEntry()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        $checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
        $ip_address = Yii::$app->params['ipAddress'];
        if($checkAccess=='Yes')
        {
          if ($model->load(Yii::$app->request->post())) 
          {
              $created_at = date("Y-m-d H:i:s");
              $updateBy = Yii::$app->user->getId(); 
              
              if(isset($_POST['reg_number']) && count($_POST['reg_number'])>0 && !empty($model->exam_session) && !empty($model->exam_date))
              {                    
                  $chief_exam_name = '';
                  $mark_type = $model->mark_type;
                  $reguLar = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Regular%'")->queryScalar();
                  $get_cat_entry_type = Categorytype::find()->where(['description'=>'Practical Entry'])->orWhere(['category_type'=>'Practical Entry'])->one();
                  $absent_entry_type = $get_cat_entry_type['coe_category_type_id'];

                  $totalSuccess = '';
                  $subject_map_id = $model->subject_map_id;// as subject_id
                  $year = $model->exam_year;
                  $term = 34;
                  $stu_map_id_in = array_filter(['']);
                  $totalSuccess = 0;
                  $month = $model->exam_month;
                  
                  $count_of_reg_num = count($_POST['reg_number']);
                  $created_at = date("Y-m-d H:i:s");
                  $updateBy = Yii::$app->user->getId(); 
                  $subject_map_id_de =SubjectsMapping::findOne($subject_map_id); 
                  $subjMax = Subjects::findOne($subject_map_id_de->subject_id);

                  $getExternalEntry = PracticalExamTimetable::find()->where(['batch_mapping_id'=>$subject_map_id_de['batch_mapping_id'],'subject_map_id'=>$subject_map_id,'exam_year'=>$year,'exam_month'=>$month,'mark_type'=>$mark_type,'exam_session'=>$model->exam_session,'exam_date'=>$model->exam_date])->one();
                  
                  $examiner_name = $getExternalEntry['internal_examiner_name'];
                  $chief_exam_name =  $getExternalEntry['external_examiner_name'];
                  $failedJobs = '';
                  for($i=0; $i < count($_POST['reg_number']) ; $i++) 
                  { 
                      if(!empty($_POST['reg_number'][$i]) && (!empty($_POST['ese_marks'][$i])  || $_POST['ese_marks'][$i]==0 || $_POST['ese_marks'][$i]<=0 ) && !empty($getExternalEntry))
                      {  
                        $student_map_id = $_POST['reg_number'][$i];
                         $stuMapid = StudentMapping::findOne($student_map_id);
                          $stuReg = Student::findOne($stuMapid['student_rel_id']);
                          $check_inserted = PracticalEntry::find()->where(['year'=>$year,'month'=>$month,'student_map_id'=>$_POST['reg_number'][$i],'subject_map_id'=>$subject_map_id,'mark_type'=>$mark_type])->one();

                          if(!empty($check_inserted) && ( $_POST['ese_marks'][$i]=='-1' || $_POST['ese_marks'][$i]<=0 ) )
                          {
                            //// TRCKING CODE STARTS //////

                            $updated_from = ucwords( str_replace('-', ' ', Yii::$app->controller->action->controller->id.' '.Yii::$app->controller->action->id) );
                            $data_updated = 'Prev ESE '.$check_inserted['out_of_100'].' New ESE -1 Entry';
                            $data_array = ['subject_map_id'=>$subject_map_id,'updated_link_from'=>$updated_from,'data_updated'=>nl2br($data_updated),'exam_month'=>$month,'exam_year'=>$year,'student_map_id'=>$student_map_id];            
                            $update_track = ConfigUtilities::updateTracker($data_array);
                            //// TRCKING CODE ENDS //////

                              $delete_data = Yii::$app->db->createCommand('DELETE FROM coe_practical_entry WHERE coe_practical_entry_id ="'.$check_inserted['coe_practical_entry_id'].'"')->execute();
                              if(!empty($delete_data))
                              {
                                $getAbsentList = AbsentEntry::find()->where(['exam_type'=>$mark_type,'absent_term'=>$term,'exam_subject_id'=>$subject_map_id,'exam_month'=>$month,'exam_year'=>$year,'absent_student_reg'=>$_POST['reg_number'][$i]])->all();
                                $absentInsert = new AbsentEntry();
                                $absentInsert->absent_student_reg = $student_map_id;
                                $absentInsert->exam_type = $mark_type;
                                $absentInsert->absent_term = $term;
                                $absentInsert->exam_subject_id = $subject_map_id;
                                $absentInsert->exam_absent_status = $absent_entry_type;
                                $absentInsert->exam_month = $month;
                                $absentInsert->exam_year = $year;
                                $absentInsert->created_by = $updateBy;
                                $absentInsert->updated_by = $updateBy;
                                $absentInsert->created_at = $created_at;
                                $absentInsert->updated_at = $created_at;
                                if(empty($getAbsentList))
                                {
                                   $absentInsert->save(false);
                                }
                              }
                              else{
                                $failedJobs .=$stuReg['register_number'].', ';
                              }                            
                          }
                          $connection = Yii::$app->db;
                          if(!empty($check_inserted))
                          {
                              $INSERT_ESE_MARKS = ($_POST['ese_marks'][$i]=='-1' || $_POST['ese_marks'][$i]<=0)?0:$_POST['ese_marks'][$i];
                              $converted_ese = (($subjMax->ESE_max*$INSERT_ESE_MARKS)/100);
                              
                              //// TRCKING CODE STARTS //////
                              
                              $updated_from = ucwords( str_replace('-', ' ', Yii::$app->controller->action->controller->id.' '.Yii::$app->controller->action->id) );
                              $data_updated = 'Prev ESE '.$check_inserted['ESE'].' New ESE '.$converted_ese.' Entry';
                              $data_array = ['subject_map_id'=>$subject_map_id,'updated_link_from'=>$updated_from,'data_updated'=>nl2br($data_updated),'exam_month'=>$month,'exam_year'=>$year,'student_map_id'=>$student_map_id];            
                              $update_track = ConfigUtilities::updateTracker($data_array);

                              //// TRCKING CODE ENDS //////
                              
                              $getDetails = PracticalExamTimetable::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'exam_year'=>$year,'exam_month'=>$month,'mark_type'=>$mark_type])->one();

                              if(!empty($getDetails))
                              {
                                $connection = Yii::$app->db;
                                $command1 = $connection->createCommand('UPDATE coe_prac_exam_ttable SET out_of_100="'.$INSERT_ESE_MARKS.'",ESE="'.$converted_ese.'",updated_by="'.$updateBy.'",updated_at="'.$created_at.'" WHERE coe_prac_exam_ttable_id="'.$getDetails['coe_prac_exam_ttable_id'].'"');
                                $res = $command1->execute();
                              }
                              $res_1  = '';
                              $getDetailsPrac = PracticalEntry::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'year'=>$year,'month'=>$month,'mark_type'=>$mark_type])->one();

                              if(!empty($getDetailsPrac))
                              {
                                $connection = Yii::$app->db;
                                $command1 = $connection->createCommand('UPDATE coe_practical_entry SET out_of_100="'.$INSERT_ESE_MARKS.'",ESE="'.$converted_ese.'",updated_by="'.$updateBy.'",updated_at="'.$created_at.'" WHERE coe_practical_entry_id="'.$getDetailsPrac['coe_practical_entry_id'].'"');
                                $res_1 = $command1->execute();
                              }

                              if(isset($res_1) && !empty($res_1))
                              {
                                  $totalSuccess+=1;
                                  $dispResults[] = ['type' => 'S',  'message' => 'Success'];    
                              }
                              else
                              {
                                 $failedJobs .=$stuReg['register_number'].', ';
                                  $dispResults[] = ['type' => 'E',  'message' => 'Error'];
                              }
                              Yii::$app->ShowFlashMessages->setMsg('Success','Practical Marks Updated Successfully!!!');
                          }
                          else
                          {
                              Yii::$app->ShowFlashMessages->setMsg('Error','Unable to Insert the Marks');
                          }
                          $stu_map_id_in[$_POST['reg_number'][$i]] = $_POST['reg_number'][$i];
                      }//Not Empty of the Register Number
                     
                  } // For Loop

                   if($totalSuccess>0)
                  {
                      Yii::$app->ShowFlashMessages->setMsg('SUCCESS','DATA UPDATED SUCCESSFULLY!!');
                      return $this->redirect(['edit-mark-entry']);  
                  } // Successful insertion
                  else
                  {
                      Yii::$app->ShowFlashMessages->setMsg('ERROR',$failedJobs.' UPDATING ERROR');
                      return $this->redirect(['edit-mark-entry']);  
                  }
              }
              else
              {
                  Yii::$app->ShowFlashMessages->setMsg('ERROR','KINDLY RESUBMIT THE FORM!!');
                  return $this->redirect(['edit-mark-entry']);
              }
              
              
          } else {
              Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to EDIT Practical Mark Entry');
              return $this->render('edit-mark-entry', [
                  'model' => $model,
                  'student'=>$student,
                  'markEntry'=>$markEntry,
                  'MarkEntryMaster'=>$MarkEntryMaster,
              ]);
          }
        }
        else
        {
          $lockUser = Yii::$app->db->createCommand('UPDATE user SET status="11" WHERE id="'.Yii::$app->user->getId().'"')->execute();
            $created_by = $updated_by = Yii::$app->user->getId();
            $created_at = $updated_at = date("Y-m-d H:i:s");
            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            $filename =  Yii::getAlias("@webroot").'/access_logs/log_'.date("j.n.Y").'.txt';
            $content  = "User Name: ".Yii::$app->user->getUsername().' - '.date("F j, Y, g:i a").PHP_EOL.
                        "Accessed URLS: ".$url.PHP_EOL.
                        "----------------------------------------------------------------".PHP_EOL;

            //print_r(parse_url($url)); // This will returns the parts of the URL
            
            $removed_path = str_replace('index.php', '', parse_url($url, PHP_URL_PATH));
            $image_path = parse_url($url, PHP_URL_SCHEME)."://".parse_url($url, PHP_URL_HOST).parse_url($url, PHP_URL_PORT).$removed_path.'images/notfound.png'; 
            
            $image_path = Yii::getAlias("@web").'/images/notfound.png'; 

            if(file_put_contents($filename, $content.PHP_EOL , FILE_APPEND | LOCK_EX))
            {   
                $file_content = file_get_contents($filename, true);
                echo "<div style='width:1000px;  text-align: center; margin: 0 auto;'><img src='".$image_path."' alt='not found' height='600' width='900' align='center' /></div>";
                
            }
            unset($_SESSION);
            session_destroy();
            Yii::$app->ShowFlashMessages->setMsg('Error','OOOPS You are not allowed!!! Your Account is Locked!!!');
            return $this->redirect(['site/index']);            
        }
    }

    /**
     * Creates a new PracticalExamTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAllocateExaminer()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        //$checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
        
            if ($model->load(Yii::$app->request->post())) 
            {          
                if(isset($_POST['unique_prac_id']) && count($_POST['unique_prac_id'])>0 && !empty($_POST['internal_examiner']) && !empty($_POST['external_examiner']) )
                { 
                    $totalSuccess = '';
                    $year = $model->exam_year;
                    $term = 34;
                    $stu_map_id_in = array_filter(['']);
                    $totalSuccess = 0;
                    $month = $model->exam_month;
                    $exam_session=$model->exam_session;
                    $section_name=$_POST['MarkEntry']['section']=='All' ?'NO':$_POST['MarkEntry']['section'];
                    $get_session = Categorytype::findOne($exam_session);
                    $connection = Yii::$app->db;
                    $created_at = date("Y-m-d H:i:s");
                    $updateBy = Yii::$app->user->getId(); 

                    //$unique_prac_id=$_POST['PracticalExamTimetable']['unique_prac_id'];

                    if($section_name==='NO')
                    {
                      Yii::$app->ShowFlashMessages->setMsg('ERROR','SECTION NOT SELECTED KINDLY RESUBMIT THE FORM WITH PROPER SECTION NAME!!');
                      return $this->redirect(['allocate-examiner']);
                    }
                    for($i=0; $i < count($_POST['unique_prac_id']) ; $i++) 
                    { 

                        if(!empty($_POST['unique_prac_id'][$i]) && !empty($_POST['external_examiner'][$i]) )
                        {  
                            $external_examiner = $_POST['external_examiner'][$i];
                            $internal_examiner = $_POST['internal_examiner'][$i];

                            $skilledstaff = $_POST['supportstaff'][$i];
                            $lab_tech = $_POST['lab_tech_staff'][$i];

                           $venue1 = $skilledstaff1 =$lab_tech1 =0;
                            if(!empty($_POST['supportstaff1']))
                            {
                              $skilledstaff1 = $_POST['supportstaff1'][$i];
                            }

                            if(!empty($_POST['lab_tech_staff1']))
                            {
                              $lab_tech1 = $_POST['lab_tech_staff1'][$i];
                            }

                            if(!empty($_POST['venue1']))
                            {
                              $venue1 = $_POST['venue1'][$i];
                            }
                           // echo  $venue1.'='.$skilledstaff1.'='.$lab_tech1; exit;
                            $venue = $_POST['venue'][$i];

                            $external_examiner2 =$internal_examiner2 ='';
                            if(!empty($_POST['external_examiner1']))
                            {
                              $external_examiner2 = $_POST['external_examiner1'][$i];
                              $internal_examiner2 = $_POST['internal_examiner1'][$i];
                            }
                            $unique_prac_id = $_POST['unique_prac_id'][$i];                            
                            $getCat = Categorytype::findOne(['coe_category_type_id'=>$exam_session]);

                            if(isset($getCat) && !empty($getCat))
                            {
                              $check_inserted = PracticalExamTimetable::find()->where(['exam_year'=>$year,'exam_month'=>$month,'unique_prac_id'=>$unique_prac_id,'exam_session'=>$getCat['coe_category_type_id']])->one();  
                            }
                            else
                            {
                              $check_inserted = PracticalExamTimetable::find()->where(['exam_year'=>$year,'exam_month'=>$month,'unique_prac_id'=>$unique_prac_id])->one();
                            }
                            if(!empty($check_inserted))
                            {
                                 $exam_dates =DATE('Y-m-d', strtotime($_POST['exam_dates'][$i]));
                                 if(isset($section_name) && !empty($section_name)  &&  !empty($getCat) )
                                  { 
                                    $update_query = $connection->createCommand('UPDATE coe_prac_exam_ttable  as A join coe_student_mapping as B On A.student_map_id=B.coe_student_mapping_id  set internal_examiner_name="'.$internal_examiner.'", external_examiner_name="'.$external_examiner.'", internal_examiner2="'.$internal_examiner2.'", external_examiner2="'.$external_examiner2.'", A.updated_at="'.$created_at.'",A.updated_by="'.$updateBy.'",venue="'.$venue.'", skill_support_staff="'.$skilledstaff.'", lab_tech_staff="'.$lab_tech.'", skill_support_staff1="'.$skilledstaff1.'", lab_tech_staff1="'.$lab_tech1.'",venue1="'.$venue1.'"  where A.exam_year="'.$year.'" and A.exam_month="'.$month.'" and A.exam_date="'.$exam_dates.'" and B.section_name="'. $section_name.'" and A.exam_session="'.$getCat['coe_category_type_id'].'" and A.unique_prac_id="'.$unique_prac_id.'"')->execute();

                                  }
                                  else if(isset($section_name) && !empty($section_name))
                                  {
                                   
                                      $update_query = $connection->createCommand('UPDATE coe_prac_exam_ttable  as A join coe_student_mapping as B On A.student_map_id=B.coe_student_mapping_id  set internal_examiner_name="'.$internal_examiner.'", external_examiner_name="'.$external_examiner.'", internal_examiner2="'.$internal_examiner2.'", external_examiner2="'.$external_examiner2.'", A.updated_at="'.$created_at.'",A.updated_by="'.$updateBy.'",venue="'.$venue.'", skill_support_staff="'.$skilledstaff.'", lab_tech_staff="'.$lab_tech.'", skill_support_staff1="'.$skilledstaff1.'", lab_tech_staff1="'.$lab_tech1.'",venue1="'.$venue1.'" where A.exam_year="'.$year.'" and A.exam_month="'.$month.'" and A.exam_date="'.$exam_dates.'" and B.section_name="'. $section_name.'" and exam_session="'.$getCat['coe_category_type_id'].'" and A.unique_prac_id="'.$unique_prac_id.'"')->execute();
                                  }
                                  else if(!empty($_POST['exam_dates'][$i]))
                                  {
                                   
                                   $update_query = $connection->createCommand('UPDATE coe_prac_exam_ttable set internal_examiner_name="'.$internal_examiner.'", external_examiner_name="'.$external_examiner.'", internal_examiner2="'.$internal_examiner2.'", external_examiner2="'.$external_examiner2.'", updated_at="'.$created_at.'",updated_by="'.$updateBy.'",venue="'.$venue.'", skill_support_staff="'.$skilledstaff.'", lab_tech_staff="'.$lab_tech.'", skill_support_staff1="'.$skilledstaff1.'", lab_tech_staff1="'.$lab_tech1.'",venue1="'.$venue1.'" where exam_year="'.$year.'" and exam_month="'.$month.'" and exam_date="'.$exam_dates.'" and unique_prac_id="'.$unique_prac_id.'"')->execute();

                                  }
                                  else
                                  {
                                     $update_query = $connection->createCommand('UPDATE coe_prac_exam_ttable set internal_examiner_name="'.$internal_examiner.'", external_examiner_name="'.$external_examiner.'", internal_examiner2="'.$internal_examiner2.'", external_examiner2="'.$external_examiner2.'", updated_at="'.$created_at.'",updated_by="'.$updateBy.'",venue="'.$venue.'", skill_support_staff="'.$skilledstaff.'", lab_tech_staff="'.$lab_tech.'", skill_support_staff1="'.$skilledstaff1.'", lab_tech_staff1="'.$lab_tech1.'",venue1="'.$venue1.'" where exam_year="'.$year.'" and exam_month="'.$month.'" and unique_prac_id="'.$unique_prac_id.'"')->execute();
                                  }
                            
                               if($update_query)
                                {
                                    $totalSuccess+=1;
                                    $dispResults[] = ['type' => 'S',  'message' => 'Success'];    
                                }
                                else
                                {
                                    $dispResults[] = ['type' => 'E',  'message' => 'Error'];
                                }                            
                                Yii::$app->ShowFlashMessages->setMsg('Success','Practical External Examiner Updated Successfully!!!');
                            }
                            else
                            {
                                Yii::$app->ShowFlashMessages->setMsg('Error','Unable to Update the record');
                            }
                            
                        }//Not Empty of the Register Number
                       
                    } // For Loop
                    
                     if($totalSuccess>0)
                    {
                     
                        $getSubsInfo = new Query();              
                        $getSubsInfo->select(['F.subject_name','F.subject_code','B.exam_date','degree_code','programme_code','z.category_type','r.section_name','EF.faculty_name as external_examiner_name', 'IF.faculty_name as internal_examiner_name','EF.college_code','EF.phone_no','internal_examiner2','external_examiner2','v.category_type as venue']);
                        $getSubsInfo->from('coe_subjects_mapping as E')                
                        ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                        ->join('JOIN', 'coe_prac_exam_ttable as B', 'B.subject_map_id=E.coe_subjects_mapping_id and B.batch_mapping_id=E.batch_mapping_id')
                        ->join('JOIN', 'coe_bat_deg_reg as C', 'C.coe_bat_deg_reg_id=B.batch_mapping_id and C.coe_bat_deg_reg_id=E.batch_mapping_id')
                        ->join('JOIN', 'coe_degree as D', 'D.coe_degree_id=C.coe_degree_id')
                        ->join('JOIN', 'coe_programme as A', 'A.coe_programme_id=C.coe_programme_id')
                         ->join('JOIN', 'coe_category_type as  z', 'z.coe_category_type_id=B.exam_session')
                         ->join('JOIN', 'coe_valuation_faculty as IF', 'IF.coe_val_faculty_id=B.internal_examiner_name')
                         ->join('JOIN', 'coe_valuation_faculty as EF', 'EF.coe_val_faculty_id=B.external_examiner_name')
                         ->join('JOIN', 'coe_student_mapping as  r', 'r.coe_student_mapping_id=B.student_map_id')
                         ->join('JOIN', 'coe_category_type as  v', 'v.coe_category_type_id=B.venue')
                        ->Where(['exam_year'=>$year,'exam_month'=>$month]);
                        if(isset($_POST['PracticalExamTimetable']['batch_mapping_id']))
                        {
                          $getSubsInfo->andWhere(['=','E.batch_mapping_id',$_POST['PracticalExamTimetable']['batch_mapping_id']])
                                      ->andWhere(['=','B.batch_mapping_id',$_POST['PracticalExamTimetable']['batch_mapping_id']]);   
                        }
                      
                       $getSubsInfo->groupBy('unique_prac_id')->orderBy('unique_prac_id');   
                        //echo $getSubsInfo->createCommand()->getrawsql();exit;                 
                        $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
                        
                        if(!empty($getSubsInfoDet))
                        {
                            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                            $table='';
                            $get_month_name=Categorytype::findOne($month);
                            $header = $footer = $final_html = $body = '';
                            $header = '<table width="100%" class="table table-bordered table-responsive bulk_edit_table table-hover"  >
                                <tr>
                                        
                                          <td> 
                                            <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                                          </td>

                                          <td colspan=7 align="center"> 
                                              <center><b>'.$org_name.'</b></center>
                                              <center> '.$org_address.'</center>
                                              
                                              <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                                         </td>
                                          <td align="center">  
                                            <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                                          </td>
                                        </tr>
                                        
                                        
                                        <tr>
                                        <td align="center" colspan=9><h5>PRACTICAL EXAMINATIONS '.$year.' - '.strtoupper($get_month_name['description']).' EXTERNAL EXAMINER\' LIST </h5>
                                        </td></tr>
                                        <tr>
                                       
                                        <tr class="table-danger">
                                            <th>SNO</th>  
                                            <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)).'</th>
                                            <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." CODE").'</th>
                                            <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE").'</th>
                                            <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME").'</th>
                                            <th>EXAM SESSION</th>
                                            <th>EXAM VENUE</th>
                                            <th>EXTERNAL EXAMINER</th>
                                            <th>PHONE NUMBER</th>
                                        </tr>               
                                        
                                        ';
                             

                              $increment = 1;
                              $Num_30_nums = 0; $ext2_ph='';
                                foreach ($getSubsInfoDet as $value)
                                {

                                  $clg_code=$value["college_code"];

                                  if(empty($clg_code)){$clg_code='SKCT';}


                                  $ext_faculty2=Yii::$app->db->createCommand("SELECT concat(faculty_name,' - ',college_code) as faculty_name,phone_no FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['external_examiner2']."'")->queryOne();
                                  $ext_faculty='';
                                  if(!empty($ext_faculty2))
                                  {
                                    $ext_faculty=$value["external_examiner_name"].' - '.$clg_code.'<br>'.$ext_faculty2['faculty_name'];
                                    $ext2_ph=" <br> ".$ext_faculty2['phone_no'];
                                  }
                                  else
                                  {
                                     $ext_faculty=$value["external_examiner_name"].' - '.$clg_code;
                                  }
                                   
                                   $body .='<tr><td>'.$increment.'</td><td>'.DATE('d-m-Y',strtotime($value["exam_date"])).'</td><td>'.$value["degree_code"].'-'.$value["programme_code"].'</td><td>'.$value["subject_code"].'</td><td>'.strtoupper($value["subject_name"]).'</td><td>'.$value["category_type"].'</td><td>'.$value["venue"].'</td><td>'.$ext_faculty.'</td><td>'.$value["phone_no"].$ext2_ph.'</td></tr>';
                                    $increment++;
                                    if($increment%31==0)
                                    {
                                        $Num_30_nums =1;
                                        $html = $header.$body.'</table>';
                                        $final_html .=$html;
                                        $html = $body = '';
                                    }
                                }
                                if($Num_30_nums<=30)
                                  {
                                    $html = $header.$body.'</table>';     
                                  }                  
                              $final_html .=$html;               
                              $content = $final_html;


                                $pdf = new Pdf([                   
                                    'mode' => Pdf::MODE_CORE,                 
                                    'filename' => 'PRACTICAL EXTERNAL EXAMINER VERIFICATION REPORT.pdf',                
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
                                               
                                    'options' => ['title' => strtoupper('PRACTICAL').' EXTERNAL EXAMINER '],
                                    'methods' => [ 
                                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                                        'SetFooter'=>[strtoupper('PRACTICAL').' EXTERNAL EXAMINER VERIFICATION REPORT '.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                                    ],
                                    
                                ]);
                                
                                $pdf->marginLeft="8";
                                $pdf->marginRight="8";
                                $pdf->marginBottom="8";
                                $pdf->marginFooter="8";
                                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                                $headers = Yii::$app->response->headers;
                                $headers->add('Content-Type', 'application/pdf');
                                return $pdf->render(); 
                        } // Successfull data Available
                        else
                        {
                             Yii::$app->ShowFlashMessages->setMsg('Error','NO DATA FOUND');
                             return $this->redirect(['allocate-examiner']);
                        }
                    } // Successful insertion
                    else
                    {
                         Yii::$app->ShowFlashMessages->setMsg('Error','Unable to save records');
                         return $this->redirect(['allocate-examiner']);
                    }
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('ERROR','KINDLY SELECT INTERNAL & EXTERNAL FACULTY AND SUBMIT!!');
                    return $this->redirect(['allocate-examiner']);
                }
                
                
            } else {
                Yii::$app->ShowFlashMessages->setMsg('WELCOME','WELCOME TO PRACTICAL EXAMINER ALLOCATION');
                return $this->render('allocate-examiner', [
                    'model' => $model,
                    'student'=>$student,
                    'markEntry'=>$markEntry,
                    'MarkEntryMaster'=>$MarkEntryMaster,
                ]);
            }
        
        
    }
    /**
     * Updates an existing PracticalExamTimetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionReportExaminer()
    {
        $model = new PracticalExamTimetable();
        if ($model->load(Yii::$app->request->post())) 
        {
            return $this->redirect(['report-examiner']);
        } else {
            return $this->render('report-examiner', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PracticalExamTimetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      $checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());

      if($checkAccess=='Yes')
      {
          $model = $this->findModel($id);
          if ($model->load(Yii::$app->request->post()) && isset($_POST['PracticalExamTimetable']['out_of_100']) && !empty($_POST['PracticalExamTimetable']['out_of_100']) ) 
          {
            
            $changeEse = $_POST['PracticalExamTimetable']['out_of_100'];
            if(!empty($changeEse) && $changeEse<=100 )
            {  
               $updaeQuery = Yii::$app->db->createCommand('UPDATE coe_prac_exam_ttable set out_of_100 = "'.$changeEse.'" WHERE coe_prac_exam_ttable_id="'.$id.'" ')->execute();
               Yii::$app->ShowFlashMessages->setMsg('Success','Updated Successfully!!!');
            }//Not Empty of the Register Number
              return $this->redirect(['index']);
          } 
          else 
          {
              return $this->render('update_stu_data', [
                  'model' => $model,        
              ]);
          }
      }
      else
        {
            $lockUser = Yii::$app->db->createCommand('UPDATE user SET status="11" WHERE id="'.Yii::$app->user->getId().'"')->execute();
            $created_by = $updated_by = Yii::$app->user->getId();
            $created_at = $updated_at = date("Y-m-d H:i:s");
            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            $filename =  Yii::getAlias("@webroot").'/access_logs/log_'.date("j.n.Y").'.txt';
            $content  = "User Name: ".Yii::$app->user->getUsername().' - '.date("F j, Y, g:i a").PHP_EOL.
                        "Accessed URLS: ".$url.PHP_EOL.
                        "----------------------------------------------------------------".PHP_EOL;            
            $removed_path = str_replace('index.php', '', parse_url($url, PHP_URL_PATH));
            $image_path = parse_url($url, PHP_URL_SCHEME)."://".parse_url($url, PHP_URL_HOST).parse_url($url, PHP_URL_PORT).$removed_path.'images/notfound.png'; 
            
            $image_path = Yii::getAlias("@web").'/images/notfound.png'; 

            if(file_put_contents($filename, $content.PHP_EOL , FILE_APPEND | LOCK_EX))
            {   
                $file_content = file_get_contents($filename, true);
                echo "<div style='width:1000px;  text-align: center; margin: 0 auto;'><img src='".$image_path."' alt='not found' height='600' width='900' align='center' /></div>";
                
            }
            unset($_SESSION);
            session_destroy();
            Yii::$app->ShowFlashMessages->setMsg('Error','OOOPS You are not allowed!!! Your Account is Locked!!!');
            return $this->redirect(['site/index']);            
        }
      
    }

    /**
     * Deletes an existing PracticalExamTimetable model.
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
     * Finds the PracticalExamTimetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PracticalExamTimetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PracticalExamTimetable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function valueReplaceNumber($array_data)
    {
        $array= array('0'=>'ZERO','1'=>'ONE','2'=>'TWO','3'=>'THREE','4'=>'FOUR','5'=>'FIVE','6'=>'SIX','7'=>'SEVEN','8'=>'EIGHT','9'=>'NINE','10'=>'TEN','-'=>'ABSENT');  
        $return_string='';
        for($i=0;$i<count($array_data);$i++)
        {
            $return_string .=$array[$array_data[$i]]." ";
        }
        return !empty($return_string)?$return_string:'No Data Found';
           
    }
    /**
     * Creates a new PracticalExamTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEditDates()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        if ($model->load(Yii::$app->request->post())) 
        {           
            $created_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId(); 
            
            if(isset($_POST['prac_id']) && count($_POST['prac_id']>0) && !empty($_POST['reason']) && !empty($_POST['new_exam_date']))
            {
                $new_exam_date = $_POST['new_exam_date'];
                $prev_exam_date = $model->exam_date;
                $reason = $_POST['reason'];
                for($i=0;$i<count($_POST['prac_id']);$i++)
                {
                  $exam_update = PracticalExamTimetable::findOne(['exam_year'=>$model->exam_year,'exam_month'=>$model->exam_month,'exam_date'=>$prev_exam_date,'exam_session'=>$model->exam_session,'subject_map_id'=>$_POST['sub_map_id'][$i],'student_map_id'=>$_POST['reg_number'][$i]]);
                  if(!empty($exam_update))
                  {
                    $exam_update->updateAttributes(['exam_date'=>$new_exam_date,'update_reason'=>$reason]);
                  }
                  
                }
                //// TRCKING CODE STARTS //////
                              
                $updated_from = ucwords( str_replace('-', ' ', Yii::$app->controller->action->controller->id.' '.Yii::$app->controller->action->id) );
                $data_updated = 'Practical Exam Dates Updated New Exam Date : '.$new_exam_date.' Prev Exam Date '.$prev_exam_date.' Reason : '.$reason;
                $data_array = ['subject_map_id'=>0,'updated_link_from'=>$updated_from,'data_updated'=>nl2br($data_updated),'exam_month'=>$model->exam_month,'exam_year'=>$model->exam_year,'student_map_id'=>0];            
                $update_track = ConfigUtilities::updateTracker($data_array);

                //// TRCKING CODE ENDS //////

            }
            Yii::$app->ShowFlashMessages->setMsg('Success','EXAM DATES UPDATE SUCCESSFULLY!!!');
            return $this->redirect(['edit-dates']);
            
            
        } else {
            Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to Practical Edit Exam Timetable');
            return $this->render('edit-dates', [
                'model' => $model,
                'student'=>$student,
                'markEntry'=>$markEntry,
                'MarkEntryMaster'=>$MarkEntryMaster,
            ]);
        }
    }

    public function actionDeletePracticalExamTimetable()
    {
        $model = new PracticalExamTimetable();

        Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to Practical Exam Timetable Delete');
        return $this->render('delete-practical-exam-timetable', [
            'model' => $model
        ]);
    }


    public function actionDeletepracticalexamdata() 
    {
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $sub_code = Yii::$app->request->post('sub_code');
        $exam_date = Yii::$app->request->post('exam_date');
        $exam_session = Yii::$app->request->post('exam_session');


        $getSubsInfo = new Query();
        $getSubsInfo->select('A.subject_map_id')
                ->from('coe_practical_entry as A')
                ->join('JOIN', 'coe_prac_exam_ttable as P', 'P.subject_map_id=A.subject_map_id AND P.student_map_id=A.student_map_id AND P.exam_year=A.year AND P.exam_month=A.month')
                ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=A.subject_map_id')
                ->join('JOIN', 'coe_subjects as C', 'C.coe_subjects_id=B.subject_id')
                ->where(['P.exam_date'=>date('Y-m-d',strtotime($exam_date)),'C.subject_code'=>$sub_code,'P.exam_session'=>$exam_session]);
        
        $data_details1 = $getSubsInfo->createCommand()->queryAll(); 


        
        $result=0;
        if(empty($data_details1))
        {

            $getSubsInfo = new Query();
            $getSubsInfo->select('A.coe_prac_exam_ttable_id')
                ->from('coe_prac_exam_ttable as A')
                ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=A.subject_map_id')
                ->join('JOIN', 'coe_subjects as C', 'C.coe_subjects_id=B.subject_id')
                ->where(['A.exam_date'=>date('Y-m-d',strtotime($exam_date)),'C.subject_code'=>$sub_code,'A.exam_session'=>$exam_session]);
        
            $data_details = $getSubsInfo->createCommand()->queryAll();

            //print_r($data_details); exit;

            $deletesuccess=0;
            foreach ($data_details as $value) 
            {
                 $delete= Yii::$app->db->createCommand('DELETE FROM coe_prac_exam_ttable WHERE coe_prac_exam_ttable_id='.$value['coe_prac_exam_ttable_id'])->execute();

                 if($delete)
                {
                    $deletesuccess++;
                }
            }
            
            if($deletesuccess>0)
            {
                 $result=1;
            }
            else
            {
                 $result=0;
            }

        }
        else
        {
             $result=2;
        }

        return Json::encode($result);
        
    }

    public function actionIndexStuBatch()
    {
        $searchModel = new PracStuPerBatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       
        return $this->render('indexstubatch', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateStuPerBatch()
    {
        $model = new PracStuPerBatch();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        if ($model->load(Yii::$app->request->post())) 
        {           
            //print_r($model); exit();
            $created_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId(); 
            if(!empty($model->subject_map_id))
            {
                $model->created_at = $created_at;
                $model->created_by = $updateBy;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success','STUDENT PER BATCH SUCCESSFULLY CREATED');
                    return $this->redirect(['index-stu-batch']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('ERROR','UNABLE TO CREATE STUDENT PER BATCH');
                    return $this->redirect(['create-stu-per-batch']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('ERROR','UNABLE TO CREATE STUDENT PER BATCH');
                return $this->redirect(['create-stu-per-batch']);
            }
            
            
        } 
        else 
        {
            
             return $this->render('createstuperbatch', [
                'model' => $model,
                'student'=>$student,
                'markEntry'=>$markEntry,
                'MarkEntryMaster'=>$MarkEntryMaster,
            ]);
        }
    }

    public function actionUpdateStuPerBatch($id)
    {
        $model = PracStuPerBatch::findOne($id);
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        if ($model->load(Yii::$app->request->post())) 
        {           
            //print_r($model); exit();
            $created_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId(); 
            if(!empty($model->subject_map_id))
            {
                $model->created_at = $created_at;
                $model->created_by = $updateBy;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success','STUDENT PER BATCH UPDATED SUCCESSFULLY');
                    return $this->redirect(['index-stu-batch']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('ERROR','UNABLE TO CREATE STUDENT PER BATCH');
                    return $this->redirect(['update-stu-per-batch']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('ERROR','UNABLE TO CREATE STUDENT PER BATCH');
                return $this->redirect(['update-stu-per-batch']);
            }
            
            
        } 
        else 
        {
            
             return $this->render('updatestuperbatch', [
                'model' => $model,
                'student'=>$student,
                'markEntry'=>$markEntry,
                'MarkEntryMaster'=>$MarkEntryMaster,
            ]);
        }
    }

    public function actionDeleteStuPerBatch($id)
    {
        $subject_map_id = Yii::$app->db->createCommand("SELECT * FROM  coe_prac_stu_per_batch WHERE coe_spb_id=".$id)->queryOne();

        $checksubject = Yii::$app->db->createCommand("SELECT count(subject_map_id) FROM  coe_prac_exam_ttable WHERE subject_map_id=".$subject_map_id['subject_map_id']." AND exam_year=".$subject_map_id['exam_year']." AND exam_month=".$subject_map_id['exam_month']." AND mark_type=".$subject_map_id['exam_type'])->queryScalar();

        if($checksubject==0)
        {
            $delete= Yii::$app->db->createCommand('DELETE FROM coe_prac_stu_per_batch WHERE coe_spb_id='.$id)->execute();
            
            if($delete)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Successfully Deleted..");
                return $this->redirect(['index-stu-batch']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Can't Delete! Please Check");

                return $this->redirect(['index-stu-batch']);
            }

            
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Can't Delete! Subject Practical Schedule Created! Please Check");

            return $this->redirect(['index-stu-batch']);
        }
    }

    public function actionCreateLabSchedule()
    {
        $model = new PracStuPerBatch();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        if (Yii::$app->request->post()) 
        {           
            $created_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId();

            $stu_batch_id = $_POST['PracStuPerBatch']['coe_batch_id'];
            $bat_map_val = $_POST['PracStuPerBatch']['batch_mapping_id'];
            $exam_year = $_POST['PracStuPerBatch']['exam_year'];
            $exam_month = $_POST['PracStuPerBatch']['exam_month'];
            $mark_type=$exam_type = $_POST['PracStuPerBatch']['exam_type'];

            $checksubject = Yii::$app->db->createCommand("SELECT * FROM  coe_prac_stu_per_batch WHERE coe_batch_id=".$stu_batch_id." AND batch_mapping_id=".$bat_map_val." AND exam_year=".$exam_year." AND exam_month=".$exam_month." AND exam_type=".$exam_type)->queryAll();
            //print_r($checksubject); exit;
            if(!empty($checksubject))
            {
                $checElect = Categorytype::find()->where(['description'=>'Elective'])->one();

                $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                
                $reguLar = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Regular%'")->queryScalar();
                
                $getSubsInfo = new Query();
                $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                            ->from('coe_student as A')
                            ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')
                            ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                            ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id');

                $success=0;

                foreach ($checksubject as $value) 
                {        
                    $sub_map_id=$value['subject_map_id'];
                    $getSubMapp = SubjectsMapping::findOne($sub_map_id);

                    if($mark_type==$reguLar)
                    {
                        $sem_id = ConfigUtilities::SemCaluclation($exam_year,$exam_month,$bat_map_val);
                                                
                        if($getSubMapp['subject_type_id']==$checElect['coe_category_type_id'])
                        {
                            $getSemester = SubjectsMapping::findOne($getSubMapp['coe_subjects_mapping_id']);
                            
                            $getSubsInfo->join('JOIN','coe_nominal as G','G.coe_subjects_id=F.coe_subjects_id and G.coe_student_id=A.coe_student_id and G.course_batch_mapping_id=B.course_batch_mapping_id and G.course_batch_mapping_id=E.batch_mapping_id');
                            $getSubsInfo->Where(['G.semester' => $getSemester->semester]);
                            
                        }

                        $getSubsInfo->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val]);
                        
                        if($getSubMapp['subject_type_id']==$checElect['coe_category_type_id'])
                        {
                            $getSubsInfo->andWhere(['G.coe_subjects_id'=>$getSubMapp['subject_id']]);
                        }

                        $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_cat_type]);
                        $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                        
                        $getSubsInfo->groupBy('register_number')->orderBy('register_number,section_name');   

                        $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
                        $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
                        
                    }
                    else
                    {
                        echo "arrear need to create"; exit;
                    }
                    
                    $checkExam = Yii::$app->db->createCommand("SELECT * FROM  coe_prac_exam_ttable WHERE subject_map_id=".$sub_map_id." AND exam_year=".$exam_year." AND exam_month=".$exam_month." AND mark_type=".$exam_type)->queryAll();
 
                     //print_r("SELECT * FROM  coe_prac_exam_ttable WHERE subject_map_id=".$sub_map_id." AND exam_year=".$exam_year." AND exam_month=".$exam_month." AND mark_type=".$exam_type); exit;
                    if(count($checkExam)==0 && count($getSubsInfoDetailsse)>0)   
                    {
                        $rand=date("YmdHis").rand(10,1000);
                        $Loop=1;
                        foreach ($getSubsInfoDetailsse as $stuvalue) 
                        {
                            if($Loop==$value['stu_per_batch_count'])
                            {
                                $rand=date("YmdHis").rand(10,1000);
                                $Loop=1;
                            }
                            else
                            {
                                $Loop++;
                            }

                            $save_model = new PracticalExamTimetable();
                            $save_model->batch_mapping_id = $bat_map_val;
                            $save_model->student_map_id = $stuvalue['coe_student_mapping_id'];
                            $save_model->subject_map_id = $sub_map_id;
                            $save_model->exam_year = $exam_year;
                            $save_model->exam_month = $exam_month;
                            $save_model->mark_type = $exam_type;
                            $save_model->term = 34;
                            $save_model->unique_prac_id = $rand;
                            $save_model->semester = $sem_id;
                            $save_model->created_at = $created_at;
                            $save_model->created_by = $updateBy;
                            //echo "<pre>";
                            //print_r($save_model); exit;
                            if($save_model->save(false))
                            {
                                $success++;
                            }

                            
                        }
                    } 
                    else
                    {
                        // echo "<pre>";
                        //print_r($getSubsInfoDetailsse); exit;
                        Yii::$app->ShowFlashMessages->setMsg('FAILURE','UNABLE TO CREATE PRACTICAL SCHEDULE');
                        return $this->redirect(['create-lab-schedule']);
                    }        
                }
                
                if($success>0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('SUCCESS','SCHEDULE CREATED SUCCESSFULLY');
                    return $this->redirect(['create-lab-schedule']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('FAILURE','UNABLE TO CREATE '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' CONTACT COE');
                    return $this->redirect(['create-lab-schedule']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('ERROR','UNABLE TO CREATE PRACTICAL SCHEDULE PLEASE CHECK PRACTICAL STUDENT PER BATCH COUNT!');
                return $this->redirect(['create-lab-schedule']);
            }
            
            
        } else {
            Yii::$app->ShowFlashMessages->setMsg('WELCOME','Welcome to Practical Perfroma');
            return $this->render('create-lab-schedule', [
                'model' => $model,
                'student'=>$student,
                'markEntry'=>$markEntry,
                'MarkEntryMaster'=>$MarkEntryMaster,
            ]);
        }
    }

    public function actionPracticalStatus()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        
        return $this->render('practical_status', [
            'model' => $model,
            'student'=>$student,
            'markEntry'=>$markEntry,
            'MarkEntryMaster'=>$MarkEntryMaster,
        ]);
        
    }

    public function actionPracticalScheduleReport()
    {
        $model = new PracticalExamTimetable();
        $markEntry = new MarkEntry();
        $student = new Student();
        $MarkEntryMaster = new MarkEntryMaster();
        
        return $this->render('practical_schedule_report', [
            'model' => $model,
            'student'=>$student,
            'markEntry'=>$markEntry,
            'MarkEntryMaster'=>$MarkEntryMaster,
        ]);
        
    }

    public function actionPreExternalExaminerReportPdf()
    {
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $content = $_SESSION['export_eaminer_repo'];        
        //unset($_SESSION['student_application_date']);
            $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE,
                'filename' => 'Practical Schedule Pre Report.pdf',                
                'format' => Pdf::FORMAT_LEGAL,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse: collapse; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width:100%;   }

                        table td table{border: none !important;}
                        table td{
                          
                            font-size: 15px !important; 
                            padding: 1px 0 !important; 
                            text-align: center;
                        }
                        table th{
                            font-size: 11px !important; 
                            text-align: left;
                            padding: 1px 0 !important; 
                           
                        }
                    }   
                ', 
                'options' => ['title' => 'Practical Schedule Pre Report'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['Practical Schedule Pre Report'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ]
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }

    public function actionPreExcelExternalExaminer()
    {
        $content = $_SESSION['export_eaminer_repoxl'];
        $fileName ='Practical Schedule Pre Report'.date('Y-m-d-H-i-s').'.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
}
