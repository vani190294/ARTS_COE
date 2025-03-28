<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\models\Categorytype;
use app\models\Categories;
use app\models\ExamTimetable;
use yii\db\Query;
/**
 * This is the model class for table "coe_absent_entry_int".
 *
 * @property integer $coe_absent_entry_id
 * @property integer $absent_student_reg
 * @property integer $exam_type
 * @property integer $absent_term
 * @property string $exam_date
 * @property integer $exam_year
 * @property integer $exam_month
 * @property string $exam_session
 * @property integer $exam_subject_id
 * @property integer $exam_absent_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class AbsentEntryInt extends \yii\db\ActiveRecord
{
     /**
     * @inheritdoc
     */
    public $absent_type,$course_batch_id,$batch_id,$stu_section_name,$halls,$exam_semester_id;
    public static function tableName()
    {
        return '{{%coe_absent_entry_int}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['absent_student_reg', 'exam_type', 'absent_term', 'exam_subject_id', 'exam_absent_status','absent_type'], 'required'],
            [['absent_student_reg', 'exam_type', 'absent_term','exam_month','exam_year','exam_subject_id', 'exam_absent_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at','halls'], 'safe'],
            [['exam_date', 'exam_session'], 'string', 'max' => 45],
            [['absent_student_reg', 'exam_type', 'exam_subject_id', 'absent_term'], 'unique', 'targetAttribute' => ['absent_student_reg', 'exam_type', 'exam_subject_id', 'absent_term'], 'message' => 'The combination of '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).', '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).', '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).', Term and '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' has already been taken.'],            
            [['absent_term'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryType::className(), 'targetAttribute' => ['absent_term' => 'coe_category_type_id']],
            [['absent_student_reg'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['absent_student_reg' => 'coe_student_mapping_id']],
            [['exam_type'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['exam_type' => 'coe_category_type_id']],
            [['exam_subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['exam_subject_id' => 'coe_subjects_mapping_id']],
            [['exam_absent_status'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['exam_absent_status' => 'coe_category_type_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_absent_entry_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' ID',
            'absent_student_reg' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Register Number",
            'exam_type' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE),
            'exam_date' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date',
            'exam_session' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION),
            'exam_subject_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code",
            'exam_absent_status' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT).' Status',
            'exam_month' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month',
            'absent_type' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT_TYPE),
            'absent_term' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TERM),
            'batch_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
            'course_batch_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
            'halls' => 'Available Halls',
            'exam_year' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' YEAR',
            'exam_semester_id' => 'Semester',
            'stu_section_name' => 'Section',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getAbsentTerm()
    {
        return $this->hasOne(CategoryType::className(), ['coe_category_type_id' => 'absent_term']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAbsentMonth()
    {
        return $this->hasOne(CategoryType::className(), ['coe_category_type_id' => 'exam_month']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    public function getAbsentStudentReg()
    {
        return $this->hasOne(StudentMapping::className(), ['coe_student_mapping_id' => 'absent_student_reg']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'exam_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamSubject()
    {
        return $this->hasOne(SubjectsMapping::className(), ['coe_subjects_mapping_id' => 'exam_subject_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamAbsentStatus()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'exam_absent_status']);
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
     * @return AbsentEntryInt Types
     */
    public function getAbTypes()
    {
        $ab_type = Categories::find()->where(['description'=>ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_ABSENT_TYPE)])->one();
        $ab_types = Categorytype::find()->where(['category_id'=>$ab_type->coe_category_id])->andWhere(['NOT LIKE','description','Hall Wise Entry'])->orderBy(['category_type'=>SORT_ASC])->all();
        $types = ArrayHelper::map($ab_types,'coe_category_type_id','category_type');
        return $types;
    }
    public function getAbviewTypes()
    {
        $ab_type = Categories::find()->where(['description'=>ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_ABSENT_TYPE)])->one();
        $ab_types = Categorytype::find()->where(['category_id'=>$ab_type->coe_category_id])->andWhere(['<>','category_type', "Practical Entry"])->andWhere(['<>','category_type', "Hall Wise Entry"])->orderBy(['category_type'=>SORT_ASC])->all();
        $types = ArrayHelper::map($ab_types,'coe_category_type_id','category_type');
        return $types;
    }
    public function getAbStatus()
    {
        $ab_type = Categories::find()->where(['description'=>'Absent Status'])->one();
        $ab_types = Categorytype::find()->where(['category_id'=>$ab_type->coe_category_id])->orderBy(['category_type'=>SORT_ASC])->all();
        $types = ArrayHelper::map($ab_types,'coe_category_type_id','category_type');
        return $types;
    }
    public function getExamDates($exam_year)
    {
        $get_exam_dates = ExamTimetableInt::find()->where(["exam_year"=>$exam_year])->orderBy('exam_date')->all();
        $exam_dates = ArrayHelper::map($get_exam_dates,'coe_exam_timetable_id','exam_date');
        $exam_data = [];
        $old_value='';
        foreach($exam_dates as $key=>$value)
        {
            if($old_value!=$value)
            {
                $exam_data[$key]=$value;
                $old_value=$value;
            }
            
        }
        
        return $exam_data;
    }
    public function findModel($id)
    {
        if (($model = AbsentEntryInt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

      public function getMonth(){
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
    }
}
