<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cur_frontpage_list".
 *
 * @property integer $cur_fpl_id
 * @property integer $cur_fp_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $vision
 * @property string $mission
 * @property string $peo
 * @property string $pso
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class CDCFrontpageList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_frontpage_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_fp_id', 'degree_type', 'coe_regulation_id', 'coe_dept_id', 'vision'], 'required'],
            [['cur_fp_id', 'coe_regulation_id', 'coe_dept_id', 'created_by', 'updated_by'], 'integer'],
            [['vision', 'mission', 'peo', 'pso'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_fpl_id' => 'Cur Fpl ID',
            'cur_fp_id' => 'Cur Fp ID',
            'degree_type' => 'Degree Type',
            'coe_regulation_id' => 'Coe Regulation ID',
            'coe_dept_id' => 'Coe Dept ID',
            'vision' => 'Vision',
            'mission' => 'Mission',
            'peo' => 'Peo',
            'pso' => 'Pso',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
