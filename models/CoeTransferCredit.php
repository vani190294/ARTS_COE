<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_transfer_credit".
 *
 * @property integer $coe_tc_id
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
 */
class CoeTransferCredit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_transfer_credit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'removed_sub_map_id', 'waiver_reason', 'total_studied', 'subject_codes', 'year', 'month', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['student_map_id', 'removed_sub_map_id', 'total_studied', 'year', 'month', 'created_by', 'updated_by','subject_map_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['waiver_reason'], 'string', 'max' => 1000],
            [['student_map_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_tc_id' => 'Coe Tc ID',
            'student_map_id' => 'Student Map ID',
            'removed_sub_map_id' => 'Removed Sub Map ID',
            'waiver_reason' => 'Waiver Reason',
            'total_studied' => 'Total Studied',
            'subject_map_id' => 'Subject Codes',
            'year' => 'Year',
            'month' => 'Month',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
