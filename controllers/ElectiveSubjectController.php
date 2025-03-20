<?php

namespace app\controllers;

use Yii;
use app\models\CurriculumSubject;
use app\models\CurriculumSubjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Department;
use yii\db\Query;
use app\models\SubjectPrefix;
use app\models\LTP;
use app\models\Batch;
use app\models\Degree;
use app\models\ElectiveSubject;
use app\models\ElectiveSubjectSearch;
use app\models\Servicesubjecttodept;
use app\models\Electivetodept;
error_reporting(0);
/**
 * ElectiveSubjectController implements the CRUD actions for ElectiveSubject model.
 */
class ElectiveSubjectController extends Controller
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
     * Lists all ElectiveSubject models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ElectiveSubjectSearch();
        $_SESSION['electsubject']='191';
         $_SESSION['minor']='0';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single ElectiveSubject model.
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
     * Creates a new ElectiveSubject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ElectiveSubject();
        $electivemodel = new Electivetodept();

        if ($model->load(Yii::$app->request->post())) 
        {
            $subject_code=strtoupper($_POST['subject_prefix']).strtoupper($model->subject_code);  

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();        

            $query1 = new Query();           
            $query1->select('coe_cur_id')->from('cur_curriculum_subject')->where(['subject_code' =>$subject_code])->andWhere(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ]);
            $pgmdata1 = $query1->createCommand()->queryAll();

             $query = new Query();           
            $query->select('coe_elective_id')->from('cur_elective_subject')->where(['subject_code' =>$subject_code ])->andWhere(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ]);

            $pgmdata = $query->createCommand()->queryAll();

            $query11 = new Query();           
            $query11->select('subject_name')->from('cur_curriculum_subject')->Where(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ]);
            $pgmdata11 = $query11->createCommand()->queryAll();

            $query2 = new Query();           
            $query2->select('subject_name')->from('cur_elective_subject')->Where(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ])->andWhere(['<>','coe_elective_option',203]);
            $pgmdata2 = $query2->createCommand()->queryAll();

            $subjmerge=array_merge($pgmdata11,$pgmdata2);
            $checksubject_name=0;
            foreach ($subjmerge as $value) 
            {
                $existname = str_replace(' ', '-', strtolower($value['subject_name']));
                $newname = str_replace(' ', '-', strtolower($model->subject_name));
                $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                if($existname==$newname)
                {
                    $checksubject_name=1;

                }
            }
            //echo $checksubject_name; exit;

            $query = new Query();           
            $query->select('subject_name')->from('cur_elective_subject')->where(['subject_code' =>$subject_code, 'coe_regulation_id'=>$model->coe_regulation_id])->andWhere(['!=','coe_batch_id',$coe_batch_id])->andWhere(['degree_type'=>$model->degree_type ])->andWhere(['<>','coe_elective_option',203]);

            $checkexname = $query->createCommand()->queryScalar();

            $ckexistname = str_replace(' ', '-', strtolower($checkexname));
            $newsubname = str_replace(' ', '-', strtolower($model->subject_name));
            $ckexistname=preg_replace('/[^A-Za-z\-]/', '',$ckexistname);
            $newsubname=preg_replace('/[^A-Za-z\-]/', '',$newsubname );
            $ckexitone=0;
            if($ckexistname!=$newsubname && $checkexname!='')
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Course Name Different From Previous Batch Please Change Course Code!");
                return $this->redirect(['create']);
            }
            else if(empty($pgmdata) && empty($pgmdata1) && $checksubject_name==0)
            {
                $stream_id = Yii::$app->db->createCommand("SELECT cur_an_id FROM cur_aicte_norms WHERE stream_name='OEC'")->queryScalar();
                $model->stream_id=$stream_id;
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();
                $model->coe_dept_id=$_POST['coe_dept_id'];
                $model->subject_type_id=$_POST['subject_type_id'];
                $model->subject_category_type_id=$_POST['subject_category_type_id']; 
                $model->coe_batch_id=$coe_batch_id;
                $model->coe_elective_option=191;
                $model->subject_code=$subject_code;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Open Elective Course Added successfully..");
                         return $this->redirect(['index']);                    
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                    return $this->redirect(['create']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Already Added Elective Course or Curriculum Course! Please Check");
                return $this->redirect(['create']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'electivemodel'=>$electivemodel
            ]);
        }
    }


    public function actionPecIndex()
    {
        $searchModel = new ElectiveSubjectSearch();
        $_SESSION['electsubject']='192';
         $_SESSION['minor']='0';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('pec_elective_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreatePec()
    {
        $model = new ElectiveSubject();
        $electivemodel = new Electivetodept();

        if ($model->load(Yii::$app->request->post())) 
        {
            $subject_code=strtoupper($_POST['subject_prefix']).strtoupper($model->subject_code);
           
            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();  

            $query1 = new Query();           
            $query1->select('coe_cur_id')->from('cur_curriculum_subject')->where(['subject_code' =>$subject_code])->andWhere(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ]);
            $pgmdata1 = $query1->createCommand()->queryAll();

            $query = new Query();           
            $query->select('coe_elective_id')->from('cur_elective_subject')->where(['subject_code' =>$subject_code])->andWhere(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ]);
            $pgmdata = $query->createCommand()->queryAll();

            $query11 = new Query();           
            $query11->select('subject_name')->from('cur_curriculum_subject')->Where(['coe_regulation_id'=>$model->coe_regulation_id , 'coe_batch_id'=>$coe_batch_id])->andWhere(['degree_type'=>$model->degree_type ]);
            $pgmdata11 = $query11->createCommand()->queryAll();

            $query2 = new Query();           
            $query2->select('subject_name')->from('cur_elective_subject')->Where(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ])->andWhere(['<>','coe_elective_option',203]);
            $pgmdata2 = $query2->createCommand()->queryAll();

            $subjmerge=array_merge($pgmdata11,$pgmdata2);
            $checksubject_name=0;
            foreach ($subjmerge as $value) 
            {
                $existname = str_replace(' ', '-', strtolower($value['subject_name']));
                $newname = str_replace(' ', '-', strtolower($model->subject_name));
                $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                if($existname==$newname)
                {
                    $checksubject_name=1;

                }
            }
            //echo $checksubject_name; exit;

            $query = new Query();           
            $query->select('subject_name')->from('cur_elective_subject')->where(['subject_code' =>$subject_code, 'coe_regulation_id'=>$model->coe_regulation_id])->andWhere(['!=','coe_batch_id',$coe_batch_id])->andWhere(['degree_type'=>$model->degree_type ])->andWhere(['<>','coe_elective_option',203]);

            $checkexname = $query->createCommand()->queryScalar();

            $ckexistname = str_replace(' ', '-', strtolower($checkexname));
            $newsubname = str_replace(' ', '-', strtolower($model->subject_name));
            $ckexistname=preg_replace('/[^A-Za-z\-]/', '',$ckexistname);
            $newsubname=preg_replace('/[^A-Za-z\-]/', '',$newsubname );
            $ckexitone=0;
            if($ckexistname!=$newsubname && $checkexname!='')
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Course Name Different From Previous Batch Please Change Course Code!");
                return $this->redirect(['create-pec']);
            }
            else if(empty($pgmdata) && empty($pgmdata1) && $checksubject_name==0)
            {
                if($model->degree_type=='MBA')
                {
                    $stream_id = Yii::$app->db->createCommand("SELECT cur_an_id FROM cur_aicte_norms WHERE stream_name='SEC'")->queryScalar();
                }
                else
                {
                    $stream_id = Yii::$app->db->createCommand("SELECT cur_an_id FROM cur_aicte_norms WHERE stream_name='PEC'")->queryScalar();
                }
                
                $model->stream_id=$stream_id;
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();
                $model->coe_dept_id=$_POST['coe_dept_id'];
                $model->subject_type_id=$_POST['subject_type_id'];
                $model->subject_category_type_id=$_POST['subject_category_type_id']; 
                $model->coe_batch_id=$coe_batch_id;
                $model->coe_elective_option=192;
                $model->subject_code=$subject_code;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Professional Elective Course Added successfully..");
                        return $this->redirect(['pec-index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                    return $this->redirect(['create-pec']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Already Added Elective Course or Curriculum Course! Please Check");
                return $this->redirect(['create-pec']);
            }
        } else {
            return $this->render('create-pec', [
                'model' => $model,
                'electivemodel'=>$electivemodel
            ]);
        }
    }

    public function actionEecIndex()
    {
        $searchModel = new ElectiveSubjectSearch();
        $_SESSION['electsubject']='193';

         $_SESSION['minor']='0';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('emg_elective_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateEec()
    {
        $model = new ElectiveSubject();
        $electivemodel = new Electivetodept();

        if ($model->load(Yii::$app->request->post())) 
        {
            $subject_code=strtoupper($_POST['subject_prefix']).strtoupper($model->subject_code);
           
            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();  

            $query = new Query();           
            $query->select('coe_elective_id')->from('cur_elective_subject')->where(['subject_code' =>$subject_code ])->andWhere(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ]);
            $pgmdata = $query->createCommand()->queryAll();

            $query1 = new Query();           
            $query1->select('coe_cur_id')->from('cur_curriculum_subject')->where(['subject_code' =>$subject_code])->andWhere(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ]);
            $pgmdata1 = $query1->createCommand()->queryAll();

            $query11 = new Query();           
            $query11->select('subject_name')->from('cur_curriculum_subject')->Where(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ]);
            $pgmdata11 = $query11->createCommand()->queryAll();

            $query2 = new Query();           
            $query2->select('subject_name')->from('cur_elective_subject')->Where(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type ]);
            $pgmdata2 = $query2->createCommand()->queryAll();

            $subjmerge=array_merge($pgmdata11,$pgmdata2);
            $checksubject_name=0;
            foreach ($subjmerge as $value) 
            {
                $existname = str_replace(' ', '-', strtolower($value['subject_name']));
                $newname = str_replace(' ', '-', strtolower($model->subject_name));
                $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                if($existname==$newname)
                {
                    $checksubject_name=1;

                }
            }
            //echo $checksubject_name; exit;
            //print_r($pgmdata); exit();
             $query = new Query();           
            $query->select('subject_name')->from('cur_elective_subject')->where(['subject_code' =>$subject_code, 'coe_regulation_id'=>$model->coe_regulation_id])->andWhere(['!=','coe_batch_id',$coe_batch_id])->andWhere(['degree_type'=>$model->degree_type ]);

            $checkexname = $query->createCommand()->queryScalar();

            $ckexistname = str_replace(' ', '-', strtolower($checkexname));
            $newsubname = str_replace(' ', '-', strtolower($model->subject_name));
            $ckexistname=preg_replace('/[^A-Za-z\-]/', '',$ckexistname);
            $newsubname=preg_replace('/[^A-Za-z\-]/', '',$newsubname );
            $ckexitone=0;
            if($ckexistname!=$newsubname && $checkexname!='')
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Course Name Different From Previous Batch Please Change Course Code!");
                return $this->redirect(['create-eec']);
            }
            else if(empty($pgmdata) && empty($pgmdata1) && $checksubject_name==0)
            {
                $stream_id = Yii::$app->db->createCommand("SELECT cur_an_id FROM cur_aicte_norms WHERE stream_name='EEC'")->queryScalar();
                $model->stream_id=$stream_id;
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();
                $model->coe_dept_id=$_POST['coe_dept_id'];
                $model->subject_type_id=$_POST['subject_type_id'];
                $model->subject_category_type_id=$_POST['subject_category_type_id']; 
                $model->coe_batch_id=$coe_batch_id;
                $model->coe_elective_option=193;
                $model->subject_code=$subject_code;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Emerging Elective Course Added successfully..");
                        return $this->redirect(['eec-index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                    return $this->redirect(['create-eec']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Already Added Elective Course or Curriculum Course! Please Check");
                return $this->redirect(['create-eec']);
            }
        } else {
            return $this->render('create-eec', [
                'model' => $model,
                'electivemodel'=>$electivemodel
            ]);
        }
    }

   
    public function actionDeleteOec($id) //open elective
    {
        $checkelective = Yii::$app->db->createCommand("SELECT count(A.coe_electivetodept_id) FROM cur_electivetodept A JOIN cur_elective_subject B ON B.coe_elective_id=A.coe_subject_id WHERE A.coe_subject_id=".$id." AND A.coe_elective_option<200")->queryScalar();

        $checksubject = Yii::$app->db->createCommand("SELECT A.subject_code FROM cur_electivetodept A JOIN cur_elective_subject B ON B.coe_elective_id=A.coe_subject_id WHERE A.coe_subject_id=".$id." AND A.coe_elective_option<200")->queryScalar();

        $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM  cur_elective_subject WHERE coe_elective_id=".$id)->queryScalar();

        $checksyllabus = Yii::$app->db->createCommand("SELECT count(*) FROM  cur_syllabus WHERE subject_code='".$checksubject."' AND coe_batch_id=".$coe_batch_id)->queryScalar();

        if($checkelective==0 && $checksyllabus ==0 && !empty($coe_batch_id))
        {
            $this->findModel($id)->delete();

            Yii::$app->ShowFlashMessages->setMsg('Success', "Successfully deleted");

            return $this->redirect(['index']);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Course can't Delete! Course Assigned to other Dept. or Syllabus Added! Please Check");

            return $this->redirect(['index']);
        }
    }

    public function actionDeletePec($id)
    {
        $checkelective = Yii::$app->db->createCommand("SELECT count(A.coe_electivetodept_id) FROM cur_electivetodept A JOIN cur_elective_subject B ON B.coe_elective_id=A.coe_subject_id WHERE A.coe_subject_id=".$id." AND A.coe_elective_option<200")->queryScalar();

        $checksubject = Yii::$app->db->createCommand("SELECT A.subject_code FROM cur_electivetodept A JOIN cur_elective_subject B ON B.coe_elective_id=A.coe_subject_id WHERE A.coe_subject_id=".$id." AND A.coe_elective_option<200")->queryScalar();

        $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM  cur_elective_subject WHERE coe_elective_id=".$id)->queryScalar();

        $checksyllabus = Yii::$app->db->createCommand("SELECT count(*) FROM  cur_syllabus WHERE subject_code='".$checksubject."' AND coe_batch_id=".$coe_batch_id)->queryScalar();

        if($checkelective==0 && $checksyllabus ==0 && !empty($coe_batch_id))
        {
            $this->findModel($id)->delete();
            Yii::$app->ShowFlashMessages->setMsg('Success', "Successfully deleted");
            return $this->redirect(['pec-index']);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Course can't Delete! Course Assigned to other Dept. or Syllabus Added! Please Check");

            return $this->redirect(['pec-index']);
        }
        
    }

     public function actionDeleteEec($id)
    {
        $checkelective = Yii::$app->db->createCommand("SELECT count(A.coe_electivetodept_id) FROM cur_electivetodept A JOIN cur_elective_subject B ON B.coe_elective_id=A.coe_subject_id WHERE A.coe_subject_id=".$id." AND A.coe_elective_option<200")->queryScalar();

        $checksubject = Yii::$app->db->createCommand("SELECT A.subject_code FROM cur_electivetodept A JOIN cur_elective_subject B ON B.coe_elective_id=A.coe_subject_id WHERE A.coe_subject_id=".$id." AND A.coe_elective_option<200")->queryScalar();

        $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM  cur_elective_subject WHERE coe_elective_id=".$id)->queryScalar();

        $checksyllabus = Yii::$app->db->createCommand("SELECT count(*) FROM  cur_syllabus WHERE subject_code='".$checksubject."' AND coe_batch_id=".$coe_batch_id)->queryScalar();

        if($checkelective==0 && $checksyllabus ==0 && !empty($coe_batch_id))
        {
            $this->findModel($id)->delete();
            Yii::$app->ShowFlashMessages->setMsg('Success', "Successfully deleted");
            return $this->redirect(['eec-index']);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Course can't Delete! Course Assigned to other Dept. or Syllabus Added! Please Check");

            return $this->redirect(['eec-index']);
        }
        
    }

    public function actionDeleteMinordeg($id)
    {
        $checkelective = Yii::$app->db->createCommand("SELECT count(A.coe_electivetodept_id) FROM cur_electivetodept A JOIN cur_elective_subject B ON B.coe_elective_id=A.coe_subject_id WHERE A.coe_subject_id=".$id." AND A.coe_elective_option<200")->queryScalar();
        //$checkelective = Yii::$app->db->createCommand("SELECT count(coe_electivetodept_id) FROM cur_electivetodept WHERE coe_subject_id=".$id)->queryScalar();

        $checksubject = Yii::$app->db->createCommand("SELECT B.subject_code FROM cur_electivetodept A JOIN cur_elective_subject B ON B.coe_elective_id=A.coe_subject_id WHERE A.coe_subject_id=".$id." AND A.coe_elective_option<200")->queryScalar();

        $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM  cur_elective_subject WHERE coe_elective_id=".$id)->queryScalar();

        $checksyllabus = Yii::$app->db->createCommand("SELECT count(*) FROM  cur_syllabus WHERE subject_code='".$checksubject."' AND coe_batch_id=".$coe_batch_id)->queryScalar();

        if($checkelective==0 && $checksyllabus ==0 && !empty($coe_batch_id))
        {
            $this->findModel($id)->delete();

            return $this->redirect(['minordeg-index']);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Course can't Delete! Course Assigned to other Dept. or Syllabus Added! Please Check");

            return $this->redirect(['minordeg-index']);
        }
        
    }

    public function actionMinordegIndex()
    {

        $_SESSION['electsubject']='203';
         $_SESSION['minor']='1';
        $searchModel = new ElectiveSubjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('minor_degree_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionCreateMinordeg()
    {
        $model = new ElectiveSubject();
        $electivemodel = new Electivetodept();

        if ($model->load(Yii::$app->request->post())) 
        {
            $subject_code=strtoupper($_POST['subject_prefix']).strtoupper($model->subject_code);
            $query = new Query();           
            $query->select('coe_elective_id')->from('cur_elective_subject')->where(['subject_code' =>$subject_code ]);
            $pgmdata = $query->createCommand()->queryAll();

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();

            $query1 = new Query();           
            $query1->select('coe_cur_id')->from('cur_curriculum_subject')->where(['subject_code' =>$subject_code])->andWhere(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id])->andWhere(['degree_type'=>$model->degree_type ]);;
            $pgmdata1 = $query1->createCommand()->queryAll();

            $query11 = new Query();           
            $query11->select('subject_name')->from('cur_curriculum_subject')->Where(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type]);;
            $pgmdata11 = $query11->createCommand()->queryAll();

            $query2 = new Query();           
            $query2->select('subject_name')->from('cur_elective_subject')->Where(['coe_regulation_id'=>$model->coe_regulation_id, 'coe_batch_id'=>$coe_batch_id ])->andWhere(['degree_type'=>$model->degree_type]);;
            $pgmdata2 = $query2->createCommand()->queryAll();

            $subjmerge=array_merge($pgmdata11,$pgmdata2);
            $checksubject_name=0;
            foreach ($subjmerge as $value) 
            {
                $existname = str_replace(' ', '-', strtolower($value['subject_name']));
                $newname = str_replace(' ', '-', strtolower($model->subject_name));
                $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                if($existname==$newname)
                {
                    $checksubject_name=1;

                }
            }
            //echo $checksubject_name; exit;

            $query = new Query();           
            $query->select('subject_name')->from('cur_elective_subject')->where(['subject_code' =>$subject_code, 'coe_regulation_id'=>$model->coe_regulation_id])->andWhere(['!=','coe_batch_id',$coe_batch_id])->andWhere(['degree_type'=>$model->degree_type ]);

            $checkexname = $query->createCommand()->queryScalar();

            $ckexistname = str_replace(' ', '-', strtolower($checkexname));
            $newsubname = str_replace(' ', '-', strtolower($model->subject_name));
            $ckexistname=preg_replace('/[^A-Za-z\-]/', '',$ckexistname);
            $newsubname=preg_replace('/[^A-Za-z\-]/', '',$newsubname );
            $ckexitone=0;
            if($ckexistname!=$newsubname && $checkexname!='')
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Course Name Different From Previous Batch Please Change Course Code!");
                return $this->redirect(['create-minordeg']);
            }
            else if(empty($pgmdata) && empty($pgmdata1) && $checksubject_name==0)
            {
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();
                $model->coe_dept_id=$_POST['coe_dept_id'];
                $model->subject_type_id=$_POST['subject_type_id'];
                $model->subject_category_type_id=$_POST['subject_category_type_id']; 
                $model->coe_batch_id=$coe_batch_id;
                $model->coe_elective_option=203;
                $model->subject_code=$subject_code;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                if($model->save(false))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Course Added successfully..");
                        return $this->redirect(['minordeg-index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                    return $this->redirect(['create-minordeg']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Already Added Course! Please Check");
                return $this->redirect(['create-minordeg']);
            }
        } else {
            return $this->render('create-minordeg', [
                'model' => $model,
                'electivemodel'=>$electivemodel
            ]);
        }
    }
    
    protected function findModel($id)
    {
        if (($model = ElectiveSubject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
