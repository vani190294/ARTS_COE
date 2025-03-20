<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "{{%coe_practical_exam_timetable}}".
 *
 * @property integer $coe_practical_exam_timetable_id
 * @property integer $batch_mapping_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $exam_year
 * @property integer $exam_month
 * @property integer $mark_type
 * @property integer $term
 * @property string $exam_date
 * @property string $exam_session
 * @property integer $out_of_100
 * @property integer $ESE
 * @property string $internal_examiner_name
 * @property string $external_examiner_name
 * @property string $approve_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Categorytype $term0
 * @property User $createdBy
 * @property User $updatedBy
 * @property CoeBatDegReg $batchMapping
 * @property Categorytype $markType
 * @property StudentMapping $studentMap
 * @property SubjectsMapping $subjectMap
 */
class PracticalExamTimetable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_practical_exam_timetable}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch_mapping_id', 'student_map_id', 'subject_map_id', 'exam_year', 'exam_month', 'mark_type', 'term', 'exam_date', 'exam_session', 'internal_examiner_name'], 'required'],
            [['batch_mapping_id', 'student_map_id', 'subject_map_id', 'exam_year', 'exam_month', 'mark_type', 'term', 'out_of_100', 'ESE', 'created_by', 'updated_by'], 'integer'],
            [['exam_date', 'exam_session', 'created_at', 'updated_at'], 'safe'],
            [['internal_examiner_name'], 'string', 'max' => 145],
            [['external_examiner_name'], 'string', 'max' => 245],
            [['approve_status'], 'string', 'max' => 45],
            [['batch_mapping_id', 'student_map_id', 'subject_map_id', 'exam_year', 'exam_month', 'mark_type', 'term', 'exam_date', 'exam_session'], 'unique', 'targetAttribute' => ['batch_mapping_id', 'student_map_id', 'subject_map_id', 'exam_year', 'exam_month', 'mark_type', 'term', 'exam_date', 'exam_session'], 'message' => 'The combination of Batch Mapping ID, Student Map ID, Subject Map ID, Exam Year, Exam Month, Mark Type, Term, Exam Date and Exam Session has already been taken.'],
            [['term'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['term' => 'coe_category_type_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['batch_mapping_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoeBatDegReg::className(), 'targetAttribute' => ['batch_mapping_id' => 'coe_bat_deg_reg_id']],
            [['mark_type'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['mark_type' => 'coe_category_type_id']],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['subject_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['subject_map_id' => 'coe_subjects_mapping_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_practical_exam_timetable_id' => 'Practical Exam Timetable ID',
            'batch_mapping_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE),
            'student_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
            'subject_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT),
            'exam_year' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Year',
            'exam_month' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month',
            'mark_type' => 'Mark Type',
            'term' => 'Term',
            'exam_date' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date',
            'exam_session' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Session',
            'out_of_100' => 'Out Of 100',
            'ESE' => 'Ese',
            'internal_examiner_name' => 'Internal Examiner Name',
            'external_examiner_name' => 'External Examiner Name',
            'approve_status' => 'Approve Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerm0()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'term']);
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
    public function getBatchMapping()
    {
        return $this->hasOne(CoeBatDegReg::className(), ['coe_bat_deg_reg_id' => 'batch_mapping_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarkType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'mark_type']);
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
}
