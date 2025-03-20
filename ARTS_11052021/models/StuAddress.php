<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/**
 * This is the model class for table "{{%coe_stu_address}}".
 *
 * @property integer $coe_stu_address_id
 * @property integer $stu_address_id
 * @property string $current_address
 * @property string $current_city
 * @property string $current_state
 * @property string $current_country
 * @property string $current_pincode
 * @property string $permanant_address
 * @property string $permanant_state
 * @property string $permanant_country
 * @property string $permanant_pincode
 *
 * @property CoeStudent $stuAddress
 * @property CoeStudentMapping[] $coeStudentMappings
 */
class StuAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_stu_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stu_address_id', 'current_address', 'current_city', 'current_state','current_pincode'], 'required'],
            [['stu_address_id','permanant_pincode','current_pincode'], 'integer','min' => 6, 'max'=>999999,'message'=> '{attribute} Must be in integer Only.','tooSmall'=>'Should be 6 digit long.(Example: 123456)' , 'tooBig' => 'Should be 6 digit long and Maximum 999999.(Example: 999999)'],
            [['current_address', 'permanant_address','permanant_city',], 'string'],
            [['permanant_pincode','current_pincode'],'integer', 'min' => 1000, 'max'=>9999999999,'message'=> 'Pincode Must be in integer Only.','tooSmall'=>'Should be 6 digit long.(Example: 123456)' , 'tooBig' => 'Should be 6 digit long and Maximum 999999.(Example: 123456)'],
            [['current_city', 'current_state', 'current_country', 'permanant_state', 'permanant_country', ], 'string', 'max' => 45],
            [['current_city', 'current_state', 'current_country', 'permanant_state', 'permanant_country', 'permanant_city'],'match', 'pattern' => '/^[a-zA-Z\s]+\w*$/i'],
            [['stu_address_id','permanant_pincode','permanant_city','permanant_state','permanant_country'], 'exist', 'skipOnError' => true, 'targetClass' => Student::className(), 'targetAttribute' => ['stu_address_id' => 'coe_student_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_stu_address_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).'Address ID',
            'stu_address_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).'Address ID',
            'current_address' => 'Current Address',
            'current_city' => 'Current City',
            'current_state' => 'Current State',
            'current_country' => 'Current Country',
            'current_pincode' => 'Current Pincode',
            'permanant_city' => 'Permanent City',
            'permanant_address' => 'Permanent Address',
            'permanant_state' => 'Permanent State',
            'permanant_country' => 'Permanent Country',
            'permanant_pincode' => 'Permanent Pincode',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStuAddress()
    {
        return $this->hasOne(Student::className(), ['coe_student_id' => 'stu_address_id']);
    }

    
}
