<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "coe_answer_packet".
 *
 * @property integer $answer_packet_id
 * @property integer $coe_batch_id
 * @property integer $exam_year
 * @property integer $exam_month
 * @property string $exam_date
 * @property string $exam_session
 * @property string $subject_code
 * @property string $subject_name
 * @property string $answer_packet_serial
 * @property integer $answer_packet_number
 * @property integer $total_answer_scripts
 * @property integer $print_script_count
 * @property string $created_at
 */
class AnswerPacket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_answer_packet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_batch_id', 'exam_year', 'exam_month', 'exam_date','exam_type' ,'exam_session', 'subject_code', 'subject_name', 'answer_packet_serial', 'answer_packet_number', 'total_answer_scripts', 'print_script_count', 'created_at'], 'required'],
            [['coe_batch_id', 'exam_year', 'exam_month', 'answer_packet_number', 'total_answer_scripts', 'print_script_count'], 'integer'],
            [['exam_date', 'created_at'], 'safe'],
            [['subject_name'], 'string'],
            [['exam_session'], 'string', 'max' => 10],
            [['subject_code'], 'string', 'max' => 100],
            [['answer_packet_serial'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'answer_packet_id' => 'Answer Packet ID',
            'coe_batch_id' => 'Batch',
            'exam_year' => 'Year',
            'exam_month' => 'Month',
            'exam_date' => 'Exam Date',
            'exam_session' => 'Exam Session',
            'exam_type' => 'Exam Type',
            'subject_code' => 'Subject Code',
            'subject_name' => 'Subject Name',
            'answer_packet_serial' => 'Answer Packet Serial',
            'answer_packet_number' => 'Answer Packet Number',
            'total_answer_scripts' => 'Total Answer Scripts',
            'print_script_count' => 'Print Script Count',
            'created_at' => 'Created At',
        ];
    }

    public function getMonth(){
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
    }
}
