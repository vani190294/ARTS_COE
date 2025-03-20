<?php

namespace app\controllers;

use Yii;
use app\models\ElectiveFacultys;
use app\models\ElectiveFacultysSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ElectiveFacultyList;
use app\models\ElectiveFacultyListSearch;
use app\models\ElectiveFacultyStudent;
use app\models\Regulation;
/**
 * ElectiveFacultysController implements the CRUD actions for ElectiveFacultys model.
 */
class ElectiveFacultysController extends Controller
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
     * Lists all ElectiveFacultys models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ElectiveFacultysSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ElectiveFacultys model.
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
     * Creates a new ElectiveFacultys model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ElectiveFacultys();

        if (Yii::$app->request->post()) 
        {
            $degree_type=$_POST['ElectiveFacultys']['degree_type'];
            $coe_dept_id=$_POST['coe_dept_id'];
            $coe_regulation_id=$_POST['ElectiveFacultys']['coe_regulation_id'];
            $semester=$_POST['ElectiveFacultys']['semester'];
            $coe_elective_option=$_POST['ElectiveFacultys']['coe_elective_option'];
            $subject_code=$_POST['ElectiveFacultys']['subject_code'];

            $faculty_ids=$_POST['faculty_ids'];

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$coe_regulation_id)->queryScalar(); 

            if($coe_dept_id==9 || ($coe_dept_id>=15 && $coe_dept_id<=19) && $coe_elective_option!=191)
            {
                 $checkelective = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.subject_code='".$subject_code."' AND A.semester=".$semester)->queryOne();

            }
            else
            {
                
                if($coe_elective_option==191)
                {
                    $checkelective = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_register_subject A WHERE A.handle_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.subject_code='".$subject_code."' AND A.semester=".$semester)->queryOne();
                }
                else
                {
                    $checkelective = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.subject_code='".$subject_code."' AND A.semester=".$semester)->queryOne();
                }
            }

            //print_r($checkelective); exit;

            $checkfaculty = Yii::$app->db->createCommand("SELECT count(*) FROM  cur_elective_facultys A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.subject_code='".$subject_code."' AND A.semester=".$semester)->queryScalar(); //exit;

             $reg = Regulation::find()->where(['coe_regulation_id'=>$coe_regulation_id])->one();
         
             $programme = Yii::$app->db->createCommand("select programme_shortname,B.coe_dept_id, A.coe_programme_id  from coe_programme  as A  join cur_department as B ON A.programme_shortname=B.dept_code   where coe_dept_id='" . $coe_dept_id . "'")->queryOne();

             $batch_map_id=Yii::$app->db->createCommand("SELECT coe_bat_deg_reg_id FROM coe_bat_deg_reg WHERE coe_programme_id='".$programme['coe_programme_id']."' AND coe_batch_id='".$reg['coe_batch_id']."'")->queryScalar();

            $success=0;
            if(!empty($checkelective) && count($faculty_ids)>0 && $checkfaculty==0)
            {
                $implode=implode(",", $faculty_ids);
                
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 

                $model = new ElectiveFacultys();      
                $model->cur_ers_id=$checkelective['cur_ers_id'];
                $model->coe_dept_id=$coe_dept_id;
                $model->degree_type=$degree_type;
                $model->coe_regulation_id=$coe_regulation_id;
                $model->coe_batch_id=$coe_batch_id;
                $model->semester=$semester;
                $model->coe_elective_option=$coe_elective_option;
                $model->elective_paper=$checkelective['elective_paper'];
                $model->subject_code=$subject_code;
                $model->faculty_ids=$implode;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                //print_r($model); exit;
                if($model->save(false))
                {
                    for ($i=0; $i <count($faculty_ids) ; $i++) 
                    { 
                        $checkfacultysubject = Yii::$app->db->createCommand("SELECT count(cur_ef_id) FROM   cur_elective_faculty_list A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$subject_code."' AND faculty_id=".$faculty_ids[$i]." AND A.semester=".$semester)->queryScalar();

                        if($checkfacultysubject==0)
                        {
                            $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_faculty_list(batch_map_id, cur_ersf_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by, faculty_id) VALUES ("'.$batch_map_id.'", "'.$model->cur_ersf_id.'", "'.$degree_type.'", "'.$coe_batch_id.'", "'.$coe_regulation_id.'", "'.$coe_dept_id.'", '.$coe_elective_option.', "'.$checkelective['elective_paper'].'", "'.$subject_code.'", "'.$semester.'", "'.$created_at.'","'.$userid.'","'.$faculty_ids[$i].'")')->execute();

                            if($insert)
                            {
                                $success++;
                            }
                        }
                    }
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Faculty Registered successfully..");
                    return $this->redirect(['index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Something Insert Error2! Please Check");
                    return $this->redirect(['create']);
                }

            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Already created or Something Insert Error! Please Check");
                return $this->redirect(['create']);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ElectiveFacultys model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) 
        {
            
            $degree_type=$model->degree_type;
            $coe_dept_id=$model->coe_dept_id;
            $coe_regulation_id=$model->coe_regulation_id;
            $semester=$model->semester;
            $coe_elective_option=$model->coe_elective_option;
            $subject_code=$model->subject_code;

            $faculty_ids=$_POST['faculty_ids'];

           

             $reg = Regulation::find()->where(['coe_regulation_id'=>$coe_regulation_id])->one();
         
             $programme = Yii::$app->db->createCommand("select programme_shortname,B.coe_dept_id, A.coe_programme_id  from coe_programme  as A  join cur_department as B ON A.programme_shortname=B.dept_code   where coe_dept_id='" . $coe_dept_id . "'")->queryOne();

             $batch_map_id=Yii::$app->db->createCommand("SELECT coe_bat_deg_reg_id FROM coe_bat_deg_reg WHERE coe_programme_id='".$programme['coe_programme_id']."' AND coe_batch_id='".$reg['coe_batch_id']."'")->queryScalar();
                
            $coe_batch_id = $reg['coe_batch_id']; 

             $checkelective = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_register_subject A WHERE A.handle_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.subject_code='".$subject_code."' AND A.semester=".$semester)->queryOne();

            $success=0;
            if(!empty($checkelective) && count($faculty_ids)>0)
            {
                $implode=implode(",", $faculty_ids);
                
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 

                $model = ElectiveFacultys::findone(['cur_ersf_id'=>$id]);        
                $model->cur_ers_id=$checkelective['cur_ers_id'];
                $model->coe_dept_id=$coe_dept_id;
                $model->degree_type=$degree_type;
                $model->coe_batch_id=$coe_batch_id;
                $model->coe_regulation_id=$coe_regulation_id;
                $model->semester=$semester;
                $model->coe_elective_option=$coe_elective_option;
                $model->elective_paper=$checkelective['elective_paper'];
                $model->subject_code=$subject_code;
                $model->faculty_ids=$implode;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                //print_r($model); exit;
                if($model->save(false))
                {
                    Yii::$app->db->createCommand('DELETE FROM cur_elective_faculty_list WHERE cur_ersf_id="'.$id.'"')->execute();

                    for ($i=0; $i <count($faculty_ids) ; $i++) 
                    { 
                        $checkfacultysubject = Yii::$app->db->createCommand("SELECT count(cur_ef_id) FROM   cur_elective_faculty_list A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$subject_code."' AND faculty_id=".$faculty_ids[$i]." AND A.semester=".$semester)->queryScalar();

                        if($checkfacultysubject==0)
                        {
                            $insert = Yii::$app->db->createCommand('INSERT INTO cur_elective_faculty_list(batch_map_id, cur_ersf_id, degree_type, coe_batch_id, coe_regulation_id, coe_dept_id, coe_elective_option, elective_paper, subject_code, semester, created_at, created_by, faculty_id) VALUES ("'.$batch_map_id.'", "'.$model->cur_ersf_id.'", "'.$degree_type.'", "'.$coe_batch_id.'", "'.$coe_regulation_id.'", "'.$coe_dept_id.'", '.$coe_elective_option.', "'.$checkelective['elective_paper'].'", "'.$subject_code.'", "'.$semester.'", "'.$created_at.'","'.$userid.'","'.$faculty_ids[$i].'")')->execute();

                            if($insert)
                            {
                                $success++;
                            }
                        }
                    }
                    Yii::$app->ShowFlashMessages->setMsg('Success', "Faculty Registered Updated successfully..");
                    return $this->redirect(['index']);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error2! Please Check");
                    return $this->redirect(['index']);
                }

            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                return $this->redirect(['index']);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ElectiveFacultys model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletedata($id)
    {
        $efid = Yii::$app->db->createCommand("SELECT cur_ef_id FROM  cur_elective_faculty_list A WHERE A.cur_ersf_id='".$id."'")->queryAll();

        $cur_ef_id=array();
        foreach ($efid as $value) 
        {
            $cur_ef_id[]=$value['cur_ef_id']; 
        }

        if(!empty($cur_ef_id))
        {
            $impode=implode(",", $cur_ef_id);
            Yii::$app->db->createCommand('DELETE FROM cur_elective_faculty_student WHERE cur_ef_id in ('.$impode.')')->execute();
        }
        
        Yii::$app->db->createCommand('DELETE FROM cur_elective_faculty_list WHERE cur_ersf_id="'.$id.'"')->execute();
        Yii::$app->db->createCommand('DELETE FROM cur_elective_facultys WHERE cur_ersf_id="'.$id.'"')->execute();
        Yii::$app->ShowFlashMessages->setMsg('Success', "Deleted Successfully");
        return $this->redirect(['index']);
    }

    /**
     * Finds the ElectiveFacultys model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ElectiveFacultys the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ElectiveFacultys::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel1($id)
    {
        if (($model = ElectiveFacultyList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFsIndex()
    {
        $searchModel = new ElectiveFacultyListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('fs-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFacultyStudentAllocate($id)
    {
        $model = $this->findModel1($id);

        $degree_type=$model->degree_type;
        $coe_dept_id=$model->coe_dept_id;
        $coe_regulation_id=$model->coe_regulation_id;
        $semester=$model->semester;
        $coe_elective_option=$model->coe_elective_option;
        $subject_code=$model->subject_code;

        $faculty_id=$model->faculty_id;

         $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$coe_regulation_id)->queryScalar(); 

        $faculty_name = Yii::$app->db->createCommand("SELECT concat(faculty_name,' (',faculty_board,')') FROM coe_valuation_faculty WHERE coe_val_faculty_id = '".$faculty_id."'")->queryScalar();

        $checkelective = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.subject_code='".$subject_code."' AND A.semester=".$semester)->queryOne();

        $checksemdata = Yii::$app->db->createCommand("SELECT dept_code,degree_type FROM cur_department WHERE coe_dept_id='".$model->coe_dept_id."'")->queryOne();

        $regulationyear = Yii::$app->db->createCommand("SELECT regulation_year FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();

        $det_cat_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE 'detain%'")->queryScalar();

        $det_disc_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE '%Discontinued%'")->queryScalar();

        $getbatch_map_ids= Yii::$app->db->createCommand("SELECT batch_map_id, B.dept_code FROM  cur_elective_nominal A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id WHERE A.coe_regulation_id=".$coe_regulation_id." AND A.coe_batch_id=".$coe_batch_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.subject_code='".$subject_code."' AND A.semester=".$semester." GROUP BY batch_map_id" )->queryAll();

        $deptdata=array();
        foreach ($getbatch_map_ids as $bvalue)
        { 

            $getsection = Yii::$app->db->createCommand("SELECT section_name FROM coe_student_mapping WHERE course_batch_mapping_id='" . $bvalue['batch_map_id'] . "' and status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') group by section_name order by section_name")->queryAll();
            
            $reg_num=array();

            foreach ($getsection as $secvalue) 
            {
               $regnums = Yii::$app->db->createCommand("SELECT B.coe_student_mapping_id,A.register_number FROM coe_student as A,coe_student_mapping as B , cur_elective_nominal as C WHERE C.register_number=A.register_number AND C.batch_map_id=B.course_batch_mapping_id AND B.student_rel_id=A.coe_student_id AND B.course_batch_mapping_id='" .$bvalue['batch_map_id'] . "' and B.status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') AND C.subject_code='".$subject_code."' AND A.register_number NOT IN (SELECT register_number FROM cur_elective_faculty_student WHERE batch_map_id='" . $bvalue['batch_map_id'] . "' AND semester='" . $semester . "' AND cur_ef_id!='".$id."' AND subject_code='".$subject_code."') and A.student_status='Active' AND B.section_name='".$secvalue['section_name']."' order by A.register_number")->queryAll();

                if(!empty($regnums))
                {
                   $reg_num[$secvalue['section_name']]=$regnums;
                }

               
            }
            
            if(!empty($reg_num))
            {
                $deptdata[$bvalue['batch_map_id']]=array('dept_code'=>$bvalue['dept_code'],'reg_num'=>$reg_num);
            }
            
        }
        
        //print_r($deptdata); exit();
        if (Yii::$app->request->post()) 
        {
            $register_number=$_POST['register_number'];

            Yii::$app->db->createCommand('DELETE FROM cur_elective_faculty_student WHERE cur_ef_id="'.$id.'"')->execute();
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();
            $Success=0;
            for ($i=0; $i <count($register_number) ; $i++) 
            {
                $stu_batchmapid= Yii::$app->db->createCommand("SELECT batch_map_id FROM cur_elective_nominal WHERE  register_number='".$register_number[$i]."'")->queryScalar();

                $stu_deptid= Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_elective_nominal WHERE  register_number='".$register_number[$i]."'")->queryScalar();

                $model1 = new ElectiveFacultyStudent();                
                $model1->cur_ef_id=$id;
                $model1->batch_map_id=$stu_batchmapid;
                $model1->coe_dept_id=$stu_deptid;
                $model1->degree_type=$degree_type;
                $model1->coe_batch_id=$coe_batch_id;
                $model1->coe_regulation_id=$coe_regulation_id;
                $model1->semester=$semester;
                $model1->coe_elective_option=$model->coe_elective_option;
                $model1->elective_paper=$model->elective_paper;
                $model1->register_number=$register_number[$i];
                $model1->subject_code=$model->subject_code;
                $model1->faculty_id=$faculty_id;
                $model1->created_at=$created_at;
                $model1->created_by=$userid;   

                if($model1->save(false))
                {
                    $Success++;
                }

            }
            
            if($Success>0)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Course Registered successfully..");
                return $this->redirect(['viewfacultystudent', 'id' => $id]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Student Registered Not successful Please check");
                return $this->redirect(['faculty-student-allocate', 'id' => $id]);
            }
        } 
        else 
        {
            return $this->render('faculty-student-allocate', [
                'model' => $model,
                'deptdata'=>$deptdata,
                'checkelective'=>$checkelective,
                'checksemdata'=>$checksemdata,
                'regulationyear'=>$regulationyear,
                'getbatch_map_ids'=>$getbatch_map_ids,
                'cur_ef_id'=>$id,
                'faculty_name'=>$faculty_name
            ]);
        }
    }

     public function actionViewfacultystudent($id)
    {
        $model = $this->findModel1($id);

        $degree_type=$model->degree_type;
        $coe_dept_id=$model->coe_dept_id;
        $coe_regulation_id=$model->coe_regulation_id;
        $semester=$model->semester;
        $coe_elective_option=$model->coe_elective_option;
        $subject_code=$model->subject_code;
        
        $faculty_id=$model->faculty_id;

        $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_elective_faculty_list WHERE cur_ef_id='".$id."'")->queryOne();

        $checksemdata = Yii::$app->db->createCommand("SELECT dept_code,degree_type FROM cur_department WHERE coe_dept_id='".$model->coe_dept_id."'")->queryOne();

        $regulationyear = Yii::$app->db->createCommand("SELECT regulation_year FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();

        $det_cat_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE 'detain%'")->queryScalar();

        $det_disc_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE '%Discontinued%'")->queryScalar();

        $faculty_name = Yii::$app->db->createCommand("SELECT concat(faculty_name,' (',faculty_board,')') FROM coe_valuation_faculty WHERE coe_val_faculty_id = '".$faculty_id."'")->queryScalar();

        $getbatch_map_ids= Yii::$app->db->createCommand("SELECT batch_map_id, B.dept_code FROM  cur_elective_nominal A JOIN cur_department B ON B.coe_dept_id=A.coe_dept_id WHERE A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.subject_code='".$subject_code."' AND A.semester=".$semester." GROUP BY batch_map_id" )->queryAll();

        $deptdata=array();
        foreach ($getbatch_map_ids as $bvalue)
        { 

            $getsection = Yii::$app->db->createCommand("SELECT section_name FROM coe_student_mapping WHERE course_batch_mapping_id='" . $bvalue['batch_map_id'] . "' and status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') group by section_name order by section_name")->queryAll();
            
            $reg_num=array();

            foreach ($getsection as $secvalue) 
            {
               $regnums = Yii::$app->db->createCommand("SELECT B.coe_student_mapping_id,A.register_number FROM coe_student as A,coe_student_mapping as B , cur_elective_faculty_student as C WHERE C.register_number=A.register_number AND C.batch_map_id=B.course_batch_mapping_id AND B.student_rel_id=A.coe_student_id AND B.course_batch_mapping_id='" .$bvalue['batch_map_id'] . "' and B.status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') and A.student_status='Active' AND B.section_name='".$secvalue['section_name']."' AND C.cur_ef_id='".$id."' order by A.register_number")->queryAll();

                if(!empty($regnums))
                {
                   $reg_num[$secvalue['section_name']]=$regnums;
                }

               
            }
            
            if(!empty($reg_num))
            {
                $deptdata[$bvalue['batch_map_id']]=array('dept_code'=>$bvalue['dept_code'],'reg_num'=>$reg_num);
            }
            
        }
        
        //print_r($deptdata); exit;
        if (Yii::$app->request->post())
        {
            $updated_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId();

            $updated=Yii::$app->db->createCommand('UPDATE cur_elective_faculty_student SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_ef_id="' . $id . '"')->execute();

            $updated1= Yii::$app->db->createCommand('UPDATE cur_elective_faculty_list SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_ef_id="' . $id . '"')->execute();

            $updated2= Yii::$app->db->createCommand('UPDATE cur_elective_facultys SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_ersf_id="' . $model->cur_ersf_id . '"')->execute();

                
            Yii::$app->ShowFlashMessages->setMsg('Success', "Approved Successfully!");
                
            return $this->redirect(['viewfacultystudent', 'id' => $id]);
            
        }
        else
        {
            if(!empty($checkelective))
            {
                return $this->render('Viewfacultystudent', [
                    'model' => $model,
                    'checkelective'=>$checkelective,
                    'checksemdata'=>$checksemdata,
                    'regulationyear'=>$regulationyear,
                    'cur_ef_id'=>$id,
                    'faculty_name'=>$faculty_name,
                    'deptdata'=>$deptdata,
                    'getbatch_map_ids'=>$getbatch_map_ids,
                    ]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "No Data Found, Please Check");
                return $this->redirect(['index']);
            }
        }
    }
}
