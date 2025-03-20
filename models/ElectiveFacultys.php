<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

use yii\helpers\ArrayHelper;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Batch;
use app\models\CoeBatDegReg;
use app\models\Department;
use app\models\Categories;
use app\models\Categorytype;
use app\models\Regulation;
use app\models\Degree;
use app\models\LTP;
use app\models\SubjectPrefix;
use app\models\AicteNorms;

/**
 * This is the model class for table "cur_elective_facultys".
 *
 * @property integer $cur_ersf_id
 * @property integer $cur_elect_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $coe_elective_option
 * @property string $elective_paper
 * @property string $subject_code
 * @property integer $semester
 * @property string $faculty_ids
 * @property integer $approve_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class ElectiveFacultys extends \yii\db\ActiveRecord
{
    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_elective_facultys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_ers_id', 'degree_type', 'coe_batch_id', 'coe_regulation_id', 'coe_dept_id', 'coe_elective_option', 'elective_paper', 'subject_code', 'semester', 'faculty_ids', 'approve_status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['cur_ers_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type', 'coe_elective_option', 'elective_paper'], 'string', 'max' => 50],
            [['subject_code'], 'string', 'max' => 255],
            //[['faculty_ids'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_ersf_id' => 'Cur Ersf ID',
            'cur_ers_id' => 'Cur ERS ID',
            'degree_type' => 'Degree Type',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_dept_id' => 'Department',
            'coe_elective_option' => 'Elective Option',
            'elective_paper' => 'Elective Paper',
            'subject_code' => 'Subject Code',
            'semester' => 'Semester',
            'faculty_ids' => 'Faculty',
            'approve_status' => 'Approve Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function getDept()
    { 
         return $this->hasOne(Department::className(), ['coe_dept_id' => 'coe_dept_id']);
    }

    public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

    public static function getDegreeType()
    {
        return [
        Yii::t('app', self::TYPE_UG) => Yii::t('app', 'UG'),
        Yii::t('app', self::TYPE_PG) => Yii::t('app', 'PG'), 
        Yii::t('app', self::TYPE_MBA) => Yii::t('app', 'MBA'), 
        ];  
    }

    public function getElectivetype()
    { 
         return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'coe_elective_option']);
    }


     public function getBatch()
    { 
         return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id']);
    }


    public function getRegulationDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE regulation_year >=2023 GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }

    public function getDepartmentdetails()
    {
        $deptall =Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }

    public function getElectivetypeDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,category_type FROM coe_category_type WHERE category_id=27 AND description IN ('PEC','OEC','EEC')")->queryAll();
         //print_r($deptall); exit;
        return  ArrayHelper::map($deptall,'coe_category_type_id','category_type');
    }

    public function getFacultysdetails()
    {
        $facultyids = explode(",", $this->faculty_ids);
        
        $faculty_idsdata=array();
        for ($i=0; $i <count($facultyids) ; $i++) 
        { 
            $val_faculty=Yii::$app->db->createCommand("SELECT concat(faculty_name,' (',faculty_board,')') FROM coe_valuation_faculty WHERE coe_val_faculty_id=". $facultyids[$i])->queryScalar();
           $faculty_idsdata[]=$val_faculty;
        }  
        //print_r($faculty_idsdata); exit;
        $facultydata = array();
        $facultydata['facultydatas']=implode(", ", $faculty_idsdata);
         return $facultydata;
        
    }

    public function getCDCelectivefacutly() 
    {
        $coe_dept_id = $this->coe_dept_id;
                
        $borad='';
        if($coe_dept_id==9 || ($coe_dept_id>=15 && ($coe_dept_id<=19) || $coe_dept_id==22))
        {
            $borad=" AND faculty_board IN ('CSE/IT')";
        }
        else if($coe_dept_id==10 || $coe_dept_id==25)
        {
            $borad=" AND faculty_board IN ('CIVIL')";
        }
        else if($coe_dept_id==11 || $coe_dept_id==23)
        {
            $borad=" AND faculty_board IN ('MECH')";
        }
        else if($coe_dept_id==13 || $coe_dept_id==24)
        {
            $borad=" AND faculty_board IN ('EEE')";
        }
        else if($coe_dept_id==12 || $coe_dept_id==20)
        {
            $borad=" AND faculty_board IN ('ECE')";
        }
        else if($coe_dept_id==14)
        {
            $borad=" AND faculty_board IN ('ICE')";
        }
        else if($coe_dept_id==26)
        {
            $borad=" AND faculty_board IN ('MBA')";
        }
        else if($coe_dept_id==8)
        {
            $borad=" AND faculty_board IN ('MATHS','PHYSICS','CHEMISTRY','ENGLISH')";
        }

        $val_faculty = Yii::$app->db->createCommand("SELECT coe_val_faculty_id, concat(faculty_name,' (',faculty_board,')') as faculty_name FROM coe_valuation_faculty WHERE faculty_mode='INTERNAL' " . $borad)->queryAll();
       
        return  ArrayHelper::map($val_faculty,'coe_val_faculty_id','faculty_name');
    }
  
}
