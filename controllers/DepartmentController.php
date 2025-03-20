<?php

namespace app\controllers;

use Yii;
use app\models\Department;
use app\models\DepartmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\SubjectPrefix;
use yii\db\Query;
/**
 * DepartmentController implements the CRUD actions for Department model.
 */
class DepartmentController extends Controller
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
     * Lists all Department models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DepartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Department model.
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
     * Creates a new Department model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Department();

         if ($model->load(Yii::$app->request->post()))
        {
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId(); 
            $prefix_name=explode(",", $model->prefix_name);            
            $model->dept_code=strtoupper($model->dept_code);
            $model->dept_name=strtoupper($model->dept_name);
            $model->prefix_name=strtoupper($model->prefix_name);
            $model->created_at=$created_at;
            $model->created_by=$userid;
            $model->save(false);
            $coe_dept_id = $model->coe_dept_id;            
            
            $success=0;
            for ($i=0; $i <count($prefix_name) ; $i++) 
            { 
                $query = new Query();           
                $query->select('*')->from('cur_subject_prefix A')->where(['coe_dept_id' => $coe_dept_id,'prefix_name' =>strtoupper($prefix_name[$i])]);
                $pgmdata = $query->createCommand()->queryAll();

                if(empty($pgmdata))
                {
                    $model1 = new SubjectPrefix();
                    $model1->coe_dept_id=$coe_dept_id;
                    $model1->prefix_name=strtoupper($prefix_name[$i]);
                    $model1->created_at=$created_at;
                    $model1->created_by=$userid;
                    $model1->save(false);

                     $success=$success+1;
                }

               
            }
           
            if($success>0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Dept and Subject Prefix Added successfully..");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Dept and Subject Prefix Not added or already exist! please check..");
                return $this->redirect(['create']);
            }
            
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Department model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            $modelold = $this->findModel($id);  
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId(); 

            $prefix_name=explode(",", $model->prefix_name);            
            $model->dept_code=strtoupper($model->dept_code);
            $model->dept_name=strtoupper($model->dept_name);
            $model->prefix_name=strtoupper($model->prefix_name);
            $model->created_at=$created_at;
            $model->created_by=$userid;
            $model->save(false);
            
            $coe_dept_id = $id;    
             $success=0;
                 
            if($modelold->prefix_name!=$model->prefix_name)
            {
                
                Yii::$app->db->createCommand()->delete('cur_subject_prefix', ['coe_dept_id' => $coe_dept_id])->execute();
               
                for ($i=0; $i <count($prefix_name) ; $i++) 
                { 
                    
                    $query = new Query();           
                    $query->select('*')->from('cur_subject_prefix A')->where(['coe_dept_id' => $coe_dept_id,'prefix_name' =>strtoupper($prefix_name[$i])]);
                    $pgmdata = $query->createCommand()->queryone();

                    if(empty($pgmdata))
                    {
                        $model1 = new SubjectPrefix();
                        $model1->coe_dept_id=$coe_dept_id;
                        $model1->prefix_name=strtoupper($prefix_name[$i]);
                        $model1->created_at=$created_at;
                        $model1->created_by=$userid;
                        $model1->save(false);

                         $success=$success+1;
                    }

                   
                }

            }
           
            if($success>0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Dept and Subject Prefix updated successfully..");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Dept and Subject Prefix Not updated or already exist! please check..");
               return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Department model.
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
     * Finds the Department model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Department the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Department::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
