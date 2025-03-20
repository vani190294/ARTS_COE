<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\models\ValuationFaculty;
use yii\db\Query;

/**
 * This is the model class for table "coe_faculty_hall_arrange_add".
 *
 * @property integer $fh_arrange_id
 * @property integer $hall_master_id
 * @property integer $year
 * @property integer $month
 * @property integer $faculty_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class FacultyHallArrangeAdd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_faculty_hall_arrange';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hall_master_id', 'year', 'month', 'faculty_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['hall_master_id', 'year', 'month', 'faculty_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fh_arrange_id' => 'Fh Arrange ID',
            'hall_master_id' => 'Hall Master',
            'year' => 'Year',
            'month' => 'Month',
            'faculty_id' => 'Faculty',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

     public function getMonth(){
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
    }
}
