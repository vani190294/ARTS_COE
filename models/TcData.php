<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tc_data}}".
 *
 * @property string $name
 * @property string $reg_num
 * @property string $dob
 * @property string $guardian_name
 * @property string $nationality
 * @property string $religion
 * @property string $caste
 * @property string $sub_caste
 * @property string $admission_date
 */
class TcData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tc_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dob', 'nationality', 'religion', 'caste', 'admission_date'], 'required'],
            [['dob', 'admission_date'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['reg_num', 'guardian_name', 'nationality', 'religion', 'caste', 'sub_caste'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Upper(name)',
            'reg_num' => 'Upper(register Number)',
            'guardian_name' => 'Upper(Guardian Name)',
            'dob' => 'Dob',
            'nationality' => 'Nationality',
            'religion' => 'Religion',
            'caste' => 'Caste',
            'sub_caste' => 'Sub Caste',
            'admission_date' => 'Admission Date',
        ];
    }
}
