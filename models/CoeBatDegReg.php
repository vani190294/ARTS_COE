<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/**
 * This is the model class for table "{{%coe_bat_deg_reg}}".
 *
 * @property integer $coe_bat_deg_reg_id
 * @property integer $coe_degree_id
 * @property integer $coe_programme_id
 * @property integer $coe_batch_id
 * @property integer $regulation_year
 * @property integer $no_of_section
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property CoeDegree $coeDegree
 * @property CoeProgramme $coeProgramme
 * @property CoeBatch $coeBatch
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $updatedBy
 * @property CoeNominal[] $coeNominals
 * @property CoeStudentMapping[] $coeStudentMappings
 * @property CoeSubjectsMapping[] $coeSubjectsMappings
 */
class CoeBatDegReg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_bat_deg_reg}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_degree_id', 'coe_programme_id', 'coe_batch_id', 'no_of_section', 'created_by', 'updated_by'], 'required'],
            [['coe_degree_id', 'coe_programme_id', 'coe_batch_id','regulation_year', 'no_of_section', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['coe_degree_id'], 'exist', 'skipOnError' => true, 'targetClass' => Degree::className(), 'targetAttribute' => ['coe_degree_id' => 'coe_degree_id']],
            [['coe_programme_id'], 'exist', 'skipOnError' => true, 'targetClass' => Programme::className(), 'targetAttribute' => ['coe_programme_id' => 'coe_programme_id']],
            [['coe_batch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Batch::className(), 'targetAttribute' => ['coe_batch_id' => 'coe_batch_id']],
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
            'coe_bat_deg_reg_id' => 'Coe Bat Deg Reg ID',
            'coe_degree_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE),
            'coe_programme_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
            'coe_batch_id' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
            'no_of_section' => 'No Of '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION),
            
            'regulation_year' => 'Regulation Year',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeMandatorySubcatSubjects()
    {
        return $this->hasMany(CoeMandatorySubcatSubjects::className(), ['batch_map_id' => 'coe_bat_deg_reg_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeDegree()
    {
        return $this->hasOne(Degree::className(), ['coe_degree_id' => 'coe_degree_id'])->alias('coe_deg');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeProgramme()
    {
        return $this->hasOne(Programme::className(), ['coe_programme_id' => 'coe_programme_id'])->alias('coe_peg');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeBatch()
    {
        return $this->hasOne(Batch::className(), ['coe_batch_id' => 'coe_batch_id'])->alias('coe_bat_re');
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeNominals()
    {
        return $this->hasMany(Nominal::className(), ['course_batch_mapping_id' => 'coe_bat_deg_reg_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeStudentMappings()
    {
        return $this->hasMany(StudentMapping::className(), ['course_batch_mapping_id' => 'coe_bat_deg_reg_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoeSubjectsMappings()
    {
        return $this->hasMany(SubjectsMapping::className(), ['batch_mapping_id' => 'coe_bat_deg_reg_id']);
    }

    public function findModel($id)
    {
       if (($model = CoeBatDegReg::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
