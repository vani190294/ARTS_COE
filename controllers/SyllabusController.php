<?php

namespace app\controllers;

use Yii;
use app\models\CurSyllabus;
use app\models\CurSyllabusSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CurriculumSubject;
use app\models\ElectiveSubject;
use app\models\CurLabComponent;
use app\models\CurCourseArticulationMatrix;
use app\models\CurCourseArticulationMatrixService;
use kartik\mpdf\Pdf;
use app\models\SyllabusExisting;
use app\models\SyllabusExistingSearch;
use yii\db\Query;
/**
 * SyllabusController implements the CRUD actions for CurSyllabus model.
 */
class SyllabusController extends Controller
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
     * Lists all CurSyllabus models.
     * @return mixed
     */
    public function actionIndex()
    {
        $_SESSION['servicesubject']='All';
        $searchModel = new CurSyllabusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExistingIndex()
    {
        $_SESSION['mapping']=1;
        $searchModel = new SyllabusExistingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('existing-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPreIndex()
    {
        $_SESSION['mapping']=2;
        $searchModel = new SyllabusExistingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('premapping-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreatePremapping()
    {
        $model = new SyllabusExisting();

        if (Yii::$app->request->post()) 
        {
           
            $success=0;
           
            $query = new Query();           
            $query->select('from_regulation_id')->from('cur_syllabus_existing')->where(['from_regulation_id' =>$_POST['from_regulation_id'], 'from_subject_code' =>$_POST['from_subject_code'],'mapping_type'=>2]);
            $SyllabusExistingcheck = $query->createCommand()->queryAll();           
            
            if(empty($SyllabusExistingcheck))
            {
                $query = new Query();           
                $query->select('degree_type')->from('cur_syllabus_existing')->where(['to_subject_code' =>$_POST['from_subject_code']]);
                $degree_type = $query->createCommand()->queryScalar(); 

                $model = new SyllabusExisting();
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();  

                $prerequisties=implode(",", $_POST['to_subject_code']);

                $from_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['from_regulation_id'])->queryScalar();  

                $model->from_batch_id=$from_batch_id;
                $model->degree_type=$degree_type;          
                $model->coe_dept_id=$_POST['coe_dept_id'];
                $model->from_regulation_id=$_POST['from_regulation_id'];
                $model->from_subject_code=$_POST['from_subject_code'];
                $model->to_regulation_id=$_POST['from_regulation_id'];
                $model->to_subject_code=$prerequisties;
                $model->mapping_type=2;
                $model->created_at=$created_at; 
                $model->created_by=$userid;

                if($model->save(false))
                {
                     Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Mapped successfully..");
                    return $this->redirect(['pre-index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Syllabus Not Mapped successful! Please Check");
                    return $this->redirect(['pre-index']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Syllabus Already Mapped or Syllabus Already Mapped! Please Check");
                return $this->redirect(['pre-index']);
            }
        
            
        } else {
            return $this->render('create-premapping', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeletePreMap($id)
    { 
        $checksyllabus = Yii::$app->db->createCommand("SELECT count(cur_se_id) FROM cur_syllabus_existing WHERE cur_se_id='".$id."' AND approve_status=1")->queryScalar();

        if($checksyllabus==0)
        {
            $delete_dummy=Yii::$app->db->createCommand('DELETE FROM cur_syllabus_existing WHERE cur_se_id="'.$id.'"')->execute();

            Yii::$app->ShowFlashMessages->setMsg('Success', "Prerequisties Deleted successfully! Please Check");
            return $this->redirect(['pre-index']);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Prerequisties can't Delete! Please Check");
            return $this->redirect(['pre-index']);
        }
        
    }

    public function actionCreateExisting()
    {
        $model = new SyllabusExisting();

        if (Yii::$app->request->post()) 
        {
           
            $success=0;
           
            $query = new Query();           
            $query->select('*')->from('cur_syllabus_existing')->where(['from_regulation_id' =>$_POST['from_regulation_id'],'to_regulation_id' =>$_POST['to_regulation_id'], 'to_subject_code' =>$_POST['to_subject_code'],'mapping_type'=>1]);
            $SyllabusExistingcheck = $query->createCommand()->queryAll();

            $query = new Query();           
            $query->select('coe_regulation_id')->from('cur_syllabus')->where(['coe_regulation_id' =>$_POST['to_regulation_id'], 'subject_code' =>$_POST['to_subject_code']]);
            $Syllabuscheck = $query->createCommand()->queryAll();
            //print_r($SyllabusExistingcheck); exit;
            if(empty($SyllabusExistingcheck) && empty($Syllabuscheck))
            {
                $query = new Query();           
                $query->select('degree_type')->from('cur_syllabus')->where(['coe_regulation_id' =>$_POST['from_regulation_id'], 'subject_code' =>$_POST['from_subject_code']]);
                $degree_type = $query->createCommand()->queryScalar(); 

                $model = new SyllabusExisting();
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();  

                $from_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['from_regulation_id'])->queryScalar(); 

                $to_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['to_regulation_id'])->queryScalar();   

                $model->degree_type=$degree_type;          
                $model->coe_dept_id=$_POST['coe_dept_id'];
                $model->from_batch_id=$from_batch_id;
                $model->to_batch_id=$to_batch_id;
                $model->from_regulation_id=$_POST['from_regulation_id'];
                $model->from_subject_code=$_POST['from_subject_code'];
                $model->to_regulation_id=$_POST['to_regulation_id'];
                $model->to_subject_code=$_POST['to_subject_code'];
                $model->mapping_type=1;
                $model->created_at=$created_at; 
                $model->created_by=$userid;

                if($model->save(false))
                {
                     Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Assigned successfully..");
                    return $this->redirect(['existing-index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Syllabus Not Assigned successful! Please Check");
                    return $this->redirect(['existing-index']);
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Syllabus Already Assigned or Syllabus Already Created! Please Check");
                return $this->redirect(['existing-index']);
            }
        
            
        } else {
            return $this->render('create-existingassign', [
                'model' => $model,
            ]);
        }
    }
   
    public function actionDeleteSe($id)
    { 
        $checksyllabus = Yii::$app->db->createCommand("SELECT count(cur_se_id) FROM cur_syllabus_existing WHERE cur_se_id='".$id."' AND approve_status=1")->queryScalar();

        if($checksyllabus==0)
        {
            $delete_dummy=Yii::$app->db->createCommand('DELETE FROM cur_syllabus_existing WHERE cur_se_id="'.$id.'"')->execute();

            Yii::$app->ShowFlashMessages->setMsg('Success', "Course Deleted successfully! Please Check");
            return $this->redirect(['existing-index']);
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Course can't Delete! Please Check");
            return $this->redirect(['existing-index']);
        }
        
    }


    public function actionCreate()
    {
        $model = new CurSyllabus();
        $curmodel = new CurriculumSubject();
        if (Yii::$app->request->post()) 
        {

            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId(); 

             $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['CurSyllabus']['coe_regulation_id'])->queryScalar();

             $degree_type = Yii::$app->db->createCommand("SELECT degree_type FROM cur_curriculum_subject WHERE subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

            $checksyllabus = Yii::$app->db->createCommand("SELECT count(coe_dept_id) FROM cur_syllabus WHERE subject_code='". $_POST['CurSyllabus']['subject_code']."' AND coe_regulation_id=".$_POST['CurSyllabus']['coe_regulation_id']." AND coe_batch_id=". $coe_batch_id)->queryScalar(); 

            if($checksyllabus==0)
            {
               
                $subject_category_type = Yii::$app->db->createCommand("SELECT subject_category_type_id FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

                $subject_type = Yii::$app->db->createCommand("SELECT subject_type_id FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar(); 

                $internal_mark = Yii::$app->db->createCommand("SELECT internal_mark FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();                

                $coe_dept_id1 = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_curriculum_subject WHERE subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

                $elcetive=0; $elective_option='';
                if(empty($subject_category_type))
                {
                    $subject_category_type = Yii::$app->db->createCommand("SELECT subject_category_type_id FROM cur_elective_subject WHERE coe_batch_id=". $coe_batch_id." AND coe_dept_id=". $_POST['coe_dept_id']." AND  subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

                    $elective_option = Yii::$app->db->createCommand("SELECT coe_elective_option FROM cur_elective_subject WHERE coe_batch_id=". $coe_batch_id." AND coe_dept_id=". $_POST['coe_dept_id']." AND  subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

                     $elcetive=1;

                     $degree_type = Yii::$app->db->createCommand("SELECT degree_type FROM cur_elective_subject WHERE subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

                     $coe_dept_id1 = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_elective_subject WHERE subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

                     $internal_mark = Yii::$app->db->createCommand("SELECT internal_mark FROM cur_elective_subject WHERE coe_batch_id=". $coe_batch_id." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();
                }

                $prerequisties='';
                
                if(!empty($_POST['prerequisties']) && isset($_POST['prerequisties']))
                {
                    $prerequisties=implode(",", $_POST['prerequisties']);
                }
                else if($prerequisties=='')
                {
                    $prerequisties='Nil';
                }
                //echo $prerequisties; exit;
                $model->degree_type=$degree_type;
                $model->prerequisties=$prerequisties;
                $model->coe_regulation_id=$_POST['CurSyllabus']['coe_regulation_id'];
                $model->coe_batch_id=$coe_batch_id;            
                $model->coe_dept_id=$coe_dept_id1;
                $model->semester=0;//$_POST['CurSyllabus']['semester'];
                $model->subject_code=$_POST['CurSyllabus']['subject_code'];
                $model->subject_type=($elcetive==1)?2:1;

                if($degree_type=='MBA')
                {
                    $model->course_description=$_POST['course_description'];
                }
                else
                {
                    $model->course_description='';
                }
                
                $course_objectives=$_POST['course_objectives'];

                if(count($course_objectives)>0)
                {
                    $l=1;
                    for ($i=0; $i <count($course_objectives) ; $i++) 
                    { 
                        $cobj='course_objectives'.$l;
                        $model->$cobj=$course_objectives[$i];
                        $l++;
                    }
                }

                $course_outcomes=$_POST['course_outcomes'];

                if(count($course_outcomes)>0)
                {
                    $l=1;
                    for ($i=0; $i <count($course_outcomes) ; $i++) 
                    { 
                        $cobj='course_outcomes'.$l;
                        $model->$cobj=$course_outcomes[$i];
                        $l++;
                    }
                }

                $rpt=$_POST['rpt'];

                if(count($rpt)>0)
                {
                    $l=1;
                    for ($i=0; $i <count($rpt) ; $i++) 
                    { 
                        $cobj='rpt'.$l;
                        $model->$cobj=$rpt[$i];
                        $l++;
                    }
                }

                $module_title=$_POST['module_title'];

                if(count($module_title)>0)
                {
                    $l=1;
                    for ($i=0; $i <count($module_title) ; $i++) 
                    { 
                        $cobj='module_title'.$l;
                        $model->$cobj=$module_title[$i];
                        $l++;
                    }
                }

                $module_hr=$_POST['module_hr'];

                if(count($module_hr)>0)
                {
                    $l=1;
                    for ($i=0; $i <count($module_hr) ; $i++) 
                    { 
                        $cobj='module_hr'.$l;
                        $model->$cobj=$module_hr[$i];
                        $l++;
                    }
                }

                $cource_content_mod=$_POST['cource_content_mod'];
                //print_r($cource_content_mod); exit();
                if(count($cource_content_mod)>0)
                {
                    $l=1;
                    for ($i=0; $i <count($cource_content_mod) ; $i++) 
                    { 
                        $cobj='cource_content_mod'.$l;
                        $model->$cobj=$cource_content_mod[$i];
                        $l++;
                    }
                }

                $text_book=$_POST['text_book'];

                if(count($text_book)>0)
                {
                    $l=1;
                    for ($i=0; $i <count($text_book) ; $i++) 
                    { 
                        $cobj='text_book'.$l;
                        $model->$cobj=$text_book[$i];
                        $l++;
                    }
                }

                $web_reference=$_POST['web_reference'];

                if(count($web_reference)>0)
                {
                    $l=1;
                    for ($i=0; $i <count($web_reference) ; $i++) 
                    { 
                        $cobj='web_reference'.$l;
                        $model->$cobj=$web_reference[$i];
                        $l++;
                    }
                }

                $reference_book=$_POST['reference_book'];

                if(count($reference_book)>0)
                {
                    $l=1;
                    for ($i=0; $i <count($reference_book) ; $i++) 
                    { 
                        $cobj='reference_book'.$l;
                        $model->$cobj=$reference_book[$i];
                        $l++;
                    }
                }

                $online_reference=$_POST['online_reference'];

                if(count($online_reference)>0)
                {
                    $l=1;
                    for ($i=0; $i <count($online_reference) ; $i++) 
                    { 
                        $cobj='online_reference'.$l;
                        $model->$cobj=$online_reference[$i];
                        $l++;
                    }
                }

                $model->created_at=$created_at;
                $model->created_by=$userid;
                
            
                if($model->save(false))
                {
                    if((($subject_category_type>= 140 && 142 >=$subject_category_type) || $subject_category_type==144) || ($subject_type==10 && $internal_mark !=100 ))
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                        return $this->redirect(['labcomponet', 'id' => $model->cur_syllabus_id]);
                    }
                    else
                    {
                        if($elective_option==191)
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                            return $this->redirect(['view', 'id' => $model->cur_syllabus_id]);
                        }
                        else
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                            return $this->redirect(['coursearticulationmatrix', 'id' => $model->cur_syllabus_id]);
                        }
                        
                    }
                    
                }
                else
                {
                    if((($subject_category_type>= 140 && 142 >=$subject_category_type) || $subject_category_type==144)  || ($subject_type==10 && $internal_mark !=100 ))
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added Already..");
                        return $this->redirect(['labcomponet', 'id' => $model->cur_syllabus_id]);
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added Already..");
                        return $this->redirect(['coursearticulationmatrix', 'id' => $model->cur_syllabus_id]);
                    }
                }
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Syllabus Added Already or Something Error Please Check");
                return $this->redirect(['create']);
            }
            
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,'curmodel'=>$curmodel
            ]);
        }
    }


    public function actionLabcomponet($id)
    {
        $model = new CurSyllabus();
        $labmodel = new CurLabComponent();

        $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();

        if(empty($codatalist))
        {
            $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();
        }


        if(Yii::$app->request->post()) 
        {
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();

            $experiment_titlecount=$_POST['experiment_title'];
            //$cource_outcome=$_POST['cource_outcome'];
            $rpt=$_POST['rpt'];
            $Error=$success=0;
            //print_r(count($experiment_title)); exit();
            if(count($experiment_titlecount)>0)
            {
                $experiment=1;
                for ($i=0; $i <count($experiment_titlecount) ; $i++) 
                { 
                     $cotitle='cource_outcome'.$experiment;
                     $cource_outcome=$cotitle;
                     
                    $co=implode(",",$_POST[$cource_outcome]);

                    if($experiment_titlecount[$i]!='')
                    {
                        $experiment_title=trim($experiment_titlecount[$i],"'");
                        $experiment_title = nl2br($experiment_title);

                        //echo 'SELECT * FROM cur_lab_component WHERE experiment_title="'.$experiment_title.'" AND cur_syllabus_id="'.$id.'" AND cource_outcome="'.$co.'"'; exit;
                        $cheklab = Yii::$app->db->createCommand('SELECT * FROM cur_lab_component WHERE experiment_title="'.$experiment_title.'" AND cur_syllabus_id="'.$id.'" AND cource_outcome="'.$co.'"')->queryAll();
                        //print_r(count($cheklab)); exit();
                        if(empty($cheklab))
                        {
                            //print_r($cource_outcome); exit();
                            $labcomponetmodel= new CurLabComponent();
                            $labcomponetmodel->experiment_title=$experiment_title;
                            $labcomponetmodel->cource_outcome=$co;
                            $labcomponetmodel->rpt=$rpt[$i];                    
                            $labcomponetmodel->cur_syllabus_id=$id;
                            $labcomponetmodel->created_at=$created_at;
                            $labcomponetmodel->created_by=$userid;

                            if($labcomponetmodel->save(false))
                            {
                                $success++;
                            }
                            else
                            {
                                $Error++;
                            }
                        }
                        else
                        {
                             $success++;
                        }
                    } 
                    else
                    {
                        //ECHO $experiment_titlecount[$i]; exit;
                        $Error++;
                    } 
                       $experiment++;

                }
            }

            //echo $success."err".$Error; exit;

            if($success>0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Lab Component Added successfully..");
               
                return $this->redirect(['coursearticulationmatrix', 'id' => $id]);
                
            }
            else
            {
                 Yii::$app->ShowFlashMessages->setMsg('Success', "Success ".$success." Error".$Error." Lab Component not insert successfully..");
                return $this->render('labcomponet', [
                'model' => $model,
                'labmodel'=>$labmodel,
                'cur_syllabus_id'=>$id,
                'codatalist'=>$codatalist
                ]);
            }
        }
        else
        {
             return $this->render('labcomponet', [
            'model' => $model,
            'labmodel'=>$labmodel,
            'cur_syllabus_id'=>$id,
            'codatalist'=>$codatalist
            ]);
        }
       
    }

    public function actionCoursearticulationmatrix($id)
    {
        $model = new CurSyllabus();
        $labmodel = new CurLabComponent();
        $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();

        if(empty($codatalist))
        {
            $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();
        }

         $coe_dept_id = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_syllabus WHERE cur_syllabus_id=".$id)->queryScalar();

        $colablist = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE cur_syllabus_id=".$id)->queryAll();

            return $this->render('course_articulation_matrix', [
                'model' => $model,
                'labmodel'=>$labmodel,
                'cur_syllabus_id'=>$id,
                'codatalist'=>$codatalist,
                'colablist'=>$colablist,
                'coe_dept_id'=>$coe_dept_id
            ]);
    }

    public function actionView($id)
    {
        $model = new CurSyllabus();
        $labmodel = new CurLabComponent();
        

        if (Yii::$app->request->post()) 
        {
            
            $shdept = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_syllabus WHERE cur_syllabus_id=".$id)->queryScalar();

            if($shdept==8)
            {
                Yii::$app->db->createCommand("UPDATE cur_course_service_matrix SET approve_status  ='1' WHERE cur_syllabus_id='" . $id . "'")->execute();
            }
            else
            {
                Yii::$app->db->createCommand("UPDATE cur_course_articulation_matrix SET approve_status  ='1' WHERE cur_syllabus_id='" . $id . "'")->execute();
            }
            
            Yii::$app->db->createCommand("UPDATE cur_syllabus SET approve_status  ='1' WHERE cur_syllabus_id='" . $id . "'")->execute();

            Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Approved successfully..");
            
            return $this->redirect(['view', 'id' => $id]);

        }
        else
        {
            $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type,A.approve_status  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();

            $elective_option='';

            if(empty($codatalist))
            {
                $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type, B.coe_elective_option, A.approve_status  FROM cur_syllabus A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();
                $elective_option=$codatalist['coe_elective_option'];
            }

            $colablist = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE cur_syllabus_id=".$id)->queryAll();

            $co_matrix = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_syllabus_id=".$id)->queryAll();

            $coe_regulation_id = Yii::$app->db->createCommand("SELECT coe_regulation_id FROM cur_syllabus WHERE cur_syllabus_id=".$id)->queryScalar();

            $coe_dept_id = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_syllabus WHERE cur_syllabus_id=".$id)->queryScalar();

            $regulation_year = Yii::$app->db->createCommand("SELECT concat('Regulation: ',A.regulation_year,' (Batch: ',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE coe_regulation_id =".$coe_regulation_id)->queryScalar();

            return $this->render('view', [
                 'model' => $model,
                    'labmodel'=>$labmodel,
                    'cur_syllabus_id'=>$id,
                    'codatalist'=>$codatalist,
                    'colablist'=>$colablist,
                    'co_matrix'=>$co_matrix,
                    'elective_option'=>$elective_option,
                    'regulation_year'=>$regulation_year,
                    'coe_dept_id'=>$coe_dept_id
            ]);
        }


    }


    public function actionSyllabusPdf()
    {
        
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
        $content = $_SESSION['SyllabusPdf'];        
        $Syllabussubject=$_SESSION['Syllabussubject'];

            $pdf = new Pdf([
               
                'mode' => Pdf::MODE_UTF8,
                'filename' => $Syllabussubject.'_syllabus.pdf',                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_PORTRAIT,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content,  
                //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => ' @media all{
                        table{border-collapse: collapse; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width:100%;  border:1px solid #000; }

                        table td{
                           border:1px solid #000;
                            font-size: 15px !important; 
                            padding: 5px !important; 
                            text-align: justify;
                            text-justify: inter-word;
                        }
                        table th{
                            font-size: 11px !important; 
                            text-align: left;
                            padding: 5px !important; 
                            border:1px solid #000;
                            
                        }
                    }   
                ', 
                'options' => ['title' => $Syllabussubject.' Syllabus'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>[strtoupper($Syllabussubject).' SYLLABUS PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ]
            ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render(); 
    }

    /**
     * Updates an existing CurSyllabus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //print_r($model); exit();
         if (Yii::$app->request->post()) 
        {

            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId(); 

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['CurSyllabus']['coe_regulation_id'])->queryScalar();

            $subject_category_type = Yii::$app->db->createCommand("SELECT subject_category_type_id FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

             $subject_type = Yii::$app->db->createCommand("SELECT subject_type_id FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

             $degree_type = Yii::$app->db->createCommand("SELECT degree_type FROM cur_curriculum_subject WHERE subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

            $coe_dept_id1 = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_curriculum_subject WHERE subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

            $internal_mark = Yii::$app->db->createCommand("SELECT internal_mark FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

             if(empty($subject_type))
            {
                 $subject_category_type = Yii::$app->db->createCommand("SELECT subject_category_type_id FROM cur_elective_subject WHERE coe_batch_id=". $coe_batch_id." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

                $subject_type = Yii::$app->db->createCommand("SELECT subject_type_id FROM cur_elective_subject WHERE coe_batch_id=". $coe_batch_id." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

                $degree_type = Yii::$app->db->createCommand("SELECT degree_type FROM cur_elective_subject WHERE subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

                $coe_dept_id1 = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_elective_subject WHERE subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

                $internal_mark = Yii::$app->db->createCommand("SELECT internal_mark FROM cur_elective_subject WHERE coe_batch_id=". $coe_batch_id." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();
            }
            
            $prerequisties='';
            
            if(!empty($_POST['prerequisties']) && isset($_POST['prerequisties']))
            {
                $prerequisties=implode(",", $_POST['prerequisties']);
            }
            if($prerequisties=='')
            {
                $prerequisties='Nil';
            }

            $model->degree_type=$degree_type;
            $model->prerequisties=$prerequisties;
            $model->coe_regulation_id=$_POST['CurSyllabus']['coe_regulation_id'];
            $model->coe_batch_id=$coe_batch_id;
            $model->coe_dept_id=$coe_dept_id1;
            $model->semester=0; //$_POST['CurSyllabus']['semester'];
            $model->subject_code=$_POST['CurSyllabus']['subject_code'];
            $model->subject_type=1;

            $course_objectives=$_POST['course_objectives'];

            if(count($course_objectives)>0)
            {
                $l=1;
                for ($i=0; $i <count($course_objectives) ; $i++) 
                { 
                    $cobj='course_objectives'.$l;
                    $model->$cobj=$course_objectives[$i];
                    $l++;
                }
            }

            $course_outcomes=$_POST['course_outcomes'];

            if(count($course_outcomes)>0)
            {
                $l=1;
                for ($i=0; $i <count($course_outcomes) ; $i++) 
                { 
                    $cobj='course_outcomes'.$l;
                    $model->$cobj=$course_outcomes[$i];
                    $l++;
                }
            }

            $rpt=$_POST['rpt'];

            if(count($rpt)>0)
            {
                $l=1;
                for ($i=0; $i <count($rpt) ; $i++) 
                { 
                    $cobj='rpt'.$l;
                    $model->$cobj=$rpt[$i];
                    $l++;
                }
            }

            $module_title=$_POST['module_title'];

            if(count($module_title)>0)
            {
                $l=1;
                for ($i=0; $i <count($module_title) ; $i++) 
                { 
                    $cobj='module_title'.$l;
                    $model->$cobj=$module_title[$i];
                    $l++;
                }
            }

            $module_hr=$_POST['module_hr'];

            if(count($module_hr)>0)
            {
                $l=1;
                for ($i=0; $i <count($module_hr) ; $i++) 
                { 
                    $cobj='module_hr'.$l;
                    $model->$cobj=$module_hr[$i];
                    $l++;
                }
            }

            $cource_content_mod=$_POST['cource_content_mod'];

            if(count($cource_content_mod)>0)
            {
                $l=1;
                for ($i=0; $i <count($cource_content_mod) ; $i++) 
                { 
                    $cobj='cource_content_mod'.$l;
                    $model->$cobj=$cource_content_mod[$i];
                    $l++;
                }
            }

            $text_book=$_POST['text_book'];

            if(count($text_book)>0)
            {
                $l=1;
                for ($i=0; $i <count($text_book) ; $i++) 
                { 
                    $cobj='text_book'.$l;
                    $model->$cobj=$text_book[$i];
                    $l++;
                }
            }

            $web_reference=$_POST['web_reference'];

            if(count($web_reference)>0)
            {
                $l=1;
                for ($i=0; $i <count($web_reference) ; $i++) 
                { 
                    $cobj='web_reference'.$l;
                    $model->$cobj=$web_reference[$i];
                    $l++;
                }
            }

            $reference_book=$_POST['reference_book'];

            if(count($reference_book)>0)
            {
                $l=1;
                for ($i=0; $i <count($reference_book) ; $i++) 
                { 
                    $cobj='reference_book'.$l;
                    $model->$cobj=$reference_book[$i];
                    $l++;
                }
            }

            $online_reference=$_POST['online_reference'];

            if(count($online_reference)>0 && !empty($_POST['online_reference']))
            {
                //print_r($online_reference); exit();
                $l=1;
                for ($i=0; $i <count($online_reference) ; $i++) 
                { 
                    if($online_reference[$i]!='')
                    {
                        $cobj='online_reference'.$l;
                        $model->$cobj=$online_reference[$i];
                         $l++;
                    }
                    
                   
                }
            }

            $model->created_at=$created_at;
            $model->created_by=$userid;
            
            //echo $subject_type; exit;
            if($model->save(false))
            {
                if($subject_category_type==144 && $subject_type==123)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                    return $this->redirect(['view', 'id' => $model->cur_syllabus_id]);
                }
                else if((($subject_category_type>= 140 && 142 >=$subject_category_type) || $subject_category_type==144) || ($subject_type==10 && $internal_mark !=100))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                    return $this->redirect(['labcomponetupdate', 'id' => $model->cur_syllabus_id]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                    return $this->redirect(['view', 'id' => $model->cur_syllabus_id]);
                }
                
            }
            else
            {
                if($subject_category_type==144 && $subject_type==123)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                    return $this->redirect(['view', 'id' => $model->cur_syllabus_id]);
                }
                else if((($subject_category_type>= 140 && 142 >=$subject_category_type) || $subject_category_type==144) || ($subject_type==10 && $internal_mark !=100))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added Already..");
                    return $this->redirect(['labcomponetupdate', 'id' => $model->cur_syllabus_id]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added Already..");
                    return $this->redirect(['view', 'id' => $model->cur_syllabus_id]);
                }
            }
            
            
        } 
        else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionLabcomponetupdate($id)
    {
        $model = new CurSyllabus();
        $labmodel = new CurLabComponent(); //CurLabComponent::findOne($id);
        //print_r($labmodel); exit;
        $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();

        if(empty($codatalist))
        {
            $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();
        }

        $cheklabdata = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE cur_syllabus_id=".$id."")->queryAll();
        //print_r($codatalist); exit;
        if(Yii::$app->request->post()) 
        {
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();

            $experiment_id=$_POST['experiment_id'];
            $experiment_titlecount=$_POST['experiment_title'];
            //$cource_outcome=$_POST['cource_outcome'];
            $rpt=$_POST['rpt'];
            $Error=$success=0;
            //print_r($experiment_id); exit();
            if(count($experiment_titlecount)>0)
            {
                $experiment=1;
                
                for ($i=0; $i <count($experiment_titlecount) ; $i++) 
                { 
                    //echo $i;
                    if($i<count($experiment_id) && $experiment_id[0]!='')
                    {
                        //$cheklab = Yii::$app->db->createCommand("SELECT cur_labcomp_id FROM cur_lab_component WHERE cur_syllabus_id=".$id."")->queryScalar();
                         $cotitle='cource_outcome'.$experiment;
                        $cource_outcome=$cotitle;
                      
                        $co=implode(",",$_POST[$cource_outcome]); //exit;

                        if($experiment_id[$i]!='')
                        {
                            $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE cur_labcomp_id!=".$experiment_id[$i])->queryAll();
                            //print_r($experiment_id); //exit;
                            if(!empty($cheklab))
                            {
                                $experiment_title = nl2br($experiment_titlecount[$i]);

                                $labcomponetmodel= CurLabComponent::findOne($experiment_id[$i]);
                                $labcomponetmodel->experiment_title=$experiment_title;
                                $labcomponetmodel->cource_outcome=$co;
                                $labcomponetmodel->rpt=$rpt[$i];                    
                                $labcomponetmodel->cur_syllabus_id=$id;
                                $labcomponetmodel->updated_at=$created_at;
                                $labcomponetmodel->updated_by=$userid;

                                if($labcomponetmodel->save(false))
                                {
                                    $success++;
                                }
                                else
                                {
                                    $Error++;
                                }
                            }
                        }
                        else
                        {
                            //print_r("2");
                            $cotitle='cource_outcome'.$experiment;
                            $cource_outcome=$cotitle;
                            $co=implode(",",$_POST[$cource_outcome]);

                            if($experiment_titlecount[$i]!='')
                            {
                                $experiment_title = nl2br($experiment_titlecount[$i]);

                                $cheklab = Yii::$app->db->createCommand('SELECT * FROM cur_lab_component WHERE experiment_title="'.$experiment_title.'" AND cur_syllabus_id="'.$id.'" AND cource_outcome="'.$co.'"')->queryAll();

                                //print_r($cheklab); exit;
                                if(empty($cheklab))
                                {

                                    $labcomponetmodel= new CurLabComponent();
                                    $labcomponetmodel->experiment_title=$experiment_title;
                                    $labcomponetmodel->cource_outcome=$co;
                                    $labcomponetmodel->rpt=$rpt[$i];                    
                                    $labcomponetmodel->cur_syllabus_id=$id;
                                    $labcomponetmodel->created_at=$created_at;
                                    $labcomponetmodel->created_by=$userid;
                                    //print_r($labcomponetmodel); exit;
                                    if($labcomponetmodel->save(false))
                                    {
                                        $success++;
                                    }
                                    else
                                    {
                                        $Error++;
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        $cotitle='cource_outcome'.$experiment;
                        $cource_outcome=$cotitle;
                        $co=implode(",",$_POST[$cource_outcome]);

                        if($experiment_titlecount[$i]!='')
                        {
                            $experiment_title = nl2br($experiment_titlecount[$i]);

                            $cheklab = Yii::$app->db->createCommand('SELECT * FROM cur_lab_component WHERE experiment_title="'.$experiment_title.'" AND cur_syllabus_id="'.$id.'" AND cource_outcome="'.$co.'"')->queryAll();

                            if(empty($cheklab))
                            {

                                $labcomponetmodel= new CurLabComponent();
                                $labcomponetmodel->experiment_title=$experiment_title;
                                $labcomponetmodel->cource_outcome=$co;
                                $labcomponetmodel->rpt=$rpt[$i];                    
                                $labcomponetmodel->cur_syllabus_id=$id;
                                $labcomponetmodel->created_at=$created_at;
                                $labcomponetmodel->created_by=$userid;
                                //print_r($labcomponetmodel); exit;
                                if($labcomponetmodel->save(false))
                                {
                                    $success++;
                                }
                                else
                                {
                                    $Error++;
                                }
                            }
                        }
                    }
                   
                   $experiment++;
                }
            }

             //exit;

            if($success>0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Lab Component updated successfully..");
               
                return $this->redirect(['view', 'id' => $id]);
                
            }
            else
            {
                 Yii::$app->ShowFlashMessages->setMsg('Success', "Success ".$success." Error".$Error." Lab Component not updated successfully..");
                return $this->render('labcomponetupdate', [
                'model' => $model,
                'labmodel'=>$labmodel,
                'cur_syllabus_id'=>$id,
                'codatalist'=>$codatalist,
                'cheklabdata'=>$cheklabdata
                ]);
            }
        }
        else
        {
             return $this->render('labcomponetupdate', [
            'model' => $model,
            'labmodel'=>$labmodel,
            'cur_syllabus_id'=>$id,
            'codatalist'=>$codatalist,
            'cheklabdata'=>$cheklabdata
            ]);
        }
       
    }

    public function actionCoursearticulationmatrixupdate($id)
    {
        $model = new CurSyllabus();
        $labmodel = new CurLabComponent();
        $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type, F.no_of_pso  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_dept_pso F ON F.coe_dept_id=A.coe_dept_id  WHERE cur_syllabus_id=".$id)->queryOne();

        $colablist = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE cur_syllabus_id=".$id)->queryAll();

        $matrixdata ='';

            return $this->render('course_articulation_matrixupdate', [
                'model' => $model,
                'labmodel'=>$labmodel,
                'cur_syllabus_id'=>$id,
                'codatalist'=>$codatalist,
                'colablist'=>$colablist,
                'matrixdata'=>$matrixdata
            ]);
    }

    public function actionServiceIndex()
    {
        $_SESSION['servicesubject']=3;
        $searchModel = new CurSyllabusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('serviceindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateservice()
    {
        $model = new CurSyllabus();
        $curmodel = new CurriculumSubject();
        if (Yii::$app->request->post()) 
        {

            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId(); 

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['CurSyllabus']['coe_regulation_id'])->queryScalar();

            $subject_category_type = Yii::$app->db->createCommand("SELECT subject_category_type_id FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND coe_dept_id=8 AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar(); //exit();

            $subject_type = Yii::$app->db->createCommand("SELECT subject_type_id FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND coe_dept_id=". $_POST['coe_dept_id']." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

             $degree_type = Yii::$app->db->createCommand("SELECT degree_type FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND coe_dept_id=". $_POST['coe_dept_id']." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();
            
            $model->degree_type=$degree_type;
            $model->coe_regulation_id=$_POST['CurSyllabus']['coe_regulation_id'];
            $model->coe_batch_id=$coe_batch_id;
            $model->coe_dept_id=8;
            $model->semester=0;//$_POST['CurSyllabus']['semester'];
            $model->subject_code=$_POST['CurSyllabus']['subject_code'];
            $model->subject_type=3;

            $course_objectives=$_POST['course_objectives'];

            if(count($course_objectives)>0)
            {
                $l=1;
                for ($i=0; $i <count($course_objectives) ; $i++) 
                { 
                    $cobj='course_objectives'.$l;
                    $model->$cobj=$course_objectives[$i];
                    $l++;
                }
            }

            $course_outcomes=$_POST['course_outcomes'];

            if(count($course_outcomes)>0)
            {
                $l=1;
                for ($i=0; $i <count($course_outcomes) ; $i++) 
                { 
                    $cobj='course_outcomes'.$l;
                    $model->$cobj=$course_outcomes[$i];
                    $l++;
                }
            }

            $rpt=$_POST['rpt'];

            if(count($rpt)>0)
            {
                $l=1;
                for ($i=0; $i <count($rpt) ; $i++) 
                { 
                    $cobj='rpt'.$l;
                    $model->$cobj=$rpt[$i];
                    $l++;
                }
            }

            $module_title=$_POST['module_title'];

            if(count($module_title)>0)
            {
                $l=1;
                for ($i=0; $i <count($module_title) ; $i++) 
                { 
                    $cobj='module_title'.$l;
                    $model->$cobj=$module_title[$i];
                    $l++;
                }
            }

            $module_hr=$_POST['module_hr'];

            if(count($module_hr)>0)
            {
                $l=1;
                for ($i=0; $i <count($module_hr) ; $i++) 
                { 
                    $cobj='module_hr'.$l;
                    $model->$cobj=$module_hr[$i];
                    $l++;
                }
            }

            $cource_content_mod=$_POST['cource_content_mod'];

            if(count($cource_content_mod)>0)
            {
                $l=1;
                for ($i=0; $i <count($cource_content_mod) ; $i++) 
                { 
                    $cobj='cource_content_mod'.$l;
                    $model->$cobj=$cource_content_mod[$i];
                    $l++;
                }
            }

            $text_book=$_POST['text_book'];

            if(count($text_book)>0)
            {
                $l=1;
                for ($i=0; $i <count($text_book) ; $i++) 
                { 
                    $cobj='text_book'.$l;
                    $model->$cobj=$text_book[$i];
                    $l++;
                }
            }

            $web_reference=$_POST['web_reference'];

            if(count($web_reference)>0)
            {
                $l=1;
                for ($i=0; $i <count($web_reference) ; $i++) 
                { 
                    $cobj='web_reference'.$l;
                    $model->$cobj=$web_reference[$i];
                    $l++;
                }
            }

            $reference_book=$_POST['reference_book'];

            if(count($reference_book)>0)
            {
                $l=1;
                for ($i=0; $i <count($reference_book) ; $i++) 
                { 
                    $cobj='reference_book'.$l;
                    $model->$cobj=$reference_book[$i];
                    $l++;
                }
            }

            $online_reference=$_POST['online_reference'];

            if(count($online_reference)>0)
            {
                $l=1;
                for ($i=0; $i <count($online_reference) ; $i++) 
                { 
                    $cobj='online_reference'.$l;
                    $model->$cobj=$online_reference[$i];
                    $l++;
                }
            }

            $model->created_at=$created_at;
            $model->created_by=$userid;
            
            
            if($model->save(false))
            {
                if((($subject_category_type>= 140 && 142 >=$subject_category_type) || $subject_category_type==144) || $subject_type==10)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                    return $this->redirect(['labcomponetservice', 'id' => $model->cur_syllabus_id]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                    return $this->redirect(['viewservice', 'id' => $model->cur_syllabus_id]);
                }
                
            }
            else
            {
                if((($subject_category_type>= 140 && 142 >=$subject_category_type) || $subject_category_type==144) || $subject_type==10)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added Already..");
                    return $this->redirect(['labcomponetservice', 'id' => $model->cur_syllabus_id]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added Already..");
                    return $this->redirect(['viewservice', 'id' => $model->cur_syllabus_id]);
                }
            }
            
            
        } 
        else 
        {
            return $this->render('createservice', [
                'model' => $model,'curmodel'=>$curmodel
            ]);
        }
    }

    public function actionLabcomponetservice($id)
    {
        $model = new CurSyllabus();
        $labmodel = new CurLabComponent();

        $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();


        if(Yii::$app->request->post()) 
        {
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();

            $experiment_title=$_POST['experiment_title'];
            
            $rpt=$_POST['rpt'];
            $Error=$success=0;
            //print_r(count($experiment_title)); exit();
            if(count($experiment_title)>0)
            {
                $experiment=1;
                for ($i=0; $i <count($experiment_title) ; $i++) 
                { 
                    $cotitle='cource_outcome'.$experiment;
                    $cource_outcome=$cotitle;
                      
                    $co=implode(",",$_POST[$cource_outcome]); //exit();

                    //$cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE experiment_title='".$experiment_title[$i]."' AND cur_syllabus_id=".$id." AND cource_outcome='".$co."'")->queryAll();

                    $experiment_title1 = nl2br($experiment_title[$i]);

                    $cheklab = Yii::$app->db->createCommand('SELECT * FROM cur_lab_component WHERE experiment_title="'.$experiment_title1.'" AND cur_syllabus_id="'.$id.'" AND cource_outcome="'.$co.'"')->queryAll();

                    if(empty($cheklab))
                    {
                        
                        $labcomponetmodel= new CurLabComponent();
                        $labcomponetmodel->experiment_title=$experiment_title1;
                        $labcomponetmodel->cource_outcome=$co;
                        $labcomponetmodel->rpt=$rpt[$i];                    
                        $labcomponetmodel->cur_syllabus_id=$id;
                        $labcomponetmodel->created_at=$created_at;
                        $labcomponetmodel->created_by=$userid;

                        if($labcomponetmodel->save(false))
                        {
                            $success++;
                        }
                        else
                        {
                            $Error++;
                        }
                    }
                    else
                    {
                         $success++;
                    }
                   
                   $experiment++;
                }
            }

            //echo $success."err".$Error; exit;

            if($success==count($experiment_title))
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Lab Component Added successfully..");
               
                return $this->redirect(['viewservice', 'id' => $id]);
                
            }
            else
            {
                 Yii::$app->ShowFlashMessages->setMsg('Success', "Success ".$success." Error".$Error." Lab Component not insert successfully..");
                return $this->render('labcomponetservice', [
                'model' => $model,
                'labmodel'=>$labmodel,
                'cur_syllabus_id'=>$id,
                'codatalist'=>$codatalist
                ]);
            }
        }
        else
        {
             return $this->render('labcomponetservice', [
            'model' => $model,
            'labmodel'=>$labmodel,
            'cur_syllabus_id'=>$id,
            'codatalist'=>$codatalist
            ]);
        }
       
    }


    public function actionServicearticulationmatrix($id)
    {
        $model = new CurSyllabus();
        $labmodel = new CurLabComponent();
        $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type, B.coe_dept_ids  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();

        $colablist = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE cur_syllabus_id=".$id)->queryAll();

       
        return $this->render('servicearticulationmatrix', [
            'model' => $model,
            'labmodel'=>$labmodel,
            'cur_syllabus_id'=>$id,
            'codatalist'=>$codatalist,
            'colablist'=>$colablist
        ]);
        
    }


    public function actionUpdateservice($id)
    {
        $model = $this->findModel($id);

         if (Yii::$app->request->post()) 
        {

            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId(); 

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$_POST['CurSyllabus']['coe_regulation_id'])->queryScalar();

            $subject_category_type = Yii::$app->db->createCommand("SELECT subject_category_type_id FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND coe_dept_id=8 AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

            $subject_type = Yii::$app->db->createCommand("SELECT subject_type_id FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND coe_dept_id=". $_POST['coe_dept_id']." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();

             $degree_type = Yii::$app->db->createCommand("SELECT degree_type FROM cur_curriculum_subject WHERE coe_batch_id=". $coe_batch_id." AND coe_dept_id=". $_POST['coe_dept_id']." AND subject_code='". $_POST['CurSyllabus']['subject_code']."'")->queryScalar();
            
            $model->degree_type=$degree_type;
            
            $model->coe_regulation_id=$_POST['CurSyllabus']['coe_regulation_id'];
            $model->coe_batch_id=$coe_batch_id;
            $model->coe_dept_id=8;//$_POST['coe_dept_id'];
            $model->semester=0;//$_POST['CurSyllabus']['semester'];
            $model->subject_code=$_POST['CurSyllabus']['subject_code'];
            $model->subject_type=3;

            $course_objectives=$_POST['course_objectives'];

            if(count($course_objectives)>0)
            {
                $l=1;
                for ($i=0; $i <count($course_objectives) ; $i++) 
                { 
                    $cobj='course_objectives'.$l;
                    $model->$cobj=$course_objectives[$i];
                    $l++;
                }
            }

            $course_outcomes=$_POST['course_outcomes'];

            if(count($course_outcomes)>0)
            {
                $l=1;
                for ($i=0; $i <count($course_outcomes) ; $i++) 
                { 
                    $cobj='course_outcomes'.$l;
                    $model->$cobj=$course_outcomes[$i];
                    $l++;
                }
            }

            $rpt=$_POST['rpt'];

            if(count($rpt)>0)
            {
                $l=1;
                for ($i=0; $i <count($rpt) ; $i++) 
                { 
                    $cobj='rpt'.$l;
                    $model->$cobj=$rpt[$i];
                    $l++;
                }
            }

            $module_title=$_POST['module_title'];

            if(count($module_title)>0)
            {
                $l=1;
                for ($i=0; $i <count($module_title) ; $i++) 
                { 
                    $cobj='module_title'.$l;
                    $model->$cobj=$module_title[$i];
                    $l++;
                }
            }

            $module_hr=$_POST['module_hr'];

            if(count($module_hr)>0)
            {
                $l=1;
                for ($i=0; $i <count($module_hr) ; $i++) 
                { 
                    $cobj='module_hr'.$l;
                    $model->$cobj=$module_hr[$i];
                    $l++;
                }
            }

            $cource_content_mod=$_POST['cource_content_mod'];

            if(count($cource_content_mod)>0)
            {
                $l=1;
                for ($i=0; $i <count($cource_content_mod) ; $i++) 
                { 
                    $cobj='cource_content_mod'.$l;
                    $model->$cobj=$cource_content_mod[$i];
                    $l++;
                }
            }

            $text_book=$_POST['text_book'];

            if(count($text_book)>0)
            {
                $l=1;
                for ($i=0; $i <count($text_book) ; $i++) 
                { 
                    $cobj='text_book'.$l;
                    $model->$cobj=$text_book[$i];
                    $l++;
                }
            }

            $web_reference=$_POST['web_reference'];

            if(count($web_reference)>0)
            {
                $l=1;
                for ($i=0; $i <count($web_reference) ; $i++) 
                { 
                    $cobj='web_reference'.$l;
                    $model->$cobj=$web_reference[$i];
                    $l++;
                }
            }

            $reference_book=$_POST['reference_book'];

            if(count($reference_book)>0)
            {
                $l=1;
                for ($i=0; $i <count($reference_book) ; $i++) 
                { 
                    $cobj='reference_book'.$l;
                    $model->$cobj=$reference_book[$i];
                    $l++;
                }
            }

            $online_reference=$_POST['online_reference'];

            if(count($online_reference)>0)
            {
                $l=1;
                for ($i=0; $i <count($online_reference) ; $i++) 
                { 
                    $cobj='online_reference'.$l;
                    $model->$cobj=$online_reference[$i];
                    $l++;
                }
            }

            $model->created_at=$created_at;
            $model->created_by=$userid;
            
            
            if($model->save(false))
            {
                if((($subject_category_type>= 140 && 142 >=$subject_category_type) || $subject_category_type==144) || $subject_type==10)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                    return $this->redirect(['labcomponetserviceupdate', 'id' => $model->cur_syllabus_id]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added successfully..");
                    return $this->redirect(['viewservice', 'id' => $model->cur_syllabus_id]);
                }
                
            }
            else
            {
                if((($subject_category_type>= 140 && 142 >=$subject_category_type) || $subject_category_type==144) || $subject_type==10)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added Already..");
                    return $this->redirect(['labcomponetserviceupdate', 'id' => $model->cur_syllabus_id]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus Added Already..");
                    return $this->redirect(['viewservice', 'id' => $model->cur_syllabus_id]);
                }
            }
            
            
        } 
        else {
            return $this->render('updateservice', [
                'model' => $model,
            ]);
        }
    }

    public function actionLabcomponetserviceupdate($id)
    {
        $model = new CurSyllabus();
        $labmodel = new CurLabComponent();
        //print_r($labmodel); exit;
        $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();

        $cheklabdata = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE cur_syllabus_id=".$id."")->queryAll();

        if(Yii::$app->request->post()) 
        {
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();

            $experiment_id=$_POST['experiment_id'];
            $experiment_title=$_POST['experiment_title'];
            //$cource_outcome=$_POST['cource_outcome'];
            $rpt=$_POST['rpt'];
            $Error=$success=0;
             //print_r($experiment_id); exit();
            if(count($experiment_title)>0)
            {
                $experiment=1;
                for ($i=0; $i <count($experiment_title) ; $i++) 
                { 
                    if($i<count($experiment_id) && !empty($experiment_id) && $experiment_id[0]!='')
                    {
                         $cotitle='cource_outcome'.$experiment;
                        $cource_outcome=$cotitle;
                      
                        $co=implode(",",$_POST[$cource_outcome]);
                        //print_r($experiment_id); exit();
                        $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE cur_labcomp_id!=".$experiment_id[$i])->queryAll();

                        if(!empty($cheklab))
                        {
                            $labcomponetmodel= CurLabComponent::findOne($experiment_id[$i]);
                            $labcomponetmodel->experiment_title=$experiment_title[$i];
                            $labcomponetmodel->cource_outcome=$co;
                            $labcomponetmodel->rpt=$rpt[$i];                    
                            $labcomponetmodel->cur_syllabus_id=$id;
                            $labcomponetmodel->updated_at=$created_at;
                            $labcomponetmodel->updated_by=$userid;
                            //print_r($labcomponetmodel); exit();
                            if($labcomponetmodel->save(false))
                            {
                                $success++;
                            }
                            else
                            {
                                $Error++;
                            }
                        }

                       // exit();
                    }
                    else
                    {
                       $cotitle='cource_outcome'.$experiment;
                        $cource_outcome=$cotitle;
                      
                        $co=implode(",",$_POST[$cource_outcome]);

                        //$cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE experiment_title='".$experiment_title[$i]."' AND cur_syllabus_id=".$id." AND cource_outcome='".$co."'")->queryAll();

                        $experiment_title1 = nl2br($experiment_title[$i]);

                        $cheklab = Yii::$app->db->createCommand('SELECT * FROM cur_lab_component WHERE experiment_title="'.$experiment_title1.'" AND cur_syllabus_id="'.$id.'" AND cource_outcome="'.$co.'"')->queryAll();

                        if(empty($cheklab))
                        {
                            $labcomponetmodel= new CurLabComponent();
                            $labcomponetmodel->experiment_title=$experiment_title1; //[$i];
                            $labcomponetmodel->cource_outcome=$co;
                            $labcomponetmodel->rpt=$rpt[$i];                    
                            $labcomponetmodel->cur_syllabus_id=$id;
                            $labcomponetmodel->created_at=$created_at;
                            $labcomponetmodel->created_by=$userid;
                            //print_r($labcomponetmodel); exit();
                            if($labcomponetmodel->save(false))
                            {
                                $success++;
                            }
                            else
                            {
                                $Error++;
                            }
                        }
                    }
                   $experiment++;
                }
            }

            //echo $success."err".$Error; exit;

            if($success==count($experiment_title))
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Lab Component updated successfully..");
               
                return $this->redirect(['viewservice', 'id' => $id]);
                
            }
            else
            {
                 Yii::$app->ShowFlashMessages->setMsg('Success', "Success ".$success." Error".$Error." Lab Component not updated successfully..");
                return $this->render('labcomponetserviceupdate', [
                'model' => $model,
                'labmodel'=>$labmodel,
                'cur_syllabus_id'=>$id,
                'codatalist'=>$codatalist,
                'cheklabdata'=>$cheklabdata
                ]);
            }
        }
        else
        {
             return $this->render('labcomponetserviceupdate', [
            'model' => $model,
            'labmodel'=>$labmodel,
            'cur_syllabus_id'=>$id,
            'codatalist'=>$codatalist,
            'cheklabdata'=>$cheklabdata
            ]);
        }
       
    }

    public function actionServicearticulationmatrixupdate($id)
    {
        $model = new CurSyllabus();
        $labmodel = new CurLabComponent();
        $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id  WHERE cur_syllabus_id=".$id)->queryOne();

        $colablist = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE cur_syllabus_id=".$id)->queryAll();

        $matrixdata = '';
       
        return $this->render('servicearticulationmatrixupdate', [
            'model' => $model,
            'labmodel'=>$labmodel,
            'cur_syllabus_id'=>$id,
            'codatalist'=>$codatalist,
            'colablist'=>$colablist,
            'matrixdata'=>$matrixdata
        ]);
       
    }

    public function actionCreateMatrix()
    {
        $model = new CurSyllabus();

        $codatalist='';
        if(Yii::$app->request->post()) 
        {
             $subject_code=$_POST['subject_code'];
             $coe_regulation_id=$_POST['coe_regulation_id'];
             $coe_dept_id=$_POST['coe_dept_id'];
             
             $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE A.coe_dept_id ='".$coe_dept_id."' AND A.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
            $codatalist = Yii::$app->db->createCommand($query)->queryOne();

            if(empty($codatalist))
            {
                $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE A.coe_dept_id ='".$coe_dept_id."' AND A.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
                $codatalist = Yii::$app->db->createCommand($query)->queryOne();

                if(empty($codatalist))
                {
                    $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_electivetodept S ON S.subject_code=B.subject_code WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
                    $codatalist = Yii::$app->db->createCommand($query)->queryOne();
                }

                if(empty($codatalist))
                {
                    $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_electivetodept S ON S.subject_code=B.subject_code WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
                    $codatalist = Yii::$app->db->createCommand($query)->queryOne();

                    if(empty($codatalist))
                    {
                        $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_curriculum_subject B JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_electivetodept S ON S.subject_code=B.subject_code JOIN cur_syllabus_existing SE ON SE.to_subject_code=B.subject_code JOIN cur_syllabus A ON SE.from_subject_code=A.subject_code WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND B.coe_regulation_id=".$coe_regulation_id;

                        $codatalist = Yii::$app->db->createCommand($query)->queryOne();

                        if(empty($codatalist))
                        {
                             $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type FROM cur_elective_subject B JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_electivetodept S ON S.subject_code=B.subject_code JOIN cur_syllabus_existing SE ON SE.to_subject_code=B.subject_code JOIN cur_syllabus A ON SE.from_subject_code=A.subject_code WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND B.coe_regulation_id=".$coe_regulation_id;

                             $codatalist = Yii::$app->db->createCommand($query)->queryOne();
                        }
                    }

                }
            }

             $deptpso = Yii::$app->db->createCommand("SELECT pso_count,po_count FROM cur_frontpage WHERE coe_dept_id=".$coe_dept_id." AND coe_regulation_id=".$coe_regulation_id)->queryOne();

            //print_r($codatalist); exit();
            if(isset($_POST['finishsyllabus']))
            { 
                $com='co_matrix';

                $co_matrix=$_POST[$com];

                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();


                $success=0; $Error=0;
                if(!empty($deptpso))
                {
                    if($_POST['degree_type']=='UG')
                    {
                        $l=1;
                        for ($i=0; $i <count($co_matrix); $i++) 
                        { 
                            $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE co='".$co_matrix[$i]."' AND coe_regulation_id=".$coe_regulation_id." AND cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id=".$coe_dept_id)->queryAll();
                            //print_r($cheklab); exit();
                            if(empty($cheklab))
                            {
                                $labcomponetmodel= new CurCourseArticulationMatrix();
                                $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                $labcomponetmodel->coe_dept_id=$coe_dept_id;
                                $labcomponetmodel->co=$co_matrix[$i];
                                
                                $po='po_matrix'.$l;
                                $poo='po_matrix'.$l;                        
                                $n=$_POST[$poo];
                                
                                //print_r($n); exit();

                                $k=1;
                                for ($j=0; $j<12; $j++) 
                                { 
                                    $po='po'.$k;
                                    $labcomponetmodel->$po=$n[$j]; 

                                    $k++; 
                                }
                                
                                $psovaluematrix='';

                                for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                { 
                                    $psovalue='pso_matrix'.$p.$l;
                                    $psovalueo='pso_matrix'.$p.$l; 
                                    $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                } 
                                
                                $psovaluematrix=rtrim($psovaluematrix,",");

                                $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                $labcomponetmodel->pso_value=$psovaluematrix;
                                $labcomponetmodel->created_at=$created_at;
                                $labcomponetmodel->created_by=$userid;

                                if($labcomponetmodel->save(false))
                                {
                                    $success++;
                                }
                                else
                                {
                                    $Error++;
                                }
                            }
                            else
                            {
                                 $Error++;
                            }
                           
                           $l++;
                        }
                    }
                    else if($_POST['degree_type']=='PG')
                    {
                        $l=1;
                        for ($i=0; $i <count($co_matrix); $i++) 
                        { 
                            $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id=".$coe_dept_id)->queryAll();

                            if(empty($cheklab))
                            {
                                $labcomponetmodel= new CurCourseArticulationMatrix();
                                $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                $labcomponetmodel->coe_dept_id=$coe_dept_id;
                                $labcomponetmodel->co=$co_matrix[$i];
                                
                                $po='po_matrix'.$l;
                                $poo='po_matrix'.$l;                        
                                $n=$_POST[$poo];
                                
                                //print_r($n); exit();

                                $k=1;
                                for ($j=0; $j<$deptpso['po_count']; $j++) 
                                { 
                                    $po='po'.$k;
                                    $labcomponetmodel->$po=$n[$j]; 

                                    $k++; 
                                }
                                
                                $psovaluematrix='';

                                for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                { 
                                    $psovalue='pso_matrix'.$p.$l;
                                    $psovalueo='pso_matrix'.$p.$l; 
                                    $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                } 
                                
                                $psovaluematrix=rtrim($psovaluematrix,",");

                                $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                $labcomponetmodel->pso_value=$psovaluematrix;
                                $labcomponetmodel->created_at=$created_at;
                                $labcomponetmodel->created_by=$userid;

                                if($labcomponetmodel->save(false))
                                {
                                    $success++;
                                }
                                else
                                {
                                    $Error++;
                                }
                            }
                            else
                            {
                                 $Error++;
                            }
                           
                           $l++;
                        }
                    }
                    else if($_POST['degree_type']=='MBA')
                    {
                        $l=1;
                        for ($i=0; $i <count($co_matrix); $i++) 
                        { 
                            $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id=".$coe_dept_id)->queryAll();

                            if(empty($cheklab))
                            {
                                $labcomponetmodel= new CurCourseArticulationMatrix();
                                $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                $labcomponetmodel->coe_dept_id=$coe_dept_id;
                                $labcomponetmodel->co=$co_matrix[$i];
                                
                                $po='po_matrix'.$l;
                                $poo='po_matrix'.$l;                        
                                $n=$_POST[$poo];
                                
                                //print_r($n); exit();

                                $k=1;
                                for ($j=0; $j<6; $j++) 
                                { 
                                    $po='po'.$k;
                                    $labcomponetmodel->$po=$n[$j]; 

                                    $k++; 
                                }
                                
                                $psovaluematrix='';

                                for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                { 
                                    $psovalue='pso_matrix'.$p.$l;
                                    $psovalueo='pso_matrix'.$p.$l; 
                                    $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                } 
                                
                                $psovaluematrix=rtrim($psovaluematrix,",");

                                $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                $labcomponetmodel->pso_value=$psovaluematrix;
                                $labcomponetmodel->created_at=$created_at;
                                $labcomponetmodel->created_by=$userid;

                                if($labcomponetmodel->save(false))
                                {
                                    $success++;
                                }
                                else
                                {
                                    $Error++;
                                }
                            }
                            else
                            {
                                 $Error++;
                            }
                           
                           $l++;
                        }
                    }
                }
                //echo $success; exit();
                if($success>0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Course Matrix insert successfully..");
                   
                    return $this->redirect(['create-matrix']);
                    
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Course Matrix Not insert or Already Inserted! please check Update Matrix or Report");
                     return $this->redirect(['create-matrix']);
                    
                }
            }
            else
            {
                $matrixdata = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id ='".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id)->queryAll();

                if(empty($deptpso))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Create PSO Count First in Settings Menu");
                   
                    return $this->redirect(['create-matrix']);
                }
                else if(!empty($matrixdata))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "CAM Matrix Already Created");
                   
                    return $this->redirect(['create-matrix']);
                }
                else if(empty($codatalist))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Please Create Syllabus First");
                   
                    return $this->redirect(['create-matrix']);
                }
                else
                {
                    $matrixdata = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_syllabus_id=".$codatalist['cur_syllabus_id'])->queryAll();
                    //print_r($matrixdata); exit();
                    return $this->render('create_matrix', [
                        'model' => $model,
                        'codatalist'=>$codatalist,
                        'coe_regulation_id'=>$coe_regulation_id,
                        'subject_code'=> $subject_code,
                        'coe_regulation_id'=>$coe_regulation_id,
                        'coe_dept_id'=>$coe_dept_id,
                        'matrixdata'=>$matrixdata
                    ]);
                }
            }
        }
        else
        {
            return $this->render('create_matrix', [
                'model' => $model,
                'codatalist'=>$codatalist
            ]);
        }
    }

    public function actionUpdateMatrix()
    {
        $model = new CurSyllabus();

        $codatalist='';
        if(Yii::$app->request->post()) 
        {
            $subject_code=$_POST['subject_code'];
            $coe_regulation_id=$_POST['coe_regulation_id'];
            $coe_dept_id=$_POST['coe_dept_id'];
             
           $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE A.coe_dept_id ='".$coe_dept_id."' AND A.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
            $codatalist = Yii::$app->db->createCommand($query)->queryOne();

            if(empty($codatalist))
            {
                $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE A.coe_dept_id ='".$coe_dept_id."' AND A.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
                $codatalist = Yii::$app->db->createCommand($query)->queryOne();

                if(empty($codatalist))
                {
                    $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_electivetodept S ON S.subject_code=B.subject_code WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
                    $codatalist = Yii::$app->db->createCommand($query)->queryOne();
                }

                if(empty($codatalist))
                {
                    $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_electivetodept S ON S.subject_code=B.subject_code WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
                    $codatalist = Yii::$app->db->createCommand($query)->queryOne();

                    if(empty($codatalist))
                    {
                        $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_curriculum_subject B JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_electivetodept S ON S.subject_code=B.subject_code JOIN cur_syllabus_existing SE ON SE.to_subject_code=B.subject_code JOIN cur_syllabus A ON SE.from_subject_code=A.subject_code WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND B.coe_regulation_id=".$coe_regulation_id;

                        $codatalist = Yii::$app->db->createCommand($query)->queryOne();

                        if(empty($codatalist))
                        {
                             $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type FROM cur_elective_subject B JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_electivetodept S ON S.subject_code=B.subject_code JOIN cur_syllabus_existing SE ON SE.to_subject_code=B.subject_code JOIN cur_syllabus A ON SE.from_subject_code=A.subject_code WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND B.coe_regulation_id=".$coe_regulation_id;

                             $codatalist = Yii::$app->db->createCommand($query)->queryOne();
                        }
                    }
                }
            }
            //print_r($codatalist); exit();
            if(empty($codatalist))
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Please Create Syllabus First");
               
                return $this->redirect(['update-matrix']);
            }
            else
            {
                $matrixdata = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_regulation_id=".$coe_regulation_id)->queryAll();
                //print_r($codatalist['cur_syllabus_id']); exit();
                if(empty($matrixdata))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Create CAM Matrix First");
                   
                    return $this->redirect(['update-matrix']);
                }
                else if(isset($_POST['finishsyllabus']))
                {
                    $deptpso = Yii::$app->db->createCommand("SELECT pso_count,po_count FROM cur_frontpage WHERE coe_dept_id=".$coe_dept_id." AND coe_regulation_id=".$coe_regulation_id)->queryOne();
                    
                    $com='co_matrix';

                    $co_matrix=$_POST[$com];

                    $created_at = date("Y-m-d H:i:s");
                    $userid = Yii::$app->user->getId();

                    $success=0; $Error=0;
                    if(!empty($deptpso))
                    {
                        if($_POST['degree_type']=='UG')
                        {
                            $l=1;
                            for ($i=0; $i <count($co_matrix) ; $i++) 
                            { 
                                $chekmat = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id'])->queryAll();

                                //print_r($chekmat); exit;

                                foreach ($chekmat as  $matvalue) 
                                {
                                    $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id'])->queryAll();

                                   if(!empty($cheklab))
                                    {
                                        $labcomponetmodel=  CurCourseArticulationMatrix::findOne(['cur_cam_id'=>$matvalue['cur_cam_id']]);
                                        $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                        $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                        $labcomponetmodel->coe_dept_id=$matvalue['coe_dept_id'];
                                        $labcomponetmodel->co=$co_matrix[$i];
                                        
                                        $po='po_matrix'.$l;
                                        $poo='po_matrix'.$l;                        
                                        $n=$_POST[$poo];
                                        
                                        //print_r($n); exit();

                                        $k=1;
                                        for ($j=0; $j<12; $j++) 
                                        { 
                                            $po='po'.$k;
                                            $labcomponetmodel->$po=$n[$j]; 

                                            $k++; 
                                        }
                                        
                                        $chekpso = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id']." AND coe_dept_id=".$coe_dept_id)->queryAll();

                                        if(!empty($chekpso))
                                        {
                                            $psovaluematrix='';

                                            for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                            { 
                                                $psovalue='pso_matrix'.$p.$l;
                                                $psovalueo='pso_matrix'.$p.$l; 
                                                $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                            } 
                                            
                                            $psovaluematrix=rtrim($psovaluematrix,",");
                                            $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                            $labcomponetmodel->pso_value=$psovaluematrix;
                                        }

                                        $labcomponetmodel->created_at=$created_at;
                                        $labcomponetmodel->created_by=$userid;

                                        if($labcomponetmodel->save(false))
                                        {
                                            $success++;
                                        }
                                        else
                                        {
                                            $Error++;
                                        }
                                    }
                                    else
                                    {
                                         $Error++;
                                    }
                                }
                                
                               
                               $l++;
                            }
                        }
                        else if($_POST['degree_type']=='PG')
                        {
                            $l=1;
                            for ($i=0; $i <count($co_matrix) ; $i++) 
                            { 
                                $chekmat = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id'])->queryAll();

                                //print_r($chekmat); exit;

                                foreach ($chekmat as  $matvalue) 
                                {
                                    $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id'])->queryAll();

                                   if(!empty($cheklab))
                                    {
                                        $labcomponetmodel=  CurCourseArticulationMatrix::findOne(['cur_cam_id'=>$matvalue['cur_cam_id']]);
                                        $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                        $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                        $labcomponetmodel->coe_dept_id=$matvalue['coe_dept_id'];
                                        $labcomponetmodel->co=$co_matrix[$i];
                                        
                                        $po='po_matrix'.$l;
                                        $poo='po_matrix'.$l;                        
                                        $n=$_POST[$poo];
                                        
                                        //print_r($n); exit();

                                        $k=1;
                                        for ($j=0; $j<$deptpso['po_count']; $j++) 
                                        { 
                                            $po='po'.$k;
                                            $labcomponetmodel->$po=$n[$j]; 

                                            $k++; 
                                        }
                                        
                                        $chekpso = Yii::$app->db->createCommand("SELECT pso_count FROM cur_course_articulation_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id']." AND coe_dept_id=".$coe_dept_id)->queryScalar();

                                        if($chekpso>0)
                                        {
                                            $psovaluematrix='';

                                            for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                            { 
                                                $psovalue='pso_matrix'.$p.$l;
                                                $psovalueo='pso_matrix'.$p.$l; 
                                                $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                            } 
                                            
                                            $psovaluematrix=rtrim($psovaluematrix,",");
                                            $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                            $labcomponetmodel->pso_value=$psovaluematrix;
                                        }

                                        $labcomponetmodel->created_at=$created_at;
                                        $labcomponetmodel->created_by=$userid;

                                        if($labcomponetmodel->save(false))
                                        {
                                            $success++;
                                        }
                                        else
                                        {
                                            $Error++;
                                        }
                                    }
                                    else
                                    {
                                         $Error++;
                                    }
                                }
                                
                               
                               $l++;
                            }
                        }
                        else if($_POST['degree_type']=='MBA')
                        {
                            $l=1;
                            for ($i=0; $i <count($co_matrix) ; $i++) 
                            { 
                                $chekmat = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id'])->queryAll();

                                //print_r($chekmat); exit;

                                foreach ($chekmat as  $matvalue) 
                                {
                                    $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id'])->queryAll();

                                   if(!empty($cheklab))
                                    {
                                        $labcomponetmodel=  CurCourseArticulationMatrix::findOne(['cur_cam_id'=>$matvalue['cur_cam_id']]);
                                        $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                        $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                        $labcomponetmodel->coe_dept_id=$matvalue['coe_dept_id'];
                                        $labcomponetmodel->co=$co_matrix[$i];
                                        
                                        $po='po_matrix'.$l;
                                        $poo='po_matrix'.$l;                        
                                        $n=$_POST[$poo];
                                        
                                        //print_r($n); exit();

                                        $k=1;
                                        for ($j=0; $j<6; $j++) 
                                        { 
                                            $po='po'.$k;
                                            $labcomponetmodel->$po=$n[$j]; 

                                            $k++; 
                                        }
                                        
                                        $chekpso = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id']." AND coe_dept_id=".$coe_dept_id)->queryAll();

                                        if(!empty($chekpso))
                                        {
                                            $psovaluematrix='';

                                            for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                            { 
                                                $psovalue='pso_matrix'.$p.$l;
                                                $psovalueo='pso_matrix'.$p.$l; 
                                                $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                            } 
                                            
                                            $psovaluematrix=rtrim($psovaluematrix,",");
                                            $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                            $labcomponetmodel->pso_value=$psovaluematrix;
                                        }

                                        $labcomponetmodel->created_at=$created_at;
                                        $labcomponetmodel->created_by=$userid;

                                        if($labcomponetmodel->save(false))
                                        {
                                            $success++;
                                        }
                                        else
                                        {
                                            $Error++;
                                        }
                                    }
                                    else
                                    {
                                         $Error++;
                                    }
                                }
                                
                               
                               $l++;
                            }
                        }
                    }

                    if($success>0)
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Success', "Course Matrix updated successfully..");
                       
                        return $this->redirect(['update-matrix']);
                        
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error', "Course Matrix Not update successfully! please check");
                         return $this->render('update_matrix', [
                        'model' => $model,
                        'codatalist'=>$codatalist,
                        'coe_dept_id'=>$coe_dept_id,
                        'subject_code'=> $subject_code,
                        'coe_regulation_id'=>$coe_regulation_id,
                        'coe_dept_id'=>$coe_dept_id,
                        ]);
                        
                    }
                }
                else
                {
                    return $this->render('update_matrix', [
                        'model' => $model,
                        'codatalist'=>$codatalist,
                        'coe_dept_id'=>$coe_dept_id,
                        'subject_code'=> $subject_code,
                        'coe_regulation_id'=>$coe_regulation_id,
                        'coe_dept_id'=>$coe_dept_id,
                        'matrixdata'=>$matrixdata
                    ]);
                    
                }
            }
        }
        else
        {
            return $this->render('update_matrix', [
                'model' => $model,
                'codatalist'=>$codatalist
            ]);
        }
    }

    public function actionDeleteMatrix()
    {
        $model = new CurSyllabus();

        $codatalist='';
        if(Yii::$app->request->post()) 
        {
            $subject_code=$_POST['subject_code'];
            $coe_regulation_id=$_POST['coe_regulation_id'];
            $coe_dept_id=$_POST['coe_dept_id'];
             
           $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE A.coe_dept_id ='".$coe_dept_id."' AND A.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
            $codatalist = Yii::$app->db->createCommand($query)->queryOne();

            if(empty($codatalist))
            {
                $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE A.coe_dept_id ='".$coe_dept_id."' AND A.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
                $codatalist = Yii::$app->db->createCommand($query)->queryOne();

                if(empty($codatalist))
                {
                    $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type  FROM cur_syllabus A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_electivetodept S ON S.subject_code=B.subject_code WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
                    $codatalist = Yii::$app->db->createCommand($query)->queryOne();
                }
            }

             $matrixdata = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE approve_status=0 AND cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id ='".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id)->queryAll();

            //print_r($codatalist); exit();
            if(!empty($matrixdata))
            {
                 Yii::$app->db->createCommand("DELETE FROM cur_course_articulation_matrix WHERE cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id ='".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id)->execute();

                  Yii::$app->ShowFlashMessages->setMsg('Success', "Course Matrix delete successfully! please check");
                 return $this->redirect(['delete-matrix']);
            }
            else
            {
                 Yii::$app->ShowFlashMessages->setMsg('Error', "No Matrix found or Approved Matrix not delete! please check");

                return $this->redirect(['delete-matrix']);
                
            }
        }
        else
        {
            return $this->render('delete_matrix', [
                'model' => $model,
                'codatalist'=>$codatalist
            ]);
        }
    }


    public function actionCreateServiceMatrix()
    {
        $model = new CurSyllabus();

        $codatalist=''; $Error=0;
        if(Yii::$app->request->post()) 
        {
             $subject_code=$_POST['subject_code'];
             $coe_regulation_id=$_POST['coe_regulation_id'];
             $coe_dept_id=$_POST['coe_dept_id'];
             
             $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type, B.coe_dept_ids  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_servicesubtodept S ON S.coe_cur_subid=B.coe_cur_id WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
            $codatalist = Yii::$app->db->createCommand($query)->queryOne();

            $deptpso = Yii::$app->db->createCommand("SELECT pso_count,po_count FROM cur_frontpage WHERE coe_dept_id=".$coe_dept_id." AND coe_regulation_id=".$coe_regulation_id)->queryOne();

            //print_r($codatalist); exit();
            if(isset($_POST['finishsyllabus']))
            {               
                
                $com='co_matrix';

                $co_matrix=$_POST[$com];

                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();

                $success=0;
                if(!empty($deptpso))
                {
                    if($_POST['degree_type']=='UG')
                    {
                        $l=1;
                        for ($i=0; $i <count($co_matrix) ; $i++) 
                        { 

                            $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id=".$coe_dept_id)->queryAll();

                            if(empty($cheklab))
                            {
                                $labcomponetmodel= new CurCourseArticulationMatrixService();
                                $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                $labcomponetmodel->coe_dept_id=$coe_dept_id;
                                $labcomponetmodel->co=$co_matrix[$i];
                                
                                $po='po_matrix'.$l;
                                $poo='po_matrix'.$l;                        
                                $n=$_POST[$poo];
                                
                                //print_r($n); exit();

                                $k=1;
                                for ($j=0; $j<12; $j++) 
                                { 
                                    $po='po'.$k;
                                    $labcomponetmodel->$po=$n[$j]; 

                                    $k++; 
                                }
                                
                                $psovaluematrix='';

                                for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                { 
                                    $psovalue='pso_matrix'.$p.$l;
                                    $psovalueo='pso_matrix'.$p.$l; 
                                    $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                } 
                                
                                $psovaluematrix=rtrim($psovaluematrix,",");

                                $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                $labcomponetmodel->pso_value=$psovaluematrix;
                                $labcomponetmodel->created_at=$created_at;
                                $labcomponetmodel->created_by=$userid;

                                if($labcomponetmodel->save(false))
                                {
                                    $success++;
                                }
                                else
                                {
                                    $Error++;
                                }
                            }
                            else
                            {  //exit;
                                 $Error++;
                            }
                           
                           $l++;
                        }
                    }
                    else if($_POST['degree_type']=='PG')
                    {
                        $l=1;
                        for ($i=0; $i <count($co_matrix); $i++) 
                        { 
                            $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id=".$coe_dept_id)->queryAll();

                            if(empty($cheklab))
                            {
                                $labcomponetmodel= new CurCourseArticulationMatrixService();
                                $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                $labcomponetmodel->coe_dept_id=$coe_dept_id;
                                $labcomponetmodel->co=$co_matrix[$i];
                                
                                $po='po_matrix'.$l;
                                $poo='po_matrix'.$l;                        
                                $n=$_POST[$poo];
                                
                                //print_r($n); exit();

                                $k=1;
                                for ($j=0; $j<$deptpso['po_count']; $j++) 
                                { 
                                    $po='po'.$k;
                                    $labcomponetmodel->$po=$n[$j]; 

                                    $k++; 
                                }
                                
                                $psovaluematrix='';

                                for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                { 
                                    $psovalue='pso_matrix'.$p.$l;
                                    $psovalueo='pso_matrix'.$p.$l; 
                                    $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                } 
                                
                                $psovaluematrix=rtrim($psovaluematrix,",");

                                $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                $labcomponetmodel->pso_value=$psovaluematrix;
                                $labcomponetmodel->created_at=$created_at;
                                $labcomponetmodel->created_by=$userid;

                                if($labcomponetmodel->save(false))
                                {
                                    $success++;
                                }
                                else
                                {
                                    $Error++;
                                }
                            }
                            else
                            {
                                 $Error++;
                            }
                           
                           $l++;
                        }
                    }
                    else if($_POST['degree_type']=='MBA')
                    {
                        $l=1;
                        for ($i=0; $i <count($co_matrix) ; $i++) 
                        { 

                            $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id=".$coe_dept_id)->queryAll();

                            if(empty($cheklab))
                            {
                                $labcomponetmodel= new CurCourseArticulationMatrixService();
                                $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                $labcomponetmodel->coe_dept_id=$coe_dept_id;
                                $labcomponetmodel->co=$co_matrix[$i];
                                
                                $po='po_matrix'.$l;
                                $poo='po_matrix'.$l;                        
                                $n=$_POST[$poo];
                                
                                //print_r($n); exit();

                                $k=1;
                                for ($j=0; $j<6; $j++) 
                                { 
                                    $po='po'.$k;
                                    $labcomponetmodel->$po=$n[$j]; 

                                    $k++; 
                                }
                                
                                $psovaluematrix='';

                                for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                { 
                                    $psovalue='pso_matrix'.$p.$l;
                                    $psovalueo='pso_matrix'.$p.$l; 
                                    $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                } 
                                
                                $psovaluematrix=rtrim($psovaluematrix,",");

                                $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                $labcomponetmodel->pso_value=$psovaluematrix;
                                $labcomponetmodel->created_at=$created_at;
                                $labcomponetmodel->created_by=$userid;

                                if($labcomponetmodel->save(false))
                                {
                                    $success++;
                                }
                                else
                                {
                                    $Error++;
                                }
                            }
                            else
                            {  //exit;
                                 $Error++;
                            }
                           
                           $l++;
                        }
                    }
                }

                if($success>0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Course Matrix insert successfully..");
                   
                    return $this->redirect(['create-service-matrix']);
                    
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Course Matrix Not insert successfully! please check");
                     return $this->render('createservice_martix', [
                    'model' => $model,
                    'codatalist'=>$codatalist,
                    'coe_dept_id'=>$coe_dept_id,
                    'subject_code'=> $subject_code,
                    'coe_regulation_id'=>$coe_regulation_id,
                    'coe_dept_id'=>$coe_dept_id,
                    ]);
                    
                }
            }
            else
            {
                $matrixdata = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id ='".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id)->queryAll();

                if(empty($deptpso))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Create PSO Count First in Settings Menu");
                   
                    return $this->redirect(['create-service-matrix']);
                }
                else if(!empty($matrixdata))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "CAM Matrix Already Created");
                   
                    return $this->redirect(['create-service-matrix']);
                }
                else if(empty($codatalist))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Please Create Syllabus First");
                   
                    return $this->redirect(['create-service-matrix']);
                }
                else
                {
                    $matrixdata = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_regulation_id=".$coe_regulation_id)->queryAll();
                    //print_r($matrixdata); exit;
                    return $this->render('createservice_martix', [
                        'model' => $model,
                        'codatalist'=>$codatalist,
                        'subject_code'=> $subject_code,
                        'coe_regulation_id'=>$coe_regulation_id,
                        'coe_dept_id'=>$coe_dept_id,
                        'matrixdata'=>$matrixdata
                    ]);
                }
                
            }
        }
        else
        {
            return $this->render('createservice_martix', [
                'model' => $model,
                'codatalist'=>$codatalist
            ]);
        }
    }

    public function actionUpdateServiceMatrix()
    {
        $model = new CurSyllabus();

        $codatalist='';
        if(Yii::$app->request->post()) 
        {
            $subject_code=$_POST['subject_code'];
            $coe_regulation_id=$_POST['coe_regulation_id'];
            $coe_dept_id=$_POST['coe_dept_id'];
             
            $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type, B.coe_dept_ids  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_servicesubtodept S ON S.coe_cur_subid=B.coe_cur_id WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
            
            $codatalist = Yii::$app->db->createCommand($query)->queryOne();

            
            $matrixdata = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id ='".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id)->queryAll();
                
            if(empty($matrixdata))
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Create CAM Matrix First");
               
                return $this->redirect(['update-matrix']);
            }
            else if(isset($_POST['finishsyllabus']))
            {
                $deptpso = Yii::$app->db->createCommand("SELECT pso_count,po_count FROM cur_frontpage WHERE coe_dept_id=".$coe_dept_id." AND coe_regulation_id=".$coe_regulation_id)->queryOne();
                
                $com='co_matrix';

                $co_matrix=$_POST[$com];

                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId();

                $success=0;
                if(!empty($deptpso))
                {
                    if($_POST['degree_type']=='UG')
                    {
                        $l=1;
                        for ($i=0; $i <count($co_matrix) ; $i++) 
                        { 
                            $chekmat = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id'])->queryAll();
                            //print_r(count($chekmat)); exit;
                            foreach ($chekmat as  $matvalue) 
                            {
                                $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id'])->queryAll();

                                if(!empty($cheklab))
                                {
                                    $labcomponetmodel=  CurCourseArticulationMatrixService::findOne(['cur_cam_id'=>$matvalue['cur_cam_id']]);
                                    $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                    $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                    $labcomponetmodel->coe_dept_id=$matvalue['coe_dept_id'];
                                    $labcomponetmodel->co=$co_matrix[$i];
                                    
                                    $po='po_matrix'.$l;
                                    $poo='po_matrix'.$l;                        
                                    $n=$_POST[$poo];
                                    
                                    //print_r($n); exit();

                                    $k=1;
                                    for ($j=0; $j<12; $j++) 
                                    { 
                                        $po='po'.$k;
                                        $labcomponetmodel->$po=$n[$j]; 

                                        $k++; 
                                    }

                                    $chekpso = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id']." AND coe_dept_id=".$coe_dept_id)->queryAll();

                                    if(!empty($chekpso))
                                    {
                                    
                                        $psovaluematrix='';

                                        for ($p=1; $p <=$deptpso['po_count'] ; $p++) 
                                        { 
                                            $psovalue='pso_matrix'.$p.$l;
                                            $psovalueo='pso_matrix'.$p.$l; 
                                            $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                        } 
                                        
                                        $psovaluematrix=rtrim($psovaluematrix,",");

                                        $labcomponetmodel->pso_count=$deptpso['po_count'];
                                        $labcomponetmodel->pso_value=$psovaluematrix;

                                    }

                                    $labcomponetmodel->created_at=$created_at;
                                    $labcomponetmodel->created_by=$userid;

                                    if($labcomponetmodel->save(false))
                                    {
                                        $success++;
                                    }
                                    else
                                    {
                                        $Error++;
                                    }
                                }
                                else
                                {
                                     $Error++;
                                }
                            }
                           $l++;
                        }
                    }
                    else if($_POST['degree_type']=='PG')
                    {
                        $l=1;
                        for ($i=0; $i <count($co_matrix) ; $i++) 
                        { 
                            $chekmat = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id'])->queryAll();

                            //print_r($chekmat); exit;

                            foreach ($chekmat as  $matvalue) 
                            {
                                $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id'])->queryAll();

                               if(!empty($cheklab))
                                {
                                    $labcomponetmodel=  CurCourseArticulationMatrixService::findOne(['cur_cam_id'=>$matvalue['cur_cam_id']]);
                                    $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                    $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                    $labcomponetmodel->coe_dept_id=$matvalue['coe_dept_id'];
                                    $labcomponetmodel->co=$co_matrix[$i];
                                    
                                    $po='po_matrix'.$l;
                                    $poo='po_matrix'.$l;                        
                                    $n=$_POST[$poo];
                                    
                                    //print_r($n); exit();

                                    $k=1;
                                    for ($j=0; $j<$deptpso['po_count']; $j++) 
                                    { 
                                        $po='po'.$k;
                                        $labcomponetmodel->$po=$n[$j]; 

                                        $k++; 
                                    }
                                    
                                    $chekpso = Yii::$app->db->createCommand("SELECT pso_count FROM cur_course_service_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id']." AND coe_dept_id=".$coe_dept_id)->queryScalar();

                                    if($chekpso>0)
                                    {
                                        $psovaluematrix='';

                                        for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                        { 
                                            $psovalue='pso_matrix'.$p.$l;
                                            $psovalueo='pso_matrix'.$p.$l; 
                                            $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                        } 
                                        
                                        $psovaluematrix=rtrim($psovaluematrix,",");
                                        $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                        $labcomponetmodel->pso_value=$psovaluematrix;
                                    }

                                    $labcomponetmodel->created_at=$created_at;
                                    $labcomponetmodel->created_by=$userid;

                                    if($labcomponetmodel->save(false))
                                    {
                                        $success++;
                                    }
                                    else
                                    {
                                        $Error++;
                                    }
                                }
                                else
                                {
                                     $Error++;
                                }
                            }
                            
                           
                           $l++;
                        }
                    }
                    else if($_POST['degree_type']=='MBA')
                    {
                        $l=1;
                        for ($i=0; $i <count($co_matrix) ; $i++) 
                        { 
                            $chekmat = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE co='".$co_matrix[$i]."' AND cur_syllabus_id=".$codatalist['cur_syllabus_id'])->queryAll();

                            foreach ($chekmat as  $matvalue) 
                            {
                                $cheklab = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id'])->queryAll();

                                if(!empty($cheklab))
                                {
                                    $labcomponetmodel=  CurCourseArticulationMatrixService::findOne(['cur_cam_id'=>$matvalue['cur_cam_id']]);
                                    $labcomponetmodel->cur_syllabus_id=$codatalist['cur_syllabus_id'];
                                    $labcomponetmodel->coe_regulation_id=$coe_regulation_id;
                                    $labcomponetmodel->coe_dept_id=$matvalue['coe_dept_id'];
                                    $labcomponetmodel->co=$co_matrix[$i];
                                    
                                    $po='po_matrix'.$l;
                                    $poo='po_matrix'.$l;                        
                                    $n=$_POST[$poo];
                                    
                                    //print_r($n); exit();

                                    $k=1;
                                    for ($j=0; $j<6; $j++) 
                                    { 
                                        $po='po'.$k;
                                        $labcomponetmodel->$po=$n[$j]; 

                                        $k++; 
                                    }

                                    $chekpso = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE cur_cam_id=".$matvalue['cur_cam_id']." AND coe_dept_id=".$coe_dept_id)->queryAll();

                                    if(!empty($chekpso))
                                    {
                                    
                                        $psovaluematrix='';

                                        for ($p=1; $p <=$deptpso['pso_count'] ; $p++) 
                                        { 
                                            $psovalue='pso_matrix'.$p.$l;
                                            $psovalueo='pso_matrix'.$p.$l; 
                                            $psovaluematrix.=$_POST[$psovalueo][0].",";                            
                                        } 
                                        
                                        $psovaluematrix=rtrim($psovaluematrix,",");

                                        $labcomponetmodel->pso_count=$deptpso['pso_count'];
                                        $labcomponetmodel->pso_value=$psovaluematrix;

                                    }

                                    $labcomponetmodel->created_at=$created_at;
                                    $labcomponetmodel->created_by=$userid;

                                    if($labcomponetmodel->save(false))
                                    {
                                        $success++;
                                    }
                                    else
                                    {
                                        $Error++;
                                    }
                                }
                                else
                                {
                                     $Error++;
                                }
                            }
                           $l++;
                        }
                    }
                }

                if($success>0)
                {
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Course Matrix updated successfully..");
                   
                    return $this->redirect(['update-service-matrix']);
                    
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Course Matrix Not update successfully! please check");
                     return $this->render('updateservice_martix', [
                    'model' => $model,
                    'codatalist'=>$codatalist,
                    'coe_dept_id'=>$coe_dept_id,
                    'subject_code'=> $subject_code,
                    'coe_regulation_id'=>$coe_regulation_id,
                    'coe_dept_id'=>$coe_dept_id,
                    ]);
                    
                }
            }
            else
            {
                return $this->render('updateservice_martix', [
                    'model' => $model,
                    'codatalist'=>$codatalist,
                    'coe_dept_id'=>$coe_dept_id,
                    'subject_code'=> $subject_code,
                    'coe_regulation_id'=>$coe_regulation_id,
                    'coe_dept_id'=>$coe_dept_id,
                    'matrixdata'=>$matrixdata
                ]);
                
            }
        }
        else
        {
            return $this->render('updateservice_martix', [
                'model' => $model,
                'codatalist'=>$codatalist
            ]);
        }
    }

    public function actionViewservice($id)
    {
        $model = new CurSyllabus();
        $labmodel = new CurLabComponent();
        $codatalist = Yii::$app->db->createCommand("SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type, A.coe_regulation_id  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id WHERE cur_syllabus_id=".$id)->queryOne();

        $colablist = Yii::$app->db->createCommand("SELECT * FROM cur_lab_component WHERE cur_syllabus_id=".$id)->queryAll();

        $co_matrix = Yii::$app->db->createCommand("SELECT * FROM cur_course_articulation_matrix WHERE cur_syllabus_id=".$id)->queryAll();

         $regulation_year = Yii::$app->db->createCommand("SELECT concat('Regulation: ',A.regulation_year,' (Batch: ',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE coe_regulation_id =".$codatalist['coe_regulation_id'])->queryScalar();


        return $this->render('viewservice', [
             'model' => $model,
                'labmodel'=>$labmodel,
                'cur_syllabus_id'=>$id,
                'codatalist'=>$codatalist,
                'colablist'=>$colablist,
                'co_matrix'=>$co_matrix,
                'regulation_year'=>$regulation_year
        ]);
    }

     public function actionDeleteserviceMatrix()
    {
        $model = new CurSyllabus();

        $codatalist='';
        if(Yii::$app->request->post()) 
        {
            $subject_code=$_POST['subject_code'];
            $coe_regulation_id=$_POST['coe_regulation_id'];
            $coe_dept_id=$_POST['coe_dept_id'];
             
            $query="SELECT A.*,B.subject_code,B.subject_name,B.subject_category_type_id, concat(L,'/',T,'/',P)as ltp, D.category_type as subject_type, E.category_type as subject_category_type, B.coe_dept_ids  FROM cur_syllabus A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_batch_id=A.coe_batch_id AND B.coe_dept_id=A.coe_dept_id JOIN cur_ltp C ON C.coe_ltp_id=B.coe_ltp_id JOIN coe_category_type D ON D.coe_category_type_id=B.subject_type_id JOIN coe_category_type E ON E.coe_category_type_id=B.subject_category_type_id JOIN cur_servicesubtodept S ON S.coe_cur_subid=B.coe_cur_id WHERE S.coe_dept_ids ='".$coe_dept_id."' AND B.subject_code='".$subject_code."' AND A.coe_regulation_id=".$coe_regulation_id;
            $codatalist = Yii::$app->db->createCommand($query)->queryOne();

             $matrixdata = Yii::$app->db->createCommand("SELECT * FROM cur_course_service_matrix WHERE approve_status=0 AND cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id ='".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id)->queryAll();

            //print_r($codatalist); exit();
            if(!empty($matrixdata))
            {
                 Yii::$app->db->createCommand("DELETE FROM cur_course_service_matrix WHERE cur_syllabus_id=".$codatalist['cur_syllabus_id']." AND coe_dept_id ='".$coe_dept_id."' AND coe_regulation_id=".$coe_regulation_id)->execute();

                  Yii::$app->ShowFlashMessages->setMsg('Success', "Course Matrix delete successfully! please check");
                 return $this->redirect(['deleteservice-matrix']);
            }
            else
            {
                 Yii::$app->ShowFlashMessages->setMsg('Error', "No Matrix found or Approved Matrix not delete! please check");

                return $this->redirect(['deleteservice-matrix']);
                
            }
        }
        else
        {
            return $this->render('deleteservice_matrix', [
                'model' => $model,
                'codatalist'=>$codatalist
            ]);
        }
    }


   
    /**
     * Deletes an existing CurSyllabus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    { 
        

        Yii::$app->db->createCommand("DELETE FROM cur_course_articulation_matrix WHERE cur_syllabus_id=".$id)->execute();
        Yii::$app->db->createCommand("DELETE FROM cur_lab_component WHERE cur_syllabus_id=".$id)->execute();

         Yii::$app->ShowFlashMessages->setMsg('Success', "Delete successfully! please check");
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteelective($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['elective-index']);
    }

    public function actionDeleteservice($id)
    {
        Yii::$app->db->createCommand("DELETE FROM cur_course_service_matrix WHERE cur_syllabus_id=".$id)->execute();
        Yii::$app->db->createCommand("DELETE FROM cur_lab_component WHERE cur_syllabus_id=".$id)->execute();
        
        $this->findModel($id)->delete();
         Yii::$app->ShowFlashMessages->setMsg('Success', "Delete successfully! please check");
        return $this->redirect(['service-index']);
    }

    /**
     * Finds the CurSyllabus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CurSyllabus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CurSyllabus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

     public function actionDeapprove($id)
    { 
        $checksyllabus = Yii::$app->db->createCommand("SELECT count(cur_syllabus_id) FROM cur_syllabus WHERE cur_syllabus_id='".$id."' AND approve_status=1")->queryScalar();

        if($checksyllabus>0)
        {
            $updated_at = date("Y-m-d H:i:s");
            $updated_by = Yii::$app->user->getId(); 
            
            $delete_dummy=Yii::$app->db->createCommand('UPDATE cur_syllabus SET approve_status=0, updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE cur_syllabus_id="'.$id.'"')->execute();

            Yii::$app->ShowFlashMessages->setMsg('Success', "Syllabus De-Approved successfully! Please Check");
            return $this->redirect(['index']);
        }
        else
        {
           // Yii::$app->ShowFlashMessages->setMsg('Error', "No Action Found! Please Check");
            return $this->redirect(['index']);
        }
        
    }
}
