<?php

namespace app\controllers;

use app\models\Electivetodept;
use app\models\ElectivetodeptSearch;
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
/**
 * ElectivetodeptController implements the CRUD actions for Electivetodept model.
 */
class ElectivetodeptController extends Controller
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
     * Lists all Electivetodept models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['coresubject-to-dept-existing']);
    }


    public function actionCoresubjectToDeptExisting()
    {
        $_SESSION['electiveoption']='Exist';
        $searchModel = new ElectivetodeptSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('coresubject_to_dept_existing', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

   
    public function actionCreatecoreExisting()
    {
        $model = new Electivetodept();
         $model1 = new ElectiveSubject();
        if(Yii::$app->request->post()) 
        {  
            
            //echo "<pre>" ;
            //print_r($model1->subject_code);exit();
            //echo  $newsyllbus=$_POST['subject_type_new'];
            //exit();
            if($_POST['subject_type_new']=='NEW' && ($_POST['subject_code_new']=='' || $_POST['subject_prefix_new']==''))
            { //exit();
                Yii::$app->ShowFlashMessages->setMsg('Error', "New Course Code Not empty! Please Check");
                    return $this->redirect(['createcore-existing']);
            }
            else
            {   //exit();
                $getSubcode =ElectiveSubject::find()->where(['subject_code'=>$_POST['subject_code']])->one();

                $model->subject_type_new=$_POST['subject_type_new']; //exit();
                $coe_dept_ids=$_POST['coe_dept_ids'];

                $subject_code_new='';

                $subject_code=$_POST['subject_code'];
                if(!empty($getSubcode))
                {
                    $model->coe_subject_id=$getSubcode['coe_elective_id'];
                }
                else
                {
                    $getSubcode =CurriculumSubject::find()->where(['subject_code'=>$_POST['subject_code']])->one(); 
                    $model->subject_code=$_POST['subject_code'];
                    $model->coe_subject_id=$getSubcode['coe_cur_id'];
                }

                if($_POST['subject_type_new']=='NEW')
                {
                    $model->subject_code=$_POST['subject_code'];
                    $subject_code_new=strtoupper($_POST['subject_prefix_new']).strtoupper($_POST['subject_code_new']);
                    $model->subject_code_new=$subject_code_new;
                }
                else
                {
                    $model->subject_code=$_POST['subject_code'];
                    $model->subject_code_new=$subject_code_new=$_POST['subject_code'];
                }

                //$nesubcode=$_POST['subject_code'];
                $query1 = new Query();           
                $query1->select('coe_cur_id')->from('cur_curriculum_subject')->where(['subject_code' =>$subject_code_new]);
                $pgmdata1 = $query1->createCommand()->queryAll();

                $query = new Query();           
                $query->select('coe_elective_id')->from('cur_elective_subject')->where(['subject_code' =>$subject_code_new]);
                $pgmdata = $query->createCommand()->queryAll();

                $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['coe_regulation_id'])->queryScalar();  

                if(empty($pgmdata) && empty($pgmdata1) && $_POST['subject_type_new']=='NEW')
                {            
                    $sem=0;
                    if(!empty($_POST['semester_e']))
                    {
                        $sem=$_POST['semester_e'];
                    }
                    $cur_vs_id=0;
                    if(!empty($_POST['cur_vs_id']))
                    {
                        $cur_vs_id=$_POST['cur_vs_id'];
                    }
                    
                    $model->cur_vs_id=$cur_vs_id;
                    $model->coe_batch_id=$coe_batch_id;
                    $created_at = date("Y-m-d H:i:s");
                    $model->degree_type=$_POST['degree_type'];
                    $model->coe_regulation_id=$_POST['coe_regulation_id'];
                    $model->coe_dept_id=$_POST['coe_dept_id'];
                    $model->semester=$sem;
                    $model->coe_elective_option=$_POST['coe_elective_option_e'];
                    $userid = Yii::$app->user->getId(); 
                    $model->coe_dept_ids=$coe_dept_ids;
                    $model->created_at=$created_at;
                    $model->created_by=$userid; 
                   //print_r($model); exit();
                    $model->save(false);
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Course Assigned successfully..");
                    return $this->redirect(['coresubject-to-dept-existing']);
                }
                else if($subject_code==$subject_code_new && $_POST['subject_type_new']=='EXIST')
                {         
                    $checkassigned = Yii::$app->db->createCommand("SELECT count(coe_electivetodept_id) FROM cur_electivetodept WHERE subject_code='".$_POST['subject_code']."' AND coe_dept_ids=".$_POST['coe_dept_ids'])->queryScalar();

                    $sem=0;
                    if(!empty($_POST['semester_e']))
                    {
                        $sem=$_POST['semester_e'];
                    }

                    if(!empty($_POST['semester_ee']))
                    {
                        $sem=$_POST['semester_ee'];
                    }
                    
                    $cur_vs_id=0;
                    if(!empty($_POST['cur_vs_id']))
                    {
                        $cur_vs_id=$_POST['cur_vs_id'];
                    }
                    
                    $model->cur_vs_id=$cur_vs_id;
                    
                    if($checkassigned==0)
                    {
                        $model->coe_batch_id=$coe_batch_id;
                         $created_at = date("Y-m-d H:i:s");
                        $model->degree_type=$_POST['degree_type'];
                        $model->coe_regulation_id=$_POST['coe_regulation_id'];
                        $model->coe_dept_id=$_POST['coe_dept_id'];
                        $model->semester=$sem;
                        $model->coe_elective_option=$_POST['coe_elective_option_e'];
                        $userid = Yii::$app->user->getId(); 
                        $model->coe_dept_ids=$coe_dept_ids;
                        $model->created_at=$created_at;
                        $model->created_by=$userid; 
                        //print_r($model); exit();
                        $model->save(false);
                        Yii::$app->ShowFlashMessages->setMsg('Success', "Course Assigned successfully..");
                        return $this->redirect(['coresubject-to-dept-existing']); //index
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error', "Course Code Already Assigned! Please Check");
                        return $this->redirect(['createcore-existing']);
                    }
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Course Code Already Exist! Please Check");
                    return $this->redirect(['createcore-existing']);
                }
            }
            
        } else { 
            return $this->render('createcore-existing', [
                'model1' => $model1,
                'model' => $model,
            ]);
        }
    }


    public function actionCoresubjectToDeptNew()
    {
        $_SESSION['electiveoption']='New';
        $searchModel = new ElectivetodeptSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);    
        return $this->render('coresubject_to_dept_new', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreatecoreNewsyllabus()
    {
        $model = new Electivetodept();
         $model1 = new ElectiveSubject();
        if($model1->load(Yii::$app->request->post())) 
        {  
            
            //echo "<pre>" ;
            //print_r($model1->subject_code);exit();
            $newsyllbus='NEWSYLLABUS';
            
            $subject_code=strtoupper($_POST['subject_prefix']).strtoupper($model1->subject_code);
            $model->subject_type_new=$newsyllbus; //exit();
            $coe_dept_ids=$_POST['coe_dept_ids'];//exit;  //implode(",",$model->coe_dept_ids); 

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$model1->coe_regulation_id)->queryScalar(); //exit;

            $query1 = new Query();           
            $query1->select('coe_cur_id')->from('cur_curriculum_subject')->where(['subject_code' =>$subject_code])->andWhere(['coe_regulation_id'=>$model1->coe_regulation_id ]);
            $pgmdata1 = $query1->createCommand()->queryAll();

            $query = new Query();           
            $query->select('coe_elective_id')->from('cur_elective_subject')->where(['subject_code' =>$subject_code])->andWhere(['coe_regulation_id'=>$model1->coe_regulation_id ]);
            $pgmdata = $query->createCommand()->queryAll();

            $query11 = new Query();           
            $query11->select('subject_name')->from('cur_curriculum_subject')->Where(['coe_regulation_id'=>$model1->coe_regulation_id ]);
            $pgmdata11 = $query11->createCommand()->queryAll();

            $query2 = new Query();           
            $query2->select('subject_name')->from('cur_elective_subject')->Where(['coe_regulation_id'=>$model1->coe_regulation_id ]);
            $pgmdata2 = $query2->createCommand()->queryAll();

            $subjmerge=array_merge($pgmdata11,$pgmdata2);
            $checksubject_name=0;
            foreach ($subjmerge as $value) 
            {
                $existname = str_replace(' ', '-', strtolower($value['subject_name']));
                $newname = str_replace(' ', '-', strtolower($model1->subject_name));
                $existname=preg_replace('/[^A-Za-z\-]/', '',$existname);
                $newname=preg_replace('/[^A-Za-z\-]/', '',$newname );
                if($existname==$newname)
                {
                    $checksubject_name=1;

                }
            }
            
            //echo $checksubject_name; exit();

            //print_r($pgmdata); exit;
            if(empty($pgmdata) && empty($pgmdata1) && $checksubject_name==0)
            {
               // $stream_id = Yii::$app->db->createCommand("SELECT A.cur_an_id FROM cur_aicte_norms A JOIN coe_category_type B ON B.description=A.stream_name WHERE B.coe_category_type_id=".$model1->coe_elective_option." AND coe_dept_id=".$_POST['coe_dept_id'])->queryScalar();
                $model1->stream_id=$model1->coe_elective_option; //exit;
                if(empty($model1->semester))
                {
                    $model1->semester=0;
                } 
                
                $v_stream=0;
                if($model1->coe_elective_option==192)
                {
                    $v_stream=$_POST['cur_vs_id'];
                }

                
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();
                $sem=$model1->semester;
                $model1->coe_batch_id=$coe_batch_id;
                $model1->degree_type=$_POST['degree_type'];
                $model1->coe_dept_id=$_POST['coe_dept_id'];
                $model1->subject_type_id=$_POST['subject_type_id'];
                $model1->subject_category_type_id=$_POST['subject_category_type_id']; 
                $model1->coe_batch_id=$coe_batch_id;
                $model1->cur_vs_id=$v_stream;
                $model1->service_paper=1;
                $model1->subject_code=$subject_code;
                $model1->created_at=$created_at;
                $model1->created_by=$userid;

                if($model1->save(false))
                {

                    $model = new Electivetodept();
                    $model->subject_code=$subject_code;
                    $model->degree_type=$_POST['degree_type'];
                    $model->coe_dept_id=$_POST['coe_dept_id'];
                    $model->coe_batch_id=$coe_batch_id;
                    $model->coe_regulation_id=$model1->coe_regulation_id;
                    $model->coe_elective_option=$model1->coe_elective_option;
                    $model->coe_subject_id=$model1->coe_elective_id;
                    $model->semester=$sem;                               
                    $model->coe_dept_ids=$coe_dept_ids;
                    $model->cur_vs_id=$v_stream;
                    $model->subject_type_new=$newsyllbus;
                    $model->subject_code_new=$subject_code;
                    $model->created_at=$created_at;
                    $model->created_by=$userid; 

                    //print_r($model); exit();
                    $model->save(false);
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Course Assigned successfully..");
                    return $this->redirect(['coresubject-to-dept-new']); //index
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                    return $this->redirect(['createcore-newsyllabus']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Course Code or Course Name Already Exist! Please Enter New One");
                return $this->redirect(['createcore-newsyllabus']);
            }
            
        
        } else { 
            return $this->render('createcore-newsyllabus', [
                'model1' => $model1,
                'model' => $model,
            ]);
        }
    }


    public function actionCoreExistingNewsyllabi()
    {
        $model = new Electivetodept();
         $model1 = new ElectiveSubject();
        if(Yii::$app->request->post()) 
        {  
            
             $coe_elective_option=$_POST['coe_elective_option_e'];

             $v_stream=0;
            if($coe_elective_option==192)
            {
                $v_stream=$_POST['cur_vs_id'];
            }

            if($coe_elective_option==192 && $v_stream==0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Please Choose Vertical Stream!");
                return $this->redirect(['core-existing-newsyllabi']);
            } 
            else 
            {   //exit();
                $getSubcode =ElectiveSubject::find()->where(['subject_code'=>$_POST['subject_code']])->one();

                $model->subject_type_new='NEWSYLLABUS'; 
                $coe_dept_ids=$_POST['coe_dept_ids'];

                $subject_code_new='';

                $subject_code=$_POST['subject_code'];
                if(!empty($getSubcode))
                {
                    $model->coe_subject_id=$getSubcode['coe_elective_id'];
                }
                else
                {
                    $getSubcode =CurriculumSubject::find()->where(['subject_code'=>$_POST['subject_code']])->one(); 
                    $model->subject_code=$_POST['subject_code'];
                    $model->coe_subject_id=$getSubcode['coe_cur_id'];
                }

                if($_POST['subject_code_new']=='')
                {                    
                   
                    $model->subject_code=$_POST['subject_code'];
                    $model->subject_code_new=$_POST['subject_code'];
                }
                else
                {
                    $subject_code_new=strtoupper($_POST['subject_prefix_new']).strtoupper($_POST['subject_code_new']);
                   
                    $model->subject_code=$_POST['subject_code'];
                    $model->subject_code_new=$subject_code_new;
                }
                    
               
                $query1 = new Query();           
                $query1->select('coe_cur_id')->from('cur_curriculum_subject')->where(['subject_code' =>$subject_code_new]);
                $pgmdata1 = $query1->createCommand()->queryAll();

                $query = new Query();           
                $query->select('coe_elective_id')->from('cur_elective_subject')->where(['subject_code' =>$subject_code_new]);
                $pgmdata = $query->createCommand()->queryAll();


                if(empty($pgmdata) && empty($pgmdata1))
                {            
                    $sem=0;
                    if(!empty($_POST['semester_e']))
                    {
                        $sem=$_POST['semester_e'];
                    }
                    $cur_vs_id=0;
                    if(!empty($_POST['cur_vs_id']))
                    {
                        $cur_vs_id=$_POST['cur_vs_id'];
                    }

                    $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['coe_regulation_id'])->queryScalar();
                    
                    $model->cur_vs_id=$cur_vs_id;

                    $created_at = date("Y-m-d H:i:s");
                    $model->degree_type='UG';//$_POST['degree_type'];
                    $model->coe_batch_id=$coe_batch_id;
                    $model->coe_regulation_id=$_POST['coe_regulation_id'];
                    $model->coe_dept_id=$_POST['coe_dept_id'];
                    $model->semester=$sem;
                    $model->coe_elective_option=$_POST['coe_elective_option_e'];
                    $userid = Yii::$app->user->getId(); 
                    $model->coe_dept_ids=$coe_dept_ids;
                    $model->created_at=$created_at;
                    $model->created_by=$userid; 
                    //print_r($model); exit();
                    $model->save(false);
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Course Assigned successfully..");
                    return $this->redirect(['coresubject-to-dept-new']); //index
                }
                else
                { //echo "string"; exit;
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Course Code Already Exist! Please Check");
                     return $this->redirect(['core-existing-newsyllabi']);
                }
            }
            
        } else { 
            return $this->render('createcore-newsyllabus-existing', [
                'model1' => $model1,
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Electivetodept model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->ShowFlashMessages->setMsg('Success', "Assigned Course Deleted successfully..");
        return $this->redirect(['index']);
    }

    public function actionDeletecore($id)
    {
        
        $subject_type_new = Yii::$app->db->createCommand("SELECT subject_type_new,coe_subject_id FROM  cur_electivetodept WHERE coe_electivetodept_id=".$id)->queryOne();

        if(!empty($subject_type_new))
        {            

            $this->findModel($id)->delete();
            
            Yii::$app->ShowFlashMessages->setMsg('Success', "Assigned Course Deleted successfully..");
            return $this->redirect(['coresubject-to-dept-existing']);

            
        }
        else
        {
            $this->findModel($id)->delete();
            return $this->redirect(['coresubject-to-dept']);
        }
        
    }

    public function actionDeletecorenew($id)
    {

        $subject_type_new = Yii::$app->db->createCommand("SELECT subject_type_new,coe_subject_id FROM  cur_electivetodept WHERE coe_electivetodept_id=".$id)->queryOne();

        if(!empty($subject_type_new))
        {            

            $this->findModel($id)->delete();
            
            Yii::$app->ShowFlashMessages->setMsg('Success', "Assigned Course Deleted successfully..");
            return $this->redirect(['coresubject-to-dept-new']);

            
        }
        else
        {
            $this->findModel($id)->delete();
            return $this->redirect(['coresubject-to-dept']);
        }
        
    }


    /**
     * Finds the Electivetodept model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Electivetodept the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Electivetodept::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionNewSyllabusIndex()
    {
        $searchModel = new ElectiveSubjectSearch();
        $_SESSION['electsubject']='200';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('new-syllabus-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleteCorenewelective($id) 
    {
        $checkelective = Yii::$app->db->createCommand("SELECT count(A.coe_electivetodept_id) FROM cur_electivetodept A JOIN cur_elective_subject B ON B.coe_elective_id=A.coe_subject_id WHERE A.coe_subject_id=".$id." AND A.coe_elective_option=200")->queryScalar();

        $checksubject = Yii::$app->db->createCommand("SELECT A.subject_code FROM cur_electivetodept A JOIN cur_elective_subject B ON B.coe_elective_id=A.coe_subject_id WHERE A.coe_subject_id=".$id)->queryScalar();

        $checksyllabus = Yii::$app->db->createCommand("SELECT count(*) FROM  cur_syllabus WHERE subject_code='".$checksubject."'")->queryScalar();

        if($checkelective==0 && $checksyllabus ==0)
        {
            $deletedcore=Yii::$app->db->createCommand('DELETE FROM cur_elective_subject where coe_elective_id='.$id.' and service_paper=1 and coe_elective_option IN (200,201)')->execute();

            if($deletedcore)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Successfully deleted");
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Delete Not Successfully Please Check");
            }

            return $this->redirect(['index']);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Course can't Delete! Course Assigned to other Dept. or Syllabus Added! Please Check");

            return $this->redirect(['index']);
        }
    }
}
