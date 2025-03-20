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
 * This is the model class for table "{{%coe_additional_credits}}".
 *
 * @property integer $coe_additional_credits_id
 * @property integer $exam_year
 * @property integer $exam_month
 * @property integer $student_map_id
 * @property string $subject_code
 * @property string $subject_name
 * @property integer $credits
 * @property integer $out_of_maximum
 * @property integer $CIA
 * @property integer $ESE
 * @property integer $total
 * @property string $grade_point
 * @property string $grade_name
 * @property integer $cia_maximum
 * @property integer $cia_minimum
 * @property integer $ese_minimum
 * @property integer $ese_maximum
 * @property integer $total_minimum_pass
 * @property string $result
 * @property string $year_of_passing
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property StudentMapping $studentMap
 * @property CategoryType $examMonth
 * @property User $createdBy
 * @property User $updatedBy
 */
class AdditionalCredits extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_additional_credits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exam_year', 'exam_month', 'student_map_id', 'subject_code', 'subject_name', 'credits', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['exam_year', 'exam_month', 'student_map_id', 'credits', 'out_of_maximum', 'CIA', 'ESE', 'total', 'cia_maximum', 'cia_minimum', 'ese_minimum', 'ese_maximum','total_minimum_pass', 'created_by', 'updated_by'], 'integer'],
            [['subject_code', 'grade_point', 'grade_name', 'result', 'year_of_passing', 'created_at', 'updated_at'], 'string', 'max' => 45],
            [['subject_name'], 'string', 'max' => 255],
            [['student_map_id', 'subject_code'], 'unique', 'targetAttribute' => ['student_map_id', 'subject_code'], 'message' => 'The combination of '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' and '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code has already been taken.'],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['exam_month'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['exam_month' => 'coe_category_type_id']],
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
            'coe_additional_credits_id' => 'Coe Additional Credits ID',
            'exam_year' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Year',
            'exam_month' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month',
            'student_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
            'subject_code' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code',
            'subject_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name',
            'credits' => 'Credits',

            'out_of_maximum' => 'Out Of Maximum',
            'CIA' => 'Cia',
            'ESE' => 'Ese',
            'total' => 'Total',
            'grade_point' => 'Grade',
            'grade_name' => 'Grade Name',
            'cia_maximum' => 'Cia Maximum',
            'cia_minimum' => 'Cia Minimum',
            'ese_minimum' => 'Ese Minimum',
            'ese_maximum' => 'Ese Maximum',
            'total_minimum_pass' => 'Minimum Pass',
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
    public function getExamMonth()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'exam_month']);
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

    public function getProgrammeType()
    {
        $sub = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_PROGRAMME_TYPE);
        $config_list = Categories::find()->where(['category_name' => $sub])->one();
        $c_id = $config_list['coe_category_id'];

        $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
        $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');
        return $vals;
    }
}
