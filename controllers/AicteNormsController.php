<?php

namespace app\controllers;

use Yii;
use app\models\AicteNorms;
use app\models\AicteNormsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AicteNormsController implements the CRUD actions for AicteNorms model.
 */
class AicteNormsController extends Controller
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
     * Lists all AicteNorms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AicteNormsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AicteNorms model.
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
     * Creates a new AicteNorms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AicteNorms();

        if ($model->load(Yii::$app->request->post())) 
        {
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();             
            $model->created_at=$created_at;
            $model->created_by=$userid;
            if($model->save(false))
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Stream Added successfully..");
                return $this->redirect(['create']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Dept and Subject Prefix Added successfully..");
                return $this->redirect(['index']);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing AicteNorms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();             
            $model->created_at=$created_at;
            $model->created_by=$userid;
            if($model->save(false))
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Stream updated successfully..");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Dept and Subject Prefix updated successfully..");
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function actionCreateExisting()
    {
        $model = new AicteNorms();

        if (Yii::$app->request->post()) 
        {
            $streamdata = Yii::$app->db->createCommand("SELECT * FROM cur_aicte_norms WHERE coe_regulation_id=".$_POST['from_regulation_id']."  AND degree_type='".$_POST['degree_type']."'")->queryAll();

            $success=0;
            foreach ($streamdata as $value) 
            {

                $streamcheck = Yii::$app->db->createCommand("SELECT count(*) FROM cur_aicte_norms WHERE coe_regulation_id=".$_POST['to_regulation_id']."  AND degree_type='".$_POST['degree_type']."' AND stream_name='".$value['stream_name']."'")->queryScalar();
                
                if($streamcheck==0)
                {
                    $model = new AicteNorms();
                    $created_at = date("Y-m-d H:i:s");
                    $userid = Yii::$app->user->getId();  
                    $model->coe_regulation_id=$_POST['to_regulation_id'];
                    $model->degree_type=$_POST['degree_type'];           
                    $model->stream_name=$value['stream_name'];
                    $model->stream_fullname=$value['stream_fullname'];
                    $model->created_at=$created_at;
                    $model->created_by=$userid;
                    if($model->save(false))
                    {
                        $success++;
                    }
                }
                
            }
            
            if($success>0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Stream Name Assigned successfully..");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Stream Name Assigned Not successful! Please Check");
                return $this->redirect(['index']);
            }
        
            
        } else {
            return $this->render('create-existing', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AicteNorms model.
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
     * Finds the AicteNorms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AicteNorms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AicteNorms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
