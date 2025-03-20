<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "coe_valuation_scrutiny".
 *
 * @property integer $coe_scrutiny_id
 * @property string $name
 * @property string $designation
 * @property string $department
 * @property string $phone_no
 * @property integer $year
 * @property integer $month
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class ValuationScrutiny extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_valuation_scrutiny';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'designation', 'department', 'phone_no', 'year', 'month', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['year', 'month', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['designation'], 'string', 'max' => 255],
            [['department', 'phone_no'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_scrutiny_id' => 'Scrutiny',
            'name' => 'Name',
            'designation' => 'Designation',
            'department' => 'Department',
            'phone_no' => 'Phone No',
            'year' => 'Year',
            'month' => 'Month',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function getScrutiny()
    {
        $scrutiny = Yii::$app->db->createCommand("select coe_scrutiny_id, concat(name,'(',department,')') as name from coe_valuation_scrutiny ")->queryAll();
        return  $all_scrutiny = ArrayHelper::map($scrutiny,'coe_scrutiny_id','name');
    }
}
