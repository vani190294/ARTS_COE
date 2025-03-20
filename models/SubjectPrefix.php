<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

use yii\helpers\ArrayHelper;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

// Course Information
use app\models\Batch;
use app\models\CoeBatDegReg;
use app\models\Department;
use app\models\Categories;
use app\models\Categorytype;

/**
 * This is the model class for table "cur_subject_prefix".
 *
 * @property integer $coe_prefix_id
 * @property integer $coe_dept_id
 * @property string $prefix_name
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class SubjectPrefix extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_subject_prefix';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_dept_id', 'prefix_name', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['coe_dept_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['prefix_name'], 'string', 'max' => 10],
            [['coe_dept_id', 'prefix_name'], 'unique', 'targetAttribute' => ['coe_dept_id', 'prefix_name'], 'message' => 'The combination of Coe Dept ID and Prefix Name has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_prefix_id' => 'Coe Prefix ID',
            'coe_dept_id' => 'Department',
            'prefix_name' => 'Course Prefix',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    public function getDept()
    { 
         return $this->hasOne(Department::className(), ['coe_dept_id' => 'coe_dept_id']);
    }

     public function getDepartmentdetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }
}
