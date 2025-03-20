<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coe_consolidate_marks}}".
 *
 * @property integer $coe_consolidate_marks_id
 * @property integer $batch_maping_id
 * @property integer $student_map_id
 * @property integer $part_no
 * @property integer $part_credits
 * @property integer $marks_gain
 * @property integer $marks_total
 * @property double $percentage
 * @property double $cgpa
 * @property string $grade
 * @property string $classification
 * @property integer $part_add_credits
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property CoeBatDegReg $batchMaping
 * @property CoeStudentMapping $studentMap
 * @property User $createdBy
 * @property User $updatedBy
 */
class ConsolidateMarks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_consolidate_marks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch_maping_id', 'student_map_id', 'part_credits', 'marks_gain', 'marks_total', 'percentage', 'cgpa', 'grade', 'classification', 'created_by', 'updated_by'], 'required'],
            [['batch_maping_id', 'student_map_id', 'part_no', 'part_credits', 'marks_gain', 'marks_total', 'part_add_credits', 'created_by', 'updated_by'], 'integer'],
            [['percentage', 'cgpa'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['grade'], 'string', 'max' => 10],
            [['classification'], 'string', 'max' => 300],
            [['batch_maping_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoeBatDegReg::className(), 'targetAttribute' => ['batch_maping_id' => 'coe_bat_deg_reg_id']],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoeStudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
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
            'coe_consolidate_marks_id' => 'Coe Consolidate Marks ID',
            'batch_maping_id' => 'Batch Maping ID',
            'student_map_id' => 'Student Map ID',
            'part_no' => 'Part No',
            'part_credits' => 'Part Credits',
            'marks_gain' => 'Marks Gain',
            'marks_total' => 'Marks Total',
            'percentage' => 'Percentage',
            'cgpa' => 'Cgpa',
            'grade' => 'Grade',
            'classification' => 'Classification',
            'part_add_credits' => 'Part Add Credits',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchMaping()
    {
        return $this->hasOne(CoeBatDegReg::className(), ['coe_bat_deg_reg_id' => 'batch_maping_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentMap()
    {
        return $this->hasOne(CoeStudentMapping::className(), ['coe_student_mapping_id' => 'student_map_id']);
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
}
