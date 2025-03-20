<?php
namespace app\models;
use yii\helpers\ArrayHelper;
use Yii;
use app\models\Department;
/**
 * This is the model class for table "coe_valuation_scrutiny".
 *
 * @property integer $coe_scrutiny_id
 * @property string $name
 * @property integer $designation
 * @property integer $department
 * @property integer $phone_no
 * @property string $email
 * @property string $bank_accno
 * @property string $bank_ifsc
 * @property string $bank_name
 * @property string $bank_branch
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class Scrutiny extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_valuation_scrutiny';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'designation', 'department', 'phone_no', 'email', 'bank_accno', 'bank_ifsc', 'bank_name', 'bank_branch',], 'required'],
            [['designation', 'department', 'phone_no', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'bank_accno', 'bank_name', 'bank_branch'], 'string', 'max' => 100],
            [['bank_ifsc'], 'string', 'max' => 50],
            [['phone_no'], 'string', 'max' => 10],
            [['phone_no','email','bank_accno'], 'unique'],
            [['email'], 'unique', 'targetAttribute' => ['email'], 'message' => 'The Email address has already been taken.'],
            ['name', 'match', 'pattern' => '/^[a-zA-Z\s.]+$/', 'message' => 'Name can only contain letters, spaces, and periods.'],
            ['phone_no', 'match', 'pattern' => '/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/', 'message' => 'Invalid phone number.(eg:9876543210)'],
            [['phone_no'], 'number', 'max' => 9999999999],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_scrutiny_id' => 'Coe Scrutiny ID',
            'name' => 'Name',
            'designation' => 'Designation',
            'department' => 'Department',
            'phone_no' => 'Phone No',
            'email' => 'Email',
            'bank_accno' => 'Bank Accno',
            'bank_ifsc' => 'Bank Ifsc',
            'bank_name' => 'Bank Name',
            'bank_branch' => 'Bank Branch',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
    public function getDepartmentData()
    {
        $programmes = Yii::$app->db->createCommand('SELECT coe_dept_id,dept_code FROM cur_department ORDER BY  dept_code')->queryAll();
        return ArrayHelper::map($programmes, 'coe_dept_id', 'dept_code');
    }
    public function getDesignationData()
    {
        $query = 'SELECT coe_category_type_id, category_type FROM coe_category_type WHERE category_id = 35 ORDER BY category_type';
        $programmes = Yii::$app->db->createCommand($query)->queryAll();
        return ArrayHelper::map($programmes, 'coe_category_type_id', 'category_type');
    }
    public function getDepartmentName()
    {
        return $this->hasOne(Department::className(), ['coe_dept_id' => 'department'])->alias('department_name');
    }
    public function getDesignationName()
    {
        return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'designation'])->alias('designation');
    }
}
