<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * This is the model class for table "{{%coe_guardian}}".
 *
 * @property integer $coe_guardian_id
 * @property integer $stu_guardian_id
 * @property string $guardian_name
 * @property string $guardian_relation
 * @property string $guardian_mobile_no
 * @property string $guardian_address
 * @property string $guardian_email
 * @property string $guardian_occupation
 *
 * @property CoeStudent $stuGuardian
 */
class Guardian extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $guardian_name_1,$guardian_relation_1,$guardian_mobile_no_1;
    public static function tableName()
    {
        return '{{%coe_stu_guardian}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stu_guardian_id', 'guardian_name',  'guardian_mobile_no', 'guardian_address'], 'required'],
            [['stu_guardian_id','guardian_mobile_no'], 'integer'],
            [['guardian_income'], 'integer','min' => 0, 'max'=>9999999999,'message'=> '{attribute} Must be in integer Only.','tooSmall'=>'Should be 10 digit long.(Example: 1234567890)' , 'tooBig' => 'Should be 10 digit long and Maximum 1234567890.(Example: 1234567890)'],
            [['guardian_email'],  'email'],
             [['guardian_mobile_no'],'integer', 'min' => 1000000000, 'max'=>9999999999,'message'=> 'Mobile Number Must be in integer Only.','tooSmall'=>'Should be 10 digit long.(Example: 1234567890)' , 'tooBig' => 'Should be 10 digit long and Maximum 9999999999.(Example: 1234567890)'],
            [['guardian_mobile_no','guardian_income'],'integer','integerPattern'=>'/^\s*[+-]?\d+\s*$/','message'=> '{attribute} Must be in integer Only.'],
            [['guardian_name', 'guardian_relation',  'guardian_address', 'guardian_occupation'], 'string', 'max' => 45, ],
            [['guardian_relation',  'guardian_occupation'],'match', 'pattern' => '/^[a-zA-Z\s]+\w*$/i'],
            [['guardian_name'],'match', 'pattern' => '/^[a-zA-Z\s .]+\w*$/i'],
            [['stu_guardian_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::className(), 'targetAttribute' => ['stu_guardian_id' => 'coe_student_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_guardian_id' => 'Guardian ID',
            'stu_guardian_id' => 'Guardian',
            'guardian_name' => 'Guardian Name',
            'guardian_name_1' => 'Guardian Name',
            'guardian_relation_1' => 'Guardian Relation',
            'guardian_relation' => 'Guardian Relation',
            'guardian_mobile_no_1' => 'Guardian Mobile Number',
            'guardian_mobile_no' => 'Guardian Mobile Number',
            'guardian_address' => 'Guardian Address',
            'guardian_email' => 'Guardian Email',
            'guardian_income' => 'Guardian Income',
            'guardian_occupation' => 'Guardian Occupation',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStuGuardian()
    {
        return $this->hasOne(Student::className(), ['coe_student_id' => 'stu_guardian_id']);
    }
   

}
