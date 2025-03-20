<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "{{%coe_store_dummy_mapping}}".
 *
 * @property integer $coe_store_dummy_mapping
 * @property integer $subject_map_id
 * @property integer $year
 * @property integer $month
 * @property integer $dummy_from
 * @property integer $dummy_to
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class DummySequence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_store_dummy_mapping}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject_map_id', 'year', 'month', 'dummy_from', 'dummy_to', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['subject_map_id', 'year', 'month', 'dummy_from', 'dummy_to', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_store_dummy_mapping' => 'Coe Store Dummy Mapping',
            'subject_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT),
            'year' => 'Year',
            'month' => 'Month',
            'dummy_from' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' From',
            'dummy_to' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' To',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectMap()
    {
        return $this->hasOne(SubjectsMapping::className(), ['coe_subjects_mapping_id' => 'subject_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectDet()
    {
        return $this->hasOne(Subjects::className(), ['coe_subjects_id' => 'subject_id'])->via('subjectMap');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonthName()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'month']);
    }
}
