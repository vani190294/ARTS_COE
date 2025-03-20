<?php

namespace app\controllers;

use Yii;
use app\models\DummySequence;
use app\models\DummySequenceSearch;
use app\models\DummyNumbers;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\MarkEntryMaster;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;

/**
 * DummySequenceController implements the CRUD actions for DummySequence model.
 */
class DummySequenceController extends Controller
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
     * Lists all DummySequence models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DummySequenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DummySequence model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->redirect(['index']);   
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DummySequence model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DummySequence();
        return $this->redirect(['index']);   
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coe_store_dummy_mapping]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DummySequence model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $getStatus = MarkEntryMaster::find()->where(['year'=>$model->year,'month'=>$model->month,'status_id'=>1])->one();
        if( isset($getStatus) && !empty($getStatus) && $getStatus->status_id==1)
        {
            Yii::$app->ShowFlashMessages->setMsg('ERROR', "OOPS SORRY RESULTS ALREADY PUBLISHED!!!");
            return $this->redirect(['index']);   
        }

        if ($model->load(Yii::$app->request->post())) 
        {
            $updated_from = ucwords( str_replace('-', ' ', Yii::$app->controller->action->controller->id.' '.Yii::$app->controller->action->id) );
            $data_updated = 'Prev Dummy Number From '.$model->oldAttributes['dummy_from'].' New Dummy Number From '.$model->dummy_from.' Prev Dummy Number To '.$model->oldAttributes['dummy_to'].' New Dummy Number To '.$model->dummy_to;
            $data_array = ['subject_map_id'=>0,'updated_link_from'=>$updated_from,'data_updated'=>nl2br($data_updated),'exam_month'=>0,'exam_year'=>0,'student_map_id'=>0];            
            $update_track = ConfigUtilities::updateTracker($data_array);

             $model->save();
            return $this->redirect(['view', 'id' => $model->coe_store_dummy_mapping]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DummySequence model.
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
     * Finds the DummySequence model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DummySequence the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DummySequence::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
