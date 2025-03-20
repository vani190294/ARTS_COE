<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
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

/**
 * This is the model class for table "cur_lab_component".
 *
 * @property integer $cur_labcomp_id
 * @property integer $cur_syllabus_id
 * @property string $experiment_title
 * @property integer $cource_outcome
 * @property string $rpt
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class CurLabComponent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_lab_component';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_syllabus_id', 'experiment_title', 'cource_outcome'], 'required'],
            [['cur_syllabus_id',  'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['experiment_title'], 'string', 'max' => 300],
            //[['cource_outcome'], 'string', 'max' => 11],
            [['rpt'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_labcomp_id' => 'Cur Labcomp ID',
            'cur_syllabus_id' => 'Cur Syllabus ID',
            'experiment_title' => 'Experiment Title',
            'cource_outcome' => 'Cource Outcome',
            'rpt' => 'RBT',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getCourceOutcome($cur_syllabus_id)
    {
        
        $codata = Yii::$app->db->createCommand("SELECT * FROM cur_syllabus WHERE cur_syllabus_id=".$cur_syllabus_id)->queryOne();
        $coarray = array('CO1' => $codata['course_outcomes1'],'CO2' => $codata['course_outcomes2'],'CO3' => $codata['course_outcomes3'],'CO4' => $codata['course_outcomes4'],'CO5' => $codata['course_outcomes5'],'CO6' => $codata['course_outcomes6'] );
        
        $coarraydata=array();
        foreach ($coarray as $key => $value) 
        {
            if($value!='')
            {
                $coarraydata[]=array('coid'=>$key,'cocontent'=>$value);
            }
            
        }
        //print_r($coarraydata); exit();
        return  ArrayHelper::map($coarraydata,'coid','coid');
    }
}
