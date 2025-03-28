<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use app\models\ExamTimetable;
use app\models\ExamTimetableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Subjects;
use app\models\Nominal;
use app\models\SubjectsMapping;
use app\models\Configuration;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\HallAllocate;
use app\models\StudentMapping;
use app\models\AbsentEntry;
use app\models\MarkEntry;
use app\models\MarkEntryMaster;
use app\models\Categorytype;
use app\models\AnswerPacket;
use kartik\mpdf\Pdf;
use yii\widgets\ActiveForm;
/**
 * ExamTimetableController implements the CRUD actions for ExamTimetable model.
 */
class ExamTimetableController extends Controller
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
     * Lists all ExamTimetable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExamTimetableSearch();
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
                $exam_date = ExamTimetable::findOne($exam_id[$i]);
                $hall_allo_check = HallAllocate::find()->where(['exam_timetable_id'=>$exam_id[$i]])->all();
                $absent_check = AbsentEntry::find()->where(['exam_date'=>$exam_date['exam_date']])->all();
                if(empty($absent_check) && empty($hall_allo_check))
                {
                    $del_ids[$exam_id[$i]] = $exam_id[$i];                    
                }          
                $exam_dates[] = !empty($exam_date) ? date('d-m-Y',strtotime($exam_date['exam_date'])) :'';      
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
                        ->delete('coe_exam_timetable', ['IN','coe_exam_timetable_id',$del_ids])
                        ->execute();
            Yii::$app->ShowFlashMessages->setMsg('Success','Selected '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM). ' Dates <b>'.$exam_dates.'</b> Deleted Successfully!!');
          }
          else
          {
                Yii::$app->ShowFlashMessages->setMsg('Error','Unable to Delete '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM). ' <b>'.$exam_dates.'</b> already in Use');
          }
        }
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM). ' Timetable Management');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewAbsent()
    {
        $model = new AbsentEntry();
        $examTimetable = new ExamTimetable();
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' Management');
        return $this->render('view-absent', [
            'model' => $model,
            'examTimetable' => $examTimetable,
        ]);
    }
    public function actionDeleteAbsent()
    {
        $model = new AbsentEntry();
        $examTimetable = new ExamTimetable();
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Delete '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' Management');
        $checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
        if($checkAccess=='Yes')
        {
            if (Yii::$app->request->post())
            {

            }
            else{
                return $this->render('delete-absent', [
                    'model' => $model,
                    'examTimetable' => $examTimetable,
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

    public function actionConsolidateAbsentList()
    {
        $model = new AbsentEntry();
        $examTimetable = new ExamTimetable();

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Consolidate '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' List');
        return $this->render('consolidate-absent-list', [
            'model' => $model,
            'examTimetable' => $examTimetable,
        ]);
    }

    public function actionCoverAbsent()
    {
        $model = new AbsentEntry();
        $examTimetable = new ExamTimetable();

        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Consolidate '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' List');
        return $this->render('cover-absent', [
            'model' => $model,
            'examTimetable' => $examTimetable,
        ]);
    }

    public function actionBoardWiseAbsent()
    {
        $model = new AbsentEntry();
         $ans = new AnswerPacket();
        $examTimetable = new ExamTimetable();

        //Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Consolidate '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' List');
        return $this->render('board-wise-absent', [
            'model' => $model,
            'examTimetable' => $examTimetable,'ans'=>$ans,
        ]);
    }


    public function actionBoardWiseAbsentList()
    {
        $model = new AbsentEntry();
         $ans = new AnswerPacket();
        $examTimetable = new ExamTimetable();

        //Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Consolidate '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' List');
        return $this->render('board-wise-absent-list', [
            'model' => $model,
            'examTimetable' => $examTimetable,'ans'=>$ans,
        ]);
    }

    public function actionExcelViewAb(){        
        
        $content = $_SESSION['print_ab_list'];
        
        $fileName =ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' List-Printing-'.date('Y-m-d-H-i-s').'.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }


    public function actionViewAbPdf()
    {
        if(isset($_SESSION['absent_list']))
        {
          $content_loop = $_SESSION['absent_list'];
         
          require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
          unset($_SESSION['absent_list']);
          $content = '';
          $content .= '<table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >

                    <tr>
                      <td colspan=2> 
                        <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=6 align="center"> 
                          <center><b><font size="6px">'.$org_name.'</font></b></center>
                          <center> <font size="3px">'.$org_address.'</font></center>
                          
                          <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                     </td>
                      <td colspan=2 align="center">  
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>
                    <thead class="thead-inverse">


                    <tr class="table-danger">
                        
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).'</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).'</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Date".'</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Session".'</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Type".'</th>
                        <th>Register Number</th>
                        <th>Name</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code".'</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Name".'</th>
                        <th>Semester</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' Status</th>

                    </tr>               
                    </thead> 
                    <tbody class="show_ab_data">

                    ';
            foreach ($content_loop as $key => $value) 
            {
                $content .='<tr>';
                $content .='<td>'.$value['batch_name'].'</td>';
                $content .='<td>'.$value['programme_degree_name'].'</td>';
                $content .='<td>'.$value['exam_date'].'</td>';
                $content .='<td>'.$value['exam_session'].'</td>';
                $content .='<td>'.$value['exam_type'].'</td>';
                $content .='<td>'.$value['register_number'].'</td>';
                $content .='<td>'.$value['name'].'</td>';
                $content .='<td>'.$value['subject_code'].'</td>';
                $content .='<td>'.$value['subject_name'].'</td>';
                $content .='<td>'.$value['semester'].'</td>';
                $content .='<td> YES </td>';
                $content .='</tr>';

                $application_name = $value['exam_date']." ".$value['exam_session'];
            }

            $content .='</tbody>
                </table>'; 
            if(isset($_SESSION['print_ab_list']))
            {
                unset($_SESSION['print_ab_list']);
            }
            $_SESSION['print_ab_list'] = $content;
        }
        else
        {
          Yii::$app->ShowFlashMessages->setMsg('Error',"OOPS... Unkown Error Please Refresh the Page / Re-Submit the Form Again Now!!");
          return $this->redirect(['exam-timetable/view-absent']); 
        }
        
        //unset($_SESSION['student_application_date']);
            $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE,
                'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' List-Printing-PDF'.$application_name.".pdf",                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border: none !important; font-family:"Roboto, sans-serif"; width:100%; font-size: 15px; padding: 20px 0px; } table.no-border
                        {
                          border: none;
                        } 
                    }   
                ', 
                'options' => ['title' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' List-Printing-PDF'.$application_name],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>[ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' List-PDF'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ]
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }

    public function actionConsolidateExcelAbPdf()
    {        
         $content = $_SESSION['consolidate_absent_list'];
        $fileName = "CONSOLIDATE ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).date('Y-m-d').'.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }


    public function actionConsolidateAbsentPdf()
    {

        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $content = $_SESSION['consolidate_absent_list'];
        $pdf = new Pdf([
           
            'mode' => Pdf::MODE_CORE,
            'filename' => "CONSOLIDATE ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' LIST.pdf',                
            'format' => Pdf::FORMAT_A4,                 
            'orientation' => Pdf::ORIENT_PORTRAIT,                 
            'destination' => Pdf::DEST_BROWSER,                 
            'content' => $content,  
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: left;  font-family:"Roboto, sans-serif";  border: 1px solid #000; width:100%; } 
                        
                        table td{
                            border: 1px solid #000;
                            text-align: left;
                            font-size: 12px;
                            line-height: 1.5em;
                        }
                        table th{
                            border: 1px solid #000;
                            text-align: left;
                            font-size: 12px;
                            line-height:1.5em;
                        }
                        table td{padding:3px  !important;  } 
                    table tr{ line-height: 30px !important; height: 20px !important;}
                    }   
                ',
            'options' => ['title' => "CONSOLIDATE ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)],
            'methods' => [ 
                'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 

            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }

    public function actionConsolidateExcelBoardPdf()
    {        
         $content = $_SESSION['consolidate_absent_list'];
        $fileName = "CONSOLIDATE BATCH  WISE ANALYSIS  ".'.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }


    public function actionConsolidateAbsentBoardPdf()
    {

        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $content = $_SESSION['consolidate_absent_list'];
        $pdf = new Pdf([
           
            'mode' => Pdf::MODE_CORE,
            'filename' => "Board Wise Analysis",                
            'format' => Pdf::FORMAT_A4,                 
            'orientation' => Pdf::ORIENT_PORTRAIT,                 
            'destination' => Pdf::DEST_BROWSER,                 
            'content' => $content,  
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: left;  font-family:"Roboto, sans-serif";  border: 1px solid #000; width:100%; } 
                        
                        table td{
                            border: 1px solid #000;
                            text-align: left;
                            font-size: 14px;
                            line-height: 1.3em;
                            height:60px;
                        }
                        table th{
                            border: 1px solid #000;
                            text-align: left;
                            font-size: 13px;
                            line-height:1.3em;
                            height:60px;
                        }
                        table td{padding:3px  !important;  } 
                    table tr{ line-height: 40px !important; height: 30px !important;}
                    }   
                ',
            'options' => ['title' => "Board Wise Analysis"],
            'methods' => [ 
                'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 

            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }

    public function actionAbsent()
    {
        $model = new AbsentEntry();
        $examTimetable = new ExamTimetable();
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        if(isset($_POST['update']))
        {
            $total_students = $_POST['totalCount'];
            $stu_ab_id = isset($_POST['ab'])?array_keys($_POST['ab']):[];
            $created_at = date("Y-m-d H:i:s");
            $createdBy = Yii::$app->user->getId(); 
            $external_type = Categorytype::find()->where(['description'=>'ESE'])->one();
            $exam_subject_id = $_POST['exam_subject_id'];
            $exam_month = $_POST['exam_month'];
            $exam_year = $_POST['exam_year'];
            $exam_term = $_POST['absent_term'];
            $exam_type = $_POST['exam_type'];
            if($_POST['entry_type']=='Practical' )
            {
                $exam_semester_id = $_POST['exam_semester_id'];
                $get_cat_entry_type = Categorytype::find()->where(['description'=>'Practical Entry'])->one();
                $absent_entry_type = $get_cat_entry_type['coe_category_type_id'];
                if(!empty($stu_ab_id))
                {
                    for ($i=0; $i <count($stu_ab_id) ; $i++) 
                    { 
                        $stuMaping = StudentMapping::findOne($stu_ab_id[$i]);                
                        $sub_batch_maping = SubjectsMapping::find()->where(['batch_mapping_id'=>$stuMaping->course_batch_mapping_id,'subject_id'=>$exam_subject_id,'semester'=>$exam_semester_id])->one();
                        $subject_map_ab_id = $sub_batch_maping->coe_subjects_mapping_id; 
                        $check_data = "SELECT * FROM coe_absent_entry WHERE absent_student_reg='".$stu_ab_id[$i]."' AND exam_type='".$_POST['exam_type']."' AND absent_term='".$_POST['absent_term']."' and exam_month='".$exam_month."' and exam_year='".$exam_year."' AND exam_subject_id='".$subject_map_ab_id." '";
                        $available_data = Yii::$app->db->createCommand($check_data)->queryAll();
                        if(count($available_data)>0)
                        {
                            
                        } 
                        else{
                            
                            $query_insert = 'INSERT INTO coe_absent_entry (`absent_student_reg`,`exam_type`,`absent_term`,`exam_subject_id`,`exam_absent_status`,`exam_month`,`exam_year`,`created_by`,`created_at`,`updated_by`,`updated_at`) VALUES ("'.$stu_ab_id[$i].'","'.$_POST['exam_type'].'","'.$_POST['absent_term'].'","'.$subject_map_ab_id.'","'.$get_cat_entry_type['coe_category_type_id'].'","'.$exam_month.'","'.$exam_year.'","'.$createdBy.'","'.$created_at.'","'.$createdBy.'","'.$created_at.'")';
                            $res[$stu_ab_id[$i]] = Yii::$app->db->createCommand($query_insert)->execute();
                           
                        }
                    }
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error','NO STUDENT SELECTED');
                }
            }
            else if ($_POST['entry_type']=='Exam') 
            {
                $exam_date = isset($_POST['exam_date'])? date("Y-m-d",strtotime($_POST['exam_date'])):'';
                $get_cat_entry_type = Categorytype::find()->where(['description'=>'Hall Wise Entry'])->one();
                $absent_entry_type = $get_cat_entry_type['coe_category_type_id'];
                for ($i=0; $i <count($stu_ab_id) ; $i++) 
                {
                    $stuMaping = StudentMapping::findOne($stu_ab_id[$i]);                
                    $sub_batch_maping = SubjectsMapping::find()->where(['batch_mapping_id'=>$stuMaping->course_batch_mapping_id,'subject_id'=>$exam_subject_id])->all();
                    $subject_map_ab_id = '';
                    $status=0;
                    foreach ($sub_batch_maping as $value) 
                    { 
                        $getExamDetail = ExamTimetable::find()->where(['subject_mapping_id'=>$value['coe_subjects_mapping_id'],'exam_date'=>$exam_date,'exam_month'=>$exam_month,'exam_type'=>$_POST['exam_type'],'exam_session'=>$_POST['exam_session']])->one();
                        if($status==0 && !empty($getExamDetail))
                        {
                            $status=1;
                            $subject_map_ab_id = $value['coe_subjects_mapping_id'];
                        }
                        
                    }
                    $status=0;
                    if(!empty($exam_date) && !empty($subject_map_ab_id))
                    {
                         $check_data = "SELECT * FROM coe_absent_entry WHERE absent_student_reg='".$stu_ab_id[$i]."' AND exam_type='".$_POST['exam_type']."' AND absent_term='".$_POST['absent_term']."' AND exam_subject_id =".$subject_map_ab_id." and exam_date='".$exam_date."' and exam_month='".$exam_month."' and exam_session='".$_POST['exam_session']."' and exam_year='".$exam_year."' ";
                        $available_data = Yii::$app->db->createCommand($check_data)->queryAll();

                        if(count($available_data)>0)
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Error','Few Records already updated');
                        } 
                        else{
                            $query_insert = 'INSERT INTO coe_absent_entry (`absent_student_reg`,`exam_type`, `exam_year`,`absent_term`,`exam_subject_id`,`exam_absent_status`,`exam_date`,`exam_session`,`exam_month`,`created_by`,`created_at`,`updated_by`,`updated_at`) VALUES ("'.$stu_ab_id[$i].'","'.$_POST['exam_type'].'","'.$exam_year.'","'.$_POST['absent_term'].'","'.$subject_map_ab_id.'","'.$absent_entry_type.'","'.$exam_date.'","'.$_POST['exam_session'].'","'.$exam_month.'","'.$createdBy.'","'.$created_at.'","'.$createdBy.'","'.$created_at.'")';
                            $res[$stu_ab_id[$i]] = Yii::$app->db->createCommand($query_insert)->execute();
                        }
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error','NO DATA FOUND');
                    }
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','NO DATA FOUND');
            }

            if(isset($res) && !empty($res))
            {
               Yii::$app->ShowFlashMessages->setMsg('Success',' Records Updated Successfully');
            }
            else
            {
               Yii::$app->ShowFlashMessages->setMsg('Error',' Please Re-Check your Submission for '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." We found some records were already updated");
            }
            return $this->redirect(['exam-timetable/absent']);
        } // Updating the student as Absent Ends Here 
       
        if($model->load(Yii::$app->request->post())) 
        {   
               
                $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

                $query = new Query();
                $subject = new Query();
                $get_type = CategoryType::find()->where(['coe_category_type_id'=>$model->absent_type])->one();
                $get_exam_type = CategoryType::find()->where(['coe_category_type_id'=>$model->exam_type])->one();
				$year =$_POST['AbsentEntry']['exam_year'];
                $exam_month =$model->exam_month;
                
                $ab_locking = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT_LOCKING);
                $last_exam_data = ExamTimetable::find()->where(['exam_year'=>$year,'exam_month'=>$exam_month])->orderBy('exam_date DESC')->one();
                $today=date('Y-m-d');
                if(!empty($last_exam_data))
                {
                    $grace_period = date_add(date_create($last_exam_data->exam_date), date_interval_create_from_date_string($ab_locking.' days'));
                    $final_date = date_format($grace_period, 'Y-m-d');
                }
                if(isset($final_date) && !empty($final_date) && $today>$final_date)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)." Update Date has been Passed");
                    return $this->redirect(['exam-timetable/absent']);
                }
                
                $exam_subject_id = $model->exam_subject_id;

                $get_mapping_id = SubjectsMapping::find()->where(['subject_id'=>$exam_subject_id])->all();
                
                $exam_subject_id_array =[];
                $exam_subject_id_string ='';
                if(!empty($get_mapping_id))
                {
                    foreach ($get_mapping_id as $key => $value) {
                        $exam_subject_id_array[$value['coe_subjects_mapping_id']] = $value['coe_subjects_mapping_id'];
                        $exam_subject_id_string .=$value['coe_subjects_mapping_id'].",";
                    }
                    $exam_subject_id_array = array_filter($exam_subject_id_array);
                    $exam_subject_id_string = trim($exam_subject_id_string,",");
                }
                
                if(isset($model->exam_date)  && !empty($model->exam_date))
                {
                    $exam_date =  date("Y-m-d",strtotime($model->exam_date));
					$get_exam_month = ExamTimetable::find()->where(['exam_date'=>$exam_date])->one();
					$marks = MarkEntryMaster::find()->where(['year'=>$year,'mark_type'=>$model->exam_type,'term'=>$model->absent_term,'status_id'=>1,'month'=>$get_exam_month->exam_month])->andWhere(['NOT LIKE','result','Absent'])->andWhere(['IN','subject_map_id',$exam_subject_id_array])->all();
                }
				else
				{
					$marks = MarkEntryMaster::find()->where(['year'=>$year,'mark_type'=>$model->exam_type,'month'=>$last_exam_data->exam_month,'status_id'=>1,'term'=>$model->absent_term])->andWhere(['NOT LIKE','result','Absent'])->andWhere(['IN','subject_map_id',$exam_subject_id_array])->all();
				}

                $select_cat = (stristr($get_type->category_type, "Practical") || stristr($get_type->description, "Practical"))?'a.dob':'exam_type.description as ab_type';

                $mapping_id = SubjectsMapping::find()->where(['IN','coe_subjects_mapping_id',$exam_subject_id_array])->all();
                
                $subject_id = Subjects::find()->where(['coe_subjects_id'=>$model->exam_subject_id])->one();
                 if(!empty($marks))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',"Mark Entry Already Completed for <b>".$subject_id->subject_code."</b> So you can not update ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)." Now");
                    return $this->redirect(['exam-timetable/absent']);    
                } 
                
                if(isset($model->exam_date)  && !empty($model->exam_date))
                {
                    $whereCondition = [
                        'k.exam_date'=>$exam_date,
                        'k.exam_session'=>$model->exam_session,
                        'k.exam_type'=>$model->exam_type,
                        'k.exam_term'=>$model->absent_term,
                        'a.student_status'=>'Active',
                    ];
                }
                else
                {
                    $whereCondition = [
                        'a.student_status'=>'Active',
                    ];
                }
	
                
                $query->select(['c.coe_student_mapping_id',$select_cat,'a.register_number','a.name','b.coe_subjects_id','b.subject_code', 'concat(h.degree_name,"-",g.programme_code) as degree_name', 'e.semester','f.description','b.subject_name' ])
                        ->from('coe_student a')
                        ->join('JOIN','coe_student_mapping c','c.student_rel_id = a.coe_student_id')
                        ->join('JOIN','coe_bat_deg_reg d','d.coe_bat_deg_reg_id = c.course_batch_mapping_id')   
                        ->join('JOIN','coe_subjects_mapping e','e.batch_mapping_id = d.coe_bat_deg_reg_id and e.batch_mapping_id = c.course_batch_mapping_id')
                        ->join('JOIN','coe_subjects b','b.coe_subjects_id = e.subject_id')
                        ->join('JOIN','coe_category_type f','f.coe_category_type_id = e.paper_type_id')                        
                        ->join('JOIN','coe_degree h','h.coe_degree_id = d.coe_degree_id')
                        ->join('JOIN','coe_programme g','g.coe_programme_id = d.coe_programme_id');
                        

                if((stristr($get_exam_type->category_type, "Arrear") || stristr($get_exam_type->description, "Arrear")) && isset($model->exam_date) && empty($model->halls) && !empty($model->exam_date))
                {
                   
                    $get_exam_details = ExamTimetable::find()->where(['exam_date'=>$exam_date,'exam_session'=>$model->exam_session,'exam_type'=>$model->exam_type,'exam_term'=>$model->absent_term])->andWhere(['IN','subject_mapping_id',$exam_subject_id_array])->one();
                       
						if(stristr($get_type->category_type, "Practical"))
                        {
                            $query->join('JOIN','coe_mark_entry_master q','q.student_map_id=c.coe_student_mapping_id and q.subject_map_id=e.coe_subjects_mapping_id')
                            ->join('JOIN','coe_category_type exam_type','exam_type.coe_category_type_id = q.mark_type')
                            ->join('JOIN','coe_fees_paid fees','fees.student_map_id = q.student_map_id and fees.subject_map_id = q.subject_map_id')
                            ->andWhere(['NOT LIKE', 'q.result', 'pass'])    
                            ->andWhere(['<>', 'fees.status', 'NO'])    
                            ->groupBy('a.register_number')->orderBy('a.register_number');       
                      
                           $whereCondition = [ 'b.coe_subjects_id'=>$model->exam_subject_id,'e.semester'=>$_POST['AbsentEntry']['exam_semester_id'],'fees.status'=>'YES'];
                        }
                        else if(!empty($get_exam_details))
                        {
                            $query->join('JOIN','coe_exam_timetable k','k.subject_mapping_id = e.coe_subjects_mapping_id')
                            ->join('JOIN','coe_mark_entry_master q','q.student_map_id=c.coe_student_mapping_id and q.subject_map_id=e.coe_subjects_mapping_id')
                            ->join('JOIN','coe_category_type exam_type','exam_type.coe_category_type_id = k.exam_type')
                            ->join('JOIN','coe_fees_paid fees','fees.student_map_id = q.student_map_id and fees.subject_map_id = q.subject_map_id')
                            ->groupBy('a.register_number')
							->orderBy('a.register_number');       
                      
                            $whereCondition = array_merge($whereCondition,[
                                'b.coe_subjects_id'=>$model->exam_subject_id,
                                'q.year_of_passing'=>'',
                                'fees.status'=>'YES',
                            ]);
                        }
                        else
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
                            return $this->redirect(['exam-timetable/absent']);
                        }
                        $query->where($whereCondition)
                        ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                        ->andWhere(['IN', 'e.coe_subjects_mapping_id', $exam_subject_id_array])->orderBy('a.register_number');
                        $exam_result = $query->createCommand()->queryAll();

                         $unset_id = 0;
                         foreach($exam_result as $check_pass)
                         {
                            
                            $check = Yii::$app->db->createCommand('select * from coe_mark_entry_master where subject_map_id IN('.$exam_subject_id_string.') AND 
                            student_map_id="'.$check_pass["coe_student_mapping_id"].'" and result like "%pass%" and (withheld is NULL OR withheld="" ) ')->queryAll(); 
                            if(empty($check))
                            {         
                               
                            }
                            else
                            {
                              unset($exam_result[$unset_id]);
                            }
                            $unset_id++;
                         }
                    array_multisort(array_column($exam_result, 'register_number'),  SORT_ASC, $exam_result);

                   $send_result = $exam_result;
                   if(stristr($get_type->category_type, "Practical"))
                   {
                        if(count($send_result)>0)
                        {
                            return $this->render('absent', [
                                'model' => $model,
                                'examTimetable' => $examTimetable,
                                'send_result'=>$send_result,
                            ]);
                        }
                        else
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
                            return $this->redirect(['exam-timetable/absent']);
                            
                        } 
                   }

                    if(count($send_result)>0)
                    {
                        return $this->render('absent', [
                            'model' => $model,
                            'examTimetable' => $examTimetable,
                            'exam_result' =>$send_result,
                            
                        ]);
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
                        return $this->redirect(['exam-timetable/absent']);
                        
                    } 
                }

                if(stristr($get_type->category_type, "Exam") || stristr($get_type->description, "Exam"))
                {
                    $query->join('JOIN','coe_exam_timetable k','k.subject_mapping_id = e.coe_subjects_mapping_id')
                            ->join('JOIN','coe_category_type exam_type','exam_type.coe_category_type_id = k.exam_type');
                    $whereCondition = array_merge($whereCondition,['b.coe_subjects_id'=>$exam_subject_id,'subject_id'=>$exam_subject_id]);
                            
                    
                } 
                elseif (stristr($get_type->category_type, "Hall") || stristr($get_type->description, "Hall")) 
                {
                    
                    $query->join('JOIN','coe_exam_timetable k','k.subject_mapping_id = e.coe_subjects_mapping_id')
                        ->join('JOIN','coe_hall_allocate y','y.exam_timetable_id = k.coe_exam_timetable_id and y.register_number=a.register_number')
                        
                        ->join('JOIN','coe_category_type exam_type','exam_type.coe_category_type_id = k.exam_type');
                    $whereCondition = array_merge($whereCondition,['y.hall_master_id'=>$model->halls]);
                }
                else if ((stristr($get_type->category_type, "Practical") || stristr($get_type->description, "Practical")) && (stristr($get_exam_type->category_type, "Arrear") || stristr($get_exam_type->description, "Arrear")) )  
                {
                    $status_check = 'Practical-Arr';  
                    $query->join('JOIN','coe_mark_entry_master q','q.student_map_id=c.coe_student_mapping_id and q.subject_map_id=e.coe_subjects_mapping_id')
                        ->join('JOIN','coe_category_type exam_type','exam_type.coe_category_type_id = q.mark_type')
                        ->join('JOIN','coe_fees_paid fees','fees.student_map_id = q.student_map_id and fees.subject_map_id = q.subject_map_id')
                        ->andWhere(['<>', 'fees.status', 'NO'])    
                        ->andWhere(['NOT LIKE', 'q.result', 'pass'])
                        ->groupBy('a.register_number');       
                     
                    $whereCondition = [ 'b.coe_subjects_id'=>$model->exam_subject_id,'e.semester'=>$_POST['AbsentEntry']['exam_semester_id'],'e.batch_mapping_id'=>$_POST['AbsentEntry']['course_batch_id'],'fees.status'=>'YES'];
                }
                else if ((stristr($get_type->category_type, "Practical") || stristr($get_type->description, "Practical")) )  
                {
                    $status_check = 'Practical-Reg';
                    $check_if_nominal = Nominal::findOne(['course_batch_mapping_id'=>$_POST['AbsentEntry']['course_batch_id'],'coe_subjects_id'=>$model->exam_subject_id]);
                    if(count($check_if_nominal)>0 && !empty($check_if_nominal))
                    {
                        $query->join('JOIN','coe_nominal abc','abc.course_batch_mapping_id = e.batch_mapping_id AND abc.coe_subjects_id=b.coe_subjects_id AND abc.coe_student_id=a.coe_student_id');
                        $whereCondition = array_merge($whereCondition,['abc.coe_subjects_id'=>$model->exam_subject_id]);
                    }
                    $whereCondition = [ 'b.coe_subjects_id'=>$model->exam_subject_id,'e.semester'=>$_POST['AbsentEntry']['exam_semester_id'],'e.batch_mapping_id'=>$_POST['AbsentEntry']['course_batch_id']];

                }
                else
                {
                    $whereCondition = $whereCondition;
                }
                
                if(isset($model->exam_date) && isset($model->exam_session) && isset($model->exam_subject_id) && empty($model->halls) && (stristr($get_type->category_type, "Exam") || stristr($get_type->description, "Exam")) )
                {

                    $mapping_id = SubjectsMapping::find()->where(['IN','coe_subjects_mapping_id',$exam_subject_id_array])->all();
                    $subject_query_res=CategoryType::find()->where(['coe_category_type_id'=>$mapping_id[0]['subject_type_id']])->one();
                    if(stristr($subject_query_res->category_type, "Elective") || stristr($subject_query_res->description, "Elective"))
                    {
                        $query->join('JOIN','coe_nominal x','x.coe_student_id = a.coe_student_id and x.coe_subjects_id=b.coe_subjects_id and x.course_batch_mapping_id=e.batch_mapping_id')
                            ->where($whereCondition)
                            ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                            ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                            ->andWhere(['IN', 'coe_subjects_mapping_id', $exam_subject_id_array])
                            ->groupBy('a.register_number')
                            ->orderBy('a.register_number');
                        $exam_result = $query->createCommand()->queryAll();

                    }
                    else
                    {
                        $query->where($whereCondition)
                            ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                            ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                            ->andWhere(['IN', 'coe_subjects_mapping_id', $exam_subject_id_array])
                            ->groupBy('a.register_number')
                            ->orderBy('a.register_number');
                        $exam_result = $query->createCommand()->queryAll();
                    }     

                    if(count($exam_result)>0)
                    {
                        return $this->render('absent', [
                            'model' => $model,
                            'examTimetable' => $examTimetable,
                            'exam_result'=>$exam_result,
                        ]);
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
                            // return $this->render('absent', [
                            //     'model' => $model,
                            //     'examTimetable' => $examTimetable,
                            // ]);
                        return $this->redirect(['exam-timetable/absent']);
                        
                    }
                 
                } // Closing the Exam Entry
                else if (isset($model->exam_date) && isset($model->exam_session) && isset($model->exam_subject_id) && isset($model->halls) && (stristr($get_type->category_type, "Hall") || stristr($get_type->description, "Hall"))  && (stristr($get_exam_type->category_type, "Regular") || stristr($get_exam_type->description, "Regular"))) 
                {
                    
                    $mapping_id = SubjectsMapping::find()->where(['IN','coe_subjects_mapping_id',$exam_subject_id_array])->one();

                    $subject_id = Subjects::find()->where(['coe_subjects_id'=>$model->exam_subject_id])->one();

                    $subject_query_res=CategoryType::find()->where(['coe_category_type_id'=>$mapping_id->subject_type_id])->one();

                    if(stristr($subject_query_res->category_type, "Elective") || stristr($subject_query_res->description, "Elective"))
                    {
                        $query->join('JOIN','coe_nominal x','x.coe_student_id = a.coe_student_id and x.coe_subjects_id=b.coe_subjects_id and x.course_batch_mapping_id=e.batch_mapping_id')
                            ->where($whereCondition)
                            ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                            ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                            ->andWhere(['IN', 'coe_subjects_mapping_id', $exam_subject_id_array])
                            ->groupBy('a.register_number')
                            ->orderBy('a.register_number');
                        $exam_result = $query->createCommand()->queryAll();
                        
                    }
                    else
                    {
                        $query->where($whereCondition)
                            ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                            ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                            ->andWhere(['IN', 'coe_subjects_mapping_id', $exam_subject_id_array])
                            ->groupBy('a.register_number')
                            ->orderBy('a.register_number');
                        $exam_result = $query->createCommand()->queryAll();
                    }       
                    
                    if(count($exam_result)>0)
                    {
                        return $this->render('absent', [
                            'model' => $model,
                            'examTimetable' => $examTimetable,
                            'exam_result'=>$exam_result,
                        ]);
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
                        // return $this->render('absent', [
                        //     'model' => $model,
                        //     'examTimetable' => $examTimetable,
                        // ]);
                        return $this->redirect(['exam-timetable/absent']);
                    }

                } // Closing the Hall Entry Arrear
                else if (isset($model->exam_date) && isset($model->exam_session) && isset($model->exam_subject_id) && isset($model->halls) && (stristr($get_type->category_type, "Hall") || stristr($get_type->description, "Hall"))  && (stristr($get_exam_type->category_type, "Arrear") || stristr($get_exam_type->description, "Arrear"))) 
                {
                    $mapping_id = SubjectsMapping::find()->where(['IN','coe_subjects_mapping_id',$exam_subject_id_array])->one();

                    $subject_id = Subjects::find()->where(['coe_subjects_id'=>$model->exam_subject_id])->one();

                    $subject_query_res=CategoryType::find()->where(['coe_category_type_id'=>$mapping_id->subject_type_id])->one();

                    $query->join('JOIN','coe_mark_entry_master q','q.student_map_id=c.coe_student_mapping_id and q.subject_map_id=e.coe_subjects_mapping_id')
                        ->join('JOIN','coe_fees_paid fees','fees.student_map_id = q.student_map_id and fees.subject_map_id = q.subject_map_id')
                        ->andWhere(['<>', 'fees.status', 'NO'])   
                        ->andWhere(['NOT LIKE', 'q.result', 'pass']);                     
                    $whereCondition = [ 'b.coe_subjects_id'=>$model->exam_subject_id,'y.year'=>$year,'y.month'=>$exam_month,'y.hall_master_id'=>$model->halls,'fees.status'=>'YES'];
                    $query->where($whereCondition)
                            ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                            ->andWhere(['IN', 'coe_subjects_mapping_id', $exam_subject_id_array])
                            ->groupBy('a.register_number')
                            ->orderBy('a.register_number');
                    $exam_result = $query->createCommand()->queryAll();
                    
                    $unset_id = 0;
                     foreach($exam_result as $check_pass)
                     {
                        
                        $check = Yii::$app->db->createCommand('select * from coe_mark_entry_master where subject_map_id IN ('.$exam_subject_id_string.') AND 
                        student_map_id="'.$check_pass["coe_student_mapping_id"].'" and result like "%pass%" and (withheld is NULL OR withheld="" ) ')->queryAll(); 
                        if(empty($check))
                        {         
                           
                        }
                        else
                        {
                          unset($exam_result[$unset_id]);
                        }
                        $unset_id++;
                     }
                    array_multisort(array_column($exam_result, 'register_number'),  SORT_ASC, $exam_result);

                   $exam_result = $exam_result;
                   
                    if(count($exam_result)>0)
                    {
                        return $this->render('absent', [
                            'model' => $model,
                            'examTimetable' => $examTimetable,
                            'exam_result'=>$exam_result,
                        ]);
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
                        // return $this->render('absent', [
                        //     'model' => $model,
                        //     'examTimetable' => $examTimetable,
                        // ]);
                        return $this->redirect(['exam-timetable/absent']);
                    }

                } // Closing the Hall Entry Regular
                else
                {

                    $query->where($whereCondition)
                        ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                    if(isset($status_check) && $status_check=='Practical-Reg')
                    {
                        $query->andWhere(['<>', 'status_category_type_id', $det_cat_type]);
                    }                    
                    $query->groupBy('a.register_number')
                          ->orderBy('a.register_number');
                    $send_result = $query->createCommand()->queryAll(); 
                    
                    $unset_id = 0;
                    foreach($send_result as $check_pass)
                     {
                        $get_sub_map = SubjectsMapping::find()->where(['subject_id'=>$model->exam_subject_id,'batch_mapping_id'=>$_POST['AbsentEntry']['course_batch_id'],'semester'=>$_POST['AbsentEntry']['exam_semester_id']])->one();
                        $sub_mappped_id = $get_sub_map->coe_subjects_mapping_id;
                        $check = Yii::$app->db->createCommand('select * from coe_mark_entry_master where subject_map_id="'.$sub_mappped_id.'" AND 
                        student_map_id="'.$check_pass["coe_student_mapping_id"].'" and result like "%pass%" and (withheld is NULL OR withheld="" ) ')->queryAll(); 
                        if(empty($check))
                        {         
                           
                        }
                        else
                        {
                          unset($send_result[$unset_id]);
                        }
                        $unset_id++;
                     }
                    array_multisort(array_column($send_result, 'register_number'),  SORT_ASC, $send_result);

                   $send_result = $send_result;
                   
                    if(count($send_result)>0)
                    {
                       return $this->render('absent', [
                            'model' => $model,
                            'examTimetable' => $examTimetable,
                            'send_result'=>$send_result,
                        ]);  
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
                        return $this->redirect(['exam-timetable/absent']);
                        
                    }

                                
                }
        return $this->render('absent', [
                'model' => $model,'examTimetable' => $examTimetable,
        ]);

        }
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' Entry');

        return $this->render('absent', [
            'model' => $model,'examTimetable' => $examTimetable,
        ]);
    }

    // Export Exam Time Table
    
    public function actionExportExamTimetable()
    {
        if(isset($_SESSION['export_exam_time_data']))
        {
            unset($_SESSION['export_exam_time_data']);
        }
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $practica = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like 'Practical%'")->queryScalar();
        $model = new AbsentEntry();
        $examTimetable = new ExamTimetable();
        if ($model->load(Yii::$app->request->post())) 
            {            
                $query = new Query();                
                $query->select(['DISTINCT (b.subject_code) as subject_code','b.subject_name','concat(h.degree_name) as degree_name','degree_code','g.programme_name','g.programme_code', 'e.semester','j.category_type as month','i.batch_name','e.batch_mapping_id','e.coe_subjects_mapping_id','k.exam_date','k.exam_year','d.regulation_year','m.category_type as exam_type','n.category_type as exam_session'])
                        ->from('coe_student a')  // If students are available then only data will be exported
                        ->join('JOIN','coe_student_mapping c','c.student_rel_id = a.coe_student_id')
                        ->join('JOIN','coe_bat_deg_reg d','d.coe_bat_deg_reg_id = c.course_batch_mapping_id')   
                        ->join('JOIN','coe_subjects_mapping e','e.batch_mapping_id = d.coe_bat_deg_reg_id')
                        ->join('JOIN','coe_subjects b','b.coe_subjects_id = e.subject_id')
                        ->join('JOIN','coe_degree h','h.coe_degree_id = d.coe_degree_id')
                        ->join('JOIN','coe_batch i','i.coe_batch_id = d.coe_batch_id')
                        ->join('JOIN','coe_programme g','g.coe_programme_id = d.coe_programme_id');
                
                    $query->join('JOIN','coe_exam_timetable k','k.subject_mapping_id = e.coe_subjects_mapping_id')
                        ->join('JOIN','coe_category_type j','j.coe_category_type_id = k.exam_month')
                        ->join('JOIN','coe_category_type m','m.coe_category_type_id = k.exam_type')
                        ->join('JOIN','coe_category_type n','n.coe_category_type_id = k.exam_session');
                    
                    $whereCondition = [
                        'k.exam_year'=>$_POST['ExamTimetable']['exam_year'],
                        'k.exam_month'=>$_POST['ExamTimetable']['exam_month'],
                        'i.coe_batch_id'=>$_POST['AbsentEntry']['batch_id']
                    ];     
                    
                    $query->Where($whereCondition)->andWhere(['<>', 'status_category_type_id', $det_cat_type])->andWhere(['<>', 'status_category_type_id', $det_disc_type])->andWhere(['NOT IN', 'paper_type_id', $practica])
                    ->orderBy('programme_name,semester,k.exam_date');
                    $export_exam_time = $query->createCommand()->queryAll();
                    
                if(isset($export_exam_time) && !empty($export_exam_time))
                {
                    return $this->render('export-exam-timetable', [
                        'model' => $model,'examTimetable' => $examTimetable,'export_exam_time'=>$export_exam_time,
                    ]);
                }
                else
                {
                   Yii::$app->ShowFlashMessages->setMsg('ERROR','NO DATA FOUND');
                    return $this->redirect(['export-exam-timetable']); 
                }
                
        }
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Export '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Timetable');
        return $this->render('export-exam-timetable', [
            'model' => $model,'examTimetable' => $examTimetable
        ]);
    }

    public function actionExcelExamtimetable(){
        $content = $_SESSION['export_exam_time_data'];
        $fileName =ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Time Table-Printing.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }


    public function actionPrintExamtimetable()
    {
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        
        $content = $_SESSION['export_exam_time_data'];
         
        
        //unset($_SESSION['student_application_date']);
            $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE,
                'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Time Table .pdf',                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: center;  font-family:"Roboto, sans-serif"; width:100%; font-size: 20px; } 
                        table tr{
                            border: 1px solid #CCC;
                            padding: 10px;
                        }
                        table td{
                            border: 1px solid #CCC;
                            white-space: nowrap;
                            text-overflow: ellipsis;                            
                            text-align: center;
                            padding: 10px;
                            font-size: 16px;
                            line-height: 2em;
                        }
                        table th{
                            text-overflow: ellipsis;
                            white-space: nowrap;
                            text-align: center;
                            padding: 10px;
                            font-size: 16px;
                            line-height: 2em;
                        }
                    }   
                ', 
                'options' => ['title' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Time Table '],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>[ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Time Table '.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ]
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }




    // Exam Timetable Export Completed 



    public function actionExternal()
    {
        if(isset($_SESSION['external_score_data']))
        {
            unset($_SESSION['external_score_data']);
        }

        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
         $exam_type_g = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%arrear%'")->queryScalar();
       
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $model = new AbsentEntry();
        $examTimetable = new ExamTimetable();
        if ($model->load(Yii::$app->request->post())) 
        { 
            $paper = SubjectsMapping::find()->where(['coe_subjects_mapping_id'=>$model->exam_subject_id])->one();
            
            $section = isset($_POST['AbsentEntry']['stu_section_name'])?$_POST['AbsentEntry']['stu_section_name']:'';
            $paper_type_name = Categorytype::findOne(['coe_category_type_id'=>$paper->paper_type_id]);
            $subject_type_name = Categorytype::findOne(['coe_category_type_id'=>$paper->subject_type_id]);
           
            $query = new Query();
                $whereCondition = [                    
                    'e.batch_mapping_id'=>$_POST['AbsentEntry']['course_batch_id'],
                    'a.student_status'=>'Active',
                ];
            $query->select(['c.coe_student_mapping_id','c.course_batch_mapping_id','a.register_number','a.name','b.coe_subjects_id','b.subject_code', 'concat(h.degree_code,"-",g.programme_code) as degree_name', 'e.semester','b.subject_name','j.category_type as month','i.batch_name','b.CIA_max','b.ESE_max','e.batch_mapping_id','e.coe_subjects_mapping_id','k.qp_code','k.exam_year year','k.exam_type','k.subject_mapping_id','k.exam_term','k.exam_month'])
                    ->from('coe_student a')
                    ->join('JOIN','coe_student_mapping c','c.student_rel_id = a.coe_student_id')
                    ->join('JOIN','coe_bat_deg_reg d','d.coe_bat_deg_reg_id = c.course_batch_mapping_id')   
                    ->join('JOIN','coe_subjects_mapping e','e.batch_mapping_id = d.coe_bat_deg_reg_id')
                    ->join('JOIN','coe_subjects b','b.coe_subjects_id = e.subject_id')
                    ->join('JOIN','coe_degree h','h.coe_degree_id = d.coe_degree_id')
                    ->join('JOIN','coe_batch i','i.coe_batch_id = d.coe_batch_id')
                    ->join('JOIN','coe_programme g','g.coe_programme_id = d.coe_programme_id')
                    ->join('JOIN','coe_category_type xyz','xyz.coe_category_type_id = c.status_category_type_id')
                    ->join('JOIN','coe_categories abcd','abcd.coe_category_id = xyz.category_id');

            if($_POST['AbsentEntry']['exam_type']==$exam_type_g)
            {
                    $query->join('JOIN','coe_mark_entry_master l','l.subject_map_id = e.coe_subjects_mapping_id and l.student_map_id=c.coe_student_mapping_id')
                        ->join('JOIN','coe_exam_timetable k','k.subject_mapping_id = e.coe_subjects_mapping_id')
                        ->join('JOIN','coe_fees_paid fees','fees.student_map_id = l.student_map_id and fees.subject_map_id = l.subject_map_id')
                        ->join('JOIN','coe_category_type j','j.coe_category_type_id = k.exam_month');
                   
                    $get_stu_list = MarkEntryMaster::find()->where(['subject_map_id'=>$model->exam_subject_id,'result'=>'Pass'])->all();

                    $stu_ids = [];
                    foreach ($get_stu_list as $key => $value) 
                    {
                        $stu_ids[] = $value['student_map_id'];
                    }
                    
                    $query->where(['NOT IN','l.student_map_id',$stu_ids]);
                    $query->where(['NOT LIKE','l.result','Pass'])
                          ->andWhere(['<>', 'fees.status', 'NO']);
                    $query->andWhere(['NOT IN','coe_student_mapping_id',$stu_ids]);
                    
                    $whereCondition = array_merge($whereCondition,[
                        'k.subject_mapping_id'=>$model->exam_subject_id,
                        'l.subject_map_id'=>$model->exam_subject_id,
                        'k.exam_type'=>$model->exam_type,
                        'k.exam_term'=>$model->absent_term,
                        'k.exam_year'=>$_POST['AbsentEntry']['year'],
                        'k.exam_month'=>$_POST['AbsentEntry']['exam_month'],
                        'fees.year'=>$_POST['AbsentEntry']['year'],
                        'fees.month'=>$_POST['AbsentEntry']['exam_month'],
                        'fees.status'=>'YES',

                    ]);

               
            }
            else
            {
                 if(stristr($subject_type_name->category_type, "Elective") || stristr($subject_type_name->description, "Elective"))
                { 
                    $query->join('JOIN','coe_nominal l','l.course_batch_mapping_id = d.coe_bat_deg_reg_id and l.coe_student_id=a.coe_student_id and l.coe_subjects_id=b.coe_subjects_id and l.course_batch_mapping_id=e.batch_mapping_id');
                    $query->join('JOIN','coe_exam_timetable k','k.subject_mapping_id = e.coe_subjects_mapping_id')
                        ->join('JOIN','coe_category_type j','j.coe_category_type_id = k.exam_month');
                    $whereCondition = array_merge($whereCondition,[
                        'l.coe_subjects_id'=>$paper->subject_id,
                    ]);
                }
                
                else {
                    $query->join('JOIN','coe_exam_timetable k','k.subject_mapping_id = e.coe_subjects_mapping_id')
                        ->join('JOIN','coe_category_type j','j.coe_category_type_id = k.exam_month');
                    
                    $whereCondition = array_merge($whereCondition,[
                        'k.subject_mapping_id'=>$model->exam_subject_id,
                        'k.exam_type'=>$model->exam_type,
                        'k.exam_term'=>$model->absent_term,
                        'k.exam_year'=>$_POST['AbsentEntry']['year'],
                        'k.exam_month'=>$_POST['AbsentEntry']['exam_month'],
                    ]);
                    
                }
            }    

            $query->Where($whereCondition)
            ->andWhere(['NOT LIKE','xyz.description', 'Discontinued'])->groupBy('a.register_number');
            if(!empty($section) && $section!='All')
            {
                $query->andWhere(['c.section_name'=>$section]);
            }   
            $external_score = $query->createCommand()->queryAll();
           
            return $this->render('external', [
                'model' => $model,
                'examTimetable' => $examTimetable,
                'external_score'=>$external_score,
            ]);
        }
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to External Score Card');
        return $this->render('external', [
            'model' => $model,
            'examTimetable' => $examTimetable,
        ]);
    }
    public function actionExternalFormat()
    {
        if(isset($_SESSION['external_score_data']))
        {
            unset($_SESSION['external_score_data']);
        }

        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
         $exam_type_g = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%arrear%'")->queryScalar();
       
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $model = new AbsentEntry();
        $examTimetable = new ExamTimetable();
        if ($model->load(Yii::$app->request->post())) 
        { 
            $paper = SubjectsMapping::find()->where(['coe_subjects_mapping_id'=>$model->exam_subject_id])->one();
            
            $section = isset($_POST['AbsentEntry']['stu_section_name'])?$_POST['AbsentEntry']['stu_section_name']:'';
            $paper_type_name = Categorytype::findOne(['coe_category_type_id'=>$paper->paper_type_id]);
            $subject_type_name = Categorytype::findOne(['coe_category_type_id'=>$paper->subject_type_id]);
           
            $query = new Query();
                $whereCondition = [                    
                    'e.batch_mapping_id'=>$_POST['AbsentEntry']['course_batch_id'],
                    'a.student_status'=>'Active',
                ];
            $query->select(['c.coe_student_mapping_id','c.course_batch_mapping_id','a.register_number','a.name','b.coe_subjects_id','b.subject_code', 'concat(h.degree_code,"-",g.programme_name) as degree_name', 'e.semester','b.subject_name','j.category_type as month','i.batch_name','b.CIA_max','b.ESE_max','e.batch_mapping_id','e.coe_subjects_mapping_id','k.qp_code','k.exam_year as year','k.exam_type','k.subject_mapping_id','k.exam_term','k.exam_month'])
                    ->from('coe_student a')
                    ->join('JOIN','coe_student_mapping c','c.student_rel_id = a.coe_student_id')
                    ->join('JOIN','coe_bat_deg_reg d','d.coe_bat_deg_reg_id = c.course_batch_mapping_id')   
                    ->join('JOIN','coe_subjects_mapping e','e.batch_mapping_id = d.coe_bat_deg_reg_id')
                    ->join('JOIN','coe_subjects b','b.coe_subjects_id = e.subject_id')
                    ->join('JOIN','coe_degree h','h.coe_degree_id = d.coe_degree_id')
                    ->join('JOIN','coe_batch i','i.coe_batch_id = d.coe_batch_id')
                    ->join('JOIN','coe_programme g','g.coe_programme_id = d.coe_programme_id')
                    ->join('JOIN','coe_category_type xyz','xyz.coe_category_type_id = c.status_category_type_id')
                    ->join('JOIN','coe_categories abcd','abcd.coe_category_id = xyz.category_id');

            if($_POST['AbsentEntry']['exam_type']==$exam_type_g)
            {
                    $query->join('JOIN','coe_mark_entry_master l','l.subject_map_id = e.coe_subjects_mapping_id and l.student_map_id=c.coe_student_mapping_id')
                        ->join('JOIN','coe_exam_timetable k','k.subject_mapping_id = e.coe_subjects_mapping_id')
                        ->join('JOIN','coe_fees_paid fees','fees.student_map_id = l.student_map_id and fees.subject_map_id = l.subject_map_id')
                        ->join('JOIN','coe_category_type j','j.coe_category_type_id = k.exam_month');
                   
                    $get_stu_list = MarkEntryMaster::find()->where(['subject_map_id'=>$model->exam_subject_id,'result'=>'Pass'])->all();

                    $stu_ids = [];
                    foreach ($get_stu_list as $key => $value) 
                    {
                        $stu_ids[] = $value['student_map_id'];
                    }
                    
                    $query->where(['NOT IN','l.student_map_id',$stu_ids]);
                    $query->where(['NOT LIKE','l.result','Pass'])
                          ->andWhere(['<>', 'fees.status', 'NO']);
                    $query->andWhere(['NOT IN','coe_student_mapping_id',$stu_ids]);
                    
                    $whereCondition = array_merge($whereCondition,[
                        'k.subject_mapping_id'=>$model->exam_subject_id,
                        'l.subject_map_id'=>$model->exam_subject_id,
                        'k.exam_type'=>$model->exam_type,
                        'k.exam_term'=>$model->absent_term,
                        'k.exam_year'=>$_POST['AbsentEntry']['year'],
                        'k.exam_month'=>$_POST['AbsentEntry']['exam_month'],
                        'fees.year'=>$_POST['AbsentEntry']['year'],
                        'fees.status'=>'YES',
                        'fees.month'=>$_POST['AbsentEntry']['exam_month'],

                    ]);

               
            }
            else
            {
                 if(stristr($subject_type_name->category_type, "Elective") || stristr($subject_type_name->description, "Elective"))
                { 
                    $query->join('JOIN','coe_nominal l','l.course_batch_mapping_id = d.coe_bat_deg_reg_id and l.coe_student_id=a.coe_student_id and l.coe_subjects_id=b.coe_subjects_id and l.course_batch_mapping_id=e.batch_mapping_id');
                    $query->join('JOIN','coe_exam_timetable k','k.subject_mapping_id = e.coe_subjects_mapping_id')
                        ->join('JOIN','coe_category_type j','j.coe_category_type_id = k.exam_month');
                    $whereCondition = array_merge($whereCondition,[
                        'l.coe_subjects_id'=>$paper->subject_id,
                    ]);
                }
                
                else {
                    $query->join('JOIN','coe_exam_timetable k','k.subject_mapping_id = e.coe_subjects_mapping_id')
                        ->join('JOIN','coe_category_type j','j.coe_category_type_id = k.exam_month');
                    
                    $whereCondition = array_merge($whereCondition,[
                        'k.subject_mapping_id'=>$model->exam_subject_id,
                        'k.exam_type'=>$model->exam_type,
                        'k.exam_term'=>$model->absent_term,
                        'k.exam_year'=>$_POST['AbsentEntry']['year'],
                        'k.exam_month'=>$_POST['AbsentEntry']['exam_month'],
                    ]);
                    
                }
            }     

            $query->Where($whereCondition)
            ->andWhere(['NOT LIKE','xyz.description', 'Discontinued'])->groupBy('a.register_number');
            if(!empty($section) && $section!='All')
            {
                $query->andWhere(['c.section_name'=>$section]);
            }   
            $external_score = $query->createCommand()->queryAll();
           
            return $this->render('external-format', [
                'model' => $model,
                'examTimetable' => $examTimetable,
                'external_score'=>$external_score,
            ]);
        }
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to External Score Card');
        return $this->render('external-format', [
            'model' => $model,
            'examTimetable' => $examTimetable,
        ]);
    }
    public function actionExportexternal()
    {

        $content = $_SESSION['external_score_data'];
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE, 
                'filename' => 'External-Score-Card.pdf',                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse: collapse;  text-align: center;  font-family:"Roboto, sans-serif"; width:100%; font-size: 12px; } 
                        
                        table td{
                            border: 1px solid #000;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: center;
                            line-height: 1em;
                        }
                        table th{
                            border: 1px solid #000;
                            overflow: hidden;
                            white-space: nowrap;
                            text-overflow: ellipsis;
                            text-align: center;
                        }
                    }   
                ',
                'options' => ['title' =>'External Score Card'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['External Score Card'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ]
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }
    public function actionExportexternalArts()
    {

        $content = $_SESSION['external_score_data'];
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE, 
                'filename' => 'External-Score-Card.pdf',                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                         table{border-collapse: collapse;  text-align: center;  font-family:"Roboto, sans-serif"; width:100%; font-size: 12px; } 
                        
                        table td{
                            border: 1px solid #000;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: center;
                            line-height: 1em;
                        }
                        table th{
                            border: 1px solid #000;
                            overflow: hidden;
                            white-space: nowrap;
                            text-overflow: ellipsis;
                            text-align: center;
                        }
                    }   
                ',
                'options' => ['title' =>'External Score Card'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['External Score Card'.' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ]
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }
    /**
     * Displays a single ExamTimetable model.
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
     * Creates a new ExamTimetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExamTimetable();

        if (Yii::$app->request->isAjax) {
            if($model->load(Yii::$app->request->post())) {
                array('onclick'=>'$("#student_form_required_page").dialog("open"); return false;');
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        if ($model->load(Yii::$app->request->post())) 
        {

            $batch = $_POST['bat_val'];
            $batch_map_id = $_POST['bat_map_val'];
            $sem = $_POST['exam_semester'];
            $sub_id = $_POST['exam_subject_code'];
            $sub_name = $_POST['exam_subject_name'];

            $date = Yii::$app->formatter->asDate($_POST['exam_date'], 'yyyy-MM-dd');
            $display_data = Yii::$app->formatter->asDate($_POST['exam_date'], 'dd-MM-yyyy');
            $exam_year = $model->exam_year;

            $subject = new Query();
            $subject->select("A.subject_code,B.coe_subjects_mapping_id,B.subject_type_id")
                ->from("coe_subjects A")
                ->join('JOIN','coe_subjects_mapping B','A.coe_subjects_id=B.subject_id')
                ->where(['batch_mapping_id'=>$batch_map_id,'B.coe_subjects_mapping_id'=>$sub_id,'B.semester'=>$sem]);
            $sub_det = $subject->createCommand()->queryOne();
            
            $model->subject_mapping_id=$sub_det['coe_subjects_mapping_id'];
            $model->exam_year=$exam_year;
            $model->exam_date=$date;
            $model->created_at = new \yii\db\Expression('NOW()');
            $model->created_by = Yii::$app->user->getId();
            $model->updated_at = new \yii\db\Expression('NOW()');
            $model->updated_by = Yii::$app->user->getId();

            $cat_sub_type = Categorytype::find()->where(['coe_category_type_id'=>$sub_det['subject_type_id']])->one();

            $same_date = new Query();
            $same_date->select("B.*")
                ->from("coe_subjects_mapping A")
                ->join('JOIN','coe_exam_timetable B','B.subject_mapping_id=A.coe_subjects_mapping_id')
                ->where(['A.batch_mapping_id'=>$batch_map_id,'B.exam_date'=>$date,'B.exam_session'=>$model->exam_session]);
            $course_exam_date = $same_date->createCommand()->queryAll();

            $same_sub_date = new Query();
            $same_sub_date->select("C.subject_mapping_id")
                ->from("coe_subjects A")
                ->join('JOIN','coe_subjects_mapping B','B.subject_id=A.coe_subjects_id')
                ->join('JOIN','coe_exam_timetable C','C.subject_mapping_id=B.coe_subjects_mapping_id')
                ->where(['B.batch_mapping_id'=>$batch_map_id,'B.coe_subjects_mapping_id'=>$sub_id,'B.semester'=>$sem,'exam_year'=>$exam_year,'exam_month'=>$model->exam_month,'exam_term'=>$model->exam_term]);
            $same_subject_date = $same_sub_date->createCommand()->queryAll();

            $without_elective_date = Yii::$app->db->createCommand("select * from coe_subjects_mapping as A,coe_exam_timetable as B where A.coe_subjects_mapping_id=B.subject_mapping_id and batch_mapping_id='".$batch_map_id."' and exam_date='".$date."' and exam_session='".$model->exam_session."' and subject_type_id!='".$cat_sub_type->coe_category_type_id."'")->queryAll();

            if($cat_sub_type->category_type!='Elective')
            {
                if(count($course_exam_date)>0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Can not be Created Because Same '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." has multiple ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." on Same <b>".$display_data."</b> and Same Session ");
                    return $this->redirect(['create']);
                }
                else
                {
                    if(count($same_subject_date)>0)
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' date already created for this '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME));
                        return $this->redirect(['create']);
                    }
                    else
                    {
                        $model->save();
                        Yii::$app->ShowFlashMessages->setMsg('Success',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Date <b>".$display_data."</b> Has created Successfully!!! for <b>".$sub_det['subject_code']."</b>");
                        return $this->redirect(['create']);
                    }
                }
            }
            else//elective
            {
                
                if(!empty($without_elective_date))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Can not be Created Because Same '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." has multiple ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." on Same <b>".$display_data."</b> and Same Session ");
                    return $this->redirect(['create']);
                }
               
                if(!empty($same_subject_date))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' date already created for this '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME));
                    return $this->redirect(['create']);
                }
                else
                {
                    $model->save(false);
                    Yii::$app->ShowFlashMessages->setMsg('Success',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Date <b>".$display_data."</b> Has created Successfully!!! for <b>".$sub_det['subject_code']."</b>");
                    return $this->redirect(['create']);
                }
                
            }

           return $this->redirect(['create']);
        } 
        else 
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM). ' Timetable');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ExamTimetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $model->exam_date = date('Y-m-d',strtotime($_POST['exam_date']));
            $model->qp_code = $_POST['ExamTimetable']['qp_code'];
            $model->exam_session = $_POST['ExamTimetable']['exam_session'];
            $model->save(false);
            Yii::$app->ShowFlashMessages->setMsg('Success','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Date ".$_POST['exam_date'] . ' Updated Successfully!!');
            return $this->redirect(['view', 'id' => $model->coe_exam_timetable_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ExamTimetable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $exam_date = ExamTimetable::findOne($id);
        $checkHallAll = HallAllocate::find()->where(['exam_timetable_id'=>$id])->one();
        $absentEntry = AbsentEntry::find()->where(['exam_type'=>$exam_date->exam_type,'exam_date'=>$exam_date->exam_date,'exam_month'=>$exam_date->exam_month,'exam_session'=>$exam_date->exam_session,'exam_subject_id'=>$exam_date->subject_mapping_id])->one();
        $date_sess = $exam_date->exam_date;
        if(!empty($checkHallAll))
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','Unable to delete This '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Because Galley Arranged.');
        }
        else if(!empty($absentEntry))
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','Unable to delete this '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Already Absent Entry Completed.');
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Success',$date_sess.' Deleted Successfully!!!');
            $this->findModel($id)->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the ExamTimetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExamTimetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExamTimetable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionHallWiseAbsent()
    {
        $model = new AbsentEntry();
        $examTimetable = new ExamTimetable();
        if (Yii::$app->request->post()) 
        { 
            $exam_year = $_POST['AbsentEntry']['exam_year'];
            $exam_month = $_POST['AbsentEntry']['exam_month'];
            $exam_date = DATE('Y-m-d',strtotime($_POST['AbsentEntry']['exam_date']));
            $exam_session = $_POST['AbsentEntry']['exam_session'];
            $halls = $_POST['AbsentEntry']['halls'];
            $getData = Categorytype::find()->where(['description'=>'Hall Wise Entry'])->one();
            $reg_nums = '';
            if(isset($_POST['ab']))
            {
                $absent_status = $getData->coe_category_type_id;
                $total_absent =count($_POST['ab']);
                $total_count = count($_POST['stuTotal']);
                for ($i=0; $i <$total_count ; $i++) 
                { 
                    $stu_map_id = $_POST['stuTotal'][$i];
                    $sub_map_id = $_POST['exam_subject_id'][$i];
                    $exam_type = $_POST['exam_type'][$i];
                    $absent_term = $_POST['absent_term'][$i];
                    if(isset($_POST['ab'][$stu_map_id]) && $_POST['ab'][$stu_map_id]=='on')
                    {
                        $checkExists = AbsentEntry::find()->where(['absent_student_reg'=>$stu_map_id,'exam_subject_id'=>$sub_map_id,'exam_type'=>$exam_type,'exam_year'=>$exam_year,'exam_month'=>$exam_month])->one();
                        if(empty($checkExists))
                        {
                            $absent_save = new AbsentEntry();
                            $absent_save->absent_student_reg = $stu_map_id;
                            $absent_save->exam_type = $exam_type;
                            $absent_save->absent_term = $absent_term;
                            $absent_save->exam_date = $exam_date;
                            $absent_save->exam_month = $exam_month;
                            $absent_save->exam_session = $exam_session;
                            $absent_save->exam_subject_id = $sub_map_id;
                            $absent_save->exam_absent_status = $absent_status;
                            $absent_save->exam_year = $exam_year;
                            $absent_save->created_by = Yii::$app->user->getId();
                            $absent_save->created_at = new \yii\db\Expression('NOW()');
                            $absent_save->updated_by = Yii::$app->user->getId();
                            $absent_save->updated_at = new \yii\db\Expression('NOW()');
                            if($absent_save->save(false))
                            {
                                $reg_nums .=$_POST['reg_nu_sem_ab_'.$stu_map_id].", ";
                            }
                        }
                    }
                }
                Yii::$app->ShowFlashMessages->setMsg('Success',"<b>".$reg_nums.'</b><br /> Added as Absent');
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','Nothing Happens');
            }
            return $this->render('hall-wise-absent', [
                'model' => $model,
                'examTimetable' => $examTimetable,
            ]);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Hall Wise Absent Entry');
            return $this->render('hall-wise-absent', [
                'model' => $model,
                'examTimetable' => $examTimetable,
            ]);
        }
        
    }

    public function actionExamDateWiseAbsent()
    {
        $model = new AbsentEntry();
        $examTimetable = new ExamTimetable();
        if (Yii::$app->request->post()) 
        { 
            $exam_year = $_POST['AbsentEntry']['exam_year'];
            $exam_month = $_POST['AbsentEntry']['exam_month'];
            $exam_date = DATE('Y-m-d',strtotime($_POST['AbsentEntry']['exam_date']));
            $exam_session = $_POST['AbsentEntry']['exam_session'];
            $halls = $_POST['AbsentEntry']['exam_subject_id'];
            $getData = Categorytype::find()->where(['description'=>'Hall Wise Entry'])->one();
            $reg_nums = '';
            if(isset($_POST['ab']))
            {
                $absent_status = $getData->coe_category_type_id;
                $total_absent =count($_POST['ab']);
                $total_count = count($_POST['stuTotal']);
                for ($i=0; $i <$total_count ; $i++) 
                { 
                    $stu_map_id = $_POST['stuTotal'][$i];
                    $sub_map_id = $_POST['exam_subject_id'][$i];
                    $exam_type = $_POST['exam_type'][$i];
                    $absent_term = $_POST['absent_term'][$i];
                    if(isset($_POST['ab'][$stu_map_id]) && $_POST['ab'][$stu_map_id]=='on')
                    {
                        $checkExists = AbsentEntry::find()->where(['absent_student_reg'=>$stu_map_id,'exam_subject_id'=>$sub_map_id,'exam_type'=>$exam_type,'exam_year'=>$exam_year,'exam_month'=>$exam_month])->one();
                        if(empty($checkExists))
                        {
                            $absent_save = new AbsentEntry();
                            $absent_save->absent_student_reg = $stu_map_id;
                            $absent_save->exam_type = $exam_type;
                            $absent_save->absent_term = $absent_term;
                            $absent_save->exam_date = $exam_date;
                            $absent_save->exam_month = $exam_month;
                            $absent_save->exam_session = $exam_session;
                            $absent_save->exam_subject_id = $sub_map_id;
                            $absent_save->exam_absent_status = $absent_status;
                            $absent_save->exam_year = $exam_year;
                            $absent_save->created_by = Yii::$app->user->getId();
                            $absent_save->created_at = new \yii\db\Expression('NOW()');
                            $absent_save->updated_by = Yii::$app->user->getId();
                            $absent_save->updated_at = new \yii\db\Expression('NOW()');
                            if($absent_save->save(false))
                            {
                                $reg_nums .=$_POST['reg_nu_sem_ab_'.$stu_map_id].", ";
                            }
                        }
                    }
                }
                Yii::$app->ShowFlashMessages->setMsg('Success',"<b>".$reg_nums.'</b><br /> Added as Absent');
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','Nothing Happens');
            }
            return $this->render('exam-date-wise-absent', [
                'model' => $model,
                'examTimetable' => $examTimetable,
            ]);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Exam Date Wise Absent Entry');
            return $this->render('exam-date-wise-absent', [
                'model' => $model,
                'examTimetable' => $examTimetable,
            ]);
        }
        
    }


}
