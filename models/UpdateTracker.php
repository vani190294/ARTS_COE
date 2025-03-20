<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coe_update_tracker}}".
 *
 * @property integer $coe_update_tracker_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $exam_year
 * @property integer $exam_month
 * @property string $updated_ip_address
 * @property string $updated_link_from
 * @property string $data_updated
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property User $updatedBy
 */
class UpdateTracker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_update_tracker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'subject_map_id', 'exam_year', 'exam_month', 'updated_by'], 'integer'],
            [['data_updated'], 'string'],
            [['updated_at'], 'safe'],
            [['updated_by'], 'required'],
            [['updated_ip_address'], 'string', 'max' => 255],
            [['updated_link_from'], 'string', 'max' => 1000],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_update_tracker_id' => 'Coe Update Tracker ID',
            'student_map_id' => 'Student Map ID',
            'subject_map_id' => 'Subject Map ID',
            'exam_year' => 'Exam Year',
            'exam_month' => 'Exam Month',
            'updated_ip_address' => 'Updated Ip Address',
            'updated_link_from' => 'Updated Link From',
            'data_updated' => 'Data Updated',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
