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
use app\models\VerticalStream;
/**
 * This is the model class for table "cur_elective_subject".
 *
 * @property integer $coe_elective_id
 * @property integer $external_mark
 * @property integer $internal_mark
 * @property integer $coe_batch_id
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $degree_type
 * @property integer $semester
 * @property integer $coe_elective_option
 * @property string $subject_code
 * @property string $subject_name
 * @property integer $coe_ltp_id
 * @property integer $subject_type_id
 * @property integer $subject_category_type_id
 * @property string $remarks
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class ElectiveSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

   const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
     const TYPE_MBA= 'MBA';

    public static function tableName()
    {
        return 'cur_elective_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stream_id','external_mark', 'internal_mark', 'coe_batch_id', 'coe_regulation_id', 'coe_dept_id', 'degree_type', 'coe_elective_option', 'subject_code', 'subject_name', 'coe_ltp_id', 'subject_type_id', 'subject_category_type_id'], 'required'],
            [['external_mark', 'internal_mark', 'coe_batch_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'coe_elective_option', 'coe_ltp_id', 'created_by', 'updated_by','cur_vs_id'], 'integer'],
            [['subject_name'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 3],
            [['subject_code'], 'string', 'min' => 0,'max' => 8],
            [['external_mark', 'internal_mark'], 'integer','min' => 0, 'max' => 100],
            [['approve_status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_elective_id' => 'Coe Ele ID',
            'external_mark' => 'External Mark',
            'internal_mark' => 'Internal Mark',
            'coe_batch_id' => ' Batch',
            'coe_regulation_id' =>  'Regulation(Batch)',
            'coe_dept_id' => 'Dept',
            'degree_type' => 'Degree Type',
            'semester' => 'Semester',
            'coe_elective_option' => 'Elective Option',
            'subject_code' => 'Course Code',
            'subject_name' => 'Course Name',
            'coe_ltp_id' => 'LTP',
            'subject_type_id' => 'Course Type',
            'subject_category_type_id' => 'Assessment Type',
            'stream_id'=>'Course Category',
            'cur_vs_id' => 'Vertical Stream',
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

    public function getBatch()
    { 
         return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id']);
    }

    public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

    public function getVertical()
    { 
         return $this->hasOne(VerticalStream::className(), ['cur_vs_id' => 'cur_vs_id']);
    }

    public function getLtp()
    { 
        $ltps = Yii::$app->db->createCommand("SELECT * FROM cur_ltp WHERE coe_ltp_id=". $this->coe_ltp_id)->queryOne();

        $countryList = array();
        $countryList['LTP']=$ltps['L'].'-'.$ltps['T'].'-'.$ltps['P'];
        $countryList['contact_hrsperweek']=$ltps['contact_hrsperweek'];
        $countryList['credit_point']=$ltps['credit_point'];
         return $countryList;
    }

    public function getElectivetype()
    { 
         return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'coe_elective_option']);
    }

    public function getSubjecttype()
    { 
         return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'subject_type_id']);
    }

    public function getSubjectctype()
    { 
         return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'subject_category_type_id']);
    }

     public function getDepartmentdetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }

    public function getBatchDetails()
    {
         $batch_list = Yii::$app->db->createCommand("SELECT coe_batch_id,batch_name FROM coe_batch WHERE batch_name >=2023 ORDER BY batch_name DESC")->queryAll();
        return  ArrayHelper::map($batch_list,'coe_batch_id','batch_name');
    }

    public function getRegulationDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }
    
    public function getLTPdetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_ltp_id,concat(L,'-',T,'-',P) as LTP FROM cur_ltp WHERE coe_regulation_id=".$this->coe_regulation_id)->queryAll();
        return  ArrayHelper::map($deptall,'coe_ltp_id','LTP');
    }

    public static function getDegreeType()
    {
        return [
        Yii::t('app', self::TYPE_UG) => Yii::t('app', 'UG'),
        Yii::t('app', self::TYPE_PG) => Yii::t('app', 'PG'), 
        Yii::t('app', self::TYPE_MBA) => Yii::t('app', 'MBA'), 
        ];  
    }

    public function getSubjecttypeDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,category_type FROM coe_category_type WHERE category_id=3 AND (coe_category_type_id NOT IN (9,38))")->queryAll();
        return  ArrayHelper::map($deptall,'coe_category_type_id','category_type');
    }

    public function getSubjectctypeDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,category_type FROM coe_category_type WHERE category_id=24")->queryAll();
        return  ArrayHelper::map($deptall,'coe_category_type_id','category_type');
    }
    public function getElectivetypeDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,category_type FROM coe_category_type WHERE category_id=27")->queryAll();
        return  ArrayHelper::map($deptall,'coe_category_type_id','category_type');
    }

    public function getElectivetypeDetails1()
    {   
        //echo Yii::$app->user->getDeptId(); exit();
        // if(Yii::$app->user->getDeptId()=='9,22' || Yii::$app->user->getDeptId()==15)
        // {
        //      $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,description FROM coe_category_type WHERE category_id=27 AND description IN ('PCC','PEC','ESC')")->queryAll();
        // }
        // else
        // {
            $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,description FROM coe_category_type WHERE category_id=27 AND description IN ('PCC','PEC','ESC','EEC','HSMC')")->queryAll();
        //}
        return  ArrayHelper::map($deptall,'coe_category_type_id','description');
    }
}
