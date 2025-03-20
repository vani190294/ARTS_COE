<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "%coe_degree".
 *
 * @property integer $coe_degree_id
 * @property string $degree_code
 * @property string $degree_name
 * @property string $degree_type
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $degree_total_years
 * @property integer $degree_total_semesters
 *
 * @property CoeBatDegReg[] $coeBatDegRegs
 * @property User $createdBy
 * @property User $updatedBy
 */
class Degree extends \yii\db\ActiveRecord
{
  const TYPE_UG= 'UG';
  const TYPE_PG= 'PG';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_degree';
    }

    /**
     * @inheritdoc
     */
   
    public function rules()
    {
        return [
		[['degree_code', 'degree_name', 'degree_type', 'created_by', 'updated_by','degree_total_years','degree_total_semesters'], 'required'],
		[['created_by', 'updated_by','degree_total_years','degree_total_semesters'], 'integer'],
		[['created_at', 'updated_at'], 'safe'],
		[['degree_code', 'degree_type'], 'string', 'max' => 100],
		[['degree_name'], 'string', 'max' => 255],
		[['degree_name','degree_type'], 'match' ,'pattern'=> '/^[A-Za-z. ]+$/u','message'=> 'Enter valid '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' description.'],
		[['degree_code'], 'match' ,'pattern'=> '/^[A-Za-z. ]+$/u','message'=> 'Enter valid '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' name.'],
		[['degree_total_years'],'compare','compareValue'=>'0','operator'=>'>','message'=>'Year must be positive integer'],
		[['degree_total_semesters'],'compare','compareValue'=>'0','operator'=>'>','message'=>'Semester must be positive integer'],
		//[['degree_code'], 'unique', 'targetAttribute' => ['degree_code', 'degree_name'], 'message' => 'The combination of Degree Code and Degree Name has already been taken.'],
		[['degree_code'], 'unique', 'targetAttribute' => ['degree_code'], 'message' => 'The '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' name has already been taken.'],
		[['degree_name'], 'unique', 'targetAttribute' => ['degree_name'], 'message' => 'The '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' description has already been taken.'],
		];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
		'coe_degree_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' ID',
		'degree_code' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' Name',
		'degree_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' Description',
		'degree_type' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' Type',
		'degree_total_years' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' Total Years',
		'degree_total_semesters' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' Total Semesters',
		'created_by' => 'Created By',
		'created_at' => 'Created At',
		'updated_by' => 'Updated By',
		'updated_at' => 'Updated At',
        ];
    }
    public static function getDegreeType()
    {
        return [
		Yii::t('app', self::TYPE_UG) => Yii::t('app', 'UG'),
		Yii::t('app', self::TYPE_PG) => Yii::t('app', 'PG'), 
		];  
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeBatDegRegs()
    {
        return $this->hasMany(CoeBatDegReg::className(), ['coe_degree_id' => 'coe_degree_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
