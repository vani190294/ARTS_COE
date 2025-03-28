<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\db\Query;
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Configuration;
use kartik\widgets\Growl;
// Below models related to the Cateogry
use app\models\Categories;
use app\models\Categorytype;
use app\models\FeesPaid;
// Below Models Related to Batch
use app\models\Regulation;
use app\models\Degree;
use app\models\Programme;
use app\models\Batch;
use app\models\CoeBatDegReg;
use app\models\Student;
use app\models\AdditionalCredits;
use app\models\StudentMapping;
/* Absent */
use app\models\AbsentEntry;
use app\models\ExamTimetable;
use app\models\HallAllocate;
/* Absent End */
/* Migration Requirement */
use app\models\Subjects;
use app\models\SubjectsMapping;
use app\models\PracticalEntry;
use app\models\MandatorySubjects;
use app\models\MandatoryStuMarks;
use app\models\MandatorySubcatSubjects;
/* Galley */
use app\models\HallMaster;
/* MARK */
use app\models\Nominal;
use app\models\MarkEntry;
use app\models\MarkEntryMaster;
use app\models\DummyNumbers;
use app\models\StoreDummyMapping;
use app\models\ElectiveWaiver;
use app\models\SubInfo;
use app\models\StuInfo;
use app\models\ConsolidateMarks;
use app\models\EqualentSubjects;
use app\models\DelQualentSubjects;

use app\models\Sub;
use app\models\CoeValueSubjects;
use app\models\CoeValueMarkEntry;
/**
 * BatchController implements the CRUD actions for Batch model.
 */
class AjaxrequestController extends Controller {

    public function actionGetconfigvalue() {
        $config_desc = Yii::$app->request->post('config_desc');
        $config_val = Configuration::find()->where(['config_desc' => $config_desc])->orderBy(['config_desc' => SORT_ASC])->all();
        return Json::encode($config_val);
    }

    public function actionGetpapernumber() 
    {
        $batch_map_id = Yii::$app->request->post('batch_mapping_id');
        $coun = SubjectsMapping::find()->where(['batch_mapping_id'=>$batch_map_id])->all();
        return Json::encode(count($coun));
    }

    public function actionGetmanpapernumber() 
    {
        $batch_map_id = Yii::$app->request->post('batch_mapping_id');
        $coun = MandatorySubjects::find()->where(['batch_mapping_id'=>$batch_map_id])->all();
        return Json::encode(count($coun));
    }

    // Student Functions Start Here     
    public function actionSendemail() {
        // ini_set("SMTP","us2.smtp.mailhostbox.com");
        // ini_set("IMAP","us2.imap.mailhostbox.com");
        // ini_set("imap_port","143");
        // ini_set("smtp_port","25");
        $email_to = Yii::$app->request->post('email_to');
        $subj_info = Yii::$app->request->post('subj_info');
        $text_info = Yii::$app->request->post('text_info');
        $headers = 'From: sainagendra@srikrishnaitech.com' . "\r\n" .
                'Reply-To: sainagendra@srikrishnaitech.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
        $email_to = 'sainagendra@srikrishnaitech.com';
        $res = Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($email_to)
                ->setSubject($subj_info)
                ->setTextBody($text_info)
                ->setHtmlBody($text_info)
                ->send();
                
        if ($res)
            $data = "E-Mail Sent Successfully!!";
        else
            $data = "Unable to Send E-Mail";
        // if(mail($email_to, $subj_info, $text_info, $headers))
        //   $data = "Successfully";
        // else
        //   $data = "Error";
        return Json::encode($data);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function actionGetdegreedetails() {
        $global_batch_id = Yii::$app->request->post('global_batch_id');
        $query = "SELECT a.coe_bat_deg_reg_id,concat(b.degree_code, ' ' , c.programme_code) as degree_name FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_programme c ON c.coe_programme_id = a.coe_programme_id and a.coe_batch_id='" . $global_batch_id . "' order by a.coe_bat_deg_reg_id";
        $degreeInfo = Yii::$app->db->createCommand($query)->queryAll();
        $stu_dropdown = "";
        $stu_dropdown = "<option value='' > --- Select " . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . " ---</option>";
        foreach ($degreeInfo as $key => $value) {
            $stu_dropdown .= "<option value='" . $value['coe_bat_deg_reg_id'] . "' > " . $value['degree_name'] . "</option>";
        }
        return Json::encode($stu_dropdown);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function actionGetintsubjects() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
        
        if($get_regular['coe_category_type_id']==$mark_type)
        {

            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_subjects_mapping as E')                
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val,'F.CIA_max' => 0,'F.CIA_min' => 0,'F.ESE_max' => 0,'F.ESE_min' => 0])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }

        else
        {
            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

            $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_subjects_mapping as E')                
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->join('JOIN', 'coe_student_mapping as C', 'C.course_batch_mapping_id=E.batch_mapping_id')
                    ->join('JOIN', 'coe_mark_entry_master as D', 'D.student_map_id=C.coe_student_mapping_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val,'C.course_batch_mapping_id'=>$bat_map_val,'F.CIA_max' => 0,'F.CIA_min' => 0,'F.ESE_max' => 0,'F.ESE_min' => 0])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->andWhere(['NOT LIKE','D.result','pass'])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }
        
        $getSubsInfoDet = !empty($getSubsInfoDet) && count($getSubsInfoDet) >0 ? $getSubsInfoDet : 0;

        return Json::encode($getSubsInfoDet);
    }

    public function actionGetexternalarsubjects() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
        
        if($get_regular['coe_category_type_id']==$mark_type)
        {
            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_subjects_mapping as E')                
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }
        else
        {
            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

            $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_subjects_mapping as E')                
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->join('JOIN', 'coe_student_mapping as C', 'C.course_batch_mapping_id=E.batch_mapping_id')
                    ->join('JOIN', 'coe_mark_entry_master as D', 'D.student_map_id=C.coe_student_mapping_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val,'C.course_batch_mapping_id'=>$bat_map_val])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->andWhere(['<>','D.result','pass'])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }        
        $getSubsInfoDet = !empty($getSubsInfoDet) && count($getSubsInfoDet) >0 ? $getSubsInfoDet : 0;

        return Json::encode($getSubsInfoDet);
    }


    public function actionGetexternalvalsub() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
        
        if($get_regular['coe_category_type_id']==$mark_type)
        {
            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_sub_mapping_id'])
                    ->from('sub as E')                
                    ->join('JOIN', 'coe_value_subjects as F', 'F.coe_val_sub_id=E.val_subject_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }
        else
        {
            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

            $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_sub_mapping_id'])
                    ->from('sub as E')                
                    ->join('JOIN', 'coe_value_subjects as F', 'F.coe_val_sub_id=E.val_subject_id')
                    ->join('JOIN', 'coe_student_mapping as C', 'C.course_batch_mapping_id=E.batch_mapping_id')
                    ->join('JOIN', 'coe_value_mark_entry as D', 'D.student_map_id=C.coe_student_mapping_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val,'C.course_batch_mapping_id'=>$bat_map_val])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->andWhere(['<>','D.result','pass'])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();

        }        
        $getSubsInfoDet = !empty($getSubsInfoDet) && count($getSubsInfoDet) >0 ? $getSubsInfoDet : 0;

        return Json::encode($getSubsInfoDet);
    }



    public function actionGetpracticalsubjects() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
        $practical = Categorytype::find()->where(['description'=>'Practical'])->one();

        if($get_regular['coe_category_type_id']==$mark_type)
        {
            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_subjects_mapping as E')                
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->join('JOIN', 'coe_category_type as G', 'G.coe_category_type_id=E.paper_type_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val])->andWhere(['NOT LIKE','G.description','Theory'])->andWhere(['NOT LIKE','G.category_type','Theory'])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }
        else
        {
            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

            $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_subjects_mapping as E')                
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->join('JOIN', 'coe_student_mapping as C', 'C.course_batch_mapping_id=E.batch_mapping_id')
                    ->join('JOIN', 'coe_mark_entry_master as D', 'D.student_map_id=C.coe_student_mapping_id')
                    ->join('JOIN', 'coe_category_type as A', 'A.coe_category_type_id=E.paper_type_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val,'C.course_batch_mapping_id'=>$bat_map_val])
                    ->andWhere(['NOT LIKE', 'A.description', 'Theory'])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->andWhere(['NOT LIKE','D.result','fail'])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }
        
        $getSubsInfoDet = !empty($getSubsInfoDet) && count($getSubsInfoDet) >0 ? $getSubsInfoDet : 0;

        return Json::encode($getSubsInfoDet);
    }

    public function actionGetintsubjectdetails() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');

        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $sub_type = Yii::$app->request->post('sub_type');
        $sub_map_id = Yii::$app->request->post('sub_map_id');
         $paper_type = Yii::$app->db->createCommand("select subject_type_id from  coe_subjects_mapping where coe_subjects_mapping_id= $sub_map_id")->queryScalar();
        $get_elective = Categorytype::find()->where(['description'=>'Elective'])->one();
        $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
        $updated = 'No';
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $det_elective = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Elective%'")->queryScalar();

        /*if($det_elective==15)
        {

            $getSubsInfo = new Query();
        $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                ->from('coe_student as A')
                ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')                
                ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_nominal as N', 'N.coe_student_id=B.student_rel_id and E.subject_id=N.coe_subjects_id')
                ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id');
                ;
        if($get_regular['coe_category_type_id']==$mark_type)
        {

        }
        else
        {
            $getSubsInfo->join('JOIN', 'coe_mark_entry_master as C', 'C.student_map_id=B.coe_student_mapping_id and C.subject_map_id=E.coe_subjects_mapping_id');
            if($sub_type==0)
            {
                $getSubsInfo->andWhere(['NOT LIKE', 'result','Pass']);
            }
            if($sub_type==1)
            {
                $getSubsInfo->andWhere(['NOT LIKE', 'result','Completed']);
            }
        }
        $getSubsInfo->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val]);

        if($get_regular['coe_category_type_id']!=$mark_type)
        {
            $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                        ->andWhere(['NOT LIKE', 'result','Pass']);
        }
        

        $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->orderBy('register_number');
        $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();


        }


        else
        {
        $getSubsInfo = new Query();
        $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                ->from('coe_student as A')
                ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')                
                ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                //->join('JOIN', 'coe_nominal as N', 'N.coe_student_id=B.student_rel_id and E.subject_id=N.coe_subjects_id')
                ->join('JOIN', 'coe_subjects as F', 'F.coe_subject_id=E.subject_id');
                ;
        if($get_regular['coe_category_type_id']==$mark_type)
        {

        }
        else
        {
            $getSubsInfo->join('JOIN', 'coe_mark_entry_master as C', 'C.student_map_id=B.coe_student_mapping_id and C.subject_map_id=E.coe_subjects_mapping_id');
            if($sub_type==0)
            {
                $getSubsInfo->andWhere(['NOT LIKE', 'result','Pass']);
            }
            if($sub_type==1)
            {
                $getSubsInfo->andWhere(['NOT LIKE', 'result','Completed']);
            }
        }
        $getSubsInfo->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val]);

        if($get_regular['coe_category_type_id']!=$mark_type)
        {
            $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                        ->andWhere(['NOT LIKE', 'result','Pass']);
        }
        

        $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->orderBy('register_number');
        $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
        //print_r($getSubsInfoDetails );exit;
    }*/

    $getSubsInfo = new Query();
        $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                ->from('coe_student as A')
                ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')                
                ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id');
               
                ;
        if($get_regular['coe_category_type_id']==$mark_type)
        {

        }
        else
        {
            $getSubsInfo->join('JOIN', 'coe_mark_entry_master as C', 'C.student_map_id=B.coe_student_mapping_id and C.subject_map_id=E.coe_subjects_mapping_id');
        }

        if($get_elective['coe_category_type_id']==$paper_type)
        {
               $getSubsInfo->join('JOIN', 'coe_nominal as x', 'x.coe_student_id=A.coe_student_id and x.coe_subjects_id=F.coe_subjects_id');
        }
        $getSubsInfo->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val]);
        if($get_regular['coe_category_type_id']!=$mark_type)
        {
            $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_cat_type])

                        ->andWhere(['NOT LIKE', 'result','Pass']);
        }

        $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                        ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                 ->groupBy('register_number')   
                ->orderBy('register_number');
        $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
      
        
        $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
        
        $check_entry = MarkEntryMaster::find()->where(['subject_map_id'=>$sub_map_id,'year'=>$exam_year,'month'=>$month,'term'=>$term,'mark_type'=>$mark_type])->all();

        $getSubsInfoDetailsse = !empty($check_entry) && count($check_entry)>0 ? 1 : $getSubsInfoDetailsse;

        return Json::encode($getSubsInfoDetailsse);
    }
    public function actionGetviewintsubjectdetails() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
        $updated = 'No';
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $getSubsInfo = new Query();
        $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','E.coe_subjects_mapping_id','result'])
                ->from('coe_student as A')
                ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')                
                ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                ->join('JOIN', 'coe_mark_entry_master as G', 'G.subject_map_id=E.coe_subjects_mapping_id and G.student_map_id=B.coe_student_mapping_id')
                ->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val,'G.year'=>$exam_year,'G.month'=>$month,'G.mark_type'=>$mark_type])
                ->orderBy('register_number');
        $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
        
        $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
        return Json::encode($getSubsInfoDetailsse);
    }

    public function actionGetpracticalsubjectdetails() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $section = Yii::$app->request->post('section');
        $reg_from = Yii::$app->request->post('reg_from');
        $reg_to = Yii::$app->request->post('reg_to');
        $mark_type = Yii::$app->request->post('mark_type');
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $updated = 'No';
        $section_name = Yii::$app->request->post('section')!='All'?Yii::$app->request->post('section'):'';
        $checElect = Categorytype::find()->where(['description'=>'Elective'])->one();

        $getSubMapp = SubjectsMapping::findOne($sub_map_id);

        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $reguLar = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Regular%'")->queryScalar();
        
        $getSubsInfo = new Query();
            $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_student as A')
                    ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')
                    ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id');
        $stAbsList = [];
        if($mark_type==$reguLar)
        {
			$get_stu_absents = AbsentEntry::find()->where(['exam_subject_id'=>$sub_map_id,'exam_type'=>$mark_type,'absent_term'=>$term])->all();
            $sem_verify = ConfigUtilities::SemCaluclation($exam_year,$month,$bat_map_val);
            if($sem_verify!=$getSubMapp['semester'])
            {
                return 3;
            }
            foreach ($get_stu_absents as $absents) 
            {
                $stAbsList[$absents['absent_student_reg']] = $absents['absent_student_reg'];
            }
            $stAbsList = array_filter($stAbsList);
            if($getSubMapp['subject_type_id']==$checElect['coe_category_type_id'])
            {
                $getSemester = SubjectsMapping::findOne($getSubMapp['coe_subjects_mapping_id']);
                $getSubsInfo->join('JOIN','coe_nominal as G','G.coe_subjects_id=F.coe_subjects_id and G.coe_student_id=A.coe_student_id and G.course_batch_mapping_id=B.course_batch_mapping_id and G.course_batch_mapping_id=E.batch_mapping_id');
                $getSubsInfo->Where(['G.semester' => $getSemester->semester]);
            }

            $getSubsInfo->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val]);
           
            if($getSubMapp['subject_type_id']==$checElect['coe_category_type_id'])
            {
                $getSubsInfo->andWhere(['G.coe_subjects_id'=>$getSubMapp['subject_id']]);
            }
            if($section_name!='')
            {
                $getSubsInfo->andWhere(['B.section_name'=>$section_name]);
            }

            $check_entry = PracticalEntry::find()->where(['subject_map_id'=>$sub_map_id,'year'=>$exam_year,'month'=>$month,'term'=>$term,'mark_type'=>$mark_type])->all();
            $stuIds=[];
            foreach ($check_entry as $value) 
            {
                $stuIds[$value['student_map_id']]=$value['student_map_id'];
            }

            $stuIds = array_filter($stuIds);
            if(!empty($stAbsList))
            {
                $stuIds = array_merge($stuIds,$stAbsList);
                $stuIds = array_filter($stuIds);
            }
            $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
            if($get_regular['coe_category_type_id']==$mark_type)
            {
                $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_cat_type]);
            }
            $getSubsInfo->andWhere(['NOT IN', 'coe_student_mapping_id', $stuIds])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
            
            if(!empty($reg_to) && !empty($reg_from))
            {
                $getSubsInfo->andWhere(['between', "A.register_number", $reg_from, $reg_to])->groupBy('register_number')->orderBy('register_number');
            }
            else
            {
                $getSubsInfo->groupBy('register_number')->orderBy('register_number')->limit(30);
            }      
            $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
            $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
            
        }
        else
        {
            $get_stu_absents = AbsentEntry::find()->where(['exam_subject_id'=>$sub_map_id,'exam_year'=>$exam_year,'exam_month'=>$month,'exam_type'=>$mark_type,'absent_term'=>$term])->all();

            $stAbsList = [];
            foreach ($get_stu_absents as $absents) 
            {
                $stAbsList[$absents['absent_student_reg']] = $absents['absent_student_reg'];
            }
            $stAbsList = array_filter($stAbsList);
            $getSubsInfo->join('JOIN', 'coe_mark_entry_master as G', 'G.subject_map_id=E.coe_subjects_mapping_id and G.student_map_id=B.coe_student_mapping_id')
            ->join('JOIN','coe_fees_paid fees','fees.student_map_id = G.student_map_id and fees.subject_map_id = G.subject_map_id');

            if($getSubMapp['subject_type_id']==$checElect['coe_category_type_id'])
            {
                $getSemester = SubjectsMapping::findOne($getSubMapp['coe_subjects_mapping_id']);
                $getSubsInfo->join('JOIN','coe_nominal as G','G.coe_subjects_id=F.coe_subjects_id and G.coe_student_id=A.coe_student_id and G.course_batch_mapping_id=B.course_batch_mapping_id and G.course_batch_mapping_id=E.batch_mapping_id');
                $getSubsInfo->Where(['G.semester' => $getSemester->semester]);
            }

            $getSubsInfo->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val,'fees.status'=>'YES']);
            $getSubsInfo->andWhere(['NOT LIKE','result','Pass']);
           
            if($getSubMapp['subject_type_id']==$checElect['coe_category_type_id'])
            {
                $getSubsInfo->andWhere(['G.coe_subjects_id'=>$getSubMapp['subject_id']]);
            }
            if($section_name!='')
            {
                $getSubsInfo->andWhere(['B.section_name'=>$section_name]);
            }

            $check_entry = MarkEntryMaster::find()->where(['subject_map_id'=>$sub_map_id,'result'=>'Pass'])->all();
			$check_entry_same = MarkEntryMaster::find()->where(['subject_map_id'=>$sub_map_id,'year'=>$exam_year,'month'=>$month,'term'=>$term])->all();
            $stuIds=[];
            foreach ($check_entry as $value) 
            {
                $stuIds[$value['student_map_id']]=$value['student_map_id'];
            }
			$stuIds1=[];
            foreach ($check_entry_same as $value) 
            {
                $stuIds1[$value['student_map_id']]=$value['student_map_id'];
            }
			$stuIds1 = array_filter($stuIds1);
            $stuIds = array_filter($stuIds);
            if(!empty($stAbsList))
            {
                $stuIds = array_merge($stuIds,$stAbsList);
                $stuIds = array_filter($stuIds);
            }
			if(!empty($stuIds1))
            {
                $stuIds = array_merge($stuIds,$stuIds1);
                $stuIds = array_filter($stuIds);
            }

            $getSubsInfo->andWhere(['NOT IN', 'coe_student_mapping_id', $stuIds])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->andWhere(['<>', 'fees.status', 'NO']);
            if(!empty($reg_to) && !empty($reg_from))
            {
                $getSubsInfo->andWhere(['between', "A.register_number", $reg_from, $reg_to])->groupBy('register_number')->orderBy('register_number');
            }
            else
            {
                $getSubsInfo->groupBy('register_number')->orderBy('register_number')->limit(30);
            } 
            $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
            $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
        }
        return Json::encode($getSubsInfoDetailsse);
    }
    
    public function actionGetexternalstusubjectdetails() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $updated = 'No';
        $section_name = 'All';
        $checElect = Categorytype::find()->where(['description'=>'Elective'])->one();

        $getSubMapp = SubjectsMapping::findOne($sub_map_id);

        $getBatchDe = CoeBatDegReg::findOne($getSubMapp->batch_mapping_id);
        $getDepreeInfo = Degree::findOne($getBatchDe->coe_degree_id);

        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $cia_cat_id = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Internal%' OR category_type like '%CIA%'")->queryScalar();
        $reguLar = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Regular%'")->queryScalar();

        $ese_cat_id = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%ESE%' OR category_type like '%External%'")->queryScalar();
        $dummy_cat = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%ESE(Dummy)%'")->queryScalar();
        
        $ext_cat_ids=[$ese_cat_id,$dummy_cat];
        $check_INternal_entry = MarkEntry::find()->where(['category_type_id'=>$cia_cat_id,'subject_map_id'=>$sub_map_id])->one();
        if(empty($check_INternal_entry))
        {
            return 2;
        }
        $stAbsList = [];
        $getSubsInfo = new Query();
            $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','F.CIA_max','F.CIA_min','F.total_minimum_pass','F.ESE_min','F.ESE_max','E.coe_subjects_mapping_id','mar.category_type_id_marks','mar.student_map_id'])
                    ->from('coe_student as A')
                    ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')
                    ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                    ->join('JOIN', 'coe_mark_entry as mar', 'mar.student_map_id=B.coe_student_mapping_id and mar.subject_map_id=E.coe_subjects_mapping_id')
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id');

        if($mark_type==$reguLar)
        {
            $get_stu_absents = AbsentEntry::find()->where(['exam_subject_id'=>$sub_map_id,'exam_type'=>$mark_type,'absent_term'=>$term,'exam_year'=>$exam_year,'exam_month'=>$month])->all();
            $sem_verify = ConfigUtilities::SemCaluclation($exam_year,$month,$bat_map_val);
            if($sem_verify!=$getSubMapp['semester'] && $getDepreeInfo->degree_code!='Ph.D')
            {
                return 3;
            }
            foreach ($get_stu_absents as $absents) 
            {
                $stAbsList[$absents['absent_student_reg']] = $absents['absent_student_reg'];
            }
            $stAbsList = array_filter($stAbsList);
            if($getSubMapp['subject_type_id']==$checElect['coe_category_type_id'])
            {
                $getSemester = SubjectsMapping::findOne($getSubMapp['coe_subjects_mapping_id']);
                $getSubsInfo->join('JOIN','coe_nominal as G','G.coe_subjects_id=F.coe_subjects_id and G.coe_student_id=A.coe_student_id and G.course_batch_mapping_id=B.course_batch_mapping_id and G.course_batch_mapping_id=E.batch_mapping_id');
                $getSubsInfo->Where(['G.semester' => $getSemester->semester]);

            }

            $getSubsInfo->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val]);
           
            if($getSubMapp['subject_type_id']==$checElect['coe_category_type_id'])
            {
                $getSubsInfo->andWhere(['G.coe_subjects_id'=>$getSubMapp['subject_id']]);
            }
            
            $check_entry = MarkEntry::find()->where(['subject_map_id'=>$sub_map_id,'year'=>$exam_year,'month'=>$month,'term'=>$term,'mark_type'=>$mark_type])->andWhere(['IN','category_type_id',$ext_cat_ids])->all();
            $stuIds=[];
            foreach ($check_entry as $value) 
            {
                $stuIds[$value['student_map_id']]=$value['student_map_id'];
            }

            $stuIds = array_filter($stuIds);
            if(!empty($stAbsList))
            {
                $stuIds = array_merge($stuIds,$stAbsList);
                $stuIds = array_filter($stuIds);
            }
            $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
            if($get_regular['coe_category_type_id']!=$mark_type)
            {
                $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_cat_type]);
            }
            $getSubsInfo->Where(['mar.subject_map_id'=>$sub_map_id,'mar.year'=>$exam_year,'mar.category_type_id'=>$cia_cat_id])
                    ->andWhere(['NOT IN', 'student_map_id', $stuIds])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->groupBy('register_number')
                    ->orderBy('register_number');

            $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
            
            $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
            
        }
        else
        {
            $get_stu_absents = AbsentEntry::find()->where(['exam_subject_id'=>$sub_map_id,'exam_year'=>$exam_year,'exam_month'=>$month,'exam_type'=>$mark_type,'absent_term'=>$term])->all();

            $stAbsList = [];
            foreach ($get_stu_absents as $absents) 
            {
                $stAbsList[$absents['absent_student_reg']] = $absents['absent_student_reg'];
            }
            $stAbsList = array_filter($stAbsList);
            $getSubsInfo->join('JOIN', 'coe_mark_entry_master as G', 'G.subject_map_id=E.coe_subjects_mapping_id and G.student_map_id=B.coe_student_mapping_id')
            ->join('JOIN','coe_fees_paid fees','fees.student_map_id = G.student_map_id and fees.subject_map_id = G.subject_map_id');

            $getSubsInfo->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val, 'fees.status'=>'YES']);
            $getSubsInfo->andWhere(['NOT LIKE','result','Pass'])
                        ->andWhere(['<>', 'fees.status', 'NO']);            
            if($section_name!='')
            {
                $getSubsInfo->andWhere(['B.section_name'=>$section_name]);
            }

            $check_entry = MarkEntryMaster::find()->where(['subject_map_id'=>$sub_map_id,'result'=>'Pass'])->all();
            $check_entry_same = MarkEntryMaster::find()->where(['subject_map_id'=>$sub_map_id,'year'=>$exam_year,'month'=>$month,'term'=>$term])->all();
            $stuIds=[];
            foreach ($check_entry as $value) 
            {
                $stuIds[$value['student_map_id']]=$value['student_map_id'];
            }
            $stuIds1=[];
            foreach ($check_entry_same as $value) 
            {
                $stuIds1[$value['student_map_id']]=$value['student_map_id'];
            }
            $stuIds1 = array_filter($stuIds1);
            $stuIds = array_filter($stuIds);
            if(!empty($stAbsList))
            {
                $stuIds = array_merge($stuIds,$stAbsList);
                $stuIds = array_filter($stuIds);
            }
            if(!empty($stuIds1))
            {
                $stuIds = array_merge($stuIds,$stuIds1);
                $stuIds = array_filter($stuIds);
            }
            $getSubsInfo->Where(['G.subject_map_id'=>$sub_map_id,'fees.subject_map_id'=>$sub_map_id,'fees.status'=>'YES','fees.year'=>$exam_year,'fees.month'=>$month])
                    ->andWhere(['NOT IN', 'coe_student_mapping_id', $stuIds])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
            $getSubsInfo->groupBy('register_number');
            $getSubsInfo->orderBy('register_number');
            $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
            $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
          
        }
        return Json::encode($getSubsInfoDetailsse);
    }

    public function actionGetexternalstumarkdetails() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('exam_month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $updated = 'No';
        $section_name = 'All';
        $checElect = Categorytype::find()->where(['description'=>'Elective'])->one();

        $getSubMapp = SubjectsMapping::findOne($sub_map_id);

        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $cia_cat_id = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Internal%' OR category_type like '%CIA%'")->queryScalar();
        $reguLar = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Regular%'")->queryScalar();
        
        $getSubsInfo = new Query();
            $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','F.CIA_max','F.total_minimum_pass','F.CIA_min','F.ESE_min','F.ESE_max','E.coe_subjects_mapping_id','mast.CIA','mast.result','mast.total','mast.grade_name','mast.grade_point','mast.ESE','mar.student_map_id'])
                    ->from('coe_student as A')
                    ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')
                    ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                    ->join('JOIN', 'coe_mark_entry as mar', 'mar.student_map_id=B.coe_student_mapping_id and mar.subject_map_id=E.coe_subjects_mapping_id')
                    ->join('JOIN', 'coe_mark_entry_master as mast', 'mast.student_map_id=B.coe_student_mapping_id and mast.subject_map_id=E.coe_subjects_mapping_id')
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id');
            
            $getSubsInfo->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val,'mast.subject_map_id'=>$sub_map_id,'mast.year'=>$exam_year,'mast.mark_type'=>'27','mast.month'=>$month,'mast.term'=>'34'])->andWhere(['<>','B.status_category_type_id',$det_disc_type]);
            $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
            if($get_regular['coe_category_type_id']!=$mark_type)
            {
                $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_cat_type]);
            }

            $getSubsInfo->groupBy('register_number')->orderBy('register_number');

            $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
            $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
            
        return Json::encode($getSubsInfoDetailsse);
    }



    public function actionGetexternalstumarkdetailsvaladd() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $updated = 'No';
        $section_name = 'All';
        $checElect = Categorytype::find()->where(['description'=>'Elective'])->one();

        $getSubMapp = SubjectsMapping::findOne($sub_map_id);

        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $cia_cat_id = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Internal%' OR category_type like '%CIA%'")->queryScalar();
        $reguLar = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Regular%'")->queryScalar();
        
        $getSubsInfo = new Query();
            $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','F.CIA_max','F.total_minimum_pass','F.CIA_min','F.ESE_min','F.ESE_max','E.coe_sub_mapping_id','mast.CIA','mast.result','mast.total','mast.grade_name','mast.grade_point','mast.ESE','mast.student_map_id'])
                    ->from('coe_student as A')
                    ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')
                    ->join('JOIN', 'sub as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                    ->join('JOIN', 'coe_value_mark_entry as mast', 'mast.student_map_id=B.coe_student_mapping_id and mast.subject_map_id=E.coe_sub_mapping_id')
                    
                    ->join('JOIN', 'coe_value_subjects as F', 'F.coe_val_sub_id=E.val_subject_id');
            
            $getSubsInfo->Where(['E.semester' => $sem_id,'E.coe_sub_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val,'mast.subject_map_id'=>$sub_map_id,'mast.year'=>$exam_year,'mast.mark_type'=>'27','mast.month'=>$month,'mast.term'=>'34'])->andWhere(['<>','B.status_category_type_id',$det_disc_type]);
            $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
            if($get_regular['coe_category_type_id']!=$mark_type)
            {
                $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_cat_type]);
            }

            $getSubsInfo->groupBy('register_number')->orderBy('register_number');

          // print_r($getSubsInfo);exit;
//echo $getSubsInfo->createCommand()->getrawsql(); exit;
            $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
            $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
            
        return Json::encode($getSubsInfoDetailsse);
    }
    public function actionYearexamsubjectsdata()
    {
        $exam_year = Yii::$app->request->post('year');      
        $bat_map_id = Yii::$app->request->post('bat_map_id');      
        $exam_month = Yii::$app->request->post('month');     
        $sem_verify = ConfigUtilities::SemCaluclation($exam_year,$exam_month,$bat_map_id);

        $subjectMapids = Yii::$app->db->createCommand("SELECT distinct C.coe_subjects_id as coe_subjects_id,subject_code FROM  coe_subjects_mapping as B JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id  WHERE B.batch_mapping_id='".$bat_map_id."' and B.semester='".$sem_verify."' group by B.coe_subjects_mapping_id ORDER BY subject_code")->queryAll();
        if(!empty($subjectMapids))
        {
            return Json::encode($subjectMapids);
        }
        else
        {
            return Json::encode("NO");
        }

    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function actionGetsectionnames() {
        $coe_bat_deg_reg_id = Yii::$app->request->post('coe_bat_deg_reg_id');
        $section_list = CoeBatDegReg::find()->select('no_of_section')->where(['coe_bat_deg_reg_id' => $coe_bat_deg_reg_id])->one();
        return Json::encode($section_list);
    }

    public function actionSemesternames() {
        $coe_bat_deg_reg_id = Yii::$app->request->post('coe_bat_deg_reg_id');
        $section_list = CoeBatDegReg::findOne($coe_bat_deg_reg_id);
        $degree_type = Degree::findOne($section_list->coe_degree_id);
        $data = round($degree_type->degree_total_semesters / $degree_type->degree_total_years);
        $sem_type = $data == 3 ? "PG" : 'UG';
        return Json::encode($sem_type);
    }

    public function actionShowrequirefields() {
        $category_type_id = Categorytype::find()->where(['description' => "Rejoin"])->one();
        return $category_type_id->coe_category_type_id;
    }

    /* Dummy Numbers */

    public function actionVerifymarks() 
	{
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $end_number = Yii::$app->request->post('end_number');
        $start_number = Yii::$app->request->post('start_number');
        $exam_type = Yii::$app->request->post('exam_type');
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $exam_term = Yii::$app->request->post('exam_term');
        $examiner_name = Yii::$app->request->post('examiner_name');
        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];
        $category_type_id = Categorytype::find()->where(['description' => "ESE(Dummy)"])->one();
        //Verify the data submission with the below query.
        $get_exam_subj_details = ExamTimetable::find()->where(['exam_year' => $get_id_details['exam_year'], 'exam_month' => $get_id_details['exam_month'], 'subject_mapping_id' => $sub_map_id])->one();

        $subject_mapo = SubjectsMapping::findOne($sub_map_id);
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $subject_name = Subjects::findOne(['coe_subjects_id' => $subject_mapo->subject_id]);
        $semester_name = ConfigUtilities::getSemesterName($subject_mapo->batch_mapping_id);
        // Have to write the count of above query code
        $get_stu_data = "SELECT D.dummy_number,E.subject_code,E.subject_name,A.category_type_id_marks as dummy_marks FROM coe_mark_entry AS A JOIN coe_student_mapping As B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects as E ON E.coe_subjects_id=C.subject_id JOIN coe_dummy_number as D ON D.student_map_id=A.student_map_id AND D.subject_map_id=A.subject_map_id WHERE category_type_id='" . $category_type_id->coe_category_type_id . "' AND D.year='" . $get_id_details['exam_year'] . "' AND D.month='" . $get_id_details['exam_month'] . "' AND status_category_type_id IN('".$det_disc_type."') AND D.subject_map_id='" . $sub_map_id . "' AND D.dummy_number between " . $start_number . " AND " . $end_number . " ";
        $verify_stu_data = Yii::$app->db->createCommand($get_stu_data)->queryAll();
        $get_month_name = Categorytype::findOne($get_id_details['exam_month']);
        $html = '';
        if (count($verify_stu_data) > 0) {
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            /*
             *   Already Defined Variables from the above included file
             *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
             *   use these variables for application
             *   use $file_content_available="Yes" for Content Status of the Organisation
             */
            $header = $body = '';
            $header = '<table  border="1"  class="table table-bordered table-responsive dum_edit_table table-hover" >
            <thead class="thead-inverse">
            <tr>
                    <td colspan=4>
                    <table  width="100%" align="center" border="1" >                    
                    <tr>
                      <td> 
                        <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=2 align="center"> 
                          <center><b><font size="6px">' . $org_name . '</font></b></center>
                          <center> <font size="3px">' . $org_address . '</font></center>
                          
                          <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                     </td>
                      <td align="center">  
                        <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>
                    
                    </table></td></tr>
                    <tr>
                    <td align="center" colspan=4><h5>APPLICATION FOR END ' . $semester_name . ' EXAMINATIONS ' . $get_id_details['exam_year'] . ' - ' . $get_month_name['description'] . '</h5>
                    </td></tr>
                    <tr>
                    <td align="center" colspan=4><h5>STATEMENT OF MARKS</h5></td></tr>
                    <tr>
                        <td align="left"  colspan=2>
                            Q.P.CODE : ' . $get_exam_subj_details->qp_code . '
                        </td>
                        <td align="right" colspan=2>
                            DATE OF VALUATION : ' . date("d/m/Y") . '
                        </td> 
                    </tr>
                    <tr>
                        <td align="left" colspan=4> 
                            ' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " CODE") . ' : (' . $subject_name->subject_code . ') ' . $subject_name->subject_name . '
                        </td>
                    </tr>
            <tr class="table-danger">
                
                <th>SNO</th>  
                <th>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)) . '</th>
                <th>Marks</th>
                <th>Marks In Words</th>
                            
                        </tr>               
                    </thead> 
                    <tbody>     

                    ';
            $increment = 1;
            foreach ($verify_stu_data as $value) {
                $split_number = str_split($value["dummy_marks"]);
                $print_text = $this->valueReplaceNumber($split_number);
                $body .= '<tr><td>' . $increment . '</td><td>' . $value["dummy_number"] . '</td><td>' . $value["dummy_marks"] . '</td><td>' . $print_text . '</td></tr>';
                $increment++;
            }
            $body .= '<tr>
                        <td align="left" colspan=2>
                            Name of the Examiner <br /><br />
                            ' . $examiner_name . ' <br />

                        </td>
                        <td align="right" colspan=2>
                            Name of the Chief Examiner / Controller <br /><br /><br />
                        </td> 
                    </tr>
                    <tr>
                        <td align="left" colspan=2>
                           Signature With Date <br /><br /><br />
                        </td>
                        <td align="right" colspan=2>
                            Signature With Date <br /><br /><br />
                        </td> 
                    </tr></tbody></table>';
            $html = $header . $body;
            if (isset($_SESSION['verify_dummy_marks'])) {
                unset($_SESSION['verify_dummy_marks']);
            }
            $_SESSION['verify_dummy_marks'] = $html;
        }

        $data_pass = !empty($html) ? $html : 0;
        return Json::encode($data_pass);
    }

    public function actionGetdummystudents() {
        $sub_map_id = Yii::$app->request->post('sub_map_id'); // Subject Id
        $limit = Yii::$app->request->post('limit');        
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $start_number = Yii::$app->request->post('start_number');
        $end_number = Yii::$app->request->post('end_number');
        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];
        $sub_ids  = ConfigUtilities::getSubjectMappingIds($sub_map_id,$exam_year,$exam_month);
        $subject_ids = '';
        if(is_array($sub_ids))
        {
            sort($sub_ids);
            for ($i=0; $i <count($sub_ids) ; $i++) 
            { 
                $subject_ids .= "'".$sub_ids[$i]."',";
            }
            $subject_ids = trim($subject_ids,',');
        }
        else
        {
            $subject_ids = "'".$sub_ids."'";
        }
        
        $dummy_student = "SELECT coe_dummy_number_id,student_map_id,dummy_number,subject_map_id FROM coe_dummy_number WHERE subject_map_id IN (" . $subject_ids . ") AND year='" . $get_id_details['exam_year'] . "' AND month='" . $get_id_details['exam_month'] . "' AND student_map_id NOT IN(SELECT student_map_id FROM coe_mark_entry_master WHERE subject_map_id IN (" . $subject_ids . ") AND year='" . $get_id_details['exam_year'] . "' AND month='" . $get_id_details['exam_month'] . "' ) AND dummy_number between $start_number AND $end_number order by dummy_number";

        $dummy_student_data = Yii::$app->db->createCommand($dummy_student)->queryAll();
        $data_pass = !empty($dummy_student_data) ? $dummy_student_data : 0;
        return Json::encode($data_pass);
    }

    public function actionGetdummysubjects() 
    {
        $batch_mapping_id = Yii::$app->request->post('batch_map_id');
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');

        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];

        $subjectMapids = Yii::$app->db->createCommand("SELECT A.subject_mapping_id,subject_code FROM coe_exam_timetable as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id WHERE A.exam_year='" . $get_id_details['exam_year'] . "' AND A.exam_month='" . $get_id_details['exam_month'] . "' and B.batch_mapping_id='" . $batch_mapping_id . "' group by A.subject_mapping_id ORDER BY subject_code")->queryAll();

        $return_subj = array_filter(array('' => ''));

        foreach ($subjectMapids as $key => $value) 
        {
            $return_subj[] = ['sub_map_id' => $value['subject_mapping_id'], 'subject_code' => $value['subject_code']];
        }
    }

    public function actionGetdummynumbersarranged() {
        $subject_map_id = Yii::$app->request->post('subject_map_id'); // Subject Id
        $limit = Yii::$app->request->post('limit');
        $start_number = Yii::$app->request->post('start_number');
        $exam_month = Yii::$app->request->post('exam_month');
        $exam_year = Yii::$app->request->post('exam_year');
        $end_number = $start_number + $limit;
        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];
        $sub_ids = ConfigUtilities::getSubjectMappingIds($subject_map_id,$exam_year,$exam_month);

        $checkStuInfo = new Query();
        $checkStuInfo->select('*')
                ->from('coe_dummy_number as A')                
                ->Where(['A.year' => $get_id_details['exam_year'], 'A.month' => $get_id_details['exam_month']])
                ->andWhere(['IN','subject_map_id',$sub_ids]);
        $check_data_exists = $checkStuInfo->createCommand()->queryAll();

        if(is_array($sub_ids))
        {
            sort($sub_ids);
            $subject_ids = '';
            for ($kk=0; $kk <count($sub_ids) ; $kk++) { 
                $subject_ids .="'".$sub_ids[$kk]."',";
            }
            $subject_ids = trim($subject_ids,',');

        }
        else{
            $subject_ids = $sub_ids;
        }

        
        if (!empty($check_data_exists)) 
        {
             $query = "SELECT count(*) FROM coe_dummy_number WHERE year='" . $get_id_details['exam_year'] . "'  AND month='" . $get_id_details['exam_month'] . "' AND subject_map_id IN (" . $subject_ids . ") AND dummy_number between $start_number AND $end_number"; 

            $get_data = Yii::$app->db->createCommand($query)->queryScalar();

            if($get_data<=30)
            {
                $query_1 = "SELECT max(dummy_number) FROM coe_dummy_number WHERE year='" . $get_id_details['exam_year'] . "'  AND month='" . $get_id_details['exam_month'] . "' AND subject_map_id IN (" . $subject_ids . ") AND dummy_number between $start_number AND $end_number"; 
                $get_max_dum = Yii::$app->db->createCommand($query_1)->queryScalar();
                $get_data = $start_number==$get_max_dum?1:$get_max_dum - $start_number;
            }
            else{
                $get_data = $get_data - 2;
            }


            $end_number = $start_number + $get_data;
            $return_dum_info = $get_data > 0 && !empty($get_data) ? $end_number : 0;
        } else {
            $return_dum_info = 0;
        }

        return Json::encode($return_dum_info);
    }

    public function actionGetrevaldetails() {
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $month_name = Categorytype::findOne($month);
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $revalStuInfo = new Query();
        $revalStuInfo->select(['B.coe_student_mapping_id as student_map_id','register_number', 'subject_code', 'subject_name','E.coe_subjects_mapping_id as sub_map_id'])
                ->from('coe_revaluation as A')
                ->join('JOIN', 'coe_student_mapping as B', 'B.coe_student_mapping_id=A.student_map_id')                
                ->join('JOIN', 'coe_student as D', 'D.coe_student_id=B.student_rel_id')
                ->join('JOIN', 'coe_subjects_mapping as E', 'E.coe_subjects_mapping_id=A.subject_map_id')
                ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                ->Where(['A.year' => $year, 'A.month' => $month,'A.reval_status'=>'YES'])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->groupBy('A.subject_map_id,A.student_map_id')
                ->orderBy('register_number');
        $revalStuDetails = $revalStuInfo->createCommand()->queryAll();
       
        if (!empty($revalStuDetails)) {

            foreach ($revalStuDetails as $valuesss) 
            {
                $get_dummy_numbessr = DummyNumbers::find()->where(['year'=>$year,'month'=>$month,'student_map_id'=>$valuesss['student_map_id']])->one();
                if(!empty($get_dummy_numbessr))
                {
                    $dum_nu = $get_dummy_numbessr['dummy_number'];
                    $batch_mapping_i = SubjectsMapping::findOne($valuesss['sub_map_id']);
                }
            }
            $addd_dow = isset($dum_nu) && !empty($dum_nu) ? '<th>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)) . '</th>' : '';
            $header = $footer = $final_html = $body = '';
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            $semester_name = ConfigUtilities::getSemesterName($batch_mapping_i->batch_mapping_id);
            $header = '<table width="100%"  border="1" style="line-height: 1em;"  class="table table-bordered table-responsive dum_edit_table table-hover" >
            <thead class="thead-inverse">
            <tr>
                    <td colspan=8>
                    <table  width="100%" align="center" border="1" >                    
                    <tr>
                      <td> 
                        <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=6 align="center"> 
                          <center><b><font size="6px">' . $org_name . '</font></b></center>
                          <center> <font size="3px">' . $org_address . '</font></center>
                          
                          <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                     </td>
                      <td align="center">  
                        <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>
                    
                    </table></td></tr>
                    <tr>
                    <td align="center" colspan=8><h5>REVALUATION DETAILS FOR END '.$semester_name.' EXAMINATION ' . $year . ' - ' . $month_name->description . '</h5>
                    </td></tr>
                    
                <tr class="table-danger">
                    <th>SNO</th>  
                    '. $addd_dow . '
                    <th>REGISTER NUMBER</th>
                    <th>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " Code") . '</th>
                    <th colspan=4>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " naME") . '</th>
                </tr>               
            </thead> 
            <tbody>';
            $increment = 1;

            foreach ($revalStuDetails as $value) 
            {
                $get_dummy_number = DummyNumbers::find()->where(['year'=>$year,'month'=>$month,'student_map_id'=>$value['student_map_id'],'subject_map_id'=>$value['sub_map_id']])->one();
                if(empty($get_dummy_number))
                {
                    $body .= '<tr><td>' . $increment . '</td><td> NO ENTRY</td><td>' . $value["register_number"] . '</td><td>' . $value["subject_code"] . '</td><td colspan=4>' . $value["subject_name"] . '</td></tr>';
                }
                else{

                    $body .= '<tr><td>' . $increment . '</td><td>' . $get_dummy_number["dummy_number"] . '</td><td>' . $value["register_number"] . '</td><td>' . $value["subject_code"] . '</td><td colspan=4>' . $value["subject_name"] . '</td></tr>';
                }
                
                $increment++;
                if ($increment % 30 == 0) {
                    $html = $header . $body;
                    $final_html .= $html;
                    $html = $body = '';
                }
            }

            $html = $header . $body;
            $final_html .= $html . "</tbody></table>";
            $content = $final_html;
            $content_1 = '';

            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['dummy-numbers/dummy-revaluation-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
            ]);
            $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/hall-allocate/excel-galley-arrangement'], [
                        'class' => 'pull-right btn btn-block btn-warning',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
            ]);
            $content_1 = '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf . ' </div><div class="col-lg-10" >' . $content . '</div></div></div></div></div>';
            $_SESSION['reval_report_dummy'] = $content;
            
            $return_data = $content_1;
        } else {
            $return_data = 0;
        }
        return Json::encode($return_data);
    }

    public function actionGetdumnuminfo() 
    {
        $year = Yii::$app->request->post('year'); 
        $month = Yii::$app->request->post('month'); 
        $add_sub_map_ids = array_filter([]);
        $exam_date = DATE('Y-m-d',strtotime(Yii::$app->request->post('exam_date'))); 
        $ese_dummy = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%ESE(dummy)%'")->queryScalar();
        $ese_entry = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%ESE%' ")->queryScalar();
        $ese_exter = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%External%' ")->queryScalar();
        if($exam_date!='')
        {
            $getQpCodes = ExamTimetable::find()->where(['exam_year'=>$year,'exam_month'=>$month,'exam_date'=>$exam_date])->all();
            
            if(!empty($getQpCodes))
            {            
                foreach ($getQpCodes as $key => $value) {
                    $add_sub_map_ids[$value['subject_mapping_id']]=$value['subject_mapping_id'];
                }
            }
        }
        else
        {
            $getQpCodes = ExamTimetable::find()->where(['exam_year'=>$year,'exam_month'=>$month])->all();
        }
        
        $month_name = Categorytype::findOne($month);
        if($exam_date!='' && !empty($add_sub_map_ids))
        {
           $check_data_exists = StoreDummyMapping::find()->where(['year' => $year, 'month' => $month])->andWhere(['IN','subject_map_id',$add_sub_map_ids])->groupBy('dummy_from')->orderby('dummy_from')->all();
        }
        else
        {
           $check_data_exists = StoreDummyMapping::find()->where(['year' => $year, 'month' => $month])->groupBy('dummy_from')->orderby('dummy_from')->all();
        }
        
        
        if(!empty($check_data_exists))
        {
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            $header='';  
            $header .= '<table width="100%"  border="1" style="line-height: 1em;"   >
                <tr>
                    <td>
                        <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                    </td>
                    <td colspan=5 align="center"> 
                          <center><b><font size="6px">' . $org_name . '</font></b></center>
                          <center> <font size="3px">' . $org_address . '</font></center>
                          <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                     </td>
                      <td align="center">  
                        <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                      </td>
                 </tr>
                 <tr>
                    <td align="center" colspan=7 ><h5> ' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)). ' DETAILS FOR SEQUENCE FOR ' . $year . ' - ' . strtoupper($month_name->description) . '</h5>
                    </td>
                </tr>  
                <tr class="table-danger">
                    <th>SNO</th>
                    <th>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " CODE") . '</th>
                    <th>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " NAME") . '</th>
                    <th> STARTING NO </th>
                    <th>ENDING NO </th>
                    <th>COUNT</th>
                    <th>MARK ENTRY</th>
                </tr> 
                <tbody>';
                $increment=1;
                foreach ($check_data_exists as $value) 
                {
                    $COUNT = Yii::$app->db->createCommand('SELECT count(*) FROM coe_dummy_number  WHERE dummy_number BETWEEN "'.$value["dummy_from"].'" AND "'.$value["dummy_to"].'" and year="'.$year.'" and month="'.$month.'" ')->queryScalar();
                    
                    $sub_info = Yii::$app->db->createCommand('SELECT subject_code,subject_name FROM coe_subjects as A JOIN coe_subjects_mapping as B ON B.subject_id=A.coe_subjects_id WHERE B.coe_subjects_mapping_id="'.$value["subject_map_id"].'"')->queryOne();
                    
                    $get_mark_count = Yii::$app->db->createCommand('SELECT count(*) FROM coe_mark_entry_master  where subject_map_id IN(SELECT coe_subjects_mapping_id FROM coe_subjects_mapping as A JOIN coe_subjects as B ON B.coe_subjects_id=A.subject_id where subject_code="'.$sub_info["subject_code"].'" ) and year="'.$year.'" and month="'.$month.'" and result NOT LIKE "%Absent%"')->queryScalar();
                    $count_dum = $COUNT!=$get_mark_count?'style="background: orange; color: #FFF; font-weight: bold;"':'';
                    $header .= '<tr '.$count_dum.' >
                                <td>' . $increment . '</td>
                                <td>' . $sub_info["subject_code"] . '</td>
                                <td>' . $sub_info["subject_name"] . '</td>
                                <td>' . $value["dummy_from"] . '</td>
                                <td>' . $value["dummy_to"] . '</td>
                                <td>' .$COUNT . '</td>
                                <td>' .$get_mark_count . '</td>
                             </tr>';
                    $increment++;
                }
                $header .='</tbody></table>';

                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['dummy-numbers/dummy-store-info-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
                ]);
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/dummy-numbers/excel-dummy-info-pdf-excel'], [
                            'class' => 'pull-right btn btn-block btn-warning',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated Excel file in a new window'
                ]);
                $content_1 = '<br /><br /><br /><br /><br /><br /><div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf . ' <br /> ' . $print_excel . ' </div><div class="col-lg-10" >' . $header . '</div></div></div></div></div>';
               
                if(isset($_SESSION['dummy_store_info']))
                {
                    unset($_SESSION['dummy_store_info']);
                }
                $_SESSION['dummy_store_info']=$header;             
                return Json::encode($content_1);
        }
        else
        { 
            return Json::encode(0);
        }
    }

    public function actionGetdumnumyregnumbers() 
    {
        $year = Yii::$app->request->post('year'); 
        $sub_id = Yii::$app->request->post('sub_id'); 
        $month = Yii::$app->request->post('month'); 
        $split_data = ConfigUtilities::getSubjectMappingIds($sub_id,$year,$month);
        $sub_map_id_send = array_filter(['']);
        if(is_array($split_data))
        {
            sort($split_data);
            for ($k=0; $k <count($split_data) ; $k++) 
            { 
                $sub_map_id_send[$split_data[$k]] = $split_data[$k];
            }
        }
        else
        {
           $sub_map_id_ins = SubjectsMapping::find()->where(['coe_subjects_mapping_id'=>$split_data])->one();
           $sub_map_id_send[$sub_map_id_ins->coe_subjects_mapping_id] = $sub_map_id_ins->coe_subjects_mapping_id;
        }
        $month_name = Categorytype::findOne($month);
        $check_data_exists = DummyNumbers::find()->where(['year' => $year, 'month' => $month])->andWhere(['IN','subject_map_id',$sub_map_id_send])->groupBy('dummy_number')->orderBy('dummy_number')->all();

        if(!empty($check_data_exists))
        {
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            $header='';  
            $header .= '<table class="table table-striped table-responsive table-bordered" width="100%"  border="1" style="line-height: 1em;"   >
                <tr>
                    <td colspan=5 align="center"> 
                          <center><b><font size="6px">' . $org_name . '</font></b></center>
                          <center> <font size="3px">' . $org_address . '</font></center>
                          <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                     </td>
                 </tr>
                 <tr>
                    <td align="center" colspan=5 ><h4> ' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)). ' REG NUMBERS DETAILS ' . $year . ' - ' . strtoupper($month_name->description) . '</h4>
                    </td>
                </tr>  
                <tr class="table-danger">
                    <th>SNO</th>
                    <th>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " CODE") . '</th>
                    <th>SEMESTER</th>
                    <th>REGISTER NUMBER</th>
                    <th>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)). '</th>
                </tr> 
                <tbody>';
                $increment=1;
                foreach ($check_data_exists as $value) 
                {

                    $dum_num = DummyNumbers::find()->where(['year'=>$year,'month'=>$month,'dummy_number'=>$value["dummy_number"]])->one();
                    $stuMapInfo = StudentMapping::findOne($dum_num->student_map_id);
                    $stuDetails = Student::findOne($stuMapInfo->student_rel_id);
                    $sub_info = Yii::$app->db->createCommand('SELECT subject_code,semester FROM coe_subjects as A JOIN coe_subjects_mapping as B ON B.subject_id=A.coe_subjects_id WHERE B.coe_subjects_mapping_id="'.$value["subject_map_id"].'"')->queryOne();
                    $header .= '<tr>
                                    <td>' . $increment . '</td>
                                    <td>' . $sub_info["subject_code"] . '</td>
                                    <td>' . $sub_info["semester"] . '</td>
                                    <td>' .$stuDetails->register_number . '</td>
                                    <td>' . $value["dummy_number"] . '</td>                                
                                </tr>';
                    $increment++;
                }
                $header .='</tbody></table>';

                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['dummy-numbers/dummy-store-reg-info-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
                ]);
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/dummy-numbers/excel-dummy-reg-info-pdf'], [
                            'class' => 'pull-right btn btn-block btn-warning',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated Excel file in a new window'
                ]);
                $content_1 = '<br /><br /><br /><br /><br /><br /><div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf . '<br />'.$print_excel.' </div><div class="col-lg-10" >' . $header . '</div></div></div></div></div>';
                if(isset($_SESSION['dummy_store_reg_info']))
                {
                    unset($_SESSION['dummy_store_reg_info']);
                }
                $_SESSION['dummy_store_reg_info']=$header;               
                return Json::encode($content_1);
        }
        else
        { 
            return Json::encode(0);
        }
    }

    public function actionGetstoreddata() {
        $subject_map_id = Yii::$app->request->post('subject_map_id'); // subject_id
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];
        $sub_ids = ConfigUtilities::getSubjectMappingIds($subject_map_id,$exam_year,$exam_month);
        
        $check_data_exists = StoreDummyMapping::find()->where(['year' => $get_id_details['exam_year'], 'month' => $get_id_details['exam_month']])->andWhere(['IN','subject_map_id' ,$sub_ids ])->one();

        $return_dum_info = !empty($check_data_exists) ? ['dummy_from' => $check_data_exists->dummy_from, 'dummy_to' => $check_data_exists->dummy_to] : 0;
        return Json::encode($return_dum_info);
    }

    public function actionStoredummynumbers() 
    {
        $subject_map_id = Yii::$app->request->post('sub_map_id'); // subject id
        $start_number = Yii::$app->request->post('start_number');
        $end_number = Yii::$app->request->post('end_number');
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $inserted_res = 0;
        $sub_ids = ConfigUtilities::getSubjectMappingIds($subject_map_id,$exam_year,$exam_month); 
        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];

        $check_data_exist_numbers = StoreDummyMapping::find()->where(['year' => $get_id_details['exam_year'], 'month' => $get_id_details['exam_month']])->all();
        
        $all_numbers_array = array_filter(array('' => ''));
        if (!empty($check_data_exist_numbers)) {
            foreach ($check_data_exist_numbers as $value) {
                for ($i = $value['dummy_from']; $i <= $value['dummy_to']; $i++) {
                    $all_numbers_array[] = $i;
                }
            }
        }
        if (in_array($start_number, $all_numbers_array) || in_array($end_number, $all_numbers_array)) {
            $inserted_res = 'Duplicate';
            return Json::encode($inserted_res);
        }
        
        $split_data = $sub_ids;        
        if(is_array($split_data))
        {
            sort($split_data);
           for ($i=0; $i <count($split_data) ; $i++) 
           { 
              $check_data_exists_loop = StoreDummyMapping::find()->where(['year' => $get_id_details['exam_year'],'subject_map_id'=>$split_data[$i], 'month' => $get_id_details['exam_month']])->one();

              if (empty($check_data_exists_loop)) 
                {
                    $created_at = date("Y-m-d H:i:s");
                    $updateBy = Yii::$app->user->getId();
                    $model = new StoreDummyMapping();
                    $model->subject_map_id = $split_data[$i];
                    $model->year = $get_id_details['exam_year'];
                    $model->month = $get_id_details['exam_month'];
                    $model->dummy_from = $start_number;
                    $model->dummy_to = $end_number;                    
                    $model->created_at = $created_at;
                    $model->created_by = $updateBy;
                    $model->updated_at = $created_at;
                    $model->updated_by = $updateBy;
                    $model->save();
                    unset($model);                   
                    $inserted_res = 1;
                } else {
                    $inserted_res = 0;
                }
            }
        }
        else if(!empty($sub_ids))
        {
            $check_data_exists_loop = StoreDummyMapping::find()->where(['year' => $get_id_details['exam_year'],'subject_map_id'=>$sub_ids, 'month' => $get_id_details['exam_month']])->one();
            if (empty($check_data_exists)) 
            {
                $created_at = date("Y-m-d H:i:s");
                $updateBy = Yii::$app->user->getId();
                $model = new StoreDummyMapping();
                $model->subject_map_id = $sub_ids;
                $model->year = $get_id_details['exam_year'];
                $model->month = $get_id_details['exam_month'];
                $model->dummy_from = $start_number;
                $model->dummy_to = $end_number;
               
                $model->created_at = $created_at;
                $model->created_by = $updateBy;
                $model->updated_at = $created_at;
                $model->updated_by = $updateBy;
                $model->save();
                unset($model);   
                $inserted_res = 1;
            } else {
                $inserted_res = 0;
            }
        }
        else
        {
            $inserted_res = 0;
        }
        
        return Json::encode($inserted_res);
    }

    public function actionGetexaminername() 
    {
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
         $subject_map_id  = ConfigUtilities::getSubjectMappingIds(Yii::$app->request->post('subject_map_id'),$exam_year,$exam_month);
        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];
        $subject_info = new Query();
        $subject_info->select('*')
                ->from('coe_dummy_number as A')
                ->Where(['year' => $get_id_details['exam_year'], 'month' => $get_id_details['exam_month']]);
        $subject_name_det = $subject_info->createCommand()->queryAll();
		$batch_mmap_ids = '';
        if(is_array($subject_map_id))
        {           
            sort($subject_map_id);
            for ($che=0; $che <count($subject_map_id) ; $che++) 
            { 
                $sub_code = SubjectsMapping::findOne($subject_map_id[$che]);
                $batch_mmap_ids .= $sub_code->batch_mapping_id.",";
            }            
            $batch_mmap_ids = trim($batch_mmap_ids,",");
        }
        else
        {
            $sub_code = SubjectsMapping::findOne($subject_map_id);    
            $batch_mmap_ids = $sub_code->batch_mapping_id;  
        }
        
        $get_dummy_subjects = Yii::$app->db->createCommand('SELECT DISTINCT B.batch_mapping_id as batch_map_id ,coe_subjects_mapping_id,A.chief_examiner_name FROM coe_dummy_number as A JOIN coe_subjects_mapping AS B ON B.coe_subjects_mapping_id=A.subject_map_id WHERE year="' . $get_id_details['exam_year'] . '" AND month="' . $get_id_details['exam_month'] . '"')->queryAll();
        
        if (!empty($get_dummy_subjects)) {
            foreach ($get_dummy_subjects as $value) {
                if (strpos($batch_mmap_ids, $value['batch_map_id']) !== FALSE  && $value['chief_examiner_name'] != '') {
                    $examiner_name = ['chief_examiner_name' => $value['chief_examiner_name']];
                    break;
                }
            }
        }

        if (isset($examiner_name) && !empty($examiner_name) && $examiner_name != "") {
            $data = $examiner_name;
        } else {
            $data = 0;
        }
        return Json::encode($data);
    }

    public function actionGetdummysubjectinfo() 
    {
        $subject_map_id = Yii::$app->request->post('subject_map_id'); 
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];        
        $subj_ids = ConfigUtilities::getSubjectMappingIds($subject_map_id,$exam_year,$exam_month);

        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $arranged_info = new Query();
        $arranged_info->select('count(distinct A.register_number) as count')
                ->from('coe_hall_allocate as A')
                ->join('JOIN', 'coe_exam_timetable as B', 'B.coe_exam_timetable_id=A.exam_timetable_id and B.exam_year=A.year and B.exam_month=A.month')
                ->join('JOIN', 'coe_subjects_mapping as C', 'C.coe_subjects_mapping_id=B.subject_mapping_id')
                ->join('JOIN', 'coe_student_mapping as D', 'D.course_batch_mapping_id=C.batch_mapping_id')
                ->join('JOIN', 'coe_student as E', 'D.student_rel_id=E.coe_student_id and A.register_number=E.register_number')
                ->where(['B.exam_year' => $get_id_details['exam_year'],'B.exam_month'=>$get_id_details['exam_month'],'A.year' => $get_id_details['exam_year'],'A.month'=>$get_id_details['exam_month']])->andWhere(['<>','status_category_type_id',$det_disc_type])
                ->andWhere(['IN','B.subject_mapping_id',$subj_ids]);
        $arranged = $arranged_info->createCommand()->queryScalar();

        $examm_info = new Query();
        $examm_info->select('DISTINCT (exam_date) as exam_date')
                ->from('coe_exam_timetable as B')
                ->where(['B.exam_year' => $get_id_details['exam_year'],'B.exam_month'=>$get_id_details['exam_month']])
                ->andWhere(['IN','B.subject_mapping_id',$subj_ids]);
        $get_exam_dates = $examm_info->createCommand()->queryAll();

        $exam_dates = '';
        foreach ($get_exam_dates as $dates) {
            $exam_dates .= $dates['exam_date'] . ",";
        }
        $exam_dat = trim($exam_dates, ',');
        if(strpos($exam_dat,','))
        {
            $exam_dates = array_filter(array(''=>''));
            $explode = explode(',', $exam_dat);
            for ($i=0; $i <count($explode) ; $i++) 
            { 
                $exam_dates[$explode[$i]] = $explode[$i];
            }
        }
        else
        {
            $exam_dates = $exam_dat;
        }

        $get_ab_info = new Query();
        $get_ab_info->select('count(absent_student_reg) as count')
                ->from('coe_absent_entry as A')
                ->join('JOIN', 'coe_student_mapping as B', 'B.coe_student_mapping_id=A.absent_student_reg')
                ->where(['A.exam_year'=>$get_id_details['exam_year'],'A.exam_month'=>$get_id_details['exam_month']])
                ->andWhere(['IN','A.exam_date',$exam_dates])
                ->andWhere(['IN','A.exam_subject_id',$subj_ids])
                ->andWhere(['<>','B.status_category_type_id',$det_disc_type]);
        $get_absent_reg = $get_ab_info->createCommand()->queryScalar();
       
        $get_dummy_info = new Query();
        $get_dummy_info->select('count(student_map_id) as count')
                ->from('coe_dummy_number as A')
                ->where(['A.year'=>$get_id_details['exam_year'],'A.month'=>$get_id_details['exam_month']])
                ->andWhere(['IN','A.subject_map_id',$subj_ids]);
        $get_dummy_reg = $get_dummy_info->createCommand()->queryScalar();

        $subject_info = new Query();
        $subject_info->select(['A.subject_name', 'A.subject_code', 'A.ESE_min', 'A.ESE_max', 'A.total_minimum_pass'])
                ->from('coe_subjects as A')
                ->where(['A.coe_subjects_id' => $subject_map_id]);
        $subject_name_det = $subject_info->createCommand()->queryOne();

        $return_subj = ['galley_arranged' => $arranged, 'student_absent' => $get_absent_reg, 'dummy_arranged' => $get_dummy_reg, 'subject_name' => $subject_name_det['subject_name'], 'subject_code' => $subject_name_det['subject_code'], 'min' => $subject_name_det['ESE_min'], 'max' => $subject_name_det['ESE_max'], 'pass' => $subject_name_det['total_minimum_pass']];

        return Json::encode($return_subj);
    }

    
    public function actionGeneratedummynumbers() 
    {
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $semester_val = Yii::$app->request->post('semester_val');
        $batch_mapping_id = Yii::$app->request->post('batch_mapping_id');
        if(isset($semester_val) && !empty($semester_val))
        {
            $semester_val=$semester_val+1;
        }

        $subs_ids =  ConfigUtilities::getSubjectMappingIds($sub_map_id,$exam_year,$exam_month);
        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $fetch_query = new Query();
        $fetch_query->select(['C.register_number', 'C.name', 'D.coe_student_mapping_id'])
                ->from('coe_hall_allocate as A')
                ->join('JOIN', 'coe_exam_timetable as B', 'B.coe_exam_timetable_id=A.exam_timetable_id and B.exam_year=A.year and B.exam_month=A.month')
                ->join('JOIN', 'coe_subjects_mapping as E', 'E.coe_subjects_mapping_id=B.subject_mapping_id')
                ->join('JOIN', 'coe_student as C', 'C.register_number=A.register_number')
                ->join('JOIN', 'coe_student_mapping as D', 'D.student_rel_id=C.coe_student_id and D.course_batch_mapping_id=E.batch_mapping_id')
                ->Where(['B.exam_year' => $get_id_details['exam_year'], 'B.exam_month' => $get_id_details['exam_month'],'A.year' => $get_id_details['exam_year'], 'A.month' => $get_id_details['exam_month']]);

        if(isset($semester_val) && !empty($semester_val))
        {
            $fetch_query->andWhere(['=', 'E.semester', $semester_val]);
        }
        if(isset($batch_mapping_id) && !empty($batch_mapping_id))
        {
            $fetch_query->andWhere(['=', 'E.batch_mapping_id', $batch_mapping_id])
                        ->andWhere(['=', 'D.course_batch_mapping_id', $batch_mapping_id]);
        }
        $fetch_query->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->andWhere(['IN','B.subject_mapping_id',$subs_ids])
                ->groupBy('C.register_number');
        $get_student_data = $fetch_query->createCommand()->queryAll();

        $exam_query = new Query();
        $exam_query->select(' DISTINCT (exam_date) as exam_date')
                ->from('coe_exam_timetable')
                ->Where(['exam_year' => $get_id_details['exam_year'], 'exam_month' => $get_id_details['exam_month']])
                ->andWhere(['IN','subject_mapping_id',$subs_ids]);
        $get_exam_dates = $exam_query->createCommand()->queryAll();
        $exam_dates = '';
        foreach ($get_exam_dates as $dates) {
            $exam_dates .= "'" . $dates['exam_date'] . "',";
        }
        $exam_dates = trim($exam_dates, ',');
        $get_absent_reg = array();
        
        if(is_array($subs_ids))
        {
            sort($subs_ids);
            $subject_ids = '';
            for ($kin=0; $kin <count($subs_ids) ; $kin++) 
            { 
                $subject_ids .= "'".$subs_ids[$kin]."',";
            }
            $subject_ids = trim($subject_ids,',');
        }
        else
        {
           $subject_ids = $subs_ids;
        }
        $addInQuery = isset($batch_mapping_id) && !empty($batch_mapping_id) ?" AND B.course_batch_mapping_id='".$batch_mapping_id."' ":'';

        foreach ($get_student_data as $value) 
        {
            $get_stu_map_id = $value['coe_student_mapping_id'];            
            $get_absent_reg[] = Yii::$app->db->createCommand("SELECT DISTINCT C.register_number as register_number,C.name FROM coe_absent_entry as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg JOIN coe_student as C ON C.coe_student_id=B.student_rel_id WHERE A.exam_subject_id IN(". $subject_ids .") $addInQuery AND A.exam_date IN( ". $exam_dates . ") AND A.absent_student_reg='" . $get_stu_map_id . "' and status_category_type_id NOT IN('".$det_disc_type."') and A.exam_year='".$get_id_details['exam_year']."' and A.exam_month='".$get_id_details['exam_month']."' ")->queryOne();
            
            $get_dummy_numbers[] = Yii::$app->db->createCommand("SELECT DISTINCT C.register_number as register_number,C.name FROM coe_dummy_number as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_student as C ON C.coe_student_id=B.student_rel_id WHERE A.subject_map_id IN (".$subject_ids.") $addInQuery AND A.year='" . $get_id_details['exam_year'] . "' AND A.month='" . $get_id_details['exam_month'] . "' AND A.student_map_id='" . $get_stu_map_id . "' and status_category_type_id NOT IN('".$det_disc_type."') ")->queryOne();
        }

        // Remove the empty values 

        $get_absent_reg = array_filter($get_absent_reg);
        $get_dummy_numbers = array_filter($get_dummy_numbers);
        sort($get_absent_reg);
        sort($get_dummy_numbers);


        if (!empty($get_dummy_numbers)) {
            $i = 0;
            foreach ($get_student_data as $key => $value) {
                foreach ($get_dummy_numbers as $dummy_of) {
                    if ($dummy_of['register_number'] == $value['register_number']) {
                        unset($get_student_data[$key]);
                    }
                }
            }
        }
        if (!empty($get_absent_reg)) {

            foreach ($get_student_data as $key1 => $value1) {
                foreach ($get_absent_reg as $keysss => $valuesss) {
                    if ($valuesss['register_number'] == $value1['register_number']) {
                        unset($get_student_data[$key1]);
                    }
                }
            }
        }
        sort($get_student_data);

        $final_data = count($get_student_data) > 0 ? $get_student_data : 0;

        return Json::encode($final_data);
    }

    /* Dummy Numbers Ends *./

      // Student Functions End Here
      /* Category Functions */

    public function actionGetcategoryvalue() {
        $c_id = Yii::$app->request->post('category_id');
        $c_id_val = Categorytype::find()->where(['category_id' => $c_id])->all();
        if (count($c_id_val) > 0) {
            $table = '';
            $s = 1;
            $table .= '<table id="checkAllFeat" class="table table-striped" align="center" border=1>     
                  <thead id="t_head">                                                                                                               
                     <th> S.NO </th>                                                                                                                
                     <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY_TYPE) . ' </th>  
                     <th> Description </th>
                     <th> Actions </th>                                       
                  </thead><tbody>';
            foreach ($c_id_val as $c_id_val1) {
                $table .= "<tr>" .
                        "<td>" . $s . "</td>" .
                        "<td class='nr'><input type='hidden' name=cat_type" . $s . " class='cat_type' value='" . $c_id_val1['category_type'] . "'>" . $c_id_val1['category_type'] . "</td>" .
                        "<td><input type='hidden' name=cat_desc" . $s . " value='" . $c_id_val1['description'] . "'>" . $c_id_val1['description'] . "</td>" .
                        "<td><input type='button' onclick='updateFunction(this.id)' name=cat_update" . $s . " id=" . $c_id_val1['coe_category_type_id'] . " value='Update' class='btn btn-primary cat_update'>&nbsp;" .
                        "<input type='button' onclick='deleteFunction(this.id)' name=cat_delete" . $s . " id=" . $c_id_val1['coe_category_type_id'] . " value='Delete' class='btn btn-danger cat_delete'></td>" .
                        "</tr>";
                $s++;
            }
            $table .= "</tbody></table>";
            return $table;
        } else {
            return 0;
        }
    }

    public function actionGetdegree() {
        $b_id = Yii::$app->request->post('batch_id');
        $b_id_val = CoeBatDegReg::find()->where(['coe_batch_id' => $b_id])->orderBy(['coe_batch_id' => SORT_ASC])->all();
    }

    public function actionGetcategorytype() {
        $c_type = Yii::$app->request->post('c_type');
        $c_desc = Yii::$app->request->post('c_desc');
        $cat_val = Yii::$app->request->post('cat_val');
        $c_type_val1 = Categorytype::find()->where(['category_id' => $cat_val, 'category_type' => $c_type, 'description' => $c_desc])->one();
        if (count($c_type_val1) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function actionGetcategorydelete() {
        $c_type_id = Yii::$app->request->post('c_type_id');
        $subject = Yii::$app->db->createCommand("select * from coe_subjects_mapping where paper_type_id='" . $c_type_id . "' or subject_type_id='" . $c_type_id . "' or course_type_id='" . $c_type_id . "'")->queryAll();
        $student = Student::find()->where(['admission_status' => $c_type_id])->one();
        $student_mapping = StudentMapping::find()->where(['admission_category_type_id' => $c_type_id])->one();
        $exam = Yii::$app->db->createCommand("select * from coe_exam_timetable where exam_month='" . $c_type_id . "' or exam_type='" . $c_type_id . "' or exam_term='" . $c_type_id . "' or exam_session='" . $c_type_id . "'")->queryAll();
        $galley = Yii::$app->db->createCommand("select * from coe_hall_master where hall_type_id='" . $c_type_id . "'")->queryScalar();
        //$mark_entry = MarkEntry::find()->where(['category_type_id' => $c_type_id])->one();
        if (!empty($subject) || !empty($student) || !empty($student_mapping) || !empty($exam) || !empty($galley)) {
            echo "You can not delete this property!!! This property is using in " . Yii::$app->params['app_name'];
        } else {
            Categorytype::findOne($c_type_id)->delete();
            return 0;
        }
    }

    public function actionGetcategoryupdate() {
        $c_type_id = Yii::$app->request->post('c_type_id');
        $subject = Yii::$app->db->createCommand("select * from coe_subjects_mapping where paper_type_id='" . $c_type_id . "' or subject_type_id='" . $c_type_id . "' or course_type_id='" . $c_type_id . "'")->queryAll();
        $student = Student::find()->where(['admission_status' => $c_type_id])->one();
        $student_mapping = StudentMapping::find()->where(['admission_category_type_id' => $c_type_id])->one();
        $exam = Yii::$app->db->createCommand("select * from coe_exam_timetable where exam_month='" . $c_type_id . "' or exam_type='" . $c_type_id . "' or exam_term='" . $c_type_id . "' or exam_session='" . $c_type_id . "'")->queryAll();
        $galley = Yii::$app->db->createCommand("select * from coe_hall_master where hall_type_id='" . $c_type_id . "'")->queryScalar();
        if (!empty($subject) || !empty($student) || !empty($student_mapping) || !empty($exam) || !empty($galley)) {
            return 0;
        } else {
            $cat_type_val = Yii::$app->db->createCommand("select coe_category_type_id,category_type,description from coe_category_type where coe_category_type_id='" . $c_type_id . "'")->queryAll();
            return Json::encode($cat_type_val);
        }
    }

    public function actionGetupdatecategory() {
        $cat_id = Yii::$app->request->post('cat_id');
        $cat_type = Yii::$app->request->post('cat_type');
        $cat_desc = Yii::$app->request->post('cat_desc');
        $updated_at = date("Y-m-d H:i:s");
        $updated_by = Yii::$app->user->getId();
        if ($cat_type == "" && $cat_desc == "") {
            return 0;
        } else {
            Yii::$app->db->createCommand("update coe_category_type set category_type='" . $cat_type . "',description='" . $cat_desc . "',updated_by='".$updated_by."',updated_at='".$updated_at."' where coe_category_type_id='" . $cat_id . "'")->query();
            return 1;
        }
    }

    /* Category Functions End Here */
    /* Batch Related Functions Starts Here */

    public function actionGetexistprogramme() {
        $batch_id = Batch::find()->where(['batch_name' => $_POST['batch']])->one();
        $exist_programme = Yii::$app->db->createCommand("select programme_code from coe_programme where coe_programme_id not in(select coe_programme_id from coe_bat_deg_reg where coe_degree_id='" . $_POST['degree_id'] . "' and coe_batch_id='" . $batch_id->coe_batch_id . "')")->queryAll();
        if (!empty($exist_programme)) {
            return Json::encode($exist_programme);
        } else {
            return 0;
        }
    }

    public function actionGetcheckedbatch() {
        $check_batch = Batch::find()->where(['batch_name' => $_POST['batch']])->one();
        if (!empty($check_batch->batch_name)) {
            return json_encode($check_batch->batch_name);
        } else {
            return 0;
        }
    }

    public function actionGetregulationyear() {
        $regulation = new Regulation();
        $reg_year = $_POST['reg_year'];
        $check_regulation = Regulation::find()->where(['regulation_year' => $reg_year])->one();
        if (!empty($check_regulation->regulation_year)) {
            return json_encode($check_regulation->regulation_year);
        } else {
            $reg = 0;
            return json_encode($reg);
        }
    }

    public function actionGetexistbatch() {
        $bat_name = $_POST['batch'];
        $regyear = $_POST['regyear'];
        $check_batch = Batch::find()->where(['batch_name' => $bat_name])->one();
        $configuration = new Configuration();
        $current_date = date('Y-m-d');
        $s_date = Configuration::find()->where(['config_name' => ConfigConstants::CONFIG_BATCH_LOCKING_START])->one();
        $st_date = $s_date->config_value . "-" . date("Y");
        $start_date = date("Y-m-d", strtotime($st_date));
        $e_date = Configuration::find()->where(['config_name' => ConfigConstants::CONFIG_BATCH_LOCKING_END])->one();
        $ed_date = $e_date->config_value . "-" . date("Y");
        $end_date = date("Y-m-d", strtotime($ed_date));
        if (!empty($check_batch->batch_name)) {
            $query = "select a.degree_code,b.programme_code,e.no_of_section from coe_degree a,coe_programme b,coe_batch c,coe_bat_deg_reg e where a.coe_degree_id=e.coe_degree_id and b.coe_programme_id=e.coe_programme_id and c.coe_batch_id=e.coe_batch_id and c.batch_name='" . $check_batch->batch_name . "'";
            $view_batch = Yii::$app->db->createCommand($query)->queryAll();
            $view_reg_year = Yii::$app->db->createCommand("select a.regulation_year from coe_regulation a,coe_batch b where a.coe_batch_id=b.coe_batch_id and b.batch_name='" . $check_batch->batch_name . "'")->queryScalar();
            $view_grade = Yii::$app->db->createCommand("select grade_point_from,grade_point_to,grade_name,grade_point from coe_regulation where grade_point_from is not null and grade_point_to is not null and grade_name is not null and grade_point is not null and regulation_year='" . $view_reg_year . "'")->queryAll();
            $table = '';
            $table .= '<div class="panel  box box-info"><div class="box-header  with-border" role="tab" ><div class="row"><div class="col-md-10"><h4 class="padding box-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . ' Information</a></h4></div></div></div><div id="collapseOne" class="panel-collapse collapse in"><div class="box-body">';
            $table .= '<table id="checkAllFeat" class="table table-responsive table-striped" align="center" ><thead id="t_head"><th>' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . '</th><th>' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . '</th><th> No of ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION) . '</th></thead><tbody>';
            foreach ($view_batch as $view) {
                $table .= "<tr><td>" . $view['degree_code'] . "</td><td>" . $view['programme_code'] . "</td><td>" . $view['no_of_section'] . "</td></tr>";
            }
            $table .= "</tbody></table></div></div></div>";
            $grade_table = '<div class="panel box box-danger"><div class="box-header with-border"><div class="row"><div class="col-md-10"><h4 class="padding box-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Grade Information</a></h4></div></div></div><div id="collapseTwo" class="panel-collapse collapse in"><div class="box-body">';
            $grade_table .= '<table id="checkAllFeatt" class="table table-responsive table-striped" align="center" ><thead id="t_head"><th>Regulation Year</th><th>Grade Range From </th><th>Grade Range To</th><th>Grade Name</th><th>Grade Point</th></thead><tbody>';
            foreach ($view_grade as $grade) {
                $grade_table .= "<tr><td align='center'>" . $view_reg_year . "</td><td align='center'>" . $grade['grade_point_from'] . "</td><td align='center'>" . $grade['grade_point_to'] . "</td><td align='center'>" . strtoupper($grade['grade_name']) . "</td><td align='center'>" . $grade['grade_point'] . "</td></tr>";
            }
            $grade_table .= "</tbody></table></div></div></div>";
            $data = ['grade_table' => $grade_table, 'batch_table' => $table];
            return Json::encode($data);
        } elseif ($current_date > $end_date) {
            Yii::$app->ShowFlashMessages->setMsg('Error', ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH) . ' Creation is Locked!!');
            return $this->redirect(['batch/create',]);
        } else {
            return 0;
        }
    }

    public function actionGetprogramme() {
        $programme = new Programme();
        $view_grade = Yii::$app->db->createCommand("select grade_point_from,grade_point_to,grade_name,grade_point from coe_regulation where grade_point_from!='NULL' and grade_point_to!='NULL' and grade_name!='NULL' and grade_point!='NULL' and regulation_year='" . $_POST['reg_year'] . "'")->queryAll();
        $grade_table = '';
        if ($view_grade) {
            $grade_table = '<div class="panel box box-danger"><div class="box-header with-border"><div class="row"><div class="col-md-10"><h4 class="padding box-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Grade Information</a></h4></div></div></div><div id="collapseTwo" class="panel-collapse collapse in"><div class="box-body">';
            $grade_table .= '<table id="checkAllFeat" class="table table-striped" align="center" ><thead id="t_head"><th>Grade Range From </th><th>Grade Range To</th><th>Grade Name</th><th>Grade Point</th></thead><tbody>';
            foreach ($view_grade as $grade) {
                $grade_table .= "<tr><td>" . $grade['grade_point_from'] . "</td><td>" . $grade['grade_point_to'] . "</td><td>" . strtoupper($grade['grade_name']) . "</td><td>" . $grade['grade_point'] . "</td></tr>";
            }
            $grade_table .= "</tbody></table>";
        }
        $programme_list = Programme::find()->all();
        $pgm_count = count($programme_list);
        $data = ['grade_table' => $grade_table, 'pgm_count' => $pgm_count];
        return Json::encode($data);
    }

    public function actionGetgrade() {
        
    }

    public function actionGetdegpgmtable() {
        $degree = new Degree();
        $programme = new Programme();
        $batch = new Batch();
        $degree_list = Degree::find()->all();
        $programme_list = Programme::find()->all();
        if (count($degree_list) > 0 && count($programme_list) > 0) {
            $table = '';
            $table = '<table id="checkAllFeat" class="table table-striped" align="center" >                                                                         
                    <thead id="t_head">                                                                                                                                     
                    <th>' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . '</th>                                                                                                                                          
                    <th>' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . '</th>                                                                                                                                 
                    <th> No of ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION) . '</th>                                                                                                                                                
                    </thead><tbody>';
            $table .= '<tr><td><select class="form-control width_select_box" id="degree"><option value=""> Select ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . ' </option>';
            foreach ($degree_list as $deg) {
                $table .= "<option value='" . $deg['degree_code'] . "'>" . $deg['degree_code'] . "</option>";
            }
            $table .= '</select></td><td>';
            $sn = 1;
            foreach ($programme_list as $pgm) {
                $table .= '<div class="form-group checkbox"><label class="flat-green checkbox_label"><input class="flat-green messageCheckbox" onclick="getShowData(this.id)" name="programmes_' . $sn . '" type=checkbox id=check_' . $sn . ' style="width:10px;size:100px" value=' . $pgm['programme_code'] . ' /> &nbsp;<b>' . $pgm['programme_code'] . '</b></lable></div>';
                $sn++;
            }
            $table .= '</td><td>';
            $count = count($programme_list);
            for ($i = 1; $i <= $count; $i++) {
                $table .= "<select class='form-control width_select_box' name='no_of_sections' id='programme_selected$i' disabled > 
                    <option value='1'>1</option>";
                $table .= "<option value='2'>2</option>";
                $table .= "<option value='3'>3</option>";
                $table .= "<option value='4'>4</option>";
                $table .= "<option value='5'>5</option></select>";
            }
            $table .= '</td></tr>';
            return $table;
        } else {
            return $table = 0;
        }
    }

    /* batch Related Functions Ends here */
    /* Degree Starts Here */

    public function actionGetdegreevalue() {
        $deg_name = Yii::$app->request->post('deg_name');
        $deg_val = Degree::find()->where(['degree_code' => $deg_name])->all();
        if (count($deg_val) > 0) {
            $table = '';
            $s = 1;
            $table .= '<table id="checkAllFeat" class="table table-striped" align="center" border=1>     
                <thead id="t_head">                                                                                                               
                  <th> S.NO </th>                                                                                                                
                  <th>' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . ' Name' . '</th>  
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . ' Description' . ' </th>
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . ' Type' . ' </th>
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . ' Total Year' . ' </th>
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . ' Total Semester' . ' </th>                                                                                                   
                </thead><tbody>';
            foreach ($deg_val as $deg_val1) {
                $table .= "<tr><td>" . $s . "</td><td>" . $deg_val1['degree_code'] . "</td><td>" . $deg_val1['degree_name'] . "</td><td>" . $deg_val1['degree_type'] . "</td><td>" . $deg_val1['degree_total_years'] . "</td><td>" . $deg_val1['degree_total_semesters'] . "</td>";
                $s++;
            }
            $table .= "</tbody></table>";
            return $table;
        } else {
            return 0;
        }
    }

    /* Degree Ends Here */
    /* Programme Starts Here */

    public function actionGetprogrammevalue() {
        $prgm_name = Yii::$app->request->post('prgm_name');
        $prgm_val = Programme::find()->where(['programme_code' => $prgm_name])->all();
        if (count($prgm_val) > 0) {
            $table = '';
            $s = 1;
            $table .= '<table id="checkAllFeat" class="table table-striped" align="right" border=1>     
                <thead id="t_head">                                                                                                               
                  <th> S.NO </th>                                                                                                                
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . ' Name' . ' </th>  
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . ' Description' . ' </th>
                </thead><tbody>';
            foreach ($prgm_val as $prgm_val1) {
                $table .= "<tr><td>" . $s . "</td><td>" . $prgm_val1['programme_code'] . "</td><td>" . $prgm_val1['programme_name'] . "</td>";
                $s++;
            }
            $table .= "</tbody></table>";
            return $table;
        } else {
            return 0;
        }
    }

    /* Programme Ends Here */
    /* Subjects Starts here */

    public function actionGetdegpgmdetails() {
        $global_batch_id = Yii::$app->request->post('global_batch_id');
        $query = "SELECT a.coe_bat_deg_reg_id,concat(b.degree_code, ' ' , c.programme_code) as degree_name FROM coe_bat_deg_reg as a  JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id  JOIN coe_programme c ON c.coe_programme_id = a.coe_programme_id and a.coe_batch_id='" . $global_batch_id . "' order by a.coe_bat_deg_reg_id";
        $degreeInfo = Yii::$app->db->createCommand($query)->queryAll();
        $stu_dropdown = "";
        $stu_dropdown = "<option value='' > --- Select " . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . " ---</option>";
        foreach ($degreeInfo as $key => $value) {
            $stu_dropdown .= "<option value='" . $value['coe_bat_deg_reg_id'] . "' > " . $value['degree_name'] . "</option>";
        }
        return Json::encode($stu_dropdown);
    }

    public function actionGetmigratedetails() {
        $batch = Yii::$app->request->post('batch');
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $sem = Yii::$app->request->post('sem');
        $sub_map_val = SubjectsMapping::find()->where(['batch_mapping_id' => $batch_map_id, 'migration_status' => 'NO'])->all();
        $bat_map_val = CoeBatDegReg::find()->where(['coe_bat_deg_reg_id' => $batch_map_id])->one();
        $semester_name = ConfigUtilities::getSemesterName($bat_map_val['coe_bat_deg_reg_id']);
        /*foreach ($bat_map_val as $bat) 
        {*/
            $deg_id = $bat_map_val['coe_degree_id'];
            $pgm_id = $bat_map_val['coe_programme_id'];
        //}
        if (count($sub_map_val) == 0 || empty($sub_map_val)) 
        {
            return $table = 0;
        }
        $table = '';
        $sn = 1;
        $table .= '<table id="checkAllFeat" class="table table-striped" align="right" border=1>     
                <thead id="t_head">                                                                                                               
                  <th> S.NO </th>     
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Code' . ' </th>                                                                                                             
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Name' . ' </th>
                  <th> ' . $semester_name . ' </th>
                  <th> CIA Min </th>
                  <th> CIA Max </th>
                  <th> ESE Min </th>
                  <th> ESE Max </th>
                  <th> Total Min Pass </th>
                  <th> Credit Points </th>
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Fee' . ' </th>
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MIGRATE_STATUS) . ' </th>
                </thead><tbody>';
        foreach ($sub_map_val as $sub) {
            $sub_id = $sub['subject_id'];

            $sub_val = Yii::$app->db->createCommand('SELECT B.*,A.semester FROM coe_subjects_mapping as A JOIN coe_subjects as B ON B.coe_subjects_id=A.subject_id WHERE A.subject_id="'.$sub_id.'" AND A.semester="'.$sem.'" group by B.subject_code')->queryAll();

            foreach ($sub_val as $sub1) {
                $table .= "<tr>" .
                        "<td><input type='hidden' name='sn' value=" . $sn . " />" . $sn . "</td> " .
                        "<td><input type='hidden' name=sub_code" . $sn . " value='" . $sub1['subject_code'] . "' >" . $sub1['subject_code'] . "</td>" .
                        "<td><input type='hidden' name=sub_name" . $sn . " value='" . $sub1['subject_name'] . "' >" . $sub1['subject_name'] . "</td>" .
                        "<td><input type='hidden' name=sem value='" . $sub1['semester'] . "' size='2px' >" . $sub1['semester'] . "</td>" .
                        "<td><input type='hidden' name=cia_min" . $sn . " value='" . $sub1['CIA_min'] . "' >" . $sub1['CIA_min'] . "</td>" .
                        "<td><input type='hidden' name=cia_max" . $sn . " value='" . $sub1['CIA_max'] . "' >" . $sub1['CIA_max'] . "</td>" .
                        "<td><input type='hidden' name=ese_min" . $sn . " value='" . $sub1['ESE_min'] . "' >" . $sub1['ESE_min'] . "</td>" .
                        "<td><input type='hidden' name=ese_max" . $sn . " value='" . $sub1['ESE_max'] . "' >" . $sub1['ESE_max'] . "</td>" .
                        "<td><input type='hidden' name=min_pass" . $sn . " value='" . $sub1['total_minimum_pass'] . "'>" . $sub1['total_minimum_pass'] . "</td>" .
                        "<td><input type='hidden' name=credit" . $sn . " value='" . $sub1['credit_points'] . "'>" . $sub1['credit_points'] . "</td>" .
                        "<td><input type='hidden' name=sub_fee" . $sn . " value='" . $sub1['subject_fee'] . "'>" . $sub1['subject_fee'] . "</td>" .
                        "</td><td align='center'><input type='checkbox' name=mig" . $sn . " value='YES' checked></td>";
                $sn++;
            }
        }
        $table .= "</tbody></table>";
        return $table;
    }

    public function actionGetmigbatch() {
        $global_batch_id = Yii::$app->request->post('global_batch_id');
        $batch = Batch::find()->where(['coe_batch_id' => $global_batch_id])->one();
        $check_batch = Yii::$app->db->createCommand("select * from coe_batch where batch_name >'" . $batch['batch_name'] . "'")->queryAll();
        $stu_dropdown = "";
        $stu_dropdown = "<option value='' > --- Select " . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MIGRATE_STATUS) . " ---</option>";
        foreach ($check_batch as $key => $value) {
            $stu_dropdown .= "<option value='" . $value['coe_batch_id'] . "' > " . $value['batch_name'] . "</option>";
        }
        return Json::encode($stu_dropdown);
    }

    public function actionGetmigratedvalue() {
        $year = Yii::$app->request->post('year');
        $batch = Batch::find()->where(['batch_name' => $year])->one();
        $sub = SubjectsMapping::find()->where(['batch_mapping_id' => $prgm_name])->all();
        if (count($prgm_val) > 0) {
            $table = '';
            $s = 1;
            $table .= '<table id="checkAllFeat" class="table table-striped" align="right" border=1>     
                <thead id="t_head">                                                                                                               
                  <th> S.NO </th>                                                                                                                
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . ' Name' . ' </th>  
                  <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . ' Description' . ' </th>
                </thead><tbody>';
            foreach ($prgm_val as $prgm_val1) {
                $table .= "<tr><td>" . $s . "</td><td>" . $prgm_val1['programme_code'] . "</td><td>" . $prgm_val1['programme_name'] . "</td>";
                $s++;
            }
            $table .= "</tbody></table>";
            return $table;
        } else {
            return 0;
        }
    }

// Migrate Ends Here 

    /* Nominal Starts Here */
    public function actionGetviewnominal() 
    {
        if (isset($_POST['semester'])) {
            $batch_id = $_POST['batch'];
            $coe_bat_deg_reg_id = $_POST['programme'];
            //$section = "All";
            $semester = $_POST['semester'];
            
            $add_in_the_query = 'JOIN coe_category_type as c ON c.coe_category_type_id=b.status_category_type_id JOIN coe_categories as D ON D.coe_category_id = c.category_id  WHERE c.category_type not like "%Detain%" AND c.category_type not like "%Discontinued%" and ';
            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
            $section = '';

            $student_list = Yii::$app->db->createCommand("select distinct a.register_number as register_number,a.coe_student_id from coe_student a,coe_student_mapping b " . $add_in_the_query . " a.coe_student_id=b.student_rel_id " . $section . " and b.course_batch_mapping_id='" . $coe_bat_deg_reg_id . "' and a.student_status='Active' and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') order by a.register_number")->queryAll();

             $common_list = Yii::$app->db->createCommand("select a.subject_code from coe_subjects a,coe_subjects_mapping b,coe_category_type c where c.coe_category_type_id=b.subject_type_id and c.category_type!='Elective' and a.coe_subjects_id=b.subject_id and b.semester = '" . $semester . "' and b.batch_mapping_id='" . $coe_bat_deg_reg_id . "' group by a.subject_code")->queryAll();

            $man_common_list = Yii::$app->db->createCommand("select a.subject_code from coe_mandatory_subjects as a where a.semester = '" . $semester . "' and a.batch_mapping_id='" . $coe_bat_deg_reg_id . "' group by a.subject_code")->queryAll();
            if(!empty($man_common_list))
            {
                $common_list = array_merge($common_list,$man_common_list);
            }

            $elective_list = Yii::$app->db->createCommand("select a.subject_code from coe_subjects a,coe_subjects_mapping b,coe_category_type c where c.coe_category_type_id=b.subject_type_id and c.category_type='Elective' and a.coe_subjects_id=b.subject_id and b.batch_mapping_id='" . $coe_bat_deg_reg_id . "' group by a.subject_code")->queryAll();

            $exist_nominal = Yii::$app->db->createCommand("select a.coe_student_id,course_batch_mapping_id,section_name,semester,coe_subjects_id from coe_nominal as a JOIN coe_student as B ON B.coe_student_id = a.coe_student_id where course_batch_mapping_id = '" . $coe_bat_deg_reg_id . "' " . $section . " and semester='" . $semester . "' and B.student_status='Active' order by B.register_number")->queryAll();
            $check_create_exam = Yii::$app->db->createCommand("select batch_mapping_id from coe_subjects a,coe_subjects_mapping b,coe_exam_timetable c where a.coe_subjects_id=b.subject_id and b.coe_subjects_mapping_id=c.subject_mapping_id and b.batch_mapping_id='" . $coe_bat_deg_reg_id . "' and b.semester='" . $semester . "'")->queryAll();

            $semester_name = ConfigUtilities::getSemesterName($coe_bat_deg_reg_id);
            if (count($check_create_exam) > 0) {
                $nominal_table = '';
                $sn = 1; 
                $nominal_table .= '<thead id="t_head"><th> S.No </th><th> Register Number </th><th> ' . $semester_name . ' </th>';
                for ($c = 1; $c <= count($common_list); $c++) 
                {
                    $nominal_table .= '<th>Paper ' . $c . '</th>';                    
                }

                if (isset($elective_list) && !empty($elective_list)) 
                {
                    $nominal_table .= '<th>Elective 1</th>';
                    $nominal_table .= '<th>Elective 2</th>';
                    $nominal_table .= '<th>Elective 3</th>';
                    $nominal_table .= '<th>Elective 4</th>';
                }
                $nominal_table .= '</thead><tbody>';
                foreach ($student_list as $student) {
                    $student_map_id = Yii::$app->db->createCommand("select coe_student_mapping_id from coe_student_mapping where student_rel_id='" . $student['coe_student_id'] . "'")->queryScalar();
                    $mandatory_fail_list = Yii::$app->db->createCommand("select CONCAT(a.subject_code,'-',b.sub_cat_code) as subject_code from coe_mandatory_subjects as a JOIN coe_mandatory_subcat_subjects as b ON b.man_subject_id=a.coe_mandatory_subjects_id JOIN coe_mandatory_stu_marks as C ON C.subject_map_id=b.coe_mandatory_subcat_subjects_id where b.batch_map_id='" . $coe_bat_deg_reg_id . "' and C.student_map_id='".$student['coe_student_id']."' group by a.subject_code")->queryAll();

                    $failed_subjects = Yii::$app->db->createCommand("select distinct(c.subject_code),b.semester from coe_mark_entry_master a,coe_subjects_mapping b,coe_subjects c where a.subject_map_id=b.coe_subjects_mapping_id and b.subject_id=c.coe_subjects_id and a.student_map_id='" . $student_map_id . "' and a.year_of_passing='' and subject_map_id NOT IN (SELECT subject_map_id FROM coe_mark_entry_master WHERE student_map_id='" . $student_map_id . "' and result like '%Pass%' ) group by c.subject_code")->queryAll();

                    if (count($failed_subjects) > 0) {
                        for ($i = 1; $i < $semester; $i++) {
                            $print_number = 1;
                            $reg = '';
                            $dont_repeat = '';
                            foreach ($failed_subjects as $fail_sub) {
                                if ($fail_sub['semester'] == $i) {
                                    $check_sem_value = 1;
                                    if (isset($check_sem_value) && $check_sem_value != 0) {
                                        $check_sem_value = 0;
                                        if ($reg != $student['register_number']) {
                                            $reg = $student['register_number'];
                                            $hide_data = 5;
                                            if ($dont_repeat == '') {
                                                $nominal_table .= '<tr><td> ' . $sn . ' </td><td>' . $student['register_number'] . '</td><td>' . $i . '</td>';
                                                $dont_repeat = 'has some value';
                                            } else {

                                                $nominal_table .= '<tr><td> &nbsp; </td><td> &nbsp; </td><td>' . $i . '</td>';
                                            }
                                        }
                                        $nominal_table .= '<td>' . $fail_sub['subject_code'] . '</td>';
                                    }
                                }
                            }
                            $nominal_table .= '</tr>';
                            //$sn++;
                        }
                    } //If Failed Subjects Count
                    if (isset($hide_data) && $hide_data == 5 && $dont_repeat != '' && $reg == $student['register_number']) {
                        $nominal_table .= '<tr><td>&nbsp;</td><td>&nbsp;</td><td>' . $semester . '</td>';
                    } else {
                        $nominal_table .= '<tr><td>' . $sn . '</td><td>' . $student['register_number'] . '</td><td>' . $semester . '</td>';
                    }
                    foreach ($common_list as $common) {

                        $nominal_table .= '<td>' . $common['subject_code'] . '</td>';
                    }

                    $stu_id = Yii::$app->db->createCommand("select coe_student_id from coe_student where register_number='" . $student['register_number'] . "' and student_status='Active'")->queryScalar();
                    $elective_cat = Categorytype::find()->where(['description'=>'Elective'])->one();
                    $ele_query = "select a.subject_code from coe_subjects as a JOIN coe_nominal b ON b.coe_subjects_id=a.coe_subjects_id JOIN coe_student c ON c.coe_student_id=b.coe_student_id JOIN coe_subjects_mapping as e ON e.subject_id=a.coe_subjects_id and e.batch_mapping_id=b.course_batch_mapping_id where b.coe_student_id='".$stu_id."' and e.subject_type_id='".$elective_cat->coe_category_type_id."' and b.semester='".$semester."' and c.student_status='Active' group by a.subject_code";
                    $stu_subject_code = Yii::$app->db->createCommand($ele_query)->queryAll();

                    $mandatory_list = Yii::$app->db->createCommand("select CONCAT(a.subject_code,'-',b.sub_cat_code) as subject_code from coe_mandatory_subjects as a JOIN coe_mandatory_subcat_subjects as b ON b.man_subject_id=a.coe_mandatory_subjects_id JOIN coe_mandatory_stu_marks as C ON C.subject_map_id=b.coe_mandatory_subcat_subjects_id where b.batch_map_id='" . $coe_bat_deg_reg_id . "' and C.student_map_id='".$student['coe_student_id']."' group by a.subject_code")->queryAll();
                    if(!empty($mandatory_list))
                    {
                        $stu_subject_code = array_merge($stu_subject_code,$mandatory_list);
                    }
                    if (!empty($stu_subject_code)) {
                        foreach ($stu_subject_code as $stu_subject) {
                            //$nominal_table.='<td><select><option value='.$stu_subject['subject_code'].'>'.$stu_subject['subject_code'].'</option></select></td>';
                            $nominal_table .= '<td>' . $stu_subject['subject_code'] . '</td>';
                        }
                    }
                    $nominal_table .= '</tr>';
                    $sn++;
                } // Foreeach students list completed
                $nominal_table .= '</tbody>';
                //$nominal_table.='</table></div> </div> ';
                $data = ['table' => $nominal_table, 'result' => 1];
                return Json::encode($data);
            } // If Exam Created
            else if (count($exist_nominal) > 0) 
            {
              
                $nominal_table = '';
                $sn = 1;
                $nominal_table .= '<thead id="t_head"><th> S.No </th>
                  <th> Register Number </th>
                  <th> ' . $semester_name . ' </th>';
                for ($c = 1; $c <= count($common_list); $c++) 
                {
                    if($c==count($common_list) && !empty($man_common_list))
                    {
                        $nominal_table .= '<th>Mandatory</th>';
                    }
                    else
                    {
                        $nominal_table .= '<th>Paper ' . $c . '</th>';
                    }
                    
                }

                if (isset($elective_list) && !empty($elective_list)) 
                {
                    $sn_num = 1;
                    for ($abc=0; $abc <4 ; $abc++) 
                    { 
                        $nominal_table .= '<th>Elective '.$sn_num.'</th>';
                        $sn_num = $sn_num+1;
                    }
                    
                }
                $nominal_table .= '</thead><tbody>';
                $prev_reg = '';
                $serialno = 1;
                $print_number = 0;
                foreach ($student_list as $exist) 
                {
                    $stu_reg_no = Yii::$app->db->createCommand("select register_number from coe_student where coe_student_id='" . $exist['coe_student_id'] . "' and student_status='Active'")->queryScalar();
                    if ($prev_reg != $stu_reg_no) 
                    {
                        $prev_reg = $stu_reg_no;
                        $student_map_id = Yii::$app->db->createCommand("select coe_student_mapping_id from coe_student_mapping where student_rel_id='" . $exist['coe_student_id'] . "'")->queryScalar();

                        $failed_subjects_st = Yii::$app->db->createCommand("select distinct c.subject_code as subject_code,b.semester from coe_mark_entry_master a,coe_subjects_mapping b,coe_subjects c where a.subject_map_id=b.coe_subjects_mapping_id and b.subject_id=c.coe_subjects_id and a.student_map_id='" . $student_map_id . "' and a.year_of_passing='' group by subject_code")->queryAll();
                        $print_status = 0;  
                        if (!empty($failed_subjects_st)) {
                            for ($i = 1; $i < $semester; $i++) 
                            {
                                
                                $reg = '';
                                foreach ($failed_subjects_st as $fail_sub) 
                                {  
                                    if ($fail_sub['semester'] == $i) 
                                    {
                                        $check_sem_value = 1;
                                        if (isset($check_sem_value) && $check_sem_value != 0) 
                                        {
                                            $print_number = 1;
                                            $print_status++;
                                            $check_sem_value = 0;                         
                                            if ($prev_reg!=$reg) 
                                            {
                                                $reg = $prev_reg;

                                                $nominal_table .= '<tr><td> ' . $sn . ' </td><td><input type="hidden" value=' . $prev_reg . '>' . $prev_reg . '</td><td>' . $i . '</td>';
                                            }
                                            $nominal_table .= '<td>' . $fail_sub['subject_code'] . '</td>';
                                        }
                                    }
                                }
                                if($print_status!=0)
                                {
                                    $nominal_table .= '</tr>';
                                }
                                
                                //$sn++;
                            } // For Loop Ends 
                            
                        } // If Not Empty of Failed Subjects
                        
                        if (isset($print_number) && $print_number != 0) {
                            $print_number = 0;
                            $nominal_table .= '<tr><td> &nbsp; </td><td>&nbsp;</td>';
                            //$nominal_table.='<tr><td> &nbsp; </td><td><input type="hidden" name=reg[] value='.$prev_reg.'>'.$prev_reg.'</td>';
                        } else {
                            $nominal_table .= '<tr><td>' . $sn . '</td><td><input type="hidden" name=reg[] value=' . $prev_reg . '>' . $prev_reg . '</td>';
                        }

                        $nominal_table .= '<td>' . $semester . '</td>';

                        foreach ($common_list as $common) {
                            $nominal_table .= '<td>' . $common['subject_code'] . '</td>';
                        }

                        $stu_id = Yii::$app->db->createCommand("select coe_student_id from coe_student where register_number='" . $prev_reg . "' and student_status='Active'")->queryScalar();
                        $stu_subject_code = Yii::$app->db->createCommand("select distinct a.subject_code as subject_code from coe_subjects a,coe_nominal b,coe_student c where c.coe_student_id=b.coe_student_id and a.coe_subjects_id=b.coe_subjects_id and b.coe_student_id='" . $stu_id . "' and b.semester='" . $semester . "' and c.student_status='Active' group by subject_code")->queryAll();

                        if (!empty($stu_subject_code)) {

                            $selected_value = '';
                            $old_sub_print = '';
                            for ($i = 1; $i <= 4; $i++) {

                                $nominal_table .= "<td><select name=elective" . $serialno . "_" . $i . " id=elective" . $serialno . "_" . $i . " onchange='getThisval(this.id,this.value)'>";

                                foreach ($elective_list as $elective) {
                                    
                                    for ($n = 0; $n < count($stu_subject_code); $n++) {
                                        

                                        if ($i == 1 && $n == 0 && $old_sub_print != $stu_subject_code[$n]['subject_code']) {
                                            $old_sub_print = $stu_subject_code[$n]['subject_code'];
                                            $selected_value = $stu_subject_code[$n]['subject_code'];
                                            
                                            $nominal_table .= '<option value="' . $selected_value . '">' . $selected_value . '</option>';
                                            break;
                                        } else if ($i == 2 && $n == 1 && $old_sub_print != $stu_subject_code[$n]['subject_code']) {
                                            $old_sub_print = $stu_subject_code[$n]['subject_code'];
                                            $selected_value = $stu_subject_code[$n]['subject_code'];
                                            $nominal_table .= '<option value="' . $selected_value . '">' . $selected_value . '</option>';
                                            break;
                                        }
                                        else if ($i == 3 && $n == 2 && $old_sub_print != $stu_subject_code[$n]['subject_code']) {
                                            $old_sub_print = $stu_subject_code[$n]['subject_code'];
                                            $selected_value = $stu_subject_code[$n]['subject_code'];
                                            $nominal_table .= '<option value="' . $selected_value . '">' . $selected_value . '</option>';
                                            break;
                                        }
                                        else if ($i == 4 && $n == 3 && $old_sub_print != $stu_subject_code[$n]['subject_code']) {
                                            $old_sub_print = $stu_subject_code[$n]['subject_code'];
                                            $selected_value = $stu_subject_code[$n]['subject_code'];
                                            $nominal_table .= '<option value="' . $selected_value . '">' . $selected_value . '</option>';
                                            break;
                                        }
                                    }
                                    
                                    if ($elective['subject_code'] != $selected_value) {

                                        $nominal_table .= '<option value=' . $elective['subject_code'] . '>' . $elective['subject_code'] . '</option>';
                                    }
                                }
                                $nominal_table .= '</select></td>';
                            }
                           
                            $sn++;
                        } else {
                            if (isset($elective_list) && !empty($elective_list)) {
                                for ($i = 1; $i <= 2; $i++) {
                                    $nominal_table .= "<td>
                      <select name=elective" . $serialno . "_" . $i . " id=elective" . $serialno . "_" . $i . " onchange='getThisval(this.id,this.value)' >
                      <option value=''>Select</option>";
                                    foreach ($elective_list as $elective) {
                                        $nominal_table .= '<option value=' . $elective['subject_code'] . '>' . $elective['subject_code'] . '</option>';
                                    }
                                }
                                $nominal_table .= '</select></td></tr>';
                                $sn++;
                            }
                        }
                    } //if($prev_reg!=$stu_reg_no)
                    $serialno++;
                } //foreach $student_list
                $nominal_table .= '</tr></tbody>';
                // $nomianl_table.='</table></div></div>';
                $data = ['table' => $nominal_table, 'result' => 1];
                return Json::encode($data);
            } //if(count($exist_nominal)>0)
            else {
                if (count($student_list) > 0 && count($common_list) > 0) {
                    $table = '';
                    $serialno = 1;
                    // $table.= '<div id="table-wrapper"> <div id="table-scroll"> 
                    //<table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  id="student_nominal_edit" class="table scroll table-bordered table-responsive bulk_edit_table table-hover" align="right">     
                    $table .= '<thead id="t_head">                                                                                                               
                    <th> S.NO </th>
                    <th> Register Number </th>
                    <th> ' . $semester_name . ' </th>';
                    for ($c = 1; $c <= count($common_list); $c++) 
                    {
                        if($c==count($common_list) && !empty($man_common_list))
                        {
                            $table .= '<th>Mandatory</th>';
                        }
                        else
                        {
                            $table .= '<th>Paper ' . $c . '</th>';
                        }
                    }
                    if (isset($elective_list) && !empty($elective_list)) {
                        $table .= '<th>Elective 1</th>';
                        $table .= '<th>Elective 2</th>';
                    }
                    $table .= '</thead><tbody>';
                    foreach ($student_list as $student) {
                        $table .= '<tr><td>' . $serialno . '</td><td><input type="hidden" name=reg[] value=' . $student['register_number'] . '>' . $student['register_number'] . '</td><td>' . $semester . '</td>';
                        foreach ($common_list as $common) {
                            $table .= '<td><input type="hidden" name=common value=' . $common['subject_code'] . '>' . $common['subject_code'] . '</td>';
                        }
                        if (isset($elective_list) && !empty($elective_list)) {
                            for ($i = 1; $i <= 2; $i++) {
                                $table .= "<td>
                  <select name=elective" . $serialno . "_" . $i . " id=elective" . $serialno . "_" . $i . " onchange='getThisval(this.id,this.value)' >
                  <option value=''>Select</option>";
                                foreach ($elective_list as $elective) {
                                    $table .= '<option value=' . $elective['subject_code'] . '>' . $elective['subject_code'] . '</option>';
                                }
                            }
                        }
                        $table .= '</select></td></tr>';
                        $serialno++;
                    }
                    $table .= '</tbody>';
                    // $table.='</table></div></div>';         
                    $data = ['table' => $table, 'result' => 0];

                    return Json::encode($data);
                }
            }
        }
    }

    /* Nominal Ends Here */


    /* Galley Starts Here */

    public function actionGetmethod() {
        $category_type_list = Yii::$app->db->createCommand("select coe_category_type_id, CONCAT(a.category_type,'-',(a.description),' Seats ' ) as category_type from coe_category_type a,coe_categories b where b.category_name like '%" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_HALLTYPE) . "%' and b.coe_category_id=a.category_id")->queryAll();
        return Json::encode($category_type_list);
    }

    public function actionGetexamdate() {
        $month = Yii::$app->request->post('month');
        $year = Yii::$app->request->post('year');
        $query = new Query();
        $query->select("distinct(a.exam_date)")
                ->from('coe_exam_timetable a')
                ->join('JOIN', 'coe_category_type b', 'a.exam_month=b.coe_category_type_id')
                ->where(['b.description' => $month, 'exam_year' => $year]);
        $exam_date = $query->createCommand()->queryAll();
        return Json::encode($exam_date);
    }

    public function actionGetsession() {
        
    }

    public function actionGethall() {
        $halls = Yii::$app->db->createCommand("select hall_name from coe_hall_master where hall_type_id='" . $_POST['method'] . "'")->queryAll();
        return Json::encode($halls);
    }

    public function actionGetseatcount() {
        $total_students = 0;
        $date = date("Y-m-d", strtotime(Yii::$app->request->post('date')));
        $exam_month = Yii::$app->request->post('exam_month');
        $exam_year = Yii::$app->request->post('exam_year');
        $hall_count = Yii::$app->db->createCommand("select count(a.hall_name) as hall_name_count,b.description from coe_hall_master a,coe_category_type b where a.hall_type_id=b.coe_category_type_id and b.coe_category_type_id='" . Yii::$app->request->post('method') . "' group by description")->queryAll();
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        if (count($hall_count) > 0) {
            foreach ($hall_count as $key => $value) {
                $seat_value = $value['description'];
                $hall_name_count = $value['hall_name_count'];
            }
            $query_1 = new Query();
            $sub_name_check = $query_1->select("a.subject_mapping_id,a.exam_type,b.description")
                            ->from('coe_exam_timetable a')
                            ->join('JOIN', 'coe_category_type b', 'a.exam_type=b.coe_category_type_id')
                            ->where(['exam_date' => $date,'exam_month'=>$exam_month,'exam_year'=>$exam_year, 'exam_session' => Yii::$app->request->post('session')])->createCommand()->queryAll();
            
            foreach ($sub_name_check as $exam_type) {
                
                if ($exam_type['description'] == "Arrear") {
                    $query_a = new Query();
                $arrear_students = $query_a->select("x.student_map_id")
               ->from('coe_mark_entry_master a')
              ->join('JOIN', 'coe_student_mapping b', 'a.student_map_id=b.coe_student_mapping_id')
              ->join('JOIN', 'coe_student d', 'd.coe_student_id=b.student_rel_id')
               ->join('JOIN','coe_fees_paid x','x.student_map_id=a.student_map_id and x.subject_map_id=a.subject_map_id')
              ->where(['a.subject_map_id' => $exam_type['subject_mapping_id'], 'a.year_of_passing' => '', 'd.student_status' => 'Active','x.month'=>$exam_month,'x.year'=>$exam_year])
             ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
             ->andWhere(['<>', 'x.status','NO'])
               ->groupBy('a.student_map_id')->createCommand()->queryAll();
                    $count_of_stu = 0;
                    foreach ($arrear_students as $check_pass) {
                        $check = Yii::$app->db->createCommand('select * from coe_mark_entry_master where subject_map_id="' . $exam_type['subject_mapping_id'] . '" AND 
                student_map_id="' . $check_pass["student_map_id"] . '" and result like "%pass%"')->queryAll();
                        if (empty($check)) {
                            $count_of_stu += 1;
                        }
                    }
                    $total_students += $count_of_stu;
                } else if ($exam_type['description'] != "Arrear") {
                    $subject_type = Yii::$app->db->createCommand("select b.description from coe_subjects_mapping a,coe_category_type b where a.subject_type_id=b.coe_category_type_id and a.coe_subjects_mapping_id='" . $exam_type['subject_mapping_id'] . "'")->queryScalar();
                    if ($subject_type == "Elective")

                    {
                        $getSemester = SubjectsMapping::findOne($exam_type['subject_mapping_id']);

                        $stu_count_elective = Yii::$app->db->createCommand("select count(a.coe_student_id) from coe_nominal A JOIN coe_student as B ON B.coe_student_id=A.coe_student_id JOIN coe_subjects as C ON C.coe_subjects_id=A.coe_subjects_id JOIN coe_student_mapping as D ON D.student_rel_id=B.coe_student_id JOIN coe_subjects_mapping as E ON E.subject_id=C.coe_subjects_id and E.batch_mapping_id=A.course_batch_mapping_id and A.course_batch_mapping_id=D.course_batch_mapping_id WHERE E.coe_subjects_mapping_id='".$exam_type['subject_mapping_id']."' and E.semester='".$getSemester->semester."' and A.semester='".$getSemester->semester."' and B.student_status='Active' and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') ")->queryScalar();

                        
                        $total_students += $stu_count_elective;
                    } else if ($subject_type != "Elective") {
                        $stu_count_common = Yii::$app->db->createCommand("select count(student_rel_id) from coe_student_mapping a,coe_subjects_mapping b,coe_student c where c.coe_student_id =a.student_rel_id and a.course_batch_mapping_id=b.batch_mapping_id and b.coe_subjects_mapping_id='" . $exam_type['subject_mapping_id'] . "' and c.student_status='Active' and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') ")->queryScalar();
                        $total_students += $stu_count_common;
                    }
                } //else if not arrear
                else {
                    $showMessage = ["message" => 'No data Found'];
                }
            }//foreach sub_name_check
            
            $available_hall = ceil($total_students / $seat_value);
            
            if ($available_hall <= $hall_name_count) {
                $showMessage = ['available_hall' => $available_hall, 'message' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT) . " Count : " . $total_students . " \n Neccessary Halls : " . $available_hall];
            } else {
                $showMessage = ['available_hall' => $available_hall, "message" => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT) . " Count : " . $total_students . " \n Neccessary Halls : " . $available_hall];
            }
            return Json::encode($showMessage);
        } else {
            $showMessage = 1;
            return Json::encode($showMessage);
            // Yii::$app->ShowFlashMessages->setMsg('Error','Halls not available!! Please import Halls!!!');
            // return $this->redirect(['hall-allocate/create',]); 
        }
    }

    public function actionGetsubjectseatcount() {
        $hall_count = Yii::$app->db->createCommand("select count(a.hall_name) as hall_name_count,b.description from coe_hall_master a,coe_category_type b where a.hall_type_id=b.coe_category_type_id and b.coe_category_type_id='" . $_POST['method'] . "' group by b.description")->queryAll();
        $exam_month = Yii::$app->request->post('exam_month');
        $exam_year = Yii::$app->request->post('exam_year');
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        if (count($hall_count) > 0) {
            foreach ($hall_count as $key => $value) {
                $seat_value = $value['description'];
                $hall_name_count = $value['hall_name_count'];
            }
           
            $sub_code_galley = Yii::$app->request->post('sub_code_galley');
            $total_sub_code = explode(",", $sub_code_galley);
            $total_student = 0;

            $date = date("Y-m-d", strtotime(Yii::$app->request->post('date')));
            for ($i = 0; $i < count($total_sub_code); $i++) 
            {
                $query0 = new Query();
                $query0->select("b.coe_subjects_mapping_id,c.description")
                        ->from('coe_subjects a')
                        ->join('JOIN', 'coe_subjects_mapping b', 'a.coe_subjects_id=b.subject_id')
                        ->join('JOIN', 'coe_exam_timetable d', 'b.coe_subjects_mapping_id=d.subject_mapping_id')
                        ->join('JOIN', 'coe_category_type c', 'd.exam_type=c.coe_category_type_id')
                        ->where(['subject_code' => $total_sub_code[$i], 'd.exam_date' => $date, 'd.exam_session' => $_POST['session'],'d.exam_month'=>$exam_month,'d.exam_year'=>$exam_year]);
                $subject_mapping_id = $query0->createCommand()->queryAll();
                
                foreach ($subject_mapping_id as $exam_type) 
                {                   
                    if ($exam_type['description'] != "Arrear") 
                    {
                        $query1 = new Query();
                        $query1->select("b.description")
                                ->from('coe_subjects_mapping a')
                                ->join('JOIN', 'coe_category_type b', 'a.subject_type_id=b.coe_category_type_id')
                                ->where(['a.coe_subjects_mapping_id' => $exam_type['coe_subjects_mapping_id']]);
                        $subject_type = $query1->createCommand()->queryScalar();
                        if ($subject_type != "Elective") {
                            $stu_count_common = Yii::$app->db->createCommand("select count(student_rel_id) from coe_student_mapping a,coe_subjects_mapping b,coe_student c where c.coe_student_id=a.student_rel_id and a.course_batch_mapping_id=b.batch_mapping_id and b.coe_subjects_mapping_id='" . $exam_type['coe_subjects_mapping_id'] . "' and c.student_status='Active' and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') ")->queryScalar();
                            $total_student += $stu_count_common;
                        } 
                        else if ($subject_type == "Elective") 
                        {                            
                            $getSemester = SubjectsMapping::findOne($exam_type['coe_subjects_mapping_id']);
                            $stu_count_elective = Yii::$app->db->createCommand("select count(b.coe_student_id) from coe_nominal as a JOIN coe_subjects_mapping as c ON c.subject_id=a.coe_subjects_id JOIN coe_student as b ON b.coe_student_id=a.coe_student_id JOIN coe_student_mapping as D ON D.student_rel_id=b.coe_student_id and a.course_batch_mapping_id=D.course_batch_mapping_id and D.course_batch_mapping_id=c.batch_mapping_id where  c.coe_subjects_mapping_id='" . $exam_type['coe_subjects_mapping_id'] . "' and c.semester='".$getSemester->semester."' and a.semester='".$getSemester->semester."' and b.student_status='Active' and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') ")->queryScalar();
                            $total_student += $stu_count_elective;
                        }
                    } 
                    else if ($exam_type['description'] == "Arrear") 
                    {
                        $query_a = new Query();
                        $arrear_students = $query_a->select("x.student_map_id")
                        ->from('coe_mark_entry_master a')
                        ->join('JOIN', 'coe_student_mapping b', 'a.student_map_id=b.coe_student_mapping_id')
                        ->join('JOIN', 'coe_student d', 'd.coe_student_id=b.student_rel_id')
                         ->join('JOIN','coe_fees_paid x','x.student_map_id=a.student_map_id and x.subject_map_id=a.subject_map_id')
                        ->where(['a.subject_map_id' => $exam_type['coe_subjects_mapping_id'],'x.subject_map_id' => $exam_type['coe_subjects_mapping_id'], 'a.year_of_passing' => '', 'd.student_status' => 'Active','x.year'=>$exam_year,'x.month'=>$exam_month])
                        ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                        ->andWhere(['NOT LIKE', 'result', 'Pass'])
                         ->andWhere(['<>', 'x.status','NO'])
                        ->groupBy('a.student_map_id,a.subject_map_id')
                        ->createCommand()->queryAll();
                        $count_of_stu = 0;
                        foreach ($arrear_students as $check_pass) 
                        {
                            $check = Yii::$app->db->createCommand('select * from coe_mark_entry_master where subject_map_id="' . $exam_type['coe_subjects_mapping_id'] . '" AND 
                            student_map_id="' . $check_pass["student_map_id"] . '" and result like "%pass%"')->queryAll();
                            if (empty($check)) 
                            {
                                $count_of_stu += 1;
                            }
                        }

                        $total_student += $count_of_stu;
                    } else {
                        $showMessage = ["message" => 'No data Found'];
                    }
                }
            } //for loop

            $available_hall = ceil($total_student / $seat_value);
            // }
            //$available_hall = ceil($total_student/$seat_value);
            if ($available_hall <= $hall_name_count) {
                $data = ['stu_count' => $total_student, 'available_hall' => $available_hall];
                $showMessage = ['stu_count' => $total_student, 'available_hall' => $available_hall, "message" => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT) . " Count : " . $total_student . " \n Neccessary Halls : " . $available_hall];
            } else {
                $showMessage = ['stu_count' => $total_student, 'available_hall' => $available_hall, "message" => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT) . " Count : " . $total_student . "Not Enough Halls to Allocate.  Available Halls : " . $hall_name_count . "  REQUIRED HALLS " . $available_hall];
            }
            return Json::encode($showMessage);
        } else {
            Yii::$app->ShowFlashMessages->setMsg('Error', 'Halls not available!! Please import Halls!!!');
            return $this->redirect(['hall-allocate/create',]);
        }
    }

    public function actionGetsubcode() {
        $date = date("Y-m-d", strtotime(Yii::$app->request->post('date')));
        $subject = Yii::$app->db->createCommand("select distinct a.subject_code as subject_code from coe_subjects a,coe_subjects_mapping b,coe_exam_timetable c where a.coe_subjects_id=b.subject_id and b.coe_subjects_mapping_id=c.subject_mapping_id and c.exam_date='" . $date . "' and c.exam_session='" . $_POST['session'] . "' and c.exam_month='" . $_POST['exam_month'] . "' ")->queryAll();
        return Json::encode($subject);
    }

    public function actionGetqpexamdate() {
        $month = Yii::$app->request->post('month');
        $year = Yii::$app->request->post('year');
        $query = new Query();

        $exam_date = Yii::$app->db->createCommand("SELECT DISTINCT DATE_FORMAT(exam_date, '%d-%m-%Y') as exam_date FROM coe_exam_timetable as A WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "' ORDER BY exam_date")->queryAll();

        return Json::encode($exam_date);
    }

    public function actionGetqpsession() {
        $date = date("Y-m-d", strtotime(Yii::$app->request->post('date')));
        $query = new Query();
        $query->select("distinct(a.description),a.coe_category_type_id")
                ->from('coe_category_type a')
                ->join('JOIN', 'coe_exam_timetable b', 'a.coe_category_type_id=b.exam_session')
                ->where(['b.exam_date' => $date]);
        $exam_session = $query->createCommand()->queryAll();
        return Json::encode($exam_session);
    }

    /* Galley Ends Here */
    /* Exam start */

    public function actionGetmonth() {
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        //echo $batch_map_id;
        $bat_map_val = CoeBatDegReg::find()->where(['coe_bat_deg_reg_id' => $batch_map_id])->one();
        $deg_id = $bat_map_val['coe_degree_id'];
        $pgm_id = $bat_map_val['coe_programme_id'];
        $deg_name = Degree::find()->where(['coe_degree_id' => $deg_id])->one();
        $prgm_name = Programme::find()->where(['coe_programme_id' => $pgm_id])->one();
        $tot_yrs = $deg_name['degree_total_years'];
        $tot_sem = $deg_name['degree_total_semesters'];
        $sem = $tot_sem / $tot_yrs;
         $sem = empty($sem)?"0":$sem;
        if ($sem == 3) {
            $sem_type = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_TRISEM);
        } else {
            $sem_type = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_BISEM);
        }
        $config_list = Categories::find()->where(['category_name' => $sem_type])->one();
        $c_id = $config_list['coe_category_id'];
        $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
        $exam_dropdown = "";
        $exam_dropdown = "<option value='' > --- Select Month ---</option>";
        foreach ($config_list as $key => $value) {
            $exam_dropdown .= "<option value='" . $value['coe_category_type_id'] . "' > " . $value['category_type'] . "</option>";
        }
        return Json::encode($exam_dropdown);
    }

    public function actionGetsubjectcode() {
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $sem = Yii::$app->request->post('sem');
        $type = Yii::$app->request->post('type');
        $cat_mark_type = Categorytype::find()->where(['coe_category_type_id' => $type])->one();
        $query = new Query();

        if ($cat_mark_type->category_type != "Arrear") {
            $query = (new \yii\db\Query());
            $query->select("b.coe_subjects_mapping_id as sub_id,a.subject_code")
                    ->from('coe_subjects a')
                    ->join('JOIN', 'coe_subjects_mapping b', 'b.subject_id = a.coe_subjects_id')
                    ->where(['b.semester' => $sem, 'b.batch_mapping_id' => $batch_map_id])
                    ->orderBy('a.subject_code');
            $result = $query->createCommand()->queryAll();
        } else {//Arrear
            $result = Yii::$app->db->createCommand("select distinct A.coe_subjects_mapping_id as sub_id , C.subject_code from coe_subjects_mapping as A, coe_mark_entry_master as B,coe_subjects as C where A.subject_id=C.coe_subjects_id and A.batch_mapping_id='" . $batch_map_id . "' and A.coe_subjects_mapping_id=B.subject_map_id and year_of_passing='' and A.semester='" . $sem . "'")->queryAll();
        }
        return Json::encode($result);
    }

    public function actionGetsubjectname() 
    {
        $sub_id = Yii::$app->request->post('sub_id');
        $sub_name_id = SubjectsMapping::findOne($sub_id);
        $sub_name = Subjects::find()->where(['coe_subjects_id' => $sub_name_id->subject_id])->one();
        $sub_name = !empty($sub_name)?$sub_name:'NO';
        return Json::encode($sub_name);
    }

    // Mandatory Subjects Functions 
    /*public function actionGetmansubname() 
    {
        $sub_code = Yii::$app->request->post('sub_code');
        $batch_mapping_id = Yii::$app->request->post('batch_mapping_id');
        $sub_name = Yii::$app->db->createCommand('SELECT DISTINCT (sub_cat_code) FROM coe_mandatory_subcat_subjects WHERE man_subject_id="'.$sub_code.'" and batch_map_id="'.$batch_mapping_id.'"')->queryAll();
        $sub_info = !empty($sub_name) ? ['count'=>count($sub_name),'lenth'=>strlen(count($sub_name))]:['count'=>0,'lenth'=>0];
        return Json::encode($sub_info);
    }*/


    public function actionGetsubjectnameadd() 
    {
        $sub_id = Yii::$app->request->post('sub_id');
        $sub_name_id = Sub::findOne($sub_id);
$sub_name = CoeValueSubjects::find()->where(['coe_val_sub_id' => $sub_name_id->val_subject_id])->one();
        //print_r( $sub_id );exit;
        $sub_name = !empty($sub_name)?$sub_name:'NO';
        return Json::encode($sub_name);
    }

    public function actionGetmansubname() 
    {
        $sub_code = Yii::$app->request->post('sub_code');
        $batch_mapping_id = Yii::$app->request->post('batch_mapping_id');
        $getManDetails = MandatorySubjects::findOne($sub_code);
        $sub_name = Yii::$app->db->createCommand('SELECT DISTINCT (sub_cat_code) FROM coe_mandatory_subcat_subjects as A JOIN coe_mandatory_subjects as B ON B.coe_mandatory_subjects_id=A.man_subject_id WHERE subject_code="'.$getManDetails->subject_code.'"')->queryAll();
        $sub_info = !empty($sub_name) ? ['count'=>count($sub_name),'lenth'=>strlen(count($sub_name))]:['count'=>0,'lenth'=>0];
        return Json::encode($sub_info);
    }
    
    public function actionGetsubcatlist() {
        $sub_code = Yii::$app->request->post('sub_code');
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        //Work on Arrear Subjects

        $sub_name = Yii::$app->db->createCommand('SELECT coe_mandatory_subcat_subjects_id as sub_cat_id, sub_cat_code FROM coe_mandatory_subcat_subjects WHERE man_subject_id="'.$sub_code.'" and batch_map_id="'.$batch_map_id.'" ')->queryAll();
        $sub_info = !empty($sub_name) ? $sub_name:'NO';
        return Json::encode($sub_info);
    }
    public function actionGetmansubslist() {
        $batch_map_id = Yii::$app->request->post('batch_map_id');
       
        $sub_name = Yii::$app->db->createCommand('SELECT coe_mandatory_subjects_id as man_subject_id, subject_code FROM coe_mandatory_subjects WHERE batch_mapping_id="'.$batch_map_id.'" ')->queryAll();
        $sub_info = !empty($sub_name) ? $sub_name:'NO';
        return Json::encode($sub_info);
    }
    public function actionGetsubcatsubect() 
    {
        $programme_id = Yii::$app->request->post('programme_id');
        $coe_batch_id = Yii::$app->request->post('batch_id');        
        $semester = Yii::$app->request->post('semester');   

        $sub_name = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_subjects WHERE man_batch_id="'.$coe_batch_id.'" and batch_mapping_id="'.$programme_id.'" and semester="'.$semester.'"')->queryAll();
        if(!empty($sub_name))
        {
            $table = '';
            $table .= '<option value="" >---SELECT---</option>';
            foreach ($sub_name as  $value) 
            {
              $table .= '<option value="'.$value['coe_mandatory_subjects_id'].'" >'.$value['subject_code'].'</option>';
            }
        }
        $sub_info = !empty($sub_name) ? $table:'NO';
        return Json::encode($sub_info);
    }
    public function actionChecksubcats() {
        $sub_code = Yii::$app->request->post('sub_code');
        $coe_batch_id = Yii::$app->request->post('coe_batch_id');        
        $sub_name = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_subcat_subjects WHERE man_subject_id="'.$sub_code.'" and coe_batch_id="'.$coe_batch_id.'"')->queryAll();

        $sub_info = !empty($sub_name) ? ['no_data'=>$sub_name]:['no_data'=>'NO'];
        return Json::encode($sub_info);
    }
    public function actionGetsubcatinfo() {
        $sub_code = Yii::$app->request->post('sub_code');
        $mark_type = Yii::$app->request->post('mark_type');
        $year = Yii::$app->request->post('year');
        $exam_term = Yii::$app->request->post('exam_term');
        $month = Yii::$app->request->post('month');
        $sub_cat_code = Yii::$app->request->post('manSubcatId');
        $batch_map_id = Yii::$app->request->post('batch_map_id');
         $semester = Yii::$app->request->post('semester');
         $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
        $arrear = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Arrear%'")->queryScalar();
        $regulAr = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Regular%'")->queryScalar();
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        
        $sub_cat_id = MandatorySubcatSubjects::findOne($sub_cat_code);
        if($mark_type==$arrear && $sub_cat_id->is_additional=='YES')
        {
            Yii::$app->ShowFlashMessages->setMsg('Error',"OOPS No Arrear for Additional Credits");
            return $this->redirect(['mandatory-stu-marks/create']);
        }
        $query = new Query();
        $query->select(['subject_code','subject_name','ESE_max','ESE_min','CIA_max','CIA_min','sub_cat_name','sub_cat_code'])
        ->from('coe_mandatory_subjects as A')
        ->join('JOIN', 'coe_mandatory_subcat_subjects as B', 'B.man_subject_id=A.coe_mandatory_subjects_id')
        ->where(['coe_mandatory_subcat_subjects_id'=>$sub_cat_code,'semester'=>$semester]);
        $vals = $query->createCommand()->queryOne();
        $add_table = '';

        if(!empty($vals))
        {
           
            $query = new Query();
            $query->select('b.register_number,b.name,a.coe_student_mapping_id')
                    ->from('coe_student_mapping a')
                    ->join('JOIN', 'coe_student b', 'a.student_rel_id=b.coe_student_id')
                    ->where(['a.course_batch_mapping_id' => $batch_map_id, 'b.student_status' => 'Active'])
                    ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                // ->andWhere(['<>', 'status_category_type_id', $det_disc_type])->andWhere(['NOT IN','coe_student_mapping_id',$stu_map_ids_marks]);
            $student_list = $query->createCommand()->queryAll();
            if($mark_type==$arrear)
            {
                $get_stu_list_pass = Yii::$app->db->createCommand('SELECT student_map_id FROM coe_mandatory_stu_marks WHERE result like "%Pass%"  and subject_map_id="'.$sub_cat_code.'" group by student_map_id')->queryAll();
                $student_passed_list = [];
                if(!empty($get_stu_list_pass))
                {
                    foreach ($get_stu_list_pass as $passed) 
                    {
                        $student_passed_list[$passed['student_map_id']] = $passed['student_map_id'];
                    }
                }
                $query = new Query();
                $query->select('b.register_number,b.name,a.coe_student_mapping_id')
                ->from('coe_student_mapping a')
                ->join('JOIN', 'coe_student b', 'a.student_rel_id=b.coe_student_id')
                ->join('JOIN', 'coe_mandatory_stu_marks C', 'C.student_map_id=a.coe_student_mapping_id')
                ->join('JOIN', 'coe_mandatory_subcat_subjects D', 'D.batch_map_id=a.course_batch_mapping_id')
                ->where(['D.batch_map_id' => $batch_map_id,'C.result'=>'Fail','C.subject_map_id'=>$sub_cat_code, 'b.student_status' => 'Active'])->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);

                $get_mak_entry_status = MandatoryStuMarks::find()->where(['year'=>$year,'month'=>$month,'mark_type'=>$mark_type,'subject_map_id'=>$sub_cat_code])->all();

                if(!empty($student_passed_list) && empty($get_mak_entry_status))
                {
                    $query->andWhere(['NOT IN', 'C.student_map_id', $student_passed_list]);
                }
                $student_list = $query->groupBy('b.register_number')->createCommand()->queryAll();
            }
            if(!empty($student_list))
            {
                $sno = 1;
                $add_table = $add_additional = '';
                if($sub_cat_id->is_additional=='YES')
                {
                    $add_additional ='<tr>
                    <td colspan=8 ><h2 class="blinking" style="color: #d60458; font-weight: bold;" align="center"> THIS IS ADDITIONAL CREDIT '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).'  </b> </h2> </td>
                    </tr>'; 
                }
                
                $add_table .= '<div class="col-xs-12 col-sm-12 col-lg-12">
                        <div class="col-lg-12 col-sm-12">
                <table id="mandato_style" width="100%" border=1 >
                <tr>
                    <td colspan=8 ><h2 style="color: #07af07" align="center">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE : <b style="color : #072baf; "> '.$vals['subject_code'].'</b> INFO & DETAILS</h2> </td>
                </tr>'.$add_additional.'
                     <tr style="padding: 3%;">
                       <td>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </td> 
                       <th>'.$vals['subject_code'].' </th>
                       <td>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME </td>
                       <th>'.$vals['subject_name'].' </th>
                       <td> CATEGORY CODE </td> 
                       <th>'.$vals['sub_cat_code'].' </th>
                       <td> CATEGORY NAME </td>
                       <th>'.$vals['sub_cat_name'].' </th>
                      
                    </tr>
                    <tr style="padding: 3%;">
                      
                       <td> CIA MINIMUM </td>
                       <th>'.$vals['CIA_min'].'</th>
                       <td> CIA MAXIMUM </td>
                       <th>'.$vals['CIA_max'].'</th>
                       <td> ESE MINIMUM </td>
                       <th>'.$vals['ESE_min'].'</th>
                       <td> ESE MAXIMUM </td>
                       <th>'.$vals['ESE_max'].'</th>
                    </tr>
                </table> <br /><br />
                <table class="table table-striped" align="center" border="1" >     
                          <tbody>                                                                                                             
                          <td align="center"><b> S.NO </b></td> 
                          <td align="center"><b> Register Number </b></td>
                          <td align="center"><b> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' Name </b></td>
                          <td align="center"><b> Action </b></td>
                          <td align="center" width="1px"><b>Mark</b></td>
                          <td align="center" width="1px"><b>Grade</b></td>
                          <td align="center" width="1px"><b>Grade Point</b></td>
                          <td align="center"><b>Result</b></td>';

                foreach ($student_list as $stu_list) 
                {

                    $query_exist = new Query();
                    $query_exist->select('*')
                            ->from('coe_mandatory_stu_marks a')
                            ->where(['a.student_map_id' => $stu_list['coe_student_mapping_id'], 'a.subject_map_id' => $sub_cat_code,'year'=>$year,'month'=>$month,'mark_type'=>$mark_type]);
                    $exist_student = $query_exist->createCommand()->queryOne();

                   // Check if student has the marks in the same year and month
                    if ($exist_student != "") 
                    {
                        $add_table .= "<tr><td align='center'><input type='hidden' name='sn' value=" . $sno . ">" . $sno . "</td>";
                        $add_table .= "<td align='center'><input type='hidden' name=reg_num" . $sno . " value=" . $stu_list['register_number'] . ">" . $stu_list['register_number'] . "</td>";
                        $add_table .= "<td>" . $stu_list['name'] . "</td>";

                        $add_table .= "<td align='center'><input type='checkbox' checked disabled></td>";
                       
                       $add_table .= "<td><input type='textbox' size='3px' onchange='getAddResult(this.id,this.value);' autocomplete='off' onkeypress='numbersOnly(event); allowEntr(event,this.id);' name=actxt_" . $sno . " value=" . $exist_student['total'] . " id=actxt_" . $sno . " disabled></td>";
                        $grade_point= $exist_student['grade_point']=='' || empty($exist_student['grade_point'])?0:$exist_student['grade_point'];
                        $add_table .= "<td><input type='textbox' size='3px'  name=grade_" . $sno . " id=grade_" . $sno . " autocomplete='off' value=" . $exist_student['grade_name'] . " disabled></td>";
                        $add_table .= "<td><input type='textbox' size='3px' name=grade_point_" . $sno . " id=grade_point_" . $sno . " autocomplete='off' value=" . $grade_point . " disabled></td>";
                        $add_table .= "<td align='center'><input type='textbox' autocomplete='off' size='5px' name=acresult_" . $sno . " id=acresult_" . $sno . " value=" . $exist_student['result'] . " disabled></td></tr>";
                        $sno++;

                    } 
                    else 
                    {
                            
                            $check_written = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_stu_marks AS A JOIN coe_mandatory_subcat_subjects AS B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id WHERE  A.student_map_id="'.$stu_list['coe_student_mapping_id'].'" AND B.is_additional="NO" and subject_map_id="'.$sub_cat_id->coe_mandatory_subcat_subjects_id.'"')->queryOne();
                           
                            if(!empty($check_written) && $mark_type==$regulAr && $sub_cat_id->is_additional==='YES')
                            {
                                    if($sub_cat_id->sub_cat_code==$check_written['sub_cat_code'])
                                    {

                                    }
                                    else
                                    {
                                        $add_table .= "<tr><td align='center'><input type='hidden' name='sn' value=" . $sno . ">" . $sno . "</td>";
                                        $add_table .= "<td align='center'><input type='hidden' name=reg_num" . $sno . " value=" . $stu_list['register_number'] . ">" . $stu_list['register_number'] . "</td>";
                                        $add_table .= "<td>" . $stu_list['name'] . "</td>";
                                         $add_table .= "<td align='center'>
                                            <input type='checkbox' onclick='additional_check(this.id)' name=add" . $sno . " id=add_" . $sno . "></td>";
                                    
                                        $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' onchange='getMandatoryResult(this.id,this.value);' onkeypress='numbersOnly(event); allowEntr(event,this.id);' name=actxt_" . $sno . " id=actxt_" . $sno . " disabled></td>";
                                        $add_table .= "<td><input autocomplete='off' type='textbox' size='3px'  name=grade_" . $sno . " id=grade_" . $sno . " readonly></td>";
                                        $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' name=grade_point_" . $sno . " id=grade_point_" . $sno . " readonly></td>";
                                        $add_table .= "<td align='center'><input autocomplete='off' type='textbox' size='5px' name=acresult_" . $sno . " id=acresult_" . $sno . " readonly /></td></tr>";
                                        $sno++;
                                    }
                                    
                               
                            }// If Condition Ends Her 
                            else if(!empty($check_written))
                            {

                                if($mark_type!=$regulAr)
                                {
                                    $add_table .= "<tr><td align='center'><input type='hidden' name='sn' value=" . $sno . ">" . $sno . "</td>";
                                    $add_table .= "<td align='center'><input type='hidden' name=reg_num" . $sno . " value=" . $stu_list['register_number'] . ">" . $stu_list['register_number'] . "</td>";
                                    $add_table .= "<td>" . $stu_list['name'] . "</td>";
                                     $add_table .= "<td align='center'>
                                        <input type='checkbox' onclick='additional_check(this.id)' name=add" . $sno . " id=add_" . $sno . "></td>";
                                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' onchange='getMandatoryResult(this.id,this.value);' onkeypress='numbersOnly(event); allowEntr(event,this.id);' name=actxt_" . $sno . " id=actxt_" . $sno . " disabled></td>";
                                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px'  name=grade_" . $sno . " id=grade_" . $sno . " readonly></td>";
                                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' name=grade_point_" . $sno . " id=grade_point_" . $sno . " readonly></td>";
                                    $add_table .= "<td align='center'><input autocomplete='off' type='textbox' size='5px' name=acresult_" . $sno . " id=acresult_" . $sno . " readonly /></td></tr>";
                                    $sno++;
                                }
                                
                            }// Not Marks Else Condition
                            else if($sub_cat_id->is_additional==='YES')
                            {

                                $check_attempted = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_stu_marks Where subject_map_id="'.$sub_cat_id->coe_mandatory_subcat_subjects_id.'" and student_map_id="'.$stu_list['coe_student_mapping_id'].'" ')->queryAll();
                               
                               $get_stu_list_codes = Yii::$app->db->createCommand('SELECT DISTINCT sub_cat_code as sub_cat_code FROM coe_mandatory_stu_marks as A JOIN coe_mandatory_subcat_subjects as B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id Where student_map_id="'.$stu_list['coe_student_mapping_id'].'" ')->queryAll();
                               $sub_cat_codes = [];
                               if(!empty($get_stu_list_codes))
                               {
                                    foreach ($get_stu_list_codes as $get_sub_cat_codes ) 
                                   {
                                       $sub_cat_codes[$get_sub_cat_codes['sub_cat_code']] =  $get_sub_cat_codes['sub_cat_code'];
                                   }
                               }
                             
                               
                                if(empty($check_attempted) && !in_array($sub_cat_id->sub_cat_code, $sub_cat_codes))
                                {
                                    $add_table .= "<tr><td align='center'><input type='hidden' name='sn' value=" . $sno . ">" . $sno . "</td>";
                                    $add_table .= "<td align='center'><input type='hidden' name=reg_num" . $sno . " value=" . $stu_list['register_number'] . ">" . $stu_list['register_number'] . "</td>";
                                    $add_table .= "<td>" . $stu_list['name'] . "</td>";
                                     $add_table .= "<td align='center'>
                                        <input type='checkbox' onclick='additional_check(this.id)' name=add" . $sno . " id=add_" . $sno . "></td>";
                                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' onchange='getMandatoryResult(this.id,this.value);' onkeypress='numbersOnly(event); allowEntr(event,this.id);' name=actxt_" . $sno . " id=actxt_" . $sno . " disabled></td>";
                                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px'  name=grade_" . $sno . " id=grade_" . $sno . " readonly></td>";
                                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' name=grade_point_" . $sno . " id=grade_point_" . $sno . " readonly></td>";
                                    $add_table .= "<td align='center'><input autocomplete='off' type='textbox' size='5px' name=acresult_" . $sno . " id=acresult_" . $sno . " readonly /></td></tr>";
                                    $sno++;        
                                }
                                                        
                                
                            }// Not Marks Else Condition
                            else
                            {
                                $query_entry_in_month = new Query();
                                $query_entry_in_month->select('*')
                                        ->from('coe_mandatory_stu_marks a')
                                        ->where(['a.student_map_id' => $stu_list['coe_student_mapping_id'], 'year'=>$year,'month'=>$month,'mark_type'=>$mark_type]);
                                $exist_student_month = $query_entry_in_month->createCommand()->queryOne();
                                if(empty($exist_student_month))
                                {
                                        $add_table .= "<tr><td align='center'><input type='hidden' name='sn' value=" . $sno . ">" . $sno . "</td>";
                                        $add_table .= "<td align='center'><input type='hidden' name=reg_num" . $sno . " value=" . $stu_list['register_number'] . ">" . $stu_list['register_number'] . "</td>";
                                        $add_table .= "<td>" . $stu_list['name'] . "</td>";
                                         $add_table .= "<td align='center'>
                                            <input type='checkbox' onclick='additional_check(this.id)' name=add" . $sno . " id=add_" . $sno . "></td>";
                                    
                                        $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' onchange='getMandatoryResult(this.id,this.value);' onkeypress='numbersOnly(event); allowEntr(event,this.id);' name=actxt_" . $sno . " id=actxt_" . $sno . " disabled></td>";
                                        $add_table .= "<td><input autocomplete='off' type='textbox' size='3px'  name=grade_" . $sno . " id=grade_" . $sno . " readonly></td>";
                                        $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' name=grade_point_" . $sno . " id=grade_point_" . $sno . " readonly></td>";
                                        $add_table .= "<td align='center'><input autocomplete='off' type='textbox' size='5px' name=acresult_" . $sno . " id=acresult_" . $sno . " readonly /></td></tr>";
                                        $sno++;
                                }
                                 
                            }
                       
                    }

                    
                }
                $div_show = "<div id='man_sub_credit_btn_1' class='col-xs-12 col-sm-12 col-lg-12' >
                <div class='col-xs-12 col-sm-12 col-lg-12' >
                    <div class='form-group col-lg-6 col-sm-6' > <br />
                        <div class='btn-group' role='group' aria-label='Actions to be Perform' >

                            <button name='man_sub_credit_btn' value='Submit' class='btn  btn-group-lg btn-group btn-success' > Submit </button>

                            <a href='mandatory-stu-marks/create' class='btn btn-group btn-group-lg btn-warning' > Reset </a>             

                        </div>                
                    </div>
                </div>
            </div>";
                $add_table .= '</tbody></table> '.$div_show .' </div></div>';
            }
            else
            {
                $add_table = 'NO';
            }
        }

        $sub_info = !empty($add_table) ? $add_table:'NO';
        return Json::encode($sub_info);
    }

    /* Exam End */
    /* Ansent Entry Functions Starts Here */

    public function actionExamdatesviewab() 
    {
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];
        $vals = Yii::$app->db->createCommand("SELECT DISTINCT DATE_FORMAT(exam_date, '%d-%m-%Y') as exam_date FROM coe_exam_timetable as A WHERE A.exam_year='" . $get_id_details['exam_year'] . "' AND A.exam_month='" . $get_id_details['exam_month'] . "' ORDER BY exam_date")->queryAll();
        return Json::encode($vals);
    }

    public function actionExamdates() {
        $programme_id = Yii::$app->request->post('programme_id');
        $batch_id = Yii::$app->request->post('batch_id');
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');

        $get_id_details = ['exam_year'=>$exam_year,'exam_month'=>$exam_month];
        $query = new Query();

        $vals = Yii::$app->db->createCommand("SELECT DATE_FORMAT(exam_date, '%d-%m-%Y') as exam_date FROM coe_exam_timetable as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_mapping_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.batch_mapping_id  WHERE B.batch_mapping_id='" . $programme_id . "' AND C.coe_batch_id='" . $batch_id . "' AND A.exam_year='" . $get_id_details['exam_year'] . "' AND A.exam_month='" . $get_id_details['exam_month'] . "' ORDER BY exam_date GROUP BY exam_date")->queryAll();
        return Json::encode($vals);
    }

    public function actionGethalls() {
        $exam_date = date("Y-m-d", strtotime(Yii::$app->request->post('exam_date')));
        $exam_year = Yii::$app->request->post('year');
        $exam_month = Yii::$app->request->post('month');
        $query = new Query();
        $query->select('distinct (B.coe_hall_master_id),B.hall_name')
                ->from('coe_hall_allocate as A')
                ->join('JOIN', 'coe_hall_master as B', 'B.coe_hall_master_id=A.hall_master_id')
                ->join('JOIN', 'coe_exam_timetable as C', 'C.coe_exam_timetable_id=A.exam_timetable_id')
                ->where([
                    "C.exam_date" => $exam_date,
                    'C.exam_year'=>$exam_year,
                    'C.exam_month'=>$exam_month,
                    'A.year'=>$exam_year,
                    'A.month'=>$exam_month,
                ])
                ->orderBy('hall_name');
        $vals = $query->createCommand()->queryAll();
        return Json::encode($vals);
    }

    public function actionExamtype() {
        $exam_date = date("Y-m-d", strtotime(Yii::$app->request->post('exam_date')));
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');

        $query = new Query();
        $query->select(['DISTINCT (B.coe_category_type_id)', 'B.category_type'])
                ->from('coe_exam_timetable as A')
                ->join('JOIN', 'coe_category_type as B', 'B.coe_category_type_id=A.exam_type')
                ->where(['A.exam_date' => $exam_date, 'A.exam_year' => $exam_year,'A.exam_month'=>$exam_month]);
        $vals = $query->createCommand()->queryAll();
        
        return Json::encode($vals);
    }

    public function actionExamsubcode() {
        $exam_date = date("Y-m-d", strtotime(Yii::$app->request->post('exam_date')));
         $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $query = new Query();
        $query->select(['coe_subjects_id', 'C.subject_code'])
                ->from('coe_exam_timetable as A')
                ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=A.subject_mapping_id')
                ->join('JOIN', 'coe_subjects as C', 'C.coe_subjects_id=B.subject_id')
                ->where(['A.exam_date' => $exam_date,'A.exam_year'=>$exam_year,'A.exam_month'=>$exam_month])->groupBy(['subject_code']);
        $vals = $query->createCommand()->queryAll();
        return Json::encode($vals);
    }

    public function actionExamterm() {
        $exam_date = date("Y-m-d", strtotime(Yii::$app->request->post('exam_date')));
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        
            $query = new Query();
            $query->select(['DISTINCT (B.coe_category_type_id)', 'B.category_type'])
                    ->from('coe_exam_timetable as A')
                    ->join('JOIN', 'coe_category_type as B', 'B.coe_category_type_id=A.exam_term')
                    ->where(['A.exam_date' => $exam_date, 'A.exam_year' => $exam_year, 'A.exam_month' => $exam_month]);
            $vals = $query->createCommand()->queryAll();
        
        return Json::encode($vals);
    }

    public function actionExamsession() {
        $exam_date = date("Y-m-d", strtotime(Yii::$app->request->post('exam_date')));
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');

        $query = new Query();
        $query->select(['DISTINCT (B.coe_category_type_id) as coe_category_type_id', 'B.category_type'])
                ->from('coe_exam_timetable as A')
                ->join('JOIN', 'coe_category_type as B', 'B.coe_category_type_id=A.exam_session')
                ->where(['A.exam_date' => $exam_date, 'A.exam_year' => $exam_year,'A.exam_month' => $exam_month]);
        $vals = $query->createCommand()->queryAll();
           
        return Json::encode($vals);
    }

    public function actionShowrequiredviewab() {
        $cat_id = Yii::$app->request->post('ab_type');
        $vals = Categorytype::find()->where(['coe_category_type_id' => $cat_id])->one();
        $cat_val = $vals->description;

        if (stristr($cat_val, "Exam Hall") || stristr($cat_val, "Hall")) {
            $send_data = "Hall";
        } else if (stristr($cat_val, "Examwise") || stristr($cat_val, "Exam")) {
            $send_data = "Exam";
        } else {
            $send_data = "Support";
        }

        if ($send_data == 'Hall') {
            
        }


        return Json::encode($send_data);
    }

    public function actionShowrequired() {
        $cat_id = Yii::$app->request->post('catId');
        $vals = Categorytype::find()->where(['coe_category_type_id' => $cat_id])->one();
        $cat_val = $vals->description;
        if (stristr($cat_val, "Practical Entry")) {
            $send_data = 'Practical';
        } else if (stristr($cat_val, "Exam Hall") || stristr($cat_val, "Hall")) {
            $send_data = "Hall";
        } else if (stristr($cat_val, "Examwise") || stristr($cat_val, "Exam")) {
            $send_data = "Exam";
        } else {
            $send_data = "Support";
        }
        return Json::encode($send_data);
    }

    public function actionExternalsubjectcodes() {
        $ab_prgm_id_1 = Yii::$app->request->post('programme_id_val');
        $exam_semester_id_1 = Yii::$app->request->post('exam_semester_id_1');
        $exam_type = Yii::$app->request->post('exam_type');
        $exam_term = Yii::$app->request->post('exam_term');
        $query = new Query();
        $query->select("c.coe_subjects_mapping_id as sub_id,d.subject_code as sub_code")
                ->from('coe_subjects d')
                ->join('JOIN', 'coe_subjects_mapping c', 'd.coe_subjects_id = c.subject_id')
                ->join('JOIN', 'coe_exam_timetable b', 'c.coe_subjects_mapping_id = b.subject_mapping_id')
                ->where(['c.batch_mapping_id' => $ab_prgm_id_1, 'c.semester' => $exam_semester_id_1, 'b.exam_type' => $exam_type, 'b.exam_term' => $exam_term])
                ->groupBy('c.coe_subjects_mapping_id');
        $send_result = $query->createCommand()->queryAll();
        return Json::encode($send_result);
    }

    public function actionShowsubjectcodes() {
        $exam_type_1 = Yii::$app->request->post('exam_type_1');
        $ab_prgm_id_1 = Yii::$app->request->post('ab_prgm_id_1');
        $section_1 = Yii::$app->request->post('section_1');
        $exam_semester_id_1 = Yii::$app->request->post('exam_semester_id_1');
        $absent_type_1 = Yii::$app->request->post('absent_type_1');
        $exam_session_1 = Yii::$app->request->post('exam_session_1');
        $exam_date_1 = date("Y-m-d", strtotime(Yii::$app->request->post('exam_date_1')));

        $halls_1 = Yii::$app->request->post('halls_1');
        $vals = Categorytype::find()->where(['coe_category_type_id' => $absent_type_1])->one();
        $cat_val = $vals->description;
        if (stristr($cat_val, "Practical Entry") || stristr($cat_val, "Practical")) 
        {
            $query = new Query();
            $query->select("d.coe_subjects_id as sub_id,d.subject_code as sub_code")
                    ->from('coe_category_type a')
                    ->join('JOIN', 'coe_categories b', 'b.coe_category_id = a.category_id')
                    ->join('JOIN', 'coe_subjects_mapping c', 'c.paper_type_id = a.coe_category_type_id')
                    ->join('JOIN', 'coe_subjects d', 'd.coe_subjects_id = c.subject_id')
                    ->join('JOIN', 'coe_bat_deg_reg e', 'e.coe_bat_deg_reg_id = c.batch_mapping_id')
                    ->where(['b.category_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PAPER_TYPE), 'c.semester' => $exam_semester_id_1,
                        'c.batch_mapping_id' => $ab_prgm_id_1])
                    ->andWhere(['IN', 'a.description', ['Practical','Project','Mini Project','Project and Viva Voce']])
                    ->andWhere(['not like', 'a.description', 'Practical Entry'])
                    ->groupBy('d.coe_subjects_id');
            $send_result = $query->createCommand()->queryAll();
            $send_result = ['send_result' => $send_result, 'result_type' => 'Practical'];
        } else if (stristr($cat_val, "Exam Hall") || stristr($cat_val, "Hall")) {
            $send_result = "Hall";
        } else if (stristr($cat_val, "Examwise") || stristr($cat_val, "Exam")) {
            $send_result = "Exam";
        } else {
            $send_result = "Support";
        }
        return Json::encode($send_result);
    }

    public function actionGetanswerpacketsinfo() 
    {
        $exam_date = date('Y-m-d', strtotime(Yii::$app->request->post('exam_date')));
        $exam_session = Yii::$app->request->post('exam_session');
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $packet_count = Yii::$app->request->post('packet_count');
        $packet_count = isset($packet_count) && !empty($packet_count) ?$packet_count:60;
        $getSessName = Categorytype::findOne($exam_session);
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $examAllDet = ExamTimetable::find()->where(['exam_date'=>$exam_date,'exam_session'=>$exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])->all();
       
        $examDet = ExamTimetable::find()->where(['exam_date'=>$exam_date,'exam_session'=>$exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])->one();
        $monthName = Categorytype::findOne($examDet['exam_month']);
        
        for ($subId=0; $subId <count($examAllDet) ; $subId++) 
        { 
            $getSubjecCids[] = SubjectsMapping::findOne($examAllDet[$subId]['subject_mapping_id']);
        }

        $subjectCodes=array_filter([]);
        $getSubjecCids = array_filter($getSubjecCids);
        sort($getSubjecCids);
        for ($subId1=0; $subId1 <count($getSubjecCids) ; $subId1++) 
        { 
            $getSubjecCodes = Subjects::find()->where(['coe_subjects_id'=>$getSubjecCids[$subId1]['subject_id']])->all();
            foreach ($getSubjecCodes as $key => $hhhs) 
            {
                $subjectCodes[$hhhs['subject_code']]= $hhhs['subject_code']; 
            }
        }     
        sort($subjectCodes);       
        for ($pre=0; $pre <count($subjectCodes) ; $pre++) 
        { 
            $subIdsSend = Subjects::find()->where(['subject_code'=>$subjectCodes[$pre]])->all();
            $separte_data=array_filter(['']);
            if(count($subIdsSend)>1)
            {
                for ($abse_cs=0; $abse_cs < count($subIdsSend); $abse_cs++) 
                { 
                    $getMappingId[] = SubjectsMapping::find()->where(['subject_id'=>$subIdsSend[$abse_cs]->coe_subjects_id])->all();
                }               
            }
            else
            {
                $subIdsSend = Subjects::find()->where(['subject_code'=>$subjectCodes[$pre]])->one();
                $getMappingId = SubjectsMapping::find()->where(['subject_id'=>$subIdsSend->coe_subjects_id])->all();                  
            }
            $mapIds=[];
            $flat_arrau = ConfigUtilities::array_flatten($getMappingId);
            
            if(count($subIdsSend)>1)
            {
                foreach ($flat_arrau as $key => $inceava_1) 
                {
                    $mapIds[$inceava_1['coe_subjects_mapping_id']]=$inceava_1['coe_subjects_mapping_id'];
                }
                             
            }
            else
            {
                foreach ($getMappingId as $sub_maps) 
                {
                    $mapIds[$sub_maps['coe_subjects_mapping_id']]=$sub_maps['coe_subjects_mapping_id'];    
                }                
            }          
              
            $query_1 = new Query();
            $query_1->select(['count(*) as present'])
                    ->from('coe_hall_allocate as A')
                    ->join('JOIN','coe_student as C','C.register_number=A.register_number ')
                    ->join('JOIN','coe_student_mapping as D','D.student_rel_id=C.coe_student_id ')
                    ->JOIN('JOIN', 'coe_exam_timetable B', 'B.coe_exam_timetable_id = A.exam_timetable_id')
                    ->where(['B.exam_date' => $exam_date, 'B.exam_session' => $exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->andWhere(['IN','subject_mapping_id',$mapIds]);
            $command = $query_1->createCommand();
            $subJectManppingIds[] = $mapIds;
        }
        $allIDs = ConfigUtilities::array_flatten($subJectManppingIds);

        if (count($allIDs) > 0 && !empty($allIDs)) 
        {
            $body = $header ='';
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                    $header .= '<table border=1  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="student_bulk_edit"  >
                <tr>
                    <td>
                        <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                    </td>
                    <td colspan=4 align="center"><h3> 
                          <center><b><font size="5px">' . $org_name . '</font></b></center>
                          <center> <font size="3px">' . $org_address . '</font></center>
                          <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                          </h3>
                     </td>
                      <td align="center">  
                        <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                      </td>
                 </tr>
                
                 <tr>
                    <td align="center" colspan=6 ><h4>  REGULAR / ARREAR EXAMNIATIONS ANSWER PACKET FOR DATE : <b>'.date('d-m-Y',strtotime($exam_date)).'</b> SESSION : <b>'.strtoupper($getSessName->description).'</b>   </h4>
                    </td>
                </tr>

                    <tr>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)).' DATE </th>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)).' SESSION </th>
                        
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE</th>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).'NAME</th>
                        <th text-rotate=45>ANSWER PAPER PACKET NUMBER ALLOTED BY <br /> THE AUTONOMOUS EXAM CELL</th>
                        <th text-rotate=45>TOTAL ANSWER SCRIPTS</th>
                        
                    </tr>                     
                 '; 

            for ($subId=0; $subId <count($examAllDet) ; $subId++) 
            { 
                $getSubjecCids[] = SubjectsMapping::findOne($examAllDet[$subId]['subject_mapping_id']);
            }
            $subjectCodes=array_filter([]);
             $getSubjecCids = array_filter($getSubjecCids);
            sort($getSubjecCids);
            for ($subId1=0; $subId1 <count($getSubjecCids) ; $subId1++) 
            { 
                $getSubjecCodes = Subjects::find()->where(['coe_subjects_id'=>$getSubjecCids[$subId1]['subject_id']])->all();                
                foreach ($getSubjecCodes as $key => $hhhs) 
                {
                    $subjectCodes[$hhhs['subject_code']]= $hhhs['subject_code']; 
                }           
            }       
            sort($subjectCodes);
            
            $scriptNumber = 0;
            $previ_print='';
            for ($a=0; $a <count($subjectCodes) ; $a++) 
            { 
                $subIdsSend = Subjects::find()->where(['subject_code'=>$subjectCodes[$a]])->all();
                $getMappingId = array_filter(['']);
                if(count($subIdsSend)>1)
                {
                    for ($abse_cs=0; $abse_cs < count($subIdsSend); $abse_cs++) 
                    { 
                        $getMappingId[] = SubjectsMapping::find()->where(['subject_id'=>$subIdsSend[$abse_cs]->coe_subjects_id])->all();
                    }                     
                }
                else
                {
                    $subIdsSend = Subjects::find()->where(['subject_code'=>$subjectCodes[$a]])->one();
                    $getMappingId = SubjectsMapping::find()->where(['subject_id'=>$subIdsSend->coe_subjects_id])->all();  
                }
                $mapIds=array_filter(['']);
                $flat_arrau = ConfigUtilities::array_flatten($getMappingId);        
                foreach ($flat_arrau as $sub_maps) 
                {
                    $mapIds[$sub_maps['coe_subjects_mapping_id']]=$sub_maps['coe_subjects_mapping_id'];
                }    
               
                $query = new Query();
                $query->select(['A.*'])
                        ->from('coe_absent_entry as A')
                        ->where(['A.exam_date' => $exam_date, 'A.exam_session' => $exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])->andWhere(['IN','A.exam_subject_id',$mapIds]);
                $total_absent = $query->createCommand()->queryAll();
                sort($mapIds);
                $subcsIds = '';
                for ($i=0; $i <count($mapIds) ; $i++) { 
                   $subcsIds .='"'.$mapIds[$i].'", ';
                }
                $comma_sub_dis = trim($subcsIds,', ');
                if(!empty($comma_sub_dis) && $comma_sub_dis!='')
                {
                    $getSubInfoDe = Yii::$app->db->createCommand('SELECT * FROM coe_subjects_mapping as A JOIN coe_subjects AS B ON B.coe_subjects_id=A.subject_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=A.batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_exam_timetable as F ON F.subject_mapping_id=A.coe_subjects_mapping_id WHERE A.coe_subjects_mapping_id IN ('.$comma_sub_dis.') AND F.exam_date="'.$exam_date.'" and F.exam_year="'.$exam_year.'" and F.exam_month="'.$exam_month.'" AND F.exam_session="'.$exam_session.'"')->queryOne();
                    $print_absent = count($total_absent)==0?'-':count($total_absent);

                    $query_1 = new Query();
                    $query_1->select(['count(*) as present'])
                            ->from('coe_hall_allocate as A')
                            ->JOIN('JOIN', 'coe_exam_timetable B', 'B.coe_exam_timetable_id = A.exam_timetable_id')
                            ->join('JOIN','coe_student as C','C.register_number=A.register_number ')
                            ->join('JOIN','coe_student_mapping as D','D.student_rel_id=C.coe_student_id ')
                            ->where(['B.exam_date' => $exam_date, 'B.exam_session' => $exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])->andWhere(['IN','subject_mapping_id',$mapIds])->andWhere(['<>','status_category_type_id',$det_disc_type]);
                    $total_present = $query_1->createCommand()->queryScalar();
                    
                    $present_students = ($total_present-count($total_absent));
                    $inc_script = $total_present==0 || empty($total_present) || $present_students==0 ?0:1;
                    $scriptNumber = $scriptNumber+$inc_script;
                    $print_scripts_number = strlen($scriptNumber)==1?'0'.$scriptNumber:$scriptNumber;

                    $disp = $previ_print==$print_scripts_number ? '-':$print_scripts_number;
                    $ScriptsCunt = $present_students==0 || $disp=='-' ?'-': (strlen($present_students)==1?"0".$present_students:$present_students);
                    $disp = $disp=='00'?',,':$disp;

                    if($ScriptsCunt>$packet_count)
                    {
                        $break_val = ceil($ScriptsCunt/$packet_count);
                        $total_script_break = $ScriptsCunt;
                        $ans_packs = $les_make=$packet_count; 
                        for ($break=0; $break <$break_val ; $break++) 
                        { 
                            $disp = strlen($disp)==1?'0'.$disp:$disp;
                            if($break==0)
                            {
                                $body .='<tr>';
                                $body .='<td>'.date('d-m-Y',strtotime($exam_date)).'</td>';
                                $body .='<td>'.strtoupper($getSessName->description).'</td>';
                                $body .='<td>'.strtoupper($getSubInfoDe['subject_code']).'</td>';
                                $body .='<td>'.strtoupper($getSubInfoDe['subject_name']).'</td>'; 
                                $body .='<td>'.$disp.'</td>';
                                $body .='<td>'.$ans_packs.'</td>';
                                $body .='</tr>';
                            }
                            else if($break==($break_val-1))
                            {
                                $les_make = $les_make-$packet_count;
                                $print = $ScriptsCunt-$les_make; 
                                $body .='<tr>';
                                $body .='<td align="center">,,</td>';
                                $body .='<td align="center">,,</td>';
                                $body .='<td>'.strtoupper($getSubInfoDe['subject_code']).'</td>';
                                $body .='<td>'.strtoupper($getSubInfoDe['subject_name']).'</td>'; 
                                $body .='<td>'.$disp.'</td>';
                                $body .='<td>'.$print.'</td>';
                                $body .='</tr>'; 
                                break;
                            }
                            else
                            {
                                $print = $ans_packs;
                                $body .='<tr>';
                                $body .='<td align="center">,,</td>';
                                $body .='<td align="center">,,</td>';
                                $body .='<td>'.strtoupper($getSubInfoDe['subject_code']).'</td>';
                                $body .='<td>'.strtoupper($getSubInfoDe['subject_name']).'</td>'; 
                                $body .='<td>'.$disp.'</td>';
                                $body .='<td>'.$ans_packs.'</td>';
                                $body .='</tr>';
                            }
                            $les_make = $les_make+$packet_count; 
                            $scriptNumber++;
                            $disp++;
                            $previ_print = $scriptNumber;
                        } 
                       
                    }
                    else
                    {
                        $body .='<tr>';
                        $body .='<td>'.date('d-m-Y',strtotime($exam_date)).'</td>';
                        $body .='<td>'.strtoupper($getSessName->description).'</td>';
                        $body .='<td>'.strtoupper($getSubInfoDe['subject_code']).'</td>';
                        $body .='<td>'.strtoupper($getSubInfoDe['subject_name']).'</td>'; 
                        $body .='<td>'.$disp.'</td>';
                        $body .='<td>'.$ScriptsCunt.'</td>';
                        $body .='</tr>';
                        $disp++;
                        $previ_print = $scriptNumber;
                    }
                }
                 
            }
            $send_results = $header.$body."</table>";
            
        } else {
            $send_results = 0;
        }
        if (isset($_SESSION['get_answer_packet'])) {
            unset($_SESSION['get_answer_packet']);
            
        }
        $_SESSION['get_answer_packet'] = $send_results;
        return Json::encode($send_results);
    }

    public function actionGetprintregisternumber() 
    {
        $exam_date = date('Y-m-d', strtotime(Yii::$app->request->post('exam_date')));
        $exam_session = Yii::$app->request->post('exam_session');
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $print_count = Yii::$app->request->post('print_count');
        $getSessName = Categorytype::findOne($exam_session);
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $examAllDet = ExamTimetable::find()->where(['exam_date'=>$exam_date,'exam_session'=>$exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])->all();
       
        $examDet = ExamTimetable::find()->where(['exam_date'=>$exam_date,'exam_session'=>$exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])->one();
        $monthName = Categorytype::findOne($examDet['exam_month']);
        
        for ($subId=0; $subId <count($examAllDet) ; $subId++) 
        { 
            $getSubjecCids[] = SubjectsMapping::findOne($examAllDet[$subId]['subject_mapping_id']);
        }

        $subjectCodes=array_filter([]);
        $getSubjecCids = array_filter($getSubjecCids);
        sort($getSubjecCids);
        for ($subId1=0; $subId1 <count($getSubjecCids) ; $subId1++) 
        { 
            $getSubjecCodes = Subjects::find()->where(['coe_subjects_id'=>$getSubjecCids[$subId1]['subject_id']])->all();
            foreach ($getSubjecCodes as $key => $hhhs) 
            {
                $subjectCodes[$hhhs['subject_code']]= $hhhs['subject_code']; 
            }                      
        }       
        sort($subjectCodes);
        for ($pre=0; $pre <count($subjectCodes) ; $pre++) 
        { 
            $subIdsSend = Subjects::find()->where(['subject_code'=>$subjectCodes[$pre]])->all();
            $separte_data=array_filter(['']);

            if(count($subIdsSend)>1)
            {
                for ($abse_cs=0; $abse_cs < count($subIdsSend); $abse_cs++) 
                { 
                    $getMappingId[] = SubjectsMapping::find()->where(['subject_id'=>$subIdsSend[$abse_cs]->coe_subjects_id])->all();
                }               
            }
            else
            {
                $subIdsSend = Subjects::find()->where(['subject_code'=>$subjectCodes[$pre]])->one();
                $getMappingId = SubjectsMapping::find()->where(['subject_id'=>$subIdsSend->coe_subjects_id])->all();                  
            }
            $mapIds=array_filter(['']);
            $flat_arrau = ConfigUtilities::array_flatten($getMappingId);
            
            if(count($subIdsSend)>1)
            {
                foreach ($flat_arrau as $key => $inceava_1) 
                {
                    $mapIds[$inceava_1['coe_subjects_mapping_id']]=$inceava_1['coe_subjects_mapping_id'];
                }
                             
            }
            else
            {
                foreach ($getMappingId as $sub_maps) 
                {
                    $mapIds[$sub_maps['coe_subjects_mapping_id']]=$sub_maps['coe_subjects_mapping_id'];    
                }                
            }
              
            $query_1 = new Query();
            $query_1->select(['count(*) as present'])
                    ->from('coe_hall_allocate as A')
                    ->JOIN('JOIN', 'coe_exam_timetable B', 'B.coe_exam_timetable_id = A.exam_timetable_id')
                    ->join('JOIN','coe_student as C','C.register_number=A.register_number ')
                    ->join('JOIN','coe_student_mapping as D','D.student_rel_id=C.coe_student_id ')
                    ->where(['B.exam_date' => $exam_date, 'B.exam_session' => $exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])
                    ->andWhere(['IN','subject_mapping_id',$mapIds])
                    ->andWhere(['<>','status_category_type_id',$det_disc_type]);
            $command = $query_1->createCommand();
            $subJectManppingIds[] = $mapIds;
        }
        $allIDs = ConfigUtilities::array_flatten($subJectManppingIds);
        if (count($allIDs) > 0 && !empty($allIDs)) 
        {
            $body  = $html= $header ='';
            $header = '<table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >'; 
            for ($subId=0; $subId <count($examAllDet) ; $subId++) 
            { 
                $getSubjecCids[] = SubjectsMapping::findOne($examAllDet[$subId]['subject_mapping_id']);
            }
            $subjectCodes=array_filter([]);
            for ($subId1=0; $subId1 <count($getSubjecCids) ; $subId1++) 
            { 
                $getSubjecCodes = Subjects::find()->where(['coe_subjects_id'=>$getSubjecCids[$subId1]['subject_id']])->one();
               
                $subjectCodes[$getSubjecCodes->subject_code]= $getSubjecCodes->subject_code;            
            }       
            sort($subjectCodes);
            $scriptNumber = 0;
            $previ_print='';
            $sn=1;
            $pack_num = 1;
            for ($a=0; $a <count($subjectCodes) ; $a++) 
            { 
                
                $subIdsSend = Subjects::find()->where(['subject_code'=>$subjectCodes[$a]])->all();
                $getMappingId = array_filter(['']);
                if(count($subIdsSend)>1)
                {
                    for ($abse_cs=0; $abse_cs < count($subIdsSend); $abse_cs++) 
                    { 
                        $getMappingId[] = SubjectsMapping::find()->where(['subject_id'=>$subIdsSend[$abse_cs]->coe_subjects_id])->all();
                    }                     
                }
                else
                {
                    $subIdsSend = Subjects::find()->where(['subject_code'=>$subjectCodes[$a]])->one();
                    $getMappingId = SubjectsMapping::find()->where(['subject_id'=>$subIdsSend->coe_subjects_id])->all();  
                }
                $mapIds=array_filter(['']);
                $flat_arrau = ConfigUtilities::array_flatten($getMappingId);        
                foreach ($flat_arrau as $sub_maps) 
                {
                    $mapIds[$sub_maps['coe_subjects_mapping_id']]=$sub_maps['coe_subjects_mapping_id'];
                }    
                  
                $query = new Query();
                $query->select(['A.*'])
                        ->from('coe_absent_entry as A')
                        ->where(['A.exam_date' => $exam_date, 'A.exam_session' => $exam_session,'exam_month'=>$exam_month,'A.exam_year'=>$exam_year])->andWhere(['IN','A.exam_subject_id',$mapIds]);
                $total_absent = $query->createCommand()->queryAll();

                $stu_mapIds = [];
                foreach ($total_absent as  $stuDios) {
                    $stu_mapIds[$stuDios['absent_student_reg']]=$stuDios['absent_student_reg'];
                }

                sort($mapIds);
                $subcsIds = '';
                for ($i=0; $i <count($mapIds) ; $i++) { 
                   $subcsIds .='"'.$mapIds[$i].'", ';
                }
                $comma_sub_dis = trim($subcsIds,', ');
                if(!empty($comma_sub_dis) && $comma_sub_dis!='')
                {
                    $getSubInfoDe = Yii::$app->db->createCommand('SELECT * FROM coe_subjects_mapping as A JOIN coe_subjects AS B ON B.coe_subjects_id=A.subject_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=A.batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_exam_timetable as F ON F.subject_mapping_id=A.coe_subjects_mapping_id WHERE A.coe_subjects_mapping_id IN ('.$comma_sub_dis.') AND F.exam_date="'.$exam_date.'" AND F.exam_session="'.$exam_session.'" and F.exam_year="'.$exam_year.'" and F.exam_month="'.$exam_month.'"')->queryOne();

                    $query_1 = new Query();
                    $query_1->select(['count(*) as present'])
                            ->from('coe_hall_allocate as A')
                            ->JOIN('JOIN', 'coe_exam_timetable B', 'B.coe_exam_timetable_id = A.exam_timetable_id')
                            ->join('JOIN','coe_student as C','C.register_number=A.register_number ')
                            ->join('JOIN','coe_student_mapping as D','D.student_rel_id=C.coe_student_id ')
                            ->where(['B.exam_date' => $exam_date, 'B.exam_session' => $exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])->andWhere(['IN','subject_mapping_id',$mapIds])
                            ->andWhere(['<>','status_category_type_id',$det_disc_type]);
                    $total_appeared = $query_1->createCommand()->queryScalar();
                    $print_absent = ($total_appeared-count($total_absent));
                    if($print_absent==0)
                    {

                    }
                    else
                    {
                        
                        $query_1 = new Query();
                        $query_1->select(['A.*'])
                                ->from('coe_hall_allocate as A')
                                ->JOIN('JOIN', 'coe_exam_timetable B', 'B.coe_exam_timetable_id = A.exam_timetable_id')
                                ->JOIN('JOIN', 'coe_subjects_mapping C', 'C.coe_subjects_mapping_id = B.subject_mapping_id')
                                ->JOIN('JOIN', 'coe_student_mapping D', 'D.course_batch_mapping_id = C.batch_mapping_id')
                                ->JOIN('JOIN', 'coe_student E', 'E.coe_student_id = D.student_rel_id AND E.register_number=A.register_number')
                                ->where(['B.exam_date' => $exam_date, 'B.exam_session' => $exam_session,'B.exam_month'=>$exam_month,'B.exam_year'=>$exam_year])->andWhere(['IN','subject_mapping_id',$mapIds])
                                ->andWhere(['NOT IN','D.coe_student_mapping_id',$stu_mapIds])
                                ->andWhere(['<>','D.status_category_type_id',$det_disc_type])
                                ->orderBy('A.register_number ASC');
                        $total_present = $query_1->createCommand()->queryAll();
                        $sess_name = Categorytype::findOne($exam_session);
                        $regNumbers='';
                        $count_of_60 = 0;
                        
                        if(count($total_present)==0)
                        {

                        }
                        else
                        {

                        $print_pack_number = strlen($pack_num)==1 ? "0".$pack_num:$pack_num;
                        $print_pockets = count($total_present)>=$print_count ? $print_count: count($total_present);
                        $body .='<tr height="45px"><td height=35> ========================== REGISTER NUMBERS START ============================= </td> </tr>';
                        $body .='<tr height="45px"><th style="border: 3px solid #000; color: #000;" align="center" height=35 ><h4> PACKET NO = '.$print_pack_number.' DATE '.date('d-m-Y',strtotime($exam_date)).'-'.$sess_name->category_type." ".strtoupper($getSubInfoDe['subject_code']).' '.strtoupper($getSubInfoDe['subject_name']).' ('.$print_pockets.')  </h4></th> </tr>';
                        $body .='<tr><td style="line-height: 1.6em;"> ';
                        $total_pockets = count($total_present);
                        $loop_pockets = ceil($total_pockets/$print_count);
                        $loop_inc=1; $print_inc = 0;

                        foreach ($total_present as  $valsaue) 
                        {
                            $count_of_60++;
                            if($count_of_60%($print_count+1)==0)
                            {
                                $loop_inc++;
                                $count_of_60=1;
                                if($loop_pockets==$loop_inc)
                                {
                                    $pack_num++;
                                     $print_inc = $loop_inc*$print_count;
                                     $print_pockets = $print_inc-$total_pockets; 
                                     $print_pockets = $print_count-$print_pockets;
                                }
                                else
                                {
                                    $pack_num++;
                                    $print_pockets =$print_count;
                                }
                                $print_pack_number = strlen($pack_num)==1 ? "0".$pack_num:$pack_num;
                                $body .='</td></tr>';
                                $body .='<tr height="45px"><td height=35> ============================ REGISTER NUMBERS END ============================== </td> </tr> <tr height="45px"><td height=25>  </td> </tr>';
                                $body .='<tr height="45px"><td height=35> ========================== REGISTER NUMBERS START ============================= </td> </tr>';
                                $body .='<tr height="45px"><th style="border: 3px solid #000; color: #000;" align="center" height=35 ><h4> Packet No = '.$print_pack_number.'  DATE  '.date('d-m-Y',strtotime($exam_date)).'-'.$sess_name->category_type.' '.strtoupper($getSubInfoDe['subject_code']).' '.strtoupper($getSubInfoDe['subject_name']).' ('.$print_pockets.') </h4></th> </tr>';
                                 $body .='<tr><td  style="line-height: 1.6em;">';
                            //     $body .="<b>".$valsaue['register_number'].", </b>";
                            }
                            $body .="<b>".$valsaue['register_number'].", </b>";
                        }// Foreach Closed Here 

                            $body .='</td></tr>';
                            $body .='<tr height="45px"><td height=35> ============================ REGISTER NUMBERS END ============================== </td> </tr> <tr height="45px"><td height=25>  </td> </tr>';
                            $pack_num++;
                        }
                }
                
                    
            }
            }
            $html .=$header.$body."</table>";
            $send_results = $html;  
            
        } else {
            $send_results = 0;
        }
        if (isset($_SESSION['get_print_reg'])) {
            unset($_SESSION['get_print_reg']);
            
        }
        $_SESSION['get_print_reg'] = $send_results;
        return Json::encode($send_results);
    }

    public function actionGetconsolidateablist() 
    {
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');

        $exam_date = date('Y-m-d', strtotime(Yii::$app->request->post('exam_date')));
        $exam_session = Yii::$app->request->post('exam_session');
        $getSessName = Categorytype::findOne($exam_session);
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $examAllDet = ExamTimetable::find()->where(['exam_date'=>$exam_date,'exam_session'=>$exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])->all();
        
        $monthName = Categorytype::findOne($exam_month);
        for ($subId=0; $subId <count($examAllDet) ; $subId++) 
        { 
            $getSubjecCids[] = SubjectsMapping::findOne($examAllDet[$subId]['subject_mapping_id']);
        }
        $subjectCodes=array_filter([]);
        for ($subId1=0; $subId1 <count($getSubjecCids) ; $subId1++) 
        { 
            $getSubjecCodes = Subjects::find()->where(['coe_subjects_id'=>$getSubjecCids[$subId1]['subject_id']])->one();
           
            $subjectCodes[$getSubjecCodes->subject_code]= $getSubjecCodes->subject_code;            
        }       
        sort($subjectCodes);
        for ($pre=0; $pre <count($subjectCodes) ; $pre++) 
        { 
            $subIdsSend = Subjects::find()->where(['subject_code'=>$subjectCodes[$pre]])->all();
            $separte_data=array_filter(['']);
            if(count($subIdsSend)>1)
            {
                for ($abse_cs=0; $abse_cs < count($subIdsSend); $abse_cs++) 
                { 
                    $getMappingId[] = SubjectsMapping::find()->where(['subject_id'=>$subIdsSend[$abse_cs]->coe_subjects_id])->all();
                }                 
            }
            else
            {
                $subIdsSend = Subjects::find()->where(['subject_code'=>$subjectCodes[$pre]])->one();
                $getMappingId = SubjectsMapping::find()->where(['subject_id'=>$subIdsSend->coe_subjects_id])->all();  
            }
           
            $mapIds=[];
            $flat_arrau = ConfigUtilities::array_flatten($getMappingId);            
            if(count($subIdsSend)>1)
            {
                foreach ($flat_arrau as $key => $inceava_1) 
                {
                    $mapIds[$inceava_1['coe_subjects_mapping_id']]=$inceava_1['coe_subjects_mapping_id'];
                }                             
            }
            else
            {
                foreach ($getMappingId as $sub_maps) 
                {
                    $mapIds[$sub_maps['coe_subjects_mapping_id']]=$sub_maps['coe_subjects_mapping_id'];    
                }                
            }  

            $query_1 = new Query();
            $query_1->select(['count(*) as present'])
                    ->from('coe_hall_allocate as A')
                    ->JOIN('JOIN', 'coe_exam_timetable B', 'B.coe_exam_timetable_id = A.exam_timetable_id')
                    ->join('JOIN','coe_student as C','C.register_number=A.register_number ')
                    ->join('JOIN','coe_student_mapping as D','D.student_rel_id=C.coe_student_id ')
                    ->where(['B.exam_date' => $exam_date, 'B.exam_session' => $exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])
                    ->andWhere(['IN','subject_mapping_id',$mapIds])
                    ->andWhere(['<>','status_category_type_id',$det_disc_type]);
            $command = $query_1->createCommand();
            $subJectManppingIds[] = $mapIds;
        }
        $allIDs = array_unique(ConfigUtilities::array_flatten($subJectManppingIds));
        
        $getSubInfoDe = '';$increment_val=1;
        if (count($allIDs) > 0 && !empty($allIDs)) 
        {
            $grand_total_present = $grand_total_register = $grand_total_absent = '';
        	$body = $header ='';
        	require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                    $header .= '<table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                <tr>
                    <td  colspan=2>
                        <img width="100" height="100" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                    </td>
                    <td colspan=5 align="center"> 
                          <center><b><font size="6px">' . $org_name . '</font></b></center>
                          <center> <font size="3px">' . $org_address . '</font></center>
                          <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                     </td>
                      <td  colspan=2 align="center">  
                        <img width="100" height="100" width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                      </td>
                 </tr>
                 <tr height="40">
                    <td align="center" colspan=9 style="padding: 10px;" ><h4>CONSOLIDATED '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)).' STATEMENT FOR '.$exam_year.' - '.strtoupper($monthName->description).' </h4>
                    </td>
                </tr>
                 <tr height="40">
                    <td align="center" style="padding: 10px;" colspan=9 ><h4>  REGULAR / ARREAR EXAMNIATIONS ON DATE : '.date('d-m-Y',strtotime($exam_date)).' SESSION : '.strtoupper($getSessName->description).'   </h4>
                    </td>
                </tr>
                

                    <tr class="table-danger">
                        <th width="30">SNO</th>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).'</th>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).'</th>
                        
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE</th>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).'NAME</th>
                        <th text-rotate=45>REGISTERED</th>
                        <th text-rotate=45>PRESENT</th>
                        <th text-rotate=45>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)).'</th>
                        <th>ABSENT REG NO\'S</th>
                    </tr>'; 
                sort($allIDs);
                for ($a=0; $a <count($allIDs) ; $a++) 
                {                 
                    $exce_query = "SELECT * FROM coe_subjects_mapping as A JOIN coe_subjects AS B ON B.coe_subjects_id=A.subject_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=A.batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_exam_timetable as F ON F.subject_mapping_id=A.coe_subjects_mapping_id JOIN coe_batch as G ON G.coe_batch_id=C.coe_batch_id WHERE F.exam_date='".$exam_date."' AND F.exam_session='".$exam_session."' and F.exam_month='".$exam_month."' and F.exam_year='".$exam_year."' AND A.coe_subjects_mapping_id = '".$allIDs[$a]."' group by batch_name,programme_code,subject_code order by batch_name,programme_code,subject_code";
                    $getSubInfoDe = Yii::$app->db->createCommand($exce_query)->queryAll();
                            
                    if(!empty($getSubInfoDe))
                    {                     
                        foreach ($getSubInfoDe as $changs) 
                        {
                            $query = new Query();
                            $query->select(['A.*'])
                                    ->from('coe_absent_entry as A')
                                    ->where(['A.exam_date' => $exam_date, 'A.exam_session' => $exam_session,'exam_month'=>$exam_month,'A.exam_year'=>$exam_year])->andWhere(['IN','A.exam_subject_id',$changs['subject_mapping_id']]);
                            $total_absent = $query->groupBy('absent_student_reg')->createCommand()->queryAll();
                            $print_absent = count($total_absent)==0?'-':count($total_absent);
                            
                            $query_1 = new Query();
                            $query_1->select(['count(*) as present'])
                                    ->from('coe_hall_allocate as A')
                                    ->JOIN('JOIN', 'coe_exam_timetable B', 'B.coe_exam_timetable_id = A.exam_timetable_id')
                                    ->join('JOIN','coe_student as C','C.register_number=A.register_number ')
                                    ->join('JOIN','coe_student_mapping as D','D.student_rel_id=C.coe_student_id ')
                                    ->where(['B.exam_date' => $exam_date, 'B.exam_session' => $exam_session,'exam_month'=>$exam_month,'exam_year'=>$exam_year])->andWhere(['IN','subject_mapping_id',$changs['subject_mapping_id']])
                                    ->andWhere(['<>','status_category_type_id',$det_disc_type]);
                            $total_present = $query_1->createCommand()->queryScalar();

                            if($total_present!=0)
                            {
                                $body .='<tr>';
                                $body .='<td width="30">'.$increment_val.'</td>';
                                $body .='<td>'.strtoupper($changs['batch_name']).'</td>';
                                $body .='<td>'.strtoupper($changs['degree_code']." ".$changs['programme_code']).'</td>';
                                $body .='<td>'.strtoupper($changs['subject_code']).'</td>';
                                $body .='<td>'.strtoupper($changs['subject_name']).'</td>';  
                                $body .='<td>'.$total_present.'</td>';
                                $body .='<td>'.($total_present-count($total_absent)).'</td>';
                                $body .='<td>'.$print_absent.'</td>';
                                $REG_num = '';

                                $grand_total_absent += count($total_absent); 
                                $grand_total_register += $total_present; 
                                $grand_total_present += ($total_present-count($total_absent)); 
                                foreach ($total_absent as $valuSSe) 
                                {
                                    $reg_num = Yii::$app->db->createCommand('SELECT register_number FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id where B.coe_student_mapping_id="'.$valuSSe['absent_student_reg'].'"')->queryScalar();

                                    $REG_num .=$reg_num.", ";
                                }
                                $body .='<td>'.trim($REG_num,', ').'</td>';
                                $body .='</tr>';
                                $increment_val++;
                            }
                        }
                    }
                     
                 }
                 $body .='</table><table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive bulk_edit_table table-hover" ><tr height="50">';
                 $body .='<td colspan=4  height="50" ><h4>REGISTERED: '.$grand_total_register.'</h4></td>'; 
                 $body .='<td colspan=3  height="50" align="center" ><h4>PRESENT: '.$grand_total_present.'</h4></td>'; 
                 $body .='<td colspan=2  height="50" ><h4>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)).': '.$grand_total_absent.'</h4></td>';
                 $body .='</tr></table>';
                 $send_results = $header.$body;
            
        } else {
            $send_results = 0;
        }
        if (isset($_SESSION['consolidate_absent_list'])) {
            unset($_SESSION['consolidate_absent_list']);
        } 
        $_SESSION['consolidate_absent_list'] = $send_results;
        return Json::encode($send_results);
    }

    public function actionGetablist() {
        $exam_date = date('Y-m-d', strtotime(Yii::$app->request->post('exam_date')));
        $exam_session = Yii::$app->request->post('exam_session');
        $exam_month = Yii::$app->request->post('exam_month');
        $exam_year = Yii::$app->request->post('exam_year');

        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $query = new Query();
        $query->select(['DATE_FORMAT(A.exam_date,"%d-%m-%Y") as exam_date', 'L.batch_name', "CONCAT(J.degree_code,'',K.programme_code) as programme_degree_name",  'E.category_type as exam_type', 'G.name', 'G.register_number', 'D.semester', 'H.subject_code', 'H.subject_name'])
                ->from('coe_absent_entry as A')
                ->JOIN('JOIN', 'coe_student_mapping B', 'B.coe_student_mapping_id = A.absent_student_reg')
                ->JOIN('JOIN', 'coe_student G', 'G.coe_student_id = B.student_rel_id')
                ->JOIN('JOIN', 'coe_subjects_mapping D', 'D.coe_subjects_mapping_id = A.exam_subject_id')
                ->JOIN('JOIN', 'coe_bat_deg_reg I', 'I.coe_bat_deg_reg_id = B.course_batch_mapping_id')
                ->JOIN('JOIN', 'coe_degree J', 'J.coe_degree_id = I.coe_degree_id')
                ->JOIN('JOIN', 'coe_programme K', 'K.coe_programme_id = I.coe_programme_id')
                ->JOIN('JOIN', 'coe_batch L', 'L.coe_batch_id = I.coe_batch_id')
                ->JOIN('JOIN', 'coe_subjects H', 'H.coe_subjects_id = D.subject_id')
                ->JOIN('JOIN', 'coe_category_type E', 'E.coe_category_type_id = A.exam_type')
                ->JOIN('JOIN', 'coe_exam_timetable F', 'F.subject_mapping_id = A.exam_subject_id and F.subject_mapping_id=D.coe_subjects_mapping_id')
                ->where(['A.exam_date' => $exam_date, 'A.exam_session' => $exam_session,'A.exam_year' => $exam_year, 'A.exam_month' => $exam_month,'F.exam_year'=>$exam_year,'F.exam_session'=>$exam_session,'F.exam_month'=>$exam_month,'F.exam_date'=>$exam_date])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->groupBy(['G.register_number', 'subject_code'])
                ->orderBy('G.register_number');
        $command = $query->createCommand();
        $data = $command->queryAll();
       
        if (count($data) > 0 && !empty($data)) {
            $send_results = $data;
        } else {
            $send_results = 0;
        }
        if (isset($_SESSION['absent_list'])) {
            unset($_SESSION['absent_list']);
        }
        $_SESSION['absent_list'] = $send_results;
        
        return Json::encode($send_results);
    }
    public function actionGetdeleteabsentrecord() 
    {
        $ab_id = Yii::$app->request->post('ab_id');
        $getAbDetails = AbsentEntry::findOne($ab_id); 
        if(!empty($getAbDetails))
        {
            $checkMarkEntry = MarkEntryMaster::find()->where(['student_map_id'=>$getAbDetails['absent_student_reg'],'subject_map_id'=>$getAbDetails['exam_subject_id'],'year'=>$getAbDetails['exam_year'],'month'=>$getAbDetails['exam_month'],'mark_type'=>$getAbDetails['exam_type']])->all();
            if(!empty($checkMarkEntry))
            {
                return 'NO';
            }
            else
            {
                $val = AbsentEntry::findModel($ab_id)->delete();
                if($val==1)
                {
                    return 'YES';
                }
                else
                {
                    return 0;
                }
            }
        }
        else
        {
            return 'NOT_FOUND';
        }

        
    }
    public function actionGetablistdelete() 
    {
        $exam_date = date('Y-m-d', strtotime(Yii::$app->request->post('exam_date')));
        $exam_session = Yii::$app->request->post('exam_session');
        $exam_month = Yii::$app->request->post('exam_month');
        $exam_year = Yii::$app->request->post('exam_year');

        $get_exam_ids = ExamTimetable::find()->where(['exam_year'=>$exam_year,'exam_month'=>$exam_month,'exam_date'=>$exam_date,'exam_session'=>$exam_session])->all();
        $subMap_ids = [];
        foreach ($get_exam_ids as $key => $value) 
        {
            $subMap_ids[$value['subject_mapping_id']] = $value['subject_mapping_id'];
        }

        $check_markEntry = MarkEntryMaster::find()->where(['year'=>$exam_year,'month'=>$exam_month])->andWhere(['IN','subject_map_id',$subMap_ids])->andWhere(['NOT LIKE','result','Absent'])->all();
        if(!empty($check_markEntry))
        {
            return '1';
        }
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $query = new Query();
        $query->select(['DATE_FORMAT(A.exam_date,"%d-%m-%Y") as exam_date', 'L.batch_name', "CONCAT(J.degree_code,'',K.programme_code) as programme_degree_name", 'E.category_type as exam_type', 'G.name', 'G.register_number', 'D.semester', 'H.subject_code', 'H.subject_name','coe_absent_entry_id'])
                ->from('coe_absent_entry as A')
                ->JOIN('JOIN', 'coe_student_mapping B', 'B.coe_student_mapping_id = A.absent_student_reg')
                ->JOIN('JOIN', 'coe_student G', 'G.coe_student_id = B.student_rel_id')
                ->JOIN('JOIN', 'coe_subjects_mapping D', 'D.coe_subjects_mapping_id = A.exam_subject_id')
                ->JOIN('JOIN', 'coe_bat_deg_reg I', 'I.coe_bat_deg_reg_id = B.course_batch_mapping_id and I.coe_bat_deg_reg_id = D.batch_mapping_id')
                ->JOIN('JOIN', 'coe_degree J', 'J.coe_degree_id = I.coe_degree_id')
                ->JOIN('JOIN', 'coe_programme K', 'K.coe_programme_id = I.coe_programme_id')
                ->JOIN('JOIN', 'coe_batch L', 'L.coe_batch_id = I.coe_batch_id')
                ->JOIN('JOIN', 'coe_subjects H', 'H.coe_subjects_id = D.subject_id')
                ->JOIN('JOIN', 'coe_category_type E', 'E.coe_category_type_id = A.exam_type')
               ->JOIN('JOIN', 'coe_exam_timetable F', 'F.subject_mapping_id = A.exam_subject_id and F.subject_mapping_id=D.coe_subjects_mapping_id')
                ->where(['A.exam_date' => $exam_date, 'A.exam_session' => $exam_session,'A.exam_year' => $exam_year, 'A.exam_month' => $exam_month,'F.exam_year'=>$exam_year,'F.exam_session'=>$exam_session,'F.exam_month'=>$exam_month,'F.exam_date'=>$exam_date])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->groupBy(['G.register_number', 'subject_code'])
                ->orderBy('G.register_number');
        $command = $query->createCommand();
        $data = $command->queryAll();

        if (count($data) > 0 && !empty($data)) {
            $send_results = $data;
        } else {
            $send_results = 0;
        }
        
        return Json::encode($send_results);
    }

    public function actionAbsemeters() {
        $batch_map_id = Yii::$app->request->post('programme_id_val');
        $query = new Query();
        $query->select(['b.degree_total_years as years', 'b.degree_total_semesters as semesters'])
                ->from('coe_bat_deg_reg a')
                ->leftJoin('coe_degree b', 'a.coe_degree_id = b.coe_degree_id')
                ->leftJoin('coe_programme c', 'a.coe_programme_id = c.coe_programme_id')
                ->where('a.coe_bat_deg_reg_id="' . $batch_map_id . '"');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $sem_type = 0;
        foreach ($data as $key => $value) {
            $sem_type = $data[0]['semesters'];
            $tot_yrs = $data[0]['years'];
            //$batch_name = $data['batch_name']; // for Sem Caluclation Have to work
        }
        $exam_dropdown = $sem_type;
        return Json::encode($exam_dropdown);
    }

    /* Absent Entry Functions Ends Here */
    /* Mark Start */
    /* Internal Mark Start */

    public function actionGetinternalsubjectcode() {
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $sem = Yii::$app->request->post('sem');

        $query = (new \yii\db\Query());
        $query->select("a.coe_subjects_id,a.subject_code")
                ->from('coe_subjects a')
                ->join('JOIN', 'coe_subjects_mapping b', 'b.subject_id = a.coe_subjects_id')
                ->where(['b.semester' => $sem, 'b.batch_mapping_id' => $batch_map_id])
                ->orderBy('a.subject_code');
        $result = $query->createCommand()->queryAll();
        return Json::encode($result);
    }

    public function actionGetverifysemcaluclation() 
    {
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        
        $sem_valc = ConfigUtilities::SemCaluclation($exam_year,$exam_month,$batch_map_id);

        return $sem_valc;
    }
    public function actionGetcheckexamyearbatch() {
        $batch_id = Yii::$app->request->post('global_batch_id');
        $bat_val = Batch::find()->where(['coe_batch_id' => $batch_id])->one();
        return $bat_val->batch_name;
    }

    public function actionGetstudentlist() {
        $checkAccess = ConfigUtilities::HasAccess(Yii::$app->user->getId());
        $changeVals = $checkAccess == "Yes" ? "text" : "hidden";

        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $year = Yii::$app->request->post('year');
        $section = "All";
        $sem = Yii::$app->request->post('sem');
        $sub_id = Yii::$app->request->post('sub_code');
        $mark_type = Yii::$app->request->post('internal');
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $sub_marks = Subjects::find()->where(['coe_subjects_id' => $sub_id])->one();
        if ($sub_marks['ESE_min'] == 0 && $sub_marks['ESE_max'] == 0) 
        {
            $mth_trm_type = Yii::$app->db->createCommand("select exam_month,exam_type,exam_term from coe_subjects_mapping as A,coe_exam_timetable as B where A.coe_subjects_mapping_id=B.subject_mapping_id and A.batch_mapping_id='" . $batch_map_id . "' and B.exam_year='" . $year . "'")->queryOne();
            $degree_name_get = Yii::$app->db->createCommand('SELECT CONCAT(degree_code," ",programme_code) FROM coe_bat_deg_reg as A JOIN coe_degree as B ON B.coe_degree_id=A.coe_degree_id JOIN coe_programme as C ON C.coe_programme_id=A.coe_programme_id where coe_bat_deg_reg_id="'.$batch_map_id.'"')->queryScalar();
            if(empty($mth_trm_type))
            {
                Yii::$app->ShowFlashMessages->setMsg('Error', 'PLEASE CREATE THE <b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' for '.$degree_name_get."</b> IF ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." NOT AVAILABLE USE <b>EXAM NOT AVAILABLE PROPERTY FROM ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." SESSION</b> FOR DUMMY ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." DATE");
                return $this->redirect(['mark-entry/create']);
            }
        }

        $sub_det = Yii::$app->db->createCommand("select B.subject_type_id,B.paper_type_id,B.course_type_id,B.coe_subjects_mapping_id,A.subject_code,A.subject_name,A.CIA_min,A.CIA_max,A.ESE_min,A.ESE_max,A.total_minimum_pass from coe_subjects as A,coe_subjects_mapping as B where A.coe_subjects_id=B.subject_id and B.semester='" . $sem . "' and B.batch_mapping_id='" . $batch_map_id . "' and B.subject_id='" . $sub_id . "'")->queryOne();
        $cat_subject_type = Categorytype::find()->where(['coe_category_type_id' => $sub_det['subject_type_id']])->one();
        $cat_paper_type = Categorytype::find()->where(['coe_category_type_id' => $sub_det['paper_type_id']])->one();

        $table = '';
        $sn = 1;

        $mainMarkEntryCheck = Yii::$app->db->createCommand("select * from coe_student as A,coe_student_mapping as B,coe_mark_entry_master as C where A.coe_student_id=B.student_rel_id and B.coe_student_mapping_id=C.student_map_id and  C.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') ")->queryOne();
        //$mainMarkEntryCheck = MarkEntryMaster::findOne(['subject_map_id'=>$sub_det['coe_subjects_mapping_id']]);
        $makeReadOnly = $checkAccess == "Yes" ? '' : 'disabled=true';

        $det_cat_val = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like 'detain%' or category_type='Detain'")->queryScalar();
        $get_cat_type = Categorytype::find()->where(['category_type' => 'Detain'])->orWhere(['category_type' => 'Detain/Debar'])->one();
        $get_disc_type = Categorytype::find()->where(['category_type' => 'Discontinued'])->one();
        
        $join_condition = 'and B.status_category_type_id !="' . $get_cat_type->coe_category_type_id . '" and B.status_category_type_id !="' . $get_disc_type->coe_category_type_id . '" ';

        $common_student = Yii::$app->db->createCommand("select A.name,A.register_number,B.coe_student_mapping_id from coe_student as A,coe_student_mapping as B where A.coe_student_id=B.student_rel_id and B.course_batch_mapping_id='" . $batch_map_id . "' and A.student_status='Active' " . $join_condition . " and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') order by A.register_number asc")->queryAll();
        $elective_student = Yii::$app->db->createCommand("select B.coe_student_mapping_id,C.name,C.register_number from coe_student_mapping as B,coe_nominal as D,coe_student as C where B.course_batch_mapping_id=D.course_batch_mapping_id and C.coe_student_id=B.student_rel_id and D.semester='" . $sem . "' and D.coe_subjects_id='" . $sub_id . "' and B.course_batch_mapping_id='" . $batch_map_id . "' and B.student_rel_id=D.coe_student_id and C.student_status='Active' " . $join_condition . " and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."')  order by C.register_number asc")->queryAll();

        if ($cat_subject_type->category_type == "Elective") {
            $internal_stu_count = $elective_student;
        } else {
            $internal_stu_count = $common_student;
        }
        if (count($internal_stu_count) > 0) 
        {
            $table .= "<table border=1 class='table table-striped table-responsive' width='100%'>
                      <tr>
                        <th colspan=5 style='text-align: center; font-size: 18px; color: #007bff; letter-spacing: 0.5px;'>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " Information</th>
                      </tr>
                      <tr>
                        <th>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " Code</th>
                        <th>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " Name</th>
                        <th>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT_TYPE) . "</th>
                        <th>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME_TYPE) . "</th>
                        <th>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PAPER_TYPE) . "</th>
                      </tr>
                      <tr>
                        <td>" . $sub_det['subject_code'] . "</td>
                        <td>" . $sub_det['subject_name'] . "</td>
                        <td>" . $this->valueReplace($sub_det['subject_type_id'], Categorytype::getCategoryIdName()) . "</td>
                        <td>" . $this->valueReplace($sub_det['course_type_id'], Categorytype::getCategoryIdName()) . "</td>
                        <td>" . $this->valueReplace($sub_det['paper_type_id'], Categorytype::getCategoryIdName()) . "</td>
                      </tr>
                      <tr>
                        <th>CIA MIN</th>
                        <th>CIA MAX</th>
                        <th>ESE MIN</th>
                        <th>ESE MAX</th>
                        <th>TOTAL PASS</th>
                      </tr>
                      <tr>
                        <td>" . $sub_det['CIA_min'] . "</td>
                        <td>" . $sub_det['CIA_max'] . "</td>
                        <td>" . $sub_det['ESE_min'] . "</td>
                        <td>" . $sub_det['ESE_max'] . "</td>
                        <td>" . $sub_det['total_minimum_pass'] . "</td>
                      </tr>
                    </table>";

            $table .= '<table id="checkAllFeat" class="table table-striped" align="right" border=1>     
                    <thead id="t_head">                                                                                                               
                      <th> S.NO </th> 
                      <th> Register Number </th>  
                      <th> Name </th>';
            if ($cat_paper_type->category_type == "Practical") {
                $table .= '<th> Marks </br>out of ' . $sub_det['CIA_max'] . ' </th>';
            } else {
                $table .= '<th> Marks </br>out of ' . $sub_det['CIA_max'] . ' </th>';
            }
            $table .= '<th> Attendance(%)</th>
                      <th> Remarks </th>
                      </thead><tbody>';

            foreach ($internal_stu_count as $student) {
                $mark_entry = MarkEntry::find()->where(['subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'student_map_id' => $student['coe_student_mapping_id'], 'category_type_id' => $mark_type])->one();

                $table .= '<tr>' .
                        '<td><input type="hidden" name="sn" value=' . $sn . '>' . $sn . '</td> ' .
                        '<td><input type="hidden" id="reg_attendance_percentage_' . $sn . '" name=reg' . $sn . ' value="' . $student['register_number'] . '" >' . $student['register_number'] . '</td>' .
                        '<td><input type="hidden" value=' . $student['name'] . '>' . $student['name'] . '</td>';
                $sub_map_ese_check_id = SubjectsMapping::findOne($sub_det['coe_subjects_mapping_id']);
                $check_sub_ese_max = Subjects::findOne($sub_map_ese_check_id->subject_id);

                $value = count($mark_entry) > 0 ? $mark_entry['category_type_id_marks'] : '';
                if ($check_sub_ese_max->ESE_max == 0 && $check_sub_ese_max->ESE_min == 0 && $check_sub_ese_max->CIA_min == 0 && $check_sub_ese_max->CIA_max == 0) {
                    $value = 0;
                }

                $attendance_value = count($mark_entry) > 0 ? $mark_entry['attendance_percentage'] : ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT_STATUS);
                $attendance_remark_value = count($mark_entry) > 0 ? $mark_entry['attendance_remarks'] : 'Allowed';

                $table .= '<td><input required class="mark_txt" onchange="checkMax(this.id)" id=mark_' . $sn . ' name=mark' . $sn . ' size="1px" title="Enter Numbers only" pattern="\d*" onkeypress="numbersOnly(event); autocomplete="off" allowEntr(event,this.id);" type="' . $changeVals . '" maxlength="3" value="' . $value . '" ' . $makeReadOnly . ' style="width: 100% !important;"> </td>';

                $add_css = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT_STATUS) > $attendance_value ? "color: #F00;" : "color: #000;";

                $table .= '<td><input autocomplete="off" onkeypress="numbersOnly(event); allowEntr(event,this.id);" onchange="AttendancePercentage(this.id)" id=attendance_percentage_' . $sn . ' name=attendance_percentage' . $sn . ' size="1px" pattern="[0-9]+(\.[0-9]{0,2})?%?" pattern="\d*" title="This must be a number with up to 2 decimal places and/or %" maxlength="5" type="' . $changeVals . '"  value="' . $attendance_value . '" ' . $makeReadOnly . ' style="' . $add_css . ' width: 100% !important;"> </td>';
                $table .= '<td><input onkeypress="allowEntr(event,this.id);" onchange="AttendanceRemarks(this.id)" id=remark_attendance_percentage_' . $sn . ' name=attendance_remark' . $sn . ' size="1px" pattern="[A-Za-z]+" readonly=readonly title="This must be a letters" maxlength="5" type="' . $changeVals . '"  value="' . $attendance_remark_value . '" ' . $makeReadOnly . ' style="' . $add_css . ' width: 100% !important;"> </td>';

                $table .= '</tr>';
                $sn++;
            }
            $table .= "</tbody></table>";
        } else {
            $table .= "No";
        }
        //}
        //else
        //{
        //$table.="No";
        //}
        $data = ['table' => $table, 'cia_max' => $sub_det['CIA_max'], 'sn1' => $sn - 1, 'attendance_per' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT_STATUS)];
        return Json::encode($data);
    }

    /* Internal mark End */

    /* External mark start */

    public function actionGetmarksubjectcode() {
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $sem = Yii::$app->request->post('sem');
        $type = Yii::$app->request->post('type');
        $cat_mark_type = Categorytype::find()->where(['coe_category_type_id' => $type])->one();
        if ($cat_mark_type->category_type != "Arrear") {
            $query = (new \yii\db\Query());
            $query->select("a.coe_subjects_id,a.subject_code")
                    ->from('coe_subjects a')
                    ->join('JOIN', 'coe_subjects_mapping b', 'b.subject_id = a.coe_subjects_id')
                    ->where(['b.semester' => $sem, 'b.batch_mapping_id' => $batch_map_id])
                    ->orderBy('a.subject_code');
            $result = $query->createCommand()->queryAll();
        }//regular
        else {

            $result = Yii::$app->db->createCommand("select distinct C.coe_subjects_id,C.subject_code from coe_subjects_mapping as A,coe_mark_entry_master as B,coe_subjects as C where A.subject_id=C.coe_subjects_id and A.batch_mapping_id='" . $batch_map_id . "' and A.coe_subjects_mapping_id=B.subject_map_id and (year_of_passing='' OR year_of_passing is null ) and A.semester='" . $sem . "'")->queryAll();
        }//arrear
        return Json::encode($result);
    }

    public function actionGetmarksubjectcodedetails() 
    {
        $subjects_details = Subjects::findOne(['coe_subjects_id' => Yii::$app->request->post('sub_code')]);
        if (!empty($subjects_details)) {
            return Json::encode($subjects_details);
        } else {
            $subjects_details = 0;
            return Json::encode($subjects_details);
        }
    }

    public function actionGetmarksubjecttype() {
        $mark_sub_id = Yii::$app->request->post('mark_sub_code');
        $bat_map_id = Yii::$app->request->post('bat_map_id');

        $sub_type = Yii::$app->db->createCommand("select A.subject_code,A.subject_name,C.category_type from coe_subjects as A,coe_subjects_mapping as B,coe_category_type as C where A.coe_subjects_id=B.subject_id and B.paper_type_id=C.coe_category_type_id and A.coe_subjects_id='" . $mark_sub_id . "' and B.batch_mapping_id='".$bat_map_id."' and C.description like 'practical%'")->queryAll();
        
        if (count($sub_type) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function actionGetmodtype()
    {
        $sub_type = Yii::$app->db->createCommand("select * from coe_subjects as A,coe_subjects_mapping as B,coe_mark_entry_master as C where A.coe_subjects_id=B.subject_id and B.coe_subjects_mapping_id=C.subject_map_id and A.coe_subjects_id='".$_POST['sub_id']."' and B.batch_mapping_id='".$_POST['batch_map_id']."' and C.year='".$_POST['year']."' and C.month='".$_POST['month']."' and C.term='".$_POST['term']."' and C.mark_type='".$_POST['type']."'")->queryAll();
        if (count($sub_type) > 0) 
        {
            return 1;
        } 
        else 
        {
            return 0;
        }
    }

    public function actionGetesesubjectinformation()
    {
        $sub_id = Yii::$app->request->post('sub_id');
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $mod_1 = Yii::$app->request->post('mod_1');
        $mod_2 = Yii::$app->request->post('mod_2');

        $getEse = Yii::$app->db->createCommand("SELECT ESE_max FROM coe_subjects_mapping as A JOIN coe_subjects as B ON B.coe_subjects_id=A.subject_id WHERE A.subject_id='".$sub_id."' AND A.batch_mapping_id='".$batch_map_id."' ")->queryScalar();

        $model_sum = $mod_1+$mod_2;

        if($getEse == $model_sum)
        {
            return 5;
        }
        else
        {
            return 0;
        }

        
    }

    public function actionGetesestudentlist() 
    {
        $checkAccess = ConfigUtilities::HasAccess(Yii::$app->user->getId());
		$changeVals = $checkAccess == "Yes" ? "text" : "hidden";
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
		$makeReadOnly = $checkAccess == "Yes" ? '' : 'disabled=true';
        $year = Yii::$app->request->post('year');
        $batch = Yii::$app->request->post('batch');
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $month = Yii::$app->request->post('month');
        $term = Yii::$app->request->post('term');
        $type = Yii::$app->request->post('type');
        $section = "All";
        $sem = Yii::$app->request->post('sem');
        $sub_id = Yii::$app->request->post('sub_code');
        $model_type = Yii::$app->request->post('model_type');
        $mod_1 = Yii::$app->request->post('mod_1');
        $mod_2 = Yii::$app->request->post('mod_2');

        $get_subject_mapping_id = SubjectsMapping::find()->where(['subject_id'=>$sub_id,'batch_mapping_id'=>$batch_map_id,'semester'=>$sem])->one();

        if ($model_type == "With Model") 
        {
            $sub_max = Subjects::findOne($sub_id);
            $max_entered = $mod_1+$mod_2;
            if($max_entered==$sub_max->ESE_max)
            {
                if($sub_max->ESE_max==0 && $sub_max->ESE_min==0)
                {
                    return 0;
                }
            }
            else
            {
                return 0;
            }
        }

        $regular_arrear = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='" . $type . "'")->queryScalar(); 
        $sem_verify = ConfigUtilities::SemCaluclation($year,$month,$batch_map_id);

        if(($sem_verify==$sem && $regular_arrear=="Regular" ) || ($regular_arrear=="Arrear" && $sem!=$sem_verify))
        {

        }        
        else{

            return 0;
        }

        $sub_det = Yii::$app->db->createCommand("select B.subject_type_id,B.paper_type_id,B.course_type_id,B.coe_subjects_mapping_id,A.subject_code,A.subject_name,A.CIA_min,A.CIA_max,A.ESE_min,A.ESE_max,A.total_minimum_pass from coe_subjects as A,coe_subjects_mapping as B where A.coe_subjects_id=B.subject_id and B.semester='" . $sem . "' and B.batch_mapping_id='" . $batch_map_id . "' and B.subject_id='" . $sub_id . "'")->queryOne();

        $det_cat_val = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Detain%' ")->queryScalar();
        $cat_cia_val = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like 'cia 1%' or category_type like 'Internal%'")->queryScalar();
        $det_disc_val = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%' ")->queryScalar();

        $check_int_marks = MarkEntry::find()->where(['category_type_id'=>$cat_cia_val,'subject_map_id'=>$get_subject_mapping_id['coe_subjects_mapping_id']])->orderBy('coe_mark_entry_id desc')->all();
        if(empty($check_int_marks))
        {
             $data = ['table' => "No"];
            return Json::encode($data);
        }

        $cat_model1_ese_val = Yii::$app->db->createCommand("select coe_category_type_id,description from coe_category_type where category_type like '%Model 1%' or category_type like '%model1%'")->queryOne();
        $cat_model2_ese_val = Yii::$app->db->createCommand("select coe_category_type_id,description from coe_category_type where category_type like 'model 2%' or category_type like 'model2%'")->queryOne();

        $cat_rev_mark_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where description like 'revaluation%'")->queryScalar();
        $cat_mod_mark_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where description like 'moderation%'")->queryScalar();
        $cat_ese_mark_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where description like 'ESE%' ")->queryScalar();
        $cat_ese_dummy_mark_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where description like '%ese(Dummy)%' ")->queryScalar();

        
        $get_cat_type = Categorytype::find()->where(['category_type' => 'Detain'])->orWhere(['category_type' => 'Detain/Debar'])->one();
        $get_lateral_type = Categorytype::find()->where(['category_type' => 'Lateral Entry'])->one();
		$get_cat = Categorytype::find()->where(['category_type' => 'Discontinued'])->one();

        if ($sem <= 2) 
        {
            $join_condition = 'and B.status_category_type_id NOT IN("' . $get_cat_type->coe_category_type_id . '","' . $get_cat->coe_category_type_id . '","' . $get_lateral_type->coe_category_type_id . '")  ';
        } 
        else 
        {
            $join_condition = ' ';
        }

        $internal_stu_count = Yii::$app->db->createCommand("select count(DISTINCT student_map_id) as count from coe_mark_entry as C JOIN coe_student_mapping as B ON B.coe_student_mapping_id=C.student_map_id JOIN coe_student as A ON A.coe_student_id=B.student_rel_id where C.year='" . $year . "' and C.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and C.category_type_id_marks is not null and C.category_type_id='" . $cat_cia_val . "' and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') and A.student_status='Active' " . $join_condition . " order by A.register_number asc ")->queryScalar();
        $common_student = Yii::$app->db->createCommand("select B.coe_student_mapping_id,B.student_rel_id,A.register_number,A.name from coe_student as A,coe_student_mapping as B where A.coe_student_id=B.student_rel_id and B.course_batch_mapping_id='" . $batch_map_id . "' and B.status_category_type_id NOT IN('" . $det_cat_val . "','".$det_disc_val."') and A.student_status='Active' " . $join_condition . " order by A.register_number asc")->queryAll();
        $elective_student = Yii::$app->db->createCommand("select B.coe_student_mapping_id,B.student_rel_id,C.register_number,C.name from coe_student_mapping as B,coe_nominal as D,coe_student as C where B.course_batch_mapping_id=D.course_batch_mapping_id and  C.coe_student_id=B.student_rel_id and D.semester='" . $sem . "' and D.coe_subjects_id='" . $sub_id . "' and B.course_batch_mapping_id='" . $batch_map_id . "' and B.student_rel_id=D.coe_student_id and status_category_type_id NOT IN('" . $det_cat_val . "','".$det_disc_val."') and C.student_status='Active' " . $join_condition . " group By C.register_number order by C.register_number asc")->queryAll();
        
		if ($elective_student) 
        {
            $internal_entered = $elective_student;
        } 
        else 
        {
            $internal_entered = $common_student;
        }
        
        $table = '';
        $table_heading = '';
        $data = '';
        $sn = 1;

        $c_paper_type = Categorytype::find(['category_type'])->where(['coe_category_type_id' => $sub_det['paper_type_id']])->one();

        $table_heading .= "<table border=1 class='table table-striped table-responsive' width='100%'>" .
                "<tr>" .
                "<th colspan=5 style='text-align: center; font-size: 18px; color: #007bff; letter-spacing: 0.5px;'>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " Information</th>" .
                "</tr>" .
                "<tr>" .
                "<th>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " Code</th>" .
                "<th>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " Name</th>" .
                "<th>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT_TYPE) . "</th>" .
                "<th>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME_TYPE) . "</th>" .
                "<th>" . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PAPER_TYPE) . "</th>" .
                "</tr>" .
                "<tr>" .
                "<td>" . $sub_det['subject_code'] . "</td>" .
                "<td>" . $sub_det['subject_name'] . "</td>" .
                "<td>" . $this->valueReplace($sub_det['subject_type_id'], Categorytype::getCategoryIdName()) . "</td>" .
                "<td>" . $this->valueReplace($sub_det['course_type_id'], Categorytype::getCategoryIdName()) . "</td>" .
                "<td>" . $this->valueReplace($sub_det['paper_type_id'], Categorytype::getCategoryIdName()) . "</td>" .
                "</tr>" .
                "<tr>" .
                "<th>CIA MIN</th>" .
                "<th>CIA MAX</th>" .
                "<th>ESE MIN</th>" .
                "<th>ESE MAX</th>" .
                "<th>TOTAL PASS</th>" .
                "</tr>" .
                "<tr>" .
                "<td>" . $sub_det['CIA_min'] . "</td>" .
                "<td>" . $sub_det['CIA_max'] . "</td>" .
                "<td>" . $sub_det['ESE_min'] . "</td>" .
                "<td>" . $sub_det['ESE_max'] . "</td>" .
                "<td>" . $sub_det['total_minimum_pass'] . "</td>" .
                "</tr>" .
                "</table>";

        $mark_entry_master = MarkEntryMaster::find(['CIA', 'ESE', 'total', 'result'])->where(['subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'month' => $month, 'term' => $term, 'mark_type' => $type, 'year' => $year])->all();


        $mod1_mark_type = Yii::$app->db->createCommand("select mark_out_of from coe_subjects as A,coe_subjects_mapping as B,coe_mark_entry as C where A.coe_subjects_id=B.subject_id and B.coe_subjects_mapping_id=C.subject_map_id and C.category_type_id = '".$cat_model1_ese_val['coe_category_type_id']."' and A.coe_subjects_id ='".$sub_id."' and B.batch_mapping_id='".$batch_map_id."' and C.month = '".$month."' and C.term = '".$term."' and C.mark_type = '".$type."' and C.year = '".$year."'")->queryOne();


        $mod2_mark_type = Yii::$app->db->createCommand("select mark_out_of from coe_subjects as A,coe_subjects_mapping as B,coe_mark_entry as C where A.coe_subjects_id=B.subject_id and B.coe_subjects_mapping_id=C.subject_map_id and C.category_type_id = '".$cat_model2_ese_val['coe_category_type_id']."' and A.coe_subjects_id ='".$sub_id."' and B.batch_mapping_id='".$batch_map_id."' and C.month = '".$month."' and C.term = '".$term."' and C.mark_type = '".$type."' and C.year = '".$year."'")->queryOne();

    
        $table_heading .= '<table id="checkAllFeat" class="table table-striped" align="right" border=1>     
                <thead id="t_head">                                                                                                               
                  <th> S.NO </th> 
                  <th> Register Number </th>  
                  <th> Name </th>';

        if ($c_paper_type->category_type != "Practical") 
        {
            $table_heading .= ' <th> Apeared </th>
                        <th> CIA </th>
                        <th> Marks </br>out of 100 </th>
                        <th> Marks </br>out of ' . $sub_det['ESE_max'] . ' </th>';
        } 
        else 
        {
            $table_heading .= '<th> CIA </th>';

            if ($model_type == "With Model") 
            {
                $table_heading .= '<th> Model 1 </th>';
                if(count($mark_entry_master)>0)
                {
                    $table_heading .='<th> Marks </br> out of '.$mod1_mark_type['mark_out_of'].' </th>';
                }
                else
                {
                    $table_heading .='<th> Marks </br> out of ' . $mod_1 . '</th>';
                }
                $table_heading .='<th> Model 2 </th>';
                if(count($mark_entry_master)>0)
                {
                    $table_heading .='<th> Marks </br> out of '.$mod2_mark_type['mark_out_of'].'</th>';
                }
                else
                {
                    $table_heading .='<th> Marks </br> out of ' . $mod_2 . '</th>';
                }
                if(count($mark_entry_master)>0)
                {
                    $table_heading .= '<th> Marks </br>out of ' . $sub_det['ESE_max'] . ' </th>';
                }
                else
                {
                    $mod = $mod_1 + $mod_2;
                    if($mod==$sub_det['ESE_max'])
                    {
                        $table_heading .= '<th> Marks </br>out of ' . $sub_det['ESE_max'] . ' </th>';
                    }
                    else
                    {
                        $table_heading .= '<th><b><font style="color:red;size:18px">Please Check Out of Marks</font></b></th>';
                    }
                }
            } 
            else 
            {
                if ($sub_det['ESE_min'] == 0 && $sub_det['ESE_max'] == 0) 
                {
                    $table_heading .= '<th> Marks </br>out of 100 </th>';
                    $table_heading .= '<th> Marks </br>out of ' . $sub_det['ESE_max'] . ' </th>';

                } 
                else 
                {
                    $table_heading .= '<th> Marks </br>out of 100 </th>';
                    $table_heading .= '<th> Marks </br>out of ' . $sub_det['ESE_max'] . ' </th>';
                }
            }
           
        }
        $table_heading .= '<th> Total </th>' .
                '<th> Result </th>' .
                '</thead><tbody>';

        //Regular
		
        if ($regular_arrear == "Regular") 
        {
            
            $table .= $table_heading;
            foreach ($internal_entered as $stu_map1) 
            {
               
                $mark_entry_master = MarkEntryMaster::find(['CIA', 'ESE', 'total', 'result'])->where(['subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'student_map_id' => $stu_map1['coe_student_mapping_id'], 'month' => $month, 'term' => $term, 'mark_type' => $type, 'year' => $year])->orderBy('coe_mark_entry_master_id desc')->one();
                $mark_entry_cia = MarkEntry::find(['category_type_id_marks'])->where(['subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'student_map_id' => $stu_map1['coe_student_mapping_id'], 'category_type_id' => $cat_cia_val])->orderBy('coe_mark_entry_id desc')->one();
                $mark_entry_rev = MarkEntry::find(['category_type_id_marks'])->where(['subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'student_map_id' => $stu_map1['coe_student_mapping_id'], 'month' => $month, 'term' => $term, 'mark_type' => $type, 'year' => $year, 'category_type_id' => $cat_rev_mark_type])->one();

                $check_dummy_generated = DummyNumbers::find()->where(['subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'student_map_id' => $stu_map1['coe_student_mapping_id'], 'month' => $month, 'year' => $year])->all();

                $mark_entry_mod = MarkEntry::find(['category_type_id_marks'])->where(['subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'student_map_id' => $stu_map1['coe_student_mapping_id'], 'month' => $month, 'term' => $term, 'mark_type' => $type, 'year' => $year, 'category_type_id' => $cat_mod_mark_type])->one();

                if (!empty($check_dummy_generated)) 
                {
                    $mark_entry_ese = Yii::$app->db->createCommand("select * from coe_mark_entry as A,coe_dummy_number as B where A.student_map_id=B.student_map_id and A.subject_map_id=B.subject_map_id and A.year=B.year and A.month=B.month and A.year='" . $year . "' and A.month='" . $month . "' and A.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and A.student_map_id='" . $stu_map1['coe_student_mapping_id'] . "' and A.term='" . $term . "' and A.mark_type='" . $type . "' and category_type_id='" . $cat_ese_dummy_mark_type . "' ")->queryOne();
                    if(empty($mark_entry_ese))
                    {
                        $mark_entry_ese = Yii::$app->db->createCommand("select * from coe_mark_entry as A where  
                        A.year='" . $year . "' and A.month='" . $month . "' and
                         A.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and 
                         A.student_map_id='" . $stu_map1['coe_student_mapping_id'] . "' and 
                         A.term='" . $term . "' and A.mark_type='" . $type . "' and 
                         category_type_id='" . $cat_ese_mark_type . "' ")->queryOne();
                    }
                } 
                else 
                {
                    $mark_entry_ese = Yii::$app->db->createCommand("select * from coe_mark_entry as A where  
                    A.year='" . $year . "' and A.month='" . $month . "' and
                     A.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and 
                     A.student_map_id='" . $stu_map1['coe_student_mapping_id'] . "' and 
                     A.term='" . $term . "' and A.mark_type='" . $type . "' and 
                     category_type_id='" . $cat_ese_mark_type . "' ")->queryOne();
                }
                $model1_mark_entry = MarkEntry::find(['category_type_id_marks'])->where(['category_type_id' => $cat_model1_ese_val['coe_category_type_id'], 'subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'student_map_id' => $stu_map1['student_rel_id'], 'month' => $month, 'term' => $term, 'mark_type' => $type, 'year' => $year])->one();
                $model2_mark_entry = MarkEntry::find(['category_type_id_marks'])->where(['category_type_id' => $cat_model2_ese_val['coe_category_type_id'], 'subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'student_map_id' => $stu_map1['student_rel_id'], 'month' => $month, 'term' => $term, 'mark_type' => $type, 'year' => $year])->one();

                $absent = Yii::$app->db->createCommand("select absent_student_reg from coe_absent_entry where absent_student_reg='" . $stu_map1['coe_student_mapping_id'] . "' and exam_type='" . $type . "' and absent_term='" . $term . "' and exam_month='".$month."' and exam_subject_id='" . $sub_det['coe_subjects_mapping_id'] . "' and exam_year='".$year."' ")->queryScalar();
                if ($absent) 
                {
                    $abs_type = "Absent";
                } 
                else 
                {
                    $abs_type = "Present";
                }

                $table .= '<tr>' .
                        '<td><input type="hidden" name="sn" value=' . $sn . '>' . $sn . '</td> ' .
                        '<td><input type="hidden" name=reg' . $sn . ' value=' . $stu_map1['register_number'] . '>' . $stu_map1['register_number'] . '</td>' .
                        '<td><input type="hidden" value=' . $stu_map1['name'] . '>' . $stu_map1['name'] . '</td>';
                //Mark entered
                $mark_entry = Yii::$app->db->createCommand("select * from coe_mark_entry as A where  
                    A.year='" . $year . "' and A.month='" . $month . "' and
                     A.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and 
                     A.student_map_id='" . $stu_map1['coe_student_mapping_id'] . "' and 
                     A.term='" . $term . "' and A.mark_type='" . $type . "' and 
                     category_type_id='" . $cat_ese_mark_type . "' ")->queryOne();

                if (count($mark_entry_master) > 0) 
                { 
                    $add_css1 = $mark_entry_master['result'] == "Pass" ? "color: #000;" : "color: #F00;";

                    if ($c_paper_type->category_type != "Practical") 
                    {
                        $table .= '<td><input type="hidden" name=apeared' . $sn . ' value=' . $abs_type . '>' . $abs_type . '</td>' .
                                '<td><input type="hidden" value=' . $mark_entry_master['CIA'] . '> ' . $mark_entry_master['CIA'] . '</td>';

                        if (count($mark_entry_rev) > 0) 
                        {
                            $final_rev_value = $mark_entry_rev['category_type_id_marks'] > $mark_entry['category_type_id_marks'] ? $mark_entry_rev['category_type_id_marks'] : $mark_entry['category_type_id_marks'];
                            $table .= '<td style="border: 1px solid #5dc617; "><input title="Revaluation"  type="hidden" value=' . $final_rev_value . '> ' . $final_rev_value . '</td>';
                        } 
                        else if (count($mark_entry_mod) > 0) 
                        {
                            $final_moderation_marks = $mark_entry['category_type_id_marks'] + $mark_entry_mod['category_type_id_marks'];
                            $table .= '<td style="border: 1px solid #992215; "><input title="Moderation" type="hidden" value=' . $final_moderation_marks . '> ' . $final_moderation_marks . '</td>';
                        } 
                        else 
                        {
                            $table .= '<td><input type="hidden" value=' . $mark_entry_ese['category_type_id_marks'] . '> ' . $mark_entry_ese['category_type_id_marks'] . '</td>';
                        }

                        $table .= '<td><input type="hidden" value=' . $mark_entry_master['ESE'] . '> ' . $mark_entry_master['ESE'] . '</td>' .
                                '<td><input type="hidden" value=' . $mark_entry_master['total'] . '> ' . $mark_entry_master['total'] . '</td>';
                        $table .= '<td style="' . $add_css1 . '"><input type="hidden" value=' . $mark_entry_master['result'] . '> ' . $mark_entry_master['result'] . '</td>';
                    } 
                    else 
                    {
                        $table .= '<td><input type="hidden" value=' . $mark_entry_master['CIA'] . '> ' . $mark_entry_master['CIA'] . '</td>';

                        if ($model_type == "With Model") 
                        {

                            $table .= '<td><input type="hidden" value=' . $model1_mark_entry['category_type_id_marks'] . '> ' . $model1_mark_entry['category_type_id_marks'] . '</td>';
                            $ese_15 = round((($model1_mark_entry['category_type_id_marks'] / 100) * $cat_model1_ese_val['description']));
                            $table .= '<td><input type="hidden" value=' . $ese_15 . '> ' . $ese_15 . '</td>' .
                                    '<td><input type="hidden" value=' . $model2_mark_entry['category_type_id_marks'] . '> ' . $model2_mark_entry['category_type_id_marks'] . '</td>';
                            $ese_25 = round((($model2_mark_entry['category_type_id_marks'] / 100) * $cat_model2_ese_val['description']));
                            $table .= '<td><input type="hidden" value=' . $ese_25 . '> ' . $ese_25 . '</td>';
                        } 
                        else 
                        {

                            if ($sub_det['ESE_min'] == 0 && $sub_det['ESE_max'] == 0) 
                            {

                            } 
                            else 
                            {
                                $table .= '<td><input type="hidden" value=' . $mark_entry_master['ESE'] . '> ' . $mark_entry_master['ESE'] . '</td>';
                            }
                        }

                        if ($sub_det['ESE_min'] == 0 && $sub_det['ESE_max'] == 0) 
                        {

                        } 
                        else 
                        {
                            $table .= '<td><input type="hidden" value=' . $mark_entry_master['ESE'] . '> ' . $mark_entry_master['ESE'] . '</td>';
                        }

                        $table .= '<td><input type="hidden" value=' . $mark_entry_master['total'] . '> ' . $mark_entry_master['total'] . '</td>' .
                                '<td style="' . $add_css1 . '"><input type="hidden" value=' . $mark_entry_master['result'] . '>' . $mark_entry_master['result'] . '</td>';
                    }
                }
                //Mark Not Entered
                else 
                {
                    
                    if ($c_paper_type->category_type != "Practical") 
                    {
                        if ($absent) 
                        {
                            $table .= '<td><input type="hidden" name=apeared' . $sn . ' value=' . $abs_type . '>' . $abs_type . '</td>' .
                                    '<td><input type="hidden" id=cia_' . $sn . ' name=cia' . $sn . ' value=' . $mark_entry_cia['category_type_id_marks'] . '> ' . $mark_entry_cia['category_type_id_marks'] . '</td>' .
                                    '<td><input id=mark_' . $sn . ' name=mark' . $sn . ' size="2px" type="hidden" value="0" style="width: 100% !important;"> 0 </td>' .
                                    '<td><input id=converted_marks_' . $sn . ' name=converted_marks_' . $sn . ' size="2px" type="hidden" value="0" style="width: 100% !important;"> 0 </td>' .
                                    '<td><input id=total_' . $sn . ' name=total' . $sn . ' size="2px" type="hidden" style="width: 100% !important;" value=' . $mark_entry_cia['category_type_id_marks'] . '> ' . $mark_entry_cia['category_type_id_marks'] . ' </td>' .
                                    '<td><input id=result_' . $sn . ' name=result' . $sn . ' size="2px" type="hidden" style="width: 100% !important;" value=' . $abs_type . '> ' . $abs_type . '</td>';
                        } 
                        else 
                        {

                            $get_sb_det = SubjectsMapping::findOne($sub_det['coe_subjects_mapping_id']);
                            $subject_details_get = Subjects::findOne($get_sb_det->subject_id);

                            if($subject_details_get->CIA_max==0 && $subject_details_get->ESE_max==0 && $subject_details_get->CIA_min==0 && $subject_details_get->ESE_min==0)
                            {
                                $table .= '<td><input type="hidden" name=apeared' . $sn . ' value=' . $abs_type . '>' . $abs_type . '</td>' .
                                    '<td><input type="hidden" id=cia_' . $sn . ' name=cia' . $sn . ' value=0 > 0 </td>' .
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" required class="mark_txt1" onchange="checkeseMax(this.id)" id=mark_' . $sn . ' name=mark' . $sn . ' size="2px" title="Enter Numbers only" pattern="\d*" type="text" value="0"  maxlength="3" style="width: 100% !important;"> </td>' .
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" class="mark_txt2" value="0"  id=converted_marks_' . $sn . ' name=converted_marks_' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                            $table .= '<td><input value="0"  id=total_' . $sn . ' name=total' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly> </td>' .
                                    '<td><input value="Pass"  id=result_' . $sn . ' name=result' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                            }
                            else
                            {
                                $table .= '<td><input type="hidden" name=apeared' . $sn . ' value=' . $abs_type . '>' . $abs_type . '</td>' .
                                    '<td><input type="hidden" id=cia_' . $sn . ' name=cia' . $sn . ' value=' . $mark_entry_cia['category_type_id_marks'] . '> ' . $mark_entry_cia['category_type_id_marks'] . '</td>' .
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" required class="mark_txt1" onchange="checkeseMax(this.id)" id=mark_' . $sn . ' name=mark' . $sn . ' size="2px" title="Enter Numbers only" pattern="\d*" type="text" maxlength="3" style="width: 100% !important;"> </td>' .
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" class="mark_txt2" id=converted_marks_' . $sn . ' name=converted_marks_' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                            $table .= '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=total_' . $sn . ' name=total' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly> </td>' .
                                    '<td><input  id=result_' . $sn . ' name=result' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                            }

                        
                        }
                    } 
                    else 
                    {

                        $table .= '<td><input type="hidden" id=cia_' . $sn . ' name=cia' . $sn . ' value=' . $mark_entry_cia['category_type_id_marks'] . '> ' . $mark_entry_cia['category_type_id_marks'] . '</td>';

                        if ($model_type == "With Model") 
                        {

                            $table .= '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" required class="mark_txt1" onchange="checkesemax1(this.id)" id=mark_' . $sn . ' name=mark' . $sn . ' size="2px" title="Enter Numbers only" pattern="\d*" type="text" maxlength="3" style="width: 100% !important;"> </td>' .
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=mark15_' . $sn . ' name=mark15' . $sn . ' size="2px" onchange="checkeseMax(this.id)" type="text" style="width: 100% !important;" readonly> </td>' .
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" required class="mark_txt2" onchange="checkesemax2(this.id)" id=mark1_' . $sn . ' name=mark1' . $sn . ' size="2px" title="Enter Numbers only" pattern="\d*" type="text" maxlength="3" style="width: 100% !important;"> </td>' .
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=mark25_' . $sn . ' name=mark25' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                        } 
                        else 
                        {

                            if ($sub_det['ESE_min'] == 0) 
                            {
                                $table .= '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" required class="mark_txt1" onchange="checkeseMax(this.id)" id=mark_' . $sn . ' name=mark' . $sn . ' size="2px" title="Enter Numbers only" pattern="\d*" type="text" maxlength="3" style="width: 100% !important;" value="0"> </td>';
                                
                                $table .= '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" readonly class="mark_txt3" id=converted_marks_' . $sn . ' name=converted_marks_' . $sn . ' size="2px" type="text" size="2px" style="width: 100% !important;" value="0"> </td>';

                                $result_calc = ConfigUtilities::StudentResult($stu_map1['coe_student_mapping_id'],$sub_det['coe_subjects_mapping_id'],$mark_entry_cia["category_type_id_marks"],0,$year,$month);
                                
                                $table .= '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=total_' . $sn . ' name=total' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly value='.$mark_entry_cia["category_type_id_marks"].'> </td>' .
                                '<td><input id=result_' . $sn . ' name=result' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly value='.$result_calc["result"].'> </td>';
                            } 
                            else 
                            { 
                                 $table .= '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" required class="mark_txt1" onchange="checkeseMax(this.id)" id=mark_' . $sn . ' name=mark' . $sn . ' size="2px" title="Enter Numbers only" pattern="\d*" type="text" maxlength="3" style="width: 100% !important;" > </td>';
                                 $table .= '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" readonly class="mark_txt3" id=converted_marks_' . $sn . ' name=converted_marks_' . $sn . ' size="2px" type="text" size="2px" style="width: 100% !important;"> </td>';
                                  $table .= '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=total_' . $sn . ' name=total' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly> </td>' .
                                '<td><input id=result_' . $sn . ' name=result' . $sn . ' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                            }

                           
                        }

                       
                    }
                }
                $table .= '</tr>';
                $sn++;
            }//End of foreach
            $table .= "</tbody></table>";            
        }
        //Arrear
        else 
        {

           $check_mark_exists = MarkEntryMaster::find()->where(['subject_map_id' => $sub_det['coe_subjects_mapping_id'],'year' => $year, 'month' => $month, 'term' => $term, 'mark_type' => $type ])->all();
           $table .= $table_heading;

           if(!empty($check_mark_exists))
           {
            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

            $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

                    $arrear = Yii::$app->db->createCommand("select B.coe_student_mapping_id, B.student_rel_id,E.register_number,E.name from coe_mark_entry_master as S,coe_student_mapping as B,coe_student as E where B.coe_student_mapping_id=S.student_map_id and B.student_rel_id=E.coe_student_id and S.subject_map_id='".$sub_det['coe_subjects_mapping_id']."' and S.year='".$year."' and S.month='".$month."' and S.mark_type='".$type."' and S.term='".$term."' and E.student_status='Active' ".$join_condition." and status_category_type_id NOT IN('".$det_disc_type."') group by E.register_number order by E.register_number")->queryAll();
                   
                foreach ($arrear as $arrear1) 
                {

                    $model1_mark_entry = MarkEntry::find(['category_type_id_marks'])->where(['category_type_id'=>$cat_model1_ese_val['coe_category_type_id'],'subject_map_id'=>$sub_det['coe_subjects_mapping_id'],'student_map_id'=>$arrear1['coe_student_mapping_id'],'month'=>$month,'term'=>$term,'mark_type'=>$type,'year'=>$year])->one();
                      
                    $model2_mark_entry = MarkEntry::find(['category_type_id_marks'])->where(['category_type_id'=>$cat_model2_ese_val['coe_category_type_id'],'subject_map_id'=>$sub_det['coe_subjects_mapping_id'],'student_map_id'=>$arrear1['coe_student_mapping_id'],'month'=>$month,'term'=>$term,'mark_type'=>$type,'year'=>$year])->one();

                    $absent = Yii::$app->db->createCommand("select absent_student_reg from coe_absent_entry where absent_student_reg='".$arrear1['coe_student_mapping_id']."' and exam_month='".$month."' and exam_type='".$type."' and absent_term='".$term."' and exam_subject_id='".$sub_det['coe_subjects_mapping_id']."' and exam_year='".$year."' ")->queryScalar();
                    
                    if($absent)
                    {
                        $abs_type = "Absent";
                    }
                    else
                    {
                        $abs_type = "Present";
                    }

                   
                    $check_dummy_generated = DummyNumbers::find()->where(['subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'student_map_id' => $arrear1['coe_student_mapping_id'], 'month' => $month, 'year' => $year])->all();
                    if (!empty($check_dummy_generated)) 
                    {
                        $mark_entry_ese = Yii::$app->db->createCommand("select * from coe_mark_entry as A,coe_dummy_number as B where A.student_map_id=B.student_map_id and A.subject_map_id=B.subject_map_id and A.year=B.year and A.month=B.month and A.year='" . $year . "' and A.month='" . $month . "' and A.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and A.student_map_id='" . $arrear1['coe_student_mapping_id'] . "' and A.term='" . $term . "' and A.mark_type='" . $type . "' and category_type_id='" . $cat_ese_dummy_mark_type . "' ")->queryOne();
                        if(empty($mark_entry_ese))
                        {
                            $mark_entry_ese = Yii::$app->db->createCommand("select * from coe_mark_entry as A where  
                            A.year='" . $year . "' and A.month='" . $month . "' and
                             A.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and 
                             A.student_map_id='" . $arrear1['coe_student_mapping_id'] . "' and 
                             A.term='" . $term . "' and A.mark_type='" . $type . "' and 
                             category_type_id='" . $cat_ese_mark_type . "' ")->queryOne();
                        }
                    } 
                    else 
                    {
                        $mark_entry_ese = Yii::$app->db->createCommand("select * from coe_mark_entry as A where  
                        A.year='" . $year . "' and A.month='" . $month . "' and
                         A.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and 
                         A.student_map_id='" . $arrear1['coe_student_mapping_id'] . "' and 
                         A.term='" . $term . "' and A.mark_type='" . $type . "' and 
                         category_type_id='" . $cat_ese_mark_type . "' ")->queryOne();
                    }
                  
                     $mark_entry_master = MarkEntryMaster::find(['CIA','ESE','total','result'])->where(['subject_map_id'=>$sub_det['coe_subjects_mapping_id'],'student_map_id'=>$arrear1['coe_student_mapping_id'],'month'=>$month,'term'=>$term,'mark_type'=>$type,'year'=>$year])->orderBy('coe_mark_entry_master_id DESC')->one();

                    $student = Yii::$app->db->createCommand("select register_number,name from coe_student where coe_student_id ='".$arrear1['student_rel_id']."' and student_status='Active'")->queryOne();                


                    $table.=  '<tr>'.
                                '<td><input type="hidden" name="sn" value='.$sn.'>'.$sn.'</td> '.
                                '<td><input type="hidden" name=reg'.$sn.' value='.$arrear1['register_number'].'>'.$arrear1['register_number'].'</td>'.
                                '<td><input type="hidden" value='.$arrear1['name'].'>'.$arrear1['name'].'</td>';

                    //Mark entered

                    if(count($mark_entry_master)>0)
                    {
                        $add_css1 = $mark_entry_master['result'] == "Pass" ? "color: #000;" : "color: #F00;";
                        if($c_paper_type->category_type!="Practical")
                        {

                            $table.=
                                '<td><input type="hidden" name=apeared'.$sn.' value='.$abs_type.'>'.$abs_type.'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_master['CIA'].'> '.$mark_entry_master['CIA'].'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_ese['category_type_id_marks'].'> '.$mark_entry_ese['category_type_id_marks'].'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_master['ESE'].'> '.$mark_entry_master['ESE'].'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_master['total'].'> '.$mark_entry_master['total'].'</td>'.
                                '<td style="'.$add_css1.'"><input  type="hidden" value='.$mark_entry_master['result'].'> '.$mark_entry_master['result'].'</td>';
                        }
                        else
                        {


                            if($model_type=="With Model")
                            {
                                $table.=
                                '<td><input type="hidden" value='.$mark_entry_master['CIA'].'> '.$mark_entry_master['CIA'].'</td>'.
                                '<td><input type="hidden" value='.$model1_mark_entry['category_type_id_marks'].'> '.$model1_mark_entry['category_type_id_marks'].'</td>';
                                $ese_15 =  round((($model1_mark_entry['category_type_id_marks']/100)*$cat_model1_ese_val['description']));
                              
                                $table.=  
                                    '<td><input type="hidden" value='.$ese_15.'> '.$ese_15.'</td>'.
                                    '<td><input type="hidden" value='.$model2_mark_entry['category_type_id_marks'].'> '.$model2_mark_entry['category_type_id_marks'].'</td>';
                                 $ese_25 =  round((($model2_mark_entry['category_type_id_marks']/100)*$cat_model2_ese_val['description']));
                              
                                $table.= 
                                    '<td><input type="hidden" value='.$ese_25.'> '.$ese_25.'</td>'.
                                    '<td><input type="hidden" value='.$mark_entry_master['ESE'].'> '.$mark_entry_master['ESE'].'</td>'.
                                    '<td><input type="hidden" value='.$mark_entry_master['total'].'> '.$mark_entry_master['total'].'</td>'.
                                    '<td style="'.$add_css1.'"><input  type="hidden" value='.$mark_entry_master['result'].'> '.$mark_entry_master['result'].'</td>';
                            }
                            else
                            {
                                if(isset($model1_mark_entry) && isset($model2_mark_entry))
                                {
                                    return 0;
                                }
                                 $table.=
                                
                                '<td><input type="hidden" value='.$mark_entry_master['CIA'].'> '.$mark_entry_master['CIA'].'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_ese['category_type_id_marks'].'> '.$mark_entry_ese['category_type_id_marks'].'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_master['ESE'].'> '.$mark_entry_master['ESE'].'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_master['total'].'> '.$mark_entry_master['total'].'</td>'.
                                '<td style="'.$add_css1.'"><input  type="hidden" value='.$mark_entry_master['result'].'> '.$mark_entry_master['result'].'</td>';
                            }

                            
                        }
                    }
                    //Mark not entered
                    else
                    {
                        $mark_entry_master_pass = MarkEntryMaster::find(['CIA','ESE','total','result'])->where(['subject_map_id'=>$sub_det['coe_subjects_mapping_id'],'student_map_id'=>$arrear1['coe_student_mapping_id'],'result'=>'Pass'])->orderBy('coe_mark_entry_master_id DESC')->one();


                        if($c_paper_type->category_type!="Practical")
                        {
                            $add_css1 = $mark_entry_master['result'] == "Pass" ? "color: #000;" : "color: #F00;";
                            if($absent)
                            {
                                $table.=
                                    '<td><input type="hidden" name=apeared'.$sn.' value='.$abs_type.'>'.$abs_type.'</td>'.
                                    '<td><input type="hidden" id=cia_'.$sn.' name=cia'.$sn.' value='.$mark_entry_master['CIA'].'> '.$mark_entry_master['CIA'].'</td>'.
                                    '<td><input id=mark_'.$sn.' name=mark'.$sn.' size="2px" type="hidden" value="0" style="width: 100% !important;"> 0 </td>'.
                                    '<td><input id=converted_marks_'.$sn.' name=converted_marks_'.$sn.' size="2px" type="hidden" value="0" style="width: 100% !important;"> 0 </td>'.
                                    '<td><input id=total_'.$sn.' name=total'.$sn.' size="2px" type="hidden" style="width: 100% !important;" value='.$mark_entry_master['CIA'].'> '.$mark_entry_master['CIA'].' </td>'.
                                    '<td style="'.$add_css1.'" ><input id=result_'.$sn.' name=result'.$sn.' size="2px" type="hidden" style="width: 100% !important;" value='.$abs_type.'> '.$abs_type.'</td>';
                            }
                            else
                            {
                                $table.=
                                    '<td><input type="hidden" name=apeared'.$sn.' value='.$abs_type.'>'.$abs_type.'</td>'.
                                    '<td><input type="hidden" id=cia_'.$sn.' name=cia'.$sn.' value='.$mark_entry_master['CIA'].'> '.$mark_entry_master['CIA'].'</td>'.
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" required class="mark_txt1" onchange="checkeseMax(this.id)" id=mark_'.$sn.' name=mark'.$sn.' size="2px" title="Enter Numbers only" pattern="\d*" type="text" maxlength="3" style="width: 100% !important;"> </td>'.
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" class="mark_txt2" id=converted_marks_'.$sn.' name=converted_marks_'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                                $table.= 
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=total_'.$sn.' name=total'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>'.
                                    '<td style="'.$add_css1.'"><input  id=result_'.$sn.' name=result'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                            }
                        }
                        else
                        {
                            
                            $table.=
                                '<td><input type="hidden" id=cia_'.$sn.' name=cia'.$sn.' value='.$mark_entry_master['CIA'].'> '.$mark_entry_master['CIA'].'</td>'.
                                '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" required class="mark_txt1" onchange="checkesemax1(this.id)" id=mark_'.$sn.' name=mark'.$sn.' size="2px" title="Enter Numbers only" pattern="\d*" type="text" maxlength="3" style="width: 100% !important;"> </td>'.
                                '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=mark15_'.$sn.' name=mark15'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>'.
                                '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" onchange="checkeseMax(this.id)" required class="mark_txt2" onchange="checkesemax2(this.id)" id=mark1_'.$sn.' name=mark1'.$sn.' size="2px" title="Enter Numbers only" pattern="\d*" type="text" maxlength="3" style="width: 100% !important;"> </td>'.
                                '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=mark25_'.$sn.' name=mark25'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>'.
                                '<td><input readonly class="mark_txt3" id=converted_mark_'.$sn.' name=converted_marks_'.$sn.' size="2px" type="text" size="2px" style="width: 100% !important;"> </td>';
                            $table.= 
                                '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=total_'.$sn.' name=total'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>'.
                                '<td style="'.$add_css1.'" ><input id=result_'.$sn.' name=result'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                        }
                    }
                    $table.='</tr>';  
                    $sn++;
                }//End of foreach
           } // If Marks Already Entered 
           else
           {
                $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

                $arrear = Yii::$app->db->createCommand("select B.coe_student_mapping_id, B.student_rel_id,E.register_number,E.name from coe_subjects_mapping as A,coe_mark_entry_master as S,coe_subjects as C,coe_student_mapping as B,coe_student as E where A.subject_id=C.coe_subjects_id and A.batch_mapping_id='".$batch_map_id."' and A.coe_subjects_mapping_id=S.subject_map_id and A.semester='".$sem."' and B.coe_student_mapping_id=S.student_map_id and B.student_rel_id=E.coe_student_id and A.coe_subjects_mapping_id='".$sub_det['coe_subjects_mapping_id']."' and S.result like '%fail%' and S.student_map_id NOT IN(SELECT student_map_id FROM coe_mark_entry_master WHERE subject_map_id='".$sub_det['coe_subjects_mapping_id']."' AND result like '%pass%') and E.student_status='Active' ".$join_condition." and status_category_type_id NOT IN('".$det_disc_type."') group by E.register_number order by E.register_number")->queryAll();


                if(count($arrear)==0 || empty($arrear))
                {
                    return 0;
                }
                foreach ($arrear as $arrear1) 
                {

                    $model1_mark_entry = MarkEntry::find(['category_type_id_marks'])->where(['category_type_id'=>$cat_model1_ese_val['coe_category_type_id'],'subject_map_id'=>$sub_det['coe_subjects_mapping_id'],'student_map_id'=>$arrear1['coe_student_mapping_id'],'month'=>$month,'term'=>$term,'mark_type'=>$type,'year'=>$year])->one();
                      
                    $model2_mark_entry = MarkEntry::find(['category_type_id_marks'])->where(['category_type_id'=>$cat_model2_ese_val['coe_category_type_id'],'subject_map_id'=>$sub_det['coe_subjects_mapping_id'],'student_map_id'=>$arrear1['coe_student_mapping_id'],'month'=>$month,'term'=>$term,'mark_type'=>$type,'year'=>$year])->one();

                    $absent = Yii::$app->db->createCommand("select absent_student_reg from coe_absent_entry where absent_student_reg='".$arrear1['coe_student_mapping_id']."' and exam_month='".$month."' and exam_type='".$type."' and absent_term='".$term."' and exam_subject_id='".$sub_det['coe_subjects_mapping_id']."' and exam_year ='".$year."' ")->queryAll();
                    
                    if($absent)
                    {
                        $abs_type = "Absent";
                    }
                    else
                    {
                        $abs_type = "Present";
                    }

                    $mark_entry_master_cia = MarkEntryMaster::find(['CIA'])->where(['subject_map_id'=>$sub_det['coe_subjects_mapping_id'],'student_map_id'=>$arrear1['coe_student_mapping_id']])->orderBy('coe_mark_entry_master_id DESC')->one();
                    $check_dummy_generated = DummyNumbers::find()->where(['subject_map_id' => $sub_det['coe_subjects_mapping_id'], 'student_map_id' => $arrear1['coe_student_mapping_id'], 'month' => $month, 'year' => $year])->all();
                    if (!empty($check_dummy_generated)) 
                    {
                        $mark_entry_ese = Yii::$app->db->createCommand("select * from coe_mark_entry as A,coe_dummy_number as B where A.student_map_id=B.student_map_id and A.subject_map_id=B.subject_map_id and A.year=B.year and A.month=B.month and A.year='" . $year . "' and A.month='" . $month . "' and A.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and A.student_map_id='" . $arrear1['coe_student_mapping_id'] . "' and A.term='" . $term . "' and A.mark_type='" . $type . "' and category_type_id='" . $cat_ese_dummy_mark_type . "' ")->queryOne();
                        if(empty($mark_entry_ese))
                        {
                            $mark_entry_ese = Yii::$app->db->createCommand("select * from coe_mark_entry as A where  
                            A.year='" . $year . "' and A.month='" . $month . "' and
                             A.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and 
                             A.student_map_id='" . $arrear1['coe_student_mapping_id'] . "' and 
                             A.term='" . $term . "' and A.mark_type='" . $type . "' and 
                             category_type_id='" . $cat_ese_mark_type . "' ")->queryOne();
                        }
                    } 
                    else 
                    {
                        $mark_entry_ese = Yii::$app->db->createCommand("select * from coe_mark_entry as A where  
                        A.year='" . $year . "' and A.month='" . $month . "' and
                         A.subject_map_id='" . $sub_det['coe_subjects_mapping_id'] . "' and 
                         A.student_map_id='" . $arrear1['coe_student_mapping_id'] . "' and 
                         A.term='" . $term . "' and A.mark_type='" . $type . "' and 
                         category_type_id='" . $cat_ese_mark_type . "' ")->queryOne();
                    }
                  
                     $mark_entry_master = MarkEntryMaster::find(['CIA','ESE','total','result'])->where(['subject_map_id'=>$sub_det['coe_subjects_mapping_id'],'student_map_id'=>$arrear1['coe_student_mapping_id'],'month'=>$month,'term'=>$term,'mark_type'=>$type,'year'=>$year])->orderBy('coe_mark_entry_master_id DESC')->one();

                    $student = Yii::$app->db->createCommand("select register_number,name from coe_student where coe_student_id ='".$arrear1['student_rel_id']."' and student_status='Active'")->queryOne();                


                    $table.=  '<tr>'.
                                '<td><input type="hidden" name="sn" value='.$sn.'>'.$sn.'</td> '.
                                '<td><input type="hidden" name=reg'.$sn.' value='.$arrear1['register_number'].'>'.$arrear1['register_number'].'</td>'.
                                '<td><input type="hidden" value='.$arrear1['name'].'>'.$arrear1['name'].'</td>';

                    //Mark entered

                    if(count($mark_entry_master)>0)
                    { 
                        $add_css1 = $mark_entry_master['result'] == "Pass" ? "color: #000;" : "color: #F00;";
                        if($c_paper_type->category_type!="Practical")
                        {

                            $table.=
                                '<td><input type="hidden" name=apeared'.$sn.' value='.$abs_type.'>'.$abs_type.'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_master_cia['CIA'].'> '.$mark_entry_master_cia['CIA'].'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_ese['category_type_id_marks'].'> '.$mark_entry_ese['category_type_id_marks'].'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_master['ESE'].'> '.$mark_entry_master['ESE'].'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_master['total'].'> '.$mark_entry_master['total'].'</td>'.
                                '<td style="'.$add_css1.'" ><input type="hidden" value='.$mark_entry_master['result'].'> '.$mark_entry_master['result'].'</td>';
                        }
                        else
                        {

                            $table.=
                                '<td><input type="hidden" value='.$mark_entry_master_cia['CIA'].'> '.$mark_entry_master_cia['CIA'].'</td>'.
                                '<td><input type="hidden" value='.$model1_mark_entry['category_type_id_marks'].'> '.$model1_mark_entry['category_type_id_marks'].'</td>';
                            $ese_15 =  round((($model1_mark_entry['category_type_id_marks']/100)*$cat_model1_ese_val['description']));
                          
                            $table.=  
                                '<td><input type="hidden" value='.$ese_15.'> '.$ese_15.'</td>'.
                                '<td><input type="hidden" value='.$model2_mark_entry['category_type_id_marks'].'> '.$model2_mark_entry['category_type_id_marks'].'</td>';
                             $ese_25 =  round((($model2_mark_entry['category_type_id_marks']/100)*$cat_model2_ese_val['description']));
                          
                            $table.= 
                                '<td><input type="hidden" value='.$ese_25.'> '.$ese_25.'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_master['ESE'].'> '.$mark_entry_master['ESE'].'</td>'.
                                '<td><input type="hidden" value='.$mark_entry_master['total'].'> '.$mark_entry_master['total'].'</td>'.
                                '<td style="'.$add_css1.'" ><input type="hidden" value='.$mark_entry_master['result'].'> '.$mark_entry_master['result'].'</td>';
                        }
                    }
                    //Mark not entered
                    else
                    {
                        $mark_entry_master_pass = MarkEntryMaster::find(['CIA','ESE','total','result'])->where(['subject_map_id'=>$sub_det['coe_subjects_mapping_id'],'student_map_id'=>$arrear1['coe_student_mapping_id'],'result'=>'Pass'])->orderBy('coe_mark_entry_master_id DESC')->one();

                        $check_attempt = Yii::$app->db->createCommand('SELECT count(*) FROM coe_mark_entry_master WHERE subject_map_id="'.$sub_det['coe_subjects_mapping_id'].'" AND student_map_id="'.$arrear1['coe_student_mapping_id'].'" AND result not like "%pass%"')->queryScalar();
                        $config_attempt = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CIA_ZEO);  

                        $change_cia_val =  ($check_attempt >= $config_attempt) ? "value=0" :"value='".$mark_entry_master_cia['CIA']."'";


                        $disp_cia_val = ($check_attempt >= $config_attempt) ? 0 :$mark_entry_master_cia['CIA'];

                        $function_change = ($check_attempt >= $config_attempt) ? "onchange=convertfor100(this.id)" : "onchange= checkeseMax(this.id)";
                        $add_css1 = $mark_entry_master_cia['result'] == "Pass" ? "color: #000;" : "color: #F00;";
                        if($c_paper_type->category_type!="Practical")
                        {                            
                            if($absent)
                            {
                                $table.=
                                    '<td><input type="hidden" name=apeared'.$sn.' value='.$abs_type.'>'.$abs_type.'</td>'.
                                    '<td><input type="hidden" id=cia_'.$sn.' name=cia'.$sn.'  '.$change_cia_val.' > '.$disp_cia_val.' </td>'.
                                    '<td><input id=mark_'.$sn.' name=mark'.$sn.' size="2px" type="hidden" value="0" style="width: 100% !important;"> 0 </td>'.
                                    '<td><input id=converted_marks_'.$sn.' name=converted_marks_'.$sn.' size="2px" type="hidden" value="0" style="width: 100% !important;"> 0 </td>'.
                                    '<td><input id=total_'.$sn.' name=total'.$sn.' size="2px" type="hidden" style="width: 100% !important;" '.$change_cia_val.' > '.$disp_cia_val.' </td>'.
                                    '<td style="'.$add_css1.'" ><input  id=result_'.$sn.' name=result'.$sn.' size="2px" type="hidden" style="width: 100% !important;" value='.$abs_type.'> '.$abs_type.'</td>';
                            }
                            else
                            {
                                
                                $table.=
                                    '<td><input type="hidden" name=apeared'.$sn.' value='.$abs_type.'>'.$abs_type.'</td>'.
                                    '<td><input type="hidden" id=cia_'.$sn.' name=cia'.$sn.' '.$change_cia_val.'  > '.$disp_cia_val.'</td>'.
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" '.$function_change.' required class="mark_txt1"  id=mark_'.$sn.' name=mark'.$sn.' size="2px" title="Enter Numbers only" pattern="\d*" type="text" maxlength="3" style="width: 100% !important;"> </td>'.
                                    '<td><input autocomplete="off"  onkeypress="numbersOnly(event);allowEntr(event,this.id);" class="mark_txt2" id=converted_marks_'.$sn.' name=converted_marks_'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                                $table.= 
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=total_'.$sn.' name=total'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>'.
                                    '<td style="'.$add_css1.'"><input id=result_'.$sn.'  name=result'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
                            }
                        }
                        else
                        {                            

                            if($model_type == "With Model")
                            {
								if($absent)
								{
									$table.=
										
										'<td><input type="hidden" id=cia_'.$sn.' name=cia'.$sn.'  '.$change_cia_val.' > '.$disp_cia_val.' </td>'.
										'<td><input id=mark_'.$sn.' name=mark'.$sn.' size="2px" type="hidden" value="0" style="width: 100% !important;"> 0 </td>'.
										'<td><input id=converted_marks_'.$sn.' name=converted_marks_'.$sn.' size="2px" type="hidden" value="0" style="width: 100% !important;"> 0 </td>'.
										'<td><input id=total_'.$sn.' name=total'.$sn.' size="2px" type="hidden" style="width: 100% !important;" '.$change_cia_val.' > '.$disp_cia_val.' </td>'.
										'<td style="'.$add_css1.'" ><input  id=result_'.$sn.' name=result'.$sn.' size="2px" type="hidden" style="width: 100% !important;" value='.$abs_type.'> '.$abs_type.'</td>';
								}
								else
								{
									
									$table.=
										'<td><input type="hidden" id=cia_'.$sn.' name=cia'.$sn.' value='.$mark_entry_master_cia['CIA'].'> '.$mark_entry_master_cia['CIA'].'</td>'.
										'<td><input required class="mark_txt1" onchange="checkesemax1(this.id)" id=mark_'.$sn.' name=mark'.$sn.' size="2px" title="Enter Numbers only" pattern="\d*" type="text" autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" maxlength="3" style="width: 100% !important;"> </td>'.
										'<td><input onkeypress="numbersOnly(event);allowEntr(event,this.id);" autocomplete="off" id=mark15_'.$sn.' name=mark15'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>'.
										'<td><input onkeypress="numbersOnly(event);allowEntr(event,this.id);" autocomplete="off" required class="mark_txt2" onchange="checkesemax2(this.id)" id=mark1_'.$sn.' name=mark1'.$sn.' size="2px" title="Enter Numbers only" pattern="\d*" type="text" maxlength="3" style="width: 100% !important;"> </td>'.
										'<td><input autocomplete="off" id=mark25_'.$sn.' name=mark25'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>'.
										'<td><input readonly class="mark_txt3" id=converted_mark_'.$sn.' name=converted_marks_'.$sn.' size="2px" type="text" size="2px" style="width: 100% !important;"> </td>';
											$table.= 
										'<td><input autocomplete="off" id=total_'.$sn.' name=total'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>'.
										'<td style="'.$add_css1.'"><input id=result_'.$sn.'  name=result'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
								}
                               
                            }
                            else
                            {
								if($absent)
								{
									$table.=
										
										'<td><input type="hidden" id=cia_'.$sn.' name=cia'.$sn.'  '.$change_cia_val.' > '.$disp_cia_val.' </td>'.
										'<td><input autocomplete="off" id=mark_'.$sn.' name=mark'.$sn.' size="2px" type="hidden" value="0" style="width: 100% !important;"> 0 </td>'.
										'<td><input autocomplete="off" id=converted_marks_'.$sn.' name=converted_marks_'.$sn.' size="2px" type="hidden" value="0" style="width: 100% !important;"> 0 </td>'.
										'<td><input id=total_'.$sn.' name=total'.$sn.' size="2px" type="hidden" style="width: 100% !important;" '.$change_cia_val.' > '.$disp_cia_val.' </td>'.
										'<td style="'.$add_css1.'" ><input  id=result_'.$sn.' name=result'.$sn.' size="2px" type="hidden" style="width: 100% !important;" value='.$abs_type.'> '.$abs_type.'</td>';
								}
								else
								{
									
									$table.=
                                    
                                    '<td><input type="hidden" id=cia_'.$sn.' name=cia'.$sn.' '.$change_cia_val.'  > '.$disp_cia_val.'</td>'.
                                    '<td><input autocomplete="off" required class="mark_txt1" onchange="checkeseMax(this.id)" id=mark_'.$sn.' name=mark'.$sn.' size="2px" title="Enter Numbers only" pattern="\d*" type="text" onkeypress="numbersOnly(event);allowEntr(event,this.id);" maxlength="3" style="width: 100% !important;"> </td>'.
                                    '<td><input '.$function_change.' onkeypress="numbersOnly(event);allowEntr(event,this.id);" class="mark_txt2" id=converted_marks_'.$sn.' name=converted_marks_'.$sn.' size="2px" type="text" autocomplete="off" style="width: 100% !important;" readonly> </td>';
									$table.= 
                                    '<td><input autocomplete="off" onkeypress="numbersOnly(event);allowEntr(event,this.id);" id=total_'.$sn.' name=total'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>'.
                                    '<td style="'.$add_css1.'"><input id=result_'.$sn.'  name=result'.$sn.' size="2px" type="text" style="width: 100% !important;" readonly> </td>';
								}
                                
                                
                            }
                            
                        }
                    }
                    $table.='</tr>';  
                    $sn++;
                }//End of foreach
           }
           $table .= "</tbody></table>";
        }
        //$data = ['table' => $table, 'cia_min' => $sub_det['CIA_min'], 'cia_max' => $sub_det['CIA_max'], 'ese_max' => $sub_det['ESE_max'], 'ese_min' => $sub_det['ESE_min'], 'ese_total' => $sub_det['total_minimum_pass'], 'cat_model1_ese_val' => $cat_model1_ese_val['description'], 'cat_model2_ese_val' => $cat_model2_ese_val['description'], 'sn1' => $sn - 1];
        $data = ['table' => $table, 'cia_min' => $sub_det['CIA_min'], 'cia_max' => $sub_det['CIA_max'], 'ese_max' => $sub_det['ESE_max'], 'ese_min' => $sub_det['ESE_min'], 'ese_total' => $sub_det['total_minimum_pass'], 'cat_model1_ese_val' => $mod_1, 'cat_model2_ese_val' => $mod_2, 'sn1' => $sn - 1];
        return Json::encode($data);
    }

    /* External Mark End */

    /* Moderation starts here */

    public function actionModerationsubjects() {
        $batch_mapping_id = Yii::$app->request->post('batch_map_id');
    }

    public function actionGetregrange() {
        $subject_id = Yii::$app->request->post('sub_code');
        $batch_mapping_id = Yii::$app->request->post('batch_map_id');
        $sub_map_id = Yii::$app->db->createCommand("select coe_subjects_mapping_id from coe_subjects_mapping where subject_id='" . $subject_id . "' and batch_mapping_id='" . $batch_mapping_id . "'")->queryScalar();
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $query_reg = new Query();
        $failed_stu_list = $query_reg->select('c.register_number')
                        ->from('coe_mark_entry_master a')
                        ->join('JOIN', 'coe_student_mapping b', 'a.student_map_id=b.coe_student_mapping_id')
                        ->join('JOIN', 'coe_student c', 'b.student_rel_id=c.coe_student_id')
                        ->where(['a.year' => $_POST['year'], 'a.month' => $_POST['month'], 'a.mark_type' => $_POST['type'], 'a.subject_map_id' => $sub_map_id, 'a.year_of_passing' => '', 'c.student_status' => 'Active'])
                        ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                        ->andWhere(['like', 'a.result', 'fail'])->createCommand()->queryAll();
        return Json::encode($failed_stu_list);
    }

    public function actionGetmoderationlist() 
    {
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $moderation_marks = Yii::$app->request->post('moderation_marks');
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $data_available = 0;
         $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
         $theory_paper = Yii::$app->db->createCommand("select * from coe_category_type where description like '%theory%'")->queryAll();
         $theory_id = '';
         foreach ($theory_paper as $key => $theory_val) 
         {
             $theory_id .=$theory_val['coe_category_type_id'].",";
         }
        $theory_id = trim($theory_id,',');

        
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $mod_table = '';
        $mod_table .= '<table id="checkAllFeattt" class="table table-striped" align="right" border=1>     
                   <thead id="t_head">                                                                                                               
                    <th> S.NO </th> 
                    <th> Register Number </th>
                    <th> Name </th>
                    <th> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code </th>
                    <th> ESE MIN </th>
                    <th> ESE </th>
                    <th> Result </th>
                    <th> Action </th></thead><tbody>';
       
        if (!empty($moderation_marks)) 
        {             
            $failed_stu_list = Yii::$app->db->createCommand("select register_number,name,CIA,course_batch_mapping_id as bat_map_val, subject_code, subject_name,ESE_min,ESE,total,result,subject_map_id,student_map_id, term,mark_type from coe_mark_entry_master as a JOIN coe_student_mapping as b ON b.coe_student_mapping_id=a.student_map_id JOIN coe_student c ON c.coe_student_id=b.student_rel_id JOIN coe_subjects_mapping as d ON d.coe_subjects_mapping_id=a.subject_map_id and d.batch_mapping_id=b.course_batch_mapping_id JOIN coe_subjects as e ON e.coe_subjects_id=d.subject_id JOIN coe_category_type as th ON th.coe_category_type_id=d.paper_type_id where a.year='" . $exam_year . "' and a.month='" . $exam_month . "' and b.course_batch_mapping_id='".$batch_map_id."' and paper_type_id IN($theory_id) and d.batch_mapping_id='".$batch_map_id."' and a.ESE!=0 and a.year_of_passing='' and a.result like '%fail%' and a.ESE between (e.ESE_min-$moderation_marks) AND e.ESE_min and a.total between (e.total_minimum_pass-$moderation_marks) AND e.total_minimum_pass and mark_type='27' and grade_point=0 and a.ESE!=e.ESE_min and status_category_type_id NOT IN('".$det_disc_type."','".$det_cat_type."') and c.student_status='Active' order by register_number,ESE asc")->queryAll();
            $data_available = count($failed_stu_list) > 0 ? 1 : 0;
        } 
        else 
        {
            $data_available = 0;
        }
        if ($data_available == 1) 
        {
            $sn = 1;
            foreach ($failed_stu_list as $fail_student) 
            {
                $mod_table .= "<tr><td><input type='hidden' name='sn' value=" . $sn . ">" . $sn . "</td>";
                $mod_table .= "<td><input type='hidden' name=student_map_id_" . $sn . " value='" . $fail_student['student_map_id'] . "'>" . $fail_student['register_number'] . "</td>";
                $mod_table .= "<td><input type='hidden' name=stu_name" . $sn . " value='" . $fail_student['name'] . "'>" . $fail_student['name'] . "</td>";
                $mod_table .= "<td><input type='hidden' name=subject_map_id_" . $sn . " value='" . $fail_student['subject_map_id'] . "'>" . $fail_student['subject_code'] . "</td>";
                $mod_table .= "<td><input type='hidden' name=ESE_min" . $sn . " value='" . $fail_student['ESE_min'] . "'>" . $fail_student['ESE_min'] . "</td>";
                $mod_table .= "<td><input type='hidden' name=ESE_" . $sn . " value='" . $fail_student['ESE'] . "'>" . $fail_student['ESE'] . "</td>";               
                $mod_table .= "<input type='hidden' name=term" . $sn . " value='" . $fail_student['term'] . "' /><input type='hidden' name=mark_type" . $sn . " value='" . $fail_student['mark_type'] . "' /><input type='hidden' name=CIA_" . $sn . " value='" . $fail_student['CIA'] . "' />";
                $mod_table .= "<td><input type='hidden' name=result" . $sn . "  value='" . $fail_student['result'] . "'><span id=result_" . $sn . " >" . $fail_student['result'] . "</span></td>";
                $mod_table .= "<td align='center'><input type='checkbox' onclick='changeLableModeration(this.id)' id=checked_" . $sn . " name=mod" . $sn . " value='YES' ></td></tr>";
                $sn++;
            }
            $mod_table .= '</tbody></table>';
            return $mod_table;
        } 
        else 
        {
            return 0;
        }
    }

    public function actionViewmodmonth() {
        $query_view_mod_month = new Query();
        $view_mod_month = $query_view_mod_month->select('distinct(b.description),a.month')
                        ->from('coe_mark_entry a')
                        ->join('JOIN', 'coe_category_type b', 'a.month=b.coe_category_type_id')
                        ->join('JOIN', 'coe_category_type c', 'c.coe_category_type_id=a.category_type_id')
                        ->where(['a.year' => $_POST['view_mod_year']])
                        ->andWhere(['like', 'c.description', 'mod'])
                        ->andWhere(['like', 'c.description', 'moderation'])
                        ->andWhere(['NOT', ['a.month' => 'null']])->createCommand()->queryAll();
        return Json::encode($view_mod_month);
    }

    public function actionViewmodtype() {
        $query_view_mod_type = new Query();
        $view_mod_type = $query_view_mod_type->select('distinct(b.description),a.mark_type')
                        ->from('coe_mark_entry a')
                        ->join('JOIN', 'coe_category_type b', 'a.mark_type=b.coe_category_type_id')
                        ->join('JOIN', 'coe_category_type c', 'c.coe_category_type_id=a.category_type_id')
                        ->where(['a.year' => $_POST['view_mod_year'], 'a.month' => $_POST['view_mod_month']])
                        ->andWhere(['like', 'c.description', 'mod'])
                        ->andWhere(['like', 'c.description', 'moderation'])->createCommand()->queryAll();
        return Json::encode($view_mod_type);
    }

    public function actionViewmodsubjectcode() {
        $category_type_id = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where description like 'moderation'")->queryScalar();

        if ($_POST['filter_value'] == "Department Wise") {

            $query_view_mod_dept = new Query();
            $view_mod_dept = $query_view_mod_dept->select(["distinct(batch_mapping_id),concat(degree_code,' ',programme_code) as programme"])
                            ->from('coe_mark_entry a')
                            ->join('JOIN', 'coe_subjects_mapping b', 'a.subject_map_id=b.coe_subjects_mapping_id')
                            ->join('JOIN', 'coe_bat_deg_reg c', 'b.batch_mapping_id=c.coe_bat_deg_reg_id')
                            ->join('JOIN', 'coe_degree d', 'c.coe_degree_id=d.coe_degree_id')
                            ->join('JOIN', 'coe_programme e', 'c.coe_programme_id=e.coe_programme_id')
                            ->where(['a.year' => $_POST['view_mod_year'], 'a.month' => $_POST['view_mod_month'], 'a.mark_type' => $_POST['view_mod_type'], 'a.category_type_id' => $category_type_id])->createCommand()->queryAll();
            return Json::encode($view_mod_dept);
        } else {
            $query_view_mod_subject = new Query();
            $view_mod_subject = $query_view_mod_subject->select('distinct(c.subject_code) as subject_code,a.subject_map_id')
                            ->from('coe_mark_entry a')
                            ->join('JOIN', 'coe_subjects_mapping b', 'a.subject_map_id=b.coe_subjects_mapping_id')
                            ->join('JOIN', 'coe_subjects c', 'b.subject_id=c.coe_subjects_id')
                            ->where(['a.year' => $_POST['view_mod_year'], 'a.month' => $_POST['view_mod_month'], 'a.mark_type' => $_POST['view_mod_type'], 'a.category_type_id' => $category_type_id])->createCommand()->queryAll();
            return Json::encode($view_mod_subject);
        }
    }

    /* Moderation ends here */
    /* Withheld Starts Here */

    public function actionWithheldregnum() {
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $det_cat_val = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like 'detain%'")->queryScalar();
        $det_disc_val = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $withheld_reg_num = Yii::$app->db->createCommand("select B.coe_student_mapping_id,A.register_number from coe_student as A,coe_student_mapping as B where B.student_rel_id=A.coe_student_id and B.course_batch_mapping_id='" . $batch_map_id . "' and B.status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') and A.student_status='Active' order by A.register_number")->queryAll();
        return Json::encode($withheld_reg_num);
    }

    public function actionWithheldstumarks() 
    {
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $stu_map_id = Yii::$app->request->post('stu_map_id');
        $bat_map_id = Yii::$app->request->post('bat_map_id');
        
        $check_result_publish = ConfigUtilities::getResultPublishStatus($year,$month);
       /** if($check_result_publish==1)
        {
            Yii::$app->ShowFlashMessages->setMsg('Error', 'Results already published');
            return $this->redirect(['mark-entry/withheld']);
        }**/
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        
        $query_view_mod_dept = new Query();
        $stu_mark_id = $query_view_mod_dept->select('*')
        ->from('coe_student A')
        ->join('JOIN', 'coe_student_mapping B', 'B.student_rel_id=A.coe_student_id')
        ->join('JOIN', 'coe_subjects_mapping C', 'C.batch_mapping_id=B.course_batch_mapping_id')
        ->join('JOIN', 'coe_mark_entry_master D', 'D.student_map_id=B.coe_student_mapping_id and D.subject_map_id=C.coe_subjects_mapping_id')
        ->join('JOIN', 'coe_subjects E', 'E.coe_subjects_id=C.subject_id')
        ->where(['D.student_map_id' => $stu_map_id, 'B.coe_student_mapping_id' => $stu_map_id, 'D.year' => $year, 'D.month' => $month,'B.course_batch_mapping_id'=>$bat_map_id,'C.batch_mapping_id'=>$bat_map_id])
        ->andWhere(['NOT IN','status_category_type_id',$det_disc_type])->orderBy('part_no,subject_code')->createCommand()->queryAll();


        $stu_mark_status = Yii::$app->db->createCommand("select * from coe_student as A,coe_student_mapping as B,coe_mark_entry_master as C  where B.coe_student_mapping_id='" . $stu_map_id . "' and B.student_rel_id=A.coe_student_id and B.coe_student_mapping_id=C.student_map_id and C.student_map_id='" . $stu_map_id . "' and C.year='" . $year . "' and C.month='" . $month . "' and status_category_type_id NOT IN('".$det_disc_type."') and A.student_status='Active'")->queryOne();

        $table = '';
        $sn = 1;
        
        $table .= '<table id="checkAllFeat" class="table table-striped" align="right" border=1>     
                   <thead id="t_head">                                                                                                               
                    <th> S.NO </th> 
                    <th> SEM </th> 
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Code </th>
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Name </th>  
                    <th> CIA </th>
                    <th> ESE </th>
                    <th> Total </th>
                    <th> Result </th>
                    <th> Status </th>
                    <th align="center"> Remarks </th>
                    </thead><tbody>';
					
        if (count($stu_mark_id) > 0) 
        {
            foreach ($stu_mark_id as $stu_mark_id1) 
            {
                $table .= "<tr>" .
                        "<td><input type='hidden' name='sn' value=" . $sn . ">" . $sn . "</td> 
                        <td>" . $stu_mark_id1['semester'] . "</td> " .
                        //"<td><input type='hidden' name=stu_name".$sn." value='".$stu_mark_id1['name']."'>".$stu_mark_id1['name']."</td>".
                        "<td><input type='hidden' name=sub_code" . $sn . " value='" . $stu_mark_id1['coe_subjects_mapping_id'] . "'>" . $stu_mark_id1['subject_code'] . "</td>" .
                        "<td><input type='hidden' name=sub_name" . $sn . " value='" . $stu_mark_id1['subject_name'] . "'>" . $stu_mark_id1['subject_name'] . "</td>" .
                        "<td><input type='hidden' name=cia" . $sn . " value='" . $stu_mark_id1['CIA'] . "'>" . $stu_mark_id1['CIA'] . "</td>" .
                        "<td><input type='hidden' name=ese" . $sn . " value='" . $stu_mark_id1['ESE'] . "'>" . $stu_mark_id1['ESE'] . "</td>" .
                        "<td><input type='hidden' name=total" . $sn . " value='" . $stu_mark_id1['total'] . "' size='2px'>" . $stu_mark_id1['total'] . "</td>" .
                        "<td><input type='hidden' name=result" . $sn . " value='" . $stu_mark_id1['result'] . "'>" . $stu_mark_id1['result'] . "</td>";
                
				
				$check_mark_entry_master = Yii::$app->db->createCommand("select * from coe_mark_entry_master where student_map_id='" . $stu_map_id . "' and subject_map_id='" . $stu_mark_id1['subject_map_id'] . "' and year='" . $year . "' and month='" . $month . "' and withheld IN('w','W') ")->queryOne();
                if (count($check_mark_entry_master) > 0 && !empty($check_mark_entry_master)) 
                {
				
                    $table .= "</td><td align='center'><input type='checkbox' onchange='withheld_check(this.id)' name=withheld" . $sn . " id=withheld_" . $sn . " checked></td>";
					$table .= "<td><input type='text' onkeypress='return onlyAlphabets(event,this);' name=remarks" . $sn . " required=required id=remarks" . $sn . "  value=".$check_mark_entry_master['withheld_remarks']." ></td>";
                } 
                else 
                {
                    $table .= "</td><td align='center'><input type='checkbox' onchange='withheld_check(this.id)' name=withheld" . $sn . " id=withheld_" . $sn . "></td>";
					$table .= "<td><input type='text' required=required onkeypress='return onlyAlphabets(event,this);' name=remarks" . $sn . " required=required id=remarks" . $sn . " disabled></td>";
                }

                $table .= "</tr>";
                $sn++;
            }
            $table .= "</tbody></table>";
          /*  if($stu_mark_status['status_id']==1)
            {
                return 1;
            }
    else*/
            {
                return $table;
            }
            
        } 
        else 
        {
            return 0;
        }
    }

    /* Withheld Ends Here */

    protected function valueReplace($value, $arrayData) {
        if (empty($value) || empty($arrayData)) {
            return null;
        } else {
            return $arrayData[$value];
        }
    }

// reports starts here 

    public function actionGetlistofsubjects() {
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $bat_map_id = Yii::$app->request->post('bat_map_id');

        $query = new Query();
        $query->select('DISTINCT (H.subject_code) as subject_code')
                ->from('coe_subjects_mapping as G')
                ->join('JOIN', 'coe_subjects H', 'H.coe_subjects_id=G.subject_id')
                ->join('JOIN', 'coe_exam_timetable F', 'F.subject_mapping_id=G.coe_subjects_mapping_id');

        $query->Where(['F.exam_year' => $exam_year, 'G.batch_mapping_id' => $bat_map_id, 'F.exam_month' => $month]);
        $query->groupBy('H.subject_code');
        $query->orderBy('H.coe_subjects_id');
        $subject_data = $query->createCommand()->queryAll();
        $subjects_dropdown = "";
        if (!empty($subject_data)) {
            $subjects_dropdown = "<option value='' > --- Select " . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " ---</option>";
            foreach ($subject_data as $key => $value) {
                $subjects_dropdown .= "<option value='" . $value['subject_code'] . "' > " . $value['subject_code'] . "</option>";
            }
        }
        return Json::encode($subjects_dropdown);
    }

    //Additional Credit Starts Here

    public function actionAcsubjectname() {

        $query_sub = new Query();
        $query_sub->select('distinct(subject_name),credits')
                ->from('coe_additional_credits')
                ->where(['subject_code' => $_POST['subject_code'],'semester'=>$_POST['semester']]);
        $subject_name = $query_sub->createCommand()->queryAll();
        if (count($subject_name) > 0) {
            return json_encode($subject_name);
        } else {
            return 0;
        }
    }

    public function actionAdditionalcreditstulist() {
        if ($_POST['subject_code'] != "" && $_POST['subject_name'] != "" && $_POST['credit'] != "") {

            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

            $query = new Query();
            $query->select('b.register_number,b.name,a.coe_student_mapping_id')
                    ->from('coe_student_mapping a')
                    ->join('JOIN', 'coe_student b', 'a.student_rel_id=b.coe_student_id')
                    ->where(['a.course_batch_mapping_id' => $_POST['batch_map_id'], 'b.student_status' => 'Active'])->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->orderby('b.register_number');
            $student_list = $query->createCommand()->queryAll();
            $sno = 1;
            $add_table = '';
            $add_table .= '<table id="checkAllFeattttt" class="table table-striped" align="right" border=1>     
                      <tbody>                                                                                                             
                      <td align="center"><b> S.NO </b></td> 
                      <td align="center"><b> Register Number </b></td>
                      <td align="center"><b> Student Name </b></td>
                      <td align="center"><b> Action </b></td>
                      <td align="center" width="1px"><b>Mark</b></td>
                      <td align="center" width="1px"><b>Grade</b></td>
                      <td align="center" width="1px"><b>Grade Point</b></td>
                      <td align="center"><b>Result</b></td>';
            foreach ($student_list as $stu_list) {

                $query_exist = new Query();
                $query_exist->select('*')
                        ->from('coe_additional_credits a')
                        ->where(['a.student_map_id' => $stu_list['coe_student_mapping_id'], 'a.subject_code' => $_POST['subject_code']]);
                $exist_student = $query_exist->createCommand()->queryOne();

                $add_table .= "<tr><td align='center'><input type='hidden' name='sn' value=" . $sno . ">" . $sno . "</td>";
                $add_table .= "<td align='center'><input type='hidden' name=reg_num" . $sno . " value=" . $stu_list['register_number'] . ">" . $stu_list['register_number'] . "</td>";
                $add_table .= "<td>" . $stu_list['name'] . "</td>";

                if ($exist_student != "") {
                    $add_table .= "<td align='center'><input type='checkbox' checked disabled></td>";
                   
                   $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' onchange='getAddResult(this.id,this.value);' onkeypress='numbersOnly(event); allowEntr(event,this.id);' name=actxt_" . $sno . " value=" . $exist_student['total'] . " id=actxt_" . $sno . " disabled></td>";
                    $grade_point= $exist_student['grade_point']=='' || empty($exist_student['grade_point'])?0:$exist_student['grade_point'];
                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px'  name=grade_" . $sno . " id=grade_" . $sno . " value=" . $exist_student['grade_name'] . " disabled></td>";
                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' name=grade_point_" . $sno . " id=grade_point_" . $sno . " value=" . $grade_point . " disabled></td>";
                    $add_table .= "<td align='center'><input autocomplete='off' type='textbox' size='5px' name=acresult_" . $sno . " id=acresult_" . $sno . " value=" . $exist_student['result'] . " disabled></td></tr>";

                } else {

                    $add_table .= "<td align='center'>
                            <input type='checkbox' onclick='additional_check(this.id)' name=add" . $sno . " id=add_" . $sno . "></td>";
                    
                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' onchange='getAddResult(this.id,this.value);' onkeypress='numbersOnly(event); allowEntr(event,this.id);' name=actxt_" . $sno . " id=actxt_" . $sno . " disabled></td>";
                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px'  name=grade_" . $sno . " id=grade_" . $sno . " readonly></td>";
                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' name=grade_point_" . $sno . " id=grade_point_" . $sno . " readonly></td>";
                    $add_table .= "<td align='center'><input autocomplete='off' type='textbox' size='5px' name=acresult_" . $sno . " id=acresult_" . $sno . " readonly /></td></tr>";
                }

                $sno++;
            }
            $add_table .= '</tbody></table>';
            return $add_table;
        } else {
            return 0;
        }
    }

    //Additional Credit Ends Here
    // Marks 
    public function actionGetgradepoint() 
    {        
        $batch_mapping_id = Yii::$app->request->post('batch_map_id');
        $total_marks = Yii::$app->request->post('marks');
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $coe_batch_id = CoeBatDegReg::findOne($batch_mapping_id);
        $regulation = $coe_batch_id;
        $grade_details = Regulation::find()->where(['regulation_year'=>$regulation->regulation_year])->all();

        foreach ($grade_details as $value) 
        {
            if($total_marks >= $value['grade_point_from'] &&  $total_marks <= $value['grade_point_to'] )
              {
                  if($total_marks<50)
                  {                    
                    $student_res_data = ['result'=>'Fail','total_marks'=>$total_marks,'grade_name'=>"U",'grade_point'=>'0','year_of_passing'=>''];        
                  }      
                  else
                  {
                    $student_res_data = ['result'=>'Pass','total_marks'=>$total_marks,'grade_name'=>$value['grade_name'],'grade_point'=>$value['grade_point'],'year_of_passing'=>$month."-".$year];                    
                  }
              }
        }

        return !empty($student_res_data) ? json_encode(array_filter($student_res_data)) : 0;
    }
    public function actionGetmandatosubcatlist() 
    {        
        $batch_mapping_id = Yii::$app->request->post('batch_map_id');
        $mark_type = Yii::$app->request->post('mark_type');
        $exam_term = Yii::$app->request->post('exam_term');
        $semester = Yii::$app->request->post('semester');
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');        
        $man_sub_ud = MandatorySubjects::find()->where(['batch_mapping_id'=>$batch_mapping_id,'semester'=>$semester])->all();
        $merge_data =[];
        if(!empty($man_sub_ud))
        {
            foreach ($man_sub_ud as $key => $value) {
              $merge_data[] =['sub_id'=>$value['coe_mandatory_subjects_id'],'subj_code'=>$value['subject_code']]; 
            }
        }
        $data = !empty($man_sub_ud) ? $merge_data:0;
        
        return !empty($data) ? json_encode(array_filter($data)) : 0;
    }
    public function actionGetmandatorygradepoint() 
    {        
        $batch_mapping_id = Yii::$app->request->post('batch_map_id');
        $total_marks = Yii::$app->request->post('marks');
        $manSubId = Yii::$app->request->post('manSubId');
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $coe_batch_id = CoeBatDegReg::findOne($batch_mapping_id);
        $man_sub_ud = MandatorySubjects::findOne($manSubId);
        $regulation = $coe_batch_id;
        $grade_details = Regulation::find()->where(['regulation_year'=>$regulation->regulation_year])->all();

        foreach ($grade_details as $value) 
        {
            if($total_marks >= $value['grade_point_from'] &&  $total_marks <= $value['grade_point_to'] )
              {
                  if($total_marks<$man_sub_ud->CIA_min || $total_marks<$man_sub_ud->total_minimum_pass)
                  {                    
                    $student_res_data = ['result'=>'Fail','total_marks'=>$total_marks,'grade_name'=>"U",'grade_point'=>'0','year_of_passing'=>''];        
                  }      
                  else
                  {
                    $student_res_data = ['result'=>'Pass','total_marks'=>$total_marks,'grade_name'=>strtoupper($value['grade_name']),'grade_point'=>$value['grade_point'],'year_of_passing'=>$month."-".$year];                    
                  }
              }
        }

        return !empty($student_res_data) ? json_encode(array_filter($student_res_data)) : 0;
    }

    protected function valueReplaceNumber($array_data) {
        $array = array('0' => 'ZERO', '1' => 'ONE', '2' => 'TWO', '3' => 'THREE', '4' => 'FOUR', '5' => 'FIVE', '6' => 'SIX', '7' => 'SEVEN', '8' => 'EIGHT', '9' => 'NINE', '10' => 'TEN');
        $return_string = '';
        for ($i = 0; $i < count($array_data); $i++) {
            $return_string .= $array[$array_data[$i]] . " ";
        }
        return !empty($return_string) ? $return_string : 'No Data Found';
    }

    public function actionGetarrearcountstudents() {
        $subject_map_id = Yii::$app->request->post('subject_map_id');
        $batch_mapping_id = Yii::$app->request->post('batch_mapping_id');
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $border = Yii::$app->request->post('border');
        $eseMin = Yii::$app->request->post('Min');
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $above_marks = $eseMin - $border;
        $query_exist = new Query();
        $query_exist->select('A.register_number,C.ESE,C.CIA,C.total,E.subject_code,E.ESE_min')
                ->from('coe_student A')
                ->join('JOIN', 'coe_student_mapping B', 'B.student_rel_id=A.coe_student_id')
                ->join('JOIN', 'coe_mark_entry_master C', 'C.student_map_id=B.coe_student_mapping_id')
                ->join('JOIN', 'coe_subjects_mapping D', 'D.coe_subjects_mapping_id=C.subject_map_id')
                ->join('JOIN', 'coe_subjects E', 'E.coe_subjects_id=D.subject_id')
                ->where([
                    'C.year' => $year,
                    'C.month' => $month,
                    'C.subject_map_id' => $subject_map_id,
                    'D.batch_mapping_id' => $batch_mapping_id,
                    'B.course_batch_mapping_id' => $batch_mapping_id,
                    'year_of_passing' => ''
                ])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->andWhere(['>=', 'C.ESE', $above_marks])
                ->andWhere(['<', 'C.ESE', $eseMin]);

        $exist_student = $query_exist->createCommand()->queryAll();

        return !empty($exist_student) ? json_encode(array_filter($exist_student)) : 0;
    }

    public function actionGetstudentresultexport() 
    {
            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
            $withheld_list = Yii::$app->db->createCommand('SELECT DISTINCT student_map_id as id FROM coe_mark_entry_master WHERE month="'.$_POST['month'].'" AND year="'.$_POST['year'].'" AND withheld="w" ')->queryAll();
            $withheld = [];
            foreach ($withheld_list as $key => $value) {
                $withheld[$value['id']]=$value['id'];
            }

            $internet_copy_query = new Query();

            $internet_copy_query->select('c.register_number,c.name,c.dob,e.subject_code,e.ESE_max,e.credit_points,e.ESE_min,e.CIA_max,e.CIA_min,a.subject_map_id,student_map_id,d.semester,a.CIA,a.ESE,a.total,a.grade_point,a.result,a.withheld,a.grade_name')
            ->from('coe_mark_entry_master a')
            ->join('JOIN','coe_student_mapping b','a.student_map_id=b.coe_student_mapping_id')
            ->join('JOIN','coe_student c','b.student_rel_id=c.coe_student_id')
            ->join('JOIN','coe_subjects_mapping d','a.subject_map_id=d.coe_subjects_mapping_id')
            ->join('JOIN','coe_subjects e','d.subject_id=e.coe_subjects_id')
			->join('JOIN','coe_category_type xyz','xyz.coe_category_type_id = status_category_type_id')
            ->where(['a.year'=>$_POST['year'],'a.month'=>$_POST['month']])            
            //->andWhere(['NOT IN', "a.student_map_id", $withheld])
            ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
            ->groupBy('a.student_map_id,a.subject_map_id')
            ->orderBy('c.register_number'); 
            $internet_copy = $internet_copy_query->createCommand()->queryAll();

            $query_man = new  Query();
            $query_man->select('H.subject_code,  A.name, A.register_number, A.dob, credit_points, H.ESE_min,H.ESE_max, H.CIA_max,H.CIA_min,F.subject_map_id, student_map_id,F.ESE,F.CIA, F.total,F.result,F.semester ,F.withheld,F.grade_name, F.grade_point')
                ->from('coe_student as A')
                ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id ')
                ->join('JOIN', 'coe_bat_deg_reg as C', 'C.coe_bat_deg_reg_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_degree as D', 'D.coe_degree_id=C.coe_degree_id')
                ->join('JOIN', 'coe_programme as E', 'E.coe_programme_id=C.coe_programme_id')
                ->join('JOIN', 'coe_batch as I', 'I.coe_batch_id=C.coe_batch_id')
                ->join('JOIN', 'coe_mandatory_stu_marks as F', 'F.student_map_id=B.coe_student_mapping_id')
                ->join('JOIN', 'coe_category_type as K', 'K.coe_category_type_id=F.month')
                ->join('JOIN', 'coe_mandatory_subcat_subjects as G', 'G.coe_mandatory_subcat_subjects_id=F.subject_map_id')
                ->join('JOIN', 'coe_mandatory_subjects H', 'H.coe_mandatory_subjects_id=G.man_subject_id');
            $query_man->Where(['F.year' => $_POST['year'], 'F.month' => $_POST['month'], 'A.student_status' => 'Active','G.is_additional'=>'NO'])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                //->andWhere(['NOT IN', "F.student_map_id", $withheld]);
            $query_man->groupBy('F.student_map_id,F.subject_map_id')
                ->orderBy('A.register_number');
               
            $mandatory_statement = $query_man->createCommand()->queryAll();
            if(!empty($mandatory_statement))
            {
                $internet_copy = array_merge($internet_copy,$mandatory_statement);
            }
            
            array_multisort(array_column($internet_copy, 'semester'),  SORT_ASC, $internet_copy);
            array_multisort(array_column($internet_copy, 'register_number'),  SORT_ASC, $internet_copy);

            if(isset($internet_copy) && !empty($internet_copy))
            {
               
                  $data ='<table border=1 width="100%" class="table table-responsive table-striped" align="center" ><tbody align="center">'; 
    
                    $data.='<tr>
                                                                
                               <th><center>RegisterNo</center></th>
                               <th><center>Semester</center></th>
                               <th><center>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).'Code</center></th>
                               <th><center>CIA Marks</center></th>
                               <th><center>ESE Marks</center></th>
                               <th><center>Total</center></th>
                               <th><center>Result</center></th>
                               <th><center>Credit Points</center></th>
                               <th><center>Grade</center></th>
                               <th><center>Grade Point</center></th>
                                                        
                            </tr>';    

                    $prev_value="";
                    $prev_value_br="";
                    $sn=1;
                    foreach($internet_copy as $markdetails)
                    {
                        $curr_value=$markdetails['register_number'];
                        $curr_value_br=$markdetails['register_number'];

                        $stu_withheld = 1;
                        $withheld_list = MarkEntryMaster::findOne(['month'=>$_POST['month'],'year'=>$_POST['year'],'student_map_id'=>$markdetails['student_map_id'],'withheld'=>'w']);
                        $stu_withheld = !empty($withheld_list)?2:1;

                        $data.='<tr>';                            
                        $data.='<td>'.$markdetails['register_number'].'</td>';
                        $data.='<td>'.$markdetails['semester'].'</td>';
                        $data.='<td>'.$markdetails['subject_code'].'</td>';
                        /*$data.='<td>'.$markdetails['CIA'].'</td>';
                        $data.='<td>'.$markdetails['ESE'].'</td>';
                        $data.='<td>'.$markdetails['total'].'</td>';
                        $data.='<td>'.$markdetails['result'].'</td>';*/
                        

                        if($markdetails['ESE_max']==0 && $markdetails['CIA_max']==0 && $markdetails['ESE_min']==0 && $markdetails['CIA_min']==0)
                        {
                            $data.='<td colspan=3>COMPLETED</td>';
                            $data.='<td>'.strtoupper($markdetails['result']).'</td>';
                            $data.='<td colspan=3>COMPLETED</td>';
                            
                        }
                        else
                        {
                            if($stu_withheld==2)
                            {
                                $ese_disp='-';
                                $res_dip='WITHHELD';
                                $grade_name='WH';
                                $grade_point = '0';
                                $total_disp=$markdetails['CIA'];
                            }
                            else
                            {
                                $ese_disp=$markdetails['ESE'];
                                $res_dip=strtoupper($markdetails['result']);
                                $grade_name = $markdetails['result']=='Absent' || $markdetails['result']=='ABSENT' || $markdetails['result']=='absent' ? 'U' : strtoupper($markdetails['grade_name']);
                                $grade_point = $markdetails['grade_point'];
                                $total_disp=$markdetails['total'];
                            }
                            $data.='<td>'.$markdetails['CIA'].'</td>';
                            $data.='<td>'.$ese_disp.'</td>';
                            $data.='<td>'.$total_disp.'</td>';
                            $data.='<td>'.$res_dip.'</td>';
                            $data.='<td>'.$markdetails['credit_points'].'</td>';
                            $data.='<td>'.$grade_name.'</td>';
                            $data.='<td>'.$grade_point.'</td>';
                        }

                        
                        $data.='</tr>';  
                        $prev_value=$markdetails['register_number'];
                        $sn++;                    
                        
                    }
                    $data.='</tbody>';        
                    $data.='</table>';
                    if(isset($_SESSION['student_res_export'])){ unset($_SESSION['student_res_export']);}
                    $_SESSION['student_res_export'] = $data;
                    return $data;
            }   
            else
            {
                return 0;
            }

    }
    public function actionGetstudentgraderesults() 
    {
            $withheld_list = Yii::$app->db->createCommand('SELECT DISTINCT student_map_id as id FROM coe_mark_entry_master WHERE month="'.$_POST['month'].'" AND year="'.$_POST['year'].'" AND withheld="w" ')->queryAll();
            $withheld = [];
            foreach ($withheld_list as $key => $value) {
                $withheld[$value['id']]=$value['id'];
            }
            $omit_batch = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MAX_EXAM_CONDUTION);
            $yearsas = DATE('Y');
            $omit_bnathes = $yearsas-$omit_batch;
            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

            $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
            $query = new  Query();
            
            $query->select(['F.student_map_id','B.course_batch_mapping_id','E.programme_code','E.programme_name','D.degree_name','D.degree_code','H.credit_points','F.result','F.grade_point','I.batch_name','A.dob','J.description','G.semester','A.name','A.register_number','round (sum(F.grade_point*H.credit_points)/sum(H.credit_points),2) as gpa'])
            ->from('coe_student as A')                    
            ->join('JOIN','coe_student_mapping as B','B.student_rel_id=A.coe_student_id')
            ->join('JOIN','coe_bat_deg_reg as C','C.coe_bat_deg_reg_id=B.course_batch_mapping_id')
            ->join('JOIN','coe_degree as D','D.coe_degree_id=C.coe_degree_id')
            ->join('JOIN','coe_programme as E','E.coe_programme_id=C.coe_programme_id')
            ->join('JOIN','coe_batch as I','I.coe_batch_id=C.coe_batch_id')
            ->join('JOIN','coe_mark_entry_master as F','F.student_map_id=B.coe_student_mapping_id')
            ->join('JOIN','coe_category_type as J','J.coe_category_type_id=F.mark_type')
            ->join('JOIN','coe_subjects_mapping as G','G.coe_subjects_mapping_id=F.subject_map_id')
            ->join('JOIN','coe_subjects H','H.coe_subjects_id=G.subject_id')
			->join('JOIN','coe_category_type xyz','xyz.coe_category_type_id = status_category_type_id');
                                 
            $query->Where(['F.year'=>$_POST['year'],'F.month'=>$_POST['month'],'A.student_status'=>'Active','F.result'=>'Pass' ])
                  ->andWhere(['NOT LIKE','xyz.description', 'Discontinued'])
				  ->andWhere(['NOT LIKE','xyz.description', 'Detain'])
                  ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                  ->andWhere(['>', 'batch_name', $omit_bnathes])
                  ->andWhere(['NOT IN', "F.student_map_id", $withheld]); 
            $query->groupBy('A.register_number')->orderBy('A.register_number');    
            $internet_copy = $query->createCommand()->queryAll();


            $query_man = new  Query();
            $query_man->select(['F.student_map_id', 'A.name', 'A.register_number', 'A.dob', 'credit_points', 'B.course_batch_mapping_id', 'K.description', 'F.result', 'F.semester', 'F.grade_point', 'D.degree_code','batch_name', 'D.degree_name', 'E.programme_code', 'E.programme_name', 'round ((sum(grade_point*credit_points)/sum(credit_points)),2) as gpa'])
                ->from('coe_student as A')
                ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id ')
                ->join('JOIN', 'coe_bat_deg_reg as C', 'C.coe_bat_deg_reg_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_degree as D', 'D.coe_degree_id=C.coe_degree_id')
                ->join('JOIN', 'coe_programme as E', 'E.coe_programme_id=C.coe_programme_id')
                ->join('JOIN', 'coe_batch as I', 'I.coe_batch_id=C.coe_batch_id')
                ->join('JOIN', 'coe_mandatory_stu_marks as F', 'F.student_map_id=B.coe_student_mapping_id')
                ->join('JOIN', 'coe_category_type as K', 'K.coe_category_type_id=F.mark_type')
                ->join('JOIN', 'coe_mandatory_subcat_subjects as G', 'G.coe_mandatory_subcat_subjects_id=F.subject_map_id')
                ->join('JOIN', 'coe_mandatory_subjects H', 'H.coe_mandatory_subjects_id=G.man_subject_id');
            $query_man->Where(['F.year' => $_POST['year'], 'F.month' => $_POST['month'], 'A.student_status' => 'Active','G.is_additional'=>'NO'])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->andWhere(['>', 'batch_name', $omit_bnathes])
                ->andWhere(['NOT IN', "F.student_map_id", $withheld]);
            $query_man->groupBy('A.register_number')->orderBy('A.register_number');               
            $mandatory_statement = $query_man->createCommand()->queryAll();

            if(!empty($mandatory_statement))
            {
                $internet_copy = array_merge($internet_copy,$mandatory_statement);
            }
            array_multisort(array_column($internet_copy, 'register_number'),  SORT_ASC, $internet_copy);

            if(isset($internet_copy) && !empty($internet_copy))
            {
                  $data ='<table border=1 width="100%" class="table table-responsive table-striped" align="center" ><tbody align="center">'; 
    
                    $data.='<tr>
                               <th><center>SNO</center></th>                                 
                               <th><center>RegisterNo</center></th>
                               <th><center>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).'</center></th>
                               <th><center>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).'</center></th>
                               <th><center>DOB</center></th>
                               <th><center>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' NAME</center></th>
                               <th><center>GPA</center></th>
                               <th><center>CGPA</center></th>
                                                        
                            </tr>';    

                    $prev_value="";
                    $gpa_calc=0;                    
                    $cgpa_calc=0;
                    $credits = 0;
                    $sn=1;
                    $startting_reg_nu = '';
                    $first_reg_num = $internet_copy[0]['register_number'];

                    foreach($internet_copy as $get_grade)
                    {
                        if($get_grade['result']=="Pass" || $get_grade['result']=="PASS" || $get_grade['result']=="pass")
                        {                            
                            $first_reg_gpas [$get_grade['register_number']] =  $get_grade['credit_points']*$get_grade['grade_point'];
                            $first_reg_credits [$get_grade['register_number']] =  $get_grade['credit_points'];

                        }
                    }
                    
                    $loop = $gpa = $cgpa =  0;
                    $credit_point_clas = $grade_point_clas = -1;
                    foreach($internet_copy as $markdetails)
                    {    
                        $sem_calc = ConfigUtilities::SemCaluclation($_POST['year'],$_POST['month'],$markdetails['course_batch_mapping_id']);
                        $degree_name_l = strstr($markdetails['degree_code'], "MBATRISEM")?"MBA":$markdetails['degree_code'];                        
                        $curr_value=$markdetails['register_number'];
                        $data.='<tr>';         
                        $data.='<td>'.$sn.'</td>';                   
                        $data.='<td>'.$markdetails['register_number'].'</td>';
                        $data.='<td>'.$markdetails['batch_name'].'</td>';
                        $data.='<td>'.strtoupper($degree_name_l.".".$markdetails['degree_name']." ").'</td>';
                        $data.='<td>'.date('d-M-y',strtotime($markdetails['dob'])).'</td>';
                        $data.='<td>'.strtoupper($markdetails['name']).'</td>';
                        
                        $cgpa_calc = ConfigUtilities::getCgpaCaluclation($_POST['year'],$_POST['month'],$markdetails['course_batch_mapping_id'],$markdetails['student_map_id'],$sem_calc);

                        $gpa = $markdetails['description']=='Regular'?$cgpa_calc['gpa']:'&nbsp;';
                        
                        $data.='<td>'.$gpa.'</td>';
                       
                        $data.='<td>'.$cgpa_calc['cgpa'].'</td>';
                        $data.='</tr>';  
                        $prev_value=$markdetails['register_number'];
                        $loop++;   
                        $sn++;   
                        
                    }
                    $data.='</tbody>';        
                    $data.='</table>';
                    if(isset($_SESSION['student_grade_res_export'])){ unset($_SESSION['student_grade_res_export']);}
                    $_SESSION['student_grade_res_export'] = $data;
                    return $data;
            }   
            else
            {
                return 0;
            }

    }
    public function actionStudentarkview()
    {
        $reg_num = Yii::$app->post('reg_num');
        $query = 'select A.* from coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_student as C ON C.coe_student_id=B.student_rel_id where C.register_number="'.$reg_num.'" order by year,month';
        $result = Yii::$app->db->createCommand($query);
    }
	
	public function actionGetecportstudentlistelc()
    {
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
         
		$det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
		$semester =$_POST['sem_id'];
		$batch_mapping_id = $_POST['bat_map_val'];
		$subject_id = $_POST['sub_id'];
		$header='';
		require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
		$select_query = "SELECT H.subject_code,concat(D.degree_code,'-',E.programme_code) as 
		degree_code,	H.subject_name,A.register_number,A.name, F.semester,bat.batch_name  FROM  coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id  JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_batch as bat ON bat.coe_batch_id=C.coe_batch_id  JOIN coe_nominal as F ON F.coe_student_id=A.coe_student_id and F.course_batch_mapping_id=B.course_batch_mapping_id JOIN coe_subjects H ON H.coe_subjects_id=F.coe_subjects_id JOIN coe_subjects_mapping as G ON G.subject_id=H.coe_subjects_id and G.batch_mapping_id=F.course_batch_mapping_id and G.batch_mapping_id=B.course_batch_mapping_id WHERE B.course_batch_mapping_id='".$batch_mapping_id."' and F.semester='".$semester."' and F.coe_subjects_id='".$subject_id."' and  A.student_status='Active' AND  status_category_type_id NOT IN ('".$det_cat_type."','".$det_disc_type."') group by A.register_number,semester order by batch_name,degree_code,F.semester,register_number";
		$electList = Yii::$app->db->createCommand($select_query)->queryAll();
		$body='';
		$sn=1;
		  foreach($electList as $rows) 
		  { 
			
				$degree_name = $rows["degree_code"]." SEM ".$rows["semester"];
				$body .='<tr>
					<td align="center">'.$sn.'</td>
					<td align="center">'.$rows["batch_name"].'</td>
					 <td colspan="2"  align="center">'.$rows["degree_code"].'</td>
					 <td  colspan="2" align="center">'.$rows["register_number"].'</td>
					 <td  colspan="2" align="center">'.$rows["name"].'</td>				 
				</tr>';
				$sn++;
				$subject_name = $rows["subject_name"];
				$subject_code = $rows["subject_code"];
				$semester = $rows["semester"];
			
		}
		$header .="<table border=1 align='center' class='table table-striped '>";
                    $header .= '
                    <tr>
                        
                        <td  colspan=8 align="center"> 
                              <center><b><font size="4px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font> Phone : <b>'.$org_phone.'</b> </center><br /><center class="tag_line"><b>'.$org_tagline.'</b></center>
                        </td>
                       
                    </tr>
					<tr>
                        
                        <td  colspan=2 align="center"> 
                              <center><b><font size="4px"> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' CODE : '.$subject_code.'</font></b></center>
                              
                        </td>
						<td  colspan=4 align="center"> 
                              <center><b><font size="4px"> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' NAME : '.$subject_name.'</font></b></center>
                              
                        </td>
						<td  colspan=2 align="center"> 
                              <center><b><font size="4px"> SEMESTER : '.$semester.'</font></b></center>
                              
                        </td>
                       
                    </tr>
                   
                    ';
		$header .="
                    <tr>
					  <th align='center'>S.NO</th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH))."</th>
                      <th colspan='2' align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE))." CODE</th>
                      <th colspan='2' align='center'>REGISTER NUMBER</th>
					  <th colspan='2' align='center'>NAME</th>
                    </tr>";
		
		
	$header .=$body.'</table>';
	$print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['mark-entry/elective-student-repo-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
	]);
	$print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['mark-entry/elective-student-repo-excel'], [
				'class' => 'pull-right btn btn-block btn-warning',
				'target' => '_blank',
				'data-toggle' => 'tooltip',
				'title' => 'Will open the generated PDF file in a new window'
	]);
	if(isset($_SESSION['elective_stu_count'])){ unset($_SESSION['elective_stu_count']);}
	$_SESSION['elective_stu_count'] = $header;
	
	$content_1 = '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_excel . ' </div><div class="col-lg-10" >' . $header . '</div></div></div></div></div>';
	return Json::encode($content_1);
    }

    public function actionGetelectivewaiversubs()
    {
        $stu_reg_num = $_POST['stu_reg_num'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $stu_data = Student::find()->where(['register_number'=>$stu_reg_num])->one();
        if(!empty($stu_data))
        {
           $stuMap = StudentMapping::find()->where(['student_rel_id'=>$stu_data->coe_student_id])->one();
           $sem_verify = ConfigUtilities::SemCaluclation($year,$month,$stuMap->course_batch_mapping_id);
           $getNominal = Nominal::find()->where(['course_batch_mapping_id'=>$stuMap->course_batch_mapping_id,'coe_student_id'=>$stu_data->coe_student_id,'semester'=>$sem_verify,'section_name'=>$stuMap->section_name])->all();

           $check_exists = ElectiveWaiver::find()->where(['student_map_id'=>$stuMap->coe_student_mapping_id])->all();
           if(!empty($check_exists))
           {
                $content_1 = 3;
                return Json::encode($content_1);
           }

           if(!empty($getNominal))
           {
                $subject_ids = [];
                foreach ($getNominal as $value) 
                {
                    $subject_ids[$value['coe_subjects_id']]=$value['coe_subjects_id'];
                }
                $subject_ids = array_filter($subject_ids);
                $getSubMap = SubjectsMapping::find()->where(['batch_mapping_id'=>$stuMap->course_batch_mapping_id])->andWhere(['IN','subject_id',$subject_ids])->all();

                if(!empty($subject_ids) && !empty($getSubMap))
                {
                    $subject_map_ids = [];
                    $senSubMaps = '';
                    foreach ($getSubMap as $subMaps) 
                    {
                       $subject_map_ids[$subMaps['coe_subjects_mapping_id']]=$subMaps['coe_subjects_mapping_id'];
                       $senSubMaps .=$subMaps['coe_subjects_mapping_id'].",";
                    }
                    $subject_map_ids = array_filter($subject_map_ids);
                    $senSubMaps = trim($senSubMaps,',');
                    $checkExam = ExamTimetable::find()->where(['exam_year'=>$year,'exam_month'=>$month])->andWhere(['IN','subject_mapping_id',$subject_map_ids])->all();
                    
                    /*if(!empty($checkExam))
                    {
                        $content_1=1;                    
                    }
                    else
                    {*/
                        $checkMarks = MarkEntry::find()->where(['year'=>$year,'month'=>$month,'student_map_id'=>$stuMap->coe_student_mapping_id])->andWhere(['IN','subject_map_id',$subject_map_ids])->all();
                        $checkMarksMaster = MarkEntryMaster::find()->where(['year'=>$year,'month'=>$month,'student_map_id'=>$stuMap->coe_student_mapping_id])->andWhere(['IN','subject_map_id',$subject_map_ids])->all();
                        /*if(!empty($checkMarks) || !empty($checkMarksMaster))
                        {
                            $content_1=2;   
                        }
                        else
                        {*/
                            $add_table = '';
                            $add_table .= '<table id="checkAllFeattttt" class="table table-striped" align="right" border=1>     
                                      <tr>
                                        
                                        <td colspan=2 align="center"><b> Register Number </b></td>
                                        <td colspan=2 align="center"><b> Name </b></td>
                                        <td colspan=2 align="center"><b> Semester </b></td>
                                        <td align="center"><b> Total Electives </b></td>
                                      </tr>';
                            $add_table .= '   
                                      <tr>
                                        
                                        <td colspan=2 align="center"><b> <input type="hidden" name=stu_id value='.$stuMap->coe_student_mapping_id.' > '.$stu_reg_num.' </b></td>
                                        <td colspan=2 align="center"><b> '.$stu_data->name.' </b></td>
                                        <td  colspan=2 align="center"><b><input type="hidden" name=seme value='.$sem_verify.' > '.$sem_verify.' </b></td>
                                        <td align="center"><b> '.count($subject_map_ids).' </b></td>
                                      </tr>';
                            $add_table .= '   
                                      <tr>
                                        <th><b> SNO </b></th> 
                                        <th><b> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' CODE </b></th>
                                        <th><b> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' NAME </b></th>
                                        <th><b> ACTIONS </b></th>
                                        <th><b> TOTAL WAIVER </b></th>
                                        <th><b> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' CODES (Ex: ABC,ABC) </b></th>
                                        <th><b> REASON </b></th>
                                      </tr>';

                            $send_data = Yii::$app->db->createCommand('SELECT coe_subjects_mapping_id,subject_code,subject_name FROM coe_subjects_mapping as A JOIN coe_subjects as B ON A.subject_id=B.coe_subjects_id WHERE coe_subjects_mapping_id IN ('.$senSubMaps.') group by subject_code')->queryAll();
                            $SnOS = 0;
                            $total_waivers = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ELECTIVE_WAIVER);
                            foreach ($send_data as $senRes) 
                            {
                                $add_table .= '   
                                      <tr>
                                        <td><b> '.($SnOS+1).' </b></td> 
                                        <td><b> '.$senRes['subject_code'].'  </b></td>
                                        <td><b> '.$senRes['subject_name'].'  </b></td>
                                        <td><b>
                                            <input type="hidden" name=elec_sel_wai[] value='.$senRes["coe_subjects_mapping_id"].' >
                                            <input type="checkbox" name=elect_wwa_'.$SnOS. ' onclick="additional_check_waiver(this.id)" id=elect_wwa_'.$SnOS. ' > 
                                        </b>
                                        </td>

                                        <td>
                                            <input type="text" name=total_waiver[] readonly=readonly value="'.$total_waivers.'"  > 
                                        </td>
                                        <td>
                                            <textarea rows="3" cols="20" id=elect_text_'.$SnOS. '  name=completed_subs[] disabled ></textarea>
                                        </td>
                                        <td>
                                            <textarea rows="2" cols="15" id=elect_textare_'.$SnOS. ' name=reason_'.$SnOS.' disabled ></textarea>
                                        </td>
                                      </tr>';
                                $SnOS++;
                            }
                            $add_table .="</table>";
                            $content_1 = $add_table;
                        //}

                    //}
                }
                else
                {
                    $content_1=0;
                }
           }
           else
           {
            $content_1=0;
           }

        }
        else
        {
            $content_1=0;
        }
        return Json::encode($content_1);
    }
    public function actionGetverifypractsubs()
    {
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $mark_type = Yii::$app->request->post('mark_type');
        if(isset($mark_type))
        {
            $checkStuInfo = new Query();
            $checkStuInfo->select('subject_map_id,subject_code')
            ->from('coe_practical_entry as A')      
            ->JOIN('JOIN','coe_subjects_mapping as B','B.coe_subjects_mapping_id=A.subject_map_id')
            ->JOIN('JOIN','coe_subjects as C','C.coe_subjects_id=B.subject_id')
            ->Where(['A.year' => $year, 'A.month' => $month,'mark_type'=>$mark_type])
            ->groupBy(['subject_code'])
            ->orderBy('subject_code');
            $content_1 = $checkStuInfo->createCommand()->queryAll();
        }
        else
        {
            $checkStuInfo = new Query();
            $checkStuInfo->select('subject_map_id,subject_code')
            ->from('coe_practical_entry as A')      
            ->JOIN('JOIN','coe_subjects_mapping as B','B.coe_subjects_mapping_id=A.subject_map_id')
            ->JOIN('JOIN','coe_student_mapping as D','D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id')
            ->JOIN('JOIN','coe_subjects as C','C.coe_subjects_id=B.subject_id')
            ->Where(['A.year' => $year, 'A.month' => $month])
            ->groupBy(['subject_map_id'])
            ->orderBy('subject_code');
            $content_1 = $checkStuInfo->createCommand()->queryAll();
        }
        

        return Json::encode($content_1);
    }
    public function actionGetverifypractsubsdata()
    {
        $year = Yii::$app->request->post('year');
        $batch_id = Yii::$app->request->post('batch_id');
        $dept_id = Yii::$app->request->post('dept_id');
        $month = Yii::$app->request->post('month');
        $mark_type = Yii::$app->request->post('mark_type');
        if(isset($mark_type))
        {
            $checkStuInfo = new Query();
            $checkStuInfo->select('subject_map_id,subject_code')
            ->from('coe_practical_entry as A')      
            ->JOIN('JOIN','coe_subjects_mapping as B','B.coe_subjects_mapping_id=A.subject_map_id')
            ->JOIN('JOIN','coe_subjects as C','C.coe_subjects_id=B.subject_id')
            ->Where(['A.year' => $year, 'A.month' => $month,'mark_type'=>$mark_type,'course_batch_mapping_id'=>$dept_id])
            ->groupBy(['subject_code'])
            ->orderBy('subject_code');
            $content_1 = $checkStuInfo->createCommand()->queryAll();
        }
        else
        {
            $checkStuInfo = new Query();
            $checkStuInfo->select('subject_map_id,subject_code')
            ->from('coe_practical_entry as A')      
            ->JOIN('JOIN','coe_subjects_mapping as B','B.coe_subjects_mapping_id=A.subject_map_id')
            ->JOIN('JOIN','coe_student_mapping as D','D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id')
            ->JOIN('JOIN','coe_subjects as C','C.coe_subjects_id=B.subject_id')
            ->Where(['A.year' => $year, 'A.month' => $month,'course_batch_mapping_id'=>$dept_id])
            ->groupBy(['subject_code'])
            ->orderBy('subject_code');
            $content_1 = $checkStuInfo->createCommand()->queryAll();
        }
        

        return Json::encode($content_1);
    }
    public function actionGetverifypractsubsmigrate()
    {
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $mark_type = Yii::$app->request->post('mark_type');

        if($mark_type=='28')
        {
            $content_1 = 'NO';
        }
        else
        {
            $checkStuInfo = new Query();
            $checkStuInfo->select('subject_map_id,subject_code')
            ->from('coe_practical_entry as A')      
            ->JOIN('JOIN','coe_subjects_mapping as B','B.coe_subjects_mapping_id=A.subject_map_id')
            ->JOIN('JOIN','coe_subjects as C','C.coe_subjects_id=B.subject_id')
            ->Where(['A.year' => $year, 'A.month' => $month,'mark_type'=>$mark_type,'approve_status'=>'NO'])
            ->groupBy(['subject_code'])
            ->orderBy('subject_code');
            $content_1 = $checkStuInfo->createCommand()->queryAll();
           
        }
        return Json::encode($content_1);
    }
    public function actionGetverifypractsubsdeta()
    {
        if(isset($_SESSION['re_print_practical_entry']))
        {
            unset($_SESSION['re_print_practical_entry']);
        }
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $checkStuInfo = new Query();
        $checkStuInfo->select('examiner_name,chief_exam_name,A.student_map_id,A.subject_map_id, register_number,subject_code, out_of_100, approve_status')
        ->from('coe_practical_entry as A')            
        ->JOIN('JOIN','coe_subjects_mapping as B','B.coe_subjects_mapping_id=A.subject_map_id')
        ->JOIN('JOIN','coe_subjects as C','C.coe_subjects_id=B.subject_id')
        ->JOIN('JOIN','coe_student_mapping as D','D.coe_student_mapping_id=A.student_map_id')
        ->JOIN('JOIN','coe_student as E','E.coe_student_id=D.student_rel_id')
        ->Where(['A.year' => $year, 'A.month' => $month,'subject_map_id'=>$sub_map_id,'approve_status'=>'NO'])
        ->groupBy('student_map_id,subject_map_id')
        ->orderBy('register_number');
        $content_1 = $checkStuInfo->createCommand()->queryAll();

        if(empty($content_1))
        {
            $content_1 = 'NO';
        }
        
        return Json::encode($content_1);
    }

    public function actionGetverifypractsubsdetareprint()
    {
        if(isset($_SESSION['re_print_practical_entry']))
        {
            unset($_SESSION['re_print_practical_entry']);
        }
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $examiner_name = Yii::$app->request->post('examiner_name');
        $chief_exam_name = Yii::$app->request->post('chief_exam_name');
        
        
            $reg_num =Yii::$app->request->post('register_num_from');
            $check_reg_number = Student::findOne(['register_number'=>$reg_num]);
            
            if(empty($check_reg_number))
            {
                return Json::encode("NO_reg");
            }
            $stu_map_isa = StudentMapping::findOne(['student_rel_id'=>$check_reg_number->coe_student_id]);
            $checkStuInfo = new Query();
            $checkStuInfo->select('A.student_map_id,A.subject_map_id, register_number,subject_code, out_of_100, approve_status')
            ->from('coe_practical_entry as A')            
            ->JOIN('JOIN','coe_subjects_mapping as B','B.coe_subjects_mapping_id=A.subject_map_id')
            ->JOIN('JOIN','coe_subjects as C','C.coe_subjects_id=B.subject_id')
            ->JOIN('JOIN','coe_student_mapping as D','D.coe_student_mapping_id=A.student_map_id and D.course_batch_mapping_id=B.batch_mapping_id')
            ->JOIN('JOIN','coe_student as E','E.coe_student_id=D.student_rel_id')
            ->Where(['A.year' => $year, 'A.month' => $month,'subject_map_id'=>$sub_map_id])
            ->andWhere(['>=','E.register_number',$reg_num])
            ->groupBy('student_map_id')
            ->orderBy('register_number');
            
            $content_1 = $checkStuInfo->createCommand()->queryAll();
           
            if(empty($content_1))
            {
                return Json::encode("NO_DATA");
            }
            else
            {
                $subject_map_id_de =SubjectsMapping::findOne($sub_map_id); 
                $subjMax = Subjects::findOne($subject_map_id_de->subject_id);
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                $table='';
                $get_month_name=Categorytype::findOne($month);
                $header = $footer = $final_html = $body = '';
              $header = '<table width="100%" >
                <thead class="thead-inverse">
                <tr>
                        <td colspan=4>
                        <table  width="100%" align="center" border="1" >                    
                        <tr>
                          <td> 
                            <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                          </td>

                          <td colspan=2 align="center"> 
                              <center><b>'.$org_name.'</b></center>
                              <center> '.$org_address.'</center>
                              
                              <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                         </td>
                          <td align="center">  
                            <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                          </td>
                        </tr>
                        
                        </table></td></tr>
                        <tr>
                        <td align="center" colspan=4><h5>PRACTICAL MARK ENTRY FOR EXAMINATIONS <b>'.strtoupper($get_month_name['description'])."-".$year.'</b></h5>
                        </td></tr>
                        <tr>
                        <td align="center" colspan=4><h5>MARKS VERIFICATION AND APPROVAL FROM EXAMINER</h5></td></tr>
                        <tr>                                        
                            <td align="right" colspan=4>
                                DATE OF VALUATION : '.date("d/m/Y").'
                            </td> 
                        </tr>
                        <tr>
                            <td align="left" colspan=4> 
                                '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE").' : ('.$subjMax->subject_code.') '.$subjMax->subject_name.'
                            </td>
                        </tr>
                        <tr class="table-danger">
                            <th>SNO</th>  
                            <th>REGISTER NUMBER</th>
                            <th>'.strtoupper("Marks Out of 100").'</th>
                            <th>'.strtoupper("Marks In Words").'</th>
                        </tr>               
                        </thead> 
                        <tbody>     

                        ';
              $footer .='<tr class ="alternative_border">
                    <td align="left" colspan=2>
                        NAME OF THE INTERNAL EXAMINER <br /><br />
                        '.$examiner_name.' <br />
                    </td>
                    <td align="right" colspan=2>
                        NAME OF THE EXTERNAL EXAMINER <br /><br />
                        '.$chief_exam_name.' <br />
                    </td>
                    
                </tr>
                <tr>
                    <td align="left" colspan=2>
                       Signature With Date <br /><br /><br />
                    </td>
                    <td align="right" colspan=2>
                        Signature With Date <br /><br /><br />
                    </td> 
                </tr></tbody></table>';


              $increment = 1;
              $Num_30_nums = 0;
                foreach ($content_1 as $value)
                {
                    if(isset($value["out_of_100"]))
                    {
                        $split_number = str_split($value["out_of_100"]);
                    }
                    $print_text = $this->valueReplaceNumber($split_number);
                    $body .='<tr height="15px"><td>'.$increment.'</td><td>'.$value["register_number"].'</td><td>'.$value["out_of_100"].'</td><td>'.$print_text.'</td></tr>';
                    $increment++;
                    if($increment%31==0)
                    {
                        $Num_30_nums =1;
                        $html = $header.$body.$footer;
                        $final_html .=$html;
                        $html = $body = '';
                    }
                }
              if($Num_30_nums<=30)
              {
                $html = $header.$body.$footer;     
              }                  
              $final_html .=$html;               
              $content = $final_html;
            
            $_SESSION['re_print_practical_entry'] = $content;
            }
        
        return Json::encode($content);
    }

    public function actionGetdummyentsubje()
    {
        $exam_year = Yii::$app->request->post('exam_year');
        $exam_month = Yii::$app->request->post('exam_month');
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $addInQuery = !empty($batch_map_id)?" and batch_mapping_id='".$batch_map_id."' ":'';
        $subjectMapids = Yii::$app->db->createCommand("SELECT distinct C.coe_subjects_id as coe_subjects_id,subject_code FROM coe_exam_timetable as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id  WHERE A.exam_year='".$exam_year."' $addInQuery AND A.exam_month='".$exam_month."' group by B.coe_subjects_mapping_id ORDER BY subject_code")->queryAll();
       
        if(!empty($subjectMapids))
        {
            return Json::encode($subjectMapids);
        }
        else
        {
            return Json::encode(0);
        }
        
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function actionGetintarrearsubjects() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        if($get_regular['coe_category_type_id']==$mark_type)
        {
            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_subjects_mapping as E')                
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->join('JOIN', 'coe_student_mapping as C', 'C.course_batch_mapping_id=E.batch_mapping_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val,'C.course_batch_mapping_id'=>$bat_map_val])
                    ->andWhere(['<>', 'F.CIA_max', 0])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }
        else
        {
           
            
            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_subjects_mapping as E')                
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->join('JOIN', 'coe_student_mapping as C', 'C.course_batch_mapping_id=E.batch_mapping_id')
                    ->join('JOIN', 'coe_mark_entry_master as D', 'D.student_map_id=C.coe_student_mapping_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val,'C.course_batch_mapping_id'=>$bat_map_val,'F.ESE_max' => 0,'F.ESE_min' => 0])
                    ->andWhere(['<>', 'F.CIA_max', 0])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->andWhere(['NOT LIKE','D.result','pass'])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }
        
        $getSubsInfoDet = !empty($getSubsInfoDet) && count($getSubsInfoDet) >0 ? $getSubsInfoDet : 0;

        return Json::encode($getSubsInfoDet);
    }
    public function actionGetintarrearsubjectdetails() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $getDetailsSubs = SubjectsMapping::findOne($sub_map_id);
        $withheld_list = Yii::$app->db->createCommand('SELECT DISTINCT student_map_id as id FROM coe_mark_entry_master WHERE year<="'.$exam_year.'" AND withheld="w" ')->queryAll();
            $withheld = [];
            foreach ($withheld_list as $key => $value) {
                $withheld[$value['id']]=$value['id'];
            }
        $getBatchDe = CoeBatDegReg::findOne($getDetailsSubs->batch_mapping_id);

        $getDepreeInfo = Degree::findOne($getBatchDe->coe_degree_id);
        $checElect = Categorytype::find()->where(['description'=>'Elective'])->one();
        $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $det_det_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Detain%'")->queryScalar();
        $stAbsList = [];
        $get_stu_absents = AbsentEntry::find()->where(['exam_subject_id'=>$sub_map_id,'exam_year'=>$exam_year,'exam_month'=>$month,'exam_type'=>$mark_type,'absent_term'=>$term])->all(); 

        $sem_calc = ConfigUtilities::SemCaluclation($exam_year,$month,$bat_map_val);

        if($sem_calc!=$getDetailsSubs['semester'] && $mark_type==$get_regular['coe_category_type_id'] && $getDepreeInfo->degree_code!='Ph.D')
        {
            return Json::encode(2);
        }

        if(!empty($get_stu_absents))   
        {
            foreach ($get_stu_absents as $absents) 
            {
                $stAbsList[$absents['absent_student_reg']] = $absents['absent_student_reg'];
            }
        }    
        
        $stAbsList = array_filter($stAbsList);

        if($get_regular['coe_category_type_id']==$mark_type)
        {
            $getSubsInfo = new Query();
            $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','E.coe_subjects_mapping_id','CIA_max'])
                    ->from('coe_student as A')
                    ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')                
                    ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val]);
            if($getDetailsSubs['subject_type_id']==$checElect['coe_category_type_id'])
            {
                $getSemester = SubjectsMapping::findOne($getDetailsSubs['coe_subjects_mapping_id']);
                $getSubsInfo->join('JOIN','coe_nominal as G','G.coe_subjects_id=F.coe_subjects_id and G.coe_student_id=A.coe_student_id and G.course_batch_mapping_id=B.course_batch_mapping_id and G.course_batch_mapping_id=E.batch_mapping_id');
                $getSubsInfo->Where(['G.semester' => $getSemester->semester,'G.coe_subjects_id'=>$getDetailsSubs['subject_id'],'G.course_batch_mapping_id'=>$getDetailsSubs['batch_mapping_id']]);
            }
            $getSubsInfo
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->andWhere(['<>', 'status_category_type_id', $det_det_type])
                    ->groupBy('register_number')
                    ->orderBy('register_number');
            $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();

            $check_entry = MarkEntryMaster::find()->where(['subject_map_id'=>$sub_map_id,'year'=>$exam_year,'month'=>$month,'term'=>$term,'mark_type'=>$mark_type])->all();
            $getSubsInfoDetailsse = !empty($check_entry) && count($check_entry)>0 ? 1 : $getSubsInfoDetails;
        }
        else
        {
            $updated = 'No';
            
            $getSubsInfo = new Query();
            $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','E.coe_subjects_mapping_id','CIA_max'])
                    ->from('coe_student as A')
                    ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')                
                    ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                    ->join('JOIN', 'coe_mark_entry_master as G', 'G.student_map_id=B.coe_student_mapping_id and G.subject_map_id=E.coe_subjects_mapping_id')
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val])
                    ->andWhere(['NOT LIKE', 'G.result','Pass'])
                    ->andWhere(['NOT IN', 'status_category_type_id', $det_disc_type]);
            $check_entry = MarkEntryMaster::find()->where(['subject_map_id'=>$sub_map_id,'result'=>'Pass'])->all();
           
            $stuIds=[];
            foreach ($check_entry as $value) 
            {
                $stuIds[$value['student_map_id']]=$value['student_map_id'];
            }
            
            $stuIds = array_filter($stuIds);
            if(!empty($stAbsList))
            {
                $stuIds = array_merge($stuIds,$stAbsList);
                $stuIds = array_filter($stuIds);
            }
            $getSubsInfo->andWhere(['NOT IN', 'student_map_id', $stuIds])
                    ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->groupBy('register_number')
                    ->orderBy('register_number');
            $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();

            $getSubsInfo1 = new Query();
            $getSubsInfo1->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','F.subject_name','E.coe_subjects_mapping_id','CIA_max'])
                    ->from('coe_student as A')
                    ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')                
                    ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
                    ->join('JOIN', 'coe_mark_entry_master as G', 'G.student_map_id=B.coe_student_mapping_id and G.subject_map_id=E.coe_subjects_mapping_id')
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val])
                    ->andWhere(['IN', 'G.student_map_id','Pass'])
                    ->andWhere(['NOT IN', 'G.student_map_id',$stuIds])
                    ->andWhere(['NOT LIKE', 'G.result','Pass'])
                    ->andWhere(['NOT IN', 'status_category_type_id', $det_disc_type])
                    ->groupBy('register_number')
                    ->orderBy('register_number');
            $getSubsInfoDetails_1 = $getSubsInfo1->createCommand()->queryAll();
            if(!empty($getSubsInfoDetails_1))
            {
                $getSubsInfoDetails = array_merge($getSubsInfoDetails,$getSubsInfoDetails_1);
            }

            $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
            
            $check_entry = MarkEntryMaster::find()->where(['subject_map_id'=>$sub_map_id,'year'=>$exam_year,'month'=>$month,'term'=>$term,'mark_type'=>$mark_type])->all();

            $getSubsInfoDetailsse = !empty($check_entry) && count($check_entry)>0 ? 1 : $getSubsInfoDetailsse;
        }

        return Json::encode($getSubsInfoDetailsse);
    }

    public function actionGetchangehalls() 
    {
        $exam_date = date("Y-m-d", strtotime(Yii::$app->request->post('exam_date')));
        $exam_session = Yii::$app->request->post('exam_session');
        $exam_year = Yii::$app->request->post('year');
        $exam_month = Yii::$app->request->post('month');
        $query = new Query();
        $query->select('distinct (B.coe_hall_master_id),B.hall_name')
                ->from('coe_hall_allocate as A')
                ->join('JOIN', 'coe_hall_master as B', 'B.coe_hall_master_id=A.hall_master_id')
                ->join('JOIN', 'coe_exam_timetable as C', 'C.coe_exam_timetable_id=A.exam_timetable_id')
                ->where([
                    "C.exam_date" => $exam_date,
                    'C.exam_year'=>$exam_year,
                    'C.exam_month'=>$exam_month,
                    'C.exam_session'=>$exam_session,
                    'A.year'=>$exam_year,
                    'A.month'=>$exam_month,
                ])->orderBy('hall_name');
        $vals = $query->createCommand()->queryAll();
        return Json::encode($vals);
    }
    public function actionGetchangehallstudents() {
        $exam_date = date("Y-m-d", strtotime(Yii::$app->request->post('exam_date')));
        $exam_session = Yii::$app->request->post('exam_session');
        $hall_id = Yii::$app->request->post('hall_id');
        $exam_year = Yii::$app->request->post('year');
        $exam_month = Yii::$app->request->post('month');
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $get_subs = new Query();
        $get_subs->select(['D.subject_map_id'])
        ->from('coe_hall_allocate as A')
        ->join('JOIN', 'coe_exam_timetable as C', 'C.coe_exam_timetable_id=A.exam_timetable_id and C.exam_year=A.year and C.exam_month=A.month')
        ->join('JOIN', 'coe_student as stu', 'stu.register_number=A.register_number')
        ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=C.subject_mapping_id')
        ->join('JOIN', 'coe_student_mapping as abc', 'abc.student_rel_id=stu.coe_student_id')
        ->join('JOIN', 'coe_dummy_number as D', 'D.subject_map_id=C.subject_mapping_id and D.subject_map_id=B.coe_subjects_mapping_id')
        ->where([ "C.exam_date" => $exam_date,'C.exam_year'=>$exam_year,'C.exam_month'=>$exam_month,
            'C.exam_session'=>$exam_session,'A.year'=>$exam_year,'A.month'=>$exam_month,'D.year'=>$exam_year,'D.month'=>$exam_month,'A.hall_master_id'=>$hall_id])
        ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
        ->groupBy('D.subject_map_id')
        ->orderBy('D.subject_map_id');
        $subs = $get_subs->createCommand()->queryAll();
        $exam_mapping_ids=array_filter([]);
        if(!empty($subs))
        {
            foreach ($subs as $subIdss) 
            {
                $exam_mapping_ids[$subIdss['subject_map_id']] = $subIdss['subject_map_id'];
            }
        }
        
        $query = new Query();
        $query->select(['A.register_number','coe_student_mapping_id','subject_name','name','subject_code','semester','subject_mapping_id','exam_type','exam_term','coe_subjects_id'])
                ->from('coe_hall_allocate as A')
                ->join('JOIN', 'coe_exam_timetable as C', 'C.coe_exam_timetable_id=A.exam_timetable_id and C.exam_year=A.year and C.exam_month=A.month')
                ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=C.subject_mapping_id')
                ->join('JOIN', 'coe_student_mapping as D', 'D.course_batch_mapping_id=B.batch_mapping_id')
                ->join('JOIN', 'coe_student as E', 'E.coe_student_id=D.student_rel_id and E.register_number=A.register_number')
                ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=B.subject_id')
                ->where([
                    "C.exam_date" => $exam_date,
                    'C.exam_year'=>$exam_year,
                    'C.exam_month'=>$exam_month,
                    'C.exam_session'=>$exam_session,
                    'A.year'=>$exam_year,
                    'A.month'=>$exam_month,
                    'A.hall_master_id'=>$hall_id,
                ])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
        if(!empty($exam_mapping_ids))
        {
            $query->andWhere(['NOT IN','subject_mapping_id',$exam_mapping_ids]);
        }   
        $query->orderBy('seat_no');
        $vals = $query->createCommand()->queryAll();

        if(!empty($vals) && count($vals)>0)
        {
            $i=1;
            $header ='<div class="display_stu_res" style="visibility: hidden;"> </div> 
            <div class="display_stu_res_count" style="visibility: hidden;"></div>
            <table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="exam_practical_edit" class="table table-bordered table-responsive bulk_edit_table table-hover"  >
                <thead class="thead-inverse">
                    <tr class="table-danger">
                        <th>Sno</th>
                        <th>Reg No</th>
                        <th>Name</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).'</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>               
                    </thead>';
            $body ='<tbody>';
            foreach ($vals as $key => $value) 
            {
                $stu_id = $value['coe_student_mapping_id'];
                $reg_num = $value['register_number'];
                $reg_num_send = "abs[$stu_id]";  
                $form_name = "ab[$stu_id]";  
                $check_data = "SELECT * FROM coe_absent_entry as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.exam_subject_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id WHERE absent_student_reg='".$stu_id."' AND exam_type='".$value['exam_type']."' AND absent_term='".$value['exam_term']."' AND B.subject_id='".$value['coe_subjects_id']."' AND exam_date='".$exam_date."' AND exam_session='".$exam_session."' and exam_year='".$exam_year."' and exam_month='".$exam_month."'";

                $available_data = Yii::$app->db->createCommand($check_data)->queryAll();
                $status = count($available_data)>0?"checked=true":"";
                $ab_status = $status==""?"Present":"<b style='color: #f00;' >".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)."</b>";
                $body .='<tr>
                        <td valign="top">'.$i.'</td>
                        <td valign="top"><input type="hidden" name="reg_nu_sem_ab_'.$stu_id.'" id="reg_nu_sem_ab_'.$stu_id.'" value="'.$reg_num.'" >'.$reg_num.'</td>
                        <td valign="top">'.$value['name'].'</td>
                        <td valign="top">'.$value['subject_code'].'</td>
                        <td>
                        <label class="control-label" for="absent-name_'.$stu_id.'" value="'.$stu_id.'"  >
                            '.$ab_status.'
                        </label>
                        </td>
                        <td>
                            <input onclick="changeLable(this.id);" id="'.$form_name.'" type="checkbox" name="'.$form_name.'" '.$status.' >
                        </td>

                        <input type="hidden" name="totalCount[]"  value="'.$i.'" />
                        <input type="hidden" name="stuTotal[]"  value="'.$value['coe_student_mapping_id'].'" />
                        <input type="hidden" name="exam_type[]" value="'.$value['exam_type'].'" />
                        <input type="hidden" name="absent_term[]" value="'.$value['exam_term'].'" />
                        <input type="hidden" name="exam_subject_id[]" value="'.$value['subject_mapping_id'].'" />
                    </tr>';
                $i++; 
            }
            $body .='</tbody></table>';
            $send_html = $header.$body;
            return Json::encode($send_html);
        }
        else
        {
            return Json::encode($vals=0);
        }
        
    }
    public function actionGetsubjectinfonamedetails() 
    {
        $subject_code = Yii::$app->request->post('subject_code');
        $getSubDetails = Subjects::findOne(['subject_code'=>$subject_code]);
        $return_details = !empty($getSubDetails)?$getSubDetails:'NO';
        return Json::encode($return_details);
    }
    public function actionGetviewintarrearsubjectdetails() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $sub_map_id = Yii::$app->request->post('sub_map_id');
        $getDetailsSubs = SubjectsMapping::findOne($sub_map_id);
        
        $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $det_det_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Detain%'")->queryScalar();
        $int_cate_id = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Internal%'")->queryScalar();
        
        $sem_calc = ConfigUtilities::SemCaluclation($exam_year,$month,$bat_map_val);
        if($sem_calc!=$getDetailsSubs['semester'] && $mark_type==$get_regular['coe_category_type_id'])
        {
            return Json::encode(2);
        }
        $getSubsInfo = new Query();
        $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','E.coe_subjects_mapping_id','category_type_id_marks','attendance_percentage','attendance_remarks'])
            ->from('coe_student as A')
            ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')
            ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
            ->join('JOIN', 'coe_mark_entry as F', 'F.student_map_id=B.coe_student_mapping_id and F.subject_map_id=E.coe_subjects_mapping_id')
            ->Where(['E.semester' => $sem_id,'E.coe_subjects_mapping_id'=>$sub_map_id,'B.course_batch_mapping_id'=>$bat_map_val,'E.batch_mapping_id'=>$bat_map_val,'year'=>$exam_year,'category_type_id'=>$int_cate_id]);
        if($get_regular['coe_category_type_id']==$mark_type)
        {
            $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_det_type]);
        }
        $getSubsInfo->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                    ->groupBy('register_number')
                    ->orderBy('register_number');
        $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();
        $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails)>0 ? $getSubsInfoDetails : 0;
        return Json::encode($getSubsInfoDetailsse);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function actionGetviewintarrearsubjects() 
    {
        $bat_map_val = Yii::$app->request->post('bat_map_val');
        $exam_year = Yii::$app->request->post('exam_year');
        $month = Yii::$app->request->post('month');
        $sem_id = Yii::$app->request->post('sem_id');
        $term = Yii::$app->request->post('term');
        $mark_type = Yii::$app->request->post('mark_type');
        $get_regular = Categorytype::find()->where(['description'=>'Regular'])->one();
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        
        if($get_regular['coe_category_type_id']==$mark_type)
        {
            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_subjects_mapping as E')                
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->join('JOIN', 'coe_student_mapping as C', 'C.course_batch_mapping_id=E.batch_mapping_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val,'C.course_batch_mapping_id'=>$bat_map_val])
                    ->andWhere(['<>', 'F.CIA_max', 0])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }
        else
        {
            $getSubsInfo = new Query();
            $getSubsInfo->select(['F.subject_code','F.subject_name','E.coe_subjects_mapping_id'])
                    ->from('coe_subjects_mapping as E')                
                    ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
                    ->join('JOIN', 'coe_student_mapping as C', 'C.course_batch_mapping_id=E.batch_mapping_id')
                    ->join('JOIN', 'coe_mark_entry_master as D', 'D.student_map_id=C.coe_student_mapping_id')
                    ->Where(['E.semester' => $sem_id,'E.batch_mapping_id'=>$bat_map_val,'C.course_batch_mapping_id'=>$bat_map_val])
                    ->andWhere(['<>', 'F.CIA_max', 0])
                    ->andWhere(['NOT LIKE','D.result','pass'])
                    ->groupBy('subject_code');
            $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        }
        
        $getSubsInfoDet = !empty($getSubsInfoDet) && count($getSubsInfoDet) >0 ? $getSubsInfoDet : 0;

        return Json::encode($getSubsInfoDet);
    }
    public function actionGetchangeexasubjects() 
    {
        $exam_date = date("Y-m-d", strtotime(Yii::$app->request->post('exam_date')));
        $exam_session = Yii::$app->request->post('exam_session');
        $exam_year = Yii::$app->request->post('year');
        $exam_month = Yii::$app->request->post('month');
        $query = new Query();
        $query->select(['coe_subjects_id', 'C.subject_code'])
                ->from('coe_exam_timetable as A')
                ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=A.subject_mapping_id')
                ->join('JOIN', 'coe_subjects as C', 'C.coe_subjects_id=B.subject_id')
                ->where(['A.exam_date' => $exam_date,'A.exam_year'=>$exam_year,'A.exam_month'=>$exam_month,'A.exam_session'=>$exam_session])->groupBy(['subject_code']);
        $vals = $query->createCommand()->queryAll();
        $vals = !empty($vals)?$vals:0;
        return Json::encode($vals);
    }

    public function actionGetchangeexamsubstudents() 
    {
        $exam_date = date("Y-m-d", strtotime(Yii::$app->request->post('exam_date')));
        $exam_session = Yii::$app->request->post('exam_session');
        $hall_id = Yii::$app->request->post('sub_id');
        $exam_year = Yii::$app->request->post('year');
        $exam_month = Yii::$app->request->post('month');

        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $stu_det_arry =[$det_cat_type,$det_disc_type];
        $get_subs = new Query();
        $get_subs->select(['D.subject_map_id'])
        ->from('coe_hall_allocate as A')
        ->join('JOIN', 'coe_exam_timetable as C', 'C.coe_exam_timetable_id=A.exam_timetable_id and C.exam_year=A.year and C.exam_month=A.month')
        ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=C.subject_mapping_id')
        ->join('JOIN', 'coe_dummy_number as D', 'D.subject_map_id=C.subject_mapping_id and D.subject_map_id=B.coe_subjects_mapping_id and D.year=C.exam_year and D.month=A.month')
        ->where([ "C.exam_date" => $exam_date,'C.exam_year'=>$exam_year,'C.exam_month'=>$exam_month,
            'C.exam_session'=>$exam_session,'A.year'=>$exam_year,'A.month'=>$exam_month,'B.subject_id'=>$hall_id,'D.year'=>$exam_year,'D.month'=>$exam_month])
        ->groupBy('D.subject_map_id')
        ->orderBy('D.subject_map_id');
        $subs = $get_subs->createCommand()->queryAll();
        
        $exam_mapping_ids=array_filter([]);
        if(!empty($subs))
        {
            foreach ($subs as $subIdss) 
            {
                $exam_mapping_ids[$subIdss['subject_map_id']] = $subIdss['subject_map_id'];
            }
        }
        
        $query = new Query();
        $query->select(['A.register_number','coe_student_mapping_id','subject_name','name','subject_code','semester','subject_mapping_id','exam_type','exam_term','coe_subjects_id','status_category_type_id as stu_stat'])
                ->from('coe_hall_allocate as A')
                ->join('JOIN', 'coe_exam_timetable as C', 'C.coe_exam_timetable_id=A.exam_timetable_id and C.exam_year=A.year and C.exam_month=A.month')
                ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=C.subject_mapping_id')
                ->join('JOIN', 'coe_student_mapping as D', 'D.course_batch_mapping_id=B.batch_mapping_id')
                ->join('JOIN', 'coe_student as E', 'E.coe_student_id=D.student_rel_id and E.register_number=A.register_number')
                ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=B.subject_id')
                ->where([
                    "C.exam_date" => $exam_date,
                    'C.exam_year'=>$exam_year,
                    'C.exam_month'=>$exam_month,
                    'C.exam_session'=>$exam_session,
                    'A.year'=>$exam_year,
                    'A.month'=>$exam_month,
                    'B.subject_id'=>$hall_id,
                    'F.coe_subjects_id'=>$hall_id,
                ]);
        if(!empty($exam_mapping_ids))
        {
            $query->andWhere(['NOT IN','subject_mapping_id',$exam_mapping_ids]);
        }
        $query->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
        $query->orderBy('register_number asc');
        $vals = $query->createCommand()->queryAll();
        
        if(!empty($vals) && count($vals)>0)
        {
            $i=1;
            $header ='<div class="display_stu_res" style="visibility: hidden;"> </div> 
            <div class="display_stu_res_count" style="visibility: hidden;"></div>
            <table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="exam_practical_edit" class="table table-bordered table-responsive bulk_edit_table table-hover"  >
                <thead class="thead-inverse">
                    <tr class="table-danger">
                        <th>Sno</th>
                        <th>Reg No</th>
                        <th>Name</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).'</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>               
                    </thead>';
            $body ='<tbody>';
            foreach ($vals as $key => $value) 
            {
                $stu_id = $value['coe_student_mapping_id'];
                $reg_num = $value['register_number'];
                $reg_num_send = "abs[$stu_id]";  
                $form_name = "ab[$stu_id]";  
                $check_data = "SELECT * FROM coe_absent_entry as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.exam_subject_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id WHERE absent_student_reg='".$stu_id."' AND exam_type='".$value['exam_type']."' AND absent_term='".$value['exam_term']."' AND B.subject_id='".$value['coe_subjects_id']."' AND exam_date='".$exam_date."' AND exam_session='".$exam_session."' and exam_year='".$exam_year."' and exam_month='".$exam_month."'";

                $available_data = Yii::$app->db->createCommand($check_data)->queryAll();
                $status = count($available_data)>0?"checked=true":"";
                $ab_status = $status==""?"Present":"<b style='color: #f00;' >".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)."</b>";
                if($value['exam_type']=='27' && !in_array( $value['stu_stat'],$stu_det_arry))
                {
                    $body .='<tr>
                        <td valign="top">'.$i.'</td>
                        <td valign="top"><input type="hidden" name="reg_nu_sem_ab_'.$stu_id.'" id="reg_nu_sem_ab_'.$stu_id.'" value="'.$reg_num.'" >'.$reg_num.'</td>
                        <td valign="top">'.$value['name'].'</td>
                        <td valign="top">'.$value['subject_code'].'</td>
                        <td>
                        <label class="control-label" for="absent-name_'.$stu_id.'" value="'.$stu_id.'"  >
                            '.$ab_status.'
                        </label>
                        </td>
                        <td>
                            <input onclick="changeLable(this.id);" id="'.$form_name.'" type="checkbox" name="'.$form_name.'" '.$status.' >
                        </td>

                        <input type="hidden" name="totalCount[]"  value="'.$i.'" />
                        <input type="hidden" name="stuTotal[]"  value="'.$value['coe_student_mapping_id'].'" />
                        <input type="hidden" name="exam_type[]" value="'.$value['exam_type'].'" />
                        <input type="hidden" name="absent_term[]" value="'.$value['exam_term'].'" />
                        <input type="hidden" name="exam_subject_id[]" value="'.$value['subject_mapping_id'].'" />
                    </tr>';
                    $i++; 
                }
                else if($value['exam_type']=='28' && $value['stu_stat']!=$det_disc_type)
                {
                    $body .='<tr>
                        <td valign="top">'.$i.'</td>
                        <td valign="top"><input type="hidden" name="reg_nu_sem_ab_'.$stu_id.'" id="reg_nu_sem_ab_'.$stu_id.'" value="'.$reg_num.'" >'.$reg_num.'</td>
                        <td valign="top">'.$value['name'].'</td>
                        <td valign="top">'.$value['subject_code'].'</td>
                        <td>
                        <label class="control-label" for="absent-name_'.$stu_id.'" value="'.$stu_id.'"  >
                            '.$ab_status.'
                        </label>
                        </td>
                        <td>
                            <input onclick="changeLable(this.id);" id="'.$form_name.'" type="checkbox" name="'.$form_name.'" '.$status.' >
                        </td>

                        <input type="hidden" name="totalCount[]"  value="'.$i.'" />
                        <input type="hidden" name="stuTotal[]"  value="'.$value['coe_student_mapping_id'].'" />
                        <input type="hidden" name="exam_type[]" value="'.$value['exam_type'].'" />
                        <input type="hidden" name="absent_term[]" value="'.$value['exam_term'].'" />
                        <input type="hidden" name="exam_subject_id[]" value="'.$value['subject_mapping_id'].'" />
                    </tr>';
                    $i++; 
                }
                else
                {

                }
                
            }
            $body .='</tbody></table>';
            $send_html = $header.$body;
            return Json::encode($send_html);
        }
        else
        {
            return Json::encode($vals=0);
        }
        
    }
    public function actionMarkpercentreport()
    {
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
            $batch_id = Yii::$app->request->post('batch_id');
            $year = Yii::$app->request->post('year');
            $month = Yii::$app->request->post('month');
            $month_name = Yii::$app->db->createCommand("select description from coe_category_type where coe_category_type_id='" . $month . "'")->queryScalar();
            $stUdetaIL = Yii::$app->db->createCommand("select student_map_id FROM coe_mark_entry_master 
                            where year_of_passing='' ")->queryAll();
            $noIDs = array_filter(['']);
            if(!empty($stUdetaIL))
            {
                foreach ($stUdetaIL as $key => $values) {
                   $noIDs[$values['student_map_id']]=$values['student_map_id'];
                }
            }
            $query_map_id = new Query();
            $query_map_id->select(['batch_name','count(distinct student_map_id) as count','programme_code','degree_code','B.course_batch_mapping_id'])
                ->from('coe_mark_entry_master A')
                ->join('JOIN', 'coe_student_mapping B', 'B.coe_student_mapping_id=A.student_map_id')
                ->join('JOIN', 'coe_student H', 'H.coe_student_id=B.student_rel_id')
                ->join('JOIN', 'coe_subjects_mapping C', 'C.coe_subjects_mapping_id=A.subject_map_id and C.batch_mapping_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_bat_deg_reg D', 'D.coe_bat_deg_reg_id=C.batch_mapping_id and D.coe_bat_deg_reg_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_programme E', 'E.coe_programme_id=D.coe_programme_id')
                ->join('JOIN', 'coe_batch F', 'F.coe_batch_id=D.coe_batch_id ')
                ->join('JOIN', 'coe_degree G', ' G.coe_degree_id=D.coe_degree_id ')
                ->where(['student_status' => 'Active','F.coe_batch_id'=>$batch_id,'D.coe_batch_id'=>$batch_id])
                ->andWhere(['<=', 'year', $year])
                ->andWhere(['<=', 'month', $month])
                ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                if(!empty($noIDs))
                {
                   $query_map_id->andWhere(['NOT IN', 'student_map_id', $noIDs]);
                }                
                $query_map_id->groupBy('course_batch_mapping_id')->orderby('batch_name,programme_code');
            $students_map_id = $query_map_id->createCommand()->queryAll();
            $fail_array = array();
            if (count($students_map_id) > 0) 
            {
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                $data = '<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';
                $data .= '<tr>
                            <td colspan=2> 
                                <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                            </td>
                            <td colspan=5 align="center"> 
                                <center><b><font size="4px">' . $org_name . '</font></b></center>
                                <center>' . $org_address . '</center>
                                
                                <center>' . $org_tagline . '</center> 
                            </td>
                            <td  colspan=2 align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                            
                        </tr>';
                $data .= '<tr>
                            <td colspan=9 align="center"><b>SINGLE ATTEMPT PASS COUNT FOR ALL SEMESTER TO TILL : ' . $_POST['year'] . ' ' . strtoupper($month_name) . '
                            </b></td>
                        </tr>';
                $data .= '<tr>
                            <th colspan=2 align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)) . ' 
                            </b></th>
                            <th colspan=2 align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)) . ' 
                            </b></th>
                            <th colspan=2 align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)) . ' 
                            </b></th>
                            <th colspan=2 align="center"><b>REGISTERED</b></th>
                            <th align="center"><b>COUNT</b></th>
                        </tr>';
                $count_of_total = $count_of_TOT_total=0;
                foreach ($students_map_id as $key => $value) 
                {
                    $STRENGHT_NOT = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['<>', 'status_category_type_id', $det_disc_type])->count();
                     $data .= '<tr>                                                      
                                <td colspan=2 align="left">'.$value['batch_name'].'</td>
                                <td colspan=2 align="left">'.$value['degree_code'].'</td>
                                <td colspan=2 align="left">'.$value['programme_code'].'</td>
                                <td colspan=2 align="left">'.$STRENGHT_NOT.'</td>
                                <td align="left">'.$value['count'].'</td>
                            </tr>';
                    $count_of_TOT_total +=$STRENGHT_NOT;
                    $count_of_total += $value['count'];
                }
                $data .= '<tr>   
                                <td colspan=6 align="left"><h4><b>TOTAL ' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)) . ' </b></h4></td>    
                                <td colspan=2 align="left"><h4><b>'.$count_of_TOT_total.'</b></td>                                               
                                <td align="left"><h4><b>'.$count_of_total.'</b></td>
                            </tr>
                            </table>';
                if (isset($_SESSION['singlAttemprPass'])) {
                    unset($_SESSION['singlAttemprPass']);
                }
                $_SESSION['singlAttemprPass'] = $data;
                return $data;
            } else {
                return 0;
            }
        
    }

    public function actionViewwithdrawsublist()
    {
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $reg = Yii::$app->request->post('reg');

        $sub_list = Yii::$app->db->createCommand("select coe_subjects_mapping_id,subject_code,subject_name,coe_student_mapping_id from coe_subjects as A,coe_subjects_mapping as B,coe_student as C,coe_student_mapping as D,coe_mark_entry_master as E where A.coe_subjects_id=B.subject_id and C.coe_student_id=D.student_rel_id and B.coe_subjects_mapping_id=E.subject_map_id and D.coe_student_mapping_id=E.student_map_id and C.register_number='" . $reg . "' and grade_name='WD' and status_category_type_id NOT IN('".$det_disc_type."') and E.year='" . $year . "' and E.month='" . $month . "'")->queryAll();
        $table = '';
        $sn = 1;
        $table .= '<table id="checkAllFeat" class="table table-striped" align="right" border=1>     
                   <thead id="t_head">                                                                                                               
                    <th> S.NO </th> 
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Code </th>
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Name </th>  
                    <th> Status </th>
                    </thead><tbody>';
        if (count($sub_list) > 0) 
        {
            foreach ($sub_list as $sublist) {
                $table .= "<tr>" .
                    "<td><input type='hidden' name='sn' value=" . $sn . ">" . $sn . "</td> " .
                    "<td><input type='hidden' name=sub_code" . $sn . " value='" . $sublist['coe_subjects_mapping_id'] . "'>" . $sublist['subject_code'] . "</td>" .
                    "<td><input type='hidden' name=sub_name" . $sn . " value='" . $sublist['subject_name'] . "'>" . $sublist['subject_name'] . "</td>";
                $table .= "<input type='hidden' name='stu_map_id' id='stu_map_id' value='" . $sublist['coe_student_mapping_id'] . "'>";
                $check_mark_entry_master = Yii::$app->db->createCommand("select student_map_id from coe_mark_entry_master where student_map_id='" . $sublist['coe_student_mapping_id'] . "' and subject_map_id='" . $sublist['coe_subjects_mapping_id'] . "' and year='" . $year . "' and month='" . $month . "' and withdraw='wd'")->queryAll();
                if (count($check_mark_entry_master) > 0) {
                    $table .= "</td><td align='center'><input type='checkbox' name=withdraw" . $sn . " checked disabled ></td>";
                } else {
                    $table .= "</td><td align='center'><input type='checkbox' onchange='withdraw_check(this.id)' name=withdraw" . $sn . " id=withdraw_" . $sn . " ></td>";
                }
                $table .= "</tr>";
                $sn++;
            }
            $table .= "</tbody></table>";
            return $table;
        } else {
            return 0;
        }
    }

    public function actionGetsubminmaxinfo()
    {
        $batch_id = Yii::$app->request->post('batchhh_id');
        $min_max_info = Yii::$app->request->post('min_max_info');
        $sub_info_array = ['1'=>'CIA 100','2'=>'ESE 100','3'=>'BOTH 0-0','4'=>'CIA<100','5'=>'ESE<100','6'=>'CIA>100','7'=>'ESE>100','8'=>'BOTH>100','9'=>'BOTH<100'];

        $query_map_id = new Query();
            $query_map_id->select(['batch_name','ESE_max','CIA_max','CIA_min','ESE_min','subject_code','subject_name','semester','degree_code','programme_code'])
                ->from('coe_subjects_mapping C')             
                ->join('JOIN', 'coe_subjects A', 'A.coe_subjects_id=C.subject_id')   
                ->join('JOIN', 'coe_bat_deg_reg D', 'D.coe_bat_deg_reg_id=C.batch_mapping_id')
                ->join('JOIN', 'coe_programme E', 'E.coe_programme_id=D.coe_programme_id')
                ->join('JOIN', 'coe_batch F', 'F.coe_batch_id=D.coe_batch_id ')
                ->join('JOIN', 'coe_degree G', ' G.coe_degree_id=D.coe_degree_id ')
                ->where(['F.coe_batch_id'=>$batch_id,'D.coe_batch_id'=>$batch_id]);
        if($min_max_info==1)
        {
            $query_map_id->andWhere(['=', 'CIA_max', 100])->andWhere(['=', 'ESE_max', 0]);
        }
        else if($min_max_info==2)
        {
            $query_map_id->andWhere(['=', 'ESE_max', 100])->andWhere(['=', 'CIA_max', 0]);
        }
        else if($min_max_info==3)
        {
            $query_map_id->andWhere(['=', 'ESE_max', 0])->andWhere(['=', 'CIA_max', 0]);
        }
        else if($min_max_info==4)
        {
            $query_map_id->andWhere(['<', 'CIA_max', 100]);
        }
        else if($min_max_info==5)
        {
            $query_map_id->andWhere(['<', 'ESE_max', 100]);
        }
        else if($min_max_info==6)
        {
            $query_map_id->andWhere(['>', 'CIA_max', 100]);
        }
        else if($min_max_info==7)
        {
            $query_map_id->andWhere(['>', 'ESE_max', 100]);
        }
        else if($min_max_info==8)
        {
            $query_map_id->andWhere(['>', 'end_semester_exam_value_mark', 100]);
        }
        else if($min_max_info==9)
        {
            $query_map_id->andWhere(['<', 'end_semester_exam_value_mark', 100]);
        }   
                               
        $query_map_id->groupBy('batch_mapping_id')->orderby('batch_name,degree_code,programme_code');
        $sub_list = $query_map_id->createCommand()->queryAll();

        $table = '';
        $sn = 1;
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $table .= '<table border=1 width="100%" style="overflow: scroll;" class="table table-responsive table-striped" align="center" >
        <tr>
            <td colspan=2> 
                <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
            </td>
            <td colspan=5 align="center"> 
                <center><b><font size="4px">' . $org_name . '</font></b></center>
                <center>' . $org_address . '</center>
                
                <center>' . $org_tagline . '</center> 
            </td>
            <td  colspan=2 align="center">  
                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
            </td>
            
        </tr>
        <tr>
                    <th> S.NO </th> 
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH) . ' </th> 
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . ' </th> 
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Code </th>
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' Name </th>  
                    <th> CIA MIN </th>
                    <th> CIA MAX </th>
                    <th> ESE MIN </th>
                    <th> ESE MAX </th></tr>
                    ';
        
        if (count($sub_list) > 0) 
        {
            foreach ($sub_list as $sublist) 
            {
                $table .= "<tr>" .
                    "<td>" . $sn . "</td> " .
                    "<td>" . $sublist['batch_name'] . "</td>" .
                    "<td>" . $sublist['degree_code']."-".$sublist['programme_code'] . "</td>" .
                    "<td>" . $sublist['subject_code'] . "</td>" .
                    "<td>" . $sublist['subject_name'] . "</td>
                    <td>" . $sublist['CIA_min'] . "</td>
                    <td>" . $sublist['CIA_max'] . "</td>
                    <td>" . $sublist['ESE_min'] . "</td>
                    <td>" . $sublist['ESE_max'] . "</td>";
                $table .= "</tr>";
                $sn++;
            }
            $table .= "</table>";
            if(isset($_SESSION['sub_max_min_info']))
            {
                unset($_SESSION['sub_max_min_info']);
            }
            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['batch/sub-min-max-info-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
            ]);
            $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/batch/excel-sub-min-max-info-pdf'], [
                        'class' => 'pull-right btn btn-block btn-warning',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
            ]);
            $_SESSION['sub_max_min_info'] =$table;
            $add_duiv ='<div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto;" >';
            $content_1 = '<br /><div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf . ' <br /> ' . $print_excel . ' </div><div class="col-lg-10" >' .$add_duiv. $table . '</div></div></div></div></div></div>';

            return $content_1;
        } else {
            return 0;
        }
    }

    public function actionGetcianotzero()
    {
        $batch_id = Yii::$app->request->post('batchhh_id');
        $config_attempt = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CIA_ZEO);
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $GETiDiNFOR = CoeBatDegReg::find()->where(['coe_batch_id'=>$batch_id])->all();
        $batch_name = Batch::findOne($batch_id);
        $min_no = $max_no =array_filter(['']);
        foreach ($GETiDiNFOR as $key => $min_max) 
        {
            $studentMap = StudentMapping::find()->where(['course_batch_mapping_id'=>$min_max['coe_bat_deg_reg_id']])->orderBy('coe_student_mapping_id asc')->one();
            $studentMap_1 = StudentMapping::find()->where(['course_batch_mapping_id'=>$min_max['coe_bat_deg_reg_id']])->orderBy('coe_student_mapping_id DESC')->one();
            $min_no[$studentMap['coe_student_mapping_id']]= $studentMap['coe_student_mapping_id'];
            $max_no[$studentMap_1['coe_student_mapping_id']]= $studentMap_1['coe_student_mapping_id'];
        }
        $min_no = array_filter($min_no);
        $max_no = array_filter($max_no); 
        $stu_start = !empty($min_no)? min($min_no):0;
        $stu_end = !empty($max_no)? max($max_no):0;
        
        if($stu_start==0 || $stu_end==0)
        {
            return 0;
        }

        $geteStuL = new Query();
            $geteStuL->select(['count(*)','student_map_id','subject_map_id'])
                ->from('coe_mark_entry_master A')
                ->andWhere(['<>','CIA',0])
                ->andWhere(['between','student_map_id',$stu_start,$stu_end]);
        $geteStuL->groupBy('student_map_id,subject_map_id')->having(['>','count(*)',$config_attempt])->orderby('subject_map_id');
        $getStuList = $geteStuL->createCommand()->queryAll();

        if(count($getStuList)==0 || empty($getStuList))
        {
            return 0;
        }

        $sub_list = array_filter(['']);
        if(!empty($getStuList))
        {
            foreach ($getStuList as $key => $stuMapId) 
            {
                $query_map_id = new Query();
                $query_map_id->select(['batch_name','register_number','ESE','CIA','total','subject_code','year','month','degree_code','programme_code'])
                        ->from('coe_mark_entry_master A')
                        ->join('JOIN', 'coe_student_mapping B', 'B.coe_student_mapping_id=A.student_map_id')
                        ->join('JOIN', 'coe_student H', 'H.coe_student_id=B.student_rel_id')
                        ->join('JOIN', 'coe_subjects_mapping C', 'C.coe_subjects_mapping_id=A.subject_map_id and C.batch_mapping_id=B.course_batch_mapping_id')
                        ->join('JOIN', 'coe_bat_deg_reg D', 'D.coe_bat_deg_reg_id=C.batch_mapping_id and D.coe_bat_deg_reg_id=B.course_batch_mapping_id')
                        ->join('JOIN', 'coe_programme E', 'E.coe_programme_id=D.coe_programme_id')
                        ->join('JOIN', 'coe_batch F', 'F.coe_batch_id=D.coe_batch_id ')
                        ->join('JOIN', 'coe_degree G', ' G.coe_degree_id=D.coe_degree_id ')
                        ->join('JOIN', 'coe_subjects abc', 'abc.coe_subjects_id=C.subject_id ')
                        ->where(['student_status' => 'Active','F.coe_batch_id'=>$batch_id,'D.coe_batch_id'=>$batch_id,'student_map_id'=>$stuMapId['student_map_id'],'subject_map_id'=>$stuMapId['subject_map_id'],'coe_subjects_mapping_id'=>$stuMapId['subject_map_id'],'coe_student_mapping_id'=>$stuMapId['student_map_id']])
                        ->andWhere(['<>','CIA',0])
                        ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                $query_map_id->orderby('coe_mark_entry_master_id DESC')->limit(1);
                $sub_list[] = $query_map_id->createCommand()->queryOne();
            }
            
        }
        $table = '';
        $sn = 1;
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        $table .= '<table border=1 width="100%" style="overflow: scroll;" class="table table-responsive table-striped" align="center" >
        <tr>
            <td colspan=2> 
                <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
            </td>
            <td colspan=6 align="center"> 
                <center><b><font size="4px">' . $org_name . '</font></b></center>
                <center>' . $org_address . '</center>
                
                <center>' . $org_tagline . '</center> 
                <center> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)." NAME ". $batch_name->batch_name . '</center> 
            </td>
            <td  colspan=2 align="center">  
                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
            </td>
            
        </tr>
        <tr>
                    <th> S.NO </th> 
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH) . ' </th> 
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE) . ' </th> 
                    <th> REGISTER NUMBER </th> 
                    <th> ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . ' CODE </th>
                    <th> CIA </th>  
                    <th> ESE </th>
                    <th> TOTAL </th>
                    <th> YEAR</th>
                    <th> MONTH </th></tr>
                    ';

        array_filter($sub_list);
        sort($sub_list);
        if (count($sub_list) > 0) 
        {
            $sn=1;
            for ($i=0; $i <count($sub_list) ; $i++) 
            { 
                if(!empty($sub_list[$i]))
                {
                    $month_name = Categorytype::findOne($sub_list[$i]['month']);
                    $table .= "<tr>" .
                        "<td>" . $sn . "</td> " .
                        "<td>" . $sub_list[$i]['batch_name'] . "</td>" .
                        "<td>" . $sub_list[$i]['degree_code']."-".$sub_list[$i]['programme_code'] . "</td>" .
                        "<td>" . $sub_list[$i]['register_number'] . "</td>" .
                        "<td>" . $sub_list[$i]['subject_code'] . "</td>
                        <td>" . $sub_list[$i]['CIA'] . "</td>
                        <td>" . $sub_list[$i]['ESE'] . "</td>
                        <td>" . $sub_list[$i]['total'] . "</td>
                        <td>" . $sub_list[$i]['year'] . "</td>
                        <td>" . $month_name['description'] . "</td>";
                    $table .= "</tr>";
                    $sn++;
                }
                
            }
           
            $table .= "</table>";
            if(isset($_SESSION['get_count_not_zero']))
            {
                unset($_SESSION['get_count_not_zero']);
            }
            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['batch/get-cia-not-zero-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
            ]);
            $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/batch/get-cia-not-zero-excel'], [
                        'class' => 'pull-right btn btn-block btn-warning',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
            ]);
            $_SESSION['get_count_not_zero'] =$table;
            $add_duiv ='<div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto;" >';
            $content_1 = '<br /><div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf . ' <br /> ' . $print_excel . ' </div><div class="col-lg-10" >' .$add_duiv. $table . '</div></div></div></div></div></div>';

            return $content_1;
        } else {
            return 0;
        }
    }

    public function actionViewconolidatereport()
    {
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $year = Yii::$app->request->post('year');
        $batch_id = Yii::$app->request->post('batch_id');
        $degree_name = Yii::$app->request->post('degree_name');
        $month = Yii::$app->request->post('month');

        $query_map_id = new Query();
        $query_map_id->select(['batch_name','year','H.description as month_name','month','course_batch_mapping_id','degree_code','programme_code'])
                ->from('coe_mark_entry_master A')
                ->join('JOIN', 'coe_student_mapping B', 'B.coe_student_mapping_id=A.student_map_id')
                ->join('JOIN', 'coe_subjects_mapping C', 'C.coe_subjects_mapping_id=A.subject_map_id and C.batch_mapping_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_bat_deg_reg D', 'D.coe_bat_deg_reg_id=C.batch_mapping_id and D.coe_bat_deg_reg_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_programme E', 'E.coe_programme_id=D.coe_programme_id')
                ->join('JOIN', 'coe_batch F', 'F.coe_batch_id=D.coe_batch_id ')
                ->join('JOIN', 'coe_degree G', ' G.coe_degree_id=D.coe_degree_id ')
                ->join('JOIN', 'coe_category_type H', 'H.coe_category_type_id=A.month')
                ->where(['student_status' => 'Active','F.coe_batch_id'=>$batch_id,'D.coe_batch_id'=>$batch_id,'degree_type'=>$stuMapId['degree_name'],'mark_type'=>27])
                ->andWhere(['NOT IN', 'status_category_type_id', $det_disc_type,$det_cat_type])
                ->groupBy('course_batch_mapping_id');
        $query_map_id->orderby('degree_code,programme_code ASC')->limit(1);
        $sub_list = $query_map_id->createCommand()->queryAll();

        $table = '';
        $sn = 1;
        $total_deg=0;
        

        
        if (count($sub_list) > 0) 
        {
            $add_table = '';
            foreach ($sub_list as $key => $value) 
            {
                $add_table = '<th>'.strtoupper($value['degree_code'])."-".strtoupper($value['programme_code']).'</th>';
                $total_deg++;
            }
            $table .= '<table id="checkAllFeat" class="table table-striped" border=1>     
                   <thead id="t_head">                                                                                                               
                    <th> S.NO </th> 
                    <th> TITLE </th>'.$add_table;
                    
            $table .='<th> TOTAL </th>
                        </thead>
                        <tbody>';
            $repeat= 1;
            $table .= "<tr><td>Total Number Of Students</td>";
            foreach ($sub_list as $sublist) 
            { 
                $a = StudentMapping::find()->where(['course_batch_mapping_id'=>$sublist['course_batch_mapping_id']])->andWhere(['<>','status_category_type_id',$det_disc_type])->count();
                $total_stu =!empty($a)?$a:'-';
                $table .= '<td>'.$total_stu."</td>";
                $count_of_total[$sublist['course_batch_mapping_id']]=[$a];
            }
            $table .= "</tr>";
            $table .= "<tr><td>Number Of Students Absents in All Subjects</td>";
            foreach ($sub_list as $ab_count) 
            { 
                $sem_calc = ConfigUtilities::SemCaluclation($ab_count['year'],$ab_count['month'],$ab_count['course_batch_mapping_id']);
                $getAllSubs = SubjectsMapping::find()->where(['course_batch_mapping_id'=>$ab_count['course_batch_mapping_id'],'semester'=>$sem_calc])->all();
                $allSubsMapps ='';
                foreach ($getAllSubs as $key => $values) {
                   $allSubsMapps .=$values['coe_subjects_mapping_id'].", ";
                }
                $allSubsMapps = trim($allSubsMapps,', ');
                $b =Yii::$app->db->createCommand('SELECT COUNT(absent_student_reg) AS count FROM coe_absent_entry as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg WHERE B.course_batch_mapping_id="'.$ab_count['course_batch_mapping_id'].'" and A.exam_year="'.$ab_count['year'].'" and A.exam_month="'.$ab_count['month'].'" and exam_type="27" group by absent_student_reg');
                $total_stu =!empty($a)?$a:'-';
                $table .= '<td>'.$total_stu."</td>";
            }
            $table .= "</tr>";
            $table .= "</tbody></table>";
            return $table;
        } else {
            return 0;
        }
    }
	public function actionGetstudentinfo()
    {
        $reg_num = Yii::$app->request->post('reg_num');
        $getStu = Student::findOne(['register_number'=>$reg_num]);

        return !empty($getStu)?"<h3 style='color: #f00;'>".$getStu->name."</h3>":0; 
    }

    public function actionGetrevalrepo()
    {
       
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $reval_status_entry = Categorytype::find()->where(['category_type'=>'Revaluation'])->orWhere(['description'=>'Revaluation'])->one();
        $ese = Categorytype::find()->where(['category_type'=>'ESE'])->orWhere(['description'=>'ESE'])->one();
        $ese_dummy = Categorytype::find()->where(['category_type'=>'ESE(Dummy)'])->orWhere(['description'=>'ESE(Dummy)'])->one();
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $month_NAMEd = Categorytype::findOne($month);

        $query_map_id = new Query();
        $query_map_id->select(['register_number','A.year','H.description as month_name','A.grade_name','degree_code','programme_code','subject_code','subject_name','A.CIA','CIA_max','A.ESE','ESE_max','A.total','A.student_map_id','A.subject_map_id','course_batch_mapping_id','coe_subjects_id','A.mark_type','A.term','A.month'])
                ->from('coe_mark_entry_master A')
                ->join('JOIN', 'coe_student_mapping B', 'B.coe_student_mapping_id=A.student_map_id')
                ->join('JOIN', 'coe_student stu', 'stu.coe_student_id=B.student_rel_id')
                ->join('JOIN', 'coe_subjects_mapping C', 'C.coe_subjects_mapping_id=A.subject_map_id and C.batch_mapping_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_subjects sub', 'sub.coe_subjects_id=C.subject_id')
                ->join('JOIN', 'coe_bat_deg_reg D', 'D.coe_bat_deg_reg_id=C.batch_mapping_id and D.coe_bat_deg_reg_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_programme E', 'E.coe_programme_id=D.coe_programme_id')
                ->join('JOIN', 'coe_degree G', ' G.coe_degree_id=D.coe_degree_id ')
                ->join('JOIN', 'coe_category_type H', 'H.coe_category_type_id=A.month')
                ->join('JOIN', 'coe_mark_entry I', 'I.student_map_id=A.student_map_id and I.subject_map_id=A.subject_map_id and I.month=A.month and I.year=A.year and I.mark_type=A.mark_type')
                ->where(['student_status' => 'Active','A.year'=>$year,'A.month'=>$month,'I.year'=>$year,'I.month'=>$month,'I.category_type_id'=>$reval_status_entry['coe_category_type_id']])
                ->andWhere(['NOT IN', 'status_category_type_id', $det_disc_type])
                ->groupBy('A.student_map_id,A.subject_map_id');
        $query_map_id->orderby('register_number asc');
        $sub_list = $query_map_id->createCommand()->queryAll();

        $table = '';
        $sn = 1;
        $total_deg=0;
        if (count($sub_list) > 0) 
        {
            $add_table = '';
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            $table .= '<table id="checkAllFeat" class="table table-striped" border=1>  
                <tr>
                   
                    <td colspan=8 align="center"> 
                        <center><b><font size="4px">'.$org_name.'</font></b></center>
                        <center>'.$org_address.'</center>
                        
                        <center>'.$org_tagline.'</center> 
                    </td>
                  
                </tr>
                <tr>
                    <td colspan=8 align="center"> 
                      <h3>REVALUATION RESULTS - '.strtoupper($month_NAMEd['description']).'</3>
                    </td>
                </tr>
                <tr>                      
                    <th> S.NO </th> 
                    <th> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).' </th>
                    <th> REGISTER NUMBER </th>
                    
                    <th> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </th>
                    <th> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME </th>
                    <th> GRADE </th>';
            $table .='</tr><tbody>';
            $repeat= 1;
            foreach ($sub_list as $sublist) 
            { 
                 $table .= "<tr>";
                $table .= '<td>'.$repeat.'</td>';
                $coe_batch_id = CoeBatDegReg::findOne($sublist['course_batch_mapping_id']);
                $regulation_year = Regulation::find()->where(['regulation_year' => $coe_batch_id->regulation_year])->all();

                $markEntry = Yii::$app->db->createCommand("SELECT * FROM coe_mark_entry where student_map_id='".$sublist['student_map_id']."' and subject_map_id='".$sublist['subject_map_id']."'  and year='".$sublist['year']."' and month='".$sublist['month']."' and term='".$sublist['term']."'  and mark_type='".$sublist['mark_type']."' and category_type_id IN('".$ese_dummy['coe_category_type_id']."','".$ese['coe_category_type_id']."')")->queryOne();

                $ese_marks = 0;
                $cia_marks = $sublist['CIA'];
                if(!empty($markEntry))
                {
                    $ese_marks = $markEntry['category_type_id_marks'];
                }
                $subject_details = Subjects::findOne($sublist['coe_subjects_id']);
                $ese_max = $subject_details->ESE_max;
                $convert_ese_marks =  round( ($ese_marks*$ese_max)/100 );
                $total_marks = $insert_total = $cia_marks+$convert_ese_marks;
                $student_res_data = array_filter(array(''));

                 $config_attempt = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CIA_ZEO);
                 $check_attempt = Yii::$app->db->createCommand("SELECT count(*) FROM coe_mark_entry_master WHERE student_map_id='".$sublist['student_map_id']."' and subject_map_id='".$sublist['subject_map_id']."' AND result not like '%pass%' ")->queryScalar();

                foreach ($regulation_year as $value)    
                  {
                      if($value['grade_point_to']!='')
                      {              
                          if($total_marks >= $value['grade_point_from'] &&  $total_marks <= $value['grade_point_to'] )
                          {
                           
                           if($check_attempt > $config_attempt)
                           {
                                if($convert_ese_marks<$subject_details->ESE_min || $total_marks<$subject_details->total_minimum_pass)
                                  {
                                    $result_stu = 'Fail';                                
                                    $grade_name_ins = 'U';
                                    $student_res_data = ['result'=>$result_stu,'total_marks'=>$total_marks,'grade_name'=>$grade_name_ins,'grade_point'=>0,'year_of_passing'=>'','ese_marks'=>$convert_ese_marks];        
                                  }      
                                  else
                                  {
                                    $grade_name_prit = $value['grade_name'];
                                    $grade_point_arts = $value['grade_point'];
                                    $student_res_data = ['result'=>'Pass','total_marks'=>$total_marks,'grade_name'=>$grade_name_prit,'grade_point'=>$grade_point_arts,'ese_marks'=>$convert_ese_marks];                                
                                  }
                           }
                           else
                           {
                                if($cia_marks<$subject_details->CIA_min || $convert_ese_marks<$subject_details->ESE_min || $total_marks<$subject_details->total_minimum_pass)
                                  {
                                    $result_stu = 'Fail';                                
                                    $grade_name_ins = 'U';
                                    $student_res_data = ['result'=>$result_stu,'total_marks'=>$total_marks,'grade_name'=>$grade_name_ins,'grade_point'=>0,'year_of_passing'=>'','ese_marks'=>$convert_ese_marks];        
                                  }      
                                  else
                                  {
                                    $grade_name_prit = $value['grade_name'];
                                    $grade_point_arts = $value['grade_point'];
                                    $student_res_data = ['result'=>'Pass','total_marks'=>$total_marks,'grade_name'=>$grade_name_prit,'grade_point'=>$grade_point_arts,'ese_marks'=>$convert_ese_marks];                                
                                  }
                           }
                              
                          } // Grade Point Caluclation
                      } // Not Empty of the Grade Point 
                  }   

                $grade_show_dsip =  $student_res_data['grade_name'] === $sublist['grade_name'] ?'NC':$sublist['grade_name'];
                $table .= "<td>".$sublist['degree_code']."-".$sublist['programme_code']."</td>";
                $table .= "<td>".$sublist['register_number']."</td>";
                $table .= "<td>".$sublist['subject_code']."</td>";
                $table .= "<td>".$sublist['subject_name']."</td>";
                $table .= "<td>".$grade_show_dsip."</td>";                  
                $table .= "</tr>";
                $repeat++;
            }            
            $table .= "</tbody></table>";

            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['batch/reval-print-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
            ]);
            $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/batch/reval-print-excel'], [
                        'class' => 'pull-right btn btn-block btn-warning',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
            ]);
            if(isset($_SESSION['reval_report']))
            {
                unset($_SESSION['reval_report']);
            }
            $_SESSION['reval_report'] =$table;
            $content_1 = '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf . ' <br /> ' . $print_excel . ' </div><div class="col-lg-10" >' . $table . '</div></div></div></div></div>';

            return $content_1;
        } else {
            return 0;
        }
    }

    public function actionGetrevalsubjecs()
    {
        $month = Yii::$app->request->post('month');
        $year = Yii::$app->request->post('year');
        $query_map_id = new Query();
        $query_map_id->select(['subject_code','coe_subjects_id as sub_id'])
                ->from('coe_mark_entry_master A')
                ->join('JOIN', 'coe_student_mapping B', 'B.coe_student_mapping_id=A.student_map_id')
                ->join('JOIN', 'coe_subjects_mapping C', 'C.coe_subjects_mapping_id=A.subject_map_id and C.batch_mapping_id=B.course_batch_mapping_id')
                ->join('JOIN', 'coe_subjects sub', 'sub.coe_subjects_id=C.subject_id')
                ->join('JOIN', 'coe_revaluation H', 'H.subject_map_id=A.subject_map_id and H.student_map_id=A.student_map_id and H.year=A.year and H.month=A.month and H.mark_type=A.mark_type and H.subject_map_id=C.coe_subjects_mapping_id')
                ->where(['A.year'=>$year,'A.month'=>$month,'H.year'=>$year,'H.month'=>$month,'reval_status'=>'YES']);
        $query_map_id->groupBy('coe_subjects_id,subject_code');
        $sub_list = $query_map_id->createCommand()->queryAll();
        return  !empty($sub_list)? json_encode($sub_list):json_encode(0);
    }

    public function actionGetrevalstulistapp()
    {
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $subject_id = Yii::$app->request->post('subject_id'); 
        $mark_out_of = Yii::$app->request->post('mark_out_of'); 
        $out_of = $mark_out_of==1?100:'Maximum';
       
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $cat_mod_mark_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where description like '%moderation%'")->queryScalar();
        $cat_rev_mark_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where description like '%revaluation%'")->queryScalar();
        $cat_ese_mark_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where description like 'ese%'")->queryScalar();

        $cat_ese_dum_mark_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where description like '%ESE(Dummy)%'")->queryScalar();

        $stu_mark_id = Yii::$app->db->createCommand("select DISTINCT register_number as register_number,name,A.coe_subjects_id,A.subject_code,A.subject_name,C.subject_map_id,B.coe_subjects_mapping_id,A.ESE_min,A.ESE_max,A.total_minimum_pass,C.CIA,C.ESE,C.total,C.result,C.student_map_id,C.grade_point,C.grade_name FROM coe_subjects as A JOIN coe_subjects_mapping as B ON B.subject_id=A.coe_subjects_id JOIN coe_mark_entry_master as C ON C.subject_map_id=B.coe_subjects_mapping_id JOIN coe_student_mapping as D ON D.coe_student_mapping_id=C.student_map_id and D.course_batch_mapping_id=B.batch_mapping_id JOIN coe_student as stu ON stu.coe_student_id=D.student_rel_id JOIN coe_revaluation as E ON E.student_map_id=C.student_map_id and E.subject_map_id=C.subject_map_id and E.year=C.year and E.mark_type=C.mark_type where coe_subjects_id='" . $subject_id . "' and C.year='".$year."' and C.month='".$month."' and E.reval_status='YES' and status_category_type_id NOT IN('".$det_disc_type."') and E.year='" . $year . "' and E.month='" . $month . "' order by stu.register_number")->queryAll();
       
        $table = '';
        $sn = 1;
       
            if (count($stu_mark_id) > 0) 
            {
                $table .= '<table id="checkAllFeat" class="table table-striped" align="right" border=1>     
                           <thead id="t_head">                                                                                                               
                            <th> S.NO </th> 
                            <th> Reg NO </th>                  
                            <th> Name </th>  
                            <th> CIA </th>
                            <th> Old ESE </th>
                            <th> Old ESE<br /> (OUT OF '.$out_of.') </th>
                            <th> Old Total </th>
                            <th> Old Result </th>';
                $table .= ' <th> Reval ESE </br> out of '.$out_of.'</th>
                            <th> Reval ESE </th>
                            <th> Reval Total </th>
                            <th> Reval Result </th>
                            </thead><tbody>';
                
                $checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
                foreach ($stu_mark_id as $stu_mark_id1) 
                {
                    $previous_ese_100 = Yii::$app->db->createCommand("select * from coe_mark_entry where student_map_id='" . $stu_mark_id1['student_map_id'] . "' and category_type_id IN('" . $cat_ese_mark_type . "','" . $cat_ese_dum_mark_type . "') and subject_map_id='" . $stu_mark_id1['subject_map_id'] . "' and year='".$year."' and month='".$month."'")->queryOne();
                    $pre_ese =$previous_ese_100['category_type_id_marks'];

                     $check_rev_done = Yii::$app->db->createCommand("select * from coe_mark_entry where student_map_id='" . $stu_mark_id1['student_map_id'] . "' and subject_map_id='" . $stu_mark_id1['subject_map_id'] . "' and year='" . $year . "' and month='" . $month . "' and category_type_id='" . $cat_rev_mark_type . "' ")->queryOne();

                    $checkGetDum = Yii::$app->db->createCommand("select * from coe_dummy_number where student_map_id='" . $stu_mark_id1['student_map_id'] . "' and subject_map_id='" . $stu_mark_id1['subject_map_id'] . "' and year='" . $year . "' and month='" . $month . "' ")->queryOne();
                    $print_dum = !empty($checkGetDum)?$checkGetDum['dummy_number']:'NO DATA';

                    $config_attempt = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CIA_ZEO);
                    
                      $check_attempt = Yii::$app->db->createCommand('SELECT count(*) FROM coe_mark_entry_master WHERE subject_map_id="' . $stu_mark_id1['subject_map_id'] . '" AND student_map_id="' . $stu_mark_id1['student_map_id']. '" AND result not like "%pass%" ')->queryScalar();
                      $get_sub_info = Yii::$app->db->createCommand('SELECT * FROM coe_subjects as A JOIN coe_subjects_mapping as B ON B.subject_id=A.coe_subjects_id WHERE coe_subjects_mapping_id="'.$stu_mark_id1['subject_map_id'].'"  ')->queryOne();
                      require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                      $final_sub_total = $get_sub_info['ESE_max']+$get_sub_info['CIA_max'];
                      $ese_marks = round($pre_ese*$get_sub_info['ESE_max']/100);
                        $status_check = $ese_marks<=$get_sub_info['ESE_max'] ? 'YES' : 'NO'; 
                        $total_marks = $ese_marks+$stu_mark_id1['CIA'];
                        $grade_cia_check = $stu_mark_id1['CIA'];

                      $arts_college_grade = 'NO';
                      if($org_email=='coe@skasc.ac.in')
                      {
                        $ese_marks =  $pre_ese;
                        $insert_total = $ese_marks+$grade_cia_check;
                        if($final_sub_total<100)
                        {
                          $total_marks = round(round((($insert_total/$final_sub_total)*10),1)*10);
                        }
                        else
                        {
                          $total_marks = $ese_marks+$grade_cia_check;
                        }
                        $arts_college_grade = round(($insert_total/$final_sub_total)*10,1);
                      }
                      else if ($check_attempt > $config_attempt) {
                          $ese_marks =  $pre_ese;
                          $total_marks = $pre_ese;
                      }

                      if ($check_attempt > $config_attempt && $org_email!='coe@skasc.ac.in') {
                          $grade_cia_check =  0;
                      }
                      else
                      {
                        $grade_cia_check = $stu_mark_id1['CIA'];
                      }

                      $pre_total = $grade_cia_check + $ese_marks;
                    
                    if ($ese_marks >= $stu_mark_id1['ESE_min'] && $pre_total >= $stu_mark_id1['total_minimum_pass']) {
                        $pre_result = "Pass";
                    } else {
                        $pre_result = "Fail";
                    }

                    $table .= "<tr>" .
                        "<td><input type='hidden' id=sn_" . $sn . " name='sn' value=" . $sn . ">" . $sn . "</td> " .
                         "<td><input type='hidden' name=reg_nu" . $sn . " value='" . $stu_mark_id1['student_map_id'] . "'  >" . $stu_mark_id1['register_number'] . "</td>" .
                        "<td><input type='hidden' name=sub_code" . $sn . " value='" . $stu_mark_id1['subject_map_id'] . "'>" . strtoupper($stu_mark_id1['name']) . "</td>" .
                        "<td><input type='hidden' id=cia_" . $sn . " name=cia" . $sn . " value='" . $grade_cia_check . "'>" . $grade_cia_check . "</td>" .
                        "<td><input type='hidden' id=ese_con_" . $sn . " name=ese_con_" . $sn . " value='" . $ese_marks . "'>" . $ese_marks . "</td>" .
                        "<td><input type='hidden' id=oldese_" . $sn . " name=oldese" . $sn . " value='" . $pre_ese . "'>" . $pre_ese . "</td>" .
                        "<td><input type='hidden' id=oldtotal_" . $sn . " name=oldtotal" . $sn . " value='" . $total_marks . "' size='2px'>" . $total_marks . "</td>" .
                        "<td><input type='hidden' id=oldresult_" . $sn . " name=oldresult" . $sn . " value='" . $pre_result . "'>" . $pre_result . "</td>";
                    $change_func = $mark_out_of==1?'revaluation_esereg(this.id)':'revaluation_eseregMax(this.id)';
                     $table .= "<input type='hidden' id=esemin_" . $sn . " value='" . $stu_mark_id1['ESE_min'] . "'>";
                        $table .= "<input type='hidden' id=esemax_" . $sn . " value='" . $stu_mark_id1['ESE_max'] . "'>";
                        $table .= "<input type='hidden' id=totalmin_" . $sn . " value='" . $stu_mark_id1['total_minimum_pass'] . "'>";
                        $table .= "<input type='hidden' name=esemin" . $sn . " value='" . $stu_mark_id1['ESE_min'] . "'>";
                        $table .= "<input type='hidden' name=esemax" . $sn . " value='" . $stu_mark_id1['ESE_max'] . "'>";
                        $table .= "<input type='hidden' name=totalmin" . $sn . " value='" . $stu_mark_id1['total_minimum_pass'] . "'>";
                    if (count($check_rev_done) > 0 && !empty($check_rev_done)) 
                    {

                        $check_rev_master_done = Yii::$app->db->createCommand("select * from coe_mark_entry_master where student_map_id='" . $stu_mark_id1['student_map_id'] . "' and subject_map_id='" . $stu_mark_id1['subject_map_id'] . "' and year='" . $year . "' and month='" . $month . "' and mark_type='" . $check_rev_done['mark_type'] . "' and term='".$check_rev_done['term']."' ")->queryOne();
                        $stu_res_dat = ConfigUtilities::StudentResult($stu_mark_id1['student_map_id'],$stu_mark_id1['subject_map_id'],$stu_mark_id1['CIA'] ,$check_rev_done['category_type_id_marks'],$year,$month );
                        if($checkAccess=='Yes')
                        {
                            $table .=
                            "<td><input required type='text' id=newese100_" . $sn . " name=newese100" . $sn . " size='3px'  onkeypress='numbersOnly(event); autocomplete='off' allowEntr(event,this.id);'  onchange='".$change_func."' value='" . $check_rev_done['category_type_id_marks'] . "'></td>";
                        }
                        else
                        {
                            $table .=
                            "<td><input required type='text' id=newese100_" . $sn . " name=newese100" . $sn . " size='3px' readonly value='" . $check_rev_done['category_type_id_marks'] . "'></td>";
                        }
                        $table .="<td><input required type='text' id=newese_" . $sn . " name=newese" . $sn . " readonly size='3px' value='" . $stu_res_dat['ese_marks'] . "'></td>" .
                            "<td><input required type='text' id=newtotal_" . $sn . " name=newtotal" . $sn . " readonly size='3px' value='" . $stu_res_dat['total_marks'] . "'></td>" .
                            "<td><input required type='text' id=newresult_" . $sn . " name=newresult" . $sn . " readonly size='3px' value='" . $stu_res_dat['result'] . "'></td>";
                    } else {
                        
                        $table .=
                            "<td><input type='text' id=newese100_" . $sn . " name=newese100" . $sn . " size='3px' onkeypress='numbersOnly(event); autocomplete='off' allowEntr(event,this.id);'  onchange='".$change_func."' ></td>" .
                            "<td><input type='text' id=newese_" . $sn . " autocomplete='off' name=newese" . $sn . " readonly size='3px' ></td>" .
                            "<td><input type='text' id=newtotal_" . $sn . " autocomplete='off' name=newtotal" . $sn . " readonly size='3px' ></td>" .
                            "<td><input type='text' id=newresult_" . $sn . " autocomplete='off' name=newresult" . $sn . " readonly size='3px' ></td>";
                    }
                    $table .= "</tr>";
                    $sn++;
                } // Foreach Ends Here
                $table .= "</tbody></table>";
                return $table;
            } 
            else 
            {
                return 0;
            }
        //check external mark entered or not
        return  !empty($sub_list)? json_encode($sub_list):json_encode(0);
    }
    public function actionGetsubjectnamewithsubid() 
    {
        $sub_id = Yii::$app->request->post('sub_id');
        $data_receive = is_numeric($sub_id)?$sub_id:'NO';
        if($data_receive=='NO')
        {
            $sub_name = Subjects::find()->where(['subject_code' => $sub_id])->one();
            $sub_name = !empty($sub_name)?$sub_name:'NO';
        }
        else
        {
            $sub_name = Subjects::find()->where(['coe_subjects_id' => $sub_id])->one();
            $sub_name = !empty($sub_name)?$sub_name:'NO';
        }        
        return Json::encode($sub_name);
    }
    public function actionGetpractqpexamdate() {
        $month = Yii::$app->request->post('month');
        $year = Yii::$app->request->post('exam_year');
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Practical%'")->queryAll();
        $pract_opaeprs = '';
        foreach ($det_cat_type as $key => $value) {
           $pract_opaeprs .=$value['coe_category_type_id'].",";
        }
        $pract_opaeprs = trim($pract_opaeprs,',');
        $query = new Query();

        $exam_date = Yii::$app->db->createCommand("SELECT DISTINCT DATE_FORMAT(exam_date, '%d-%m-%Y') as exam_date FROM coe_exam_timetable as A  JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_mapping_id WHERE A.exam_month='" . $month . "' AND A.exam_year='" . $year . "' and paper_type_id IN(".$pract_opaeprs.")  ORDER BY exam_date")->queryAll();

        return Json::encode($exam_date);
    }

    public function actionGetstusubgradeifo() {
        $month = Yii::$app->request->post('month');
        $year = Yii::$app->request->post('exam_year');
        $reg_num = Yii::$app->request->post('reg_num');
        $sub_code = Yii::$app->request->post('sub_code');
        $query = new Query();
        $exam_date = Yii::$app->db->createCommand("SELECT * FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects as E ON E.coe_subjects_id=B.subject_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_student as D ON D.coe_student_id=C.student_rel_id WHERE A.year='" . $year . "' AND A.month='" . $month . "' AND D.register_number='".$reg_num."' and E.subject_code='".$sub_code."'")->queryAll();
        $data_send = !empty($exam_date) && count($exam_date)>0 ? $exam_date:0;
        return Json::encode($data_send);
    }

    public function actionTopperslistrepo()
    {
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $batch_id = Yii::$app->request->post('batch_id');
        $programme = Yii::$app->request->post('programme');
        $stUdetaIL = Yii::$app->db->createCommand("select student_map_id FROM coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id where year_of_passing='' and course_batch_mapping_id='".$programme."' and grade_name not like '%COMPLETED%' ")->queryAll();
        $noIDs = array_filter(['']);
        if(!empty($stUdetaIL))
        {
            foreach ($stUdetaIL as $key => $values) {
               $noIDs[$values['student_map_id']]=$values['student_map_id'];
            }
        }
        $query_map_id = new Query();
        $query_map_id->select(['batch_name','register_number','round (sum(A.grade_point*aC.credit_points)/sum(aC.credit_points),5) as cgpa','sum(aC.credit_points) as creds','programme_code','degree_code','B.course_batch_mapping_id'])
            ->from('coe_mark_entry_master A')
            ->join('JOIN', 'coe_student_mapping B', 'B.coe_student_mapping_id=A.student_map_id')
            ->join('JOIN', 'coe_student H', 'H.coe_student_id=B.student_rel_id')
            ->join('JOIN', 'coe_subjects_mapping C', 'C.coe_subjects_mapping_id=A.subject_map_id and C.batch_mapping_id=B.course_batch_mapping_id')
            ->join('JOIN', 'coe_subjects aC', 'aC.coe_subjects_id=C.subject_id')
            ->join('JOIN', 'coe_bat_deg_reg D', 'D.coe_bat_deg_reg_id=C.batch_mapping_id and D.coe_bat_deg_reg_id=B.course_batch_mapping_id')
            ->join('JOIN', 'coe_programme E', 'E.coe_programme_id=D.coe_programme_id')
            ->join('JOIN', 'coe_batch F', 'F.coe_batch_id=D.coe_batch_id ')
            ->join('JOIN', 'coe_degree G', ' G.coe_degree_id=D.coe_degree_id ')
            ->where(['student_status' => 'Active','B.course_batch_mapping_id'=>$programme,'C.batch_mapping_id'=>$programme])
            ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
            ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
            if(!empty($noIDs))
            {
               $query_map_id->andWhere(['NOT IN', 'student_map_id', $noIDs]);
            }                
            $query_map_id->groupBy('register_number')->orderby('cgpa desc')->limit(25);
        $students_map_id = $query_map_id->createCommand()->queryAll();


        $fail_array = array();
            if (count($students_map_id) > 0) 
            {
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                $data = '<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';
                $data .= '<tr>
                            <td> 
                                <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                            </td>
                            <td colspan=4 align="center"> 
                                <center><b><font size="4px">' . $org_name . '</font></b></center>
                                <center>' . $org_address . '</center>
                                
                                <center>' . $org_tagline . '</center> 
                            </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                            
                        </tr>';
               $SN_1 = 1;
                $data .= '<tr>
                            <th  align="center"><b>SNO</b></th>
                            <th  align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)) . ' 
                            </b></th>
                            <th  align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)) . ' 
                            </b></th>
                            <th align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)) . ' 
                            </b></th>
                            <th align="center"><b>REGISTER NUMBER</b></th>
                            <th align="center"><b>CGPA</b></th>
                        </tr>';
                $count_of_total = $count_of_TOT_total=0;
                foreach ($students_map_id as $key => $value) 
                {

                     $cgpa_final = round($value['cgpa'],2);
                     $data .= '<tr>                     
                                <td align="left">'.$SN_1.'</td>                                 
                                <td align="left">'.$value['batch_name'].'</td>
                                <td align="left">'.$value['degree_code'].'</td>
                                <td align="left">'.$value['programme_code'].'</td>
                                <td align="left">'.$value['register_number'].'</td>
                                <td align="left">'.$cgpa_final.'</td>
                            </tr>';
                    $SN_1++;
                }
                $data .= '</table>';
                if (isset($_SESSION['singlAttemprPass'])) {
                    unset($_SESSION['singlAttemprPass']);
                }
                $_SESSION['singlAttemprPass'] = $data;
                return $data;
            } else {
                return 0;
            }
        
    }

    public function actionGetstudentwisearreacount()
    {
       
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $batch_id = Yii::$app->request->post('batch_id');
        $omit_batch = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MAX_EXAM_CONDUTION);

        $students_map_id = Yii::$app->db->createCommand('select register_number,name,count(distinct subject_map_id) as total_arr,programme_code,student_map_id,degree_code,batch_name from coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN 
            coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id JOIN coe_subjects as D ON D.coe_subjects_id=C.subject_id JOIN coe_student as E ON E.coe_student_id=B.student_rel_id JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id JOIN coe_programme as H ON H.coe_programme_id=F.coe_programme_id JOIN coe_batch as I ON I.coe_batch_id=F.coe_batch_id
    where year_of_passing="" and subject_map_id NOT IN(select subject_map_id from coe_mark_entry_master
    where student_map_id=A.student_map_id and result like "%Pass%") and 
    F.coe_batch_id="'.$batch_id.'" and I.coe_batch_id="'.$batch_id.'" 
    and batch_name >(YEAR(CURDATE())-'.$omit_batch.' ) and status_category_type_id NOT IN('.$det_disc_type.') group by student_map_id')->queryAll();

        $fail_array = array();
            if (count($students_map_id) > 0) 
            {
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                $data = '<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';
                $data .= '<tr>
                            <td> 
                                <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                            </td>
                            <td colspan=6 align="center"> 
                                <center><b><font size="4px">' . $org_name . '</font></b></center>
                                <center>' . $org_address . '</center>
                                
                                <center>' . $org_tagline . '</center> 
                            </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                            
                        </tr>';
               $SN_1 = 1;
                $data .= '<tr>
                            <th  align="center"><b>SNO</b></th>
                            <th  align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)) . ' 
                            </b></th>
                            <th  align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)) . ' 
                            </b></th>
                            <th align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)) . ' 
                            </b></th>
                            <th align="center"><b>REGISTER NUMBER</b></th>
                            <th align="center"><b>NAME</b></th>
                            <th align="center"><b>NO OF ARREARS</b></th>
                            <th align="center"><b>AMOUNT</b></th>
                        </tr>';
                $count_of_total = $count_of_TOT_total=0;
                foreach ($students_map_id as $key => $value) 
                {
                    $stu_details = Yii::$app->db->createCommand('select sum(DISTINCT D.subject_fee)  as sum_of from coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN 
coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id JOIN coe_subjects as D ON D.coe_subjects_id=C.subject_id
where year_of_passing="" and subject_map_id NOT IN(select subject_map_id from coe_mark_entry_master
where student_map_id=A.student_map_id and result like "%Pass%") and student_map_id="'.$value['student_map_id'].'" group by subject_code')->queryAll();
                    $stu_fees = 0;

                    for ($i=0; $i <count($stu_details) ; $i++) 
                     { 
                         $stu_fees +=$stu_details[$i]['sum_of'];
                     } 
                  
                     $data .= '<tr>                     
                                <td align="left">'.$SN_1.'</td>                                 
                                <td align="left">'.$value['batch_name'].'</td>
                                <td align="left">'.$value['degree_code'].'</td>
                                <td align="left">'.$value['programme_code'].'</td>
                                <td align="left">'.$value['register_number'].'</td>
                                <td align="left">'.$value['name'].'</td>
                                <td align="left">'.$value['total_arr'].'</td>
                                <td align="left">'.$stu_fees.'</td>
                            </tr>';
                    $SN_1++;
                }
                $data .= '</table>';
                if (isset($_SESSION['singlAttemprPass'])) {
                    unset($_SESSION['singlAttemprPass']);
                }
                $_SESSION['singlAttemprPass'] = $data;
                return $data;
            } else {
                return 0;
            }
        
    }

    public function actionGetpractsubscodes()
    {
        $batch_map_id = Yii::$app->request->post('batch_map_id');
        $semester = Yii::$app->request->post('semester')+1;
        $getSubs = Yii::$app->db->createCommand('SELECT coe_subjects_mapping_id,subject_code FROM coe_subjects_mapping as A JOIN coe_subjects as B ON B.coe_subjects_id=A.subject_id WHERE batch_mapping_id="'.$batch_map_id.'" and semester="'.$semester.'"')->queryAll();

        if(!empty($getSubs))
        {
            $subjects_dropdown = '<option value="" >----- SELECT -----</option> ';
            foreach ($getSubs as $key => $value) 
            {
                $subjects_dropdown .= "<option value='" . $value['coe_subjects_mapping_id'] . "' > " . $value['subject_code'] . "</option>";
            }
            return json_encode($subjects_dropdown);
        }
        else
        {
            return json_encode(0);
        }
    }
    public function actionGetmandatorypapers()
    {
        $batch_mapping_id = Yii::$app->request->post('batch_mapping_id');
        $batch_id = Yii::$app->request->post('batch_id');
        $man_sub_id = Yii::$app->request->post('man_sub_id');
        
        $getSubs = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_subcat_subjects as A JOIN coe_mandatory_subjects as B ON B.coe_mandatory_subjects_id=A.man_subject_id WHERE batch_map_id="'.$batch_mapping_id.'" and batch_mapping_id="'.$batch_mapping_id.'" and man_subject_id="'.$man_sub_id.'" and coe_mandatory_subjects_id="'.$man_sub_id.'" and coe_batch_id="'.$batch_id.'" and man_batch_id="'.$batch_id.'" group by sub_cat_code')->queryAll();
        $data = '';
        $SN_1 = '1';
        if(!empty($getSubs))
        {
            $data = '<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';
            $data .= '<tr>
                            <th  align="center"><b>SNO</b></th>
                            <th  align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)) . ' CODE 
                            </b></th>
                            <th  align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)) . ' CAT CODE
                            </b></th>
                            <th  align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)) . ' CAT NAME
                            </b></th>
                            <th align="center"><b>PAPER NO</b></th>
                        </tr>';
            foreach ($getSubs as $key => $value) 
            {
                $data .= '<tr>                     
                            <td align="left">'.$SN_1.'</td>                                 
                            <td align="left">'.$value['subject_code'].'</td>
                            <td align="left"><input type="hidden" name="man_update[]" value='.$value['coe_mandatory_subcat_subjects_id'].' />'.$value['sub_cat_code'].'</td>
                            <td align="left">'.$value['sub_cat_name'].'</td>
                            <td align="left"> <input type="text" name="update_paper_num[]" value='.$value['paper_no'].' /> </td>
                        </tr>';
                $SN_1++;
            }
            $data .='<tr>   
                        <td align="left" colspan="4" > &nbsp; </td>                  
                        <td align="left" > <input type="submit" name="update_paper" value="Update" class="btn btn-block btn-warning" /> </td>
                        </tr></table>';
            return json_encode($data);
        }
        else
        {
            return json_encode(0);
        }
    }

    public function actionGetmansubnamedetails() 
    {
        $batch_id = Yii::$app->request->post('batch_id');
        $batch_mapping_id = Yii::$app->request->post('batch_mapping_id');
        $sub_name = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_subcat_subjects as A JOIN coe_mandatory_subjects as B ON B.coe_mandatory_subjects_id=A.man_subject_id WHERE coe_batch_id="'.$batch_id.'" and batch_mapping_id="'.$batch_mapping_id.'" group by coe_mandatory_subjects_id')->queryAll();
        
        if(!empty($sub_name))
        {
            $subjects_dropdown = '<option value="" >----- SELECT -----</option> ';
            foreach ($sub_name as $key => $value) 
            {
                $subjects_dropdown .= "<option value='" . $value['coe_mandatory_subjects_id'] . "' > " . $value['subject_code'] . "</option>";
            }
            return json_encode($subjects_dropdown);
        }
        else
        {
            return json_encode(0);
        }
    }

    public function actionAdditionalcreditartsstulist() 
    {
       $batch_map_id = Yii::$app->request->post('batch_map_id');
       $exam_year = Yii::$app->request->post('exam_year');
       $exam_month = Yii::$app->request->post('exam_month');
       $sub_code = Yii::$app->request->post('sub_code');
       $cia_min = Yii::$app->request->post('cia_min');
       $ese_min = Yii::$app->request->post('ese_min');
       $cia_max = Yii::$app->request->post('cia_max');
       $ese_max = Yii::$app->request->post('ese_max');
       $total_min = Yii::$app->request->post('total_min');
       $semester=Yii::$app->request->post('semester');

       $total_max = $cia_max+$ese_max;
       $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

            $query = new Query();
            $query->select('b.register_number,b.name,a.coe_student_mapping_id')
                    ->from('coe_student_mapping a')
                    ->join('JOIN', 'coe_student b', 'a.student_rel_id=b.coe_student_id')
                    ->where(['a.course_batch_mapping_id' => $batch_map_id, 'b.student_status' => 'Active'])->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->orderby('b.register_number');
            $student_list = $query->createCommand()->queryAll();
            $sno = 1;
            $add_table = '';
            $add_table .= '<table id="checkAllFeattttt" class="table table-striped" align="right" border=1>     
                      <tbody>                       
                      <td align="center"><b> S.NO </b></td> 
                      <td align="center"><b> Register Number </b></td>
                      <td align="center"><b> Student Name </b></td>
                      <td align="center"><b> Action </b></td>
                      <td align="center" width="1px"><b>Out of <br /> Maximum</b></td>
                      <td align="center" width="1px"><b>Out of <br /> 100</b></td>
                      <td align="center"><b>Result</b></td>';
            foreach ($student_list as $stu_list) 
            {

                $query_exist = new Query();
                $query_exist->select('*')
                        ->from('coe_additional_credits a')
                        ->where(['a.student_map_id' => $stu_list['coe_student_mapping_id'], 'a.subject_code' => $sub_code,'a.semester'=>$semester]);
                $exist_student = $query_exist->createCommand()->queryOne();

                $add_table .= "<tr><td align='center'><input type='hidden' name='sn' value=" . $sno . ">" . $sno . "</td>";
                $add_table .= "<td align='center'><input type='hidden' name=reg_num" . $sno . " value=" . $stu_list['register_number'] . ">" . $stu_list['register_number'] . "</td>";
                $add_table .= "<td>" . $stu_list['name'] . "</td>";

                if ($exist_student != "") 
                {
                    $add_table .= "<td align='center'><input type='checkbox' checked disabled></td>";
                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' onchange='getArtsAddResult(this.id,this.value,".$cia_min.",".$ese_min.",".$total_min.",".$ese_max.",".$cia_max.");' onkeypress='numbersOnly(event); allowEntr(event,this.id);' name=actxt_" . $sno . " value=" . $exist_student['out_of_maximum'] . " id=actxt_" . $sno . " disabled></td>";
                   $add_table .= "<td><input autocomplete='off' readonly='readonly' type='textbox' size='3px' name=actxttotal_" . $sno . " value=" . $exist_student['total'] . " id=actxttotal_" . $sno . " ></td>";               
                    $add_table .= "<td align='center'><input autocomplete='off' type='textbox' size='5px' name=acresult_" . $sno . " id=acresult_" . $sno . " value=" . $exist_student['result'] . " ></td></tr>";

                } else {

                    $add_table .= "<td align='center'>
                            <input type='checkbox' onclick='additional_arts_check(this.id)' name=add" . $sno . " id=add_" . $sno . "></td>";
                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' onchange='getArtsAddResult(this.id,this.value,".$cia_min.",".$ese_min.",".$total_min.",".$ese_max.",".$cia_max.");' disabled onkeypress='numbersOnly(event); allowEntr(event,this.id);' name=actxt_" . $sno . " id=actxt_" . $sno . " ></td>";
                    $add_table .= "<td><input autocomplete='off' type='textbox' size='3px' name=actxttotal_" . $sno . " id=actxttotal_" . $sno . " readonly='readonly' ></td>";                    
                    $add_table .= "<td align='center'><input autocomplete='off' type='textbox' size='5px' name=acresult_" . $sno . " id=acresult_" . $sno . " readonly='readonly' /></td></tr>";
                }

                $sno++;
            }
            $add_table .= '</tbody></table>';
            return json_encode($add_table);
        
    }
   public function actionAdditionalcreditsubinfo() 
    {
        $sub_code = Yii::$app->request->post('sub_code');
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
        $data_pass = array_filter(['']);

        if ($sub_code != "" && !empty($year) && !empty($month) ) 
        {
            $query_exist = new Query();
            $query_exist->select('*')
                    ->from('coe_additional_credits a')
                    ->where(['a.subject_code' => $sub_code,'exam_month'=>$month,'exam_year'=>$year]);
            $send = $query_exist->createCommand()->queryOne();
            if(!empty($send))
            {
                $data_pass = ['cia_min'=>$send['cia_minimum'],'ese_min'=> $send['ese_minimum'],'cia_max'=>$send['cia_maximum'],'ese_max'=>$send['ese_maximum'],'min_pass'=>$send['total_minimum_pass'],'sub_name'=>$send['subject_name'],'credits'=>$send['credits']];
                return json_encode($data_pass);
            }
            else if ($sub_code != "") 
            {
                $query_exist = new Query();
                $query_exist->select('*')
                        ->from('coe_additional_credits a')
                        ->where(['a.subject_code' => $sub_code]);
                $send = $query_exist->createCommand()->queryOne();
              if(empty($send))
                {
                    $query_exist = new Query();
                    $query_exist->select('*')
                            ->from('coe_subjects a')
                            ->where(['a.subject_code' => $sub_code]);
                    $sub_send = $query_exist->createCommand()->queryOne();
                    if (empty($sub_send)) {
                       return json_encode(1);
                    }
                    else{

                     $data_pass = ['cia_min'=>'','ese_min'=> '','cia_max'=>'','ese_max'=>'','min_pass'=>'','sub_name'=>$sub_send['subject_name'],'credits'=>$sub_send['credit_points']];
                        return json_encode($data_pass);

                    }               
                }
                else
                {
                    $data_pass = ['cia_min'=>'','ese_min'=> '','cia_max'=>'','ese_max'=>'','min_pass'=>'','sub_name'=>$send['subject_name'],'credits'=>$send['credits']];
                    return json_encode($data_pass);
                }
            
            }
            
        }
        else 
        {
            return json_encode(0);
        }
    }
    public function actionAdditionalcreditsubinfoupdate() 
    {
        $sub_code = Yii::$app->request->post('sub_code');
        $sub_name = Yii::$app->request->post('sub_name');
        $data_pass = array_filter(['']);
        if ($sub_code != "" && $sub_name!='') 
        {
            $update_name = Yii::$app->db->createCommand('UPDATE coe_additional_credits SET subject_name="'.$sub_name.'" WHERE subject_code="'.$sub_code.'"')->execute();
            if(!empty($update_name))
            {
                return json_encode(1);       
            }            
            else
            {
                return json_encode(2);
            }
        }
        else 
        {
            return json_encode(0);
        }
    }


     public function actionGetfeessubjects() 
    {
        $batch = Yii::$app->request->post('batch');
        $getSubsInfo = new Query();
        $getSubsInfo->select(['F.subject_code'])
        ->from('coe_subjects_mapping as E')
        ->join ('JOIN','coe_mark_entry_master as d','d.subject_map_id=E.coe_subjects_mapping_id') 
        ->join ('JOIN','coe_bat_deg_reg as x','x.coe_bat_deg_reg_id=E.batch_mapping_id') 
        ->join('JOIN','coe_batch as y','y.coe_batch_id=x.coe_batch_id') 
        ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
        ->Where(['year_of_passing'=>'','y.coe_batch_id'=>$batch])
        ->groupBy('subject_code')
        ->orderBy('subject_code');
        $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        return Json::encode($getSubsInfoDet);
    }

    public function actionGetfeesarrearstu() 
    {
        $batch = Yii::$app->request->post('batch');
        $sub_code = Yii::$app->request->post('sub_id');
        $year=Yii::$app->request->post('year');
        $month= Yii::$app->request->post('month');
        
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $reguLar = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Arrear%'")->queryScalar();  
        $getSubsInfo = new Query();
        $getSubsInfo->select('sub_map_id')
        ->from('sub_info as E')
        ->Where(['sub_code'=>$sub_code]);
        $getSubsInfoDet = $getSubsInfo->createCommand()->queryAll();
        $getSubMaps = array_unique(array_column($getSubsInfoDet, 'sub_map_id','sub_map_id'));

        $checkInserted = FeesPaid::find()->where(['year'=>$year,'month'=>$month])->andWhere(['IN','subject_map_id',$getSubMaps])->all();
        if(!empty($checkInserted))
        {
            return json_encode(1);
        }

        $getPass = new Query();
        $getPass->select('student_map_id')
        ->from('coe_subjects_mapping as E')
        ->join ('JOIN','coe_mark_entry_master as d','d.subject_map_id=E.coe_subjects_mapping_id') 
        ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
        ->Where(['subject_code'=>$sub_code,'result'=>'Pass'])
        ->groupBy('student_map_id')
        ->orderBy('subject_code');
        $getPassedStu = $getPass->createCommand()->queryAll();
        $data = array_unique(array_column($getPassedStu, 'student_map_id','student_map_id'));
        
        $getSubsInfo = new Query();
        $getSubsInfo->select(['A.name','A.register_number', 'B.coe_student_mapping_id','F.subject_code','E.coe_subjects_mapping_id as sub_map_id'])
        ->from('coe_student as A')
        ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')
        ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
        ->join('JOIN', 'coe_mark_entry_master as mast', 'mast.student_map_id=B.coe_student_mapping_id and mast.subject_map_id=E.coe_subjects_mapping_id')
        ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
        ->join ('JOIN','coe_bat_deg_reg as x','x.coe_bat_deg_reg_id=E.batch_mapping_id')
        ->join('JOIN','coe_batch as y','y.coe_batch_id=x.coe_batch_id')
        ->Where(['F.subject_code'=>$sub_code,'year_of_passing'=>"",'x.coe_batch_id'=>$batch])
        ->andWhere(['<>','B.status_category_type_id',$det_disc_type])
        ->andWhere(['NOT IN','student_map_id',$data]);
        $getSubsInfo->groupBy('register_number,subject_code')->orderBy('register_number');
        $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();

        $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
            
        return Json::encode($getSubsInfoDetailsse);
    }
    public function actionGetfeespaidstudents() 
    {
        $batch = Yii::$app->request->post('batch');
        $sub_code = Yii::$app->request->post('sub_id');
        $year=Yii::$app->request->post('year');
        $month= Yii::$app->request->post('month');
        
        $getSubsInfoDet = SubInfo::find()->where(['sub_code'=>$sub_code])->all();
       
        $getSubMaps = array_unique(array_column($getSubsInfoDet, 'sub_map_id','sub_map_id'));
        $checkInserted = FeesPaid::find()->where(['year'=>$year,'month'=>$month])->andWhere(['IN','subject_map_id',$getSubMaps])->all();
        if(!empty($checkInserted))
        {
            return json_encode(0);
        }
        
        $getSubsInfo = new Query();
        $getSubsInfo->select(['A.name','A.register_number', 'F.subject_code','mast.status','username'])
        ->from('coe_student as A')
        ->join('JOIN', 'coe_student_mapping as B', 'B.student_rel_id=A.coe_student_id')
        ->join('JOIN', 'coe_subjects_mapping as E', 'E.batch_mapping_id=B.course_batch_mapping_id')
        ->join('JOIN', 'coe_fees_paid as mast', 'mast.student_map_id=B.coe_student_mapping_id and mast.subject_map_id=E.coe_subjects_mapping_id')
        ->join('JOIN', 'coe_subjects as F', 'F.coe_subjects_id=E.subject_id')
        ->join ('JOIN','coe_bat_deg_reg as x','x.coe_bat_deg_reg_id=E.batch_mapping_id')
        ->join('JOIN','coe_batch as y','y.coe_batch_id=x.coe_batch_id')
        ->join('JOIN','user as us','us.id=mast.created_by')
        ->Where(['F.subject_code'=>$sub_code,'x.coe_batch_id'=>$batch,'year'=>$year,'month'=>$month]);
        $getSubsInfo->orderBy('register_number');
        $getSubsInfoDetails = $getSubsInfo->createCommand()->queryAll();

        $getSubsInfoDetailsse = !empty($getSubsInfoDetails) && count($getSubsInfoDetails) >0 ? $getSubsInfoDetails : 0;
            
        return Json::encode($getSubsInfoDetailsse);
    }
    public function actionParttopperslistrepo()
    {
        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

        $batch_id = Yii::$app->request->post('batch_id');
        $programme = Yii::$app->request->post('programme');
        $part_no = Yii::$app->request->post('part_no');
        $limit = !empty(Yii::$app->request->post('limit')) ? Yii::$app->request->post('limit') : 25;

        $stUdetaIL = Yii::$app->db->createCommand("select student_map_id FROM coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id where year_of_passing='' and course_batch_mapping_id='".$programme."' and grade_name not like '%COMPLETED%' ")->queryAll();
        $noIDs = array_filter(['']);
        if(!empty($stUdetaIL))
        {
            foreach ($stUdetaIL as $key => $values) {
               $noIDs[$values['student_map_id']]=$values['student_map_id'];
            }
        }
        $query_map_id = new Query();
        $query_map_id->select(['batch_name','register_number','name','round (sum(A.grade_point*aC.credit_points)/sum(aC.credit_points),5) as cgpa','sum(aC.credit_points) as creds','programme_code','degree_code','B.course_batch_mapping_id'])
            ->from('coe_mark_entry_master A')
            ->join('JOIN', 'coe_student_mapping B', 'B.coe_student_mapping_id=A.student_map_id')
            ->join('JOIN', 'coe_student H', 'H.coe_student_id=B.student_rel_id')
            ->join('JOIN', 'coe_subjects_mapping C', 'C.coe_subjects_mapping_id=A.subject_map_id and C.batch_mapping_id=B.course_batch_mapping_id')
            ->join('JOIN', 'coe_subjects aC', 'aC.coe_subjects_id=C.subject_id')
            ->join('JOIN', 'coe_bat_deg_reg D', 'D.coe_bat_deg_reg_id=C.batch_mapping_id and D.coe_bat_deg_reg_id=B.course_batch_mapping_id')
            ->join('JOIN', 'coe_programme E', 'E.coe_programme_id=D.coe_programme_id')
            ->join('JOIN', 'coe_batch F', 'F.coe_batch_id=D.coe_batch_id ')
            ->join('JOIN', 'coe_degree G', ' G.coe_degree_id=D.coe_degree_id ')
            ->where(['student_status' => 'Active','B.course_batch_mapping_id'=>$programme,'C.batch_mapping_id'=>$programme,'part_no'=>$part_no])
            ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
            ->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
            if(!empty($noIDs))
            {
               $query_map_id->andWhere(['NOT IN', 'student_map_id', $noIDs]);
            }                
            $query_map_id->groupBy('register_number')->orderby('cgpa desc')->limit($limit);
            $students_map_id = $query_map_id->createCommand()->queryAll();


        $fail_array = array();
            if (count($students_map_id) > 0) 
            {
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                $data = '<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';
                $data .= '<tr>
                            <td> 
                                <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                            </td>
                            <td colspan=5 align="center"> 
                                <center><b><font size="4px">' . $org_name . '</font></b></center>
                                <center>' . $org_address . '</center>
                                
                                <center>' . $org_tagline . '</center> 
                            </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                            
                        </tr>';
               $SN_1 = 1;
                $data .= '<tr>
                            <th  align="center"><b>SNO</b></th>
                            <th  align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)) . ' 
                            </b></th>
                            <th  align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)) . ' 
                            </b></th>
                            <th align="center"><b>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)) . ' 
                            </b></th>
                            <th align="center"><b>REGISTER NUMBER</b></th>
                            <th align="center"><b>NAME</b></th>
                            <th align="center"><b>CGPA</b></th>
                        </tr>';
                $count_of_total = $count_of_TOT_total=0;
                foreach ($students_map_id as $key => $value) 
                {

                     $cgpa_final = round($value['cgpa'],2);
                     $data .= '<tr>                     
                                <td align="left">'.$SN_1.'</td>                                 
                                <td align="left">'.$value['batch_name'].'</td>
                                <td align="left">'.$value['degree_code'].'</td>
                                <td align="left">'.$value['programme_code'].'</td>
                                <td align="left">'.$value['register_number'].'</td>
                                <td align="left">'.$value['name'].'</td>
                                <td align="left">'.$cgpa_final.'</td>
                            </tr>';
                    $SN_1++;
                }
                $data .= '</table>';
                if (isset($_SESSION['singlAttemprPass'])) {
                    unset($_SESSION['singlAttemprPass']);
                }
                $_SESSION['singlAttemprPass'] = $data;
                return $data;
            } else {
                return 0;
            }
        
    }



}


