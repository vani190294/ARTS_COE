<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "sub".
 *
 * @property integer $coe_sub_mapping_id
 * @property integer $batch_mapping_id
 * @property integer $val_subject_id
 * @property integer $semester
 * @property integer $paper_type_id
 * @property integer $subject_type_id
 * @property integer $course_type_id
 * @property string $migration_status
 * @property integer $paper_no
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class Sub extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sub';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch_mapping_id', 'val_subject_id', 'semester', 'paper_type_id', 'subject_type_id', 'course_type_id', 'created_by', 'updated_by'], 'required'],
            [['batch_mapping_id', 'val_subject_id', 'semester', 'paper_type_id', 'subject_type_id', 'course_type_id', 'paper_no', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['migration_status'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
           
            
           'coe_sub_mapping_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code",
            'batch_mapping_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
           'val_subject_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code",
            'paper_type_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PAPER_TYPE),
            'subject_type_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT_TYPE),
            'course_type_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
            'semester'  =>  'Semester',
            'paper_no' => 'Paper Number',
            'subject_fee' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Fee',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',


        ];
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaperTypes()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'paper_type_id'])->alias('paper_type');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectTypes()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'subject_type_id'])->alias('subject_type');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseTypes()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'course_type_id'])->alias('course_type');
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
    public function getCoeSubjects()
    {
        return $this->hasOne(CoeValueSubjects::className(), ['coe_val_sub_id' => 'val_subject_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchMapping()
    {
        return $this->hasOne(CoeBatDegReg::className(), ['coe_bat_deg_reg_id' => 'batch_mapping_id']);
    }

    public function getCoeSubjectsMapping()
    {
        return $this->hasOne(Sub::className(), ['val_subject_id' => 'val_subject_id'])->alias('subject_map');
    }

    // Custom Functions for Filters
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
    public function getCoePaperType()
    {
        return $this->hasOne(Categorytype::className(), ['paper_type_id'=>'coe_category_type_id']);
    }
    public function getCoeSubjectType()
    {
        return $this->hasOne(Categorytype::className(), ['subject_type_id'=>'coe_category_type_id']);
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

    public function getSubjectType()
    {
      $sub = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_SUBJECT_TYPE);
      $config_list = Categories::find()->where(['category_name' => $sub ])->one();
      $c_id = $config_list['coe_category_id'];

      $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
      $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');
      return $vals;
    }

    public function getProgrammeType()
    {
        $sub = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_PROGRAMME_TYPE);
        $config_list = Categories::find()->where(['category_name' => $sub])->one();
        $c_id = $config_list['coe_category_id'];

        $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
        $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');
        return $vals;
    }

    public function getPaperType()
    {
        $sub = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_PAPER_TYPE);
        $config_list = Categories::find()->where(['category_name' => $sub])->one();
        $c_id = $config_list['coe_category_id'];

        $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
        $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');
        return $vals;
    }


    public function getSubjectMapId()
    {
        $dataTmp = Yii::$app->db->createCommand("SELECT coe_sub_mapping_id,subject_code FROM coe_value_subjects as A JOIN sub as B ON B.val_subject_id=A.coe_val_sub_id GROUP BY A.coe_val_sub_id")->queryAll();
        $result = yii\helpers\ArrayHelper::map($dataTmp, 'coe_sub_mapping_id', 'subject_code');
        return $result;
    }
    public function getSubjectMappingId($batchMapping,$sem=null)
    {
         $add_condition = $sem!=''?" and semester='".$sem."' ":'';
        $dataTmp = Yii::$app->db->createCommand("SELECT coe_sub_mapping_id,subject_code FROM coe_value_subjects as A JOIN sub as B ON B.val_subject_id=A.coe_val_sub_id where batch_mapping_id='".$batchMapping."' ".$add_condition." GROUP BY coe_val_sub_id,subject_code")->queryAll();
        $result = yii\helpers\ArrayHelper::map($dataTmp, 'coe_sub_mapping_id', 'subject_code');
            return $result;
       
        
    }
    
}
