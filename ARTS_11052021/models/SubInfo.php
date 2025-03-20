<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%sub_info}}".
 *
 * @property integer $sub_map_id
 * @property integer $sub_id
 * @property string $sub_code
 * @property integer $sub_batch_id
 */
class SubInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sub_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sub_map_id', 'sub_id', 'sub_batch_id'], 'integer'],
            [['sub_code', 'sub_batch_id'], 'required'],
            [['sub_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sub_map_id' => 'Sub Map ID',
            'sub_id' => 'Sub ID',
            'sub_code' => 'Sub Code',
            'sub_batch_id' => 'Sub Batch ID',
        ];
    }
}
