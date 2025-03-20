<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coe_hall_master}}".
 *
 * @property integer $coe_hall_master_id
 * @property string $hall_name
 * @property string $description
 * @property integer $hall_type_id
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property CoeHallAllocate[] $coeHallAllocates
 * @property CoeCategoryType $hallType
 * @property User $createdBy
 * @property User $updatedBy
 */
class HallMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_hall_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hall_name', 'description', 'hall_type_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['hall_type_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at','hall_type_id'], 'safe'],
            [['hall_name', 'description'], 'string', 'max' => 45],
            [['hall_name', 'description'], 'unique', 'targetAttribute' => ['hall_name', 'description'], 'message' => 'The combination of Hall Name and Description has already been taken.'],
            [['hall_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryType::className(), 'targetAttribute' => ['hall_type_id' => 'coe_category_type_id']],
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
            'coe_hall_master_id' => 'Coe Hall Master ID',
            'hall_name' => 'Hall Name',
            'description' => 'Description',
            'hall_type_id' => "Hall Type",
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeHallAllocates()
    {
        return $this->hasMany(HallAllocate::className(), ['hall_master_id' => 'coe_hall_master_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHallType()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'hall_type_id'])->alias('hallType');
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
