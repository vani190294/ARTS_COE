<?php
namespace app\controllers;
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\widgets\Growl;
use kartik\mpdf\Pdf;
use app\models\UpdateTracker;
use yii\db\Query;
use app\models\SubjectsMapping;
use app\models\SubjectsMappingSearch;
use app\models\Subjects;
use app\models\DummyNumbers;
use app\models\PracticalEntry;
use app\models\Revaluation;
use app\models\StoreDummyMapping;
use app\models\ElectiveWaiver;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\ExamTimetable;
use app\models\Nominal;
use app\models\MarkEntry;
use app\models\AbsentEntry;
use app\models\MarkEntryMaster;
/**
 * SubjectsMappingController implements the CRUD actions for SubjectsMapping model.
 */
class SubjectsMappingController extends Controller
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
     * Lists all SubjectsMapping models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubjectsMappingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Management');
        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        
    }
    public function actionSubjectCountReportPdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
          $content = $_SESSION['sub_report_count'];
        $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE, 

                'filename' => 'SUBJECT COUNT REPORTS.pdf',                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content, 
                'cssInline' => ' @media all{
                        table{font-family:"Roboto, sans-serif"; width:100%; font-size: 12.5px; }
                    }   
                ', 
                'options' => ['title' => 'SUBJECT COUNT REPORTS'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['SUBJECT COUNT REPORTS PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
                
            ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        return $pdf->render(); 
    }

    public function actionSubjectCountReportExcel()
    {    
        $content = $_SESSION['sub_report_count'];
        $fileName ='Subject Count Reports.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }

    public function actionStudentCountReportPdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
          $content = $_SESSION['student_count_repo'];
        $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE, 
                'filename' => 'STUDENT COUNT REPORTS.pdf',                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content, 
                'cssInline' => ' @media all{
                        table{font-family:"Roboto, sans-serif"; width:100%; font-size: 12.5px; }
                    }   
                ', 
                'options' => ['title' => 'STUDENT COUNT REPORTS'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['STUDENT COUNT REPORTS PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
                
            ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        return $pdf->render(); 
    }

    public function actionStudentCountReportExcel()
    {    
        $content = $_SESSION['student_count_repo'];
        $fileName ='Student Count Reports.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    public function actionSubjectCountReport()
    {
        $model = new SubjectsMapping();
        $subjects = new Subjects();

        if(Yii::$app->request->post())
        {
            $batch_id_value = $_POST['bat_val'];
            $checkStuInfo = new Query();
            $checkStuInfo->select(['batch_name','degree_code','programme_name','count(coe_subjects_mapping_id) as count','semester','batch_mapping_id'])
                ->from('coe_subjects_mapping as A')            
                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.batch_mapping_id')
                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                ->Where(['B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value])
                ->groupBy('batch_mapping_id,semester');
            $content_1 = $checkStuInfo->createCommand()->queryAll();
            if(!empty($content_1))
            {
                return $this->render('subject-count-report', [
                        'model' => $model,
                        'subjects' => $subjects,
                        'content_1' =>$content_1,
                    ]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','NO DATA FOUND');
                return $this->redirect(['subjects-mapping/subject-count-report']);
            }
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Count Report');
            return $this->render('subject-count-report', [
                    'model' => $model,
                    'subjects' => $subjects,
                ]);
        }
        
    }
    public function actionStudentCountReport()
    {
        $model = new SubjectsMapping();
        $subjects = new Subjects();
        if(Yii::$app->request->post())
        {
            $batch_id_value = $_POST['bat_val'];
            $checkStuInfo = new Query();
            $checkStuInfo->select(['batch_name','degree_code','programme_name','count(coe_student_mapping_id) as count','course_batch_mapping_id'])
                ->from('coe_student_mapping as A')            
                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.course_batch_mapping_id')
                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                ->Where(['B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value])
                ->groupBy('course_batch_mapping_id');
            $content_1 = $checkStuInfo->createCommand()->queryAll();
            if(!empty($content_1))
            {
                return $this->render('student-count-report', [
                        'model' => $model,
                        'subjects' => $subjects,
                        'content_1' =>$content_1,
                    ]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','NO DATA FOUND');
                return $this->redirect(['subjects-mapping/student-count-report']);
            }
        }
        else
        {
             Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Count Report');
            return $this->render('student-count-report', [
                    'model' => $model,
                    'subjects' => $subjects,
                ]);
        }
       
        
    }

    /**
     * Displays a single SubjectsMapping model.
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
     * Creates a new SubjectsMapping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SubjectsMapping();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coe_subjects_mapping_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SubjectsMapping model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $subjects = new Subjects();
        if ($model->load(Yii::$app->request->post())) {
           $sub_id =Subjects::findOne(['coe_subjects_id'=>$model->subject_id]);
           $check_nominal = Nominal::find()->where(['coe_subjects_id'=>$model->subject_id,'semester'=>$_POST['SubjectsMapping']['semester']])->all();
           $connection = Yii::$app->db;
			$updated_at = date("Y-m-d H:i:s");
        $updated_by = Yii::$app->user->getId();
           if(isset($_POST['Subjects']['subject_fee']))
           {
                $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS Fees '.$sub_id['subject_fee'].' NEW Fees '.$_POST['Subjects']['subject_fee'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                $update_track = ConfigUtilities::updateTracker($data_array);
                $command = $connection->createCommand('UPDATE coe_subjects SET subject_fee="'.$_POST['Subjects']['subject_fee'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_id="'.$_POST['SubjectsMapping']['subject_id'].'" ');
                $command->execute();  
           }
           if(isset($_POST['SubjectsMapping']['paper_no']))
            {
                $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS Paper No '.$model->paper_no.' NEW Paper No '.$_POST['SubjectsMapping']['paper_no'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                $update_track = ConfigUtilities::updateTracker($data_array);

                $command2 = $connection->createCommand('UPDATE coe_subjects_mapping SET paper_no="'.$_POST['SubjectsMapping']['paper_no'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_mapping_id="'.$id.'"');
                $command2->execute();
            }
            if(isset($_POST['SubjectsMapping']['subject_name']))
            {
                $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS SUB NAME '.$sub_id['subject_name'].' NEW SUB NAME'.$_POST['SubjectsMapping']['subject_name'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                $update_track = ConfigUtilities::updateTracker($data_array);

                $command1 = $connection->createCommand('UPDATE coe_subjects SET subject_name="'.$_POST['SubjectsMapping']['subject_name'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_id="'.$_POST['SubjectsMapping']['subject_id'].'"');
                $command1->execute();
            }
            if(isset($_POST['SubjectsMapping']['subject_code']))
            {

                $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS SUB CODE '.$sub_id['subject_code'].' NEW SUB CODE'.$_POST['SubjectsMapping']['subject_code'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                $update_track = ConfigUtilities::updateTracker($data_array);

                $command1 = $connection->createCommand('UPDATE coe_subjects SET subject_code="'.$_POST['SubjectsMapping']['subject_code'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_id="'.$_POST['SubjectsMapping']['subject_id'].'"');
                $command1->execute();
            }
            if(isset($_POST['SubjectsMapping']['total_minimum_pass']))
            {
                $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS Minimum '.$sub_id['total_minimum_pass'].' NEW Minimum Pass '.$_POST['SubjectsMapping']['total_minimum_pass'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                $update_track = ConfigUtilities::updateTracker($data_array);

                $command1 = $connection->createCommand('UPDATE coe_subjects SET total_minimum_pass="'.$_POST['SubjectsMapping']['total_minimum_pass'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_id="'.$_POST['SubjectsMapping']['subject_id'].'"');
                $command1->execute();
            }
            if(isset($_POST['SubjectsMapping']['credit_points']))
            {
                $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS Credits '.$sub_id['credit_points'].' NEW Credits '.$_POST['SubjectsMapping']['credit_points'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                $update_track = ConfigUtilities::updateTracker($data_array);

                $command1 = $connection->createCommand('UPDATE coe_subjects SET credit_points="'.$_POST['SubjectsMapping']['credit_points'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_id="'.$_POST['SubjectsMapping']['subject_id'].'"');
                $command1->execute();
            }
            $check_entry = MarkEntry::find()->where(['subject_map_id'=>$id])->one(); 
             $check_entry_master = MarkEntryMaster::find()->where(['subject_map_id'=>$id])->one();

            if(isset($_POST['SubjectsMapping']['CIA_min']) || isset($_POST['SubjectsMapping']['CIA_max']) || isset($_POST['SubjectsMapping']['ESE_min']) || isset($_POST['SubjectsMapping']['ESE_max']))
            {
             

             if(empty($check_entry) && empty($check_entry_master))
             {
                if(isset($_POST['SubjectsMapping']['semester']))
                {
                    $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS Semester '.$model->semester.' NEW Semester '.$_POST['SubjectsMapping']['semester'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                    $update_track = ConfigUtilities::updateTracker($data_array);

                    $command1 = $connection->createCommand('UPDATE coe_subjects_mapping SET semester="'.$_POST['SubjectsMapping']['semester'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_mapping_id="'.$id.'" ');
                    $command1->execute();
                }
                
                $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS CIA MAX '.$sub_id['CIA_max'].' NEW CIA MAX '.$_POST['SubjectsMapping']['CIA_max'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                $update_track = ConfigUtilities::updateTracker($data_array);

                $command1 = $connection->createCommand('UPDATE coe_subjects SET CIA_max="'.$_POST['SubjectsMapping']['CIA_max'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_id="'.$_POST['SubjectsMapping']['subject_id'].'"');
                $command1->execute();
                
                $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS ESE MIN '.$sub_id['ESE_min'].' NEW ESE MIN '.$_POST['SubjectsMapping']['ESE_min'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                $update_track = ConfigUtilities::updateTracker($data_array);

                $command1 = $connection->createCommand('UPDATE coe_subjects SET ESE_min="'.$_POST['SubjectsMapping']['ESE_min'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_id="'.$_POST['SubjectsMapping']['subject_id'].'"');
                $command1->execute();
                
                $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS ESE MAX '.$sub_id['ESE_max'].' NEW  ESE MAX '.$_POST['SubjectsMapping']['ESE_max'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                $update_track = ConfigUtilities::updateTracker($data_array);

                $command1 = $connection->createCommand('UPDATE coe_subjects SET ESE_max="'.$_POST['SubjectsMapping']['ESE_max'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_id="'.$_POST['SubjectsMapping']['subject_id'].'"');
                $command1->execute();
                
                $data_array = ['subject_map_id'=>$id,'updated_link_from'=>'Subjects->Update','data_updated'=>'PREVIOUS CIA MIN '.$sub_id['CIA_min'].' NEW  CIA MIN '.$_POST['SubjectsMapping']['CIA_min'],'exam_month'=>'NO','exam_year'=>'NO','student_map_id'=>'NO'];
                $update_track = ConfigUtilities::updateTracker($data_array);

                $command1 = $connection->createCommand('UPDATE coe_subjects SET CIA_min="'.$_POST['SubjectsMapping']['CIA_min'].'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_subjects_id="'.$_POST['SubjectsMapping']['subject_id'].'"');
                $command1->execute();

                
             }
             else
             {
                Yii::$app->ShowFlashMessages->setMsg('Error',"Con't Update <b>".$sub_id->subject_code."</b> because ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." already in Use");
             }
             
            }
            if(isset($_POST['SubjectsMapping']['subject_fee']) || isset($_POST['SubjectsMapping']['subject_name']) || isset($_POST['SubjectsMapping']['paper_no']))
            {
                 Yii::$app->ShowFlashMessages->setMsg('Success',"<b>".$sub_id->subject_code." </b>Updated Successfully!!");
            }
               $model->subject_type_id = $_POST['sub_type_val'];
               $model->paper_type_id = $_POST['paper_type_val'];
               $model->course_type_id = $_POST['prgm_type_val'];
               $model->semester = empty($check_entry) && empty($check_entry_master) && isset($_POST['SubjectsMapping']['semester']) ? $_POST['SubjectsMapping']['semester'] : $model->semester ;
               $model->save(false);
               Yii::$app->ShowFlashMessages->setMsg('Success',"<b>".$sub_id->subject_code." </b>Updated Successfully!!");
            return $this->redirect(['view', 'id' => $model->coe_subjects_mapping_id]);
        } else {
            return $this->render('update', [
                'model' => $model,'subjects'=>$subjects
            ]);
        }
    }

    /**
     * Deletes an existing SubjectsMapping model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $subjects = Subjects::findOne(['coe_subjects_id'=>$model->subject_id]);
        $name_of_subject = $subjects->subject_code;
        $check_nominal = Nominal::find()->where(['coe_subjects_id'=>$model->subject_id])->all();
        //$subject_code = $sub_id->subject_code;
        if(!empty($check_nominal))
        {
           Yii::$app->ShowFlashMessages->setMsg('Error',' You can not delete <b>'.$name_of_subject.'</b> Because already <b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)."</b> are Assigned OR <b>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)."</b> are Available OR ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Are conducted");            
        }
        else
        {
            $exam_check = ExamTimetable::findOne(['subject_mapping_id'=>$id]);
            if(!empty($exam_check))
            {
                Yii::$app->ShowFlashMessages->setMsg('Error',"Con't delete <b>".$name_of_subject."</b> because Exams already Created");
            }
            else
            {
                $mark_check = MarkEntry::findOne(['subject_map_id'=>$id]);
                $elect_waiver = ElectiveWaiver::findOne(['removed_sub_map_id'=>$id]);
                $dummy_nu = DummyNumbers::findOne(['subject_map_id'=>$id]);
                $strorre_seq = StoreDummyMapping::findOne(['subject_map_id'=>$id]);
                $practical = PracticalEntry::findOne(['subject_map_id'=>$id]);
                if(!empty($mark_check))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',"Con't delete <b>".$name_of_subject."</b> because Marks already in Entered");
                }
                else if(!empty($elect_waiver))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',"Con't delete <b>".$name_of_subject."</b> because this is Elective Waiver ");
                }
                else if(!empty($dummy_nu))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',"Con't delete <b>".$name_of_subject."</b> because Dummy NUmbers Arranged ");
                }
                else if(!empty($strorre_seq))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',"Con't delete <b>".$name_of_subject."</b> because Dummy NUmber Sequence Assigned ");
                }
                else if(!empty($practical))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',"Con't delete <b>".$name_of_subject."</b> because Practical Mark Entry ");
                }
                else
                {
                   $this->findModel($id)->delete();
                   $check_subs = SubjectsMapping::findOne(['subject_id'=>$subjects->coe_subjects_id]);
                   if(empty($check_subs))
                   {
                        Yii::$app->db->createCommand('delete from coe_subjects where coe_subjects_id="'.$subjects->coe_subjects_id.'"')->execute();
                   }                   
                   Yii::$app->ShowFlashMessages->setMsg('Success',"<b>".$name_of_subject." </b>Deleted Successfully!!");
                }
            }
            
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the SubjectsMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubjectsMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubjectsMapping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
