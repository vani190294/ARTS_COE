<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coe_mandatory_stu_marks}}".
 *
 * @property integer $coe_mandatory_stu_marks_id
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
 * @property integer $semester
 * @property integer $mark_type
 * @property integer $status_id
 * @property string $year_of_passing
 * @property string $attempt
 * @property string $withheld
 * @property string $withheld_remarks
 * @property string $withdraw
 * @property string $fees_paid
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property Categorytype $month0
 * @property StudentMapping $studentMap
 * @property MandatorySubcatSubjects $subjectMap
 * @property Categorytype $term0
 * @property User $updatedBy
 */
class MandatoryStuMarks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_mandatory_stu_marks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'subject_map_id', 'CIA', 'ESE', 'total', 'result', 'grade_point', 'grade_name', 'year', 'month', 'term', 'mark_type', 'semester','status_id', 'year_of_passing', 'created_by', 'updated_by'], 'required'],
            [['student_map_id', 'subject_map_id', 'CIA', 'ESE', 'total', 'year', 'month', 'term', 'mark_type', 'status_id', 'created_by', 'semester','updated_by'], 'integer'],
            [['grade_point'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['result', 'year_of_passing', 'attempt', 'withheld', 'withheld_remarks', 'withdraw', 'fees_paid'], 'string', 'max' => 255],
            [['grade_name'], 'string', 'max' => 10],
            [['student_map_id', 'subject_map_id', 'year', 'month', 'mark_type', 'term'], 'unique', 'targetAttribute' => ['student_map_id', 'subject_map_id', 'year', 'month', 'mark_type', 'term'], 'message' => 'The combination of Student Map ID, Subject Map ID, Year, Month, Term and Mark Type has already been taken.'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['month'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['month' => 'coe_category_type_id']],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['subject_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => MandatorySubcatSubjects::className(), 'targetAttribute' => ['subject_map_id' => 'coe_mandatory_subcat_subjects_id']],
            [['term'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['term' => 'coe_category_type_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_mandatory_stu_marks_id' => 'Coe Mandatory Stu Marks ID',
            'student_map_id' => 'Student Map ID',
            'subject_map_id' => 'Subject Map ID',
            'CIA' => 'Cia',
            'ESE' => 'Ese',
            'total' => 'Total',
            'result' => 'Result',
            'grade_point' => 'Grade Point',
            'grade_name' => 'Grade Name',
            'year' => 'Year',
            'semester' => 'Semester',
            'month' => 'Month',
            'term' => 'Term',
            'mark_type' => 'Mark Type',
            'status_id' => 'Status ID',
            'year_of_passing' => 'Year Of Passing',
            'attempt' => 'Attempt',
            'withheld' => 'Withheld',
            'withheld_remarks' => 'Withheld Remarks',
            'withdraw' => 'Withdraw',
            'fees_paid' => 'Fees Paid',
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
    public function getMonth0()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'month']);
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
        return $this->hasOne(MandatorySubcatSubjects::className(), ['coe_mandatory_subcat_subjects_id' => 'subject_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerm0()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'term']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
