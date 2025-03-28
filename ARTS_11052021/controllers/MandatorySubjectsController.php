<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use app\models\MandatorySubjects;
use app\models\MandatoryStuMarks;
use app\models\BatDegReg;
use app\models\Categorytype;
use app\models\MandatorySubcatSubjects;
use app\models\MandatorySubjectsSearch;
use app\models\SubjectsMapping;
use app\models\CoeBatDegReg;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;

/**
 * MandatorySubjectsController implements the CRUD actions for MandatorySubjects model.
 */
class MandatorySubjectsController extends Controller
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
     * Lists all MandatorySubjects models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MandatorySubjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT));
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MandatorySubjects model.
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
     * Creates a new MandatorySubjects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MandatorySubjects();
        if (Yii::$app->request->isAjax) 
          {
            if($model->load(Yii::$app->request->post())) 
            {
              \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
              return ActiveForm::validate($model);
            }
          }
        if ($model->load(Yii::$app->request->post())) 
        {

            $coe_batch_id = $model->man_batch_id;
            $is_additional_cre = $model->created_at;
            $sub_migrate_code = $model->updated_at;
            $created_by = Yii::$app->user->getId();
            $course_type_id = Categorytype::find()->where(['description'=>'Optional'])->one();
            $subject_type_id = Categorytype::find()->where(['description'=>'Mandatory Course'])->one();
            $batch_mapping_id = $model->batch_mapping_id;
            $paper_type_id = Categorytype::find()->where(['description'=>'Theory'])->one();    
             
            if($is_additional_cre==1 && !empty($sub_migrate_code))
            {              
                $getSubCatListQuery = new Query();
                $getSubCatListQuery->select(['sub_cat_code','sub_cat_name'])
                ->from('coe_mandatory_subcat_subjects A')
                ->join('JOIN', 'coe_mandatory_subjects B', 'B.coe_mandatory_subjects_id=A.man_subject_id')
                ->where(['B.coe_mandatory_subjects_id'=>$sub_migrate_code,'coe_batch_id'=>$coe_batch_id,'B.man_batch_id'=>$coe_batch_id]);
                $getSubMzainList = $getSubCatListQuery->groupBy('sub_cat_code')->createCommand()->queryAll();
               
                if(empty($getSubMzainList))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error'," Wrong Submission Kindly submit proper details ");  
                    return $this->redirect(['create']);
                }
                else
                {

                    $model->ESE_min = 0;
                    $model->ESE_max = 0;
                    $model->created_by = $created_by;
                    $model->created_at = date('Y-m-d-H-i-s');
                    $model->updated_at = date('Y-m-d-H-i-s');
                    $model->updated_by = $created_by;  
                    $model->save();  
                    $man_sub_id = $model->coe_mandatory_subjects_id;  

                    if(!empty($man_sub_id))
                    {
                        foreach ($getSubMzainList as $value) 
                        {
                          $new_Model = new MandatorySubcatSubjects();
                          $new_Model->coe_batch_id = $coe_batch_id;
                          $new_Model->man_subject_id = $man_sub_id;
                          $new_Model->batch_map_id = $batch_mapping_id;
                          $new_Model->created_by = $created_by;
                          $new_Model->sub_cat_code = $value['sub_cat_code'];
                          $new_Model->course_type_id = $course_type_id['coe_category_type_id'];
                          $new_Model->subject_type_id = $subject_type_id['coe_category_type_id'];
                          $new_Model->paper_type_id = $paper_type_id['coe_category_type_id'];
                          $new_Model->sub_cat_name = $value['sub_cat_name'];
                          $new_Model->created_at = date('Y-m-d-H-i-s');
                          $new_Model->updated_at = date('Y-m-d-H-i-s');
                          $new_Model->updated_by = $created_by;  
                          $new_Model->is_additional = 'YES';  
                          $new_Model->save(false);    
                          unset($new_Model);  
                          
                        }
                    }
                }

            }
            else
            {
                $model->ESE_min = 0;
                $model->ESE_max = 0;
                $model->created_by = $created_by;
                $model->created_at = date('Y-m-d-H-i-s');
                $model->updated_at = date('Y-m-d-H-i-s');
                $model->updated_by = $created_by;  
                $model->save(); 
            }

            Yii::$app->ShowFlashMessages->setMsg('Success',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code <b>".$model->subject_code."</b>  Created successfully!! ");  
            return $this->redirect(['create']);
        } else {
            Yii::$app->ShowFlashMessages->setMsg('Welcome','Welcome to Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); 
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    public function actionMandatorySubjectsFull()
    {

        $model = new MandatorySubjects();

        if ($model->load(Yii::$app->request->post()))
        {  
            $mandatotry_info = MandatorySubcatSubjects::find()->where(['coe_batch_id'=>$model->man_batch_id])->all();            
           if(!empty($mandatotry_info))
           {
               
                $fetch_query = (new \yii\db\Query());
                $fetch_query->select(['coe_mandatory_subjects_id','batch_name','A.semester','degree_name','programme_name','subject_code','subject_name','CIA_min','ESE_min','CIA_max','ESE_max','total_minimum_pass','end_semester_exam_value_mark as final_marks', 'I.description as paper_type','C.description as course_type','D.description as subject_type','credit_points'])  
                    ->from('coe_mandatory_subjects as A')   
                    ->join('JOIN','coe_mandatory_subcat_subjects as B','B.man_subject_id=A.coe_mandatory_subjects_id')
                    ->join('JOIN','coe_bat_deg_reg as E','E.coe_batch_id=B.coe_batch_id')                    
                    ->join('JOIN','coe_degree as F','F.coe_degree_id=E.coe_degree_id')
                    ->join('JOIN','coe_programme as G','G.coe_programme_id=E.coe_programme_id')
                    ->join('JOIN','coe_batch as H','H.coe_batch_id=E.coe_batch_id')
                    ->join('JOIN','coe_category_type as I','I.coe_category_type_id=B.paper_type_id')
                    ->join('JOIN','coe_category_type as D','D.coe_category_type_id=B.subject_type_id')
                    ->join('JOIN','coe_category_type as C','C.coe_category_type_id=B.course_type_id')
                    ->andWhere('B.coe_batch_id = :coe_batch_id', [':coe_batch_id' => $model->man_batch_id])
                    ->andWhere('A.man_batch_id = :man_batch_id', [':man_batch_id' => $model->man_batch_id])
                    ->groupBy('B.man_subject_id')
                    ->orderBy('A.subject_code'); 
                $man_su_info = $fetch_query->createCommand()->queryAll();

                return $this->render('mandatory-subjects-full', [
                    'man_su_info' => $man_su_info,
                    'model' => $model,
                   
                ]);
           }
           else {
               Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
               return $this->render('mandatory-subjects-full', [
                    'model' => $model,
                    
                ]);
           }
        }else {
            Yii::$app->ShowFlashMessages->setMsg('Success',"Welcome to Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Full Export");
            return $this->render('mandatory-subjects-full', [
                'model' => $model,
                
            ]);
        } 
    }

    public function actionManSubjectInfoExportPdf()
    {    
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));   
        $content = $_SESSION['mandatory_sub_info'];
            $pdf = new Pdf([

                'mode' => Pdf::MODE_CORE,
                'filename' => "Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).'.pdf',                
                'format' => Pdf::FORMAT_A3,                 
                //'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
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
                'options' => ['title' => "Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>["Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ]
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }
    public function actionManSubjectExportInfoExcel()
    {        
        $content = $_SESSION['mandatory_sub_info'];         
        $fileName = "Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." ". date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
    /**
     * Updates an existing MandatorySubjects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
       
        if (Yii::$app->request->isAjax) 
          {
            if($model->load(Yii::$app->request->post())) 
            {
              \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
              return ActiveForm::validate($model);
            }
          }
        $created_by = Yii::$app->user->getId();
        if ($model->load(Yii::$app->request->post())) 
        {
            $model->updated_by = $created_by;
            $model->save();
            return $this->redirect(['view', 'id' => $model->coe_mandatory_subjects_id]);
        }
        else {
            return $this->render('update', [
                'model' => $model,
                
            ]);
        }
    }

    /**
     * Deletes an existing MandatorySubjects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $subjects = MandatorySubcatSubjects::findOne(['man_subject_id'=>$id]);        
        $name_of_subject = !empty($subjects)? $subjects->sub_cat_code:'';
        $sub_name = $this->findModel($id);
        $check_marks = !empty($subjects)? MandatoryStuMarks::find()->where(['subject_map_id'=>$subjects->coe_mandatory_subcat_subjects_id])->all():'';

        if(!empty($check_marks) || !empty($subjects))
        {
           Yii::$app->ShowFlashMessages->setMsg('Error',' You can not delete <b>'.$name_of_subject.'</b> Because already <b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE </b> are Assigned OR <b>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)."</b> are Available OR Marks Are Entered "); 
        }
        else
        {
            $sub_name_code = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE <b>".$sub_name->subject_code." </b>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME <b>".$sub_name->subject_name."</b>";
            Yii::$app->ShowFlashMessages->setMsg('Success',$sub_name_code.' Deleted Successfully!!! ');
            $this->findModel($id)->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the MandatorySubjects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MandatorySubjects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MandatorySubjects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
