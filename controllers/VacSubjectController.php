<?php

namespace app\controllers;

use Yii;
use app\models\VacSubject;
use app\models\VacSubjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Department;
use yii\db\Query;
use app\models\SubjectPrefix;
use app\models\LTP;
use app\models\Batch;
use app\models\Degree;
use app\models\Regulation;
use app\models\Servicesubjecttodept;
use kartik\mpdf\Pdf;
use yii\helpers\Json;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
/**
 * VacSubjectController implements the CRUD actions for VacSubject model.
 */
class VacSubjectController extends Controller
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
     * Lists all VacSubject models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VacSubjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VacSubject model.
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
     * Creates a new VacSubject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VacSubject();

        if ($model->load(Yii::$app->request->post())) 
        {
            $subject_code=strtoupper($_POST['subject_prefix']).strtoupper($model->subject_code);

            $query1 = new Query();           
            $query1->select('subject_code,subject_name')->from('cur_vac_subject')->andWhere(['coe_regulation_id'=>$model->coe_regulation_id,'subject_code'=>$subject_code])->andWhere(['degree_type'=>$model->degree_type]);
            $pgmdata1 = $query1->createCommand()->queryAll();

            $query2 = new Query();           
            $query2->select('subject_code,subject_name')->from('cur_vac_subject')->andWhere(['coe_regulation_id'=>$model->coe_regulation_id])->andWhere(['degree_type'=>$model->degree_type]);
            $pgmdata2 = $query2->createCommand()->queryAll();

            $checksubject_name=0;
            foreach ($pgmdata2 as $value) 
            {
                $existname = str_replace(' ', '-', strtolower($value['subject_name']));
                $newname = str_replace(' ', '-', strtolower($model->subject_name));
                $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                if($existname==$newname)
                {
                    $checksubject_name=1;

                }
            }
               
            
            //echo $checksubject_name; exit;
            if(empty($pgmdata1) && $checksubject_name==0)
            { 
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 
                $model->coe_dept_id=$_POST['coe_dept_id'];
                $model->subject_code=$subject_code;
                $model->subject_type_id=218;
                $model->subject_category_type_id=143;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Course Added successfully..");
                    return $this->redirect(['index']);
                   
                    
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                    return $this->redirect(['create']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Course Code or Course Name Already Exist! Please Enter New One");
                return $this->redirect(['create']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing VacSubject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $query2 = new Query();           
            $query2->select('subject_code,subject_name')->from('cur_vac_subject')->andWhere(['coe_regulation_id'=>$model->coe_regulation_id])->andWhere(['degree_type'=>$model->degree_type]);
            $pgmdata2 = $query2->createCommand()->queryAll();

            $subjmerge=array_merge($pgmdata1,$pgmdata2);
            $checksubject_name=0;
            foreach ($subjmerge as $value) 
            {
                $existname = str_replace(' ', '-', strtolower($value['subject_name']));
                $newname = str_replace(' ', '-', strtolower($model->subject_name));
                $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                if($existname==$newname)
                {
                    $checksubject_name=1;

                }
            }
                //print_r($subjmerge); exit;
            if(empty($pgmdata2) && $checksubject_name==0)
            { 
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 
                $model->coe_dept_id=$_POST['coe_dept_id'];
                $model->subject_code=$subject_code;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Course Added successfully..");
                    return $this->redirect(['index']);
                   
                    
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                    return $this->redirect(['create']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Course Code or Course Name Already Exist! Please Enter New One");
                return $this->redirect(['create']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing VacSubject model.
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
     * Finds the VacSubject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VacSubject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VacSubject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
