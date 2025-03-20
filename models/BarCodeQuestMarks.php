<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/**
 * This is the model class for table "{{%coe_bar_code_quest_marks}}".
 *
 * @property string $coe_bar_code_quest_marks_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $dummy_number
 * @property integer $year
 * @property integer $month
 * @property integer $question_no
 * @property integer $question_no_marks
 * @property integer $mark_type
 * @property integer $term
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property DummyNumbers $DummyNumbers
 * @property Categorytype $term0
 * @property Categorytype $markType
 * @property StudentMapping $studentMap
 * @property SubjectsMapping $subjectMap
 * @property User $createdBy
 * @property User $updatedBy
 */
class BarCodeQuestMarks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_bar_code_quest_marks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'subject_map_id', 'dummy_number', 'year', 'month', 'question_no', 'question_no_marks', 'mark_type', 'term', 'created_by', 'updated_by'], 'required'],
            [['student_map_id', 'subject_map_id', 'dummy_number', 'year', 'month', 'question_no', 'question_no_marks', 'mark_type', 'term', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['student_map_id', 'subject_map_id', 'year', 'month', 'question_no', 'mark_type', 'term'], 'unique', 'targetAttribute' => ['student_map_id', 'subject_map_id', 'year', 'month', 'question_no', 'mark_type', 'term'], 'message' => 'The combination of Student Map ID, Subject Map ID, Year, Month, Question No, Mark Type and Term has already been taken.'],
            [['dummy_number'], 'exist', 'skipOnError' => true, 'targetClass' => DummyNumbers::className(), 'targetAttribute' => ['dummy_number' => 'coe_dummy_number_id']],
            [['term'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['term' => 'coe_category_type_id']],
            [['mark_type'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['mark_type' => 'coe_category_type_id']],
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
            'coe_bar_code_quest_marks_id' => 'Coe Bar Code Quest Marks ID',
            'student_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
            'subject_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT),
            'dummy_number' => 'Dummy Number',
            'year' => 'Year',
            'month' => 'Month',
            'question_no' => 'Question No',
            'question_no_marks' => 'Question No Marks',
            'mark_type' => 'Mark Type',
            'term' => 'Term',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDummyNumbers()
    {
        return $this->hasOne(DummyNumbers::className(), ['coe_dummy_number_id' => 'dummy_number']);
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
