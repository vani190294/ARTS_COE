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
 * This is the model class for table "cur_core_facultys".
 *
 * @property integer $cur_cf_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $subject_code
 * @property integer $semester
 * @property string $faculty_ids
 * @property string $no_of_section
 * @property integer $approve_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class CoreFacultys extends \yii\db\ActiveRecord
{
    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_core_facultys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['degree_type', 'coe_regulation_id', 'coe_dept_id', 'semester'], 'required'],
            [['coe_regulation_id', 'coe_dept_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 50],
            [['no_of_section'], 'string', 'max' => 10],
            [['faculty_ids'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_cf_id' => 'Cur Cf ID',
            'degree_type' => 'Degree Type',
            'coe_regulation_id' => 'Regulation',
            'coe_dept_id' => 'Dept',
            'semester' => 'Semester',
            'no_of_section' => 'No Of Section',
            'approve_status' => 'Approve Status',
            'faculty_ids' => 'Faculty',
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

    public function getRegulationDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_regulation_id,regulation_year FROM coe_regulation GROUP BY regulation_year ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }

    public function getDepartmentdetails()
    {
        $deptall =Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }
    
}
