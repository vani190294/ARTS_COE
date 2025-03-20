<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\models\ValuationFaculty;
use yii\db\Query;

/**
 * This is the model class for table "coe_faculty_hall_arrange".
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
class FacultyHall extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_faculty_hall';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['faculty_id', 'uniqueid', 'department', 'designation', 'facultymode', 'experience', 'phone', 'collegename','bankaccno','bankname','bankbranch','bankifsc','email','slot'], 'required'],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'faculty_id' => 'ID',
            'uniqueid' => 'Faculty ID',
            'department' => 'Department',
            'designation' => 'Designation',
            'faculty_id' => 'Faculty',
            'facultymode' => 'FacultyMode',
            'experience' => 'Experience',
            'phone' => 'Phone',
            'collegename' => 'College Name',
            'bankaccno' =>'Bank Account',
            'bankname' =>'Bank Name',
            'bankbranch' =>'Bank Branch',
            'bankifsc' =>'Bank IFSC',
            'email'  =>'Email',
            'slot'=>'SLOT'
        ];
    }

     public function getMonth(){
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
    }
}
