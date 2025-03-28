<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%coe_regulation}}".
 *
 * @property integer $coe_regulation_id
 * @property integer $coe_batch_id
 * @property integer $regulation_year
 * @property double $grade_point_from
 * @property double $grade_point_to
 * @property string $grade_name
 * @property integer $grade_point
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property Batch $Batch
 * @property User $createdBy
 * @property User $updatedBy
 */
class Regulation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_regulation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_batch_id', 'regulation_year'], 'required'],
            [['coe_batch_id', 'regulation_year', 'grade_point', 'created_by', 'updated_by'], 'integer'],
            [['grade_point_from', 'grade_point_to'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['grade_name'], 'string', 'max' => 10],
            [['grade_name'], 'match' ,'pattern'=> '/^[A-Za-z+]+$/u','message'=> 'Enter valid Grade Name.'],
            [['coe_batch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Batch::className(), 'targetAttribute' => ['coe_batch_id' => 'coe_batch_id']],
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
            'coe_regulation_id' => 'Coe Regulation ID',
            'coe_batch_id' => 'Coe Batch ID',
            'regulation_year' => 'Regulation Year',
            'grade_point_from' => 'Grade Point From',
            'grade_point_to' => 'Grade Point To',
            'grade_name' => 'Grade Name',
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
    public function getCoeBatch()
    {
        return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id'])->alias('coe_bat_rel');
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
    public function findModel($id)
    {
        if (($model = Regulation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
