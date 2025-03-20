<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\ExamTimetable;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "coe_dummy_number".
 *
 * @property integer $coe_dummy_number_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $dummy_number
 * @property integer $year
 * @property integer $month
 * @property string $created_at
 * @property integer $created_by
 * @property string $examiner_name
 * @property string $chief_examiner_name
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property CoeStudentMapping $studentMap
 * @property CoeSubjectsMapping $subjectMap
 * @property User $createdBy
 * @property User $updatedBy
 */
class DummyNumbers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $start_number,$end_number,$last_dummy_number,$limit;
    public static function tableName()
    {
        return '{{%coe_dummy_number}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'subject_map_id', 'dummy_number', 'year', 'month', 'start_number','end_number','examiner_name', 'chief_examiner_name'], 'required'],
            [[ 'subject_map_id', 'year', 'month', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['student_map_id', 'subject_map_id', 'dummy_number', 'year', 'month'], 'unique', 'targetAttribute' => [ 'dummy_number', 'year', 'month'], 'message' => 'The combination Dummy Number, Year and Month has already been taken.'],
            [['examiner_name', 'chief_examiner_name'], 'string', 'max' => 255],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['subject_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['subject_map_id' => 'coe_subjects_mapping_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }


    public function is8NumbersOnly($attribute)
    {
        if (!preg_match('/^[0-9]{8}$/', $this->$attribute)) {
            $this->addError($attribute, 'must contain exactly 8 digits.');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_dummy_number_id' =>  ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' ID',
            'student_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Number',
            'subject_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name',
            'dummy_number' =>  ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY),
            'year' => 'Year',
            'month' => 'Month',
            
            'start_number'=>'Starting Number',
            'end_number'=>'Ending Number',
            'last_dummy_number'=>'Last '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY),
            'examiner_name' => 'Examiner Name',
            'chief_examiner_name' => 'Chief Examiner Name',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentMap()
    {
        return $this->hasOne(StudentMapping::className(), ['coe_student_mapping_id' => 'student_map_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentDetails()
    {
        return $this->hasOne(Student::className(), [ 'coe_student_id' => 'student_rel_id'])->via('studentMap');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonthDetails()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'month']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectMap()
    {
        return $this->hasOne(SubjectsMapping::className(), ['coe_subjects_mapping_id' => 'subject_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectDetails()
    {
        return $this->hasOne(Subjects::className(), ['coe_subjects_id' => 'subject_id' ])->via('subjectMap');;
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
