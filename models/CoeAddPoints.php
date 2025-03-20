<?php

namespace app\models;
use yii\helpers\ArrayHelper;


use Yii;

/**
 * This is the model class for table "coe_add_points".
 *
 * @property integer $id
 * @property string $subject_code
 * @property string $subject_name
 * @property string $activity_points
 */
class CoeAddPoints extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_add_points';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          [['subject_code', 'subject_name',  'created_by'], 'required'],
            
            [['subject_code', 'subject_name'], 'string', 'max' => 500],
             //[[ 'activity_points'], 'integer'],
              [[ 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject_code' => 'Subject Code',
            'subject_name' => 'Subject Name',
           //'activity_points' => 'Activity Points',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
    public function getsub(){
       
        $batch = CoeAddPoints::find()->orderBy(['subject_code'=>SORT_ASC])->all();
        return  $batch_list = ArrayHelper::map($batch,'id','subject_code');
    }
}
