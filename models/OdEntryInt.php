<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_od_entry_int".
 *
 * @property integer $coe_od_entry_id
 * @property integer $student_map_id
 * @property integer $exam_type
 * @property integer $absent_term
 * @property string $exam_date
 * @property integer $exam_year
 * @property integer $exam_month
 * @property string $exam_session
 * @property integer $subject_map_id
 * @property integer $exam_status
 * @property integer $internal_number
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class OdEntryInt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_od_entry_int';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'exam_type', 'absent_term', 'subject_map_id', 'exam_status', 'internal_number', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['student_map_id', 'exam_type', 'absent_term', 'exam_year', 'exam_month', 'subject_map_id', 'exam_status', 'internal_number', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['exam_date', 'exam_session'], 'string', 'max' => 45],
            [['student_map_id', 'exam_type', 'absent_term', 'exam_month', 'subject_map_id', 'exam_year'], 'unique', 'targetAttribute' => ['student_map_id', 'exam_type', 'absent_term', 'exam_month', 'subject_map_id', 'exam_year'], 'message' => 'The combination of Student Map ID, Exam Type, Absent Term, Exam Year, Exam Month and Subject Map ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_od_entry_id' => 'Coe Od Entry ID',
            'student_map_id' => 'Student Map ID',
            'exam_type' => 'Exam Type',
            'absent_term' => 'Absent Term',
            'exam_date' => 'Exam Date',
            'exam_year' => 'Exam Year',
            'exam_month' => 'Exam Month',
            'exam_session' => 'Exam Session',
            'subject_map_id' => 'Subject Map ID',
            'exam_status' => 'Exam Status',
            'internal_number' => 'Internal Number',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
