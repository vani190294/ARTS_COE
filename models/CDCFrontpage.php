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
 * This is the model class for table "cur_frontpage".
 *
 * @property integer $cur_fp_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property integer $mission_count
 * @property integer $peo_count
 * @property integer $pso_count
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class CDCFrontpage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';

    public static function tableName()
    {
        return 'cur_frontpage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['degree_type', 'coe_regulation_id', 'coe_dept_id', 'mission_count', 'peo_count', 'pso_count'], 'required'],
            [['coe_regulation_id', 'coe_dept_id', 'mission_count', 'peo_count', 'pso_count', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 10],
            [['mission_count', 'peo_count', 'pso_count',], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_fp_id' => 'Cur Fp ID',
            'degree_type' => 'Degree Type',
            'coe_regulation_id' => 'Regulation',
            'coe_dept_id' => 'Department',
            'mission_count' => 'Mission Count',
            'peo_count' => 'PEO Count',
            'pso_count' => 'PSO Count',
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

    public static function getDegreeType()
    {
        return [
        Yii::t('app', self::TYPE_UG) => Yii::t('app', 'UG'),
        Yii::t('app', self::TYPE_PG) => Yii::t('app', 'PG'), 
        Yii::t('app', self::TYPE_MBA) => Yii::t('app', 'MBA'), 
        ];  
    }

    public function getBatchname()
    {
        $coe_regulation_id=$this->coe_regulation_id;

        $batch_name = Yii::$app->db->createCommand("SELECT B.batch_name FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE A.coe_regulation_id=".$coe_regulation_id)->queryScalar();

        $batch_name1 = array();
         $batch_name1['batch_name']=$batch_name;
        return  $batch_name1;
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
