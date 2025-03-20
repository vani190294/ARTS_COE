<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cur_front_peo_po_mapping".
 *
 * @property integer $cur_fppm_id
 * @property integer $cur_fp_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property integer $po_tick
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class FrontPeoPoMapping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_front_peo_po_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_fp_id', 'cur_fpl_id', 'degree_type', 'coe_regulation_id', 'coe_dept_id', 'po_tick'], 'required'],
            [['cur_fp_id', 'coe_regulation_id', 'coe_dept_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['po_tick', 'degree_type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_fppm_id' => 'Cur Fppm ID',
            'cur_fp_id' => 'Cur Fp ID',
            'degree_type' => 'Degree Type',
            'coe_regulation_id' => 'Coe Regulation ID',
            'coe_dept_id' => 'Coe Dept ID',
            'po_tick' => 'Po Tick',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
