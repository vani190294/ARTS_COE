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
use app\models\ElectiveSubject;
/**
 * This is the model class for table "cur_electivetodept".
 *
 * @property integer $coe_electivetodept_id
 * @property string $subject_code
 * @property integer $semester
 * @property string $coe_dept_ids
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class Electivetodept extends \yii\db\ActiveRecord
{
    const TYPE_E= 'EXIST';
    const TYPE_N= 'NEW';
    const TYPE_NS= 'NEWSYLLABUS';

    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
     const TYPE_MBA= 'MBA';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_electivetodept';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_dept_id','degree_type','coe_regulation_id','coe_elective_option','subject_code', 'coe_dept_ids'], 'required'],
            [['semester', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at','subject_code'], 'safe'],
            //[['coe_dept_ids'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_electivetodept_id' => 'Coe Electivetodept ID',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_subject_id' => 'Course id',
            'coe_elective_option' => 'Elective Option',
            'semester' => 'Semester',
            'coe_dept_ids' => 'Dept.',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'subject_type_new'=>'Type',
            'subject_code_new' => 'New Course Code',
            'subject_code'=>'Course Code'
        ];
    }

     public function getDepartmentdetails1($coe_dept_id)
    {
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8 AND  coe_dept_id!=".$coe_dept_id)->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }

     public function getDepartmentdetails()
    {
        $subjectids = Yii::$app->db->createCommand("SELECT distinct coe_elective_id,subject_code,coe_dept_id,coe_elective_option FROM cur_elective_subject WHERE coe_elective_id='".$this->coe_elective_id."'")->queryOne();

        if($this->coe_elective_option==191)
        {
           
            $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8  AND coe_dept_id!=".$subjectids['coe_dept_id'])->queryAll();
            //print_r($deptall); exit;
            return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        }
        else
        {
            $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8 ")->queryAll();
            return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        }
        
        
    }

    public function getElectivesubjectDetails()
    {
        $subjectids = Yii::$app->db->createCommand("SELECT distinct coe_elective_id,subject_code,coe_dept_id,coe_elective_option FROM cur_elective_subject WHERE coe_elective_option='".$this->coe_elective_option."' AND coe_regulation_id='".$this->coe_regulation_id."'")->queryAll();

        return  ArrayHelper::map($subjectids,'coe_elective_id','subject_code');
    }

    public function getElectivetypeDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,category_type FROM coe_category_type WHERE category_id=27")->queryAll();
        return  ArrayHelper::map($deptall,'coe_category_type_id','category_type');
    }

    public function getBatch()
    { 
         return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id']);
    }

    public function getRegulationDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE regulation_year >=2021 GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }
    

     public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

    public function getElectivesubject()
    { 
         return $this->hasOne(ElectiveSubject::className(), ['coe_elective_id' => 'coe_subject_id']);
       
    }

    public function getCurriculumSubject()
    { 
         return $this->hasOne(CurriculumSubject::className(), ['coe_cur_id' => 'coe_subject_id']);
       
    }

    public function getElectivetype()
    { 
          return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'coe_elective_option']);
    }

    public function getElectivetypeold()
    {
        $stream_name='';
        $getstreamname =Yii::$app->db->createCommand("SELECT B.stream_name FROM cur_curriculum_subject A JOIN cur_aicte_norms B ON B.cur_an_id=A.stream_id WHERE A.subject_code='".$this->subject_code."'")->queryOne();
        
        if(empty($getstreamname))
        {
             $getstreamname = Yii::$app->db->createCommand("SELECT B.description as stream_name FROM cur_elective_subject A JOIN coe_category_type B ON B.coe_category_type_id=A.coe_elective_option WHERE A.subject_code='".$this->subject_code."' ")->queryOne();

             $stream_name=$getstreamname['stream_name'];
        }
        else if(!empty($getstreamname))
        {
             $stream_name=$getstreamname['stream_name'];
        }

        $deptdata = array();
        $deptdata['stream_name']=$stream_name;
       
         return $deptdata;
    }

    public function getDeptassignlist()
    { 
        $dept_codes =''; 
        if(!empty($this->coe_dept_ids))
        {
            $depts_id=' WHERE coe_dept_id IN ('.$this->coe_dept_ids.')';
             $qdata = Yii::$app->db->createCommand("SELECT dept_code FROM cur_department ". $depts_id)->queryAll();

             $deptcodes =array();
             foreach ($qdata as $value) 
             {
                $deptcodes[]=$value['dept_code'];
             }

            $dept_codes=implode(",", $deptcodes);
             
        }
       
        $deptdata = array();
        $deptdata['depts']=$dept_codes;
         return $deptdata;
    }

    public function getCoresubjectDetails()
    {
        
        $codatalist = Yii::$app->db->createCommand("SELECT A.subject_code  FROM cur_electivetodept A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE A.coe_electivetodept_id=".$this->coe_electivetodept_id)->queryAll();

        if(empty($codatalist))
        {
            $codatalist = Yii::$app->db->createCommand("SELECT A.subject_code  FROM cur_electivetodept A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE A.coe_electivetodept_id=".$this->coe_electivetodept_id)->queryAll();
        }

        return  ArrayHelper::map($codatalist,'subject_code','subject_code');
    }

     public function getDepartmentdetails2()
    {
        $codatalist = Yii::$app->db->createCommand("SELECT B.coe_dept_id  FROM cur_electivetodept A JOIN cur_curriculum_subject B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE A.coe_electivetodept_id=".$this->coe_electivetodept_id)->queryScalar();

        if(empty($codatalist))
        {
            $codatalist = Yii::$app->db->createCommand("SELECT B.coe_dept_id  FROM cur_electivetodept A JOIN cur_elective_subject B ON B.subject_code=A.subject_code AND B.coe_regulation_id=A.coe_regulation_id WHERE A.coe_electivetodept_id=".$this->coe_electivetodept_id)->queryScalar();
        }
       
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8 AND coe_dept_id!=".$codatalist)->queryAll();
            
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }

    public static function getSubjectTypeNew()
    {
        return [
        Yii::t('app', self::TYPE_E) => Yii::t('app', 'Exist'),
        Yii::t('app', self::TYPE_N) => Yii::t('app', 'New'), 
        //Yii::t('app', self::TYPE_NS) => Yii::t('app', 'NewSyllabus'), 
        ];  
    }

    public static function getSubjectTypeNew1()
    {
        return [
        Yii::t('app', self::TYPE_E) => Yii::t('app', 'Common/Existing Syllabus'),
        Yii::t('app', self::TYPE_NS) => Yii::t('app', 'New Syllabus'), 
        ];  
    }
   
    public static function getDegreeType()
    {
        return [
        Yii::t('app', self::TYPE_UG) => Yii::t('app', 'UG'),
        Yii::t('app', self::TYPE_PG) => Yii::t('app', 'PG'), 
        Yii::t('app', self::TYPE_MBA) => Yii::t('app', 'MBA'), 
        ];  
    }
}
