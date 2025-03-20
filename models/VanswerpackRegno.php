<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_vanswerpack_regno".
 *
 * @property integer $vanswerpacket_reg_id
 * @property integer $vanswer_packet_no
 * @property string $stu_reg_no
 * @property integer $exam_year
 * @property integer $exam_month
 * @property string $exam_date
 * @property string $exam_session
 * @property string $subject_code
 * @property string $valuation_date
 * @property string $valuation_session
 * @property integer $valuation_faculty_id
 * @property string $created_at
 */
class VanswerpackRegno extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_vanswerpack_regno';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vanswer_packet_no', 'stu_reg_no', 'exam_year', 'exam_month', 'subject_code','subject_pack_i', 'valuation_date', 'valuation_session', 'valuation_faculty_id', 'created_at'], 'required'],
            [['vanswer_packet_no', 'exam_year', 'exam_month', 'valuation_faculty_id'], 'integer'],
            [[ 'valuation_date', 'created_at'], 'safe'],
            [['stu_reg_no'], 'string', 'max' => 50],
            [[ 'valuation_session'], 'string', 'max' => 10],
            [['subject_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vanswerpacket_reg_id' => 'Vanswerpacket Reg ID',
            'vanswer_packet_no' => 'Vanswer Packet No',
            'stu_reg_no' => 'Stu Reg No',
            'exam_year' => 'Exam Year',
            'exam_month' => 'Exam Month',
            'subject_code' => 'Subject Code',
            'subject_pack_i'=>'Subject Pack I',
            'valuation_date' => 'Valuation Date',
            'valuation_session' => 'Valuation Session',
            'valuation_faculty_id' => 'Valuation Faculty ID',
            'created_at' => 'Created At',
        ];
    }
}
