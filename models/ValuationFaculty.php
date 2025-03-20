<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coe_valuation_faculty".
 *
 * @property integer $coe_val_faculty_id
 * @property string $faculty_name
 * @property string $faculty_designation
 * @property string $faculty_board
 * @property string $faculty_mode
 * @property integer $faculty_experience
 * @property string $phone_no
 * @property string $college_code
 * @property string $created_at
 */
class ValuationFaculty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_valuation_faculty';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['faculty_name', 'faculty_designation', 'faculty_board', 'faculty_mode', 'faculty_experience', 'phone_no', 'college_code', 'created_at'], 'required'],
            [['faculty_experience'], 'integer'],
            [['created_at'], 'safe'],
            [['faculty_name', 'faculty_mode'], 'string', 'max' => 100],
            [['faculty_designation', 'college_code','department'], 'string', 'max' => 255],
            [['faculty_board', 'phone_no'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_val_faculty_id' => 'Coe Val Faculty ID',
            'faculty_name' => 'Faculty Name',
            'faculty_designation' => 'Faculty Designation',
            'faculty_board' => 'Faculty Board',
            'faculty_mode' => 'Faculty Mode',
            'faculty_experience' => 'Faculty Experience',
            'department' => 'Faculty Department',
            'phone_no' => 'Phone No',
            'college_code' => 'College Code',
            'created_at' => 'Created At',
        ];
    }
}
