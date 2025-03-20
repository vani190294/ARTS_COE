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
use app\models\ValuationFaculty;

/**
 * This is the model class for table "cur_elective_faculty_list".
 *
 * @property integer $cur_ef_id
 * @property integer $cur_ersf_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $coe_elective_option
 * @property string $elective_paper
 * @property string $subject_code
 * @property integer $faculty_id
 * @property integer $semester
 * @property integer $approve_status
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class ElectiveFacultyList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';

    public static function tableName()
    {
        return 'cur_elective_faculty_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_ersf_id', 'degree_type', 'coe_batch_id',  'coe_regulation_id', 'coe_dept_id', 'coe_elective_option', 'elective_paper', 'subject_code', 'faculty_id', 'semester', 'approve_status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['cur_ersf_id', 'coe_regulation_id', 'coe_dept_id', 'faculty_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 10],
            [['coe_elective_option', 'elective_paper', 'subject_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_ef_id' => 'Cur Ef ID',
            'cur_ersf_id' => 'Cur Ersf ID',
            'degree_type' => 'Degree Type',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_dept_id' => 'Department',
            'coe_elective_option' => ' Elective Option',
            'elective_paper' => 'Elective Paper',
            'subject_code' => 'Subject Code',
            'faculty_id' => 'Faculty',
            'semester' => 'Semester',
            'approve_status' => 'Approve Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
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

    
     public function getBatch()
    { 
         return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id']);
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
        $facultyids = $this->faculty_id;
        
        $val_faculty=Yii::$app->db->createCommand("SELECT concat(faculty_name,' (',faculty_board,')') FROM coe_valuation_faculty WHERE coe_val_faculty_id=". $this->faculty_id)->queryScalar();

        $facultydata = array();
        $facultydata['facultydatas']=$val_faculty;
         return $facultydata;
        
    }

}
