<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_elective_waiver".
 *
 * @property integer $coe_elective_waiver_id
 * @property integer $student_map_id
 * @property integer $removed_sub_map_id
 * @property string $waiver_reason
 * @property integer $total_studied
 * @property string $subject_codes
 * @property integer $year
 * @property integer $month
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property CoeStudentMapping $studentMap
 * @property CoeCategoryType $month0
 */
class ElectiveWaiver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_elective_waiver';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'removed_sub_map_id', 'waiver_reason', 'total_studied', 'subject_codes', 'year', 'month', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['student_map_id', 'removed_sub_map_id','total_studied', 'year', 'month', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['waiver_reason'], 'string', 'max' => 1000],
            [['subject_codes'], 'string', 'max' => 255],
            [['student_map_id'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['month'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryType::className(), 'targetAttribute' => ['month' => 'coe_category_type_id']],
            [['removed_sub_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['removed_sub_map_id' => 'coe_subjects_mapping_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_elective_waiver_id' => 'Coe Elective Waiver ID',
            'student_map_id' => 'Student Map ID',
            'removed_sub_map_id' => 'Removed Sub Map ID',
            'waiver_reason' => 'Waiver Reason',
            'total_studied' => 'Match the count from configuration',
            'subject_codes' => 'Subject Codes',
            'year' => 'Year',
            'month' => 'Month',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
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
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['coe_student_id'=>'student_rel_id' ])->via('studentMap');
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
    public function getRemovedSubMap()
    {
        return $this->hasOne(SubjectsMapping::className(), ['coe_subjects_mapping_id' => 'removed_sub_map_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasOne(Subjects::className(), ['coe_subjects_id'=>'subject_id' ])->via('removedSubMap');
    }
}
