<?php

namespace app\models;


use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/**
 * This is the model class for table "coe_prac_stu_per_batch".
 *
 * @property integer $coe_spb_id
 * @property integer $coe_batch_id
 * @property integer $batch_mapping_id
 * @property integer $subject_map_id
 * @property string $subject_code
 * @property integer $stu_per_batch_count
 * @property integer $exam_year
 * @property integer $exam_month
 * @property integer $exam_type
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class PracStuPerBatch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_prac_stu_per_batch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_batch_id', 'batch_mapping_id', 'subject_map_id', 'subject_code', 'stu_per_batch_count', 'exam_year', 'exam_month', 'exam_type'], 'required'],
            [['coe_batch_id', 'batch_mapping_id', 'subject_map_id', 'stu_per_batch_count', 'exam_year', 'exam_month', 'exam_type', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['subject_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_spb_id' => 'Coe Spb ID',
            'coe_batch_id' => 'Batch',
            'batch_mapping_id' => 'Programme',
            'subject_map_id' => 'Subject',
            'subject_code' => 'Subject Code',
            'stu_per_batch_count' => 'Student Per Batch Count',
            'exam_year' => 'Exam Year',
            'exam_month' => 'Exam Month',
            'exam_type' => 'Exam Type',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
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
    public function getMonth()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'exam_month']);
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
    public function getBatch()
    {
        return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id']);
    }
    public function getDegree()
    { 
         return $this->hasOne(Degree::className(), ['coe_degree_id' => 'coe_degree_id'])->via('batchMapping');
    }
    public function getProgramme()
    {
        return $this->hasOne(Programme::className(), ['coe_programme_id' => 'coe_programme_id'])->via('batchMapping');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarkType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'exam_type']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamSess()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'exam_session']);
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
        return $this->hasOne(Student::className(), ['coe_student_id' => 'student_rel_id'])->via('studentMap');
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
    public function getSubject()
    {
        return $this->hasOne(Subjects::className(), ['coe_subjects_id' => 'subject_id'])->via('subjectMap');
    }
}
