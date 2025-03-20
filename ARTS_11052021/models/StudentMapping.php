<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/**
 * This is the model class for table "{{%coe_student_mapping}}".
 *
 * @property integer $coe_student_mapping_id
 * @property integer $student_rel_id
 * @property integer $course_batch_mapping_id
 * @property integer $admission_category_type_id
 * @property string $section_name
 * @property integer $status_category_type_id
 * @property string $previous_reg_number
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property CoeAbsentEntry[] $coeAbsentEntries
 * @property CoeAdditionalCredits[] $coeAdditionalCredits
 * @property CoeDummyNumber[] $coeDummyNumbers
 * @property CoeMarkEntry[] $coeMarkEntries
 * @property CoeMarkEntryMaster[] $coeMarkEntryMasters
 * @property CoeRevaluation[] $coeRevaluations
 * @property CoeStudentCategoryDetails[] $coeStudentCategoryDetails
 * @property User $createdBy
 * @property User $updatedBy
 * @property CoeStudent $studentRel
 * @property CoeBatDegReg $courseBatchMapping
 * @property CoeCategoryType $statusCategoryType
 * @property CoeCategoryType $admissionCategoryType
 */
class StudentMapping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $programme_name;
    public static function tableName()
    {
        return '{{%coe_student_mapping}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_rel_id', 'course_batch_mapping_id',  'section_name', 'status_category_type_id', 'created_at', 'updated_at', 'created_by', 'updated_by','admission_category_type_id'], 'required'],
            [['student_rel_id', 'course_batch_mapping_id', 'admission_category_type_id', 'status_category_type_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at','programme_name'], 'safe'],
            [['section_name','previous_reg_number'], 'string', 'max' => 45],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['student_rel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::className(), 'targetAttribute' => ['student_rel_id' => 'coe_student_id']],
            [['course_batch_mapping_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoeBatDegReg::className(), 'targetAttribute' => ['course_batch_mapping_id' => 'coe_bat_deg_reg_id']],
            // [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => StuAddress::className(), 'targetAttribute' => ['address_id' => 'coe_stu_address_id']],
            [['status_category_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['status_category_type_id' => 'coe_category_type_id']],
            [['admission_category_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['admission_category_type_id' => 'coe_category_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_student_mapping_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).'Mapping ID',
            'student_rel_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Rel ID',
            'course_batch_mapping_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' Mapping',
            
            'section_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION).' Name',
            'status_category_type_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT_CATEGORY),
            'admission_category_type_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ADMISSION_CATEGORY),
            'degree_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." Name",
            'programme_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." Name",
            'batch_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)." Name",
            'previous_reg_number' => 'Previous Reg Number',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
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
    public function getStudentRel()
    {
        return $this->hasOne(Student::className(), ['coe_student_id' => 'student_rel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseBatchMapping()
    {
        return $this->hasOne(CoeBatDegReg::className(), ['coe_bat_deg_reg_id' => 'course_batch_mapping_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmissionCategoryType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'admission_category_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusCategoryType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'status_category_type_id']);
    }

    public function getStudentId()
    {
        $dataTmp = Yii::$app->db->createCommand("SELECT coe_student_mapping_id,register_number FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id GROUP BY A.register_number")->queryAll();
        $result = yii\helpers\ArrayHelper::map($dataTmp, 'coe_student_mapping_id', 'register_number');
        return $result;
    }
}
