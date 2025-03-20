<?php

namespace app\models;

use Yii;
use app\models\User;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "coe_subjects".
 *
 * @property integer $coe_subjects_id
 * @property string $subject_code
 * @property string $subject_name

 * @property integer $subject_fee
 * @property integer $CIA_min
 * @property integer $CIA_max
 * @property integer $ESE_min
 * @property integer $ESE_max
 * @property integer $total_minimum_pass
 * @property integer $credit_points
 * @property integer $part_no
 * @property integer $end_semester_exam_value_mark
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property CoeNominal[] $coeNominals
 * @property User $createdBy
 * @property User $updatedBy
 * @property CoeSubjectsMapping $coeSubjectsMapping
 */
class Subjects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_subjects';
    }

    /**
     * @inheritdoc
     */
    public $coe_batch_id,$batch_mapping_id,$mig_batch_id;
    public function rules()
    {
        return [
            [['subject_code', 'subject_name', 'CIA_min', 'CIA_max', 'ESE_min', 'ESE_max', 'total_minimum_pass', 'credit_points', 'end_semester_exam_value_mark', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['CIA_min', 'CIA_max', 'ESE_min', 'ESE_max', 'total_minimum_pass',  'end_semester_exam_value_mark', 'created_by', 'updated_by','subject_fee','part_no'], 'integer'],
           [['credit_points'], 'number'],
                
            [['created_at', 'updated_at'], 'safe'],
            [['subject_code'], 'string', 'max' => 50],
            [['subject_name'], 'string', 'max' => 255],            
            [['CIA_min'],'compare','compareValue'=>'0','operator'=>'>=','message'=>'CIA Minimum should be zero or greater'],
            //[['CIA_min', 'CIA_max', 'ESE_min', 'ESE_max', 'total_minimum_pass', 'end_semester_exam_value_mark'], 'maxlength'=>3],
            // [['total_minimum_pass','credit_points','end_semester_exam_value_mark'],'compare','compareValue'=>'0','operator'=>'>','message'=>'Mark must be greater than 0'],
            //[['subject_code'], 'unique', 'targetAttribute' => ['subject_code'], 'message' => 'The '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code has already been taken.'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_subjects_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' ID',
            'subject_code' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code',
            'subject_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name',
            'CIA_min' => 'CIA Min',
            'CIA_max' => 'CIA Max',
            'ESE_min' => 'ESE Min',
            'ESE_max' => 'ESE Max',
            'part_no' => 'Part No',
            'total_minimum_pass' => 'Total Minimum Pass',
            'credit_points' => 'Credit Points',
            'end_semester_exam_value_mark' => 'End Semester Exam Value Mark',
            'subject_fee'   =>  ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Fee',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
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
    public function getCoeSubjectsMapping()
    {
        return $this->hasOne(SubjectsMapping::className(), ['subject_id' => 'coe_subjects_id']);
    }

    // Custom Functions for Filters
    // 
    public function getCourseBatchMapping()
    {
        return $this->hasMany(CoeBatDegReg::className(), ['coe_bat_deg_reg_id'=>'batch_mapping_id'])->alias('coe_bat_rel')->via('coeSubjectsMapping');
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

}
