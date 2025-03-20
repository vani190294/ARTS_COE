<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_valuation_settings".
 *
 * @property integer $id
 * @property integer $current_exam_year
 * @property integer $current_exam_month
 * @property integer $updated_by
 * @property string $updated_at
 */
class ValuationSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_valuation_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['current_exam_year', 'current_exam_month', 'updated_by', 'updated_at'], 'required'],
            [['current_exam_year', 'current_exam_month', 'updated_by'], 'integer'],
            [['updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'current_exam_year' => 'Current Exam Year',
            'current_exam_month' => 'Current Exam Month',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
             'engg_graphic_subject'=>'Engg Graphic Subject'
        ];
    }
}
