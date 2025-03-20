<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_vanswer_packet".
 *
 * @property integer $vanswer_packet_id
 * @property integer $exam_year
 * @property integer $exam_month
 * @property string $exam_date
 * @property string $exam_session
 * @property string $subject_code
 * @property integer $vanswer_packet_no
 * @property integer $total_answer_scripts
 * @property string $valuation_date
 * @property string $valuation_session
 * @property integer $valuation_faculty_id
 * @property string $created_at
 */
class VanswerPacket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_vanswer_packet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exam_year', 'exam_month', 'subject_code','subject_pack_i', 'vanswer_packet_no', 'total_answer_scripts', 'valuation_date', 'valuation_session', 'valuation_faculty_id', 'created_at','val_faculty_all_id'], 'required'],
            [['exam_year', 'exam_month', 'vanswer_packet_no', 'total_answer_scripts', 'valuation_faculty_id','val_faculty_all_id'], 'integer'],
            [['valuation_date', 'created_at'], 'safe'],
            [['valuation_session'], 'string', 'max' => 10],
            [['subject_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vanswer_packet_id' => 'Vanswer Packet ID',
            'exam_year' => 'Exam Year',
            'exam_month' => 'Exam Month',
            'subject_code' => 'Subject Code',
            'subject_pack_i'=>'Subject Pack I',
            'vanswer_packet_no' => 'Vanswer Packet No',
            'total_answer_scripts' => 'Total Answer Scripts',
            'valuation_date' => 'Valuation Date',
            'valuation_session' => 'Valuation Session',
            'valuation_faculty_id' => 'Valuation Faculty ID',
            'created_at' => 'Created At',
        ];
    }
}
