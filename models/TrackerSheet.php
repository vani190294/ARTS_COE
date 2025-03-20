<?php

namespace app\models;

use Yii;

use yii\helpers\ArrayHelper;
use app\models\Categories;
use app\models\Categorytype;

/**
 * This is the model class for table "coe_tracker_sheet".
 *
 * @property integer $coe_ts_id
 * @property string $task_tittle
 * @property string $task_description
 * @property string $priority
 * @property string $date
 * @property string $task_type
 * @property string $remark
 * @property string $status
 * @property string $developed_by
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class TrackerSheet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_tracker_sheet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_tittle', 'task_description', 'priority', 'date', 'task_type'], 'required'],
            [['task_description'], 'string'],
            [['date','created_at','update_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['task_tittle', 'priority', 'task_type', 'remark', 'status','developed_by'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_ts_id' => 'Coe Ts ID',
            'task_tittle' => 'Task Tittle',
            'task_description' => 'Task Description',
            'priority' => 'Priority',
            'date' => 'Date',
            'task_type' => 'Task Type',
            'remark' => 'Remark',
            'status' => 'Status',
            'developed_by'=> 'Developer',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

 /**
     * @return \yii\db\ActiveQuery
     */



 public function getPriorityData()
    {
      return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'priority']);
    }
 public function getPriority()
    {
     
      $priority = Categories::find()->where(['category_name' => 'priority' ])->one();
      $s_id = $priority['coe_category_id'];

      $priority = Categorytype::find()->where(['category_id' => $s_id])->all();
      $vals = ArrayHelper::map($priority,'coe_category_type_id','category_type');
      return $vals;
    }     


 public function getStatusData()
    {
      return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'status']);
    }
 public function getStatus()
    {
     
      $status = Categories::find()->where(['category_name' => 'status' ])->one();
      $s_id = $status['coe_category_id'];

      $status = Categorytype::find()->where(['category_id' => $s_id])->all();
      $vals = ArrayHelper::map($status,'coe_category_type_id','category_type');
      return $vals;
    }  

 public function getTaskType()
    {
    return $this->hasOne(Categorytype::className(), ['coe_category_type_id'=>'task_type']);
    }
  public function getTask_type()
    {
     
      $task_type = Categories::find()->where(['category_name' => 'task_type' ])->one();
      $s_id = $task_type['coe_category_id'];

      $task_type = Categorytype::find()->where(['category_id' => $s_id])->all();
      $vals = ArrayHelper::map($task_type,'coe_category_type_id','category_type');
      return $vals;
    }

}

