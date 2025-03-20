<?php

namespace app\controllers;

use Yii;
use app\models\ElectiveCount;
use app\models\ElectiveCountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ElectiveCountController implements the CRUD actions for ElectiveCount model.
 */
class ElectiveCountController extends Controller
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
     * Lists all ElectiveCount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ElectiveCountSearch();
        $_SESSION['elective_types']='193';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single ElectiveCount model.
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
     * Creates a new ElectiveCount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ElectiveCount();

        if ($model->load(Yii::$app->request->post())) 
        {
            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();  
            
            $model->coe_batch_id=$coe_batch_id;

            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();  
            $model->coe_dept_id= $_POST['coe_dept_id'];
            $model->elective_type=193;
            $model->created_at=$created_at;
            $model->created_by=$userid;
                
            if($model->save(false))
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Elective Count Added successfully..");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Elective Count not successfully add please check..");
                return $this->redirect(['index']);
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ElectiveCount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();  
            
            $model->coe_batch_id=$coe_batch_id;
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();  
            $model->coe_dept_id= $_POST['coe_dept_id'];
            $model->elective_type=193;
            $model->created_at=$created_at;
            $model->created_by=$userid;
                
            if($model->save(false))
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Elective Count Updated successfully..");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Elective Count not successfully update please check..");
                return $this->redirect(['index']);
            }
            
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ElectiveCount model.
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
     * Finds the ElectiveCount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ElectiveCount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ElectiveCount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionOecIndex()
    {
        $searchModel = new ElectiveCountSearch();
        $_SESSION['elective_types']='191';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('oec-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOecCreate()
    {
        $model = new ElectiveCount();       
        
        if ($model->load(Yii::$app->request->post())) 
        {
            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();  
            
            $model->coe_batch_id=$coe_batch_id;

            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();  
            $model->coe_dept_id= $_POST['coe_dept_id'];
            $model->elective_type=191;
            $model->created_at=$created_at;
            $model->created_by=$userid;
                
            if($model->save(false))
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Elective Count Added successfully..");
                return $this->redirect(['oec-index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Elective Count not successfully add please check..");
                return $this->redirect(['oec-index']);
            }

            return $this->redirect(['oec-index']);
        } else {
            return $this->render('oec-create', [
                'model' => $model,
            ]);
        }
    }

    public function actionOecUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();  
            
            $model->coe_batch_id=$coe_batch_id;
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();  
            $model->coe_dept_id= $_POST['coe_dept_id'];
            $model->elective_type=191;
            $model->created_at=$created_at;
            $model->created_by=$userid;
                
            if($model->save(false))
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Elective Count Updated successfully..");
                return $this->redirect(['oec-index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Elective Count not successfully update please check..");
                return $this->redirect(['oec-index']);
            }
            
            return $this->redirect(['oec-index']);
        } else {
            return $this->render('oec-update', [
                'model' => $model,
            ]);
        }
    }

}
