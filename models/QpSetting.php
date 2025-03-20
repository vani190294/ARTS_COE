<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\models\ValuationFaculty;
use yii\db\Query;
/**
 * This is the model class for table "coe_qp_setting".
 *
 * @property integer $coe_qp_id
 * @property integer $year
 * @property integer $month
 * @property integer $qp_code
 * @property integer $faculty1_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class QpSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_qp_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_qp_id', 'year', 'month','exam_type', 'subject_code', 'subject_id', 'faculty1_id'], 'required'],
            [['coe_qp_id', 'batch_id', 'year', 'month', 'subject_id', 'exam_type','created_by', 'updated_by'], 'integer'],
            [['subject_id', 'faculty1_id', 'faculty2_id', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_qp_id' => 'Coe Qp ID',
            'year' => 'Year',
            'month' => 'Month',
            'exam_type'=>'Exam Type',
            'batch_id' => 'Batch',
            'subject_code' => 'Subject Code',
            'subject_id' => 'Subject ID',
            'faculty1_id' => 'Faculty1',
            'faculty2_id' => 'Faculty2',
            'choosen_qp' => 'QP For Scrutiny',
            'qp_scrutiny_id' => 'QP Scrutiny',
            'qp_scrutiny_session' => 'Scrutiny Session',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function getfaculty()
    {
        $getfaculty = ValuationFaculty::find()->orderBy(['coe_val_faculty_id'=>SORT_ASC])->all();
        //$getfacultydata = ArrayHelper::map($getfaculty,'coe_val_faculty_id','faculty_name');
        $getfacultydata = ArrayHelper::map($getfaculty, 'coe_val_faculty_id', function ($faculty) {
        return !empty($faculty->college_code)?$faculty->faculty_name.' ('.$faculty->college_code.')':$faculty->faculty_name;
        });
        return $getfacultydata;
    }

    public function getMonth(){
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
    }

    public function getExamType()
    {
        $exam = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_EXAM_TYPE);
        $config_list = Categories::find()->where(['category_name' => $exam])->one();
        $c_id = $config_list['coe_category_id'];

        $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
        $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');

        return $vals;
    }

}
