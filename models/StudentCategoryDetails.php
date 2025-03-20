<?php

namespace app\models;

use Yii;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\models\Categorytype;
/**
 * This is the model class for table "{{%coe_student_category_details}}".
 *
 * @property integer $coe_student_category_details_id
 * @property integer $student_map_id
 * @property string $old_clg_reg_no
 * @property string $subject_code
 * @property string $subject_name
 * @property integer $credit_point
 * @property integer $CIA
 * @property integer $ESE
 * @property integer $total
 * @property string $result
 * @property integer $grade_point
 * @property string $grade_name
 * @property integer $year
 * @property integer $month
 * @property string $year_of_passing
 * @property integer $stu_status_id
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property CoeStudentMapping $studentMap
 * @property CoeCategoryType $stuStatus
 * @property User $createdBy
 * @property User $updatedBy
 * @property CoeCategoryType $month0
 */
class StudentCategoryDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_student_category_details}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_map_id', 'old_clg_reg_no', 'subject_code', 'subject_name', 'credit_point', 'result', 'grade_point', 'grade_name','CIA', 'ESE', 'total', 'semester','stu_status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['student_map_id', 'credit_point', 'CIA', 'ESE', 'total', 'grade_point', 'year', 'stu_status_id','semester', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['old_clg_reg_no', 'subject_code', 'subject_name', 'result', 'grade_name', 'year_of_passing','gpa', 'month'], 'string', 'max' => 45],
            [['student_map_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentMapping::className(), 'targetAttribute' => ['student_map_id' => 'coe_student_mapping_id']],
            [['stu_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['stu_status_id' => 'coe_category_type_id']],
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
            'coe_student_category_details_id' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Category Details'),
            'student_map_id' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)),
            'old_clg_reg_no' => strtoupper('Previous College Reg No'),
            'subject_code' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code'),
            'subject_name' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name'),
            'credit_point' => strtoupper('Credit Point'),
            'CIA' => strtoupper('Cia'),
            'ESE' => strtoupper('Ese'),
            'total' => strtoupper('Total'),
            'result' => strtoupper('Result'),
            'grade_point' => strtoupper('Grade Point'),
            'grade_name' => strtoupper('Grade Name'),
            'semester' => strtoupper('Semester'),
            'gpa' => strtoupper('Gpa'),
            'year' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Year'),
            'month' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month'),
            'year_of_passing' => strtoupper('Year Of Passing'),
            'stu_status_id' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Status'),
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentMap()
    {   

       return $this->hasOne(StudentMapping::className(), ['coe_student_mapping_id' => 'student_map_id']); 
    }
    public function getStudentDetails()
    {

        return $this->hasOne(Student::className(), ['coe_student_id'=>'student_rel_id'])->via('studentMap');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStuStatus()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'stu_status_id']);
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

   
    public function getStudentStatus()
    {
        
        $query = 'SELECT A.coe_category_type_id,A.category_type FROM coe_category_type AS A JOIN coe_categories AS B ON B.coe_category_id =A.category_id WHERE B.category_name LIKE "'.ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_STUDENT_CATEGORY).'" AND A.description like "%Transfer%" order by A.category_type ';
        $stu_categories = Yii::$app->db->createCommand($query)->queryAll();
        return  $stu_categories = ArrayHelper::map($stu_categories,'coe_category_type_id','category_type');
    }
    
    
}
