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

/**
 * This is the model class for table "cur_aicte_norms".
 *
 * @property integer $cur_an_id
 * @property integer $coe_dept_id
 * @property string $stream_name
 * @property integer $aicte_norms
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class AicteNorms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';

    public static function tableName()
    {
        return 'cur_aicte_norms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_regulation_id','coe_dept_id', 'degree_type', 'stream_name','stream_fullname', 'aicte_norms'], 'required'],
            [['coe_dept_id', 'aicte_norms', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['stream_fullname','stream_name'], 'string', 'max' => 255],
            [['degree_type'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_an_id' => 'Cur An ID',
            'coe_regulation_id' => 'Regulation',
            'coe_dept_id' => 'Department',
            'degree_type' => 'Degree Type',
            'stream_name' => 'Stream Short Name',
            'stream_fullname'=>'Stream Name',
            'aicte_norms' => 'AICTE Norms',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

    public function getDept()
    { 
         return $this->hasOne(Department::className(), ['coe_dept_id' => 'coe_dept_id']);
    }

     public function getDepartmentdetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }

    public function getRegulationDetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE regulation_year >=2021 GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }

    public function getBatchname()
    {
        $coe_regulation_id=$this->coe_regulation_id;

        $batch_name = Yii::$app->db->createCommand("SELECT B.batch_name FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE A.coe_regulation_id=".$coe_regulation_id)->queryScalar();

        $batch_name1 = array();
         $batch_name1['batch_name']=$batch_name;
        return  $batch_name1;
    }

    public static function getDegreeType()
    {
        return [
        Yii::t('app', self::TYPE_UG) => Yii::t('app', 'UG'),
        Yii::t('app', self::TYPE_PG) => Yii::t('app', 'PG'), 
        Yii::t('app', self::TYPE_MBA) => Yii::t('app', 'MBA'), 
        ];  
    }

}
