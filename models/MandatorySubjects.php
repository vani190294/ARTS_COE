<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "{{%coe_mandatory_subjects}}".
 *
 * @property integer $coe_mandatory_subjects_id
 * @property integer $man_batch_id
 * @property integer $batch_mapping_id
 * @property string $subject_code
 * @property string $subject_name
 * @property integer $semester
 * @property integer $CIA_min
 * @property integer $CIA_max
 * @property integer $ESE_min
 * @property integer $ESE_max
 * @property integer $total_minimum_pass
 * @property integer $end_semester_exam_value_mark
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property MandatorySubcatSubjects[] $coeMandatorySubcatSubjects
 * @property User $createdBy
 * @property User $updatedBy
 */
class MandatorySubjects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_mandatory_subjects}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['man_batch_id','batch_mapping_id','semester','subject_code', 'subject_name', 'CIA_min', 'CIA_max', 'ESE_min', 'ESE_max', 'total_minimum_pass', 'end_semester_exam_value_mark'], 'required'],
            [['man_batch_id','batch_mapping_id', 'semester','CIA_min', 'CIA_max', 'ESE_min', 'ESE_max', 'total_minimum_pass',  'end_semester_exam_value_mark', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['subject_code', 'subject_name'], 'string', 'max' => 255],

            [['man_batch_id', 'batch_mapping_id', 'subject_code'], 'unique', 'targetAttribute' => ['man_batch_id', 'batch_mapping_id', 'subject_code'], 'message' => 'The combination of Man '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).',  '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' and '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code has already been taken.'],

            [['man_batch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Batch::className(), 'targetAttribute' => ['man_batch_id' => 'coe_batch_id']],
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
            'coe_mandatory_subjects_id' => 'Coe Mandatory Subjects ID', 
            'batch_mapping_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),      
            'semester' => 'Semester',     
            'man_batch_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
            'subject_code' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code',
            'subject_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name',
            'CIA_min' => 'Cia Min',
            'CIA_max' => 'Cia Max',
            'ESE_min' => 'Ese Min',
            'ESE_max' => 'Ese Max',
            'total_minimum_pass' => 'Total Minimum Pass',            
            'end_semester_exam_value_mark' => 'End Semester Exam Value Mark',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMandatorySubcatSubjects()
    {
        return $this->hasMany(MandatorySubcatSubjects::className(), ['man_subject_id' => 'coe_mandatory_subjects_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManBatch()
    {
        return $this->hasOne(Batch::className(), ['coe_batch_id' => 'man_batch_id']);
    }
    public function getAllSubjects()
    {
        $data =MandatorySubjects::find()->select(['coe_mandatory_subjects_id','subject_code'])->distinct()->all();
        $vals = ArrayHelper::map($data,'coe_mandatory_subjects_id','subject_code');
        return $vals;
    }
   // 
    public function getCourseBatchMapping()
    {
        return $this->hasMany(CoeBatDegReg::className(), ['coe_bat_deg_reg_id'=>'batch_mapping_id'])->alias('coe_bat_rel');
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
}
