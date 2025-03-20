<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "coe_programme".
 *
 * @property integer $coe_programme_id
 * @property string $programme_code
 * @property string $programme_name
 * @property integer $programme_total_years
 * @property integer $programme_total_semesters
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property CoeBatDegReg[] $coeBatDegRegs
 * @property User $createdBy
 * @property User $updatedBy
 */
class Programme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_programme';
    }

    /**
     * @inheritdoc
     */
   
    public function rules()
    {
        return [
            [['programme_code', 'programme_name','created_by', 'updated_by'], 'required'],
            [[ 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['programme_code'], 'string', 'max' => 100],
            [['programme_name'], 'string', 'max' => 255],
            [['programme_name'], 'match' ,'pattern'=> '/^[A-Za-z. ]+$/u','message'=> 'Enter valid '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' description.'],
            [['programme_code'], 'match' ,'pattern'=> '/^[A-Za-z0-9. ]+$/u','message'=> 'Enter valid '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' name.'],
            [['programme_code'], 'unique', 'targetAttribute' => ['programme_code'], 'message' => 'This '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' CODE has already been taken.'],
            [['programme_name'], 'unique', 'targetAttribute' => ['programme_name'], 'message' => 'This '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' NAME has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_programme_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ID',
            'programme_code' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' Name',
            'programme_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' Description',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeBatDegRegs()
    {
        return $this->hasMany(CoeBatDegReg::className(), ['coe_programme_id' => 'coe_programme_id']);
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
