<?php

namespace app\controllers;
use yii\db\Query;
use Yii;
use app\models\VerticalStream;
use app\models\VerticalStreamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\AicteNorms;
error_reporting(0);
/**
 * VerticalStreamController implements the CRUD actions for VerticalStream model.
 */
class VerticalStreamController extends Controller
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
     * Lists all VerticalStream models.
     * @return mixed
     */
    public function actionIndex()
    {
         $_SESSION['vstream']='205';

        $searchModel = new VerticalStreamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new VerticalStream model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VerticalStream();

        if ($model->load(Yii::$app->request->post())) 
        {   
            $vertical_count=explode(",", $_POST['vertical_count']);

            $query2 = new Query();           
            $query2->select('vertical_name')->from('cur_vertical_stream')->Where(['coe_regulation_id'=>$model->coe_regulation_id,'coe_dept_id'=>$model->coe_dept_id ]);
            $pgmdata2 = $query2->createCommand()->queryAll();

            $checksubject_name=0;
            foreach ($pgmdata2 as $value) 
            {
                $existname = str_replace(' ', '-', strtolower($value['vertical_name']));
                $newname = str_replace(' ', '-', strtolower($model->vertical_name));
                $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                if($existname==$newname)
                {
                    $checksubject_name=1;

                }
            }

            if(count($_POST['coe_dept_id'])==count($vertical_count) && $checksubject_name==0)
            {
                $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();  
            
                $model->coe_batch_id=$coe_batch_id;
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();  
                $model->vertical_type  =205;
                $model->degree_type  ='UG'; 
                $model->coe_dept_id= implode(",", $_POST['coe_dept_id']);
                $model->vertical_count= implode(",", $vertical_count);
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Vertical Name Added successfully..");
                    return $this->redirect(['create']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Vertical Name not successfully add please check..");
                    return $this->redirect(['index']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Choosen Dept and vertical count not same or Vertical Name Already Exist! please check..");
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
            $vertical_count=explode(",", $_POST['vertical_count']);

            $query2 = new Query();           
            $query2->select('cur_vs_id,vertical_name')->from('cur_vertical_stream')->where(['!=','cur_vs_id',$id])->Where(['coe_regulation_id'=>$model->coe_regulation_id,'coe_dept_id'=>$model->coe_dept_id ]);
            $pgmdata2 = $query2->createCommand()->queryAll();

            $checksubject_name=0;
            foreach ($pgmdata2 as $value) 
            {
                if($value['cur_vs_id']!=$id)
                {
                    $existname = str_replace(' ', '-', strtolower($value['vertical_name']));
                    $newname = str_replace(' ', '-', strtolower($model->vertical_name));
                    $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                    $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                    if($existname==$newname)
                    {
                        $checksubject_name=1;

                    }
                }
            }

            if($checksubject_name==0)
            {
                $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();  
            
                $model->coe_batch_id=$coe_batch_id;
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 
                $model->degree_type  ='UG'; 
                $model->vertical_type  =205;
                $model->coe_dept_id= implode(",", $_POST['coe_dept_id']);
                $model->vertical_count= implode(",", $vertical_count);
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Vertical Name updated successfully..");
                    return $this->redirect(['index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Vertical Name not successfully update please check..");
                    return $this->redirect(['index']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Choosen Dept and vertical count not same or Vertical Name Already Exist! please check..");
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionIndexMajor()
    {
         $_SESSION['vstream']='206';
        $searchModel = new VerticalStreamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       
        return $this->render('index-major', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new VerticalStream model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateMajor()
    {
        $model = new VerticalStream();

        if ($model->load(Yii::$app->request->post())) 
        {   
            $vertical_count=explode(",", $_POST['vertical_count']);

            $query2 = new Query();           
            $query2->select('vertical_name')->from('cur_vertical_stream')->Where(['coe_dept_id'=>$_POST['coe_dept_id'],'coe_regulation_id'=>$model->coe_regulation_id ]);
            $pgmdata2 = $query2->createCommand()->queryAll();

            $checksubject_name=0;
            foreach ($pgmdata2 as $value) 
            {
                $existname = str_replace(' ', '-', strtolower($value['vertical_name']));
                $newname = str_replace(' ', '-', strtolower($model->vertical_name));
                $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                if($existname==$newname)
                {
                    $checksubject_name=1;

                }
            }

            if(count($_POST['coe_dept_id'])==count($vertical_count) && $checksubject_name==0)
            {
                $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();  
            
                $model->coe_batch_id=$coe_batch_id;
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();  
                $model->vertical_type  =206;
                $model->degree_type  ='UG'; 
                $model->coe_dept_id= implode(",", $_POST['coe_dept_id']);
                $model->vertical_count= implode(",", $vertical_count);
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Vertical Name Added successfully..");
                    return $this->redirect(['create-major']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Vertical Name not successfully add please check..");
                    return $this->redirect(['index-major']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Choosen Dept and vertical count not same or Vertical Name Already Exist! please check..");
                return $this->redirect(['index-major']);
            }

        } else {
            return $this->render('create-major', [
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
    public function actionUpdateMajor($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $vertical_count=explode(",", $_POST['vertical_count']);

            $query2 = new Query();           
            $query2->select('cur_vs_id,vertical_name')->from('cur_vertical_stream')->where(['!=','cur_vs_id',$id])->andWhere(['coe_dept_id'=>$_POST['coe_dept_id'],'coe_regulation_id'=>$model->coe_regulation_id ]);
            $pgmdata2 = $query2->createCommand()->queryAll();

            $checksubject_name=0;
            foreach ($pgmdata2 as $value) 
            {
                if($value['cur_vs_id']!=$id)
                {
                    $existname = str_replace(' ', '-', strtolower($value['vertical_name']));
                    $newname = str_replace(' ', '-', strtolower($model->vertical_name));
                    $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                    $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                    if($existname==$newname)
                    {
                        $checksubject_name=1;

                    }
                }
            }
            //print_r($pgmdata2); exit();
            if($checksubject_name==0)
            {
                $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();  
            
                $model->coe_batch_id=$coe_batch_id;
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 
                $model->degree_type  ='UG'; 
                $model->vertical_type  =206;
                $model->coe_dept_id= implode(",", $_POST['coe_dept_id']);
                $model->vertical_count= implode(",", $vertical_count);
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Vertical Name updated successfully..");
                    return $this->redirect(['index-major']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Vertical Name not successfully update please check..");
                    return $this->redirect(['index-major']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Choosen Dept and vertical count not same or Vertical Name Already Exist! please check..");
                return $this->redirect(['index-major']);
            }
        } else {
            return $this->render('update-major', [
                'model' => $model,
            ]);
        }
    }



    /**
     * Deletes an existing VerticalStream model.
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
     * Finds the VerticalStream model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VerticalStream the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VerticalStream::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
