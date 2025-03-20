<?php

namespace app\models;

use Yii;
use app\models\User;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%coe_configuration}}".
 *
 * @property integer $coe_config_id
 * @property string $config_name
 * @property string $config_value
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Configuration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $start_date,$end_date,$is_status,$photo_url,$org_name,$org_address,$org_email,$org_phone,$org_web,$org_tagline;
    public static function tableName()
    {
        return '{{%coe_configuration}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_name'], 'required'],
            [['created_at','updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['config_name', 'config_value'], 'string', 'max' => 255],            
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],

            [['config_name', 'config_value'], 'unique', 'targetAttribute' => ['config_name', 'config_value'],'message' => 'The combination of '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME).' Code and  '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME).'Name has already been taken.'],
            [['org_name','org_phone','org_tagline'], 'string', 'max' => 255],
            [['org_email'], 'email',],
            [['org_web'], 'url',],
            /*[['config_name',], 'unique', 'targetAttribute' => ['config_name',],'message' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME).' Name has already been Exists.'],*/

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
           
            'config_name' => ' Name',
            'config_value' => 'Value',
            'config_desc' => 'Description',
            'photo_url' => 'Photos Directory',
            'is_status' => 'Active/Inactive',
            'org_name' => 'Institute Name',
            'org_phone' => 'Phone Number',
            'org_email' => 'E-Mail Address',
            'org_web' => 'Website Address',
            'org_tagline' => "Institute Description",
            'org_address' => 'Address Information',
        ];
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
    
    public function configurationList()
    {
       $config_list = Configuration::find()->select('config_desc')->orderBy(['config_desc'=>SORT_ASC])->distinct()->all();
       return $config_list = ArrayHelper::map($config_list,'config_desc','config_desc');
    }
    
}
