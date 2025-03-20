<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

use yii\helpers\ArrayHelper;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\CurriculumSubject;
use app\models\Batch;
use app\models\CoeBatDegReg;
use app\models\Department;
use app\models\Categories;
use app\models\Categorytype;
use app\models\Regulation;
use app\models\Degree;
use app\models\LTP;
use app\models\SubjectPrefix;

class CurSyllabus extends \yii\db\ActiveRecord
{
    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
     const TYPE_MBA= 'MBA';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_syllabus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'coe_batch_id','coe_regulation_id', 'coe_dept_id', 'semester', 'subject_code', 'subject_type', 'course_objectives1', 'rpt1','course_outcomes1'],'required'],
            //'cource_content_mod1', 'cource_content_mod2', 'cource_content_mod3', 'module_title1', 'module_title2', 'module_title3', 'module_hr1', 'module_hr2', 'module_hr3',
            [[ 'approve_status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['course_objectives1', 'course_objectives2', 'course_objectives3', 'course_objectives4', 'course_objectives5', 'course_objectives6', 'course_outcomes1', 'course_outcomes2', 'course_outcomes3', 'course_outcomes4', 'course_outcomes5', 'course_outcomes6', 'cource_content_mod1', 'cource_content_mod2', 'cource_content_mod3'], 'string'],
            [['module_hr1', 'module_hr2', 'module_hr3'], 'number','max' => 90],
            [['rpt1', 'rpt2', 'rpt3', 'rpt4', 'rpt5', 'rpt6'], 'string', 'max' => 5],
            [['module_title1', 'module_title2', 'module_title3'], 'string', 'max' => 500],
            [['subject_type', 'web_reference1', 'web_reference2', 'web_reference3', 'online_reference1', 'online_reference2', 'text_book1', 'text_book2', 'text_book3', 'reference_book1', 'reference_book2', 'reference_book3'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_syllabus_id' => 'Cur Syllabus ID',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'prerequisties' => 'Prerequisites',
            'coe_dept_id' => 'Department',
            'semester' => 'Semester',
            'subject_code' => 'Course',
            'subject_type' => 'Course Type',
            'course_objectives1' => 'Course Objectives 1',
            'course_objectives2' => 'Course Objectives 2',
            'course_objectives3' => 'Course Objectives 3',
            'course_objectives4' => 'Course Objectives 4',
            'course_objectives5' => 'Course Objectives 5',
            'course_objectives6' => 'Course Objectives 6',
            'course_outcomes1' => 'Course Outcomes 1',
            'course_outcomes2' => 'Course Outcomes 2',
            'course_outcomes3' => 'Course Outcomes 3',
            'course_outcomes4' => 'Course Outcomes 4',
            'course_outcomes5' => 'Course Outcomes 5',
            'course_outcomes6' => 'Course Outcomes 6',
            'rpt1' => 'RBT 1',
            'rpt2' => 'RBT 2',
            'rpt3' => 'RBT 3',
            'rpt4' => 'RBT 4',
            'rpt5' => 'RBT 5',
            'rpt6' => 'RBT 6',
            'cource_content_mod1' => 'Module 1',
            'cource_content_mod2' => 'Module 2',
            'cource_content_mod3' => 'Module 3',
            'module_title1' => 'Module Title 1',
            'module_title2' => 'Module Title 2',
            'module_title3' => 'Module Title 3',
            'module_hr1' => 'Module Hr 1',
            'module_hr2' => 'Module Hr 2',
            'module_hr3' => 'Module Hr 3',
            'text_book1' => 'Text Book 1',
            'text_book2' => 'Text Book 2',
            'text_book3' => 'Text Book 3',
            'reference_book1' => 'Reference Book 1',
            'reference_book2' => 'Reference Book 2',
            'reference_book3' => 'Reference Book 3',
            'web_reference1' => 'Web Reference 1',
            'web_reference2' => 'Web Reference 2',
            'web_reference3' => 'Web Reference 3',
            'online_reference1' => 'Online Reference 1',
            'online_reference2' => 'Online Reference 2',
            'approve_status' => 'Approve Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

     public function getBatch()
    { 
         return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id']);
    }

    public function getSubject()
    { 
        $subject_code =''; $subject_name =''; 
        if(!empty($this->subject_code))
        {
            
            $subject_code=' WHERE subject_code IN ("'.$this->subject_code.'")';
            $qdata = Yii::$app->db->createCommand("SELECT subject_code,subject_name FROM cur_curriculum_subject ". $subject_code)->queryOne();
                   
            if(empty($qdata))
            {
                //$subject_code=' WHERE subject_code IN ("'.$this->subject_code.'")';
                $qdata = Yii::$app->db->createCommand("SELECT subject_code,subject_name FROM cur_elective_subject ". $subject_code)->queryOne();

               
            }           

            if(!empty($qdata))
            {

                
                $subject_code =$qdata['subject_code']; 
                $subject_name =$qdata['subject_name']; 
            }

             
        }
       
        $subjectdata = array();
        $subjectdata['subject_code']=$subject_code;
        $subjectdata['subject_name']=$subject_name;
         return $subjectdata;
       
    }

    public function getSubjectDetails()
    { 
        $where =''; 
        $qdata1=array();$qdata2=array();
        
        $where=" WHERE coe_dept_id =".$this->coe_dept_id." AND coe_regulation_id=".$this->coe_regulation_id;

        $qdata1 = Yii::$app->db->createCommand("SELECT subject_code FROM cur_curriculum_subject ". $where)->queryAll();
   
        $qdata2 = Yii::$app->db->createCommand("SELECT subject_code FROM cur_elective_subject ". $where)->queryAll();
            
        $subjectdata=array_merge($qdata1,$qdata2);
        //print_r($subjectdata); exit();
        return  ArrayHelper::map($subjectdata,'subject_code','subject_code');
       
    }

    public function getDepartmentdetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8 ORDER BY coe_dept_id ASC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }

    public function getBatchdetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT DISTINCT A.coe_batch_id,B.batch_name FROM cur_curriculum_subject A JOIN coe_batch B ON A.coe_batch_id=B.coe_batch_id")->queryAll();
        return  ArrayHelper::map($deptall,'coe_batch_id','batch_name');
        
    }

    public function getRptDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,category_type FROM coe_category_type WHERE category_id=28")->queryAll();
        return  ArrayHelper::map($deptall,'coe_category_type_id','category_type');
    }

    public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

    public function getRegulationDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE regulation_year >=2021 GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }

    public static function getDegreeType()
    {
        return [
        Yii::t('app', self::TYPE_UG) => Yii::t('app', 'UG'),
        Yii::t('app', self::TYPE_PG) => Yii::t('app', 'PG'), 
        Yii::t('app', self::TYPE_MBA) => Yii::t('app', 'MBA'), 
        ];  
    }

    public function getPresubjectlist()
    { 
        $dept=Yii::$app->user->getDeptId();
        $userid = Yii::$app->user->getId(); 
        $qdata = Yii::$app->db->createCommand("SELECT subject_code FROM cur_curriculum_subject WHERE approve_status=1")->queryAll();
        $qdata1 = Yii::$app->db->createCommand("SELECT subject_code FROM cur_elective_subject WHERE approve_status=1")->queryAll();
        $subjectlist=array_merge($qdata,$qdata1);
       
        return  ArrayHelper::map($subjectlist,'subject_code','subject_code');
       
    }

}
