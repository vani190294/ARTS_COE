<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cur_core_faculty_list".
 *
 * @property integer $cur_cfl_id
 * @property integer $cur_cf_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $section
 * @property string $subject_code
 * @property integer $faculty_id
 * @property integer $semester
 * @property integer $approve_status
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class CoreFacultyList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_core_faculty_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_cf_id', 'degree_type', 'coe_regulation_id', 'coe_dept_id', 'section', 'subject_code', 'faculty_id', 'semester', 'approve_status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['cur_cf_id', 'coe_regulation_id', 'coe_dept_id', 'faculty_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type', 'section'], 'string', 'max' => 10],
            [['subject_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_cfl_id' => 'Cur Cfl ID',
            'cur_cf_id' => 'Cur Cf ID',
            'degree_type' => 'Degree Type',
            'coe_regulation_id' => 'Coe Regulation ID',
            'coe_dept_id' => 'Coe Dept ID',
            'section' => 'Section',
            'subject_code' => 'Subject Code',
            'faculty_id' => 'Faculty ID',
            'semester' => 'Semester',
            'approve_status' => 'Approve Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
