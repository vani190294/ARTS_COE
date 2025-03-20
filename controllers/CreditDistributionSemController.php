<?php

namespace app\controllers;

use Yii;
use app\models\CreditDistributionSem;
use app\models\CreditDistributionSemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\AicteNorms;
/**
 * CreditDistributionSemController implements the CRUD actions for CreditDistributionSem model.
 */
class CreditDistributionSemController extends Controller
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
     * Lists all CreditDistributionSem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CreditDistributionSemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new CreditDistributionSem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CreditDistributionSem();

        if(Yii::$app->request->post()) 
        {
            //echo $_POST['CreditDistributionSem']['degree_type']; exit;
            $AicteNorms=AicteNorms::Find()->where(['degree_type'=>$_POST['CreditDistributionSem']['degree_type'],'coe_regulation_id'=>$_POST['CreditDistributionSem']['coe_regulation_id']])->all();
            if(!empty($AicteNorms))
            {
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();
                $successful=0; 
                foreach ($AicteNorms as $value) 
                {
                    //echo $_POST['CreditDistributionSem']['degree_type']; 
                    $streamcheck=CreditDistributionSem::Find()->where(['degree_type'=>$_POST['CreditDistributionSem']['degree_type'],'coe_dept_id'=>$_POST['coe_dept_id'],'coe_regulation_id'=>$_POST['CreditDistributionSem']['coe_regulation_id'],'cur_stream_id'=>$value['cur_an_id']])->all();
                    
                    $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['CreditDistributionSem']['coe_regulation_id'])->queryScalar();

                    if(empty($streamcheck))
                    {
                        $model1 = new CreditDistributionSem();
                        $model1->coe_batch_id=$coe_batch_id;
                        $model1->degree_type=$_POST['CreditDistributionSem']['degree_type'];
                        $model1->coe_regulation_id=$_POST['CreditDistributionSem']['coe_regulation_id']; 
                        $model1->coe_dept_id=$_POST['coe_dept_id'];
                        $model1->cur_stream_id=$value['cur_an_id'];
                        $model1->created_at=$created_at;
                        $model1->created_by=$userid;

                        if($model1->save(false))
                        {
                            $successful++;
                        }
                    }
                }
                //echo $successful; exit;
                if($successful==count($AicteNorms))
                {
                    return $this->redirect(['credit-form', 'degree_type' =>$_POST['CreditDistributionSem']['degree_type'],'coe_dept_id'=>$_POST['coe_dept_id'],'coe_regulation_id'=>$_POST['CreditDistributionSem']['coe_regulation_id']]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Stream Creation Error! Please Check");
                    return $this->redirect(['create']);
                }
                
            } 
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Choosen Dept. AICTE Norms not found! Please Check");
                return $this->redirect(['create']);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreditForm($degree_type,$coe_dept_id,$coe_regulation_id)
    {
        $streamdata=Yii::$app->db->createCommand("SELECT A.*,B.stream_name FROM cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id." ORDER BY A.cur_stream_id ASC")->queryAll();
        
        $deptdata = Yii::$app->db->createCommand("SELECT C.regulation_year,A.degree_type,B.dept_code FROM cur_credit_distribution_sem A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id JOIN coe_regulation C ON C.coe_regulation_id=A.coe_regulation_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id." ORDER BY A.cur_stream_id ASC")->queryone();

        $regulation_year = Yii::$app->db->createCommand("SELECT concat('Regulation: ',A.regulation_year,' (Batch: ',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE coe_regulation_id =".$coe_regulation_id)->queryScalar();

        if (Yii::$app->request->post()) 
        {

            $nocredit=$_POST['stream_credit'];
            $aicte_norms=$_POST['aicte_norms'];

            if($degree_type=='UG')
            {
                if($deptdata['regulation_year']>=2023)
                {
                   // echo "if".$deptdata['regulation_year']; exit;

                    if(array_sum($nocredit)>=160 && array_sum($nocredit)<=168)
                    {
                        $creditid=$_POST['cur_dist_id'];
                        $sl=1;
                        for ($i=0; $i <count($creditid) ; $i++) 
                        { 
                           
                            if($degree_type=='UG')
                            {
                                for ($s=1; $s <=8 ; $s++) 
                                { 
                                    $name='sem'.$sl.$s;
                                    $columnname='sem'.$s;
                                    $semdata=$_POST[$name];
                                    Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET ".$columnname."='" . $semdata . "' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                                }
                            }

                             Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET total_credit='" . $nocredit[$i] . "', aicte_norms='".$aicte_norms[$i]."' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                            $sl++;
                        }
                       //exit();

                         return $this->redirect(['viewdetails', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',"Total Credit Point Maximum 168! please check");
                        return $this->redirect(['credit-form', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                    }
                }
                else
                { //echo "else"; exit;
                    if(array_sum($nocredit)==165)
                    {
                        $creditid=$_POST['cur_dist_id'];
                        $sl=1;
                        for ($i=0; $i <count($creditid) ; $i++) 
                        { 
                           
                            if($degree_type=='UG')
                            {
                                for ($s=1; $s <=8 ; $s++) 
                                { 
                                    $name='sem'.$sl.$s;
                                    $columnname='sem'.$s;
                                    $semdata=$_POST[$name];
                                    Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET ".$columnname."='" . $semdata . "' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                                }
                            }

                             Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET total_credit='" . $nocredit[$i] . "', aicte_norms='".$aicte_norms[$i]."' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                            $sl++;
                        }
                       //exit();

                         return $this->redirect(['viewdetails', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error', "Total Credit Point Less then 165! please check");
                        return $this->redirect(['credit-form', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                    }
                }
            }
            else if($degree_type=='MBA')
            {

                if(array_sum($nocredit)>=95 && array_sum($nocredit)<=110)
                {
                    $creditid=$_POST['cur_dist_id'];
                    $sl=1;
                    for ($i=0; $i <count($creditid) ; $i++) 
                    { 
                       
                        for ($s=1; $s <=4 ; $s++) 
                        { 
                            $name='sem'.$sl.$s;
                            $columnname='sem'.$s;
                            $semdata=$_POST[$name];
                            Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET ".$columnname."='" . $semdata . "' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                        }
                        
                         Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET total_credit='" . $nocredit[$i] . "', aicte_norms='".$aicte_norms[$i]."' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                        $sl++;
                    }
                   //exit();

                     return $this->redirect(['viewdetails', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',"Total Credit Point Maximum 102! please check");
                    return $this->redirect(['credit-form', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                }
                
            }
            else if($degree_type=='PG')
            {

                if(array_sum($nocredit)>=68 && array_sum($nocredit)<=70)
                {
                    $creditid=$_POST['cur_dist_id'];
                    $sl=1;
                    for ($i=0; $i <count($creditid) ; $i++) 
                    { 
                       
                        for ($s=1; $s <=4 ; $s++) 
                        { 
                            $name='sem'.$sl.$s;
                            $columnname='sem'.$s;
                            $semdata=$_POST[$name];
                            Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET ".$columnname."='" . $semdata . "' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                        }
                        
                         Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET total_credit='" . $nocredit[$i] . "', aicte_norms='".$aicte_norms[$i]."' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                        $sl++;
                    }
                   //exit();

                     return $this->redirect(['viewdetails', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',"Total Credit Point Maximum 102! please check");
                    return $this->redirect(['credit-form', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id]);
                }
                
            }
        } else {
            return $this->render('credit_form', [
                'streamdata' => $streamdata,
                'deptdata'=>$deptdata,
                'regulation_year'=>$regulation_year
            ]);
        }
    }

    public function actionViewdetails($degree_type,$coe_dept_id,$coe_regulation_id)
    {
        $streamdata=Yii::$app->db->createCommand("SELECT A.*,B.stream_name,A.aicte_norms FROM cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id." ORDER BY cur_an_id ASC ")->queryAll();

        $deptdata = Yii::$app->db->createCommand("SELECT C.regulation_year,A.degree_type,B.dept_code FROM cur_credit_distribution_sem A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id JOIN coe_regulation C ON C.coe_regulation_id=A.coe_regulation_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id)->queryone();

        $regulation_year = Yii::$app->db->createCommand("SELECT concat('Regulation: ',A.regulation_year,' (Batch: ',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE coe_regulation_id =".$coe_regulation_id)->queryScalar();

        return $this->render('view', [
            'streamdata' => $streamdata,
            'deptdata'=>$deptdata,
            'regulation_year'=>$regulation_year
        ]);
    }

    /**
     * Updates an existing CreditDistributionSem model.
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

        $streamdata=Yii::$app->db->createCommand("SELECT A.*,B.stream_name,A.aicte_norms FROM cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id."  ORDER BY cur_an_id ASC")->queryAll();

        $deptdata = Yii::$app->db->createCommand("SELECT C.regulation_year,A.degree_type,B.dept_code FROM cur_credit_distribution_sem A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id JOIN coe_regulation C ON C.coe_regulation_id=A.coe_regulation_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id)->queryone();

        $regulation_year = Yii::$app->db->createCommand("SELECT concat('Regulation: ',A.regulation_year,' (Batch: ',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE coe_regulation_id =".$coe_regulation_id)->queryScalar();

        if (Yii::$app->request->post()) 
        {
            $nocredit=$_POST['stream_credit'];

            if($degree_type=='UG')
            {
                if($deptdata['regulation_year']>=2023)
                { //echo "if"; exit;
                    if(array_sum($nocredit)>=160 && array_sum($nocredit)<=168)
                    {
                        $creditid=$_POST['cur_dist_id'];
                        $aicte_norms=$_POST['aicte_norms'];

                        $sl=1;
                        
                        for ($i=0; $i <count($creditid) ; $i++) 
                        { 
                           
                            if($degree_type=='UG')
                            {
                                for ($s=1; $s <=8 ; $s++) 
                                { 
                                    $name='sem'.$sl.$s;
                                    $columnname='sem'.$s;
                                    $semdata=$_POST[$name];
                                    Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET ".$columnname."='" . $semdata . "' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                                }
                            }

                             Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET total_credit='" . $nocredit[$i] . "', aicte_norms='".$aicte_norms[$i]."' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                            $sl++;
                        }

                        return $this->redirect(['viewdetails', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error', "Total Credit Point Maximum 168 Minimum 160! please check");
                        return $this->redirect(['index']);
                    } 
                }
                else
                {
                    //echo "else"; exit;
                    if(array_sum($nocredit)==165)
                    {
                        $creditid=$_POST['cur_dist_id'];
                        $aicte_norms=$_POST['aicte_norms'];

                        $sl=1;
                        
                        for ($i=0; $i <count($creditid) ; $i++) 
                        { 
                           
                            if($degree_type=='UG')
                            {
                                for ($s=1; $s <=8 ; $s++) 
                                { 
                                    $name='sem'.$sl.$s;
                                    $columnname='sem'.$s;
                                    $semdata=$_POST[$name];
                                    Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET ".$columnname."='" . $semdata . "' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                                }
                            }

                             Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET total_credit='" . $nocredit[$i] . "', aicte_norms='".$aicte_norms[$i]."' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                            $sl++;
                        }

                        return $this->redirect(['viewdetails', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error', "Total Credit Point Less then 165! please check");
                        return $this->redirect(['index']);
                    } 
                }
            }
            else if($degree_type=='MBA')
            {
               
                if(array_sum($nocredit)==102 && array_sum($nocredit)==102)
                {
                    $creditid=$_POST['cur_dist_id'];
                    $aicte_norms=$_POST['aicte_norms'];

                    $sl=1;
                    
                    for ($i=0; $i <count($creditid) ; $i++) 
                    { 
                       
                        for ($s=1; $s <=4 ; $s++) 
                        { 
                            $name='sem'.$sl.$s;
                            $columnname='sem'.$s;
                            $semdata=$_POST[$name];
                            Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET ".$columnname."='" . $semdata . "' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                        }
                        

                         Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET total_credit='" . $nocredit[$i] . "', aicte_norms='".$aicte_norms[$i]."' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                        $sl++;
                    }

                    return $this->redirect(['viewdetails', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Total Credit Point Maximum 102! please check");
                    return $this->redirect(['index']);
                } 
               
            }
            else if($degree_type=='PG')
            {
               
                if(array_sum($nocredit)>=68 && array_sum($nocredit)<=70)
                {
                    $creditid=$_POST['cur_dist_id'];
                    $aicte_norms=$_POST['aicte_norms'];

                    $sl=1;
                    
                    for ($i=0; $i <count($creditid) ; $i++) 
                    { 
                       
                        for ($s=1; $s <=4 ; $s++) 
                        { 
                            $name='sem'.$sl.$s;
                            $columnname='sem'.$s;
                            $semdata=$_POST[$name];
                            Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET ".$columnname."='" . $semdata . "' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                        }
                        

                         Yii::$app->db->createCommand("UPDATE cur_credit_distribution_sem SET total_credit='" . $nocredit[$i] . "', aicte_norms='".$aicte_norms[$i]."' WHERE cur_dist_id='" . $creditid[$i] . "'")->execute();
                        $sl++;
                    }

                    return $this->redirect(['viewdetails', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'regulation_year'=>$regulation_year]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Total Credit Point Maximum 102! please check");
                    return $this->redirect(['index']);
                } 
               
            }
           
        } else {
            return $this->render('credit_form_update', [
                'streamdata' => $streamdata,
                'deptdata'=>$deptdata,
                'regulation_year'=>$regulation_year
            ]);
        }
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        $degree_type=$model->degree_type;
        $coe_dept_id=$model->coe_dept_id;
        $coe_regulation_id=$model->coe_regulation_id;

        $streamdata=Yii::$app->db->createCommand("SELECT A.*,B.stream_name,A.aicte_norms FROM cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id." ORDER BY cur_an_id ASC")->queryAll();

        $deptdata = Yii::$app->db->createCommand("SELECT C.regulation_year,A.degree_type,B.dept_code FROM cur_credit_distribution_sem A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id JOIN coe_regulation C ON C.coe_regulation_id=A.coe_regulation_id WHERE A.degree_type='".$degree_type."' AND A.coe_dept_id=".$coe_dept_id." AND A.coe_regulation_id=".$coe_regulation_id)->queryone();

         $regulation_year = Yii::$app->db->createCommand("SELECT concat('Regulation: ',A.regulation_year,' (Batch: ',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE coe_regulation_id =".$coe_regulation_id)->queryScalar();
       
            return $this->render('view', [
                'streamdata' => $streamdata,
                'deptdata'=>$deptdata,
                'regulation_year'=>$regulation_year
            ]);
        
    }

    /**
     * Deletes an existing CreditDistributionSem model.
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
     * Finds the CreditDistributionSem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CreditDistributionSem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CreditDistributionSem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
