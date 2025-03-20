<?php

namespace app\controllers;

use Yii;
use app\models\Honours;
use app\models\HonoursSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\HonoursSubjectList;
use app\models\MarkEntry;
use app\models\HallAllocate;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\StuInfo;
use app\models\Regulation;
use yii\db\Query;
use yii\helpers\Json;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use kartik\mpdf\Pdf;
use yii\helpers\Html;
/**
 * HonoursController implements the CRUD actions for Honours model.
 */
class HonoursController extends Controller
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
     * Lists all Honours models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HonoursSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $approvedstatus=0;
        if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
        {
            $approvedstatus = Yii::$app->db->createCommand("SELECT count(cur_hon_id) FROM cur_honours WHERE coe_dept_id=".Yii::$app->user->getDeptId()." AND approve_status=0")->queryScalar();
        }
        else
        {
            $approvedstatus = Yii::$app->db->createCommand("SELECT count(cur_hon_id) FROM cur_honours WHERE approve_status=0")->queryScalar();
        }

        if(isset($_POST['Approve']) && !empty($_POST['finalString']))
        {
            $finalString = $_POST['finalString'];
            $finalString = split('[\^]', $finalString);
            //print_r($finalString); exit;

            $Success=0;
            for($i=0;$i<count($finalString)-1;$i++)
            {
                if(!empty($finalString[$i]))
                {
                    $updated_at = date("Y-m-d H:i:s");
                    $updateBy = Yii::$app->user->getId();

                    $updated=Yii::$app->db->createCommand('UPDATE cur_honours_subject_list SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_hon_id="' . $finalString[$i] . '"')->execute();

                    $updated1= Yii::$app->db->createCommand('UPDATE cur_honours SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_hon_id="' . $finalString[$i] . '"')->execute();

                    if($updated1)
                    {
                        $Success++;
                    }
                }
                
            }

            if($Success>0)
            {
                 Yii::$app->ShowFlashMessages->setMsg('Success', "Approved Successfully");
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', "Approved Not Successful Please Check");
                return $this->redirect(['index']);
            }
        }
        else
        {

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'approvedstatus'=>$approvedstatus
            ]);
        }

    }

    /**
     * Displays a single Honours model.
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
     * Creates a new Honours model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Honours();

        if (Yii::$app->request->post()) 
        {
            $coe_dept_id=$_POST['coe_dept_id'];
            $coe_regulation_id=$_POST['Honours']['coe_regulation_id'];
            $semester=$_POST['Honours']['semester'];
            $honours_type=$_POST['Honours']['honours_type'];
            $subject_code=$_POST['vertical_subject_code'];

            $register_number=$_POST['Honours']['register_number'];
            $batch_map_id=$_POST['batch_map_id'];

            if($honours_type==230 || $honours_type==232)
            {
                $vertical_id=$_POST['vertical_id'];

                if(empty($vertical_id))
                {
                    $vertical_id = Yii::$app->db->createCommand("SELECT vertical_name FROM cur_honours_subject_list WHERE coe_regulation_id='".$coe_regulation_id."' AND coe_dept_id='".$coe_dept_id."' AND register_number='".$register_number."'")->queryScalar();
                }
            }
            else
            {
                $vertical_id=0;
                $vertical_name ='';
            }
            //echo $vertical_id; exit();

            $checkelective = Yii::$app->db->createCommand("SELECT * FROM cur_honours A WHERE A.coe_dept_id='".$coe_dept_id."' AND A.coe_regulation_id=".$coe_regulation_id." AND register_number='".$register_number."' AND A.honours_type='".$honours_type."' AND A.semester=".$semester)->queryOne();

            $coe_batch_id = Yii::$app->db->createCommand("SELECT coe_batch_id FROM coe_regulation WHERE coe_regulation_id=".$coe_regulation_id)->queryScalar(); 

            if(empty($checkelective) && $batch_map_id!='')
            {
                $created_at = date("Y-m-d H:i:s");
                $userid = Yii::$app->user->getId(); 

                if($honours_type==232 || $honours_type==230)
                {
                    $vertical_name =$vertical_id;
                    $vertical_id = Yii::$app->db->createCommand("SELECT cur_vs_id FROM cur_vertical_stream WHERE vertical_name='".$vertical_name."'")->queryScalar();
                }

                $model = new Honours();      
                $model->batch_map_id=$batch_map_id; 
                $model->coe_dept_id=$coe_dept_id;
                $model->degree_type='UG';
                $model->coe_batch_id=$coe_batch_id;  
                $model->coe_regulation_id=$coe_regulation_id;
                $model->semester=$semester;
                $model->honours_type=$honours_type;
                $model->register_number=$register_number;
                $model->vertical_id=$vertical_id;
                $model->subject_code=implode(",", $subject_code);
                $model->created_at=$created_at;
                $model->created_by=$userid;

                if($model->save(false))
                {
                    $honid=$model->cur_hon_id;
                    for ($i=0; $i <count($subject_code) ; $i++) 
                    { 
                        $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject WHERE subject_code='".$subject_code[$i]."'")->queryScalar();

                        if($subject_name=='')
                        {
                            $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject WHERE subject_code='".$subject_code[$i]."'")->queryScalar();
                            
                            if($subject_name=='')
                            {
                                $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code_new='".$subject_code[$i]."'")->queryScalar();

                                if($subject_name=='')
                                {
                                    $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code_new='".$subject_code[$i]."'")->queryScalar();
                                }
                            }
                        }


                        $model1 = new HonoursSubjectList();  
                        $model1->cur_hon_id=$honid;     
                        $model1->batch_map_id=$batch_map_id; 
                        $model1->coe_dept_id=$coe_dept_id;
                        $model1->degree_type='UG';
                        $model1->semester=$semester;
                        $model1->coe_batch_id=$coe_batch_id;  
                        $model1->coe_regulation_id=$coe_regulation_id;
                        $model1->register_number=$register_number;
                        $model1->semester=$semester;
                        $model1->honours_type=$honours_type;
                        $model1->vertical_id=$vertical_id;
                        $model1->subject_code=$subject_code[$i];
                        $model1->subject_name=$subject_name;
                        $model1->vertical_name=$vertical_name;
                        $model1->created_at=$created_at;
                        $model1->created_by=$userid;
                        $model1->save(false);
                    }
                    

                    Yii::$app->ShowFlashMessages->setMsg('Success', "Honour Registration Inserted Successfully..");
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
                 
                Yii::$app->ShowFlashMessages->setMsg('Error', "Already Inserted or Insert Error! Please Check");
                return $this->redirect(['create']);
            }
           
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Honours model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cur_hon_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Honours model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletedata($id)
    {
        $hmdata = Yii::$app->db->createCommand("SELECT A.register_number, B.coe_student_id, C.coe_student_mapping_id, A.semester, A.subject_code,A.batch_map_id FROM cur_honours A JOIN coe_student B ON B.register_number=A.register_number JOIN coe_student_mapping C ON C.student_rel_id=B.coe_student_id  WHERE cur_hon_id='".$id."'")->queryOne();

        $explode=explode(",", $hmdata['subject_code']);

        //print_r($explode); exit;

        if(!empty($hmdata))
        {
            $hmsubcode='';
            if(count($explode)==2)
            {
                $hmsubcode='"'.$explode[0].'",';
                $hmsubcode.='"'.$explode[1].'"';
            }
            else
            {
                $hmsubcode='"'.$explode[0].'"';
            }

            $hmsubdata = Yii::$app->db->createCommand('SELECT coe_subjects_mapping_id, coe_subjects_id FROM coe_subjects_mapping A JOIN coe_subjects B ON B.coe_subjects_id=A.subject_id WHERE A.batch_mapping_id="'.$hmdata['batch_map_id'].'" AND A.semester="'.$hmdata['semester'].'" AND B.subject_code IN ('.$hmsubcode.')')->queryOne();
            //print_r($hmsubdata); exit;

           
            // if(empty($hmsubdata))
            // {
            //     Yii::$app->ShowFlashMessages->setMsg('Error', "Subjects Not Found! Please Check..");
            //     return $this->redirect(['index']);
            // }
            // else
            // {
            // }

                $mastermarkcheck =0;
                if(!empty($hmsubdata))
                {
                    $mastermarkcheck = Yii::$app->db->createCommand("SELECT count(student_map_id) FROM coe_mark_entry_master WHERE student_map_id='".$hmdata['coe_student_mapping_id']."' AND subject_map_id=".$hmsubdata['coe_subjects_mapping_id'])->queryScalar();
                }
                

                if($mastermarkcheck>0 && !empty($hmsubdata))
                {
                     Yii::$app->ShowFlashMessages->setMsg('Error', "Delete Not Possible, Marks have been Entered");
                    return $this->redirect(['index']);
                }
                else
                {
                    $checknominal='';
                    if(!empty($hmsubdata))
                    {
                        $checknominal = Yii::$app->db->createCommand('SELECT * FROM coe_nominal WHERE course_batch_mapping_id="'.$hmdata['batch_map_id'].'" AND semester="'.$hmdata['semester'].'" AND coe_student_id="'.$hmdata['coe_student_id'].'" AND coe_subjects_id='.$hmsubdata['coe_subjects_id'])->queryOne();
                    }
                    //print_r($checknominal); exit;
                    
                    if(empty($checknominal))
                    {
                        Yii::$app->db->createCommand('DELETE FROM cur_honours_subject_list WHERE cur_hon_id="'.$id.'"')->execute();
                        Yii::$app->db->createCommand('DELETE FROM cur_honours WHERE cur_hon_id="'.$id.'"')->execute();
                        Yii::$app->ShowFlashMessages->setMsg('Success', "Deleted Successfully");
                        return $this->redirect(['index']);
                    }
                    else
                    {
                        Yii::$app->db->createCommand('DELETE FROM coe_nominal WHERE course_batch_mapping_id="'.$hmdata['batch_map_id'].'" AND semester="'.$hmdata['semester'].'" AND coe_student_id="'.$hmdata['coe_student_id'].'" AND coe_subjects_id='.$hmsubdata['coe_subjects_id'])->execute();
                        Yii::$app->db->createCommand('DELETE FROM cur_honours_subject_list WHERE cur_hon_id="'.$id.'"')->execute();
                        Yii::$app->db->createCommand('DELETE FROM cur_honours WHERE cur_hon_id="'.$id.'"')->execute();
                        Yii::$app->ShowFlashMessages->setMsg('Success', "Deleted Honours and Nominal Data Successfully");
                        return $this->redirect(['index']);
                    }
                }
        }
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', "Subjects Not Found! Please Check..");
                return $this->redirect(['index']);
        }
        
    }

     public function actionApprove($id)
    {
        $updated_at = date("Y-m-d H:i:s");
        $updateBy = Yii::$app->user->getId();

        $updated=Yii::$app->db->createCommand('UPDATE cur_honours_subject_list SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_hon_id="' . $id . '"')->execute();

        $updated1= Yii::$app->db->createCommand('UPDATE cur_honours SET updated_by="'.$updateBy.'",updated_at="'.$updated_at.'", approve_status=1 WHERE cur_hon_id="' . $id . '"')->execute();

        Yii::$app->ShowFlashMessages->setMsg('Success', "Approved Successfully");
        return $this->redirect(['index']);
    }


    /**
     * Finds the Honours model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Honours the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Honours::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionHonoursReport()
    {
        $model1 = new Honours();
        $model = new MarkEntry();
        $galley = new HallAllocate();
        return $this->render('honours_report', [
                'model' => $model,
                'model1'=>$model1,
                'galley' => $galley,
            ]);
    }

    public function actionGethonoursminorsdata()
    {
        $year = Yii::$app->request->post('year');
        $batch = Yii::$app->request->post('batch');
        $batch_mapping_id = Yii::$app->request->post('batch_mapping_id');
        
        $batch_name = Yii::$app->db->createCommand("select batch_name from coe_batch where coe_batch_id='" . $batch . "'")->queryScalar();
        $month = Yii::$app->request->post('month');
        $month_name = Yii::$app->db->createCommand("select description from coe_category_type where coe_category_type_id='" . $month . "'")->queryScalar();

        if(empty($batch_mapping_id))
        {
            $batch_mapping_id=Yii::$app->db->createCommand("SELECT coe_bat_deg_reg_id FROM coe_bat_deg_reg WHERE coe_batch_id='".$batch."'")->queryScalar();
        }

        $sem_count = ConfigUtilities::SemCaluclation($year,$month,$batch_mapping_id);
        $detp_cat_tye = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
      
        //print_r($rejoin);exit;
         $stuinfo = StuInfo::findOne(['batch_map_id'=>$batch_mapping_id]);

         $reg = Regulation::find()->where(['coe_batch_id'=>$batch])->one();

         $coe_programme_id=Yii::$app->db->createCommand("SELECT coe_programme_id FROM coe_bat_deg_reg WHERE coe_bat_deg_reg_id='".$batch_mapping_id."' AND coe_batch_id='".$batch."'")->queryScalar();

        $programme = Yii::$app->db->createCommand("select programme_shortname,B.coe_dept_id, A.coe_programme_id, B.degree_type  from coe_programme  as A  join cur_department as B ON A.programme_shortname=B.dept_code   where coe_programme_id='" . $coe_programme_id . "'")->queryAll();

        $semcolumn='sem'.$sem_count;
        $semcredit=Yii::$app->db->createCommand("SELECT sum(".$semcolumn.") FROM cur_credit_distribution_sem WHERE coe_regulation_id='".$reg['coe_regulation_id']."' AND coe_dept_id='".$programme[0]['coe_dept_id']."'")->queryScalar();

        // print_r($stuinfo);exit;
        $table = "";
        $sn = 1;
        $addquery='';
        $query = new Query();
        $query->select('F.batch_name,C.degree_code,B.programme_name,E.semester,D.coe_subjects_id,D.subject_code,D.subject_name,D.CIA_max,D.ESE_max,D.total_minimum_pass,D.credit_points,D.CIA_min,D.ESE_min,G.description as paper_type,H.description as subject_type,I.description as course_type,paper_no,part_no,j.description as theoryprac_type,subject_type_id')
            ->from('coe_bat_deg_reg A')
            ->join('JOIN', 'coe_programme B', 'A.coe_programme_id=B.coe_programme_id')
            ->join('JOIN', 'coe_degree C', 'A.coe_degree_id=C.coe_degree_id')
            ->join('JOIN', 'coe_subjects_mapping E', 'A.coe_bat_deg_reg_id=E.batch_mapping_id')
            ->join('JOIN', 'coe_subjects D', 'E.subject_id=D.coe_subjects_id')
            ->join('JOIN', 'coe_category_type G', 'G.coe_category_type_id=E.paper_type_id')
            ->join('JOIN', 'coe_category_type H', 'H.coe_category_type_id=E.subject_type_id')
            ->join('JOIN', 'coe_category_type I', 'I.coe_category_type_id=E.course_type_id')
             ->join('JOIN', 'coe_category_type j', 'j.coe_category_type_id=E.type_id')
            ->join('JOIN', 'coe_batch F', 'A.coe_batch_id=F.coe_batch_id')
            ->where(['F.coe_batch_id' => $batch, 'A.coe_batch_id' => $batch, 'E.semester' => $sem_count]);
        
        if(!empty(Yii::$app->request->post('batch_mapping_id')))
        {
            $query->where(['E.batch_mapping_id' => $batch_mapping_id]);
            $addquery="B.course_batch_mapping_id='".$batch_mapping_id."' AND ";
        }
            
        $query->andWhere(['=', 'E.subject_type_id', 233])
            ->groupBy('subject_code')->orderBy('paper_no');
        $subject = $query->createCommand()->queryAll();
       
        $deptcedits=0;

        $prg_name =$header1='';
        if (count($subject) > 0) 
        {
            $prg_name =$subject[0]['programme_name'];

                $count_sub=0; $studzero=0;
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                $table = '<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';
                $table .= '
                        <tr>
                            <td colspan=3> 
                                <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                            </td>
                            <td colspan=10 align="center"> 
                                <center><b><font size="6px">' . $org_name . '</font></b></center>
                                <center>' . $org_address . '</center>
                                
                                <center>' . $org_tagline . '</center>

                                <center><b>HONOURS/MINOURS REPORT '.strtoupper($month_name).'-'.$year.' </b></center> 
                            </td>
                            <td  colspan=3 align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                            
                        </tr>
                </table>';

            
                $student_count=0;                
               

                $select_hnrs1 = "SELECT distinct (H.subject_code) as subject_code,concat(D.degree_code,'-',E.programme_shortname) as degree_code,B.course_batch_mapping_id,UPPER(H.subject_name) as subject_name,F.coe_student_id,F.coe_subjects_id,F.semester,E.programme_name,D.degree_name,bat.batch_name, G.subject_type_id, J.category_type, L.category_type as theoryprac_type, K.category_type as paper_type, G.paper_no, H.CIA_min, H.CIA_max, H.ESE_min, H.total_minimum_pass, H.credit_points,I.honours_type, (I.vertical_name) as vertical_name, G.course_type_id, A.register_number,A.name, B.coe_student_mapping_id FROM  coe_student as A 
                 JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id  
                 JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id 
                 JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id 
                 JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id 
                 JOIN coe_batch as bat ON bat.coe_batch_id=C.coe_batch_id 
                 JOIN coe_nominal as F ON F.coe_student_id=A.coe_student_id 
                 JOIN coe_subjects H ON H.coe_subjects_id=F.coe_subjects_id 
                 JOIN coe_subjects_mapping as G ON G.subject_id=H.coe_subjects_id               
                 JOIN cur_honours_subject_list as I ON I.register_number=A.register_number and I.subject_code=H.subject_code
                 JOIN coe_category_type as J ON J.coe_category_type_id=I.honours_type
                 JOIN coe_category_type as K ON K.coe_category_type_id=G.course_type_id
                 JOIN coe_category_type as L ON L.coe_category_type_id=G.type_id
                 WHERE ".$addquery." C.coe_batch_id='".$batch."' and F.semester='".$sem_count."' and  A.student_status='Active' AND  status_category_type_id NOT IN ('".$detp_cat_tye."','".$det_disc_type."') AND I.honours_type=231 Group BY A.register_number,H.subject_code order by C.coe_bat_deg_reg_id, A.register_number ASC";
                
                $honours1 = Yii::$app->db->createCommand($select_hnrs1)->queryAll();

                if(!empty($honours1))
                {
                    //$header1.='<pagebreak>';
                    $header1 .="<table border=1 align='center' class='table table-striped '>";
                   
                    $header1 .="<tr>
                      <th align='center' colspan=7>HONOURS</th>

                      </tr>
                    <tr>
                      <th align='center'>S.No</th>
                      <th align='center'>Register Number</th>
                      <th align='center'>Studnet Name</th>
                      <th align='center'>Department</th>
                      <th align='center'>Course Code</th>
                      <th align='center'>Course Name</th>
                      <th align='center'>Additional Course Count</th>
                    </tr>";
                
                    $sn=1;
                    foreach($honours1 as $rows) 
                    { 
                          $subqry = "SELECT L.category_type as theoryprac_type, K.category_type as paper_type, G.paper_no, H.CIA_min, H.CIA_max, H.ESE_min, H.ESE_max, H.total_minimum_pass, H.credit_points FROM  coe_subjects as H
                             JOIN coe_subjects_mapping as G ON G.subject_id=H.coe_subjects_id   
                             JOIN coe_category_type as K ON K.coe_category_type_id=G.paper_type_id
                             JOIN coe_category_type as L ON L.coe_category_type_id=G.type_id
                             WHERE G.batch_mapping_id='".$rows['course_batch_mapping_id']."' and G.semester='".$sem_count."' and  H.subject_code='".$rows["subject_code"]."'";
                            
                            $subjects = Yii::$app->db->createCommand($subqry)->queryOne();

                            if($rows["subject_type_id"]!=233)
                            {
                                $subject_name='';
                                $explode=explode(":", $rows["subject_name"]);
                                if(count($explode)==2)
                                {
                                    $subject_name=$explode[1];
                                }
                                

                                if(empty($subject_name))
                                {
                                    $subject_name=$rows["subject_name"];
                                }
                            }
                            else
                            {
                                $subject_name=$rows["subject_name"];
                            }   

                        $check_add_count = Yii::$app->db->createCommand('SELECT count(student_map_id) FROM coe_mandatory_stu_marks WHERE year="'.$year.'" AND month="'.$month.'" and student_map_id="'.$rows["coe_student_mapping_id"].'" and year_of_passing!=""')->queryScalar();

                        $header1 .='<tr>
                            <td align="center">'.$sn.'</td>
                             <td> ' . $rows['register_number'] . ' </td>
                            <td> ' . $rows['name'] . ' </td>
                            <td> ' . $rows['degree_code'] . ' </td>
                             <td align="center">'.$rows["subject_code"].'</td>
                             <td align="left">'.$subject_name.'</td>
                            <td align="left">'.$check_add_count.'</td>
                        </tr>';
                        $sn++;
                        
                    }

                    $header1 .='</table>';
                }

                $select_hnrs2 = "SELECT distinct (H.subject_code) as subject_code,concat(D.degree_code,'-',E.programme_shortname) as degree_code,B.course_batch_mapping_id,UPPER(H.subject_name) as subject_name,F.coe_student_id,F.coe_subjects_id,F.semester,E.programme_name,D.degree_name,bat.batch_name, G.subject_type_id, J.category_type, L.category_type as theoryprac_type, K.category_type as paper_type, G.paper_no, H.CIA_min, H.CIA_max, H.ESE_min, H.total_minimum_pass, H.credit_points,I.honours_type, (I.vertical_name) as vertical_name, G.course_type_id, A.register_number,A.name, B.coe_student_mapping_id FROM  coe_student as A 
                 JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id  
                 JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id 
                 JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id 
                 JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id 
                 JOIN coe_batch as bat ON bat.coe_batch_id=C.coe_batch_id 
                 JOIN coe_nominal as F ON F.coe_student_id=A.coe_student_id 
                 JOIN coe_subjects H ON H.coe_subjects_id=F.coe_subjects_id 
                 JOIN coe_subjects_mapping as G ON G.subject_id=H.coe_subjects_id               
                 JOIN cur_honours_subject_list as I ON I.register_number=A.register_number and I.subject_code=H.subject_code
                 JOIN coe_category_type as J ON J.coe_category_type_id=I.honours_type
                 JOIN coe_category_type as K ON K.coe_category_type_id=G.course_type_id
                 JOIN coe_category_type as L ON L.coe_category_type_id=G.type_id
                 WHERE ".$addquery." C.coe_batch_id='".$batch."' and F.semester='".$sem_count."' and  A.student_status='Active' AND  status_category_type_id NOT IN ('".$detp_cat_tye."','".$det_disc_type."') AND I.honours_type=232 Group BY A.register_number,H.subject_code order by C.coe_bat_deg_reg_id, A.register_number ASC";
                
                $honours2 = Yii::$app->db->createCommand($select_hnrs2)->queryAll();

                if(!empty($honours2))
                {
                    //$header1.='<pagebreak>';
                    $header1 .="<table border=1 align='center' class='table table-striped '>";
                   
                    $header1 .="<tr>
                      <th align='center' colspan=7>MINOURS</th>

                      </tr>
                    <tr>
                      <th align='center'>S.No</th>
                      <th align='center'>Register Number</th>
                      <th align='center'>Studnet Name</th>
                      <th align='center'>Department</th>
                      <th align='center'>Course Code</th>
                      <th align='center'>Course Name</th>
                      <th align='center'>Additional Course Count</th>
                    </tr>";
                
                    $sn=1;
                    foreach($honours2 as $rows) 
                    { 
                          $subqry = "SELECT L.category_type as theoryprac_type, K.category_type as paper_type, G.paper_no, H.CIA_min, H.CIA_max, H.ESE_min, H.ESE_max, H.total_minimum_pass, H.credit_points FROM  coe_subjects as H
                             JOIN coe_subjects_mapping as G ON G.subject_id=H.coe_subjects_id   
                             JOIN coe_category_type as K ON K.coe_category_type_id=G.paper_type_id
                             JOIN coe_category_type as L ON L.coe_category_type_id=G.type_id
                             WHERE G.batch_mapping_id='".$rows['course_batch_mapping_id']."' and G.semester='".$sem_count."' and  H.subject_code='".$rows["subject_code"]."'";
                            
                            $subjects = Yii::$app->db->createCommand($subqry)->queryOne();

                            if($rows["subject_type_id"]!=233)
                            {
                                $explode=explode(":", $rows["subject_name"]);
                                $subject_name=$explode[1];

                                if(empty($subject_name))
                                {
                                    $subject_name=$rows["subject_name"];
                                }
                            }
                            else
                            {
                                $subject_name=$rows["subject_name"];
                            }   

                         $check_add_count = Yii::$app->db->createCommand('SELECT count(student_map_id) FROM coe_mandatory_stu_marks WHERE year="'.$year.'" AND month="'.$month.'" and student_map_id="'.$rows["coe_student_mapping_id"].'" and year_of_passing!=""')->queryScalar();

                         $header1 .='<tr>
                            <td align="center">'.$sn.'</td>
                             <td> ' . $rows['register_number'] . ' </td>
                            <td> ' . $rows['name'] . ' </td>
                            <td> ' . $rows['degree_code'] . ' </td>
                             <td align="center">'.$rows["subject_code"].'</td>
                             <td align="left">'.$subject_name.'</td>
                             <td align="left">'.$check_add_count.'</td>
                            
                        </tr>';
                        $sn++;
                        
                    }

                    $header1 .='</table>';
                }

                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/honours/honoursminors-pdf'], [
                            'class'=>'pull-right btn btn-primary', 
                            'target'=>'_blank', 
                            'data-toggle'=>'tooltip', 
                            'title'=>'Will open the generated PDF file in a new window'
                            ]);
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-honoursminors','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
           
        }
        else 
        {
            $table .= 0;
        }
        if (isset($_SESSION['honoursminors'])) {
            unset($_SESSION['honoursminors']);
        }
        $_SESSION['honoursminors'] = $table.$header1;

        if (isset($_SESSION['honoursminorsxl'])) {
            unset($_SESSION['honoursminorsxl']);
        }
        $_SESSION['honoursminorsxl'] = $header1;
        
        return $table.$header1;

                        
    }

     public function actionHonoursminorsPdf()
    {
        require(Yii::getAlias('@webroot/includes/use_institute_info.php')); 
          $content = $_SESSION['honoursminors'];
        $pdf = new Pdf([
               
                'mode' => Pdf::MODE_CORE, 
                'filename' => 'honoursminors.pdf',                
                'format' => Pdf::FORMAT_A4,                 
                'orientation' => Pdf::ORIENT_LANDSCAPE,                 
                'destination' => Pdf::DEST_BROWSER,                 
                'content' => $content, 
                'cssInline' => ' @media all{
                        table{font-family:"Roboto, sans-serif"; width:100%; font-size: 12.5px; }
                    }   
                ', 
                'options' => ['title' => 'Honours Minours Report'],
                'methods' => [ 
                    'SetHeader'=>["OFFICE OF THE CONTROLLER OF EXAMINATIONS ".$org_name], 
                    'SetFooter'=>['Honours Minours Report PRINTED ON : {DATE d-m-Y H:i:s:A}  PAGE :{PAGENO}'],
                ],
                
            ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        return $pdf->render(); 
    }

    public function actionExcelHonoursminors()
    {
        
        $content = $_SESSION['honoursminorsxl'];
            
        $fileName = "Honours Minours Report" . date('Y-m-d-H-i-s') . '.xls';
        $options = ['mimeType' => 'application/vnd.ms-excel'];
        return Yii::$app->excel->exportExcel($content, $fileName, $options);
    }
}
