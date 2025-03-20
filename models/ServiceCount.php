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
 * This is the model class for table "cur_service_count".
 *
 * @property integer $cur_sc_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property string $coe_dept_id
 * @property string $service_type
 * @property string $service_count
 * @property integer $approve_status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class ServiceCount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';

    public static function tableName()
    {
        return 'cur_service_count';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['degree_type', 'coe_batch_id', 'coe_regulation_id', 'coe_dept_id'], 'required'],
            [['coe_regulation_id', 'approve_status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['degree_type'], 'string', 'max' => 10],
            [['coe_dept_id'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_sc_id' => 'Cur Sc ID',
            'coe_batch_id' => 'Batch',
            'degree_type' => 'Degree Type',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_dept_id' => 'Dept.',
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

    public function getBatch()
    { 
         return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id']);
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
        $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE regulation_year >=2021 GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }

    public function getDepartmentdetails()
    {
        $deptall =Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }

}
