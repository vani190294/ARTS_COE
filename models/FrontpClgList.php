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
 * This is the model class for table "cur_frontp_clg_list".
 *
 * @property integer $cur_fpl_id
 * @property integer $cur_fp_id
 * @property string $degree_type
 * @property integer $coe_regulation_id
 * @property string $vision
 * @property string $mission
 * @property string $po
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class FrontpClgList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const TYPE_UG= 'UG';
    const TYPE_PG= 'PG';
    const TYPE_MBA= 'MBA';

    public static function tableName()
    {
        return 'cur_frontp_clg_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_fp_id', 'degree_type', 'coe_regulation_id', 'vision', 'mission', 'po','po_title'], 'required'],
            [['cur_fp_id', 'coe_regulation_id', 'created_by', 'updated_by'], 'integer'],
            [['vision', 'mission', 'po'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['degree_type'], 'string', 'max' => 10],
            [['po_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_fpl_id' => 'Cur Fpl ID',
            'cur_fp_id' => 'Cur Fp ID',
            'degree_type' => 'Degree Type',
            'coe_regulation_id' => 'Regulation',
            'vision' => 'Vision',
            'mission' => 'Mission',
            'po' => 'Program Outcomes',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
