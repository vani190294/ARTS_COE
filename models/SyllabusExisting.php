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

/**
 * This is the model class for table "cur_syllabus_existing".
 *
 * @property integer $cur_se_id
 * @property string $degree_type
 * @property integer $from_regulation_id
 * @property string $from_subject_code
 * @property integer $to_regulation_id
 * @property string $to_subject_code
 * @property integer $approve_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class SyllabusExisting extends \yii\db\ActiveRecord
{
    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';

    public static function tableName()
    {
        return 'cur_syllabus_existing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['degree_type', 'coe_dept_id', 'from_regulation_id', 'from_subject_code', 'to_regulation_id', 'to_subject_code'], 'required'],
            [['from_regulation_id', 'coe_dept_id', 'to_regulation_id', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 10],
            [['from_subject_code', 'to_subject_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_se_id' => 'Cur Se ID',
            'from_batch_id'=>'From Batch',
            'to_batch_id'=>'To Batch',
            'degree_type' => 'Degree Type',
            'coe_dept_id' => 'Dept.',
            'from_regulation_id' => 'From Regulation',
            'from_subject_code' => 'From Course Code',
            'to_regulation_id' => 'To Regulation',
            'to_subject_code' => 'To Course Code',
            'approve_status' => 'Approve Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function getSubject()
    { 
        $subject_code =''; $subject_name =''; 
        if(!empty($this->from_subject_code))
        {
            
            $subject_code=' WHERE subject_code IN ("'.$this->from_subject_code.'")';
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
        
        $where=" WHERE coe_dept_id =".$this->coe_dept_id." AND coe_regulation_id=".$this->from_regulation_id;

        $qdata1 = Yii::$app->db->createCommand("SELECT subject_code FROM cur_curriculum_subject ". $where)->queryAll();
   
        $qdata2 = Yii::$app->db->createCommand("SELECT subject_code FROM cur_elective_subject ". $where)->queryAll();
            
        $subjectdata=array_merge($qdata1,$qdata2);
        //print_r($subjectdata); exit();
        return  ArrayHelper::map($subjectdata,'subject_code','subject_code');
       
    }

    public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'from_regulation_id']);
    }
    public function getToRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'to_regulation_id']);
    }

    public function getFrombatch()
    { 
         return $this->hasOne(Batch::className(), ['coe_batch_id' => 'from_batch_id']);
    }

    public function getTobatch()
    { 
         return $this->hasOne(Batch::className(), ['coe_batch_id' => 'to_batch_id']);
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

     public function getDepartmentdetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8 ORDER BY coe_dept_id ASC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }
}
