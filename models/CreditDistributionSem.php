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
use app\models\AicteNorms;

/**
 * This is the model class for table "cur_credit_distribution_sem".
 *
 * @property integer $cur_dist_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property integer $cur_stream_id
 * @property double $sem1
 * @property double $sem2
 * @property double $sem3
 * @property double $sem4
 * @property double $sem5
 * @property double $sem6
 * @property double $sem7
 * @property double $sem8
 * @property integer $total_credit
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class CreditDistributionSem extends \yii\db\ActiveRecord
{
     const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_credit_distribution_sem';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['degree_type', 'coe_batch_id', 'coe_regulation_id', 'coe_dept_id', 'cur_stream_id', 'sem1', 'sem2', 'sem3', 'sem4', 'sem5', 'sem6', 'sem7', 'sem8', 'total_credit'], 'required'],
            [['coe_regulation_id', 'coe_dept_id', 'cur_stream_id', 'created_by', 'updated_by'], 'integer'],
            [['sem1', 'sem2', 'sem3', 'sem4', 'sem5', 'sem6', 'sem7', 'sem8'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 10],
            [['cur_stream_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_dist_id' => 'Cur Dist ID',
            'degree_type' => 'Degree Type',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_dept_id' => 'Dept',
            'cur_stream_id' => 'Stream',
            'sem1' => 'Sem1',
            'sem2' => 'Sem2',
            'sem3' => 'Sem3',
            'sem4' => 'Sem4',
            'sem5' => 'Sem5',
            'sem6' => 'Sem6',
            'sem7' => 'Sem7',
            'sem8' => 'Sem8',
            'total_credit' => 'Total Credit',
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

    public function getStream()
    { 
         return $this->hasOne(AicteNorms::className(), ['cur_an_id' => 'cur_stream_id']);
    }

     public function getDepartmentdetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
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
         $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE regulation_year >=2023 GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }
}
