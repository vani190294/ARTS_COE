<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "{{%coe_mandatory_subcat_subjects}}".
 *
 * @property integer $coe_mandatory_subcat_subjects_id
 * @property integer $man_subject_id
 * @property integer $coe_batch_id
 * @property integer $batch_map_id
 * @property string $sub_cat_code
 * @property string $sub_cat_name
 * @property string $is_additional
 * @property integer $course_type_id
 * @property integer $paper_type_id
 * @property integer $subject_type_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $paper_no
 * @property integer $credit_points
 *
 * @property CoeMandatoryStuMarks[] $coeMandatoryStuMarks
 * @property CoeBatch $coeBatch
 * @property CoeMandatorySubjects $manSubject
 * @property CoeBatDegReg $batchMap
 * @property CoeCategoryType $courseType
 * @property CoeCategoryType $paperType
 * @property CoeCategoryType $subjectType
 */
class MandatorySubcatSubjects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_mandatory_subcat_subjects}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['man_subject_id', 'coe_batch_id', 'batch_map_id', 'sub_cat_code', 'sub_cat_name', 'course_type_id', 'paper_type_id', 'subject_type_id','credit_points',  'paper_no'], 'required'],
            [['man_subject_id', 'coe_batch_id', 'batch_map_id', 'course_type_id', 'paper_type_id', 'subject_type_id', 'created_by', 'updated_by', 'credit_points','paper_no'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['sub_cat_code'], 'string', 'max' => 45],
            [['sub_cat_name'], 'string', 'max' => 255],
            [['coe_batch_id', 'batch_map_id', 'man_subject_id', 'sub_cat_code'], 'unique', 'targetAttribute' => ['coe_batch_id', 'man_subject_id', 'sub_cat_code'], 'message' => 'The combination of '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE ,'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).' , and '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' Category Code has already been taken.'
            ],
            [['sub_cat_name', 'batch_map_id', 'coe_batch_id'], 'unique', 'targetAttribute' => ['sub_cat_name', 'batch_map_id', 'coe_batch_id'], 'message' => 'The combination of '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).' , '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).'  and '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' Category Name has already been taken.'],
            [['coe_batch_id','sub_cat_name'], 'unique','targetAttribute' => ['coe_batch_id','sub_cat_name'],'message' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' SUB CATEGORY CODE NAME ALREADY EXISTS!!'],
            [['coe_batch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Batch::className(), 'targetAttribute' => ['coe_batch_id' => 'coe_batch_id']],
            [['man_subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => MandatorySubjects::className(), 'targetAttribute' => ['man_subject_id' => 'coe_mandatory_subjects_id']],
            [['batch_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => BatDegReg::className(), 'targetAttribute' => ['batch_map_id' => 'coe_bat_deg_reg_id']],
            [['course_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['course_type_id' => 'coe_category_type_id']],
            [['paper_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['paper_type_id' => 'coe_category_type_id']],
            [['subject_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['subject_type_id' => 'coe_category_type_id']],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_mandatory_subcat_subjects_id' => 'Coe Mandatory Subcat Subjects',
            'man_subject_id' => 'MANDATORY '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)),
            'batch_map_id' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)),
            'coe_batch_id' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)),
            'sub_cat_code' => 'CATEGORY CODE',
            'sub_cat_name' => 'CATEGORY NAME',
            'is_additional' => 'Is Additional',
            'paper_no' =>'Paper No',
            'credit_points' => 'Credit Points',
            'course_type_id' => strtoupper('Course Type'),
            'paper_type_id' => strtoupper('Paper Type'),
            'subject_type_id' => strtoupper('Subject Type'),
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeMandatoryStuMarks()
    {
        return $this->hasMany(CoeMandatoryStuMarks::className(), ['subject_map_id' => 'coe_mandatory_subcat_subjects_id']);
    }



    public function getCourseBatchMapping()
    {
        return $this->hasMany(BatDegReg::className(), ['coe_bat_deg_reg_id'=>'batch_map_id'])->alias('coe_bat_rel');
    }

    public function getBatch()
    {

        return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id'])->via('courseBatchMapping')->alias('getBatchId');
    }
    public function getCoeDegree()
    { 
         return $this->hasOne(Degree::className(), ['coe_degree_id' => 'coe_degree_id'])->via('courseBatchMapping')->alias('coeDegreeIOd');
    }
    public function getCoeProgramme()
    {
        return $this->hasOne(Programme::className(), ['coe_programme_id' => 'coe_programme_id'])->via('courseBatchMapping')->alias('coeProgrammeId');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'course_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaperType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'paper_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'subject_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManSubject()
    {
        
        return $this->hasOne(MandatorySubjects::className(), ['coe_mandatory_subjects_id' => 'man_subject_id']);
    }
}
