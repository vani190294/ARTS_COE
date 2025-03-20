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
use app\models\Regulation;

/**
 * This is the model class for table "cur_ltp".
 *
 * @property integer $coe_ltp_id
 * @property integer $coe_regulation_id
 * @property integer $L
 * @property integer $T
 * @property integer $P
 * @property double $contact_hrsperweek
 * @property double $credit_point
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class LTP extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_ltp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_batch_id', 'coe_regulation_id','L', 'T', 'P', 'contact_hrsperweek', 'credit_point', 'subject_type_id', 'subject_category_type_id', 'external_mark', 'internal_mark'], 'required'],
            [['L', 'T', 'P', 'created_by', 'updated_by'], 'integer'],
            [['contact_hrsperweek', 'credit_point'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coe_ltp_id' => 'Coe Ltp ID',
            'L' => 'L',
            'T' => 'T',
            'P' => 'P',
            'contact_hrsperweek' => 'Contact Hrs/week',
            'credit_point' => 'Credit Point',
            'coe_batch_id' => 'Batch',
            'coe_regulation_id' => 'Regulation(Batch)',
            'subject_type_id' => 'Subject Type',
            'subject_category_type_id' => 'Subject Category Type',
            'external_mark' => 'External Mark',
            'internal_mark' => 'Internal Mark',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

     public function getRegulation()
    { 
         return $this->hasOne(Regulation::className(), ['coe_regulation_id' => 'coe_regulation_id']);
    }

     public function getSubjecttype()
    { 
         return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'subject_type_id']);
    }

    public function getSubjectctype()
    { 
         return $this->hasOne(Categorytype::className(), ['coe_category_type_id' => 'subject_category_type_id']);
    }
    

     public function getBatch()
    { 
         return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id']);
    }


    public function getRegulationDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT A.coe_regulation_id, concat(A.regulation_year,'(',B.batch_name,')') as regulation_year FROM coe_regulation A JOIN coe_batch B ON B.coe_batch_id=A.coe_batch_id WHERE regulation_year >=2021 GROUP BY A.regulation_year,B.batch_name ORDER BY regulation_year DESC")->queryAll();
        return  ArrayHelper::map($deptall,'coe_regulation_id','regulation_year');
    }

    public function getSubjecttypeDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,category_type FROM coe_category_type WHERE category_id=3")->queryAll();
        return  ArrayHelper::map($deptall,'coe_category_type_id','category_type');
    }

    public function getSubjectctypeDetails()
    {
         $deptall = Yii::$app->db->createCommand("SELECT coe_category_type_id,category_type FROM coe_category_type WHERE category_id=24")->queryAll();
        return  ArrayHelper::map($deptall,'coe_category_type_id','category_type');
    }

}
