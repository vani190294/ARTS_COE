<?php

namespace app\models;

use app\components\ConfigUtilities;
use Yii;

/**
 * This is the model class for table "{{%coe_external_wightage_marks}}".
 *
 * @property integer $coe_external_wightage_marks_id
 * @property integer $student_map_id
 * @property integer $subject_map_id
 * @property integer $year
 * @property integer $month
 * @property integer $out_of_100
 * @property integer $batch_map_id
 * @property integer $current_cia_marks
 * @property integer $prev_semester
 * @property integer $prev_subjects_count
 * @property integer $wightage_marks
 * @property integer $prev_ese_total
 * @property integer $prev_average
 * @property integer $current_semester
 * @property integer $new_ese_marks
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Categorytype $month0
 * @property CoeBatDegReg $batchMap
 * @property User $createdBy
 * @property StudentMapping $studentMap
 * @property SubjectsMapping $subjectMap
 * @property User $updatedBy
 */
class ExternalWightageMarks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_external_wightage_marks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'subject_map_id', 'year', 'month', 'batch_map_id', 'current_cia_marks', 'prev_semester', 'prev_subjects_count', 'wightage_marks', 'prev_ese_total', 'current_semester', 'new_ese_marks', 'created_by', 'updated_by','out_of_100'], 'required'],
            [['student_map_id', 'subject_map_id', 'year', 'out_of_100','month', 'batch_map_id', 'current_cia_marks', 'prev_semester', 'prev_subjects_count', 'wightage_marks', 'prev_ese_total', 'prev_average', 'current_semester', 'new_ese_marks', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['month'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['month' => 'coe_category_type_id']],
            [['batch_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoeBatDegReg::className(), 'targetAttribute' => ['batch_map_id' => 'coe_bat_deg_reg_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' =>  StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['subject_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['subject_map_id' => 'coe_subjects_mapping_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_external_wightage_marks_id' => 'Coe External Wightage Marks ID',
            'student_map_id' => 'Student Map ID',
            'subject_map_id' => 'Subject Map ID',
            'year' => 'Year',
            'out_of_100'=>'Out of 100',
            'month' => 'Month',
            'batch_map_id' => 'Batch Map ID',
            'current_cia_marks' => 'Current Cia Marks',
            'prev_semester' => 'Prev Semester',
            'prev_subjects_count' => 'Prev Subjects Count',
            'wightage_marks' => 'Wightage Marks',
            'prev_ese_total' => 'Prev Ese Total',
            'prev_average' => 'Prev Average',
            'current_semester' => 'Current Semester',
            'new_ese_marks' => 'New Ese Marks',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
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
    public function getBatchMap()
    {
        return $this->hasOne(CoeBatDegReg::className(), ['coe_bat_deg_reg_id' => 'batch_map_id']);
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * Save Model Data 
     * return 1
     */
    public function saveData($data)
    {
        for ($i=0; $i <count($data) ; $i++) { 

            $model = ExternalWightageMarks::find()
                            ->where([
                                'student_map_id'=>$data[$i]['student_map_id'],
                                'subject_map_id'=>$data[$i]['subject_map_id'],
                                'year'=>$data[$i]['exam_year'],
                                'month'=>$data[$i]['exam_month']
                            ])
                            ->one();
            if(empty($model))
            {
                $model = new ExternalWightageMarks();
            }
            $model->student_map_id = $data[$i]['student_map_id'];
            $model->subject_map_id = $data[$i]['subject_map_id'];
            $model->year = $data[$i]['exam_year'];
            $model->month = $data[$i]['exam_month'];
            $model->batch_map_id  = $data[$i]['batch_map_id'];
            $model->current_cia_marks = $data[$i]['CIA'];
            $model->out_of_100 = $data[$i]['out_of_100'];
            $model->prev_semester = $data[$i]['prev_semester'];
            $model->prev_subjects_count = $data[$i]['total_prev_subjects'];
            $model->wightage_marks = $data[$i]['average'];
            $model->prev_ese_total = $data[$i]['total_prev_marks'];
            $model->prev_average = $data[$i]['total_prev_average'];
            $model->current_semester = $data[$i]['semester'];
            $model->new_ese_marks = $data[$i]['convert_ese_marks'];
            $model->created_at = ConfigUtilities::getCreatedTime();
            $model->created_by = ConfigUtilities::getCreatedUser();
            $model->updated_at = ConfigUtilities::getCreatedTime();
            $model->updated_by = ConfigUtilities::getCreatedUser();
            $model->save();
            
            unset($model);

        }
        return 1;
    }
}
