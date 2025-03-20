<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\CurriculumSubject;
use app\models\Batch;
use app\models\CoeBatDegReg;
use app\models\Department;
use app\models\Categories;
use app\models\Categorytype;
use app\models\Regulation;
use app\models\Degree;
use app\models\LTP;
use app\models\SubjectPrefix;
use app\models\ElectiveSubject;

/**
 * This is the model class for table "cur_servicesubtodept".
 *
 * @property integer $coe_servtodept_id
 * @property integer $coe_cur_subid
 * @property string $coe_dept_ids
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class Servicesubjecttodept extends \yii\db\ActiveRecord
{
     const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
     const TYPE_MBA= 'MBA';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_servicesubtodept';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['degree_type','coe_regulation_id','coe_cur_subid','semester','coe_dept_ids'],'required'],
            [['coe_cur_subid', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            //[['coe_dept_ids'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_servtodept_id' => 'Coe Servtodept ID',
            'coe_regulation_id' => 'Regulation',
            'semester'=>'Semester',
            'coe_cur_subid' => 'Course',
            'coe_dept_ids' => 'Depts',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

     public function getDepartmentdetails()
    {
        $deptall = Yii::$app->db->createCommand("SELECT coe_dept_id,dept_code FROM cur_department WHERE coe_dept_id!=8")->queryAll();
        return  ArrayHelper::map($deptall,'coe_dept_id','dept_code');
        
    }

    public function getSubjectassinged()
    { 
         return $this->hasOne(CurriculumSubject::className(), ['coe_cur_id' => 'coe_cur_subid']);
       
    }

     public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

    public function getDeptassignlist()
    { 
         return $this->hasOne(Department::className(), ['coe_dept_id' => 'coe_dept_ids']);
    }

    // public function getDeptassignlist()
    // { 
    //     $dept_codes =''; 
    //     if(!empty($this->coe_dept_ids))
    //     {
    //         $depts_id=' WHERE coe_dept_id IN ('.$this->coe_dept_ids.')';
    //          $qdata = Yii::$app->db->createCommand("SELECT dept_code FROM cur_department ". $depts_id)->queryAll();

    //          $deptcodes =array();
    //          foreach ($qdata as $value) 
    //          {
    //             $deptcodes[]=$value['dept_code'];
    //          }

    //         $dept_codes=implode(",", $deptcodes);
             
    //     }
       
    //     $deptdata = array();
    //     $deptdata['depts']=$dept_codes;
    //      return $deptdata;
    // }

    public function getCurSubjectDetails()
    {
        $subjectids = Yii::$app->db->createCommand("SELECT distinct coe_cur_id,subject_code FROM cur_curriculum_subject WHERE coe_dept_id='8' ORDER BY subject_code")->queryAll();
       
        return  ArrayHelper::map($subjectids,'coe_cur_id','subject_code');
        
    }

     public function getSemester()
    { 
        
        $semester =[];
        for ($i=1; $i <=8 ; $i++) 
        { 
            
            $semester[$i]=$i;
        }
               
         return $semester;
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
         $deptall = Yii::$app->db->createCommand("SELECT coe_regulation_id,regulation_year FROM coe_regulation GROUP BY regulation_year ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }

    public function getBatchname()
    {
        $coe_regulation_id=$this->coe_regulation_id;

        $batch_name = Yii::$app->db->createCommand("SELECT B.batch_name FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE A.coe_regulation_id=".$coe_regulation_id)->queryScalar();

        $batch_name1 = array();
         $batch_name1['batch_name']=$batch_name;
        return  $batch_name1;
    }
}
