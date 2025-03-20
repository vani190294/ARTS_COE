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

use app\models\Categories;
use app\models\Categorytype;

/**
 * This is the model class for table "coe_student".
 *
 * @property integer $coe_student_id
 * @property string $name
 * @property string $register_number
 * @property string $gender
 * @property string $abc_number_id
 * @property string $dob
 * @property string $religion
 * @property string $nationality
 * @property string $caste
 * @property string $sub_caste
 * @property string $bloodgroup
 * @property string $email_id
 * @property string $admission_year
 * @property string $admission_date
 * @property string $mobile_no
 * @property string $admission_status
 * @property string $aadhar_number
 * @property string $student_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property CoeNominal[] $coeNominals
 * @property CoeGuardian[] $coeGuardians
 * @property CoeStuAddress[] $coeStuAddresses
 * @property CoeStudentMapping[] $coeStudentMappings
 * @property StudentMappings[] $course_batch_mapping_id
 * @property CoeDegree[] $coeDegree
 * @property CoeProgramme[] $coeProgramme
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const TYPE_UGCBCS = 'UG CBCS';
    const TYPE_PGCBCS = 'PG CBCS';
    const TYPE_NONCBCS = 'Non-CBCS';
    

    public $stu_programme_id,$stu_batch_id,$stu_section_name,$app_year,$app_month,$register_number_from,$register_number_to,$exam_type,$stu_cbcs;
    public static function tableName()
    {
        return 'coe_student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'register_number', 'gender', 'app_month','dob', 'religion', 'caste', 'bloodgroup', 'admission_year','admission_date','email_id','exam_type'], 'required'],
            [['dob', 'admission_year', 'admission_date','aadhar_number','student_status','exam_type'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['gender', 'religion', 'nationality', 'caste', 'sub_caste', 'bloodgroup', 'mobile_no', 'admission_status'], 'string', 'max' => 45,],
            [['aadhar_number'],'integer', 'min' => 100000000000, 'max'=>999999999999,'message'=> 'Aadhar Number Must be in integer Only.','tooSmall'=>'Should be 12 digit long.(Example: 123456789012)' , 'tooBig' => 'Should be 12 digit long and Maximum 999999999999.(Example: 123456789012)'],
            [['mobile_no'],'integer', 'min' => 1000000000, 'max'=>9999999999,'message'=> 'Mobile Number Must be in integer Only.','tooSmall'=>'Should be 10 digit long.(Example: 1234567890)' , 'tooBig' => 'Should be 10 digit long and Maximum 9999999999.(Example: 1234567890)'],
            [['email_id'], 'email', ],
            [['register_number','email_id','mobile_no','abc_number_id'], 'unique'],
            [['stu_programme_id','stu_batch_id','created_by', 'updated_by'], 'integer'],
            //[['stu_section_name'], 'string','max' => 1],
            [['register_number', 'aadhar_number'], 'unique', 'targetAttribute' => ['register_number', 'aadhar_number'], 'message' => 'The combination of Register Number and Aadhar Number has already been taken.'],
            [['stu_batch_id','stu_programme_id','stu_section_name','admission_status'],'required'],
            [['admission_year','aadhar_number','mobile_no'],'integer','integerPattern'=>'/^\s*[+-]?\d+\s*$/'],
            [['religion','caste', 'sub_caste','nationality',],'match', 'pattern' => '/^[a-zA-Z\s]+\w*$/i',],
            [['name'],'match', 'pattern' => '/^[a-zA-Z\s .]+\w*$/i',],
            // Custom Variables 
            [['stu_programme_id','stu_batch_id','created_at', 'updated_at'], 'safe'],
            [['app_year'],'integer',],
            [['register_number_to','register_number_from','stu_cbcs'],'safe',],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_student_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
            'name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Name',
            'register_number' => 'Register Number',
            'gender' => 'Gender',   
            'dob' => 'Dob',
            'religion' => 'Religion',
            'nationality' => 'Nationality',
            'caste' => 'Caste',
            'sub_caste' => 'Sub Caste',
            'bloodgroup' => 'Bloodgroup',
            'email_id' => 'Email Address',
            'admission_year' => 'Admission Year',
            'admission_date' => 'Admission Date',
            'abc_number_id' => 'Abc Number ID',
            'mobile_no' => 'Mobile Number',
            'admission_status' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT_CATEGORY),
            'student_status' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Status Active/in-Active',
            'aadhar_number' => 'Aadhar Number',
            'stu_section_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION),
            'stu_batch_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
            'stu_programme_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." Name",
            'app_year' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Year',
            'app_month' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month',
            'exam_type' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE),
            'stu_cbcs' => "Statement Type",
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeNominals()
    {
        return $this->hasMany(Nominal::className(), ['coe_student_id' => 'coe_student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeGuardians()
    {

        return $this->hasMany(Guardian::className(), ['stu_guardian_id' => 'coe_student_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeStuAddresses()
    {
        return $this->hasMany(StuAddress::className(), ['stu_address_id' => 'coe_student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeStudentMappings()
    {
    return $this->hasMany(StudentMapping::className(), ['student_rel_id' => 'coe_student_id']);
    }
    public function getCourse_batch_mapping_id()
    {
        return $this->hasMany(StudentMapping::className(), ['student_rel_id' => 'coe_student_id']);
    }
    /**
     * Custom Functions for Index Page
     */
   
    public function getCoeGuardianNames()
    {
        return $this->hasOne(Guardian::className(), ['stu_guardian_id' => 'coe_student_id']);
    }
    public function getSectionname()
    {
         return $this->hasOne(StudentMapping::className(), ['student_rel_id' => 'coe_student_id']);
    }
    public function getAdmissionname()
    {
         return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'admission_status']);
    }
    public function getStuadmission()
    {
         return $this->hasOne(StudentMapping::className(), ['student_rel_id' => 'coe_student_id'])->via('admissionname');
    }

    public function getCourseBatchMapping()
    {
        return $this->hasMany(CoeBatDegReg::className(), ['coe_bat_deg_reg_id'=>'course_batch_mapping_id'])->alias('coe_bat_rel')->via('coeStudentMappings');
    }
    public function getCoeBatch()
    {
        return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id'])->via('courseBatchMapping');
    }
    public function getCoeDegree()
    { 
         return $this->hasOne(Degree::className(), ['coe_degree_id' => 'coe_degree_id'])->via('courseBatchMapping');
    }
    public function getCoeProgramme()
    {
        return $this->hasOne(Programme::className(), ['coe_programme_id' => 'coe_programme_id'])->via('courseBatchMapping');
    }

    public function getCoeBatchName()
    {
        return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id'])->via('coeBatch');
    }
    public function getCoeDegreeName()
    { 
         return $this->hasOne(Degree::className(), ['coe_degree_id' => 'coe_degree_id'])->via('coeDegree');
    }
    public function getCoeProgrammeName()
    {
        return $this->hasOne(Programme::className(), ['coe_programme_id' => 'coe_programme_id'])->via('coeProgramme');
    }
   
    /**
     * Custom Functions for Index Page End Here 
     */
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchDetails()
    {
        $batch = Batch::find()->orderBy(['batch_name'=>SORT_ASC])->all();
        return  $batch_list = ArrayHelper::map($batch,'coe_batch_id','batch_name');
    }

    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountryList()
    {
        $list = StuAddress::find()->select('current_country')->orderBy(['current_country'=>SORT_ASC])->DISTINCT('current_country')->all();         
        $CountriesList = [
            'United States of America', 'India','United Kingdom',
        ];
        $countryList = array();
        if(!empty($list) && count($list)>0)
        {
            foreach ($list as $key => $value) {
                if(in_array($value['current_country'], $CountriesList)!==true)
                {
                    $countryList[$value['current_country']]=$value['current_country'];
                }
            }
        }
        $CountriesList = array_filter(array_merge($countryList,$CountriesList));
        return  $CountriesList;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatesList()
    {
        $list = StuAddress::find()->select('current_state')->DISTINCT('state_name')->all();
           
        $StatesList = [
            'Andhra Pradesh', 'Tamil Nadu','Karnatak',
        ];
        $stateList = array();
        if(!empty($list) && count($list)>0)
        {
            foreach ($list as $key => $value) {
                if(in_array($value['current_state'], $StatesList)!==true)
                {
                    $stateList[$value['current_state']]=$value['current_state'];
                }
            }
        }
        $StatesList = array_filter(array_merge($stateList,$StatesList));
        return  $StatesList;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCityList()
    {
        $list = StuAddress::find()->select('current_city')->DISTINCT('current_city')->all();         
        $StatesList = [
            'Hyderabad', 'Coimbatore','Bangalore',
        ];
        $cityList = array();
        if(!empty($list) && count($list)>0)
        {
            foreach ($list as $key => $value) {
                if(in_array($value['current_city'], $StatesList)!==true)
                {
                    $cityList[$value['current_city']]=$value['current_city'];
                }
            }
        }
        $StatesList = array_filter(array_merge($cityList,$StatesList));
        return  $StatesList;
    }

    public function getSubjectType()
    {
      $sub = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_SUBJECT_TYPE);
      $config_list = Categories::find()->where(['category_name' => $sub ])->one();
      $c_id = $config_list['coe_category_id'];

      $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
      $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');
      return $vals;
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentStatus()
    {
        
        $query = 'SELECT A.coe_category_type_id,A.category_type FROM coe_category_type AS A JOIN coe_categories AS B ON B.coe_category_id =A.category_id WHERE B.category_name LIKE "'.ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_STUDENT_CATEGORY).'" order by A.category_type ';
        $stu_categories = Yii::$app->db->createCommand($query)->queryAll();
        return  $stu_categories = ArrayHelper::map($stu_categories,'coe_category_type_id','category_type');
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmissionStatus()
    {
        
        $query = 'SELECT A.coe_category_type_id,A.category_type FROM coe_category_type AS A JOIN coe_categories AS B ON B.coe_category_id =A.category_id WHERE B.category_name LIKE "%'.ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_ADMISSION_CATEGORY).'%" order by A.category_type';
        $stu_categories = Yii::$app->db->createCommand($query)->queryAll();
        return  $stu_categories = ArrayHelper::map($stu_categories,'coe_category_type_id','category_type');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInitialDegreedetails()
    {
       
        $query = "SELECT a.coe_bat_deg_reg_id,concat(b.degree_code, ' ' , c.programme_code) as degree_name FROM coe_bat_deg_reg as a LEFT JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id LEFT JOIN coe_programme c ON c.coe_programme_id = a.coe_programme_id  order by a.coe_bat_deg_reg_id";
        $degreeInfo = Yii::$app->db->createCommand($query)->queryAll();        
        return ArrayHelper::map($degreeInfo,'coe_bat_deg_reg_id','degree_name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getSectionnames()
    {        
        $section_list = CoeBatDegReg::find()->max('no_of_section');
        $section_list = isset($section_list)?$section_list:4;
        $stu_dropdown = "";
        for ($char = 65; $char < 65+$section_list; $char++) {
            $stu_dropdown[chr($char)]= chr($char);
        }
        return $stu_dropdown;
    }
    public function getExamType()
    {
        $exam = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_EXAM_TYPE);
        $config_list = Categories::find()->where(['category_name' => $exam])->one();
        $c_id = $config_list['coe_category_id'];

        $config_list = Categorytype::find()->where(['category_id' => $c_id])->all();
        $vals = ArrayHelper::map($config_list,'coe_category_type_id','category_type');
        return $vals;
    }
    public function getMonths()
    {
        $Month =  array('1' => 'Oct/Nov' , '2'=>"Apr/May");
        return $Month;
    }

    public function getStatementtype(){
            return[
               Yii::t('app',self::TYPE_UGCBCS) => Yii::t('app','UG CBCS'),
               Yii::t('app',self::TYPE_PGCBCS) => Yii::t('app','PG CBCS'),
               Yii::t('app',self::TYPE_NONCBCS) => Yii::t('app','Non-CBCS'),
              
            ];
    }


}
