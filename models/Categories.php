<?php

namespace app\models;

use Yii;
use app\models\User;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;;
use yii\helpers\ArrayHelper;
use app\models\Categorytype;
/**
 * This is the model class for table "coe_categories".
 *
 * @property integer $coe_category_id
 * @property string $category_name
 * @property string $description
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coe_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['description', 'created_by', 'updated_by'], 'required'],
            [['created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['category_name', 'description'], 'string', 'max' => 255],
            //[['category_name'], 'required', 'message'=> 'Enter valid '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY).' Name.'],
            //[['category_name'], 'match' ,'pattern'=> '/^[A-Za-z ]+$/u','message'=> 'Enter valid Category Code.'],
            [['category_name'], 'unique', 'targetAttribute' => ['category_name'], 'message' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY).' Name already created.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_category_id' => 'Coe '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY).'',
            'category_name' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY).' Name',
            'description' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY).' Description',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    public function getCategories()
    {
      // $config_list = Categories::find()->orderBy(['category_name'=>SORT_ASC])->all();
      $config_list = Categories::find()->all();
        $vals = array('0' => 'Create New');        
        $vals1 = ArrayHelper::map($config_list,'coe_category_id','category_name');
        $vals = array_merge($vals,$vals1);
        return $vals;
    }

    /*public function getCategory()
    {
        $config_list = Categorytype::find()->orderBy(['category_type'=>SORT_ASC])->all();
        $vals1 = ArrayHelper::map($config_list,'coe_category_type_id','category_type');
        return "1";
	}*/
}
