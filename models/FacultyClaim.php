<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_val_faculty_claim".
 *
 * @property integer $remun_id
 * @property integer $claim_type
 * @property integer $val_faculty_id
 * @property integer $exam_year
 * @property integer $exam_month
 * @property string $claim_date
 * @property integer $total_script
 * @property double $total_script_amount
 * @property double $tada_amt
 * @property double $total_claim
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class FacultyClaim extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_val_faculty_claim';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['claim_type', 'exam_year', 'exam_month', 'total_script', 'total_script_amount', 'tada_amt', 'total_claim', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['claim_type', 'val_faculty_id', 'exam_year', 'exam_month', 'total_script', 'created_by', 'updated_by'], 'integer'],
            [['claim_date', 'created_at', 'updated_at'], 'safe'],
            [['total_script_amount', 'tada_amt', 'total_claim'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'remun_id' => 'Remun ID',
            'claim_type' => 'Claim Type',
            'val_faculty_id' => 'Faculty',
            'exam_year' => 'Exam Year',
            'exam_month' => 'Exam Month',
            'claim_date' => 'Claim Date',
            'total_script' => 'Total Script',
            'total_script_amount' => 'Total Script Amount',
            'tada_amt' => 'Tada Amt',
            'total_claim' => 'Total Claim',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
