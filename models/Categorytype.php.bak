<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "coe_category_type".
 *
 * @property integer $coe_category_type_id
 * @property integer $category_id
 * @property string $category_type
 * @property string $description
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class Categorytype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_category_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['category_id', 'category_type', 'description', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['category_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['category_type', 'description'], 'string', 'max' => 255],
            //[['category_type'], 'match' ,'pattern'=> '/^[A-Za-z0-9/ ]+$/u','message'=> 'Enter valid '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY_TYPE).'.'],
            //[['description'], 'match' ,'pattern'=> '/^[A-Za-z0-9/ ]+$/u','message'=> 'Enter valid '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY).' description.'],
            //[['category_type'], 'unique', 'targetAttribute' => ['category_type'], 'message' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY_TYPE).'already created.'],
            //[['category_type'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_category_type_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY_TYPE).' ID',
            'category_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY_TYPE).' ID',
            'category_type' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY_TYPE),
            'description' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY).' Description',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryName()
    {
        return $this->hasOne(Categories::className(), ['coe_category_id' => 'category_id']);
    }

   

    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['coe_category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamTimetables()
    {
        return $this->hasMany(ExamTimetable::className(), ['exam_month' => 'coe_category_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamTimetables0()
    {
        return $this->hasMany(ExamTimetable::className(), ['exam_type' => 'coe_category_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamTimetables1()
    {
        return $this->hasMany(ExamTimetable::className(), ['exam_term' => 'coe_category_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamTimetables2()
    {
        return $this->hasMany(ExamTimetable::className(), ['exam_session' => 'coe_category_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHallMasters()
    {
        return $this->hasMany(HallMaster::className(), ['hall_type_id' => 'coe_category_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentMappings()
    {
        return $this->hasMany(StudentMapping::className(), ['status_category_type_id' => 'coe_category_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentMappings0()
    {
        return $this->hasMany(StudentMapping::className(), ['admission_category_type_id' => 'coe_category_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectsMappings()
    {
        return $this->hasMany(SubjectsMapping::className(), ['paper_type_id' => 'coe_category_type_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getCategoryIdName()
    {
        $dataTmp = Categorytype::find()->orderBy(['category_type'=>SORT_ASC])->all();
        $result = yii\helpers\ArrayHelper::map($dataTmp, 'coe_category_type_id', 'description');
        return $result;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectsMappings0()
    {
        return $this->hasMany(SubjectsMapping::className(), ['subject_type_id' => 'coe_category_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectsMappings1()
    {
        return $this->hasMany(SubjectsMapping::className(), ['course_type_id' => 'coe_category_type_id']);
    }


    public static function getCategoryId()
    {
        $dataTmp = Categorytype::find()->orderBy(['category_type'=>SORT_ASC])->all();
        $result = yii\helpers\ArrayHelper::map($dataTmp, 'coe_category_type_id', 'category_type');
        return $result;
    }
    public function getCategorytype()
    {
      $category_type_list = Yii::$app->db->createCommand("select coe_category_type_id,category_type from coe_category_type a,coe_categories b where b.category_name like '%".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_HALLTYPE)."%' and b.coe_category_id=a.category_id")->queryAll();
      $vals1 = ArrayHelper::map($category_type_list,'coe_category_type_id','category_type');
      return $vals1;
    }
}
