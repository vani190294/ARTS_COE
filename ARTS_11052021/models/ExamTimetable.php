<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\db\Query;
/**
 * This is the model class for table "{{%coe_exam_timetable}}".
 *
 * @property integer $coe_exam_timetable_id
 * @property integer $subject_mapping_id
 * @property integer $exam_year
 * @property integer $exam_month
 * @property integer $exam_type
 * @property integer $exam_term
 * @property string $exam_date
 * @property integer $exam_session
 * @property string $qp_code
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property CoeSubjectsMapping $subjectMapping
 * @property CoeCategoryType $examMonth
 * @property CoeCategoryType $examType
 * @property CoeCategoryType $examTerm
 * @property CoeCategoryType $examSession
 * @property CoeHallAllocate[] $coeHallAllocates
 */
class ExamTimetable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_exam_timetable';
    }

    /**
     * @inheritdoc
     */
    public $coe_batch_id,$batch_mapping_id,$semester,$subject_code,$subject_name;
    public function rules()
    {
        return [
            [['subject_mapping_id', 'exam_month', 'exam_type', 'exam_term', 'exam_date', 'exam_session'], 'required'],
            [['subject_mapping_id', 'exam_year', 'exam_month', 'exam_type', 'exam_term', 'exam_session', 'created_by', 'updated_by'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
            [['qp_code'], 'string', 'max' => 45],
            [['semester'], 'integer'],
            [['exam_date', 'exam_session', 'qp_code', 'subject_mapping_id'], 'unique', 'targetAttribute' => ['exam_date', 'exam_session', 'qp_code', 'subject_mapping_id'], 'message' => 'The combination of '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).', '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date, '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' and Qp Code has already been taken.'],
            [['subject_mapping_id', 'exam_date', 'exam_session'], 'unique', 'targetAttribute' => ['subject_mapping_id', 'exam_date', 'exam_session'], 'message' => 'The combination of '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code , '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date and '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' has already been taken.'],
            [['subject_mapping_id', 'exam_year', 'exam_month', 'exam_type'], 'unique', 'targetAttribute' => ['subject_mapping_id', 'exam_year', 'exam_month', 'exam_type'], 'message' => 'The combination of '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code, '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Year, '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month and '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).' has already been taken.'],

            [['exam_type'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['exam_type' => 'coe_category_type_id']],
            [['exam_term'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['exam_term' => 'coe_category_type_id']],
            [['exam_session'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['exam_session' => 'coe_category_type_id']],
            [['subject_mapping_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['subject_mapping_id' => 'coe_subjects_mapping_id']],
            [['exam_month'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['exam_month' => 'coe_category_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_exam_timetable_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Timetable',
            'subject_mapping_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Mapping',
            'exam_type' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Type',
            'exam_month' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month',
            'exam_term' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Term',
            'qp_code' => 'Qp Code',
            'exam_date' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date',
            'exam_session' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Session',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'exam_year' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year",
            'coe_batch_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
            'batch_mapping_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
            'semester' => 'Semester',
            'subject_code' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code',
            'subject_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamTypeRel()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'exam_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamTermRel()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'exam_term']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamSessionRel()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'exam_session']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectMapping()
    {
        return $this->hasOne(SubjectsMapping::className(), ['coe_subjects_mapping_id' => 'subject_mapping_id']);
    }
    public function getCourseBatchMapping()
    {
        return $this->hasMany(CoeBatDegReg::className(), ['coe_bat_deg_reg_id'=>'batch_mapping_id'])->alias('coe_bat_rel')->via('subjectMapping');
    }
    public function getCoeBatch()
    {
        return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id'])->via('courseBatchMapping');
    }
    public function getCoeDegree()
    { 
         return $this->hasOne(Degree::className(), ['coe_degree_id' => 'coe_degree_id'])->via('courseBatchMapping');
    }
    public function getCoeProgramme()
    {
        return $this->hasOne(Programme::className(), ['coe_programme_id' => 'coe_programme_id'])->via('courseBatchMapping');
    }

    public function getCoeBatchName()
    {
        return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id'])->via('coeBatch');
    }
    public function getCoeDegreeName()
    { 
         return $this->hasOne(Degree::className(), ['coe_degree_id' => 'coe_degree_id'])->via('coeDegree');
    }
    public function getCoeProgrammeName()
    {
        return $this->hasOne(Programme::className(), ['coe_programme_id' => 'coe_programme_id'])->via('coeProgramme');
    }
    
    public function getWholeSemester()
    {
        return $this->hasOne(Subjects::className(), ['coe_subjects_id' => 'subject_id'])->via('subjectMapping');
        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamMonthRel()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'exam_month']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeHallAllocates()
    {
        return $this->hasMany(HallAllocate::className(), ['exam_timetable_id' => 'coe_exam_timetable_id']);
    }

    //New
    

    public function getExamTerm()
    {
        $exam = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_EXAM_TERM);
        $config_list = Categories::find()->where(['category_name' => $exam])->one();
        $c_id = $config_list['coe_category_id'];

        $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
        $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');
        return $vals;
    }

    public function getExamType()
    {
        $exam = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_EXAM_TYPE);
        $config_list = Categories::find()->where(['category_name' => $exam])->one();
        $c_id = $config_list['coe_category_id'];

        $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
        $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');

        return $vals;
    }

    public function getExamSession()
    {
        $exam = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_EXAM_SESSION);
        $config_list = Categories::find()->where(['category_name' => $exam])->one();
        $c_id = $config_list['coe_category_id'];

        $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
        $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');
        return $vals;
    }

}
