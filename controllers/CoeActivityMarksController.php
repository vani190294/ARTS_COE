<?php

namespace app\controllers;

use Yii;
use app\models\CoeActivityMarks;
use app\models\CoeActivityMarksSearch;
use yii\web\Controller;
use app\models\CoeAddPoints;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\mpdf\Pdf;
use app\models\Student;

/**
 * CoeActivityMarksController implements the CRUD actions for CoeActivityMarks model.
 */
class CoeActivityMarksController extends Controller
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
     * Lists all CoeActivityMarks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoeActivityMarksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoeActivityMarks model.
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
     * Creates a new CoeActivityMarks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CoeActivityMarks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CoeActivityMarks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CoeActivityMarks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionMarkreport()
    {

       $model = new CoeActivityMarks();


        if ($model->load(Yii::$app->request->post()))

        {  
             $batch=$_POST['CoeActivityMarks']['batch'];
             $programme=$_POST['CoeActivityMarks']['programme'];
       //print_r($batch);exit;
       
           if(!empty($batch) && !empty($programme))
           {
               
                $fetch_query = (new \yii\db\Query());
                $fetch_query->select([ 'A.register_number','K.duration','F.degree_name','G.programme_name','K.subject_code','D.coe_student_mapping_id'])  
                    ->from('coe_student as A')
                    ->join('JOIN','coe_student_mapping as D','A.coe_student_id=D.student_rel_id')
                    ->join('JOIN','coe_bat_deg_reg as E','E.coe_bat_deg_reg_id=D.course_batch_mapping_id')                    
                    ->join('JOIN','coe_degree as F','F.coe_degree_id=E.coe_degree_id')
                    ->join('JOIN','coe_programme as G','G.coe_programme_id=E.coe_programme_id')
                    ->join('JOIN','coe_batch as H','H.coe_batch_id=E.coe_batch_id')
                    ->join('JOIN','coe_activity_marks as K','K.register_number=D.coe_student_mapping_id')
                     ->join('JOIN','coe_add_points as j','j.subject_code=K.subject_code')
                    // ->andWhere('D.course_batch_mapping_id = :course_batch_mapping_id', [':course_batch_mapping_id' => $programme])
                    ->andWhere(['H.coe_batch_id'=>$batch,'A.student_status'=>'Active','K.programme'=>$programme])
                    ->orderBy('A.register_number,j.subject_code');
                    //->groupBy('A.register_number');
                    
                $stu_data = $fetch_query->createCommand()->queryAll();
               // print_r($stu_data);exit;


            $subject_get_data = new  Query();
            $subject_get_data->select('distinct (j.subject_code) as subject_code, j.subject_name')
                ->from('coe_student as A')
                ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id ')
                ->join('JOIN', 'coe_bat_deg_reg as C', 'C.coe_bat_deg_reg_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_degree as D', 'D.coe_degree_id=C.coe_degree_id')
                ->join('JOIN', 'coe_programme as E', 'E.coe_programme_id=C.coe_programme_id')
                ->join('JOIN','coe_activity_marks as K','K.register_number=B.coe_student_mapping_id')
                 ->join('JOIN','coe_add_points as j','j.subject_code=K.subject_code');
            $subject_get_data->Where(['B.course_batch_mapping_id' => $programme, 'C.coe_batch_id' => $batch]);
             $subject_get_data->orderBy('j.subject_code');
            $subjectsInfo = $subject_get_data->createCommand()->queryAll();
            //print_r($subjectsInfo);exit;

                return $this->render('markreport', [
                    'stu_data' => $stu_data,
                    'subjectsInfo' =>$subjectsInfo,

                    
                    'model' => $model,
                    
                ]);
           }
           else {
               Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
               return $this->render('markreport', [
                    'model' => $model,
                    //'subjectsInfo' =>$subjectsInfo,
                  
                ]);
           }
        }else {
            Yii::$app->ShowFlashMessages->setMsg('Success',"Welcome to  Activity Points");
            return $this->render('markreport', [
                'model' => $model,
               
            ]);
        } 
    }

    /**
     * Finds the CoeActivityMarks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoeActivityMarks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoeActivityMarks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    

     public function actionActivityMarkListPdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $content = $_SESSION['cia_mark_list'];
        //unset($_SESSION['student_application_date']);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'CIA MARK LIST.pdf',
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => ' @media all{
                        table{border: none; font-family:"Roboto, sans-serif"; width:100%; font-size: 12.5px; }
                    }   
                ',
            'options' => ['title' => 'CIA MARK LIST'],
            'methods' => [
                'SetHeader' => ["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name],
                'SetFooter' => ['Conslidate CIA Mark List' . ' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
            ]
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }
    public function actionExcelActivityMarkList()
    {
        
        $content = $_SESSION['cia_mark_list'];
            
        $fileName = "CIA Mark List" . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    
     public function actionDeletereport()
    {

       $model = new CoeActivityMarks();

        if (Yii::$app->request->post())
        {  
            $deleteactivit=$_POST['deleteactivit'];
            //print_r($deleteactivit); exit;
            if(!empty($deleteactivit))
            {
                $deleted=0;
                $error=0;
                for ($i=0; $i <count($deleteactivit) ; $i++) 
                { 
                    $delete_activity_marks=Yii::$app->db->createCommand('DELETE FROM coe_activity_marks WHERE id="'.$deleteactivit[$i].'"')->execute();
                    
                    if($delete_activity_marks)
                    {
                        $deleted++;
                    }
                    else
                    {
                        $error++;
                    }
                }

                if($deleted>0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', $deleted." Records Deleted ".$error." Not Delete");
                    return $this->redirect(['coe-activity-marks/deletereport']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "No data deleted");
                    return $this->redirect(['coe-activity-marks/deletereport']);
                }

            }
            else 
            {
               Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
               return $this->render('deletereport', [
                    'model' => $model,
                    'model1'=>$model1
                  
                ]);
            }
           
        }else {
            Yii::$app->ShowFlashMessages->setMsg('Welcome',"Welcome to  Delete Activity Points");
            return $this->render('deletereport', [
                'model' => $model
               
            ]);
        } 
    }

    public function actionActivityMarksheet()
    {

        $model = new CoeActivityMarks();
        $student = new Student();

        if (Yii::$app->request->post())
        {
            $batch_id = $_POST['bat_val'];
            $stu_programme_selected=$_POST['bat_map_val'];
            $from_reg = $_POST['from_reg'];
            $to_reg= $_POST['to_reg'];
            $activity_print_date = $_POST['activity_print_date'];

            $fetch_query = new Query();      
            $fetch_query->select([ 'A.name','A.register_number','K.duration','J.subject_code','K.register_number as student_map_id','K.id','F.degree_code','E.programme_name','C.regulation_year','I.batch_name','D.status_category_type_id'])  
                    ->from('coe_student as A')
                    ->join('JOIN','coe_student_mapping as D','A.coe_student_id=D.student_rel_id')
                    ->join('JOIN','coe_activity_marks as K','K.register_number=D.coe_student_mapping_id')
                    ->join('JOIN','coe_add_points as J','J.subject_code=K.subject_code')
                    ->join('JOIN', 'coe_bat_deg_reg as C', 'C.coe_bat_deg_reg_id=D.course_batch_mapping_id')
                    ->join('JOIN', 'coe_degree as F', 'F.coe_degree_id=C.coe_degree_id')
                    ->join('JOIN', 'coe_programme as E', 'E.coe_programme_id=C.coe_programme_id')
                    ->join('JOIN', 'coe_batch as I', 'I.coe_batch_id=C.coe_batch_id')
                    ->Where(['D.course_batch_mapping_id' => $stu_programme_selected,'K.batch'=>$batch_id,'A.student_status'=>'Active'])
                    ->andWhere(['between', "A.register_number", $from_reg, $to_reg])
                    ->groupBy('A.register_number')
                    ->orderBy('A.register_number');
                    //->groupBy('A.register_number');
            //echo $fetch_query->createCommand()->getrawsql(); exit;        
            $stu_data = $fetch_query->createCommand()->queryAll();

            if (!empty($stu_data)) 
            {
                return $this->render('activity-marksheet', [
                    'model' => $model,
                    'student' => $student,
                    'stu_data' => $stu_data,
                    'activity_print_date'=>$activity_print_date
                ]);
            } else {
                Yii::$app->ShowFlashMessages->setMsg('Error', "No data Found ");
                return $this->redirect(['coe-activity-marks/activity-marksheet']);
            }
        }
        else 
        {
            Yii::$app->ShowFlashMessages->setMsg('Welcome',"Welcome to Activity Points Marksheet");
            return $this->render('activity-marksheet', [
                'model' => $model,
                'student' => $student,
               
            ]);
        } 
    }

    public function actionActivitymarksheetpdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $content = $_SESSION['activity_marksheetpdf'];
        $change_css_file =  'css/activitymarksheet.css';
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'Activity Point Marksheet.pdf',
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'content' => $content,         
            'cssFile' => $change_css_file,
            'options' => ['title' =>'Activity Point Marksheet'],
        ]);

        //$pdf->marginFooter = "5";

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        
        return $pdf->render();
    }
     
}
     

