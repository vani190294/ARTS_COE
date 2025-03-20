<?php

namespace app\models;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use Yii;

/**
 * This is the model class for table "coe_revaluation".
 *
 * @property integer $coe_revaluation_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $year
 * @property integer $month
 * @property string $is_transparency
 * @property integer $mark_type
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property CoeStudentMapping $studentMap
 * @property CoeSubjectsMapping $subjectMap
 * @property User $createdBy
 * @property User $updatedBy
 */
class Revaluation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_revaluation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'subject_map_id', 'year', 'month', 'mark_type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['student_map_id', 'subject_map_id', 'year', 'month', 'mark_type', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_transparency'], 'string', 'max' => 45],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['subject_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['subject_map_id' => 'coe_subjects_mapping_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_revaluation_id' => 'Coe Revaluation ID',
            'student_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Register Number',
            'subject_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' MAPPING',
            'year' => 'Year',
            'month' => 'Month',
            'is_transparency' => 'Is Transparency',
            'mark_type' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MARK_TYPE),
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ]; 	
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentMap()
    {
        return $this->hasOne(StudentMapping::className(), ['coe_student_mapping_id' => 'student_map_id']);
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
