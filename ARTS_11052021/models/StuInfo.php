<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%stu_info}}".
 *
 * @property integer $stu_map_id
 * @property string $reg_num
 * @property integer $stu_id
 * @property integer $batch_map_id
 */
class StuInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stu_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stu_map_id', 'stu_id', 'batch_map_id'], 'integer'],
            [['reg_num', 'batch_map_id'], 'required'],
            [['reg_num'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stu_map_id' => 'Stu Map ID',
            'reg_num' => 'Reg Num',
            'stu_id' => 'Stu ID',
            'batch_map_id' => 'Batch Map ID',
        ];
    }
}
