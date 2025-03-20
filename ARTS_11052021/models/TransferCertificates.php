<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coe_transfer_certificates}}".
 *
 * @property string $coe_transfer_certificates_id
 * @property string $register_number
 * @property string $name
 * @property string $parent_name
 * @property string $dob
 * @property string $nationality
 * @property string $religion
 * @property string $community
 * @property string $caste
 * @property string $admission_date
 * @property string $class_studying
 * @property string $reason
 * @property string $is_qualified
 * @property string $conduct_char
 * @property string $date_of_tc
 * @property string $date_of_app_tc
 * @property string $serial_no
 * @property string $created_at
 * @property integer $created_by
 *
 * @property User $createdBy
 */
class TransferCertificates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_transfer_certificates}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['register_number', 'name', 'parent_name', 'dob', 'nationality', 'religion', 'community', 'caste', 'admission_date', 'class_studying', 'reason', 'is_qualified', 'conduct_char', 'date_of_tc', 'date_of_app_tc', 'serial_no'], 'required'],
            [['dob', 'admission_date', 'date_of_tc', 'date_of_app_tc', 'created_at'], 'safe'],
            [['created_by'], 'integer'],
            [['register_number', 'name', 'parent_name', 'nationality', 'religion', 'community', 'caste', 'class_studying', 'reason', 'conduct_char'], 'string', 'max' => 1005],
            [['is_qualified', 'serial_no'], 'string', 'max' => 1005],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_transfer_certificates_id' => 'Coe Transfer Certificates ID',
            'register_number' => 'Register Number',
            'name' => 'Name',
            'parent_name' => 'Parent Name',
            'dob' => 'Dob',
            'nationality' => 'Nationality',
            'religion' => 'Religion',
            'community' => 'Community',
            'caste' => 'Caste',
            'admission_date' => 'Admission Date',
            'class_studying' => 'Class Studying',
            'reason' => 'Reason',
            'is_qualified' => 'Is Qualified',
            'conduct_char' => 'Conduct',
            'date_of_tc' => 'Date Of Tc',
            'date_of_left' => 'Date Of Left',
            'date_of_app_tc' => 'Date Of App Tc',
            'serial_no' => 'Serial No',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by'])->inverseOf('transferCertificates');
    }
}
