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
 * This is the model class for table "cur_elective_register".
 *
 * @property integer $cur_elect_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $pec_paper
 * @property string $oec_paper
 * @property string $eec_paper
 * @property string $mc_paper
 * @property integer $semester
 * @property integer $approve_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class ElectiveRegister extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';

    public static function tableName()
    {
        return 'cur_elective_register';
    }

    /**
     * @inheritdoc
     */
    public $month;
    public function rules()
    {
        return [
            [['degree_type','coe_batch_id',  'coe_regulation_id', 'coe_dept_id', 'semester', 'approve_status'], 'required'],
            [['coe_regulation_id', 'coe_dept_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type', 'mc_paper'], 'string', 'max' => 50],
            [['pec_paper', 'oec_paper', 'eec_paper'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_elect_id' => 'Cur Elect ID',
            'degree_type' => 'Degree Type',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_dept_id' => 'Dept.',
            'pec_paper' => 'PEC',
            'oec_paper' => 'OEC',
            'eec_paper' => 'EEC',
            'mc_paper' => 'MC',
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

    public function getMonth()
    {
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
    }

}
