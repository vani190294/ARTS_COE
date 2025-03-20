<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_activity_marks".
 *
 * @property integer $id
 * @property integer $batch
 * @property string $programme
 * @property integer $register_number
 * @property integer $section
 * @property string $subject_code
 * @property string $duration
 */
class CoeActivityMarks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_activity_marks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch', 'programme', 'register_number', 'subject_code', 'duration'], 'required'],
            [['batch', 'register_number', ], 'integer'],
            [['programme'], 'string', 'max' => 50],
            [['subject_code', 'duration'], 'string', 'max' => 55],
             [[ 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'batch' => 'Batch',
            'programme' => 'Programme',
            'register_number' => 'Register Number',
            //'section' => 'Section',
            'subject_code' => 'Subject Code',
            'duration' => 'Duration',
             'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
