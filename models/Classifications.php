<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\CoeBatDegReg;
/**
 * This is the model class for table "{{%coe_classifications}}".
 *
 * @property integer $coe_classifications_id
 * @property integer $regulation_year
 * @property double $percentage_from
 * @property double $percentage_to
 * @property string $grade_name
 * @property string $classification_text
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Classifications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_classifications}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regulation_year', 'percentage_from', 'percentage_to', 'grade_name', 'classification_text'], 'required'],
            [['regulation_year', 'created_by', 'updated_by'], 'integer'],
            [['percentage_from', 'percentage_to'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['grade_name'], 'string', 'max' => 5],
            [['classification_text'], 'string', 'max' => 255],
            [['regulation_year', 'percentage_from', 'percentage_to'], 'unique', 'targetAttribute' => ['regulation_year', 'percentage_from', 'percentage_to'], 'message' => 'The combination of Regulation Year, Percentage From and Percentage To has already been taken.'],
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
            'coe_classifications_id' => 'Coe Classifications ID',
            'regulation_year' => 'Regulation Year',
            'percentage_from' => 'Percentage From',
            'percentage_to' => 'Percentage To',
            'grade_name' => 'Grade Name',
            'classification_text' => 'Classification Text',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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
    public function getRegulations()
    {
        $regulation_list = CoeBatDegReg::find()->groupBy('regulation_year')->all();
        $vals1 = ArrayHelper::map($regulation_list,'regulation_year','regulation_year');
        return $vals1;
    }
}
