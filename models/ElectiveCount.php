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
 * This is the model class for table "cur_elective_count".
 *
 * @property integer $cur_ec_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property string $coe_dept_id
 * @property integer $elective_type
 * @property string $elective_count
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class ElectiveCount extends \yii\db\ActiveRecord
{
      const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_elective_count';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['degree_type','coe_batch_id', 'coe_regulation_id', 'coe_dept_id', 'elective_type', 'elective_count'], 'required'],
            [['coe_regulation_id', 'elective_type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['degree_type'], 'string', 'max' => 10],
            [['coe_dept_id'], 'string', 'max' => 11],
            [['elective_count'], 'string', 'max' => 20],
            [['degree_type', 'coe_regulation_id', 'coe_dept_id'], 'unique', 'targetAttribute' => ['degree_type', 'coe_regulation_id', 'coe_dept_id'], 'message' => 'The combination of Degree Type, Coe Regulation ID and Coe Dept ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_ec_id' => 'Cur Ec ID',
            'coe_batch_id' => 'Batch',
            'degree_type' => 'Degree Type',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_dept_id' => 'Dept.',
            'elective_type' => 'Elective Type',
            'elective_count' => 'Elective Count',
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

     public function getElectivetype()
    { 
         return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'elective_type']);
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
        $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE regulation_year >=2021 GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
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

    public function getElectivetypeDetails1()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,description FROM coe_category_type WHERE category_id=27 AND description IN ('PEC','OEC','EEC')")->queryAll();
        return  ArrayHelper::map($deptall,'coe_category_type_id','description');
    }
}
