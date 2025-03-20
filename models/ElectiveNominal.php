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
use app\models\SubjectPrefix;
use app\models\AicteNorms;
/**
 * This is the model class for table "cur_elective_nominal".
 *
 * @property integer $cur_nominal_id
 * @property integer $cur_erss_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property integer $coe_dept_id
 * @property string $coe_elective_option
 * @property string $elective_paper
 * @property string $register_number
 * @property string $subject_code
 * @property integer $semester
 * @property integer $approve_status
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class ElectiveNominal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_elective_nominal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_erss_id', 'batch_map_id', 'degree_type', 'coe_batch_id', 'coe_regulation_id', 'coe_dept_id', 'coe_elective_option', 'elective_paper', 'register_number', 'subject_code', 'semester', 'approve_status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['cur_erss_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 10],
            [['coe_elective_option', 'elective_paper', 'subject_code'], 'string', 'max' => 50],
            //[['register_number'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_nominal_id' => 'Cur Nominal ID',
            'cur_erss_id' => 'Cur ERSS ID',
            'degree_type' => 'Degree Type',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'coe_dept_id' => 'Coe Dept ID',
            'coe_elective_option' => 'Coe Elective Option',
            'elective_paper' => 'Elective Paper',
            'register_number' => 'Register Number',
            'subject_code' => 'Subject Code',
            'semester' => 'Semester',
            'approve_status' => 'Approve Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    
}
