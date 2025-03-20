<?php

namespace app\controllers;

use Yii;
use app\models\CoeAddPoints;
use app\models\CoeAddPointsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/**
 * CoeAddPointsController implements the CRUD actions for CoeAddPoints model.
 */
class CoeAddPointsController extends Controller
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
     * Lists all CoeAddPoints models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoeAddPointsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoeAddPoints model.
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
     * Creates a new CoeAddPoints model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CoeAddPoints();
        // print_r($model->attributes);exit; 
       // print_r($model);exit;
    if($model->load(Yii::$app->request->post()))
    {
            $model->created_at = new \yii\db\Expression('NOW()');
            $model->created_by = Yii::$app->user->getId();
            $model->updated_at = new \yii\db\Expression('NOW()');
            $model->updated_by = Yii::$app->user->getId();
            $model->attributes;
           
            //$model->save(); 

             if($model->save())
                {  
                    
                    $model = new CoeAddPoints();
                    Yii::$app->ShowFlashMessages->setMsg('Success',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Created Successfully!!!! ');
                    return $this->render('create', ['model' => $model,]);
                } 
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Creation Error!!!! ');
                    return $this->render('create', ['model' => $model,]);
                }
            } 
            else {
                 Yii::$app->ShowFlashMessages->setMsg('Welcome to Activity ',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT));
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CoeAddPoints model.
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
     * Deletes an existing CoeAddPoints model.
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
     * Finds the CoeAddPoints model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoeAddPoints the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoeAddPoints::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
