<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "coe_value_mark_entry".
 *
 * @property integer $coe_value_mark_entry_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $CIA
 * @property integer $ESE
 * @property integer $total
 * @property string $result
 * @property double $grade_point
 * @property string $grade_name
 * @property integer $year
 * @property integer $month
 * @property integer $term
 * @property integer $mark_type
 * @property integer $status_id
 * @property string $year_of_passing
 * @property integer $attempt
 * @property string $withheld
 * @property string $withheld_remarks
 * @property string $withdraw
 * @property string $is_updated
 * @property string $fees_paid
 * @property string $result_published_date
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class CoeValueMarkEntry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_value_mark_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
       return [
            [['student_map_id', 'CIA', 'ESE', 'total', 'result', 'grade_point', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['student_map_id', 'subject_map_id','year','month','term','mark_type', 'CIA', 'ESE', 'total', 'created_by', 'updated_by'], 'integer'],
            [['grade_point'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['result','withheld'], 'string', 'max' => 50],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['subject_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['subject_map_id' => 'coe_sub_mapping_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            

             'coe_value_mark_entry_id' => 'Coe Value Mark Entry ID',
            'student_map_id' => 'Student Map ID',
            'subject_map_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE",
            'CIA' => 'Cia',
            'year' => 'Year',
            'month' => 'Month',
            'term' => 'Term',
            'mark_type' => 'Mark Type',
            'ESE' => 'Ese',
            'total' => 'Total',
            'result' => 'Result',
            'grade_point' => 'Grade Point',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',




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
    public function getTerm()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'term']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMonthName()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'month']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarkType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'mark_type']);
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
    public function getStudentDet()
    {
        return $this->hasOne(Student::className(), ['coe_student_id' => 'student_rel_id'])->via('studentMap');
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
    public function getSubjectDet()
    {
        return $this->hasOne(Subjects::className(), ['coe_subjects_id' => 'subject_id'])->via('subjectMap');
    }

     public function getMonth(){
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
}
}
