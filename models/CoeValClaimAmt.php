<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_val_claim_amt".
 *
 * @property integer $claim_id
 * @property double $ug_amt
 * @property double $pg_amt
 * @property double $ta_amt_half_day
 * @property double $ta_amt_full_day
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class CoeValClaimAmt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_val_claim_amt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exam_type'], 'required'],
            [['ug_amt', 'pg_amt', 'ta_amt_half_day', 'ta_amt_full_day', 'created_by', 'updated_by'], 'required'],
            [['ug_amt', 'pg_amt', 'ta_amt_half_day', 'ta_amt_full_day'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'exam_type'=> 'Exam Type',
            'claim_id' => 'Claim ID',
            'ug_amt' => 'UG Amount',
            'pg_amt' => 'PG Amount',
            'ta_amt_half_day' => 'TA Amount(Half Day)',
            'ta_amt_full_day' => 'TA Amount(Full Day)',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
