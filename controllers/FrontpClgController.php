<?php

namespace app\controllers;

use Yii;
use app\models\FrontpClg;
use app\models\FrontpClgSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\FrontpClgList;

/**
 * FrontpClgController implements the CRUD actions for FrontpClg model.
 */
class FrontpClgController extends Controller
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
     * Lists all FrontpClg models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FrontpClgSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FrontpClg model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $vision = Yii::$app->db->createCommand("SELECT * FROM cur_frontp_clg_list WHERE cur_fp_id='".$id."'")->queryOne();

        $mission = Yii::$app->db->createCommand("SELECT * FROM cur_frontp_clg_list WHERE cur_fp_id='".$id."' AND mission!='-'")->queryAll();

        $po_list = Yii::$app->db->createCommand("SELECT * FROM cur_frontp_clg_list WHERE cur_fp_id='".$id."' AND po!='-'")->queryAll();

        $regulationyear = Yii::$app->db->createCommand("SELECT regulation_year FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();

        if (Yii::$app->request->post())
        {
            $updated_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId();
            
            $updated1= Yii::$app->db->createCommand('UPDATE cur_frontp_clg SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_fp_id="' . $id . '"')->execute();

            if($updated1)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Approved Successfully!");
                
                $model = $this->findModel($id);

                return $this->render('view', [
                    'model'=>$model,
                    'vision' => $vision,
                    'mission'=>$mission,
                    'po_list'=>$po_list,
                    'regulationyear'=>$regulationyear
                    ]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "No Action Found, Please Check");
                return $this->redirect(['index']);
            }
        }
        else
        {
             return $this->render('view', [
                    'model'=>$model,
                    'vision' => $vision,
                    'mission'=>$mission,
                    'po_list'=>$po_list,
                    'regulationyear'=>$regulationyear
                    ]);
        }
    }

    /**
     * Creates a new FrontpClg model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   

    public function actionCreate()
    {
        $model = new FrontpClg();

        if(Yii::$app->request->post()) 
        {
            $checkfrontpage = Yii::$app->db->createCommand("SELECT * FROM cur_frontp_clg A WHERE A.coe_regulation_id=".$_POST['FrontpClg']['coe_regulation_id']." AND A.degree_type='".$_POST['FrontpClg']['degree_type']."'")->queryOne();
            
            if(empty($checkfrontpage))
            {


                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();
                $model = new FrontpClg();
                $model->degree_type=$_POST['FrontpClg']['degree_type'];
                $model->coe_regulation_id=$_POST['FrontpClg']['coe_regulation_id'];
                $model->mission_count=$_POST['FrontpClg']['mission_count'];
                $model->po_count=$_POST['FrontpClg']['po_count'];
                $model->created_at=$created_at;
                $model->created_by=$userid;

                $cnts[]=$_POST['FrontpClg']['mission_count'];
                $cnts[]=$_POST['FrontpClg']['po_count'];

                $cnts = implode(",", $cnts);
                // echo "<pre>";
                // print_r($model); exit;
                if($model->save(false))
                {
                    return $this->redirect(['frontpagelist', 'degree_type' =>$_POST['FrontpClg']['degree_type'],'coe_regulation_id'=>$_POST['FrontpClg']['coe_regulation_id'],'cnts'=>$cnts,'id'=>$model->cur_fp_id]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Data Not Insert, Please Check");
                    return $this->redirect(['create']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Already exist choosen option, Please Check");
                return $this->redirect(['create']);
            }
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionFrontpagelist($degree_type,$coe_regulation_id,$cnts,$id)
    {
        $model = new FrontpClg();

        if(Yii::$app->request->post()) 
        {
            $cntss = explode(",", $cnts);

            $labelss = array('0' =>'mission' , '1' =>'po');
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();
            //print_r($cntss); exit;
            $success=0;
            for ($i=0; $i <count($cntss) ; $i++) 
            {
                if($cntss[$i]>0)
                {
                    $array=$labelss[$i];
                    $datas=$_POST[$array];

                    $arraytitle=$labelss[$i].'title';
                    $datatitle=$_POST[$arraytitle];
                    
                    for ($j=0; $j <count($datas); $j++) 
                    {
                        $model = new FrontpClgList();
                        $model->degree_type=$degree_type;
                        $model->coe_regulation_id=$coe_regulation_id;
                        $model->cur_fp_id=$id;
                        $model->vision=$_POST['vision'];
                        if($i==0)
                        {
                            $model->mission=$datas[$j];
                            $model->po='-';
                            $model->po_title='-';
                        }
                        else if($i==1)
                        {
                            $model->mission='-';
                            $model->po=$datas[$j];
                            $model->po_title=$datatitle[$j];
                        }
                        $model->created_at=$created_at;
                        $model->created_by=$userid;
                        if($model->save(false))
                        {
                            $success++;
                        }
                    }
                }
            }

            if($success>0)
            {
               Yii::$app->ShowFlashMessages->setMsg('Success', "Data successfully inserted..");
                    return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Data Not Insert, Please Check");
                return $this->redirect(['frontpagelist', 'degree_type' =>$degree_type,'coe_regulation_id'=>$coe_regulation_id,'cnts'=>$cnts,'id'=>$id]);
            }

        }
        else
        {
            return $this->render('frontpage_list', [
                'model' => $model,
                'cnts' => $cnts
            ]);
        }

    }

    public function actionCopy()
    {
        $model = new FrontpClg();
        if(Yii::$app->request->post()) 
        {
           
            $checkfrontpage = Yii::$app->db->createCommand("SELECT * FROM cur_frontp_clg A WHERE A.coe_regulation_id=".$_POST['from_regulation_id']." AND A.degree_type='".$_POST['degree_type']."'")->queryOne();
            
            if(!empty($checkfrontpage))
            {
                $checkfrontpage1 = Yii::$app->db->createCommand("SELECT * FROM cur_frontp_clg A WHERE A.coe_regulation_id=".$_POST['to_regulation_id']." AND A.degree_type='".$_POST['degree_type']."'")->queryOne();

                if(empty($checkfrontpage1))
                {
                    $created_at = date("Y-m-d H:i:s");
                    $userid = Yii::$app->user->getId();
                    $model = new FrontpClg();
                    $model->degree_type=$checkfrontpage['degree_type'];
                    $model->coe_regulation_id=$_POST['to_regulation_id'];
                    $model->mission_count=$checkfrontpage['mission_count'];
                    $model->po_count=$checkfrontpage['po_count'];
                    $model->created_at=$created_at;
                    $model->created_by=$userid;

                    if($model->save(false))
                    {
                        $created_at = date("Y-m-d H:i:s");
                        $userid = Yii::$app->user->getId();
                        $cur_fp_id=$model->cur_fp_id;
                        $success=0;
                       
                        $datas = Yii::$app->db->createCommand("SELECT * FROM cur_frontp_clg_list A WHERE A.cur_fp_id='".$checkfrontpage['cur_fp_id']."'")->queryAll();

                        foreach($datas as $value)
                        {
                            $model1 = new FrontpClgList();
                            $model1->degree_type=$value['degree_type'];
                            $model1->coe_regulation_id=$_POST['to_regulation_id'];
                            $model1->cur_fp_id=$cur_fp_id;
                            $model1->vision=$value['vision'];
                            $model1->mission=$value['mission'];
                            $model1->po=$value['po'];
                            $model1->po_title=$value['po_title'];
                            $model1->created_at=$created_at;
                            $model1->created_by=$userid;
                            if($model1->save(false))
                            {
                                $success++;
                            }
                        }                        

                        
                        if($success>0)
                        {
                           Yii::$app->ShowFlashMessages->setMsg('Success', "Data successfully Mapped..");
                                return $this->redirect(['index']);
                        }
                        else
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Error', "Data Not Mapped successfully, Please Check");
                            return $this->redirect(['index']);
                        }
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error', "Data Not Insert, Please Check");
                        return $this->redirect(['copy']);
                    }
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Data Already Inserted, Please Check");
                    return $this->redirect(['copy']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Data Not Found for Mapping choosen regulation , Please Check");
                return $this->redirect(['copy']);
            }
        
        } 
        else 
        {
            return $this->render('copydept', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FrontpClg model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cur_fp_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FrontpClg model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     public function actionDeletedata($id)
    {
        Yii::$app->db->createCommand('DELETE FROM cur_frontp_clg_list WHERE cur_fp_id="'.$id.'"')->execute();
        Yii::$app->db->createCommand('DELETE FROM cur_frontp_clg WHERE cur_fp_id="'.$id.'"')->execute();
        Yii::$app->ShowFlashMessages->setMsg('Success', "Deleted Successfully");
        return $this->redirect(['index']);
    }

    /**
     * Finds the FrontpClg model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FrontpClg the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FrontpClg::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
