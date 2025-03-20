<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cur_honours_subject_list".
 *
 * @property integer $cur_hsl_id
 * @property integer $cur_hon_id
 * @property integer $batch_map_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property integer $honours_type
 * @property string $register_number
 * @property integer $vertical_id
 * @property string $vertical_name
 * @property string $subject_code
 * @property string $subject_name
 * @property integer $semester
 * @property integer $approve_status
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class HonoursSubjectList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_honours_subject_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_hon_id', 'batch_map_id', 'degree_type', 'coe_batch_id', 'coe_regulation_id', 'coe_dept_id', 'honours_type', 'register_number', 'vertical_id', 'vertical_name', 'subject_code', 'subject_name', 'semester', 'approve_status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['cur_hon_id', 'batch_map_id', 'coe_regulation_id', 'coe_dept_id', 'honours_type', 'vertical_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 10],
            [['register_number', 'vertical_name', 'subject_name'], 'string', 'max' => 255],
            [['subject_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_hsl_id' => 'Cur Hsl ID',
            'cur_hon_id' => 'Cur Hon ID',
            'batch_map_id' => 'Batch Map ID',
            'degree_type' => 'Degree Type',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_dept_id' => 'Coe Dept ID',
            'honours_type' => 'Honours Type',
            'register_number' => 'Register Number',
            'vertical_id' => 'Vertical ID',
            'vertical_name' => 'Vertical Name',
            'subject_code' => 'Subject Code',
            'subject_name' => 'Subject Name',
            'semester' => 'Semester',
            'approve_status' => 'Approve Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
