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
 * This is the model class for table "cur_elective_stu_subject".
 *
 * @property integer $cur_erss_id
 * @property integer $cur_ers_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $coe_elective_option
 * @property string $elective_paper
 * @property string $subject_code
 * @property integer $semester
 * @property integer $approve_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class ElectiveStuSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';

    public static function tableName()
    {
        return 'cur_elective_stu_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_ers_id', 'coe_batch_id',  'batch_map_id', 'degree_type', 'coe_regulation_id', 'coe_dept_id', 'coe_elective_option', 'elective_paper', 'subject_code', 'semester'], 'required'],
            [['cur_ers_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type', 'coe_elective_option', 'elective_paper'], 'string', 'max' => 50],
            [['subject_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_erss_id' => 'Cur Erss ID',
            'cur_ers_id' => 'Cur ERS ID',
            'degree_type' => 'Degree Type',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_dept_id' => 'Department',
            'coe_elective_option' => 'Elective Option',
            'elective_paper' => 'Elective Paper',
            'subject_code' => 'Subject Code',
            'semester' => 'Semester',
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
         $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }

    public function getDepartmentdetails()
    {
        $deptall =Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }

    public function getDeptRegulation()
    {
        return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

    public function getDeptProgramme()
    {
        return $this->hasOne(Department::className(), ['coe_dept_id' => 'coe_dept_id']);
    }

    public function getElectivetypeDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,category_type FROM coe_category_type WHERE category_id=27 AND description IN ('PEC','OEC','EEC')")->queryAll();
         //print_r($deptall); exit;
        return  ArrayHelper::map($deptall,'coe_category_type_id','category_type');
    }

    public function getStudentregnum() 
    {
       
        $batch_map_id=$this->batch_map_id; //exit;

        $det_cat_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE 'detain%'")->queryScalar();

        $det_disc_val = Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type LIKE '%Discontinued%'")->queryScalar();

        $reg_num = Yii::$app->db->createCommand("SELECT A.register_number FROM coe_student as A,coe_student_mapping as B WHERE B.student_rel_id=A.coe_student_id and B.course_batch_mapping_id='" . $batch_map_id . "' and B.status_category_type_id NOT IN ('" . $det_cat_val . "','".$det_disc_val."') and A.student_status='Active' order by A.register_number")->queryAll();

        return  ArrayHelper::map($reg_num,'register_number','register_number');
    }
}
