<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coe_equalent_subjects}}".
 *
 * @property integer $coe_equalent_subjects_id
 * @property integer $prev_stu_map_id
 * @property integer $prev_sub_map_id
 * @property integer $new_stu_map_id
 * @property integer $new_sub_map_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property CoeStudentMapping $newStuMap
 * @property CoeSubjectsMapping $newSubMap
 * @property CoeStudentMapping $prevStuMap
 * @property CoeSubjectsMapping $prevSubMap
 * @property User $createdBy
 * @property User $updatedBy
 */
class EqualentSubjects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_equalent_subjects}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prev_stu_map_id', 'prev_sub_map_id', 'new_stu_map_id', 'new_sub_map_id', 'created_by', 'updated_by'], 'required'],
            [['prev_stu_map_id', 'prev_sub_map_id', 'new_stu_map_id', 'new_sub_map_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['new_stu_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['new_stu_map_id' => 'coe_student_mapping_id']],
            [['new_sub_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['new_sub_map_id' => 'coe_subjects_mapping_id']],
            [['prev_stu_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['prev_stu_map_id' => 'coe_student_mapping_id']],
            [['prev_sub_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['prev_sub_map_id' => 'coe_subjects_mapping_id']],
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
            'coe_equalent_subjects_id' => 'Coe Equalent Subjects ID',
            'prev_stu_map_id' => 'Prev Stu Map ID',
            'prev_sub_map_id' => 'Prev Sub Map ID',
            'new_stu_map_id' => 'New Stu Map ID',
            'new_sub_map_id' => 'New Sub Map ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewStuMap()
    {
        return $this->hasOne(StudentMapping::className(), ['coe_student_mapping_id' => 'new_stu_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewSubMap()
    {
        return $this->hasOne(SubjectsMapping::className(), ['coe_subjects_mapping_id' => 'new_sub_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrevStuMap()
    {
        return $this->hasOne(StudentMapping::className(), ['coe_student_mapping_id' => 'prev_stu_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrevSubMap()
    {
        return $this->hasOne(SubjectsMapping::className(), ['coe_subjects_mapping_id' => 'prev_sub_map_id']);
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
