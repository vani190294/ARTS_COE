<?php

namespace app\models;
use app\models\Student;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "coe_nominal".
 *
 * @property integer $coe_nominal_id
 * @property integer $coe_subjects_mapping_id
 * @property integer $coe_student_id
 * @property integer $semester
 * @property integer $coe_subjects_id
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class Nominal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_nominal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_batch_mapping_id', 'coe_student_id','section_name','semester', 'coe_subjects_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['course_batch_mapping_id', 'coe_student_id', 'semester', 'coe_subjects_id', 'created_by', 'updated_by'], 'integer'],
            [['section_name'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_nominal_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL).' ID',
            'course_batch_mapping_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).'  '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).'Mapping Id',
            'coe_student_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' ID',
            'section_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION).' Name',
            'semester' => 'Semester',
            'coe_subjects_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' ID',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
