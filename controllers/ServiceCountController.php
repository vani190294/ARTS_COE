<?php

namespace app\controllers;

use Yii;
use app\models\ServiceCount;
use app\models\ServiceCountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ServiceCountDetails;
/**
 * ServiceCountController implements the CRUD actions for ServiceCount model.
 */
class ServiceCountController extends Controller
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
     * Lists all ServiceCount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServiceCountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServiceCount model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $degree_type=$model->degree_type;
        $coe_dept_id=$model->coe_dept_id;
        $coe_regulation_id=$model->coe_regulation_id;

        $streamdata=Yii::$app->db->createCommand("SELECT A.*,B.stream_name,A.aicte_norms FROM cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id." AND B.stream_name IN ('HSMC','BSC','ESC','PCC','PEC','EEC','IKS') ORDER BY cur_an_id DESC")->queryAll();

        $deptdata = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, A.coe_dept_id, C.regulation_year, A.degree_type, B.dept_code FROM cur_credit_distribution_sem A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id JOIN coe_regulation C ON C.coe_regulation_id=A.coe_regulation_id WHERE A.degree_type='".$degree_type."' AND (A.coe_dept_id!=".$coe_dept_id.") AND A.coe_regulation_id=".$coe_regulation_id." GROUP BY A.coe_dept_id ORDER BY A.coe_dept_id ASC")->queryAll();
       
       $fromdept = Yii::$app->db->createCommand("SELECT dept_code FROM cur_department WHERE coe_dept_id=".$coe_dept_id)->queryScalar();
       
       $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id, degree_type, dept_code FROM cur_department WHERE degree_type='".$degree_type."' AND (coe_dept_id!=".$coe_dept_id.") ORDER BY coe_dept_id ASC")->queryAll();
       

            return $this->render('view', [
                'streamdata' => $streamdata,
                'deptdata'=>$deptdata,
                'fromdept'=>$fromdept,                
                'coe_dept_id'=>$coe_dept_id,
                'deptall'=>$deptall
            ]);
    }

    /**
     * Creates a new ServiceCount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ServiceCount();

        if (Yii::$app->request->post()) 
        {
            $streamcheck=ServiceCount::Find()->where(['degree_type'=>$_POST['ServiceCount']['degree_type'],'coe_dept_id'=>$_POST['coe_dept_id'],'coe_regulation_id'=>$_POST['ServiceCount']['coe_regulation_id']])->all();

            if(empty($streamcheck))
            {
                $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['ServiceCount']['coe_regulation_id'])->queryScalar();

                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();

                $model1 = new ServiceCount();
                $model1->degree_type=$_POST['ServiceCount']['degree_type'];
                $model1->coe_batch_id=$coe_batch_id;
                $model1->coe_regulation_id=$_POST['ServiceCount']['coe_regulation_id']; 
                $model1->coe_dept_id=$_POST['coe_dept_id'];
                $model1->created_at=$created_at;
                $model1->created_by=$userid;
                
                if($model1->save(false))
                {
                    return $this->redirect(['service-count-form', 'degree_type' =>$_POST['ServiceCount']['degree_type'],'coe_dept_id'=>$_POST['coe_dept_id'],'coe_regulation_id'=>$_POST['ServiceCount']['coe_regulation_id'],'cur_sc_id'=>$model1->cur_sc_id]);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Service Count Already Added or Something Error! Please Check");
                return $this->redirect(['create']);
             
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionServiceCountForm($degree_type,$coe_dept_id,$coe_regulation_id,$cur_sc_id)
    {
        $streamdata=Yii::$app->db->createCommand("SELECT A.*,B.stream_name FROM cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id." AND B.stream_name IN ('HSMC','BSC','ESC','PCC','PEC','EEC','IKS') ORDER BY A.cur_stream_id DESC")->queryAll();

        $deptdata = Yii::$app->db->createCommand("SELECT A.coe_regulation_id,A.coe_dept_id,C.regulation_year,A.degree_type,B.dept_code FROM cur_credit_distribution_sem A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id JOIN coe_regulation C ON C.coe_regulation_id=A.coe_regulation_id WHERE A.degree_type='".$degree_type."' AND (A.coe_dept_id!=".$coe_dept_id.") AND A.coe_regulation_id=".$coe_regulation_id." GROUP BY A.coe_dept_id ORDER BY A.coe_dept_id ASC")->queryAll();

        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id, degree_type, dept_code FROM cur_department WHERE degree_type='".$degree_type."' AND (coe_dept_id!=".$coe_dept_id.") ORDER BY coe_dept_id ASC")->queryAll();
       // print_r($deptall); exit;

        $fromdept = Yii::$app->db->createCommand("SELECT dept_code FROM cur_department WHERE coe_dept_id=".$coe_dept_id)->queryScalar();

        $model = new ServiceCount();
        $model1 = new ServiceCountDetails();
       
        if (Yii::$app->request->post()) 
        {
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();
            $successful=0; 

            $coe_dept_ids=$_POST['coe_dept_ids'];

            foreach ($deptall as $value) 
            {
                foreach ($streamdata as $value1)                   
                { 
                    $sname='stream'.$value['coe_dept_id'].'name'; 
                    $streamname=$_POST[$sname];
                     
                    $svalue='stream'.$value['coe_dept_id'].'value';
                    $streamvalue=$_POST[$svalue];
                   //print_r($streamvalue); exit;
                    $streamcheck=ServiceCountDetails::Find()->where(['degree_type'=>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'to_dept_id'=>$value['coe_dept_id']])->all();

                    if(empty($streamcheck))
                    {
                        $streamname=implode(",", $streamname);
                        $streamvalue=implode(",", $streamvalue);

                        $model1 = new ServiceCountDetails();
                        $model1->cur_sc_id=$cur_sc_id;
                        $model1->degree_type=$degree_type;
                        $model1->coe_regulation_id=$coe_regulation_id; 
                        $model1->coe_dept_id=$coe_dept_id;
                        $model1->to_dept_id=$value['coe_dept_id'];
                        $model1->service_type=$streamname;
                        $model1->service_count=$streamvalue;
                        $model1->created_at=$created_at;
                        $model1->created_by=$userid;
                        
                        if($model1->save(false))
                        {
                            $successful++;
                        }
                    }

                }
            }

            if($successful>0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Service Count Added Successfully");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Service Count Already Added or Something Error! Please Check");
                return $this->redirect(['index']);
            }

        } 
        else 
        {
            return $this->render('service-count-form', [
                'streamdata' => $streamdata,
                'deptdata'=>$deptdata,
                'fromdept'=>$fromdept,
                'model'=>$model,
                'model1'=>$model1,
                'deptall'=>$deptall
            ]);
        }


    }

    /**
     * Updates an existing ServiceCount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $degree_type=$model->degree_type;
        $coe_dept_id=$model->coe_dept_id;
        $coe_regulation_id=$model->coe_regulation_id;

        $streamdata=Yii::$app->db->createCommand("SELECT A.*,B.stream_name,A.aicte_norms FROM cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id." AND B.stream_name IN ('HSMC','BSC','ESC','PCC','PEC','EEC','IKS') ORDER BY cur_an_id DESC")->queryAll();

        $deptdata = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, A.coe_dept_id, C.regulation_year, A.degree_type, B.dept_code FROM cur_credit_distribution_sem A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id JOIN coe_regulation C ON C.coe_regulation_id=A.coe_regulation_id WHERE A.degree_type='".$degree_type."' AND (A.coe_dept_id!=".$coe_dept_id.") AND A.coe_regulation_id=".$coe_regulation_id." GROUP BY A.coe_dept_id ORDER BY A.coe_dept_id ASC")->queryAll();
       
       $fromdept = Yii::$app->db->createCommand("SELECT dept_code FROM cur_department WHERE coe_dept_id=".$coe_dept_id)->queryScalar();

        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id, degree_type, dept_code FROM cur_department WHERE degree_type='".$degree_type."' AND (coe_dept_id!=".$coe_dept_id.") ORDER BY coe_dept_id ASC")->queryAll();
        //print_r($deptall); exit;
       
         $model = new ServiceCount();

        if (Yii::$app->request->post()) 
        {
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();
            $successful=0; 

            $coe_dept_ids=$_POST['coe_dept_ids'];

            foreach ($deptall as $value) 
            {
                foreach ($streamdata as $value1)                   
                { 
                    $svalue='stream'.$value['coe_dept_id'].'value';
                    $streamvalue=$_POST[$svalue];

                    //print_r($streamvalue); exit();
                    //echo "<pre>";
                    $streamcheck=ServiceCountDetails::Find()->where(['degree_type'=>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'to_dept_id'=>$value['coe_dept_id']])->One();

                    //print_r($streamcheck); exit();

                    if(!empty($streamcheck))
                    {
                       $streamvalue=implode(",", $streamvalue);
                       //echo "UPDATE cur_service_count_details SET service_count='" . $streamvalue . "' WHERE cur_scd_id='" . $streamcheck['cur_scd_id'] . "'"; exit;
                       $updated =Yii::$app->db->createCommand("UPDATE cur_service_count_details SET service_count='" . $streamvalue . "' WHERE cur_scd_id='" . $streamcheck['cur_scd_id'] . "'")->execute();
                        
                        if($updated)
                        {
                            $successful++;
                        }
                    }

                }
            }

            if($successful>0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Service Count Updated Successfully");
                return $this->redirect(['view', 'id' => $id]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Something Error for Update! Please Check");
                return $this->redirect(['index']);
            }
            
        } 
        else 
        {
            return $this->render('service-count-updateform', [
                'streamdata' => $streamdata,
                'deptdata'=>$deptdata,
                'fromdept'=>$fromdept,
                'model'=>$model,
                'coe_dept_id'=>$coe_dept_id,
                'deptall'=>$deptall
            ]);
        }
    }


    public function actionApproveStatus()
    {
        $model = new ServiceCount();

        if (Yii::$app->request->post()) 
        {
            $streamcheck=ServiceCountDetails::Find()->where(['degree_type'=>$_POST['ServiceCount']['degree_type'],'to_dept_id'=>$_POST['coe_dept_id'],'coe_regulation_id'=>$_POST['ServiceCount']['coe_regulation_id']])->one();

            if(!empty($streamcheck))
            {
                 return $this->redirect(['approve','degree_type'=>$_POST['ServiceCount']['degree_type'],'to_dept_id'=>$_POST['coe_dept_id'],'coe_regulation_id'=>$_POST['ServiceCount']['coe_regulation_id'],'cur_sc_id'=>$streamcheck['cur_sc_id']]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "No Data Found! Please Check");
                return $this->redirect(['approve-status']);
             
            }

        } else {
            return $this->render('approve-status', [
                'model' => $model,
            ]);
        }
    }

    public function actionApprove($degree_type,$to_dept_id,$coe_regulation_id,$cur_sc_id)
    {
        //$model = $this->findModel($cur_sc_id);

        $streamdata=Yii::$app->db->createCommand("SELECT A.*,B.stream_name,A.aicte_norms FROM cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$to_dept_id." AND A.coe_regulation_id=".$coe_regulation_id." AND B.stream_name IN ('ESC','PCC','PEC','EEC','IKS') ORDER BY cur_an_id DESC")->queryAll();

        //echo $to_dept_id; exit;
        $streamdatash=Yii::$app->db->createCommand("SELECT A.*,B.stream_name,A.aicte_norms FROM cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=9 AND A.coe_regulation_id=".$coe_regulation_id." AND B.stream_name IN ('HSMC','BSC') ORDER BY cur_an_id ASC")->queryAll();

        $deptdata = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, A.coe_dept_id, C.regulation_year, A.degree_type, B.dept_code FROM cur_credit_distribution_sem A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id JOIN coe_regulation C ON C.coe_regulation_id=A.coe_regulation_id WHERE A.degree_type='".$degree_type."' AND (A.coe_dept_id!=".$to_dept_id.") AND A.coe_regulation_id=".$coe_regulation_id." GROUP BY A.coe_dept_id ORDER BY A.coe_dept_id ASC")->queryAll();
       
       $fromdept = Yii::$app->db->createCommand("SELECT dept_code FROM cur_department WHERE coe_dept_id=".$to_dept_id)->queryScalar();
       
         $model = new ServiceCount();

        if (Yii::$app->request->post()) 
        {
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();
            $successful=0; 

            $coe_dept_ids=$_POST['coe_dept_ids'];

            $approved=$_POST['approved'];

            for ($i=0; $i <count($approved) ; $i++) 
            { 
                $updated =Yii::$app->db->createCommand("UPDATE cur_service_count_details SET approve_status='1' WHERE cur_scd_id='" . $approved[$i] . "'")->execute();
                        
                if($updated)
                {
                    $successful++;
                }
            }

            if($successful>0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Service Count Approved Successfully");
                return $this->redirect(['approve-status']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Something Error for Approved! Please Check");
                return $this->redirect(['index']);
            }
            
        } 
        else 
        {

            return $this->render('service-count-approve', [
                'streamdata' => $streamdata,
                'streamdatash'=>$streamdatash,
                'deptdata'=>$deptdata,
                'fromdept'=>$fromdept,
                'model'=>$model,
                'coe_dept_id'=>$to_dept_id
            ]);
        }
    }

    /**
     * Deletes an existing ServiceCount model.
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
     * Finds the ServiceCount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServiceCount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ServiceCount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
