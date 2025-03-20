<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coe_del_qualent_subjects}}".
 *
 * @property integer $coe_del_qualent_subjects_id
 * @property integer $stu_map_id
 * @property integer $sub_map_id
 * @property string $created_at
 * @property integer $created_by
 *
 * @property CoeStudentMapping $stuMap
 * @property CoeSubjectsMapping $subMap
 * @property User $createdBy
 */
class DelQualentSubjects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_del_qualent_subjects}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stu_map_id', 'sub_map_id', 'created_by'], 'required'],
            [['stu_map_id', 'sub_map_id', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['stu_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['stu_map_id' => 'coe_student_mapping_id']],
            [['sub_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['sub_map_id' => 'coe_subjects_mapping_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_del_qualent_subjects_id' => 'Coe Del Qualent Subjects ID',
            'stu_map_id' => 'Stu Map ID',
            'sub_map_id' => 'Sub Map ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStuMap()
    {
        return $this->hasOne(StudentMapping::className(), ['coe_student_mapping_id' => 'stu_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubMap()
    {
        return $this->hasOne(SubjectsMapping::className(), ['coe_subjects_mapping_id' => 'sub_map_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
