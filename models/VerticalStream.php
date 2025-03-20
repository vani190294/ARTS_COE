<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

use yii\helpers\ArrayHelper;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Batch;
use app\models\CoeBatDegReg;
use app\models\Department;
use app\models\Categories;
use app\models\Categorytype;
use app\models\Regulation;
use app\models\Degree;
use app\models\LTP;
use app\models\AicteNorms;

/**
 * This is the model class for table "cur_vertical_stream".
 *
 * @property integer $cur_vs_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $vertical_name
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class VerticalStream extends \yii\db\ActiveRecord
{
    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
     const TYPE_MBA= 'MBA';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_vertical_stream';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vertical_type', 'vertical_count', 'coe_batch_id', 'coe_regulation_id', 'coe_dept_id', 'vertical_name'], 'required'],
            [['coe_regulation_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['degree_type'], 'string', 'max' => 10],
            [['vertical_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_vs_id' => 'Cur Vs ID',
            'degree_type' => 'Degree Type',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_dept_id' => 'Dept.',
            'vertical_name' => 'Vertical Name',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function getDept()
    { 
         return $this->hasOne(Department::className(), ['coe_dept_id' => 'coe_dept_id']);
    }

     public function getBatch()
    { 
         return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id']);
    }

    public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

     public function getDepartmentdetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }
    public static function getDegreeType()
    {
        return [
        Yii::t('app', self::TYPE_UG) => Yii::t('app', 'UG'),
        Yii::t('app', self::TYPE_PG) => Yii::t('app', 'PG'), 
        Yii::t('app', self::TYPE_MBA) => Yii::t('app', 'MBA'), 
        ];  
    }

    public function getRegulationDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE regulation_year >=2023 GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }

    public function getDeptassignlist()
    { 
        $dept_codes =''; 
        if(!empty($this->coe_dept_id))
        {
            $depts_id=' WHERE coe_dept_id IN ('.$this->coe_dept_id.')';
             $qdata = Yii::$app->db->createCommand("SELECT dept_code FROM cur_department ". $depts_id)->queryAll();

             $deptcodes =array();
             foreach ($qdata as $value) 
             {
                $deptcodes[]=$value['dept_code'];
             }

            $dept_codes=implode(",", $deptcodes);
             
        }
         //print_r($dept_codes); exit();
        $deptdata = array();
        $deptdata['depts']=$dept_codes;

         return $deptdata;
    }
}
