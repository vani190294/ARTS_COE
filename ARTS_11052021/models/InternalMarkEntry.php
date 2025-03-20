<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "internal_mark_entry".
 *
 * @property integer $mark_entry_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $category_type_id
 * @property integer $mark_out_of
 * @property integer $category_type_id_marks
 * @property integer $year
 * @property integer $month
 * @property integer $term
 * @property integer $mark_type
 * @property integer $status_id
 * @property integer $attendance_percentage
 * @property string $attendance_remarks
 * @property string $is_updated
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class InternalMarkEntry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'internal_mark_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'subject_map_id', 'category_type_id', 'category_type_id_marks', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['student_map_id', 'subject_map_id', 'category_type_id', 'mark_out_of', 'category_type_id_marks', 'year', 'month', 'term', 'mark_type', 'status_id', 'attendance_percentage', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['attendance_remarks', 'is_updated'], 'string', 'max' => 45],
            [['student_map_id', 'subject_map_id', 'category_type_id', 'month', 'year'], 'unique', 'targetAttribute' => ['student_map_id', 'subject_map_id', 'category_type_id', 'month', 'year'], 'message' => 'The combination of Student Map ID, Subject Map ID, Category Type ID, Year and Month has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mark_entry_id' => 'Mark Entry ID',
            'student_map_id' => 'Student Map ID',
            'subject_map_id' => 'Subject Map ID',
            'category_type_id' => 'Category Type ID',
            'mark_out_of' => 'Mark Out Of',
            'category_type_id_marks' => 'Category Type Id Marks',
            'year' => 'Year',
            'month' => 'Month',
            'term' => 'Term',
            'mark_type' => 'Mark Type',
            'status_id' => 'Status ID',
            'attendance_percentage' => 'Attendance Percentage',
            'attendance_remarks' => 'Attendance Remarks',
            'is_updated' => 'Is Updated',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
