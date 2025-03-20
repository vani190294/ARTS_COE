<?php

namespace app\controllers;

use Yii;
use app\Models\DeptPso;
use app\Models\DeptPsoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeptPsoController implements the CRUD actions for DeptPso model.
 */
class DeptPsoController extends Controller
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
     * Lists all DeptPso models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeptPsoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeptPso model.
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
     * Creates a new DeptPso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeptPso();

        if ($model->load(Yii::$app->request->post()))  
        {
            $check_pso = Yii::$app->db->createCommand("SELECT count(*) FROM cur_dept_pso WHERE coe_regulation_id=".$model->coe_regulation_id."  AND degree_type='".$model->degree_type."' AND coe_dept_id='".$_POST['coe_dept_id']."'")->queryScalar();

            if($check_pso==0)
            {
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 
                $model->pso_title="PSO";
                $model->coe_dept_id= $_POST['coe_dept_id'];
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "PSO Added successfully..");
                    return $this->redirect(['index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "PSO not successfully add please check..");
                    return $this->redirect(['index']);
                }
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DeptPso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            //echo "SELECT count(*) FROM cur_dept_pso WHERE coe_regulation_id=".$model->coe_regulation_id."  AND degree_type='".$model->degree_type."' AND coe_dept_id='".$_POST['coe_dept_id']."' AND cur_vs_id!=".$id; exit();
            $check_pso = Yii::$app->db->createCommand("SELECT count(*) FROM cur_dept_pso WHERE coe_regulation_id=".$model->coe_regulation_id."  AND degree_type='".$model->degree_type."' AND coe_dept_id='".$_POST['coe_dept_id']."' AND cur_vs_id!=".$id)->queryScalar();

            if($check_pso==0)
            {
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 
                $model->pso_title="PSO";
                $model->coe_dept_id= $_POST['coe_dept_id'];
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "PSO updated successfully..");
                    return $this->redirect(['index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "PSO not successfully update please check..");
                    return $this->redirect(['index']);
                }
            }

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DeptPso model.
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
     * Finds the DeptPso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeptPso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeptPso::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
