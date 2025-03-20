<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CoeValueMarkEntry;

/**
 * CoeValueMarkEntrySearch represents the model behind the search form about `app\models\CoeValueMarkEntry`.
 */
class CoeValueMarkEntrySearch extends CoeValueMarkEntry
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_value_mark_entry_id', 'student_map_id', 'subject_map_id', 'CIA', 'ESE', 'total', 'year', 'month', 'term', 'mark_type', 'status_id', 'attempt', 'created_by', 'updated_by'], 'integer'],
            [['result', 'grade_name', 'year_of_passing', 'withheld', 'withheld_remarks', 'withdraw', 'is_updated', 'fees_paid', 'result_published_date', 'created_at', 'updated_at'], 'safe'],
            [['grade_point'], 'number'],
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
        $query = CoeValueMarkEntry::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'coe_value_mark_entry_id' => $this->coe_value_mark_entry_id,
            'student_map_id' => $this->student_map_id,
            'subject_map_id' => $this->subject_map_id,
            'CIA' => $this->CIA,
            'ESE' => $this->ESE,
            'total' => $this->total,
            'grade_point' => $this->grade_point,
            'year' => $this->year,
            'month' => $this->month,
            'term' => $this->term,
            'mark_type' => $this->mark_type,
            'status_id' => $this->status_id,
            'attempt' => $this->attempt,
            'result_published_date' => $this->result_published_date,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'result', $this->result])
            ->andFilterWhere(['like', 'grade_name', $this->grade_name])
            ->andFilterWhere(['like', 'year_of_passing', $this->year_of_passing])
            ->andFilterWhere(['like', 'withheld', $this->withheld])
            ->andFilterWhere(['like', 'withheld_remarks', $this->withheld_remarks])
            ->andFilterWhere(['like', 'withdraw', $this->withdraw])
            ->andFilterWhere(['like', 'is_updated', $this->is_updated])
            ->andFilterWhere(['like', 'fees_paid', $this->fees_paid]);

        return $dataProvider;
    }
}
