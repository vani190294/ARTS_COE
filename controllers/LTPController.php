<?php

namespace app\controllers;

use Yii;
use app\models\LTP;
use app\models\LTPSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
/**
 * LTPController implements the CRUD actions for LTP model.
 */
class LTPController extends Controller
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
     * Lists all LTP models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LTPSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LTP model.
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
     * Creates a new LTP model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LTP();

        if ($model->load(Yii::$app->request->post()))
        {
            $other_cource=$_POST['int_mode_paper']; //exit;

            if($other_cource>0)
            {
                $query = new Query();           
                $query->select('L')->from('cur_ltp')->where(['coe_regulation_id' =>$model->coe_regulation_id,'L' =>$model->L,'T' =>$model->T,'P' =>$model->P,'subject_type_id' =>$other_cource]);
                $pgmdata = $query->createCommand()->queryAll();
            }
            else
            {
                $query = new Query();           
                $query->select('L')->from('cur_ltp')->where(['coe_regulation_id' =>$model->coe_regulation_id,'L' =>$model->L,'T' =>$model->T,'P' =>$model->P,'subject_type_id' =>$other_cource ]);
                $pgmdata = $query->createCommand()->queryAll();
            }
            

            if(empty($pgmdata))
            {
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 
                $model->subject_type_id=$_POST['subject_type_id'];
                $model->subject_category_type_id=$_POST['subject_category_type_id'];
                $model->created_at=$created_at;
                $model->created_by=$userid;
                $model->save(false);
                Yii::$app->ShowFlashMessages->setMsg('Success', "LTP Added successfully..");
                return $this->redirect(['create']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Duplicate LTP Not Allowed! Please Check");
                return $this->redirect(['create']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LTP model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
         if ($model->load(Yii::$app->request->post()))
        {
            $other_cource=$_POST['int_mode_paper']; //exit;

            if($other_cource>0)
            {
                $query = new Query();           
                $query->select('L')->from('cur_ltp')->where(['coe_regulation_id' =>$model->coe_regulation_id,'L' =>$model->L,'T' =>$model->T,'P' =>$model->P ])->andWhere(['!=','coe_ltp_id',$id]);
                $pgmdata = $query->createCommand()->queryAll();
            }
            else
            {
                $query = new Query();           
                $query->select('L')->from('cur_ltp')->where(['coe_regulation_id' =>$model->coe_regulation_id,'L' =>$model->L,'T' =>$model->T,'P'=>$model->P,'subject_type_id' =>$other_cource ])->andWhere(['!=','coe_ltp_id',$id]);
                $pgmdata = $query->createCommand()->queryAll();
            }
            

            if(empty($pgmdata))
            {
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 
                $model->subject_type_id=$_POST['subject_type_id'];
                $model->subject_category_type_id=$_POST['subject_category_type_id'];
                $model->created_at=$created_at;
                $model->created_by=$userid;
                $model->save(false);
                Yii::$app->ShowFlashMessages->setMsg('Success', "LTP updated successfully..");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Duplicate LTP Not Allowed! Please Check");
                return $this->redirect(['update', 'id' => $model->coe_ltp_id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function actionCreateExisting()
    {
        $model = new LTP();

        if (Yii::$app->request->post()) 
        {
            $streamdata = Yii::$app->db->createCommand("SELECT * FROM cur_ltp WHERE coe_regulation_id='".$_POST['from_regulation_id']."'")->queryAll();

            $success=0;
            foreach ($streamdata as $value) 
            {

               $query = new Query();           
                $query->select('L')->from('cur_ltp')->where(['coe_regulation_id' =>$_POST['to_regulation_id'],'L' =>$value['L'],'T' =>$value['T'],'P' =>$value['P'],'subject_type_id' =>$value['subject_type_id'] ]);
                $pgmdata = $query->createCommand()->queryAll();
                
                if(empty($pgmdata))
                {
                    $model = new LTP();
                    $created_at = date("Y-m-d H:i:s");
                    $userid = Yii::$app->user->getId();  
                    $model->coe_regulation_id=$_POST['to_regulation_id'];
                    $model->L=$value['L'];           
                    $model->T=$value['T'];
                    $model->P=$value['P'];
                    $model->contact_hrsperweek=$value['contact_hrsperweek'];
                    $model->credit_point=$value['credit_point'];
                    $model->subject_type_id=$value['subject_type_id'];
                    $model->subject_category_type_id=$value['subject_category_type_id'];
                    $model->external_mark=$value['external_mark'];
                    $model->internal_mark=$value['internal_mark'];
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
                Yii::$app->ShowFlashMessages->setMsg('Success', "LTP Assigned successfully..");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "LTP Assigned Not successful! Please Check");
                return $this->redirect(['index']);
            }
        
            
        } else {
            return $this->render('create-existing', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Deletes an existing LTP model.
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
     * Finds the LTP model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LTP the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LTP::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
