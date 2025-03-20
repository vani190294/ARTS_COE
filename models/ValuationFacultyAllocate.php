<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "coe_valuation_faculty_allocate".
 *
 * @property integer $val_faculty_all_id
 * @property integer $coe_val_faculty_id
 * @property integer $exam_year
 * @property integer $exam_month
 * @property string $exam_fromdate
 * @property string $exam_todate
 * @property string $exam_session
 * @property string $subject_code
 * @property string $subject_pack_i
 * @property integer $total_answer_scripts
 * @property string $created_at
 */
class ValuationFacultyAllocate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_valuation_faculty_allocate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['board','coe_val_faculty_id','coe_scrutiny_id', 'exam_year', 'exam_month', 'exam_fromdate', 'exam_todate', 'exam_session', 'subject_code', 'subject_pack_i', 'total_answer_scripts', 'created_at','valuation_date','valuation_session','scrutiny_session','scrutiny_date','subject_mapping_id'], 'required'],
            [['exam_year', 'exam_month', 'total_answer_scripts','coe_scrutiny_id','scrutiny_status'], 'integer'],
            [['exam_fromdate', 'exam_todate', 'created_at','valuation_date','scrutiny_date','valuation_status'], 'safe'],
            [['board','valuation_session','scrutiny_session'], 'string'],
            [['exam_session'], 'string', 'max' => 10],
            [['subject_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'board'=>'Board',
            'val_faculty_all_id' => 'Valuation Faculty Allocate',
            'coe_val_faculty_id' => 'Valuation Faculty',
            'exam_year' => 'Exam Year',
            'exam_month' => 'Exam Month',
            'exam_fromdate' => 'Exam From Date',
            'exam_todate' => 'Exam To Date',
            'exam_session' => 'Exam Session',
            'subject_code' => 'Subject Code',
            'subject_pack_i' => 'Packet Number',
            'total_answer_scripts' => 'Total Answer Scripts',
            'created_at' => 'Created At',
            'valuation_date'=>'Valuation Date',
            'valuation_session'=>'Valuation Session',
            'coe_scrutiny_id'=>'Scrutiny Staff',
            'scrutiny_status'=>'Scrutiny Status',
            'scrutiny_session'=>'Scrutiny Session',
            'scrutiny_date'=>'Scrutiny Date',
            'valuation_status'=>'Valuation Status'
        ];
    }

      public function getScrutiny(){
       
        $scrutiny = Yii::$app->db->createCommand("select coe_scrutiny_id,name as scrutiny_name from coe_valuation_scrutiny")->queryAll();
        return  $scrutinyall = ArrayHelper::map($scrutiny,'coe_scrutiny_id','scrutiny_name');
    }

     public function getBoard(){
        $board = Yii::$app->db->createCommand("select distinct(category_type),coe_category_type_id from coe_category_type where category_id=21")->queryAll();
        return  $all_board = ArrayHelper::map($board,'coe_category_type_id','category_type');
    }
}
