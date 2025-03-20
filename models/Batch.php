<?php

namespace app\models;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "coe_batch".
 *
 * @property integer $coe_batch_id
 * @property string $batch_name
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property CoeBatDegReg[] $coeBatDegRegs
 * @property User $createdBy
 * @property User $updatedBy
 * @property CoeRegulation[] $coeRegulations
 */
class Batch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $count_reg_year,$No_of_Section,$programme_code,$coe_degree_id;
    public static function tableName()
    {
        return 'coe_batch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch_name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['batch_name'], 'integer'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_batch_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' Id',
            'batch_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' Name / Year',
            'No_of_Section' => "No of ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION),
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
    public function getBatch()
    {
      $config_list = Batch::find()->orderBy(['batch_name'=>SORT_ASC])->all();
      $vals1 = ArrayHelper::map($config_list,'coe_batch_id','batch_name');
      return $vals1;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeBatDegRegs()
    {
        return $this->hasMany(CoeBatDegReg::className(), ['coe_batch_id' => 'coe_batch_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeRegulations()
    {
        return $this->hasMany(Regulation::className(), ['coe_batch_id' => 'coe_batch_id']);
    }
}
