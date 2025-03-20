<?php

namespace app\controllers;

use Yii;
use app\models\CDCFrontpage;
use app\models\CDCFrontpageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CDCFrontpageList;
use app\models\FrontPeoPoMapping;

/**
 * CDCFrontpageController implements the CRUD actions for CDCFrontpage model.
 */
class CdcFrontpageController extends Controller
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
     * Lists all CDCFrontpage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CDCFrontpageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CDCFrontpage model.
     * @param integer $id
     * @return mixed
     */
   public function actionView($id)
    {
        $model = $this->findModel($id);
        $vision = Yii::$app->db->createCommand("SELECT * FROM cur_frontpage_list WHERE cur_fp_id='".$id."'")->queryOne();

        $mission = Yii::$app->db->createCommand("SELECT * FROM cur_frontpage_list WHERE cur_fp_id='".$id."' AND mission!='-'")->queryAll();

        $peo_list = Yii::$app->db->createCommand("SELECT * FROM cur_frontpage_list WHERE cur_fp_id='".$id."' AND peo!='-'")->queryAll();

        $pso_list = Yii::$app->db->createCommand("SELECT * FROM cur_frontpage_list WHERE cur_fp_id='".$id."' AND pso!='-'")->queryAll();

        $checksemdata = Yii::$app->db->createCommand("SELECT dept_code,degree_type FROM cur_department WHERE coe_dept_id='".$model->coe_dept_id."'")->queryOne();

        $regulationyear = Yii::$app->db->createCommand("SELECT regulation_year FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();

        if($model->degree_type=='UG')
        {
            $po_count = Yii::$app->db->createCommand("SELECT po_count FROM cur_frontp_clg A WHERE A.coe_regulation_id=".$model->coe_regulation_id." AND A.degree_type='".$model->degree_type."'")->queryScalar();
        }
        else
        {
            $po_count = Yii::$app->db->createCommand("SELECT po_count FROM cur_frontpage WHERE cur_fp_id=".$id)->queryScalar();
        }

        if (Yii::$app->request->post())
        {
            $updated_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId();
            
            $updated1= Yii::$app->db->createCommand('UPDATE cur_frontpage SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_fp_id="' . $id . '"')->execute();

            if($updated1)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Approved Successfully!");
                
                $model = $this->findModel($id);

                return $this->render('view', [
                    'model'=>$model,
                    'vision' => $vision,
                    'mission'=>$mission,
                    'peo_list'=>$peo_list,
                    'pso_list'=>$pso_list,
                    'checksemdata'=>$checksemdata,
                    'regulationyear'=>$regulationyear,
                    'po_count'=>$po_count
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
                    'peo_list'=>$peo_list,
                    'pso_list'=>$pso_list,
                    'checksemdata'=>$checksemdata,
                    'regulationyear'=>$regulationyear,
                    'po_count'=>$po_count
                    ]);
        }
    }

    /**
     * Creates a new CDCFrontpage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CDCFrontpage();

        if(Yii::$app->request->post()) 
        {
            $checkfrontpage = Yii::$app->db->createCommand("SELECT * FROM cur_frontpage A WHERE A.coe_dept_id='".$_POST['coe_dept_id']."' AND A.coe_regulation_id=".$_POST['CDCFrontpage']['coe_regulation_id']." AND A.degree_type='".$_POST['CDCFrontpage']['degree_type']."'")->queryOne();
            
            $po_count = Yii::$app->db->createCommand("SELECT po_count FROM cur_frontp_clg A WHERE A.coe_regulation_id=".$_POST['CDCFrontpage']['coe_regulation_id']." AND A.degree_type='".$_POST['CDCFrontpage']['degree_type']."'")->queryScalar();
            
            if(empty($po_count) && $_POST['CDCFrontpage']['degree_type']=='UG')
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "PO's Not Created choosen option, Please Contact Admin");
                return $this->redirect(['create']);
            }
            else if(empty($checkfrontpage))
            {


                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();
                $model = new CDCFrontpage();
                $model->degree_type=$_POST['CDCFrontpage']['degree_type'];
                $model->coe_regulation_id=$_POST['CDCFrontpage']['coe_regulation_id'];
                $model->coe_dept_id=$_POST['coe_dept_id'];
                $model->mission_count=$_POST['CDCFrontpage']['mission_count'];
                $model->po_count=$_POST['CDCFrontpage']['po_count'];
                $model->peo_count=$_POST['CDCFrontpage']['peo_count'];
                $model->pso_count=$_POST['CDCFrontpage']['pso_count'];
                $model->created_at=$created_at;
                $model->created_by=$userid;

                $cnts[]=$_POST['CDCFrontpage']['mission_count'];
                $cnts[]=$_POST['CDCFrontpage']['peo_count'];
                $cnts[]=$_POST['CDCFrontpage']['pso_count'];
                $cnts[]=$_POST['CDCFrontpage']['po_count'];
                $cnts = implode(",", $cnts);
                // echo "<pre>";
                // print_r($model); exit;
                if($model->save(false))
                {
                    return $this->redirect(['cdcfrontpagelist', 'degree_type' =>$_POST['CDCFrontpage']['degree_type'],'coe_dept_id'=>$_POST['coe_dept_id'],'coe_regulation_id'=>$_POST['CDCFrontpage']['coe_regulation_id'],'cnts'=>$cnts,'id'=>$model->cur_fp_id]);
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

    public function actionCdcfrontpagelist($degree_type,$coe_dept_id,$coe_regulation_id,$cnts,$id)
    {
        $model = new CDCFrontpage();

        $poscount=0;
        if($degree_type=='UG')
        {
            $po_count = Yii::$app->db->createCommand("SELECT po_count FROM cur_frontp_clg A WHERE A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."'")->queryScalar();

            $cntss = explode(",", $cnts);

            $poscount=count($cntss)-1;
        }
        else
        {
            //print_r($cnts); exit();
            $cntss = explode(",", $cnts);
            $po_count =  $cntss[3];

            $poscount=count($cntss);
        }

        if(Yii::$app->request->post()) 
        {
                //print_r($cntss[2]); exit();
            $labelss = array('0' =>'mission' , '1' =>'peo' ,'2' =>'pso','3' =>'po');
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();

            $success=0;
            for ($i=0; $i <$poscount ; $i++) 
            {
                $array=$labelss[$i];
                if($cntss[2]==0 && $array=='pso' && ($degree_type=='PG' || $degree_type=='MBA'))
                {

                }
                else
                {
                   
                    $datas=$_POST[$array];
                    if($i==3)
                    {   
                        $arrayt=$labelss[$i].'title';
                        $datast=$_POST[$arrayt];
                    }
                    //print_r($datas); exit;
                    for ($j=0; $j <count($datas); $j++) 
                    {
                        $model = new CDCFrontpageList();
                        $model->degree_type=$degree_type;
                        $model->coe_regulation_id=$coe_regulation_id;
                        $model->coe_dept_id=$coe_dept_id;
                        $model->cur_fp_id=$id;
                        $model->vision=$_POST['vision'];
                        if($i==0)
                        {
                            $model->mission=$datas[$j];
                            $model->pso='-';
                            $model->peo='-';
                            $model->po='-';
                            $model->po_title='-';
                        }
                        else if($i==1)
                        {
                            $model->mission='-';
                            $model->pso='-';
                            $model->po='-';
                            $model->po_title='-';
                            $model->peo=$datas[$j];
                        }
                        else if($i==2)
                        {   
                            $model->mission='-';
                            $model->peo='-';
                            $model->po='-';
                            $model->po_title='-';
                            $model->pso=$datas[$j];
                        }
                        else if($i==3)
                        {   
                            $model->mission='-';
                            $model->peo='-';
                            $model->pso='-';
                            $model->po=$datas[$j];
                            $model->po_title=$datast[$j];
                            
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

            $peo_count = Yii::$app->db->createCommand("SELECT cur_fpl_id FROM cur_frontpage_list A WHERE A.peo!='-' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND coe_dept_id=".$coe_dept_id." AND cur_fp_id=".$id)->queryAll();
            //print_r($cntss[1]); exit();
            $k=1;
            foreach ($peo_count as $key => $value) 
            { 
                if($k<=$cntss[1])
                {
                    $peos='PEO_PO'.$k;
                    $peopodata=$_POST[$peos];
                    //print_r($peo_count); exit;
                    for ($j=0; $j <count($peopodata) ; $j++) 
                    {                     
                        $models = new FrontPeoPoMapping();
                        $models->degree_type=$degree_type;
                        $models->coe_regulation_id=$coe_regulation_id;
                        $models->coe_dept_id=$coe_dept_id;
                        $models->cur_fp_id=$id;
                        $models->cur_fpl_id=$value['cur_fpl_id'];
                        $models->po_tick=$peopodata[$j];
                        $models->created_at=$created_at;
                        $models->created_by=$userid;
                        if($models->save(false))
                        {
                            $success++;
                        }
                    }

                    $k++;
                }
            }
            //echo "end"; exit;
            if($success>0)
            {
               Yii::$app->ShowFlashMessages->setMsg('Success', "Data successfully inserted..");
                    return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Data Not Insert, Please Check");
                return $this->redirect(['cdcfrontpagelist', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'cnts'=>$cnts,'id'=>$id]);
            }

        }
        else
        {
            return $this->render('cdcfrontpage_list', [
                'model' => $model,
                'cnts' => $cnts,
                'po_count'=>$po_count,
                'degree_type'=>$degree_type
            ]);
        }

    }

    public function actionCopy()
    {
        $model = new CDCFrontpage();
        if(Yii::$app->request->post()) 
        {
           
            $checkfrontpage = Yii::$app->db->createCommand("SELECT * FROM cur_frontpage A WHERE A.coe_dept_id='".$_POST['coe_dept_id']."' AND A.coe_regulation_id=".$_POST['from_regulation_id']." AND A.degree_type='".$_POST['degree_type']."'")->queryOne();

            if($_POST['degree_type']=='UG')
            {
            
                $po_count = Yii::$app->db->createCommand("SELECT po_count FROM cur_frontp_clg A WHERE A.coe_regulation_id=".$_POST['from_regulation_id']." AND A.degree_type='".$_POST['degree_type']."'")->queryScalar();
            }
            else
            {
                $po_count = $checkfrontpage['po_count'];
            }

            if(!empty($checkfrontpage) && !empty($po_count))
            {
                $checkfrontpage1 = Yii::$app->db->createCommand("SELECT * FROM cur_frontpage A WHERE A.coe_dept_id='".$_POST['coe_dept_id']."' AND A.coe_regulation_id=".$_POST['to_regulation_id']." AND A.degree_type='".$_POST['degree_type']."'")->queryOne();

                if(empty($checkfrontpage1))
                {
                    $created_at = date("Y-m-d H:i:s");
                    $userid = Yii::$app->user->getId();
                    $model = new CDCFrontpage();
                    $model->degree_type=$checkfrontpage['degree_type'];
                    $model->coe_regulation_id=$_POST['to_regulation_id'];
                    $model->coe_dept_id=$checkfrontpage['coe_dept_id'];
                    $model->mission_count=$checkfrontpage['mission_count'];
                    $model->peo_count=$checkfrontpage['peo_count'];
                    $model->pso_count=$checkfrontpage['pso_count'];
                    $model->po_count=$checkfrontpage['po_count'];
                    $model->created_at=$created_at;
                    $model->created_by=$userid;

                    if($model->save(false))
                    {
                        $created_at = date("Y-m-d H:i:s");
                        $userid = Yii::$app->user->getId();
                        $cur_fp_id=$model->cur_fp_id;
                        $success=0;
                       
                        $datas = Yii::$app->db->createCommand("SELECT * FROM cur_frontpage_list A WHERE A.cur_fp_id='".$checkfrontpage['cur_fp_id']."'")->queryAll();

                        foreach($datas as $value)
                        {
                            $model1 = new CDCFrontpageList();
                            $model1->degree_type=$value['degree_type'];
                            $model1->coe_regulation_id=$_POST['to_regulation_id'];
                            $model1->coe_dept_id=$value['coe_dept_id'];
                            $model1->cur_fp_id=$cur_fp_id;
                            $model1->vision=$value['vision'];
                            $model1->mission=$value['mission'];
                            $model1->peo=$value['peo'];
                            $model1->pso=$value['pso'];
                            $model1->po=$value['po'];
                            $model1->po_title=$value['po_title'];
                            $model1->created_at=$created_at;
                            $model1->created_by=$userid;
                            if($model1->save(false))
                            {
                                $success++;
                            }
                        }
                        

                        $peo_count = Yii::$app->db->createCommand("SELECT cur_fpl_id FROM cur_frontpage_list A WHERE A.peo!='-' AND A.coe_regulation_id=".$_POST['to_regulation_id']." AND A.degree_type='".$checkfrontpage['degree_type']."' AND coe_dept_id=".$checkfrontpage['coe_dept_id']." AND cur_fp_id=".$cur_fp_id)->queryAll();

                        //print_r($peo_count); exit;

                        $peodata=array();
                        foreach ($peo_count as $key => $values) 
                        {                             
                            $peodata[]=$values['cur_fpl_id'];
                        }

                        $peo_datas = Yii::$app->db->createCommand("SELECT * FROM cur_front_peo_po_mapping A WHERE A.coe_regulation_id=".$checkfrontpage['coe_regulation_id']." AND A.degree_type='".$checkfrontpage['degree_type']."' AND coe_dept_id=".$checkfrontpage['coe_dept_id']." AND cur_fp_id=".$checkfrontpage['cur_fp_id'])->queryAll();
                        
                        $tempfpl=0; $p=0;  $cur_fpl_id=0;
                        foreach ($peo_datas as $datvalue)
                        {        
                            if($tempfpl!=$datvalue['cur_fpl_id'])
                            {
                                $cur_fpl_id=$peodata[$p];

                                $p++;
                            } 

                            $models = new FrontPeoPoMapping();
                            $models->degree_type=$datvalue['degree_type'];
                            $models->coe_regulation_id=$_POST['to_regulation_id'];
                            $models->coe_dept_id=$datvalue['coe_dept_id'];
                            $models->cur_fp_id=$cur_fp_id;
                            $models->cur_fpl_id=$cur_fpl_id;
                            $models->po_tick=$datvalue['po_tick'];
                            $models->created_at=$created_at;
                            $models->created_by=$userid;
                            if($models->save(false))
                            {
                                $success++;
                            }

                            $tempfpl=$datvalue['cur_fpl_id'];
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
                Yii::$app->ShowFlashMessages->setMsg('Error', "Data Not Found for Mapping or PO's Not created for choosen regulation , Please Check");
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
     * Updates an existing CDCFrontpage model.
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
     * Deletes an existing CDCFrontpage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletedata($id)
    {
        Yii::$app->db->createCommand('DELETE FROM cur_front_peo_po_mapping WHERE cur_fp_id="'.$id.'"')->execute();
        Yii::$app->db->createCommand('DELETE FROM cur_frontpage_list WHERE cur_fp_id="'.$id.'"')->execute();
        Yii::$app->db->createCommand('DELETE FROM cur_frontpage WHERE cur_fp_id="'.$id.'"')->execute();
        Yii::$app->ShowFlashMessages->setMsg('Success', "Deleted Successfully");
        return $this->redirect(['index']);
    }

    /**
     * Finds the CDCFrontpage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CDCFrontpage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CDCFrontpage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
