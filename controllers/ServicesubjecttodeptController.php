<?php

namespace app\controllers;

use Yii;
use app\models\Servicesubjecttodept;
use app\models\ServicesubjecttodeptSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
/**
 * ServicesubjecttodeptController implements the CRUD actions for Servicesubjecttodept model.
 */
class ServicesubjecttodeptController extends Controller
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
     * Lists all Servicesubjecttodept models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServicesubjecttodeptSearch();
        $dataProvider = $searchModel->search(1);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Servicesubjecttodept model.
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
     * Creates a new Servicesubjecttodept model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Servicesubjecttodept();

        if (Yii::$app->request->post()) 
        {
            $coe_dept_ids=$_POST['coe_dept_ids'];
            //print_r($coe_dept_ids); exit();
            $success=0; 
            if(count($coe_dept_ids)>0)
            {
                for ($i=0; $i <count($coe_dept_ids) ; $i++) 
                {

                    $colablist = Yii::$app->db->createCommand("SELECT count(*) FROM cur_servicesubtodept WHERE coe_cur_subid=".$_POST['coe_cur_subid']." AND semester=".$_POST['semester']." AND coe_dept_ids=".$coe_dept_ids[$i])->queryScalar();
                    //echo "<pre>";
                    if($colablist==0)
                    {
                        $model = new Servicesubjecttodept();
                        $model->degree_type=$_POST['degree_type'];
                        $model->semester=$_POST['semester'];
                        $model->coe_regulation_id=$_POST['coe_regulation_id'];
                        $model->coe_cur_subid=$_POST['coe_cur_subid'];
                        $model->coe_dept_id=8;
                        $created_at = date("Y-m-d H:i:s");
                        $userid = Yii::$app->user->getId(); 
                        $model->coe_dept_ids=$coe_dept_ids[$i];
                        $model->created_at=$created_at;
                        $model->created_by=$userid;
                        //print_r($model);exit;
                        if($model->save(false))
                        {
                           $success++;
                        }
                    }
                }

                if($success>0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', $success." Course Assigned successfully..");
                    return $this->redirect(['index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Course Already Assigned..");
                    return $this->redirect(['index']);
                }
            }
            else
            {
               Yii::$app->ShowFlashMessages->setMsg('Error', "Choose Dept");
                return $this->redirect(['index']); 
            }
        
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Servicesubjecttodept model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $coe_dept_ids=implode(",",$model->coe_dept_ids);
            //print_r($coe_dept_ids);exit;
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId(); 
            $model->coe_dept_ids=$coe_dept_ids;
            $model->created_at=$created_at;
            $model->created_by=$userid;
            $model->save(false);
            Yii::$app->ShowFlashMessages->setMsg('Success', "Subject Assigned successfully..");
            return $this->redirect(['index']);
        
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Servicesubjecttodept model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteData($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->ShowFlashMessages->setMsg('Success', "Assigned Course Deleted successfully..");
        return $this->redirect(['index']);
    }

    public function actionMcIndex()
    {
        $searchModel = new ServicesubjecttodeptSearch();
        $dataProvider = $searchModel->search(2);

        return $this->render('mc-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMcCreate()
    {
        $model = new Servicesubjecttodept();

        if (Yii::$app->request->post()) 
        {
            $coe_dept_ids=$_POST['coe_dept_ids'];
            //print_r($coe_dept_ids); exit();
            $success=0; 
            if(count($coe_dept_ids)>0)
            {
                for ($i=0; $i <count($coe_dept_ids) ; $i++) 
                {

                    $colablist = Yii::$app->db->createCommand("SELECT count(*) FROM cur_servicesubtodept WHERE coe_cur_subid=".$_POST['coe_cur_subid']." AND semester=0 AND coe_dept_ids=".$coe_dept_ids[$i])->queryScalar();
                    //echo "<pre>";
                    if($colablist==0)
                    {
                        $model = new Servicesubjecttodept();
                        $model->degree_type=$_POST['degree_type'];
                        $model->semester=0;
                        $model->coe_regulation_id=$_POST['coe_regulation_id'];
                        $model->coe_cur_subid=$_POST['coe_cur_subid'];
                        $model->coe_dept_id=8;
                        $created_at = date("Y-m-d H:i:s");
                        $userid = Yii::$app->user->getId(); 
                        $model->coe_dept_ids=$coe_dept_ids[$i];
                        $model->created_at=$created_at;
                        $model->created_by=$userid;
                        //print_r($model);exit;
                        if($model->save(false))
                        {
                           $success++;
                        }
                    }
                }

                if($success>0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', $success." Course Assigned successfully..");
                    return $this->redirect(['mc-index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Course Already Assigned successfully..");
                    return $this->redirect(['mc-index']);
                }
            }
            else
            {
               Yii::$app->ShowFlashMessages->setMsg('Error', "Choose Dept");
                return $this->redirect(['mc-index']); 
            }
        
        } else {
            return $this->render('mc-create', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteMcdata($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->ShowFlashMessages->setMsg('Success', "Assigned Course Deleted successfully..");
        return $this->redirect(['mc-index']);
    }

    /**
     * Finds the Servicesubjecttodept model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Servicesubjecttodept the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Servicesubjecttodept::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
