<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cur_department".
 *
 * @property integer $coe_dept_id
 * @property string $dept_code
 * @property string $dept_name
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dept_code', 'dept_name','prefix_name','no_of_pso'], 'required'],
            [['created_by', 'updated_by','no_of_pso'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['dept_code'], 'string', 'max' => 100],
            [['dept_name'], 'string', 'max' => 255],
            [['prefix_name'], 'string', 'max' => 100],
            [['dept_code'], 'unique'],
            [['dept_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_dept_id' => 'Coe Dept ID',
            'dept_code' => 'Department Short Name',
            'dept_name' => 'Department Name',
            'prefix_name' => 'Subject Prefix',
            'no_of_pso' => 'No. of PSO',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
