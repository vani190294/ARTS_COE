<?php

namespace app\controllers;

use Yii;
use app\models\ElectiveStuSubject;
use app\models\ElectiveStuSubjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ElectiveNominal;
use app\models\Regulation;
use app\models\ElectiveRegister;
use yii\helpers\ArrayHelper;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Shared_Date;
/**
 * ElectiveStuSubjectController implements the CRUD actions for ElectiveStuSubject model.
 */
class ElectiveStuSubjectController extends Controller
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
     * Lists all ElectiveStuSubject models.
     * @return mixed
     */
    public function actionIndex()
    {
         $_SESSION['elecctive_nominal']='Other';
        $searchModel = new ElectiveStuSubjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ElectiveStuSubject model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
       
        $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_elective_stu_subject WHERE cur_erss_id='".$id."'")->queryOne();

        $checksemdata = Yii::$app->db->createCommand("SELECT dept_code,degree_type FROM cur_department WHERE coe_dept_id='".$model->coe_dept_id."'")->queryOne();

        $regulationyear = Yii::$app->db->createCommand("SELECT concat('Regulation: ',A.regulation_year,' (Batch: ',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE coe_regulation_id =".$model->coe_regulation_id)->queryScalar();

        if (Yii::$app->request->post())
        {
            $updated_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId();

            $updated=Yii::$app->db->createCommand('UPDATE cur_elective_nominal SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_erss_id="' . $id . '"')->execute();

            $updated1= Yii::$app->db->createCommand('UPDATE cur_elective_stu_subject SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_erss_id="' . $id . '"')->execute();

            if($updated1)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Approved Successfully!");
                
                $model = $this->findModel($id);
                $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_elective_stu_subject WHERE cur_erss_id='".$id."'")->queryOne();

                return $this->render('view', [
                    'model' => $model,
                    'checkelective'=>$checkelective,
                    'checksemdata'=>$checksemdata,
                    'regulationyear'=>$regulationyear,
                    'cur_erss_id'=>$id
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
                    'regulationyear'=>$regulationyear,
                    'cur_erss_id'=>$id
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
     * Creates a new ElectiveStuSubject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ElectiveStuSubject();
        $student = new ElectiveNominal();

        if (Yii::$app->request->post()) 
        {
            $degree_type=$_POST['ElectiveStuSubject']['degree_type'];
            $coe_dept_id=$_POST['coe_dept_id'];
            $coe_regulation_id=$_POST['ElectiveStuSubject']['coe_regulation_id'];
            $semester=$_POST['ElectiveStuSubject']['semester'];
            $coe_elective_option=$_POST['ElectiveStuSubject']['coe_elective_option'];
            $subject_code=$_POST['ElectiveStuSubject']['subject_code'];
            
            $batch_map_id=$_POST['batch_map_id'];
            
            $checkelective = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_stu_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.subject_code='".$subject_code."' AND A.semester=".$semester)->queryOne();
            
            if(empty($checkelective) && $batch_map_id!='')
            {
                $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$coe_regulation_id)->queryScalar(); 

                $electivepaper = Yii::$app->db->createCommand("SELECT elective_paper,cur_ers_id FROM cur_elective_register_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND subject_code='".$subject_code."' AND A.semester=".$semester)->queryOne();
                
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 

                $model = new ElectiveStuSubject();  
                $model->coe_batch_id=$coe_batch_id;    
                $model->batch_map_id=$batch_map_id;          
                $model->cur_ers_id=$electivepaper['cur_ers_id'];
                $model->coe_dept_id=$coe_dept_id;
                $model->degree_type=$degree_type;
                $model->coe_regulation_id=$coe_regulation_id;
                $model->semester=$semester;
                $model->coe_elective_option=$coe_elective_option;
                $model->elective_paper=$electivepaper['elective_paper'];
                $model->subject_code=$subject_code;
                $model->created_at=$created_at;
                $model->created_by=$userid;
                
                if($model->save(false))
                {
                    return $this->redirect(['createstureg', 'id' => $model->cur_erss_id]);
       
                }
                else
                {
                    // echo "<pre>";
                    //print_r($model); exit;

                    Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                    return $this->redirect(['create']);
                }

                //return $this->redirect(['view', 'id' => $model->cur_erss_id]);
            }
            else
            {
                 
                Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                return $this->redirect(['create']);
            }
           
        } else {
            return $this->render('create', [
                'model' => $model,
                'student'=> $student
            ]);
        }
    }

    public function actionCreatestureg($id)
    {
        $model = $this->findModel($id);
       
        $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_elective_stu_subject WHERE cur_erss_id='".$id."'")->queryOne();

        $checksemdata = Yii::$app->db->createCommand("SELECT dept_code,degree_type FROM cur_department WHERE coe_dept_id='".$model->coe_dept_id."'")->queryOne();

        $regulationyear = Yii::$app->db->createCommand("SELECT regulation_year FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();

        $coe_dept_id =$model->coe_dept_id;
        $coe_regulation_id = $model->coe_regulation_id;
        $degree_type = $model->degree_type;
        $semester = $model->semester;
        $coe_elective_option = $model->coe_elective_option;
        $elective_paper = $model->elective_paper;
        $subject_code = $model->subject_code;

        $reg = Regulation::find()->where(['coe_regulation_id'=>$coe_regulation_id])->one();
         
        $programme = Yii::$app->db->createCommand("select programme_shortname,B.coe_dept_id, A.coe_programme_id  from coe_programme  as A  join cur_department as B ON A.programme_shortname=B.dept_code   where coe_dept_id='" . $coe_dept_id . "'")->queryOne();

        $batch_map_id=Yii::$app->db->createCommand("SELECT coe_bat_deg_reg_id FROM coe_bat_deg_reg WHERE coe_programme_id='".$programme['coe_programme_id']."' AND coe_batch_id='".$reg['coe_batch_id']."'")->queryScalar();

        $det_cat_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE 'detain%'")->queryScalar();

        $det_disc_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE '%Discontinued%'")->queryScalar();

        $getsection = Yii::$app->db->createCommand("SELECT section_name FROM coe_student_mapping WHERE course_batch_mapping_id='" . $batch_map_id . "' and status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') group by section_name order by section_name")->queryAll();
        
        $reg_num=array();

        foreach ($getsection as $secvalue) 
        {
           $regnums = Yii::$app->db->createCommand("SELECT B.coe_student_mapping_id,A.register_number,A.name FROM coe_student as A,coe_student_mapping as B WHERE B.student_rel_id=A.coe_student_id and B.course_batch_mapping_id='" . $batch_map_id . "' and B.status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') and A.student_status='Active' AND B.section_name='".$secvalue['section_name']."' AND A.register_number NOT IN (SELECT register_number FROM cur_elective_nominal WHERE batch_map_id='" . $batch_map_id . "' AND semester='" . $semester . "' AND coe_elective_option='".$coe_elective_option."' AND elective_paper='".$elective_paper."') order by A.register_number")->queryAll();

           $reg_num[$secvalue['section_name']]=$regnums;
        }
        

        if (Yii::$app->request->post())
        {
            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$coe_regulation_id)->queryScalar(); 

            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();
           
            $register_number=$_POST['register_number'];
            
            $Success=0;
            if(count($register_number)>0)
            {

                for ($i=0; $i <count($register_number) ; $i++) 
                { 
                    $model1 = new ElectiveNominal();                
                    $model1->cur_erss_id=$model->cur_erss_id;
                    $model1->batch_map_id=$batch_map_id;
                    $model1->coe_dept_id=$coe_dept_id;
                    $model1->degree_type=$degree_type;
                    $model1->coe_batch_id=$coe_batch_id;    
                    $model1->coe_regulation_id=$coe_regulation_id;
                    $model1->semester=$semester;
                    $model1->coe_elective_option=$model->coe_elective_option;
                    $model1->elective_paper=$model->elective_paper;
                    $model1->register_number=$register_number[$i];
                    $model1->subject_code=$model->subject_code;
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
                    return $this->redirect(['view', 'id' => $id]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Student Registered Not successful Please check");
                    return $this->redirect(['createstureg', 'id' => $model->cur_erss_id]);
                }
                
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Student Registered Not successful Please check");
                return $this->redirect(['createstureg', 'id' => $model->cur_erss_id]);
            }
            
        }
        else
        {
            if(!empty($checkelective))
            {
                return $this->render('create_stu_regform', [
                    'model' => $model,
                    'checkelective'=>$checkelective,
                    'checksemdata'=>$checksemdata,
                    'regulationyear'=>$regulationyear,
                    'cur_erss_id'=>$id,
                    'reg_num'=>$reg_num,
                    'getsection'=>$getsection
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
     * Updates an existing ElectiveStuSubject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $student = new ElectiveNominal();
        if (Yii::$app->request->post()) 
        {
            $degree_type=$model->degree_type;
            $coe_dept_id=$model->coe_dept_id;
            $coe_regulation_id=$model->coe_regulation_id;
            $semester=$model->semester;
            $coe_elective_option=$model->coe_elective_option;
            $subject_code=$model->subject_code;
            $batch_map_id=$model->batch_map_id;

            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId();
           
            $register_number=$_POST['register_number'];
            
            $Success=0;
            if(count($register_number)>0)
            {
                Yii::$app->db->createCommand('DELETE FROM cur_elective_nominal WHERE cur_erss_id="'.$id.'"')->execute();

                $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$coe_regulation_id)->queryScalar(); 

                for ($i=0; $i <count($register_number) ; $i++) 
                { 
                    $model1 = new ElectiveNominal();                
                    $model1->cur_erss_id=$model->cur_erss_id;
                    $model1->batch_map_id=$batch_map_id;
                    $model1->coe_dept_id=$coe_dept_id;
                    $model1->degree_type=$degree_type;
                    $model1->coe_batch_id=$coe_batch_id;    
                    $model1->coe_regulation_id=$coe_regulation_id;
                    $model1->semester=$semester;
                    $model1->coe_elective_option=$model->coe_elective_option;
                    $model1->elective_paper=$model->elective_paper;
                    $model1->register_number=$register_number[$i];
                    $model1->subject_code=$model->subject_code;
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
                    return $this->redirect(['view', 'id' => $id]);
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error', "Student Registered Not successful Please check");
                    return $this->redirect(['update', 'id' => $id]);
                }
                
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Student Registered Not successful Please check");
                return $this->redirect(['update', 'id' => $id]);
            }
           
        } else {

            $degree_type=$model->degree_type;
            $coe_dept_id=$model->coe_dept_id;
            $coe_regulation_id=$model->coe_regulation_id;
            $semester=$model->semester;
            $coe_elective_option=$model->coe_elective_option;
            $subject_code=$model->subject_code;
            $elective_paper=$model->elective_paper;

            $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_elective_stu_subject WHERE cur_erss_id='".$id."'")->queryOne();

            $checksemdata = Yii::$app->db->createCommand("SELECT dept_code,degree_type FROM cur_department WHERE coe_dept_id='".$model->coe_dept_id."'")->queryOne();

            $regulationyear = Yii::$app->db->createCommand("SELECT regulation_year FROM coe_regulation WHERE coe_regulation_id=".$model->coe_regulation_id)->queryScalar();

            $reg = Regulation::find()->where(['coe_regulation_id'=>$coe_regulation_id])->one();
         
            $programme = Yii::$app->db->createCommand("select programme_shortname,B.coe_dept_id, A.coe_programme_id  from coe_programme  as A  join cur_department as B ON A.programme_shortname=B.dept_code   where coe_dept_id='" . $coe_dept_id . "'")->queryOne();

            $batch_map_id=Yii::$app->db->createCommand("SELECT coe_bat_deg_reg_id FROM coe_bat_deg_reg WHERE coe_programme_id='".$programme['coe_programme_id']."' AND coe_batch_id='".$reg['coe_batch_id']."'")->queryScalar();

            $det_cat_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE 'detain%'")->queryScalar();

            $det_disc_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE '%Discontinued%'")->queryScalar();

            $getsection = Yii::$app->db->createCommand("SELECT section_name FROM coe_student_mapping WHERE course_batch_mapping_id='" . $batch_map_id . "' and status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') group by section_name order by section_name")->queryAll();
            
            $reg_num=array();

            foreach ($getsection as $secvalue) 
            {

               $regnums = Yii::$app->db->createCommand("SELECT B.coe_student_mapping_id,A.register_number,A.name FROM coe_student as A,coe_student_mapping as B WHERE B.student_rel_id=A.coe_student_id and B.course_batch_mapping_id='" . $batch_map_id . "' and B.status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') and A.student_status='Active' AND B.section_name='".$secvalue['section_name']."' AND A.register_number NOT IN (SELECT register_number FROM cur_elective_nominal WHERE batch_map_id='" . $batch_map_id . "' AND semester='" . $semester . "' AND elective_paper='".$elective_paper."'  AND coe_elective_option='".$coe_elective_option."') order by A.register_number")->queryAll();

               $reg_num[$secvalue['section_name']]=$regnums;
            }

            return $this->render('create_stu_regformupdate', [
                    'model' => $model,
                    'checkelective'=>$checkelective,
                    'checksemdata'=>$checksemdata,
                    'regulationyear'=>$regulationyear,
                    'cur_erss_id'=>$id,
                    'reg_num'=>$reg_num,
                    'getsection'=>$getsection
                    ]);
        }
    }

    /**
     * Deletes an existing ElectiveStuSubject model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletedata($id)
    {
        Yii::$app->db->createCommand('DELETE FROM cur_elective_nominal WHERE cur_erss_id="'.$id.'"')->execute();
        Yii::$app->db->createCommand('DELETE FROM cur_elective_stu_subject WHERE cur_erss_id="'.$id.'"')->execute();
        Yii::$app->ShowFlashMessages->setMsg('Success', "Deleted Successfully");
        return $this->redirect(['index']);
    }

    /**
     * Finds the ElectiveStuSubject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ElectiveStuSubject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ElectiveStuSubject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMbaIndex()
    {
        $_SESSION['elecctive_nominal']='MBA';
        $searchModel = new ElectiveStuSubjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('mba-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

   public function actionMbaCreate()
    {
        $model = new ElectiveStuSubject();
        $student = new ElectiveNominal();

        if (Yii::$app->request->post()) 
        {
            $degree_type='MBA';
            $coe_dept_id=26;
            $coe_regulation_id=$_POST['ElectiveStuSubject']['coe_regulation_id'];
            $semester=$_POST['ElectiveStuSubject']['semester'];
            $coe_elective_option=192;
            $subject_code='';

            $reg = Regulation::find()->where(['coe_regulation_id'=>$coe_regulation_id])->one();

            $coe_batch_id=$reg['coe_batch_id'];

            $programme = Yii::$app->db->createCommand("select programme_shortname,B.coe_dept_id, A.coe_programme_id  from coe_programme  as A  join cur_department as B ON A.programme_shortname=B.dept_code   where coe_dept_id='" . $coe_dept_id . "'")->queryOne();

            $batch_map_id=Yii::$app->db->createCommand("SELECT coe_bat_deg_reg_id FROM coe_bat_deg_reg WHERE coe_programme_id='".$programme['coe_programme_id']."' AND coe_batch_id='".$coe_batch_id."'")->queryScalar();

            $register_number='';

            $checkelective = Yii::$app->db->createCommand("SELECT cur_erss_id FROM  cur_elective_stu_subject A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_batch_id=".$coe_batch_id." AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.semester=".$semester)->queryOne();
                    
            $created_at = date("Y-m-d H:i:s");
            $userid = Yii::$app->user->getId(); 
            if(empty($checkelective))
            {
                $uploaded_file = $_FILES["uploaded_file"]["name"]; 
                $path_parts = pathinfo($uploaded_file);
                $filename=$path_parts['filename'];//exit();
                $save_folder = Yii::getAlias('@webroot').'/resources/uploaded/';
                $saving_file_name = date('d-m-Y-H-i-s')."-".str_replace(" ", "-", $uploaded_file);
                $save_in_folder = $save_folder.$saving_file_name;
                if(move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $save_in_folder))
                {
                    if(!empty($save_in_folder))
                    {    

                        $model = new ElectiveStuSubject();    
                        $model->coe_batch_id=$coe_batch_id;         
                        $model->batch_map_id=$batch_map_id;          
                        $model->cur_ers_id=0;
                        $model->coe_dept_id=$coe_dept_id;
                        $model->degree_type=$degree_type;
                        $model->coe_regulation_id=$coe_regulation_id;
                        $model->semester=$semester;
                        $model->coe_elective_option=$coe_elective_option;
                        $model->elective_paper=0;
                        $model->subject_code=$subject_code;
                        $model->created_at=$created_at;
                        $model->created_by=$userid;

                        if($model->save(false))
                        {   
                            $cur_erss_id=$model->cur_erss_id;
                            $sheetData = $this->getExcelproperties($save_in_folder);
                            if(!empty($sheetData))
                            {

                                $interate = 1; // Check only 1 time for Sheet Columns
                                foreach($sheetData as $k => $line)
                                { 
                                    $exam_columns=['A'=>'Register Number','B'=>'Course 1','C'=>'Course 2','D'=>'Course 3','E'=>'Course 4','F'=>'Course 5'];

                                    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E'],'F'=>$line['F']];

                                    $mis_match=array_diff_assoc($exam_columns,$template_clumns);
                                    if($interate==1 && count($mis_match)!=0 && count($mis_match)>0)
                                    {
                                        $misMatchingColumns = '';
                                        foreach ($mis_match as $key => $value) {
                                            $misMatchingColumns .= $key.", ";
                                        }
                                        $misMatchingColumns = trim($misMatchingColumns,', ');
                                        $misMatchingColumns = wordwrap($misMatchingColumns, 10, "<br />\n");
                                        Yii::$app->ShowFlashMessages->setMsg('Error',"<b style='font-size: 17px;'> Column (s) ".$misMatchingColumns." Are Not matching with our Original ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Template </b> Please use the Original Sample Template from the Download Link!!");
                                        return Yii::$app->response->redirect(Url::to(['elective-stu-subject/mba-create']));
                                    }
                                    else
                                    {
                                        break;
                                    }
                                    $interate +=7;
                                    
                                }
                                unset($sheetData[1]);
                                
                                $dispResults = [];
                                
                                $totalSuccess = 0;
                                $importResults = [];
                                $created_by = Yii::$app->user->getId();
                                $created_at = $updated = date("Y-m-d H:i:s");

                                function array_filter_recursive( array $array, callable $callback = null ) {
                                    $array = is_callable( $callback ) ? array_filter( $array, $callback ) : array_filter($array, function($v){
                                        return $v !== false && !is_null($v) && ($v != '' || $v == '0');
                                    });
                                    foreach ( $array as &$value ) {
                                        if ( is_array( $value )) {
                                            $value = call_user_func( __FUNCTION__, $value, $callback );
                                        }
                                    }
                                 
                                    return $array;
                                }
                                $sheetData = array_filter_recursive($sheetData);
                                
                                if(empty($sheetData) || count($sheetData)==0)
                                {
                                    Yii::$app->ShowFlashMessages->setMsg('Error',"Oooops You are trying to upload the empty file <br />");
                                    unlink($fileLocation);
                                    return Yii::$app->response->redirect(['elective-stu-subject/mba-create']);
                                }
                                else
                                { 
                                    foreach($sheetData as $k => $line)
                                    {  
                                        if($line['A']!='Register Number')
                                        {
                                            $subject_codedata=array();
                                            $register_number=$line['A'];
                                            $subject_codedata[]=$line['B'];
                                            $subject_codedata[]=$line['C'];
                                            $subject_codedata[]=$line['D'];
                                            $subject_codedata[]=$line['E'];
                                            $subject_codedata[]=$line['F'];
                                            //print_r($subject_code); exit;
                                            $success=0; $messages='';
                                            for ($j=0; $j <count($subject_codedata); $j++) 
                                            {
                                                $check_mbaelectives = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_subject WHERE subject_code='".$subject_codedata[$j]."'")->queryOne();

                                                $checkelectives = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_nominal A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.register_number='".$register_number."'  AND A.subject_code='".$subject_codedata[$j]."' AND A.coe_elective_option=192 AND A.semester=".$semester)->queryOne();
                                                if(empty($check_mbaelectives))
                                                {
                                                    $messages.= $subject_codedata[$j].'<span style="background-color:red !important;"> Subject Code Not Found</span> <br>';
                                                }
                                                else if(empty($checkelectives) && !empty($subject_codedata[$j]))
                                                {

                                                    $model1 = new ElectiveNominal();                
                                                    $model1->cur_erss_id=$cur_erss_id;
                                                    $model1->coe_batch_id=$coe_batch_id;  
                                                    $model1->batch_map_id=$batch_map_id;
                                                    $model1->coe_dept_id=$coe_dept_id;
                                                    $model1->degree_type=$degree_type;
                                                    $model1->coe_regulation_id=$coe_regulation_id;
                                                    $model1->semester=$semester;
                                                    $model1->coe_elective_option=192;
                                                    $model1->elective_paper=0;
                                                    $model1->register_number=$register_number;
                                                    $model1->subject_code=$subject_codedata[$j];
                                                    $model1->created_at=$created_at;
                                                    $model1->created_by=$userid;
                                                    if($model1->save(false))
                                                    {
                                                        $success++;
                                                        $messages.= $subject_codedata[$j].'<span style="background-color:green !important;"> Added Successfully </span><br>';
                                                    }
                                                    else
                                                    {
                                                        $messages.= $subject_codedata[$j].'<span style="background-color:red !important;"> Not Added </span><br>';
                                                    }
                                                }
                                                else
                                                {
                                                    $messages.= $subject_codedata[$j].'<span style="background-color:red !important;"> Already exist</span> <br>';
                                                }

                                            }

                                            if($success==5)
                                            {
                                                $totalSuccess++;
                                            }
                                            //echo $success;  exit;
                                            //$dispResults[] = array_merge($line, ['register_number'=>$register_number,'type' => 'S',  'message' => $messages]);
                                        }
                                    }
                                    

                                    if($totalSuccess>0)
                                    {
                                        Yii::$app->ShowFlashMessages->setMsg('Success',$totalSuccess." Studnet Successfully Inserted");
                                       
                                        return $this->redirect(['viewmba', 'id' => $cur_erss_id]);
                                    }
                                    else
                                    {
                                        Yii::$app->ShowFlashMessages->setMsg('Error',"Nothing to Insert Data Please Check");
                                        return $this->redirect(['elective-stu-subject/mba-create']);
                                    }
                                    
                                }
                               
                            } // Not Empty of Sheet Ends Here 
                            else 
                            {

                                Yii::$app->ShowFlashMessages->setMsg('Error',"Unable to Resolve your Submission.");
                                return $this->redirect(['elective-stu-subject/mba-create']);
                                
                            }
                        }
                        else
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Error', "Insert Error! Please Check");
                            return $this->redirect(['mba-create']);
                        }

                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',"Unable to Import the File");
                        return $this->redirect(['elective-stu-subject/mba-create']);
                    }
                        
                }
                else
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',"Unable to Import the File");
                    return $this->redirect(['elective-stu-subject/mba-create']);
                }
            }
            else
            {
                 $checkelective = Yii::$app->db->createCommand("SELECT cur_erss_id FROM  cur_elective_stu_subject A WHERE A.approve_status=1 AND A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.coe_elective_option='".$coe_elective_option."' AND A.semester=".$semester)->queryOne();

                if(!empty($checkelective))
                {
                    Yii::$app->ShowFlashMessages->setMsg('Error',"Course Approved! Unable to upload Nominal");
                        return $this->redirect(['elective-stu-subject/mba-create']);
                }
                else
                {
                    $uploaded_file = $_FILES["uploaded_file"]["name"]; 
                    $path_parts = pathinfo($uploaded_file);
                    $filename=$path_parts['filename'];//exit();
                    $save_folder = Yii::getAlias('@webroot').'/resources/uploaded/';
                    $saving_file_name = date('d-m-Y-H-i-s')."-".str_replace(" ", "-", $uploaded_file);
                    $save_in_folder = $save_folder.$saving_file_name;
                    if(move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $save_in_folder))
                    {
                        if(!empty($save_in_folder))
                        {  
                            $cur_erss_id=$checkelective['cur_erss_id'];
                            $sheetData = $this->getExcelproperties($save_in_folder);
                            if(!empty($sheetData))
                            {

                                $interate = 1; // Check only 1 time for Sheet Columns
                                foreach($sheetData as $k => $line)
                                { 
                                    $exam_columns=['A'=>'Register Number','B'=>'Course 1','C'=>'Course 2','D'=>'Course 3','E'=>'Course 4','F'=>'Course 5'];

                                    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E'],'F'=>$line['F']];

                                    $mis_match=array_diff_assoc($exam_columns,$template_clumns);
                                    if($interate==1 && count($mis_match)!=0 && count($mis_match)>0)
                                    {
                                        $misMatchingColumns = '';
                                        foreach ($mis_match as $key => $value) {
                                            $misMatchingColumns .= $key.", ";
                                        }
                                        $misMatchingColumns = trim($misMatchingColumns,', ');
                                        $misMatchingColumns = wordwrap($misMatchingColumns, 10, "<br />\n");
                                        Yii::$app->ShowFlashMessages->setMsg('Error',"<b style='font-size: 17px;'> Column (s) ".$misMatchingColumns." Are Not matching with our Original ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Template </b> Please use the Original Sample Template from the Download Link!!");
                                        return Yii::$app->response->redirect(Url::to(['elective-stu-subject/mba-create']));
                                    }
                                    else
                                    {
                                        break;
                                    }
                                    $interate +=7;
                                    
                                }
                                //unset($sheetData[1]);
                                
                                $dispResults = [];
                                
                                $totalSuccess = 0;
                                $importResults = [];
                                $created_by = Yii::$app->user->getId();
                                $created_at = $updated = date("Y-m-d H:i:s");

                                function array_filter_recursive( array $array, callable $callback = null ) {
                                    $array = is_callable( $callback ) ? array_filter( $array, $callback ) : array_filter($array, function($v){
                                        return $v !== false && !is_null($v) && ($v != '' || $v == '0');
                                    });
                                    foreach ( $array as &$value ) {
                                        if ( is_array( $value )) {
                                            $value = call_user_func( __FUNCTION__, $value, $callback );
                                        }
                                    }
                                 
                                    return $array;
                                }
                                $sheetData = array_filter_recursive($sheetData);
                                
                                if(empty($sheetData) || count($sheetData)==0)
                                {
                                    Yii::$app->ShowFlashMessages->setMsg('Error',"Oooops You are trying to upload the empty file <br />");
                                    unlink($fileLocation);
                                    return Yii::$app->response->redirect(['elective-stu-subject/mba-create']);
                                }
                                else
                                { 
                                    
                                    //print_r($sheetData); exit;
                                    foreach($sheetData as $k => $line)
                                    {  
                                        if($line['A']!='Register Number')
                                        {
                                            $subject_codedata=array();
                                            $register_number=$line['A'];
                                            $subject_codedata[]=$line['B'];
                                            $subject_codedata[]=$line['C'];
                                            $subject_codedata[]=$line['D'];
                                            $subject_codedata[]=$line['E'];
                                            $subject_codedata[]=$line['F'];
                                            //print_r($subject_code); exit;

                                            $updatedelectives = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_nominal A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.register_number='".$register_number."' AND A.semester=".$semester)->queryOne();

                                            if(!empty($updatedelectives))
                                            {
                                                Yii::$app->db->createCommand('DELETE FROM cur_elective_nominal WHERE cur_erss_id="'.$cur_erss_id.'" AND register_number="'.$register_number.'" AND semester='.$semester)->execute();
                                            }


                                            $success=0; $messages='';
                                            for ($j=0; $j <count($subject_codedata); $j++) 
                                            {
                                                $check_mbaelectives = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_subject WHERE subject_code='".$subject_codedata[$j]."'")->queryOne();

                                                $checkelectives = Yii::$app->db->createCommand("SELECT * FROM  cur_elective_nominal A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND A.degree_type='".$degree_type."' AND A.register_number='".$register_number."'  AND A.subject_code='".$subject_codedata[$j]."' AND A.coe_elective_option=192 AND A.semester=".$semester)->queryOne();

                                                if(!empty($check_mbaelectives))
                                                {
                                                   
                                                    $model1 = new ElectiveNominal();                
                                                    $model1->cur_erss_id=$cur_erss_id;
                                                    $model1->batch_map_id=$batch_map_id;
                                                    $model1->coe_dept_id=$coe_dept_id;
                                                    $model1->degree_type=$degree_type;
                                                    $model1->coe_regulation_id=$coe_regulation_id;
                                                    $model1->semester=$semester;
                                                    $model1->coe_elective_option=192;
                                                    $model1->elective_paper=0;
                                                    $model1->register_number=$register_number;
                                                    $model1->subject_code=$subject_codedata[$j];
                                                    $model1->created_at=$created_at;
                                                    $model1->created_by=$userid;
                                                    if($model1->save(false))
                                                    {
                                                        $success++;
                                                        $messages.= $subject_codedata[$j].'<span style="background-color:green !important;"> Added Successfully </span><br>';
                                                    }
                                                    else
                                                    {
                                                        $messages.= $subject_codedata[$j].'<span style="background-color:red !important;"> Not Added </span><br>';
                                                    }
                                                }
                                                else if(empty($checkelectives) && !empty($subject_codedata[$j]))
                                                {

                                                    $model1 = new ElectiveNominal();                
                                                    $model1->cur_erss_id=$cur_erss_id;
                                                    $model1->batch_map_id=$batch_map_id;
                                                    $model1->coe_dept_id=$coe_dept_id;
                                                    $model1->degree_type=$degree_type;
                                                    $model1->coe_regulation_id=$coe_regulation_id;
                                                    $model1->semester=$semester;
                                                    $model1->coe_elective_option=192;
                                                    $model1->elective_paper=0;
                                                    $model1->register_number=$register_number;
                                                    $model1->subject_code=$subject_codedata[$j];
                                                    $model1->created_at=$created_at;
                                                    $model1->created_by=$userid;
                                                    if($model1->save(false))
                                                    {
                                                        $success++;
                                                        $messages.= $subject_codedata[$j].'<span style="background-color:green !important;"> Added Successfully </span><br>';
                                                    }
                                                    else
                                                    {
                                                        $messages.= $subject_codedata[$j].'<span style="background-color:red !important;"> Not Added </span><br>';
                                                    }
                                                }
                                                else
                                                {
                                                    $messages.= $subject_codedata[$j].'<span style="background-color:red !important;"> Already exist</span> <br>';
                                                }

                                            }

                                            if($success==5)
                                            {
                                                $totalSuccess++;
                                            }
                                            //echo $success;  exit;
                                            //$dispResults[] = array_merge($line, ['register_number'=>$register_number,'type' => 'S',  'message' => $messages]);
                                        }
                                    } 
                                    

                                    if($totalSuccess>0)
                                    {
                                        Yii::$app->ShowFlashMessages->setMsg('Success',$totalSuccess." Student Successfully Inserted");
                                       
                                        return $this->redirect(['viewmba', 'id' => $cur_erss_id]);
                                    }
                                    else
                                    {
                                        Yii::$app->ShowFlashMessages->setMsg('Error',"Nothing to Insert Data Please Check");
                                        return $this->redirect(['elective-stu-subject/mba-create']);
                                    }
                                    
                                }
                               
                            } // Not Empty of Sheet Ends Here 
                            else 
                            {

                                Yii::$app->ShowFlashMessages->setMsg('Error',"Unable to Resolve your Submission.");
                                return $this->redirect(['elective-stu-subject/mba-create']);
                                
                            }
                        }
                        else
                        {
                            Yii::$app->ShowFlashMessages->setMsg('Error',"Unable to Import the File");
                            return $this->redirect(['elective-stu-subject/mba-create']);
                        }
                            
                    }
                    else
                    {
                        Yii::$app->ShowFlashMessages->setMsg('Error',"Unable to Import the File");
                        return $this->redirect(['elective-stu-subject/mba-create']);
                    }
                }
            }
           
        } else {
            return $this->render('mba-create', [
                'model' => $model,
                'student'=> $student
            ]);
        }
    }

    public function actionDownloadSample()
    {
        
        $path = Yii::getAlias('@webroot').'/resources/samples/mbaelective.xlsx'; 

        if(file_exists($path)) {
            return \Yii::$app->response->sendFile($path);
        }
        else
            throw new NotFoundHttpException('The requested file does not exist.');  
    }

    public function actionViewmba($id)
    {
        $model = $this->findModel($id);
       
        $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_elective_stu_subject WHERE cur_erss_id='".$id."'")->queryOne();

        $checkdata = Yii::$app->db->createCommand("SELECT dept_code,degree_type FROM cur_department WHERE coe_dept_id='".$model->coe_dept_id."'")->queryOne();

        $semm=" AND sem".$model->semester."!=''";
        $semcolumn="sem".$model->semester;
        $checksemdata = Yii::$app->db->createCommand("SELECT ".$semcolumn." FROM  cur_credit_distribution_sem A JOIN cur_aicte_norms B ON B.cur_an_id=A.cur_stream_id WHERE A.coe_dept_id='".$model->coe_dept_id."' AND A.coe_regulation_id=".$model->coe_regulation_id." AND A.degree_type='".$model->degree_type."' AND stream_name IN ('PEC')".$semm)->queryScalar();

        $det_cat_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE 'detain%'")->queryScalar();

        $det_disc_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE '%Discontinued%'")->queryScalar();

        $reg_num = Yii::$app->db->createCommand("SELECT B.coe_student_mapping_id,A.register_number FROM coe_student as A,coe_student_mapping as B WHERE B.student_rel_id=A.coe_student_id and B.course_batch_mapping_id='" . $model->batch_map_id . "' and B.status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') and A.student_status='Active' order by A.register_number")->queryAll();

        $regulationyear = Yii::$app->db->createCommand("SELECT concat('Regulation: ',A.regulation_year,' (Batch: ',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE coe_regulation_id =".$model->coe_regulation_id)->queryScalar();

        if (Yii::$app->request->post())
        {
            $updated_at = date("Y-m-d H:i:s");
            $updateBy = Yii::$app->user->getId();

            $updated=Yii::$app->db->createCommand('UPDATE cur_elective_nominal SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_erss_id="' . $id . '"')->execute();

            $updated1= Yii::$app->db->createCommand('UPDATE cur_elective_stu_subject SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_erss_id="' . $id . '"')->execute();

            if($updated1)
            {
                Yii::$app->ShowFlashMessages->setMsg('Success', "Approved Successfully!");
                
                $model = $this->findModel($id);
                $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_elective_stu_subject WHERE cur_erss_id='".$id."'")->queryOne();

                return $this->render('viewmba', [
                    'model' => $model,
                    'checkelective'=>$checkelective,
                    'checksemdata'=>$checksemdata,
                    'checkdata'=>$checkdata,
                    'reg_num'=> $reg_num,
                    'regulationyear'=>$regulationyear,
                    'cur_erss_id'=>$id
                    ]);
            }
        }
        else
        {
            if(!empty($checkelective))
            {
                return $this->render('viewmba', [
                    'model' => $model,
                    'checkelective'=>$checkelective,
                    'checksemdata'=>$checksemdata,
                    'checkdata'=>$checkdata,
                    'reg_num'=> $reg_num,
                    'regulationyear'=>$regulationyear,
                    'cur_erss_id'=>$id
                    ]);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "No Data Found, Please Check");
                return $this->redirect(['mba-index']);
            }
        }
    }

     public function actionDeletembadata($id)
    {
        Yii::$app->db->createCommand('DELETE FROM cur_elective_nominal WHERE cur_erss_id="'.$id.'"')->execute();
        Yii::$app->db->createCommand('DELETE FROM cur_elective_stu_subject WHERE cur_erss_id="'.$id.'"')->execute();
        Yii::$app->ShowFlashMessages->setMsg('Success', "Deleted Successfully");
        return $this->redirect(['mba-index']);
    }

    public function getExcelproperties($fileName)
    {
        
        $objReader = PHPExcel_IOFactory::createReaderForFile($fileName);
        $objReader->setLoadSheetsOnly(array(0));
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($fileName);

        $highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestDataColumn();
        $highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $getData = $objPHPExcel->setActiveSheetIndex(0)->toArray();

        //unset($sheetData[1]); // Removing the headers         
        return $sheetData;         
    }
}
