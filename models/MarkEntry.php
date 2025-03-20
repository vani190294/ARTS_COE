<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "coe_mark_entry".
 *
 * @property integer $coe_mark_entry_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $category_type_id
 * @property integer $category_type_id_marks
 * @property integer $year
 * @property string $month
 * @property string $term
 * @property integer $status_id
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $mark_out_of
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property CoeStudentMapping $studentMap
 * @property CoeSubjectsMapping $subjectMap
 * @property CoeCategoryType $categoryType
 */
class MarkEntry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const TYPE_REG = 'Register Number';
    const TYPE_MARK = 'Mark';
    const TYPE_DEPT = 'Department Wise';    
    const TYPE_SUB = 'Subject Wise';

    const TYPE_WITHMODEL = 'With Model';
    const TYPE_WITHOUTMODEL = 'Without Model';

    const TYPE_CBCS = 'CBCS';    
    const TYPE_CBCS_NEW = 'CBCS-NEW';
    const TYPE_CBCS_NEW_PG = 'CBCS-NEW-PG';
    const TYPE_NONCBCS = 'NON-CBCS';

    public $model_1,$model_2,$credit_type,$model_type,$semester,$section,$stu_batch_id,$stu_programme_id,$filter,$reg_range_from,$reg_range_to,$mark_from,$mark_to,$register_number,$mark_view_register_number,$batch_mapping_id;
    public static function tableName()
    {
        return 'coe_mark_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id',  'category_type_id', 'category_type_id_marks', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['student_map_id', 'subject_map_id', 'category_type_id', 'mark_out_of', 'category_type_id_marks', 'year', 'status_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['attendance_percentage'],'number'],
            [['attendance_remarks'],'string'],
            [['month', 'term'], 'string', 'max' => 50],
            //[['student_map_id', 'subject_map_id', 'category_type_id','month', 'year'], 'unique', 'targetAttribute' => ['student_map_id', 'subject_map_id', 'category_type_id'], 'message' => 'The combination of Student Map ID, Subject Map ID and Category Type ID has already been taken.'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['subject_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['subject_map_id' => 'coe_subjects_mapping_id']],
            [['category_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['category_type_id' => 'coe_category_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_mark_entry_id' => 'Coe Mark Entry ID',
            'student_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
            'subject_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT),
            'category_type_id' => 'Category Type',
            'category_type_id_marks' => 'Marks',
            'year' => 'Year',
            'mark_out_of' => 'Mark Out Of',
            'month' => 'Month',
            'term' => 'Term',
            'mark_type' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE),
            'status_id' => 'Status ID',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'filter' => 'Filter Type',
            'mark_view_register_number'=>'Register Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentMap()
    {
        return $this->hasOne(StudentMapping::className(), ['coe_student_mapping_id' => 'student_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectMap()
    {
        return $this->hasOne(SubjectsMapping::className(), ['coe_subjects_mapping_id' => 'subject_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'category_type_id']);
    }

    public function getInitmarktype()
    {
        $mark_type = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MARK_TYPE);
        $config_list = Categories::find()->where(['category_name' => $mark_type ])->one();
        $c_id = $config_list['coe_category_id'];

        //$config_list = Categorytype::find()->where(['category_id' => $c_id,'description'])->all();
        $config_list = Yii::$app->db->createCommand("select * from coe_category_type where category_id='".$c_id."' and description like 'CIA%' or description like 'cia%' or description like 'internal%' or description like 'Internal%'")->queryAll();
        $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');
        return $vals;
    }

    public function getFilter(){
        return[
           
           Yii::t('app',self::TYPE_REG) => Yii::t('app','Register Number'),
           Yii::t('app',self::TYPE_MARK) => Yii::t('app','Mark'),
           ];
    }

    public function getModtype(){
        return[
           Yii::t('app',self::TYPE_DEPT) => Yii::t('app','Department Wise'),
           Yii::t('app',self::TYPE_SUB) => Yii::t('app',ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Wise'),
           ];
    }

    public function getMonth(){
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
    }

    
    public function getModel(){
        return[
           Yii::t('app',self::TYPE_WITHMODEL) => Yii::t('app','With Model'),
           Yii::t('app',self::TYPE_WITHOUTMODEL) => Yii::t('app','Without Model'),
           ];
    }



    public function getCreditsystem()
    {
        return[
            Yii::t('app',self::TYPE_CBCS) => Yii::t('app','CBCS'),
            Yii::t('app',self::TYPE_CBCS_NEW) => Yii::t('app','CBCS NEW(SKCET)'),
            Yii::t('app',self::TYPE_CBCS_NEW_PG) => Yii::t('app','CBCS NEW PG(SKCET)'),
            Yii::t('app',self::TYPE_NONCBCS) => Yii::t('app','NON-CBCS'),
        ];
    }

}
