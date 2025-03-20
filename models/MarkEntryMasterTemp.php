<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_mark_entry_master_temp".
 *
 * @property integer $coe_mark_entry_master_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $CIA
 * @property integer $ESE
 * @property integer $total
 * @property string $result
 * @property double $grade_point
 * @property string $grade_name
 * @property integer $year
 * @property integer $month
 * @property integer $term
 * @property integer $mark_type
 * @property integer $status_id
 * @property string $year_of_passing
 * @property integer $attempt
 * @property string $withheld
 * @property string $withheld_remarks
 * @property string $withdraw
 * @property string $is_updated
 * @property string $fees_paid
 * @property string $result_published_date
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class MarkEntryMasterTemp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_mark_entry_master_temp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'subject_map_id', 'CIA', 'ESE', 'total', 'result', 'grade_point', 'grade_name', 'year', 'month', 'term', 'mark_type', 'result_published_date', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['student_map_id', 'subject_map_id', 'CIA', 'ESE', 'total', 'year', 'month', 'term', 'mark_type', 'status_id', 'attempt', 'created_by', 'updated_by'], 'integer'],
            [['grade_point'], 'number'],
            [['result_published_date', 'created_at', 'updated_at'], 'safe'],
            [['result'], 'string', 'max' => 50],
            [['grade_name'], 'string', 'max' => 25],
            [['year_of_passing'], 'string', 'max' => 30],
            [['withheld', 'withdraw', 'is_updated', 'fees_paid'], 'string', 'max' => 45],
            [['withheld_remarks'], 'string', 'max' => 100],
            [['student_map_id', 'subject_map_id', 'year', 'month', 'term', 'mark_type'], 'unique', 'targetAttribute' => ['student_map_id', 'subject_map_id', 'year', 'month', 'term', 'mark_type'], 'message' => 'The combination of Student Map ID, Subject Map ID, Year, Month, Term and Mark Type has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_mark_entry_master_id' => 'Coe Mark Entry Master ID',
            'student_map_id' => 'Student Map ID',
            'subject_map_id' => 'Subject Map ID',
            'CIA' => 'Cia',
            'ESE' => 'Ese',
            'total' => 'Total',
            'result' => 'Result',
            'grade_point' => 'Grade Point',
            'grade_name' => 'Grade Name',
            'year' => 'Year',
            'month' => 'Month',
            'term' => 'Term',
            'mark_type' => 'Mark Type',
            'status_id' => 'Status ID',
            'year_of_passing' => 'Year Of Passing',
            'attempt' => 'Attempt',
            'withheld' => 'Withheld',
            'withheld_remarks' => 'Withheld Remarks',
            'withdraw' => 'Withdraw',
            'is_updated' => 'Is Updated',
            'fees_paid' => 'Fees Paid',
            'result_published_date' => 'Result Published Date',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
