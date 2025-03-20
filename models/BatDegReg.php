<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "{{%coe_bat_deg_reg}}".
 *
 * @property integer $coe_mapping_id
 * @property integer $coe_degree_id
 * @property integer $coe_programme_id
 * @property integer $coe_batch_id
 * @property integer $no_of_section
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property CoeStudentMapping[] $coeStudentMappings
 */
class BatDegReg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_bat_deg_reg}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_degree_id', 'coe_programme_id', 'coe_batch_id', 'no_of_section', 'created_by', 'updated_by'], 'required'],
            [['coe_degree_id', 'coe_programme_id', 'coe_batch_id', 'no_of_section', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_mapping_id' => 'Coe Mapping ID',
            'coe_degree_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).'ID',
            'coe_programme_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ID',
            'coe_batch_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ID',
            'no_of_section' => 'No Of '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION),
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

   /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeDegree()
    {
        return $this->hasOne(Degree::className(), ['coe_degree_id' => 'coe_degree_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeProgramme()
    {
        return $this->hasOne(Programme::className(), ['coe_programme_id' => 'coe_programme_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeBatch()
    {
        return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id']);
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
    public function getCoeStudentMappings()
    {
        return $this->hasMany(StudentMapping::className(), ['course_batch_mapping_id' => 'coe_bat_deg_reg_id']);
    }
}
