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
 * This is the model class for table "cur_vac_subject".
 *
 * @property integer $coe_vac_id
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $degree_type
 * @property integer $semester
 * @property string $subject_code
 * @property string $subject_name
 * @property double $course_hours
 * @property integer $subject_type_id
 * @property integer $subject_category_type_id
 * @property integer $approve_status
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class VacSubject extends \yii\db\ActiveRecord
{
    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';

    public static function tableName()
    {
        return 'cur_vac_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_regulation_id', 'coe_dept_id', 'degree_type', 'semester', 'subject_code', 'subject_name', 'course_hours', 'subject_type_id', 'subject_category_type_id', 'credit_point'], 'required'],
            [['coe_regulation_id', 'coe_dept_id', 'semester', 'subject_type_id', 'subject_category_type_id', 'approve_status', 'created_by', 'updated_by','credit_point'], 'integer'],
            [['subject_name'], 'string'],
            [['course_hours'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 10],
            [['subject_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_vac_id' => 'Coe Vac ID',
            'coe_regulation_id' => 'Regulation',
            'coe_dept_id' => 'Dept.',
            'degree_type' => 'Degree Type',
            'semester' => 'Semester',
            'subject_code' => 'Subject Code',
            'subject_name' => 'Subject Name',
            'course_hours' => 'Course Hours',
            'subject_type_id' => 'Subject Type',
            'subject_category_type_id' => 'Subject Category Type',
            'approve_status' => 'Approve Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'credit_point'=>'Credit Point'
        ];
    }

    public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

    public function getRegulationDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_regulation_id,regulation_year FROM coe_regulation GROUP BY regulation_year ORDER BY regulation_year DESC")->queryAll();
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
