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
 * This is the model class for table "cur_additional_course_rejoin_list".
 *
 * @property integer $cur_acrjl_id
 * @property integer $cur_acrj_id
 * @property integer $batch_map_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $register_number
 * @property string $subject_code
 * @property string $subject_name
 * @property integer $semester
 * @property integer $approve_status
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class AdditionalCourseRejoinList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';


    public static function tableName()
    {
        return 'cur_additional_course_rejoin_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_acrj_id', 'batch_map_id', 'degree_type', 'coe_regulation_id', 'coe_dept_id', 'register_number', 'subject_code', 'subject_name', 'semester', 'approve_status','student_status'], 'required'],
            [['cur_acrj_id', 'batch_map_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 10],
            [['register_number', 'subject_name'], 'string', 'max' => 255],
            [['subject_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_acrjl_id' => 'Cur Acrjl ID',
            'cur_acrj_id' => 'Cur Acrj ID',
            'batch_map_id' => 'Batch Map ID',
            'degree_type' => 'Degree Type',
            'coe_regulation_id' => 'Regulation',
            'coe_dept_id' => 'Dept',
            'register_number' => 'Register Number',
            'subject_code' => 'Subject Code',
            'subject_name' => 'Subject Name',
            'semester' => 'Semester',
            'approve_status' => 'Approve Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'student_status'=> 'Student Type'
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

    public function getMonth()
    {
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
    }

}
