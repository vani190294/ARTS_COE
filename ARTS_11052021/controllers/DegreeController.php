<?php

namespace app\controllers;

use Yii;
use app\models\Degree;
use app\models\DegreeSearch;
use yii\web\Controller;
use yii\db\Query;
use kartik\mpdf\Pdf;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\widgets\Growl;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\MarkEntryMaster;
use app\models\MarkEntry;
use app\models\SubjectsMapping;
use app\models\StudentMapping;
use app\models\CoeBatDegReg;
/**
 * DegreeController implements the CRUD actions for Degree model.
 */
class DegreeController extends Controller
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
     * Lists all Degree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DegreeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' Creation');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Degree model.
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
     * Creates a new Degree model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Degree();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->created_at = new \yii\db\Expression('NOW()');
            $model->created_by = Yii::$app->user->getId();
            $model->updated_at = new \yii\db\Expression('NOW()');
            $model->updated_by = Yii::$app->user->getId();
            
            if($model->save())
            {
        	      unset($model);
        	      $model = new Degree();
        	      Yii::$app->ShowFlashMessages->setMsg('Success','Record Saved Successfully!!!');
        	      //return $this->redirect(['index',]);    
        	      return $this->render('create', ['model' => $model]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','Record Not Created');
                return $this->render('create', ['model' => $model]);
            }
        } 
        else 
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' Creation');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionPendingStatus()
    {
        $model = new MarkEntry();
        if (Yii::$app->request->post())
        {
           
            $batch_id_value = $_POST['bat_val'];
            $exam_year = $_POST['exam_year'];
            $exam_month = $_POST['exam_month'];
            
            $checkStuInfo = new Query();
            $checkStuInfo->select(['batch_name','degree_code','programme_code','semester','batch_mapping_id','coe_subjects_mapping_id as sub_id','subject_id','subject_type_id','subject_code','subject_name','semester'])
                ->from('coe_subjects_mapping as A')            
                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.batch_mapping_id')
                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                ->JOIN('JOIN','coe_subjects as F','F.coe_subjects_id=A.subject_id')
                ->Where(['B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value]);
            $content_1 = $checkStuInfo->createCommand()->queryAll();
           
            if($content_1)
            {
                   Yii::$app->ShowFlashMessages->setMsg('SUCCESS','DATA AVALABLE TO CHECK');
                    return $this->render('pending-status', [
                        'model' => $model,
                        'content_1'=>$content_1,
                    ]);
            }
            else
            {
                 Yii::$app->ShowFlashMessages->setMsg('ERROR','NO DATA FOUND');
                return $this->render('pending-status', [
                    'model' => $model,
                ]);
            }
        } 
        else
        {
             Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to COE Pending Status');
            return $this->render('pending-status', [
                'model' => $model,
            ]);
        }
       
        
    }
    public function actionPendingStatusWithDepartment()
    {
        $model = new MarkEntry();
        if (Yii::$app->request->post())
        {
           
            $batch_id_value = $_POST['bat_val'];
            $batch_map_id = $_POST['bat_map_val'];
            $exam_year = $_POST['exam_year'];
            $exam_month = $_POST['exam_month'];
            $semester = $_POST['semester']+1; // Array will start from 0
            $sem_valc = ConfigUtilities::SemCaluclation($exam_year,$exam_month,$batch_map_id);
            $checkStuInfo = new Query();
            $checkStuInfo->select(['batch_name','degree_code','programme_code','semester','batch_mapping_id','coe_subjects_mapping_id as sub_id','subject_id','subject_type_id','subject_code','subject_name','semester'])
                ->from('coe_subjects_mapping as A')            
                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.batch_mapping_id')
                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                ->JOIN('JOIN','coe_subjects as F','F.coe_subjects_id=A.subject_id')
                ->Where(['B.coe_batch_id' => $batch_id_value, 'A.batch_mapping_id'=>$batch_map_id,'A.semester'=>$semester,'C.coe_batch_id' => $batch_id_value]);
            $content_1 = $checkStuInfo->createCommand()->queryAll();
           
            if($content_1)
            {
                   Yii::$app->ShowFlashMessages->setMsg('SUCCESS','DATA AVALABLE TO CHECK');
                    return $this->render('pending-status-with-department', [
                        'model' => $model,
                        'content_1'=>$content_1,
                    ]);
            }
            else
            {
                 Yii::$app->ShowFlashMessages->setMsg('ERROR','NO DATA FOUND');
                return $this->render('pending-status-with-department', [
                    'model' => $model,
                ]);
            }
        } 
        else
        {
             Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to COE Pending Status');
            return $this->render('pending-status-with-department', [
                'model' => $model,
            ]);
        }
       
        
    }
    public function actionPendingCountReportPdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $content = $_SESSION['PENDING_status'];
        $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE, 
                'filename' => 'PENDING COUNT REPORTS.pdf',                
                'format' => Pdf::FORMAT_A3,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content, 
                'cssInline' => ' @media all{
                        table{border-collapse: collapse; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width:100%; font-size: 15px !important;  }

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
                          white-space: nowrap;
                          padding: 5px !important;
                        }
                        table th{
                            overflow: hidden;
                            text-overflow: ellipsis;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-align: left;
                           
                        }
                    }   
                ',  
                'options' => ['title' => 'PENDING COUNT REPORTS'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['PENDING COUNT REPORTS PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
                
            ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }

    public function actionPendingCountReportExcel()
    {    
        $content = $_SESSION['PENDING_status'];
        $fileName ='PENDING Count Reports.xls';        
        $options = ['mimeType'=>'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    /**
     * Updates an existing Degree model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) ) {

            $batch_mapping = CoeBatDegReg::find()->where(['coe_degree_id'=>$id])->one();
            $degree_name = Degree::findOne($id);
            $name_of_degree = $degree_name->degree_code;
                    
            $model->save();
            Yii::$app->ShowFlashMessages->setMsg('Success',"<b>".$name_of_degree.'</b> Has Updated Successfully!! ');
            return $this->redirect(['update',  'model' => $model,'id' => $model->coe_degree_id]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Degree model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $batch_mapping = CoeBatDegReg::find()->where(['coe_degree_id'=>$id])->one();
        $degree_name = Degree::findOne($id);
        $name_of_degree = $degree_name->degree_name;
        if(empty($batch_mapping))
        {
            $this->findModel($id)->delete();
            Yii::$app->ShowFlashMessages->setMsg('Success',$name_of_degree.' Has Deleted Successfully!! ');
            return $this->redirect(['index']);
            
        }
        else
        {
            $SubjectsMapping = SubjectsMapping::findOne(['batch_mapping_id'=>$batch_mapping->coe_bat_deg_reg_id]); 
            $StudentMapping = StudentMapping::findOne(['course_batch_mapping_id'=>$batch_mapping->coe_bat_deg_reg_id]);
            if(empty($SubjectsMapping) && empty($StudentMapping))
            {      
                $batch_mapping_del = CoeBatDegReg::findModel($batch_mapping->coe_bat_deg_reg_id)->delete();
                if($batch_mapping_del)
                {
                    $this->findModel($id)->delete();
                    Yii::$app->ShowFlashMessages->setMsg('Success',$name_of_degree.' Has Deleted Successfully!! ');
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error','Error while deleting record of '.$name_of_degree);
                }
                return $this->redirect(['index']);                    
            }
            else
            {
               
                Yii::$app->ShowFlashMessages->setMsg('Error',' You can not delete this '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' Because already '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." are Assigned OR ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." are Available");
                return $this->redirect(['index']);
            }
            
        }

    }

    /**
     * Finds the Degree model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Degree the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Degree::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
