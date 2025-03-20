<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CoeValueSubjects;

/**
 * CoeValueSubjectsSearch represents the model behind the search form about `app\models\CoeValueSubjects`.
 */
class CoeValueSubjectsSearch extends CoeValueSubjects
{
    /**
     * @inheritdoc
     */
    public $batch_name,$degree_code,$programme_code,$coe_batch_id;
    public function rules()
    {
        return [
            [['coe_val_sub_id', 'subject_fee', 'CIA_min', 'CIA_max', 'ESE_min', 'ESE_max', 'total_minimum_pass', 'part_no', 'end_semester_exam_value_mark', 'created_by', 'updated_by'], 'integer'],
            [['subject_code', 'subject_name', 'created_at', 'updated_at'], 'safe'],
            [['credit_points'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CoeValueSubjects::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        /*$query->andFilterWhere([
            'coe_val_sub_id' => $this->coe_val_sub_id,
            'subject_fee' => $this->subject_fee,
            'CIA_min' => $this->CIA_min,
            'CIA_max' => $this->CIA_max,
            'ESE_min' => $this->ESE_min,
            'ESE_max' => $this->ESE_max,
            'total_minimum_pass' => $this->total_minimum_pass,
            'credit_points' => $this->credit_points,
            'part_no' => $this->part_no,
            'end_semester_exam_value_mark' => $this->end_semester_exam_value_mark,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'subject_code', $this->subject_code])
            ->andFilterWhere(['like', 'subject_name', $this->subject_name]);

        return $dataProvider;
    }*/

     $query->andFilterWhere([
           'coe_val_sub_id' => $this->coe_val_sub_id,
            'CIA_min' => $this->CIA_min,
            'CIA_max' => $this->CIA_max,
            'ESE_min' => $this->ESE_min,
            'ESE_max' => $this->ESE_max,
            'total_minimum_pass' => $this->total_minimum_pass,
            'credit_points' => $this->credit_points,
            'end_semester_exam_value_mark' => $this->end_semester_exam_value_mark, 
            'subject_fee'   =>  $this->subject_fee,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'subject_code', $this->subject_code])
            ->andFilterWhere(['like', 'subject_name', $this->subject_name])
            ->andFilterWhere(['like', 'programme_code', $this->programme_code])
            ->andFilterWhere(['like', 'degree_code', $this->degree_code])
            ->andFilterWhere(['like', 'batch_name', $this->batch_name]);

        return $dataProvider;
    }
}
