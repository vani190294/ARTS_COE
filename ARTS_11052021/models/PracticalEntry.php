<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "{{%coe_practical_entry}}".
 *
 * @property integer $coe_practical_entry_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $out_of_100
 * @property integer $ESE
 * @property integer $year
 * @property integer $month
 * @property integer $term
 * @property integer $mark_type
 * @property string $examiner_name
 * @property string $approve_status
 * @property string $chief_exam_name
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property User $createdBy
 * @property CoeCategoryType $markType
 * @property CoeCategoryType $month0
 * @property CoeStudentMapping $studentMap
 * @property CoeSubjectsMapping $subjectMap
 * @property CoeCategoryType $term0
 * @property User $updatedBy
 */
class PracticalEntry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_practical_entry}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'subject_map_id', 'out_of_100', 'ESE', 'year', 'month', 'term', 'mark_type', 'examiner_name','chief_exam_name','created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['student_map_id', 'subject_map_id', 'out_of_100', 'ESE', 'year', 'month', 'term', 'mark_type', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['examiner_name','approve_status','chief_exam_name'], 'string', 'max' => 255],
            [['student_map_id', 'subject_map_id', 'year', 'month', 'mark_type'], 'unique', 'targetAttribute' => ['student_map_id', 'subject_map_id', 'year', 'month', 'mark_type'], 'message' => 'The combination of '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' , '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).', Year, Month and Mark Type has already been taken.'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['mark_type'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['mark_type' => 'coe_category_type_id']],
            [['month'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['month' => 'coe_category_type_id']],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['subject_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['subject_map_id' => 'coe_subjects_mapping_id']],
            [['term'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['term' => 'coe_category_type_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_practical_entry_id' => 'Coe Practical Entry ID',
            'student_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
            'subject_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code',
            'out_of_100' => 'Out Of 100',
            'ESE' => 'Ese',
            'year' => 'Year',
            'month' => 'Month',
            'term' => 'Term',
            'mark_type' => 'Mark Type',
            'examiner_name' => 'Examiner Name',
            'approve_status' => 'Approve Status',
            'chief_exam_name' => 'Chief Exam Name',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
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
    public function getMarkType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'mark_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonth0()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'month']);
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
    public function getTerm0()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'term']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
