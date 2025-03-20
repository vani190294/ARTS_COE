<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cur_service_count_details".
 *
 * @property integer $cur_scd_id
 * @property integer $cur_sc_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property string $coe_dept_id
 * @property integer $to_dept_id
 * @property string $service_type
 * @property string $service_count
 * @property integer $total_count
 * @property string $oec_count
 * @property string $eec_count
 * @property integer $approve_status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class ServiceCountDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_service_count_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_sc_id', 'degree_type', 'coe_regulation_id', 'coe_dept_id', 'to_dept_id', 'service_type', 'service_count', 'total_count', 'oec_count', 'eec_count', 'approve_status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['cur_sc_id', 'coe_regulation_id', 'to_dept_id', 'total_count', 'approve_status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['degree_type'], 'string', 'max' => 10],
            [['coe_dept_id'], 'string', 'max' => 11],
            [['service_type', 'service_count', 'oec_count', 'eec_count'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_scd_id' => 'Cur Scd ID',
            'cur_sc_id' => 'Cur Sc ID',
            'degree_type' => 'Degree Type',
            'coe_regulation_id' => 'Coe Regulation ID',
            'coe_dept_id' => 'Coe Dept ID',
            'to_dept_id' => 'To Dept ID',
            'service_type' => 'Service Type',
            'service_count' => 'Service Count',
            'total_count' => 'Total Count',
            'oec_count' => 'Oec Count',
            'eec_count' => 'Eec Count',
            'approve_status' => 'Approve Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
