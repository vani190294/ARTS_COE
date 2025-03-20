<?php

namespace app\controllers;

use Yii;
use app\models\ElectiveRegister;
use app\models\ElectiveRegisterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Regulation;
error_reporting(0);
/**
 * ElectiveRegisterController implements the CRUD actions for ElectiveRegister model.
 */
class ElectiveRegisterController extends Controller
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
     * Lists all ElectiveRegister models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ElectiveRegisterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ElectiveRegister model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
       
        $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_elective_register_subject WHERE cur_elect_id='".$id."'")->queryOne();

        $checksemdata = Yii::$app->db->createCommand("SELECT dept_code,degree_type FROM cur_department WHERE coe_dept_id='".$model->coe_dept_id."'")->queryOne();

        $regulationyear = Yii::$app->db->createCommand("SELECT concat('Regulation: ',A.regulation_year,' (Batch: ',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE coe_regulation_id =".$model->coe_regulation_id)->queryScalar();

        if (Yii::$app->request->post())
        {
            $updated_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId();

            $updated=Yii::$app->db->createCommand('UPDATE cur_elective_register_subject SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_elect_id="' . $id . '"')->execute();

            $updated1= Yii::$app->db->createCommand('UPDATE cur_elective_register SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_elect_id="' . $id . '"')->execute();

            if($updated1)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Approved Successfully!");
                
                $model = $this->findModel($id);
                $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_elective_register_subject WHERE cur_elect_id='".$id."'")->queryOne();

                return $this->render('view', [
                    'model' => $model,
                    'checkelective'=>$checkelective,
                    'checksemdata'=>$checksemdata,
                    'regulationyear'=>$regulationyear
                    ]);
            }
        }
        else
        {
            if(!empty($checkelective))
            {
                return $this->render('view', [
                    'model' => $model,
                    'checkelective'=>$checkelective,
                    'checksemdata'=>$checksemdata,
                    'regulationyear'=>$regulationyear
                    ]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "No Data Found, Please Check");
                return $this->redirect(['index']);
            }
        }

            
    }

    /**
     * Creates a new ElectiveRegister model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ElectiveRegister();

        if (Yii::$app->request->post())
        {
            $degree_type=$_POST['ElectiveRegister']['degree_type'];
            $coe_dept_id=$_POST['coe_dept_id'];
            $coe_regulation_id=$_POST['ElectiveRegister']['coe_regulation_id'];
            $semester=$_POST['ElectiveRegister']['semester'];

            $semm=" AND sem".$semester."!=''";
            $semcolumn="sem".$semester;

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$coe_regulation_id)->queryScalar(); 

            $reg = Regulation::find()->where(['coe_regulation_id'=>$coe_regulation_id])->one();
         
            $programme = Yii::$app->db->createCommand("select programme_shortname,B.coe_dept_id, A.coe_programme_id  from coe_programme  as A  join cur_department as B ON A.programme_shortname=B.dept_code   where coe_dept_id='" . $coe_dept_id . "'")->queryOne();

            $batch_map_id=Yii::$app->db->createCommand("SELECT coe_bat_deg_reg_id FROM coe_bat_deg_reg WHERE coe_programme_id='".$programme['coe_programme_id']."' AND coe_batch_id='".$reg['coe_batch_id']."'")->queryScalar();

            $checksemester=Yii::$app->db->createCommand("SELECT semester FROM coe_subjects_mapping WHERE batch_mapping_id='".$batch_map_id."' ORDER BY coe_subjects_mapping_id DESC")->queryScalar();
            
            if($degree_type=='UG')
            {
                $checksemdata = Yii::$app->db->createCommand("SELECT A.*,stream_name FROM  cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id."  AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND stream_name IN ('PEC','OEC','EEC','MC')".$semm)->queryAll();
            }
            else if($degree_type=='PG')
            {
                $checksemdata = Yii::$app->db->createCommand("SELECT A.*,stream_name FROM  cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id."  AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND stream_name IN ('PEC','OEC','AC','MC')".$semm)->queryAll();
            }
            else
            {
                $checksemdata = Yii::$app->db->createCommand("SELECT A.*,stream_name FROM  cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id."  AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND stream_name IN ('PEC')".$semm)->queryAll();
            }
            

            $checkelective = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_register A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id."  AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.semester=".$semester)->queryOne();
            //print_r($checkelective); exit;
            $model = new ElectiveRegister();
            if(empty($checkelective) && !empty($checksemdata) && $checksemester<=$semester)
            {
                

                foreach ($checksemdata as $value) 
                {
                    $electsemcount = Yii::$app->db->createCommand("SELECT ".$semcolumn." FROM  cur_credit_distribution_sem A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id."  AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND cur_stream_id ='".$value['cur_stream_id']."' AND ".$semcolumn."!=''")->queryScalar();

                    $n = $electsemcount/3;
                    $whole = floor($n);      // 1
                    $fraction = $n - $whole; // .25

                    if($fraction>0)
                    {
                        $n = $electsemcount/4;
                    }

                    if($value['stream_name']=='PEC' && $electsemcount!=0)
                    {
                        $model->pec_paper=$n;
                    }
                    if($value['stream_name']=='OEC' && $electsemcount!=0)
                    {
                        $model->oec_paper=$n;
                    }
                    if($value['stream_name']=='EEC' && $electsemcount!=0)
                    {
                        $model->eec_paper=$n;
                    }
                    if($value['stream_name']=='MC' && $electsemcount==0)
                    {
                        $model->mc_paper=1;
                    }
                    if($value['stream_name']=='AC' && $electsemcount==0)
                    {
                        $model->ac_paper=1;
                    }
                }

                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();
                
                $model->degree_type=$degree_type;
                $model->coe_batch_id=$coe_batch_id;    
                $model->coe_regulation_id=$coe_regulation_id;
                $model->coe_dept_id=$coe_dept_id;
                $model->semester=$semester;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                // echo "<pre>";
                // print_r($model); exit;
                if($model->save(false))
                {
                    return $this->redirect(['elective-register-form', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'semester'=>$semester]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Selected fields Not Save, Please Check");
                    return $this->redirect(['create']);
                }
            }
            else
            {   //echo "string".$semester; exit();
                if($checksemester>$semester)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Choosen Semester ".$semester." Exam Completed, Please Choose Another Semester");
                    return $this->redirect(['create']);
                }
                if(!empty($checkelective))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Already Created, Please Check");
                    return $this->redirect(['create']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "No Data Found or Courses Already migrated, Please Check");
                    return $this->redirect(['create']);
                }
            }
                

            

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionElectiveRegisterForm($degree_type,$coe_dept_id,$coe_regulation_id,$semester)
    {

        $model = new ElectiveRegister();
        if(!empty($semester)) 
        {
            $semm=" AND sem".$semester."!=''";
            $semcolumn="sem".$semester;

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$coe_regulation_id)->queryScalar(); 


             if($degree_type=='UG')
            {
                $checksemdata = Yii::$app->db->createCommand("SELECT A.*,stream_name FROM  cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND stream_name IN ('PEC','OEC','EEC','MC')".$semm)->queryAll();
            }
            else if($degree_type=='PG')
            {
                $checksemdata = Yii::$app->db->createCommand("SELECT A.*,stream_name FROM  cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND stream_name IN ('OEC','PEC','MC','AC')".$semm)->queryAll();
            }
            else if($degree_type=='MBA')
            {
                $checksemdata = Yii::$app->db->createCommand("SELECT A.*,stream_name FROM  cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND stream_name IN ('PEC')".$semm)->queryAll();
            }
            

            $electdata=[];$electcount=[];
            foreach ($checksemdata as $value) 
            {
                $electsemcount = Yii::$app->db->createCommand("SELECT ".$semcolumn." FROM  cur_credit_distribution_sem A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND cur_stream_id ='".$value['cur_stream_id']."' AND ".$semcolumn."!=''")->queryScalar();

                if(($electsemcount!=0) || ($electsemcount==0 && $value['stream_name']=='MC') || ($electsemcount==0 && $value['stream_name']=='AC'))
                {
                    $electcount[]=$electsemcount;
                    $electdata[]=$value['stream_name'];
                }
            }
           
            if(isset($_POST['saveelect']))
            {
                $checkelective = Yii::$app->db->createCommand("SELECT cur_elect_id,pec_paper,oec_paper,eec_paper,mc_paper,ac_paper FROM  cur_elective_register A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.semester=".$semester)->queryOne();

                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();

                if ($checkelective['pec_paper']>0) 
                { 
                    $temparray=''; $success=0;
                    for ($loop=1; $loop <=$checkelective['pec_paper'] ; $loop++) 
                    {
                        
                        $id='PEC'.$loop;
                        $loopdata=$_POST[$id];
                        
                        for ($i=0; $i <count($loopdata) ; $i++) 
                        { 
                            $checkelectivesubject = Yii::$app->db->createCommand("SELECT count(cur_elect_id) FROM   cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$loopdata[$i]."' AND A.semester=".$semester)->queryScalar();

                            $handle_dept_id = Yii::$app->db->createCommand("SELECT coe_dept_id FROM   cur_electivetodept WHERE subject_code_new='".$loopdata[$i]."'")->queryScalar();

                            if(empty($handle_dept_id))
                            {
                                $handle_dept_id=Yii::$app->db->createCommand("SELECT coe_dept_id FROM   cur_elective_subject WHERE subject_code='".$loopdata[$i]."'")->queryScalar();

                                if(empty($handle_dept_id))
                                {
                                    $handle_dept_id=Yii::$app->db->createCommand("SELECT coe_dept_id FROM   cur_curriculum_subject WHERE subject_code='".$loopdata[$i]."'")->queryScalar();
                                }
                            }

                            
                            if($checkelectivesubject==0)
                            {
                                $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_register_subject(cur_elect_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by, handle_dept_id) VALUES ("'.$checkelective['cur_elect_id'].'", "'.$degree_type.'", "'.$coe_batch_id.'", "'.$coe_regulation_id.'", "'.$coe_dept_id.'", 192, "'.$id.'", "'.$loopdata[$i].'", "'.$semester.'", "'.$created_at.'","'.$userid.'","'.$handle_dept_id.'")')->execute();

                                if($insert)
                                {
                                    $success++;
                                }
                            }
                        }

                        
                    }
                }

                if ($checkelective['oec_paper']>0) 
                { 
                    $temparray=''; $success=0;
                    for ($loop=1; $loop <=$checkelective['oec_paper'] ; $loop++) 
                    {
                        
                        $id='OEC'.$loop;
                        $loopdata=$_POST[$id];
                        
                        for ($i=0; $i <count($loopdata) ; $i++) 
                        { 
                            $checkelectivesubject = Yii::$app->db->createCommand("SELECT count(cur_elect_id) FROM   cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$loopdata[$i]."' AND A.semester=".$semester)->queryScalar();
                            
                            $handle_dept_id = Yii::$app->db->createCommand("SELECT coe_dept_id FROM   cur_electivetodept WHERE subject_code_new='".$loopdata[$i]."'")->queryScalar();

                            if(empty($handle_dept_id))
                            {
                                $handle_dept_id=Yii::$app->db->createCommand("SELECT coe_dept_id FROM   cur_elective_subject WHERE subject_code='".$loopdata[$i]."'")->queryScalar();

                                if(empty($handle_dept_id))
                                {
                                    $handle_dept_id=Yii::$app->db->createCommand("SELECT coe_dept_id FROM   cur_curriculum_subject WHERE subject_code='".$loopdata[$i]."'")->queryScalar();
                                }
                            }

                            if($checkelectivesubject==0)
                            {
                                $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_register_subject(cur_elect_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by, handle_dept_id) VALUES ("'.$checkelective['cur_elect_id'].'", "'.$degree_type.'", "'.$coe_batch_id.'", "'.$coe_regulation_id.'", "'.$coe_dept_id.'", 191, "'.$id.'", "'.$loopdata[$i].'", "'.$semester.'", "'.$created_at.'","'.$userid.'","'.$handle_dept_id.'")')->execute();

                                if($insert)
                                {
                                    $success++;
                                }
                            }
                        }

                        
                    }
                }

                if ($checkelective['eec_paper']>0) 
                { 
                    $temparray=''; $success=0;
                    for ($loop=1; $loop <=$checkelective['eec_paper'] ; $loop++) 
                    {
                        
                        $id='EEC'.$loop;
                        $loopdata=$_POST[$id];
                        
                        for ($i=0; $i <count($loopdata) ; $i++) 
                        { 
                            $checkelectivesubject = Yii::$app->db->createCommand("SELECT count(cur_elect_id) FROM   cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$loopdata[$i]."' AND A.semester=".$semester)->queryScalar();

                            $handle_dept_id = Yii::$app->db->createCommand("SELECT coe_dept_id FROM   cur_electivetodept WHERE subject_code_new='".$loopdata[$i]."'")->queryScalar();

                            if(empty($handle_dept_id))
                            {
                                $handle_dept_id=Yii::$app->db->createCommand("SELECT coe_dept_id FROM   cur_elective_subject WHERE subject_code='".$loopdata[$i]."'")->queryScalar();

                                if(empty($handle_dept_id))
                                {
                                    $handle_dept_id=Yii::$app->db->createCommand("SELECT coe_dept_id FROM   cur_curriculum_subject WHERE subject_code='".$loopdata[$i]."'")->queryScalar();
                                }
                            }
                            

                            if($checkelectivesubject==0)
                            {
                                $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_register_subject(cur_elect_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by, handle_dept_id) VALUES ("'.$checkelective['cur_elect_id'].'", "'.$degree_type.'", "'.$coe_batch_id.'", "'.$coe_regulation_id.'", "'.$coe_dept_id.'", 193, "'.$id.'", "'.$loopdata[$i].'", "'.$semester.'", "'.$created_at.'","'.$userid.'","'.$handle_dept_id.'")')->execute();

                                if($insert)
                                {
                                    $success++;
                                }
                            }
                        }

                        
                    }
                }

                if ($checkelective['mc_paper']>0 && $_POST['mc_course']!='') 
                { 
                    $temparray=''; $success=0; 
                    $mc_course=$_POST['mc_course'];

                    $checkelectivesubject = Yii::$app->db->createCommand("SELECT count(cur_elect_id) FROM   cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$mc_course."' AND A.semester=".$semester)->queryScalar();
                            
                    if($checkelectivesubject==0)
                    {
                        $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_register_subject(cur_elect_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by) VALUES ("'.$checkelective['cur_elect_id'].'", "'.$degree_type.'", "'.$coe_batch_id.'" "'.$coe_regulation_id.'", "'.$coe_dept_id.'", 106, "MC", "'.$mc_course.'", "'.$semester.'", "'.$created_at.'","'.$userid.'")')->execute();

                        if($insert)
                        {
                            $success++;
                        }
                    }
                }

                if($degree_type=='PG' || $degree_type=='MBA')
                {
                    if ($checkelective['ac_paper']>0 && $_POST['ac_course']!='') 
                    { 
                        $temparray=''; $success=0; 
                        $ac_course=$_POST['ac_course'];

                        $checkelectivesubject = Yii::$app->db->createCommand("SELECT count(cur_elect_id) FROM   cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$ac_course."' AND A.semester=".$semester)->queryScalar();
                                
                        if($checkelectivesubject==0)
                        {
                            $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_register_subject(cur_elect_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by) VALUES ("'.$checkelective['cur_elect_id'].'", "'.$degree_type.'", "'.$coe_batch_id.'" "'.$coe_regulation_id.'", "'.$coe_dept_id.'", 122, "AC", "'.$ac_course.'", "'.$semester.'", "'.$created_at.'","'.$userid.'")')->execute();

                            if($insert)
                            {
                                $success++;
                            }
                        }
                    }
                }
                
                if($success>0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Course Registered Successfully");
                    return $this->redirect(['view', 'id' => $checkelective['cur_elect_id']]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Data Not insert! Please Check");
                    return $this->redirect(['elective-register-form', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'semester'=>$semester]);
                }

            }
            else
            {
                $pecdata = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_elective_subject A WHERE A.coe_dept_id = '".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option IN ('192')  AND A.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

                $pecfromotherdept = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_dept_ids = '".$coe_dept_id."' AND B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('192')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

                $pecdata=array_merge($pecdata,$pecfromotherdept);

                $pecfromotherdept1 = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_dept_ids = '".$coe_dept_id."' AND B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('192')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

                $pecdata=array_merge($pecdata,$pecfromotherdept1);

                $eecdata = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_elective_subject A WHERE A.coe_dept_id = '".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option IN ('193')  AND A.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

                $eecfromotherdept = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_dept_ids = '".$coe_dept_id."' AND B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('193')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

                $eecdata=array_merge($eecdata,$eecfromotherdept);

                $eecfromotherdept1 = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_dept_ids = '".$coe_dept_id."' AND B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('193')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

                $eecdata=array_merge($eecdata,$eecfromotherdept1);


                $oecdata = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_elective_subject A WHERE A.coe_dept_id != '".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option IN ('191')  AND A.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

                $oecfromotherdept = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('191')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

                $oecdata=array_merge($oecdata,$oecfromotherdept);

                $oecfromotherdept1 = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('191')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND semester='".$semester."')")->queryAll();

                $oecdata=array_merge($oecdata,$oecfromotherdept1);

                $mcdata = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_aicte_norms F ON F.cur_an_id=A.stream_id WHERE A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND F.stream_name IN ('MC')  AND A.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."')")->queryAll();

                $acdata = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_aicte_norms F ON F.cur_an_id=A.stream_id WHERE A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND F.stream_name IN ('AC')  AND A.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."')")->queryAll();
                //print_r($mcdata); exit;
                return $this->render('elective-register-form', [
                'model' => $model,
                'electdata'=>$electdata,
                'electcount'=>$electcount,
                'pecdata'=>$pecdata,
                'eecdata'=>$eecdata,
                'oecdata'=>$oecdata,
                'mcdata'=>$mcdata,
                'acdata'=>$acdata
                ]);
            }
            
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Please Choose All fields! Please Check");
             return $this->redirect(['index']);
        }

    }

    /**
     * Updates an existing ElectiveRegister model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

         if (Yii::$app->request->post()) 
        {
            $degree_type=$_POST['ElectiveRegister']['degree_type'];
            $coe_dept_id=$_POST['coe_dept_id'];
            $coe_regulation_id=$_POST['ElectiveRegister']['coe_regulation_id'];
            $semester=$_POST['ElectiveRegister']['semester'];

            $semm=" AND sem".$semester."!=''";
            $semcolumn="sem".$semester;
            
            $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_elective_register A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.semester=".$semester)->queryOne();

            if(!empty($checkelective))
            {
                return $this->redirect(['elective-register-formupdate', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'semester'=>$semester, 'id'=>$id]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "No Data Found, Please Check");
                return $this->redirect(['update','id'=>$id]);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionElectiveRegisterFormupdate($degree_type,$coe_dept_id,$coe_regulation_id,$semester,$id)
    {

        $model = new ElectiveRegister();
        if(!empty($semester)) 
        {
            $semm=" AND sem".$semester."!=''";
            $semcolumn="sem".$semester;

            if($degree_type=='UG')
            {
                $checksemdata = Yii::$app->db->createCommand("SELECT A.*,stream_name FROM  cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND stream_name IN ('PEC','OEC','EEC','MC')".$semm)->queryAll();
            }
            else if($degree_type=='PG')
            {
                $checksemdata = Yii::$app->db->createCommand("SELECT A.*,stream_name FROM  cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND stream_name IN ('OEC','PEC','MC','AC')".$semm)->queryAll();
            }
            else
            {
                $checksemdata = Yii::$app->db->createCommand("SELECT A.*,stream_name FROM  cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND stream_name IN ('PEC')".$semm)->queryAll();
            }

            

            $electdata=[];$electcount=[];
            foreach ($checksemdata as $value) 
            {
                $electsemcount = Yii::$app->db->createCommand("SELECT ".$semcolumn." FROM  cur_credit_distribution_sem A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND cur_stream_id ='".$value['cur_stream_id']."' AND ".$semcolumn."!=''")->queryScalar();

                if(($electsemcount!=0) || ($electsemcount==0 && $value['stream_name']=='MC') || ($electsemcount==0 && $value['stream_name']=='AC'))
                {
                    $electcount[]=$electsemcount;
                    $electdata[]=$value['stream_name'];
                }
            }
           
            if(isset($_POST['saveelect']))
            {
                $checkelective = Yii::$app->db->createCommand("SELECT cur_elect_id,pec_paper,oec_paper,eec_paper,mc_paper FROM  cur_elective_register A WHERE A.cur_elect_id='".$id."'")->queryOne();

                Yii::$app->db->createCommand('DELETE FROM cur_elective_register_subject WHERE cur_elect_id="'.$id.'"')->execute();

                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();

                 $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$coe_regulation_id)->queryScalar(); 

                if ($checkelective['pec_paper']>0) 
                { 
                    $temparray=''; $success=0;
                    for ($loop=1; $loop <=$checkelective['pec_paper'] ; $loop++) 
                    {
                        
                        $id='PEC'.$loop;
                        $loopdata=$_POST[$id];
                        
                        for ($i=0; $i <count($loopdata) ; $i++) 
                        { 
                            $checkelectivesubject = Yii::$app->db->createCommand("SELECT count(cur_elect_id) FROM   cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$loopdata[$i]."' AND A.semester=".$semester)->queryScalar();
                            
                            if($checkelectivesubject==0)
                            {
                                $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_register_subject(cur_elect_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by) VALUES ("'.$checkelective['cur_elect_id'].'", "'.$degree_type.'", "'.$coe_batch_id.'", "'.$coe_regulation_id.'", "'.$coe_dept_id.'", 192, "'.$id.'", "'.$loopdata[$i].'", "'.$semester.'", "'.$created_at.'","'.$userid.'")')->execute();

                                if($insert)
                                {
                                    $success++;
                                }
                            }
                        }

                        
                    }
                }

                if ($checkelective['oec_paper']>0) 
                { 
                    $temparray=''; $success=0;
                    for ($loop=1; $loop <=$checkelective['oec_paper'] ; $loop++) 
                    {
                        
                        $id='OEC'.$loop;
                        $loopdata=$_POST[$id];
                        
                        for ($i=0; $i <count($loopdata) ; $i++) 
                        { 
                            $checkelectivesubject = Yii::$app->db->createCommand("SELECT count(cur_elect_id) FROM   cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$loopdata[$i]."' AND A.semester=".$semester)->queryScalar();
                            
                            if($checkelectivesubject==0)
                            {
                                $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_register_subject(cur_elect_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by) VALUES ("'.$checkelective['cur_elect_id'].'", "'.$degree_type.'", "'.$coe_batch_id.'", "'.$coe_regulation_id.'", "'.$coe_dept_id.'", 191, "'.$id.'", "'.$loopdata[$i].'", "'.$semester.'", "'.$created_at.'","'.$userid.'")')->execute();

                                if($insert)
                                {
                                    $success++;
                                }
                            }
                        }

                        
                    }
                }

                if ($checkelective['eec_paper']>0) 
                { 
                    $temparray=''; $success=0;
                    for ($loop=1; $loop <=$checkelective['eec_paper'] ; $loop++) 
                    {
                        
                        $id='EEC'.$loop;
                        $loopdata=$_POST[$id];
                        
                        for ($i=0; $i <count($loopdata) ; $i++) 
                        { 
                            $checkelectivesubject = Yii::$app->db->createCommand("SELECT count(cur_elect_id) FROM   cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$loopdata[$i]."' AND A.semester=".$semester)->queryScalar();
                            
                            if($checkelectivesubject==0)
                            {
                                $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_register_subject(cur_elect_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by) VALUES ("'.$checkelective['cur_elect_id'].'", "'.$degree_type.'", "'.$coe_batch_id.'", "'.$coe_regulation_id.'", "'.$coe_dept_id.'", 193, "'.$id.'", "'.$loopdata[$i].'", "'.$semester.'", "'.$created_at.'","'.$userid.'")')->execute();

                                if($insert)
                                {
                                    $success++;
                                }
                            }
                        }

                        
                    }
                }

                if ($checkelective['mc_paper']>0 && $_POST['mc_course']!='') 
                { 
                    $temparray=''; $success=0; 
                    $mc_course=$_POST['mc_course'];

                    $checkelectivesubject = Yii::$app->db->createCommand("SELECT count(cur_elect_id) FROM   cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$mc_course."' AND A.semester=".$semester)->queryScalar();
                            
                    if($checkelectivesubject==0)
                    {
                        $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_register_subject(cur_elect_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by) VALUES ("'.$checkelective['cur_elect_id'].'", "'.$degree_type.'", "'.$coe_batch_id.'", "'.$coe_regulation_id.'", "'.$coe_dept_id.'", 106, "MC", "'.$mc_course.'", "'.$semester.'", "'.$created_at.'","'.$userid.'")')->execute();

                        if($insert)
                        {
                            $success++;
                        }
                    }
                }

                if($degree_type=='PG' || $degree_type=='MBA')
                {
                    if ($checkelective['ac_paper']>0 && $_POST['ac_course']!='') 
                    { 
                        $temparray=''; $success=0; 
                        $ac_course=$_POST['ac_course'];

                        $checkelectivesubject = Yii::$app->db->createCommand("SELECT count(cur_elect_id) FROM   cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$ac_course."' AND A.semester=".$semester)->queryScalar();
                                
                        if($checkelectivesubject==0)
                        {
                            $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_register_subject(cur_elect_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by) VALUES ("'.$checkelective['cur_elect_id'].'", "'.$degree_type.'", "'.$coe_batch_id.'", "'.$coe_regulation_id.'", "'.$coe_dept_id.'", 122, "AC", "'.$ac_course.'", "'.$semester.'", "'.$created_at.'","'.$userid.'")')->execute();

                            if($insert)
                            {
                                $success++;
                            }
                        }
                    }
                }
                
                if($success>0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Elective Course Registration Updated Successfully");
                    return $this->redirect(['view', 'id' => $checkelective['cur_elect_id']]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Data Not insert! Please Check");
                    return $this->redirect(['elective-register-formupdate', 'degree_type' =>$degree_type,'coe_dept_id'=>$coe_dept_id,'coe_regulation_id'=>$coe_regulation_id,'semester'=>$semester, 'id'=>$id]);
                }

            }
            else
            {
                $pecdata = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_elective_subject A WHERE A.coe_dept_id = '".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option IN ('192')  AND A.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."')")->queryAll();

                $pecfromotherdept = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_dept_ids = '".$coe_dept_id."' AND B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('192')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."') ")->queryAll();

                $pecdata=array_merge($pecdata,$pecfromotherdept);

                $pecfromotherdept1 = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_dept_ids = '".$coe_dept_id."' AND B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('192')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."')")->queryAll();

                $pecdata=array_merge($pecdata,$pecfromotherdept1);

                $eecdata = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_elective_subject A WHERE A.coe_dept_id = '".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option IN ('193')  AND A.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."')")->queryAll();

                $eecfromotherdept = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_dept_ids = '".$coe_dept_id."' AND B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('193')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."')")->queryAll();

                $eecdata=array_merge($eecdata,$eecfromotherdept);

                $eecfromotherdept1 = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_dept_ids = '".$coe_dept_id."' AND B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('193')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."')")->queryAll();

                $eecdata=array_merge($eecdata,$eecfromotherdept1);


                $oecdata = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_elective_subject A WHERE A.coe_dept_id != '".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option IN ('191')  AND A.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."')")->queryAll();

                $oecfromotherdept = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('191')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."')")->queryAll();

                $oecdata=array_merge($oecdata,$oecfromotherdept);

                $oecfromotherdept1 = Yii::$app->db->createCommand("SELECT B.subject_code_new as subject_code, A.subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE B.coe_regulation_id=".$coe_regulation_id." AND B.degree_type='".$degree_type."' AND B.coe_elective_option IN ('191')  AND B.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."')")->queryAll();

                $oecdata=array_merge($oecdata,$oecfromotherdept1);

                $mcdata = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_aicte_norms F ON F.cur_an_id=A.stream_id WHERE A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND F.stream_name IN ('MC')  AND A.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."')")->queryAll();

                 $acdata = Yii::$app->db->createCommand("SELECT A.subject_code, A.subject_name FROM cur_curriculum_subject A JOIN cur_aicte_norms F ON F.cur_an_id=A.stream_id WHERE A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND F.stream_name IN ('AC')  AND A.approve_status=1 AND A.subject_code NOT IN (SELECT subject_code FROM cur_elective_register_subject WHERE coe_dept_id = '".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id." AND degree_type='".$degree_type."' AND cur_elect_id!=".$id." AND semester='".$semester."')")->queryAll();

                return $this->render('elective-register-formupdate', [
                'model' => $model,
                'electdata'=>$electdata,
                'electcount'=>$electcount,
                'pecdata'=>$pecdata,
                'eecdata'=>$eecdata,
                'oecdata'=>$oecdata,
                'mcdata'=>$mcdata,
                'acdata'=>$acdata,
                'electid'=>$id
                ]);
            }
            
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Please Choose All fields! Please Check");
             return $this->redirect(['index']);
        }

    }

    /**
     * Deletes an existing ElectiveRegister model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletedata($id)
    {

        Yii::$app->db->createCommand('DELETE FROM cur_elective_register_subject WHERE cur_elect_id="'.$id.'"')->execute();
        Yii::$app->db->createCommand('DELETE FROM cur_elective_register WHERE cur_elect_id="'.$id.'"')->execute();
        Yii::$app->ShowFlashMessages->setMsg('Success', "Deleted Successfully");
        return $this->redirect(['index']);
    }

    /**
     * Finds the ElectiveRegister model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ElectiveRegister the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ElectiveRegister::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
