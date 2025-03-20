<?php

namespace app\models;

use Yii;
use app\models\Student;


use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/**
 * This is the model class for table "coe_value_nominal".
 *
 * @property integer $coe_nominal_val_id
 * @property integer $course_batch_mapping_id
 * @property integer $coe_student_id
 * @property integer $coe_subjects_id
 * @property string $section_name
 * @property integer $semester
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class CoeValueNominal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_value_nominal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_batch_mapping_id', 'coe_student_id', 'coe_subjects_id', 'section_name', 'semester', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['course_batch_mapping_id', 'coe_student_id', 'coe_subjects_id', 'semester', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['section_name'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
           
             'coe_nominal_val_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL).' ID',
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
