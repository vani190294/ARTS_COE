<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "coe_add_hall_allocate".
 *
 * @property integer $coe_add_hall_allocate_id
 * @property integer $hall_master_id
 * @property integer $exam_timetable_id
 * @property integer $year
 * @property string $month
 * @property string $register_number
 * @property integer $row
 * @property integer $row_column
 * @property integer $seat_no
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class CoeAddHallAllocate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
     const TYPE_SUBJECTWISE = 'Subject Wise';
    const TYPE_NONSUBJECTWISE = 'Non-Subject Wise';
    const TYPE_STRAIGHT= 'Straight Arrangement';
    const TYPE_CROSS='Cross Arrangement';

    public $arrangement_type,$student_count,$subject_code,$seat_arrangement;
    public static function tableName()
    {
        return 'coe_add_hall_allocate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hall_master_id', 'exam_timetable_id', 'year', 'month', 'register_number', 'row', 'row_column', 'seat_no', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['hall_master_id', 'exam_timetable_id', 'year', 'row', 'row_column', 'seat_no', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['month', 'register_number'], 'string', 'max' => 45],
            [['register_number', 'exam_timetable_id'], 'unique', 'targetAttribute' => ['register_number', 'exam_timetable_id'], 'message' => 'The combination of Exam Timetable ID and Register Number has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_add_hall_allocate_id' => 'Coe Hall Allocate ID',
            'hall_master_id' => 'Hall Master ID',
            'student_count' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Count",
            'exam_timetable_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Timetabe Id',
            'year' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Year',
            'month' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month',            
            'register_number' => 'Register Number',
            'row' => 'Row',
            'subject_code' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code",
            'row_column' => 'row_column',
            'seat_no' => 'Seat No',           
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
 public function getHallarrangement(){
            return[
               Yii::t('app',self::TYPE_SUBJECTWISE) => Yii::t('app',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Wise'),
               Yii::t('app',self::TYPE_NONSUBJECTWISE) => Yii::t('app','Non '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Wise'),
            ];
    }

    public function getArrangement(){
            return[
               Yii::t('app',self::TYPE_STRAIGHT) => Yii::t('app','Straight Arrangement'),
               Yii::t('app',self::TYPE_CROSS) => Yii::t('app','Cross Arrangement'),
            ];
    }

    public function getMonth(){
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
    }

    
}
