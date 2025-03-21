<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\widgets\Growl;
use kartik\mpdf\Pdf;
use yii\db\Query;
use app\models\User;
use app\models\AbsentEntry;
use app\models\HallAllocate;
use app\models\ExamTimetable;
use app\models\DummyNumbers;
use app\models\DummyNumbersSearch;
use app\models\MarkEntry;
use app\models\StudentMapping;
use app\models\MarkEntryMaster;
use app\models\Regulation;
use app\models\Categorytype;
use app\models\Subjects;
use app\models\SubjectsMapping;

/**
 * DummyNumbersController implements the CRUD actions for DummyNumbers model.
 */
class DummyNumbersController extends Controller
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
     * Lists all DummyNumbers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DummyNumbersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //return $this->redirect(['create']);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DummyNumbers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->redirect(['create']);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single DummyNumbers model.
     * @param integer $id
     * @return mixed
     */
    public function actionDummyNumberReport()
    {
        $model = new DummyNumbers();
        $examtimetable = new ExamTimetable(); 
        
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Report');
        return $this->render('dummy-number-report', [
            'model' => $model,
            'examModel'=>$examtimetable,
            
        ]);
        
        
    }

    /**
     * Displays a single DummyNumbers model.
     * @param integer $id
     * @return mixed
     */
    public function actionDummyNumberRegisterNumber()
    {
        $model = new DummyNumbers();
        $examtimetable = new ExamTimetable(); 
        $checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
        if($checkAccess=='Yes')
        {

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
        if(Yii::$app->request->post()) 
        {   
           
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Report');
            return $this->render('dummy-number-register-number', [
                'model' => $model,
                'examModel'=>$examtimetable,
                
            ]);
        }
        
    }
    public function actionVerifyMarks()
    {
        $model = new DummyNumbers();
        $examtimetable = new ExamTimetable(); 
        if(isset($_SESSION['verify_dummy_marks']))  
        {
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
             $content = $_SESSION['verify_dummy_marks'];
                $pdf = new Pdf([
                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' MARK VERIFICATION.pdf',                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_PORTRAIT,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,  
                    'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                    'cssInline' => ' @media all{
                            table{border: 1px solid #ccc; font-family:"Roboto, sans-serif"; width:100%; font-size: 15px; }
                        }   
                    ', 
                    'options' => ['title' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' MARK VERIFICATION'],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                        'SetFooter'=>[strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' MARK VERIFICATION'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                    ],
                    
                ]);

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $headers = Yii::$app->response->headers;
            $headers->add('Content-Type', 'application/pdf');
            return $pdf->render(); 
        
        }     
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Mark Verification');
        return $this->render('verify-marks', [
            'model' => $model,                
            'examtimetable' => $examtimetable,
        ]); 
        
    }
    public function actionDummyStoreInfoPdf()
    {
        $model = new DummyNumbers();
        $examtimetable = new ExamTimetable(); 
        if(isset($_SESSION['dummy_store_info']))  
        {
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));             
            $content = $_SESSION['dummy_store_info'];
            $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE,                 
                'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' INFO.pdf',                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse: collapse; font-family:"Roboto, sans-serif"; width:100%; font-size: 13px; }
                    }   
                ', 
                'options' => ['title' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' INFO'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>[strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' INFO'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
                
            ]);
            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $headers = Yii::$app->response->headers;
            $headers->add('Content-Type', 'application/pdf');
            return $pdf->render();
        }             
    }
    public function actionExcelDummyInfoPdfExcel()
    {

        $content = $_SESSION['dummy_store_info'];          
        $fileName = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY) . ' SEQUENCE' . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    public function actionExcelDummyRegInfoPdf()
    {
        $content = $_SESSION['dummy_store_reg_info'];          
        $fileName = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY) . ' REGISTER NUMBER ' . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    public function actionDummyStoreRegInfoPdf()
    {
        $model = new DummyNumbers();
        $examtimetable = new ExamTimetable(); 
        if(isset($_SESSION['dummy_store_reg_info']))  
        {
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));             
            $content = $_SESSION['dummy_store_reg_info'];
            $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE,                 
                'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).'REGISTER NUMBER INFO.pdf',                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse: collapse; font-family:"Roboto, sans-serif"; width:100%; font-size: 13px; }
                    }   
                ', 
                'options' => ['title' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).'REGISTER NUMBER INFO',  ],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>[ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).'REGISTER NUMBER INFO'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
                
            ]);
            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $headers = Yii::$app->response->headers;
            $headers->add('Content-Type', 'application/pdf');
            return $pdf->render();
        }             
    }

    /**
     * Generates a Dummy No Report for Revaluation Application Students 
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDummyNumberRevaluationReport()
    {
        $model = new DummyNumbers();
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Revaluation Report');
       return $this->render('dummy-number-revaluation-report', [
                'model' => $model,
                
                
            ]);
    }
    public function actionDummyRevaluationPdf()
    {
        
        $content = $_SESSION['reval_report_dummy'];
           
          require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $pdf = new Pdf([
                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' REVALUATION REPORT.pdf',                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_PORTRAIT,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,  
                    
                    'options' => ['title' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' REVALUATION REPORT'],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                        'SetFooter'=>[ strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' REVALUATION REPORT '.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                    ],
                ]);

                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
                return $pdf->render(); 

    }

    /**
     * Creates a new DummyNumbers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DummyNumbers();
        $examModel = new ExamTimetable();

        if ($model->load(Yii::$app->request->post())) 
        {  
            $subject_map_id = $_POST['exam_subject_code'];// as subject_id

            $get_id_details = ['exam_year'=>$_POST['DummyNumbers']['year'],'exam_month'=>$_POST['DummyNumbers']['month']];

            $year = $get_id_details['exam_year'];
            $month = $get_id_details['exam_month'];
            
            $count_of_reg_num = count($_POST['register_number']);
            $created_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId(); 
            $getElecId= Yii::$app->db->createCommand("select * from coe_category_type where description like '%Elective%' ")->queryOne();

            $split_data = ConfigUtilities::getSubjectMappingIds($subject_map_id,$year,$month);

            if(isset($_POST['register_number']))
            {
                for ($i=0; $i < count($_POST['register_number']) ; $i++) 
                { 
                    if(!empty($_POST['register_number'][$i]) && !empty($_POST['dummy_numbers'][$i]))
                    {
                        $find_dum_num = DummyNumbers::find()->where(['student_map_id'=>$_POST['register_number'][$i],'year'=>$get_id_details['exam_year'],'month'=>$get_id_details['exam_month']])->andWhere(['IN','subject_map_id',$split_data])->all();

                        if(!empty($find_dum_num))
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Error',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Already Available');
                        }
                        else
                        {
                            $course_bat_id = StudentMapping::findOne($_POST['register_number'][$i]);
                            $batch_mapping_id = isset($_POST['bat_map_val']) && !empty($_POST['bat_map_val'])?$_POST['bat_map_val']:$course_bat_id->course_batch_mapping_id;

                           if(is_array($split_data))
                            {
                                sort($split_data);
                                for ($k=0; $k <count($split_data) ; $k++) 
                                { 
                                    if(isset($_POST['semester_val']) && !empty($_POST['semester_val']))
                                    {
                                        $semester = $_POST['semester_val']+1;
                                        $getElective = SubjectsMapping::findOne(['coe_subjects_mapping_id'=>$split_data[$k],'batch_mapping_id'=>$batch_mapping_id,'semester'=>$semester]);
                                    }
                                    else
                                    {
                                        $getElective = SubjectsMapping::findOne(['coe_subjects_mapping_id'=>$split_data[$k],'batch_mapping_id'=>$batch_mapping_id]);
                                    }
                                    
                                    if($getElective['subject_type_id']==$getElecId['coe_category_type_id'])
                                    {
                                        $query = new Query();
                                        $query->select('*')
                                            ->from('coe_subjects_mapping a')
                                            ->join('JOIN', 'coe_student_mapping b', 'b.course_batch_mapping_id=a.batch_mapping_id')
                                            ->join('JOIN', 'coe_student C', 'C.coe_student_id=b.student_rel_id')
                                            ->join('JOIN', 'coe_nominal D', 'D.coe_student_id=C.coe_student_id and D.coe_subjects_id=a.subject_id and D.course_batch_mapping_id=b.course_batch_mapping_id and D.course_batch_mapping_id=a.batch_mapping_id and D.semester=a.semester')
                                            ->where(['a.batch_mapping_id' => $batch_mapping_id,'coe_subjects_mapping_id'=>$split_data[$k],'coe_student_mapping_id'=>$_POST['register_number'][$i]]);
                                        $sub_map_id_ins = $query->createCommand()->queryOne();
                                      
                                    }
                                    else
                                    {
                                        if(isset($_POST['semester_val']) && !empty($_POST['semester_val']))
                                        {
                                            $semester = $_POST['semester_val']+1;
                                            $sub_map_id_ins = SubjectsMapping::find()->where(['coe_subjects_mapping_id'=>$split_data[$k],'batch_mapping_id'=>$batch_mapping_id,'semester'=>$semester])->one();
                                        }
                                        else
                                        {
                                            $sub_map_id_ins = SubjectsMapping::find()->where(['coe_subjects_mapping_id'=>$split_data[$k],'batch_mapping_id'=>$batch_mapping_id])->one();
                                        }
                                    }
                                    
                                    if(!empty($sub_map_id_ins))
                                    {
                                        break;
                                    }
                                }

                            }
                            else
                            {
                                if(isset($_POST['semester_val']) && !empty($_POST['semester_val']))
                                {
                                    $semester = $_POST['semester_val']+1;
                                    $sub_map_id_ins = SubjectsMapping::find()->where(['coe_subjects_mapping_id'=>$split_data,'batch_mapping_id'=>$batch_mapping_id,'semester'=>$semester])->one();
                                }
                                else
                                {
                                    $sub_map_id_ins = SubjectsMapping::find()->where(['coe_subjects_mapping_id'=>$split_data,'batch_mapping_id'=>$batch_mapping_id])->one();
                                }
                            }
                            
                            $check_assigned = DummyNumbers::find()->where(['year'=>$get_id_details['exam_year'],'month'=>$get_id_details['exam_month'],'dummy_number'=>$_POST['dummy_numbers'][$i]])->all();

                            if(empty($sub_map_id_ins))
                            {
                                Yii::$app->ShowFlashMessages->setMsg('Error',"SOMETHING WRONG HERE NOT ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." INFORMATION FOUND");
                            }
                            else if(empty($check_assigned))
                            {
                                $model_save = new DummyNumbers();
                                $model_save->student_map_id = $_POST['register_number'][$i];
                                $model_save->subject_map_id = $sub_map_id_ins['coe_subjects_mapping_id'];
                                $model_save->dummy_number = $_POST['dummy_numbers'][$i];
                                $model_save->year = $get_id_details['exam_year'];
                                $model_save->month = $get_id_details['exam_month'];
                                $model_save->created_at = $created_at;
                                $model_save->created_by = $updateBy;
                                $model_save->updated_at = $created_at;
                                $model_save->updated_by = $updateBy;
                                $model_save->save(false);                           
                                unset($model_save);
                                $model_save = new DummyNumbers();

                                Yii::$app->ShowFlashMessages->setMsg('Success',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Created Successfully!!!');
                            }
                            else
                            {
                                Yii::$app->ShowFlashMessages->setMsg('Error',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)." Already Assinged Use Different Number");
                            }
                            
                        }
                        
                    }
                   
                }
                
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','Something Gone Wrong');
            }            
            return $this->redirect(['dummy-numbers/create']);

        } else {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Creation');
            return $this->render('create', [
                'model' => $model,
                'examModel'=>$examModel,
                
            ]);
        }
    }

    public function actionEmptyMarkSheet()
    {
        $model = new DummyNumbers();
        $examModel = new ExamTimetable();

        if ($model->load(Yii::$app->request->post())) 
        {   
            $exam_year = $model->year;
            $exam_month = $model->month;
            $subject_id = $_POST['exam_subject_code'];
            $external_score = Yii::$app->db->createCommand("SELECT dummy_number,subject_code,subject_name FROM coe_dummy_number as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id  WHERE A.year='".$exam_year."' AND A.month='".$exam_month."' and C.coe_subjects_id='".$subject_id."' and B.subject_id='".$subject_id."' group by dummy_number ORDER BY dummy_number")->queryAll();
           
            if(!empty($external_score))
            {
                $subInfo = Subjects::findOne($subject_id);
                $month_disp = Categorytype::findOne($exam_month);
                $subject_code = strtoupper($subInfo['subject_code']);
                $subject_name = strtoupper($subInfo['subject_name']);
                $mark_month = strtoupper($month_disp->description);
                $html = '';
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                $table_open ="<table border=1 width='100%' >";
                $table_close = "</table>";
                $print_data_again =$table_open.'
                <tr>
                    <td> 
                        <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                    </td>
                    <td colspan=8 align="center"> 
                        <center><b><font size="4px">'.$org_name.'</font></b></center>
                        <center>'.$org_address.'</center>
                        
                        <center>'.$org_tagline.'</center> 
                    </td>
                    <td align="right">  
                        <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                    </td>
                </tr>
                 <tr>
                   <th align=center colspan="10">EXTERNAL SCORE CARD FOR '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE : '.$subject_code.' '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME : '.$subject_name.'</th>
                 </tr>
                 <tr>
                   <th align=left colspan="5"> YEAR : '.$exam_year.' /  MONTH :'.$mark_month.'</th>
                   <th align=left colspan="3">DATE</th>
                   <th align=left  colspan="2"></th>
                 </tr>
                 
                <tr>
                  <th>S.NO </th>
                  <th colspan=3> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' </th>
                  <th colspan=2>MARKS OUT OF 100</th>
                  <th colspan=4>MARKS IN WORDS</th>
                </tr>'; 
                $i=1;
                $html .= $print_data_again;
                $bottom_data = "<tr>
                      <td colspan=5>EXAMINER</td>
                      <td colspan=5>CHIEF EXAMINER </td>
                      </tr>
                      <tr height='30px'>
                          <td colspan=5>&nbsp;</td>
                          <td colspan=5>&nbsp;</td>
                      </tr>
                      ";
                foreach ($external_score as $value) 
                {
                    if(($i%31)==0)
                    {
                      $i=1;
                      $html .=$bottom_data.$table_close."<pagebreak />".$print_data_again;
                    } 
                    $html .="<tr>
                          <td  style='line-height: 22px;'  > $i </td>
                          <td  style='line-height: 22px;'  colspan=3 > <b>".strtoupper($value['dummy_number'])."</b> </td>
                          <td  style='line-height: 22px;'  colspan=2 > &nbsp; </td>
                          <td  style='line-height: 22px;'  colspan=4 > &nbsp; </td>
                        </tr>";
                     $i++; 
                }
                $full_html = $html.$bottom_data."</table>";
                
                $_SESSION['dummy_empty_external'] = $full_html;
                
                return $this->render('empty-mark-sheet', [
                    'model' => $model,
                    'examModel'=>$examModel,
                    'full_html' =>$full_html,
                    
                ]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','NO DATA FOUND');
                return $this->redirect(['dummy-numbers/empty-mark-sheet']);
            }

        } else {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Mark Sheet for External Exams');
            return $this->render('empty-mark-sheet', [
                'model' => $model,
                'examModel'=>$examModel,
                
            ]);
        }
    }

    public function actionDummyExternalPdf()
    {
        
        $content = $_SESSION['dummy_empty_external'];
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $pdf = new Pdf([
                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' REVALUATION REPORT.pdf',                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_PORTRAIT,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,  
                     'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: center;  font-family:"Roboto, sans-serif"; width:100%; font-size: 13px; } 
                        
                        table td{
                            border: 1px solid #000;
                            text-align: center;
                        }
                        table th{
                            border: 1px solid #000;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: center;
                        }
                    }   
                ',
                    'options' => ['title' => "EXTERNAL SCORE CARD FOR ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY))],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                        'SetFooter'=>[ "EXTERNAL SCORE CARD FOR ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                    ],
                ]);

                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $headers = Yii::$app->response->headers;
                $headers->add('Content-Type', 'application/pdf');
                return $pdf->render(); 

    }
    // Dumy Number Mark Entry 

    public function actionDummyNumberEntry()
    {
        $model = new DummyNumbers();
        $examtimetable = new ExamTimetable();
        $connection = Yii::$app->db;
        if(Yii::$app->request->post() && isset($_POST['exam_subject_code']) && isset($_POST['dummy_numbers']))
        {  
            $get_id_details_data = ['exam_year'=>$_POST['DummyNumbers']['year'],'exam_month'=>$_POST['DummyNumbers']['month']];
            $sub_ids  = ConfigUtilities::getSubjectMappingIds($_POST['exam_subject_code'],$_POST['DummyNumbers']['year'],$_POST['DummyNumbers']['month']);
            
            $externAl = Categorytype::find()->where(['category_type'=>'ESE'])->one();
            $category_type_id = Categorytype::find()->where(['description'=>"ESE(Dummy)"])->one();
            $cia_type_id = Categorytype::find()->where(['description'=>"CIA"])->orWhere(['description'=>'Internal'])->one();
            $reg_cat_id = Categorytype::find()->where(['description'=>"Regular"])->one();
            $arr_cat_id = Categorytype::find()->where(['description'=>"Arrear"])->one();
            $exam_term_id = Categorytype::find()->where(['description'=>"End"])->one();
            $exam_term = $exam_term_id->coe_category_type_id;

            if(empty($cia_type_id))
            {
                $cia_type_id = Categorytype::find()->where(['description'=>"Internal Final"])->one();
            }
            $created_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId(); 

            $update_data = Yii::$app->db->createCommand('UPDATE coe_dummy_number SET examiner_name="'.$_POST['DummyNumbers']['examiner_name'].'",updated_by="'.$updateBy.'",updated_at="'.$created_at.'",chief_examiner_name="'.$_POST['DummyNumbers']['chief_examiner_name'].'" WHERE dummy_number BETWEEN "'.$_POST['DummyNumbers']['start_number'].'" AND "'.$_POST['DummyNumbers']['end_number'].'" AND year="'.$get_id_details_data['exam_year'].'" AND month="'.$get_id_details_data['exam_month'].'"')->execute();
            for ($i=0; $i <count($_POST['dummy_numbers']) ; $i++) 
            { 
                $sub_map_ids = '';
                $subject_map_id_dum = $_POST['sub_map_id_dumm'][$i];
                $stu_cia_marks_check = MarkEntry::find()->where(['student_map_id'=>$_POST['dummy_numbers'][$i],'category_type_id'=>$cia_type_id->coe_category_type_id,'subject_map_id'=>$subject_map_id_dum])->one();

                if(empty($stu_cia_marks_check))
                {
                    $stu_cia_marks_check_master = MarkEntryMaster::find()->where(['student_map_id'=>$_POST['dummy_numbers'][$i],'subject_map_id'=>$subject_map_id_dum])->orderBy('coe_mark_entry_master_id asc')->one();
                    if(empty($stu_cia_marks_check_master))
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found!! Kindly Fininsh the Internal Mark Entry First.');
                        return $this->redirect(['dummy-numbers/dummy-number-entry']);   
                    }
                    else
                    {
                         $stu_cia_marks_check['category_type_id_marks'] = $stu_cia_marks_check_master['CIA'];
                    }
                    
                }
                $stu_cia_marks = MarkEntry::find()->where(['student_map_id'=>$_POST['dummy_numbers'][$i],'category_type_id'=>$category_type_id->coe_category_type_id,'year'=>$get_id_details_data['exam_year'],'month'=>$get_id_details_data['exam_month'],'subject_map_id'=>$subject_map_id_dum])->all();

                $stu_ese_marks = MarkEntryMaster::find()->where(['student_map_id'=>$_POST['dummy_numbers'][$i],'year'=>$get_id_details_data['exam_year'],'month'=>$get_id_details_data['exam_month'],'subject_map_id'=>$subject_map_id_dum])->all();
                
                if(empty($stu_cia_marks) && empty($stu_ese_marks))
                {
                    $mark_entry_master = new MarkEntryMaster();
                    $mark_entry = new MarkEntry();
                    $regulation = new Regulation();
                    $student = StudentMapping::findOne($_POST['dummy_numbers'][$i]);
                    $checkMarkEntry = MarkEntryMaster::find()->where(['student_map_id'=>$_POST['dummy_numbers'][$i],'subject_map_id'=>$subject_map_id_dum])->one();
                    
                    $sub_map_ids = $subject_map_id_dum;
                    $exam_type = empty($checkMarkEntry)?$reg_cat_id->coe_category_type_id:$arr_cat_id->coe_category_type_id;

                    $getAbsentList = AbsentEntry::find()->where(['exam_type'=>$exam_type,'absent_term'=>$exam_term,'exam_subject_id'=>$sub_map_ids,'exam_month'=>$get_id_details_data['exam_month'],'exam_year'=>$get_id_details_data['exam_year']])->all();
               
                    if(!empty($getAbsentList))
                    {
                        for ($abse=0; $abse <count($getAbsentList) ; $abse++) 
                        { 
                           
                           $check_mark_entry = MarkEntry::find()->where(['category_type_id'=>$stu_cia_marks_check['category_type_id_marks'],'subject_map_id'=>$sub_map_ids,'student_map_id'=>$getAbsentList[$abse]['absent_student_reg'],'year'=>$get_id_details_data['exam_year'],'month'=>$get_id_details_data['exam_month'],'term'=>$exam_term,'mark_type'=>$exam_type])->orderBy('coe_mark_entry_id desc')->one();

                           $check_mark_entry_master = MarkEntryMaster::find()->where(['subject_map_id'=>$sub_map_ids,'student_map_id'=>$getAbsentList[$abse]['absent_student_reg'],'year'=>$get_id_details_data['exam_year'],'month'=>$get_id_details_data['exam_month'],'term'=>$exam_term,'mark_type'=>$exam_type])->one();

                           /*  Send Student Mapping Id, Subject Mapping Id , CIA Marks & ESE Marks to get the appropriate results  */
                    
                            $ab_stu_result_data = ConfigUtilities::StudentResult($_POST['dummy_numbers'][$i],$sub_map_ids,$stu_cia_marks_check['category_type_id_marks'],$_POST['ese_marks'][$i],$get_id_details_data['exam_year'],$get_id_details_data['exam_month']);

                            /*  Result Completed Here */

                            $absen_model_save = new MarkEntry();
                            $absen_model_save->student_map_id = $getAbsentList[$abse]['absent_student_reg'];
                            $absen_model_save->subject_map_id = $sub_map_ids;
                            $absen_model_save->category_type_id =$externAl->coe_category_type_id;
                            $absen_model_save->category_type_id_marks =0;
                            $absen_model_save->year = $get_id_details_data['exam_year'];
                            $absen_model_save->month = $get_id_details_data['exam_month'];
                            $absen_model_save->term = $exam_term;
                            $absen_model_save->mark_type = $exam_type;
                            $absen_model_save->created_at = $created_at;
                            $absen_model_save->created_by = $updateBy;
                            $absen_model_save->updated_at = $created_at;
                            $absen_model_save->updated_by = $updateBy;

                            if(empty($check_mark_entry) && empty($check_mark_entry_master) && $absen_model_save->save(false))
                            {
                                $config_attempt = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CIA_ZEO);
                                $cia_marks = $ab_stu_result_data['attempt']>$config_attempt ? 0:$stu_cia_marks_check['category_type_id_marks']; 

                                unset($absen_model_save);
                                $ab_MarkEntryMaster = new MarkEntryMaster();
                                $ab_MarkEntryMaster->student_map_id = $getAbsentList[$abse]['absent_student_reg'];
                                $ab_MarkEntryMaster->subject_map_id =$sub_map_ids;
                                $ab_MarkEntryMaster->CIA = $cia_marks;
                                $ab_MarkEntryMaster->ESE = 0;
                                $ab_MarkEntryMaster->total = $cia_marks;
                                $ab_MarkEntryMaster->result = 'Absent';
                                $ab_MarkEntryMaster->grade_point = 0;
                                $ab_MarkEntryMaster->grade_name = 'U';
                                $ab_MarkEntryMaster->attempt = $ab_stu_result_data['attempt'];
                                $ab_MarkEntryMaster->year = $get_id_details_data['exam_year'];
                                $ab_MarkEntryMaster->month = $get_id_details_data['exam_month'];
                                $ab_MarkEntryMaster->term = $exam_term;
                                $ab_MarkEntryMaster->mark_type = $exam_type;
                                $ab_MarkEntryMaster->year_of_passing = '';
                                $ab_MarkEntryMaster->status_id = 0;
                                $ab_MarkEntryMaster->created_by = $updateBy;
                                $ab_MarkEntryMaster->created_at = $created_at;
                                $ab_MarkEntryMaster->updated_by = $updateBy;
                                $ab_MarkEntryMaster->updated_at = $created_at;
                                $ab_MarkEntryMaster->save(false);
                                unset($ab_MarkEntryMaster);
                            }

                        }
                    }

                    $mark_entry->student_map_id = $_POST['dummy_numbers'][$i];
                    $mark_entry->subject_map_id = $sub_map_ids;
                    $mark_entry->category_type_id = $category_type_id->coe_category_type_id;
                    $mark_entry->category_type_id_marks = $_POST['ese_marks'][$i];
                    $mark_entry->year = $get_id_details_data['exam_year'];
                    $mark_entry->attendance_remarks = "Allowed";
                    $mark_entry->attendance_percentage = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT_STATUS);
                    $mark_entry->month = $get_id_details_data['exam_month'];
                    $mark_entry->term = $exam_term;
                    $mark_entry->mark_type = $exam_type;
                    $mark_entry->status_id = 0;
                    $mark_entry->created_by = $updateBy;
                    $mark_entry->created_at = $created_at;                
                    $mark_entry->updated_at = $created_at;
                    $mark_entry->updated_by = $updateBy;
                
                    /*  Send Student Mapping Id, Subject Mapping Id , CIA Marks & ESE Marks to get the appropriate results  */
                    
                    $stu_result_data = ConfigUtilities::StudentResult($_POST['dummy_numbers'][$i],$sub_map_ids,$stu_cia_marks_check['category_type_id_marks'],$_POST['ese_marks'][$i],$get_id_details_data['exam_year'],$get_id_details_data['exam_month']);

                    /*  Result Completed Here */

                    if(!empty($stu_cia_marks_check))
                    {
                        $config_attempt = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CIA_ZEO);
                        $cia_marks = $stu_result_data['attempt']>$config_attempt ? 0:$stu_cia_marks_check['category_type_id_marks']; 
                        $year_of_passing = $stu_result_data['result'] == "Pass" || $stu_result_data['result'] == "pass" || $stu_result_data['result'] == "PASS" ? $get_id_details_data['exam_month']. "-" . $get_id_details_data['exam_year'] : '';

						$mark_entry_master->student_map_id = $_POST['dummy_numbers'][$i];
                        $mark_entry_master->subject_map_id = $sub_map_ids;
                        $mark_entry_master->CIA = $cia_marks;
                        $mark_entry_master->ESE = $stu_result_data['ese_marks'];
                        $mark_entry_master->total = ($stu_result_data['ese_marks']+$cia_marks);
                        $mark_entry_master->result = $stu_result_data['result'];
                        $mark_entry_master->grade_point = $stu_result_data['grade_point'];
                        $mark_entry_master->grade_name = $stu_result_data['grade_name'];
                        $mark_entry_master->year = $get_id_details_data['exam_year'];
                        $mark_entry_master->month = $get_id_details_data['exam_month'];
                        $mark_entry_master->term = $exam_term;
                        $mark_entry_master->mark_type = $exam_type;
                        $mark_entry_master->status_id = 0;
                        $mark_entry_master->attempt = $stu_result_data['attempt'];
                        $mark_entry_master->year_of_passing = $year_of_passing;
                        $mark_entry_master->created_by = $updateBy;
                        $mark_entry_master->created_at = $created_at;                
                        $mark_entry_master->updated_at = $created_at;
                        $mark_entry_master->updated_by = $updateBy;
                    }
                
                    $transaction = Yii::$app->db->beginTransaction();
                    try
                       {
                            if($mark_entry->save(false) && $mark_entry_master->save(false))
                            {
                                $transaction->commit();
                                $totalSuccess+=1;
                                $dispResults[] = ['type' => 'S',  'message' => 'Success'];    
                            }
                            else
                            {
                                $transaction->rollback(); 
                                $dispResults[] = ['type' => 'E',  'message' => 'Error'];
                            }
                            unset($mark_entry);unset($mark_entry_master);
                       }
                       catch(\Exception $e)
                       {
                           if($e->getCode()=='23000')
                           {
                               $message = "Duplicate Entry";
                           }
                           else
                           {
                               $message = "Error";
                           }
                           $dispResults[] = ['type' => 'E',  'message' => $message];
                       }

                    Yii::$app->ShowFlashMessages->setMsg('Success','Successfully Updated');
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error','Unable to Insert the Data. Marks Already Exists');
                }
                
            }
            if(isset($totalSuccess) && $totalSuccess>0)
            {
                $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                $sub_map_id =$sub_ids;
                $subjectId = $_POST['exam_subject_code'];
                $end_number =$_POST['DummyNumbers']['end_number'];
                $start_number =$_POST['DummyNumbers']['start_number'];
               
                $examiner_name = $_POST['DummyNumbers']['examiner_name'];
                $chief_examiner_name = $_POST['DummyNumbers']['chief_examiner_name'];
                $get_id_details = ['exam_year'=>$_POST['DummyNumbers']['year'],'exam_month'=>$_POST['DummyNumbers']['month']];
                $category_type_id = Categorytype::find()->where(['description'=>"ESE(Dummy)"])->one();

                 if(is_array($sub_ids))
                {
                    sort($sub_ids);
                    $check_sub_ids = '';
                    for ($K=0; $K <count($sub_ids) ; $K++) 
                    { 
                        $subject_ids = $sub_ids[$K];
                        $check_sub_ids .=$sub_ids[$K].",";
                    }
                    $check_sub_ids=trim($check_sub_ids,',');
                }
                else
                {
                    $subject_ids = $sub_ids;
                    $check_sub_ids = $subject_ids;
                }

                //Verify the data submission with the below query.
                $get_exam_subj_details = ExamTimetable::find()->where(['exam_year'=>$get_id_details['exam_year'],'exam_month'=>$get_id_details['exam_month']])->andWhere(['IN','subject_mapping_id',$sub_ids])->one();

                $subject_mapo = SubjectsMapping::findOne($subject_ids);

                $subject_name = Subjects::findOne(['coe_subjects_id'=>$subject_mapo->subject_id]);
                // Have to write the count of above query code
				$created_20_days = date('Y-m-d',strtotime('-20 days')); 
                $get_stu_data = "SELECT D.dummy_number,E.subject_code,E.subject_name,A.category_type_id_marks as dummy_marks FROM coe_mark_entry AS A JOIN coe_student_mapping As B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects as E ON E.coe_subjects_id=C.subject_id JOIN coe_dummy_number as D ON D.student_map_id=A.student_map_id AND D.subject_map_id=A.subject_map_id WHERE category_type_id='".$category_type_id->coe_category_type_id."' AND D.year='".$get_id_details['exam_year']."' and A.year='".$get_id_details['exam_year']."' and A.month='".$get_id_details['exam_month']."' AND D.month='".$get_id_details['exam_month']."' AND E.coe_subjects_id='".$subjectId."' and C.subject_id='".$subjectId."' and status_category_type_id NOT IN ('".$det_disc_type."') and DATE(A.created_at)>='".$created_20_days."' AND D.dummy_number between ".$start_number." AND ".$end_number." order by D.dummy_number ";
                $verify_stu_data = Yii::$app->db->createCommand($get_stu_data)->queryAll();
                $get_month_name = Categorytype::findOne($get_id_details['exam_month']);
                $html='';
                if(count($verify_stu_data)>0)
                { 
                    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                    /* 
                    *   Already Defined Variables from the above included file
                    *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
                    *   use these variables for application
                    *   use $file_content_available="Yes" for Content Status of the Organisation
                    */         
                  $header = $footer = $final_html = $body = '';
                  $header = '<table width="100%" >
                    <thead class="thead-inverse">
                    <tr>                            
					  <td> 
						<img witdth="100" height="100"  src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
					  </td>

					  <td colspan=2 align="center"> 
						  <center><b>'.$org_name.'</b></center>
						  <center> '.$org_address.'</center>
						  
						  <center class="tag_line"><b>'.$org_tagline.'</b></center> 
					 </td>
					  <td align="center">  
						<img witdth="100" height="100"  class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
					  </td>
                    </tr>
                            <tr>
                            <td align="center" colspan=4><h5>AUTONOMOUS END SEMESTER EXAMINATIONS '.strtoupper($get_month_name['description']).' '.$get_id_details['exam_year'].' </h5>
                            </td><
							/tr>
                            <tr>
                            <td align="center" colspan=4><h5>STATEMENT OF MARKS</h5></td></tr>
                            <tr>
                                <td align="left"  colspan=2>
                                    Q.P.CODE : '.$get_exam_subj_details->qp_code.'
                                </td>
                                <td align="right" colspan=2>
                                    DATE OF VALUATION : '.date("d/m/Y").'
                                </td> 
                            </tr>
                            <tr>
                                <td align="left" colspan=4> 
                                    '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE").' : ('.$subject_name->subject_code.') '.$subject_name->subject_name.'
                                </td>
                            </tr>
                            <tr class="table-danger">
                                <th>SNO</th>  
                                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).'</th>
                                <th>'.strtoupper("Marks").'</th>
                                <th>'.strtoupper("Marks In Words").'</th>
                            </tr>               
                            </thead> 
                            <tbody>     

                            ';
                  $increment = 1;
                  $footer .='<tr height=45px class ="alternative_border">
                                <td align="left" colspan=2>
                                    Name of the Examiner <br /><br />
                                    <b>'.$examiner_name.'</b> <br />

                                </td>
                                <td align="right" colspan=2>
                                    Name of the Chief Examiner / Controller <br /><br />
                                    <b>'.$chief_examiner_name.'</b> <br />
                                </td> 
                            </tr>
                            <tr>
                                <td height="65px" align="left" colspan=2>
                                   Signature With Date <br /><br /><br />
                                </td>
                                <td  height="65px" align="right" colspan=2>
                                    Signature With Date <br /><br /><br />
                                </td> 
                            </tr></tbody></table>';
                  $Num_30_nums = 0;
                  foreach ($verify_stu_data as $value) 
                  {
                        $split_number = str_split($value["dummy_marks"]);
                        $print_text = $this->valueReplaceNumber($split_number);
                        $body .='<tr height="10px"><td>'.$increment.'</td><td>'.$value["dummy_number"].'</td><td>'.$value["dummy_marks"].'</td><td>'.$print_text.'</td></tr>';
                        $increment++;
                        if($increment%31==0)
                        {
                            $Num_30_nums =1;
                            $html = $header.$body.$footer;
                            $final_html .=$html;
                            $html = $body = '';
                        }
                  } 
                  if($Num_30_nums==0)
                  {
                    $html = $header.$body.$footer;     
                  }                  
                  $final_html .=$html;               
                  $content = $final_html; 

                  $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' MARK.pdf',                
                    'format' => Pdf::FORMAT_A4,                 
                    'orientation' => Pdf::ORIENT_PORTRAIT,                 
                    'destination' => Pdf::DEST_BROWSER,                 
                    'content' => $content,                     
                    'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: center;  font-family:"Roboto, sans-serif"; width:100%; font-size: 15px; } 
                        
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
                    'options' => ['title' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' MARK VERIFICATION'],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                        'SetFooter'=>[strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' MARK VERIFICATION '.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
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
            }
            return $this->redirect(['dummy-numbers/dummy-number-entry']);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Mark Entry');
           return $this->render('dummy-number-entry', [
                'model' => $model,
                'examtimetable' => $examtimetable,
            ]); 
        } 
        
    }


    public function actionRePrintDummyNumber()
    {
        $model = new DummyNumbers();
        $examtimetable = new ExamTimetable();
        
        if(Yii::$app->request->post() && isset($_POST['exam_subject_code']) && isset($_POST['DummyNumbers']['end_number']))
        {  
                $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
                $exam_month =$_POST['DummyNumbers']['month'];
                $exam_year =$_POST['DummyNumbers']['year'];
                $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                $sub_ids  = ConfigUtilities::getSubjectMappingIds($_POST['exam_subject_code'],$exam_year,$exam_month);
                $sub_map_id =$sub_ids;
                $end_number =$_POST['DummyNumbers']['end_number'];
                $start_number =$_POST['DummyNumbers']['start_number'];
                

                $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];;
                $category_type_id = Categorytype::find()->where(['description'=>"ESE(Dummy)"])->one();

                 if(is_array($sub_ids))
                {
                    sort($sub_ids);
                    $check_sub_ids = '';
                    for ($K=0; $K <count($sub_ids) ; $K++) 
                    { 
                        $subject_ids = $sub_ids[$K];
                        $check_sub_ids .=$sub_ids[$K].",";
                    }
                    $check_sub_ids=trim($check_sub_ids,',');
                }
                else
                {
                    $subject_ids = $sub_ids;
                    $check_sub_ids = $subject_ids;
                }

                //Verify the data submission with the below query.
                $get_exam_subj_details = ExamTimetable::find()->where(['exam_year'=>$get_id_details['exam_year'],'exam_month'=>$get_id_details['exam_month']])->andWhere(['IN','subject_mapping_id',$sub_ids])->one();

                $subject_mapo = SubjectsMapping::findOne($subject_ids);

                $subject_name = Subjects::findOne(['coe_subjects_id'=>$subject_mapo->subject_id]);
                // Have to write the count of above query code
				$created_20_days = date('Y-m-d',strtotime('-100 days')); 
                $get_stu_data = "SELECT D.dummy_number,D.examiner_name,D.chief_examiner_name,E.subject_code,E.subject_name,A.category_type_id_marks as dummy_marks FROM coe_mark_entry AS A JOIN coe_student_mapping As B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects as E ON E.coe_subjects_id=C.subject_id JOIN coe_dummy_number as D ON D.student_map_id=A.student_map_id AND D.subject_map_id=A.subject_map_id WHERE category_type_id='".$category_type_id->coe_category_type_id."' and A.year='".$get_id_details['exam_year']."' and A.month='".$get_id_details['exam_month']."' AND D.year='".$get_id_details['exam_year']."' AND D.month='".$get_id_details['exam_month']."' AND D.subject_map_id IN (".$check_sub_ids.") and status_category_type_id NOT IN('".$det_disc_type."') AND D.dummy_number between ".$start_number." AND ".$end_number." and DATE(A.created_at)>='".$created_20_days."' order by D.dummy_number ";
                $verify_stu_data = Yii::$app->db->createCommand($get_stu_data)->queryAll();
                $get_month_name = Categorytype::findOne($get_id_details['exam_month']);
                
                $html='';
                if(count($verify_stu_data)>0)
                { 
                    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                    /* 
                    *   Already Defined Variables from the above included file
                    *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
                    *   use these variables for application
                    *   use $file_content_available="Yes" for Content Status of the Organisation
                    */         
                     foreach ($verify_stu_data as $ssss) 
                      {
                            $examiner_name = strtoupper($ssss['examiner_name']);
                            $chief_examiner_name = strtoupper($ssss['chief_examiner_name']);
                           
                      } 
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
                    <td align="center" colspan=4><h5>AUTONOMOUS END SEMESTER EXAMINATIONS '.strtoupper($get_month_name['description']).' '.$get_id_details['exam_year'].' </h5>
                    </td><
                    /tr>
                    <tr><td align="center" colspan=4><h5>STATEMENT OF MARKS</h5></td></tr>
                    <tr>
                        <td align="left"  colspan=2>
                            Q.P.CODE : '.$get_exam_subj_details->qp_code.'
                        </td>
                        <td align="right" colspan=2>
                            DATE OF VALUATION : '.date("d/m/Y").'
                        </td> 
                    </tr>
                    <tr>
                        <td align="left" colspan=4> 
                            '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE").' : ('.$subject_name->subject_code.') '.$subject_name->subject_name.'
                        </td>
                    </tr>
                    <tr class="table-danger">
                        <th width="30px;">S.NO</th>  
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).'</th>
                        <th>'.strtoupper("Marks").'</th>
                        <th>'.strtoupper("Marks In Words").'</th>
                    </tr>  
                    <tbody>     

                            ';
                  $increment = 1;
                  $footer .='<tr height=45px class ="alternative_border">
                                <td align="left" colspan=2>
                                    Name of the Examiner <br /><br />
                                    <b>'.$examiner_name.'</b> <br />

                                </td>
                                <td align="right" colspan=2>
                                    Name of the Chief Examiner / Controller <br /><br />
                                    <b>'.$chief_examiner_name.'</b> <br />
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
                  foreach ($verify_stu_data as $value) 
                  {
                        $split_number = str_split($value["dummy_marks"]);
                        $print_text = $this->valueReplaceNumber($split_number);
                        $body .='<tr height="10px"><td>'.$increment.'</td><td>'.$value["dummy_number"].'</td><td>'.$value["dummy_marks"].'</td><td><b style="color: #000">'.$print_text.'</b></td></tr>';
                        $increment++;
                        if($increment%31==0)
                        {
                            $Num_30_nums =1;
                            $html = $header.$body.$footer;
                            $final_html .=$html;
                            $html = $body = '';
                        }
                  }
                  if($Num_30_nums==0)
                  {
                    $html = $header.$body.$footer;     
                  } 
                  $final_html .=$html;               
                  $content = $final_html; 

                  $pdf = new Pdf([                   
                    'mode' => Pdf::MODE_CORE,                 
                    'filename' => "Re Print ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).'.pdf',                
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
                                   
                    'options' => ['title' => "Re Print ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY))],
                    'methods' => [ 
                        'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                        'SetFooter'=>["Re Print ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
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
            return $this->redirect(['dummy-numbers/re-print-dummy-number']);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Re Print '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY));
           return $this->render('re-print-dummy-number', [
                'model' => $model,
                'examtimetable' => $examtimetable,
            ]); 
        } 
        
    }

    /**
     * Updates an existing DummyNumbers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        return $this->redirect(['create']);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coe_dummy_number_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DummyNumbers model.
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
     * Finds the DummyNumbers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DummyNumbers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DummyNumbers::findOne($id)) !== null) {
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
}
