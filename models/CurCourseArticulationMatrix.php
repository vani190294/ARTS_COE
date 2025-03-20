<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cur_course_articulation_matrix".
 *
 * @property integer $cur_cam_id
 * @property integer $cur_syllabus_id
 * @property integer $co
 * @property integer $po1
 * @property integer $po2
 * @property integer $po3
 * @property integer $po4
 * @property integer $po5
 * @property integer $po6
 * @property integer $po7
 * @property integer $po8
 * @property integer $po9
 * @property integer $po10
 * @property integer $po11
 * @property integer $po12
 * @property integer $pso1
 * @property integer $pso2
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class CurCourseArticulationMatrix extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cur_course_articulation_matrix';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_cam_id', 'cur_syllabus_id', 'po1', 'po2', 'po3', 'po4', 'po5', 'po6', 'po7', 'po8', 'po9', 'po10', 'po11', 'po12', 'pso1', 'pso2', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['cur_cam_id', 'cur_syllabus_id', 'co', 'po1', 'po2', 'po3', 'po4', 'po5', 'po6', 'po7', 'po8', 'po9', 'po10', 'po11', 'po12', 'pso1', 'pso2', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
             [[ 'co'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_cam_id' => 'Cur Cam ID',
            'cur_syllabus_id' => 'Cur Syllabus ID',
            'co' => 'Co',
            'po1' => 'Po1',
            'po2' => 'Po2',
            'po3' => 'Po3',
            'po4' => 'Po4',
            'po5' => 'Po5',
            'po6' => 'Po6',
            'po7' => 'Po7',
            'po8' => 'Po8',
            'po9' => 'Po9',
            'po10' => 'Po10',
            'po11' => 'Po11',
            'po12' => 'Po12',
            'pso1' => 'Pso1',
            'pso2' => 'Pso2',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
