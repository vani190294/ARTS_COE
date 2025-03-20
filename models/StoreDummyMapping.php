<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coe_store_dummy_mapping}}".
 *
 * @property integer $coe_store_dummy_mapping
 * @property integer $subject_map_id
 * @property integer $year
 * @property integer $month
 * @property integer $dummy_from
 * @property integer $dummy_to
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property CoeCategoryType $month0
 * @property CoeSubjectsMapping $subjectMap
 * @property User $createdBy
 * @property User $updatedBy
 */
class StoreDummyMapping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_store_dummy_mapping}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject_map_id', 'year', 'month', 'dummy_from', 'dummy_to', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['subject_map_id', 'year', 'month', 'dummy_from', 'dummy_to', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['month'], 'exist', 'skipOnError' => true, 'targetClass' =>Categorytype::className(), 'targetAttribute' => ['month' => 'coe_category_type_id']],
            [['subject_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubjectsMapping::className(), 'targetAttribute' => ['subject_map_id' => 'coe_subjects_mapping_id']],
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
            'coe_store_dummy_mapping' => 'Coe Store Dummy Mapping',
            'subject_map_id' => 'Subject Map ID',
            'year' => 'Year',
            'month' => 'Month',
            'dummy_from' => 'Dummy From',
            'dummy_to' => 'Dummy To',
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
    public function getSubjectMap()
    {
        return $this->hasOne(SubjectsMapping::className(), ['coe_subjects_mapping_id' => 'subject_map_id']);
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
