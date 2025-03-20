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
 * This is the model class for table "cur_dept_pso".
 *
 * @property integer $cur_vs_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property string $coe_dept_id
 * @property string $pso_title
 * @property integer $no_of_pso
 * @property integer $approve_status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class DeptPso extends \yii\db\ActiveRecord
{
     const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
     const TYPE_MBA= 'MBA';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_dept_pso';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['degree_type', 'coe_regulation_id', 'coe_dept_id', 'no_of_pso', 'approve_status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['coe_regulation_id', 'no_of_pso', 'approve_status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['degree_type'], 'string', 'max' => 10],
            [['coe_dept_id'], 'string', 'max' => 11],
            [['pso_title'], 'string', 'max' => 255],
            [['degree_type', 'coe_regulation_id', 'coe_dept_id', 'pso_title'], 'unique', 'targetAttribute' => ['degree_type', 'coe_regulation_id', 'coe_dept_id', 'pso_title'], 'message' => 'The combination of Degree Type, Coe Regulation ID, Coe Dept ID and Pso Title has already been taken.'],
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
            'coe_regulation_id' => 'Regulation',
            'coe_dept_id' => 'Dept',
            'pso_title' => 'PSO Title',
            'no_of_pso' => 'No. of Pso',
            'approve_status' => 'Approve Status',
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

    public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

     public function getDepartmentdetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }

    public function getBatchname()
    {
        $coe_regulation_id=$this->coe_regulation_id;

        $batch_name = Yii::$app->db->createCommand("SELECT B.batch_name FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE A.coe_regulation_id=".$coe_regulation_id)->queryScalar();

        $batch_name1 = array();
         $batch_name1['batch_name']=$batch_name;
        return  $batch_name1;
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
}
