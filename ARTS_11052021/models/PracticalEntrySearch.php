<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PracticalEntry;

/**
 * PracticalEntrySearch represents the model behind the search form about `app\models\PracticalEntry`.
 */
class PracticalEntrySearch extends PracticalEntry
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_practical_entry_id', 'student_map_id', 'subject_map_id', 'out_of_100', 'ESE', 'year', 'month', 'term', 'mark_type', 'created_by', 'updated_by'], 'integer'],
            [['approve_status', 'created_at', 'updated_at'], 'safe'],
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
        $query = PracticalEntry::find();

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
            'coe_practical_entry_id' => $this->coe_practical_entry_id,
            'student_map_id' => $this->student_map_id,
            'subject_map_id' => $this->subject_map_id,
            'out_of_100' => $this->out_of_100,
            'ESE' => $this->ESE,
            'year' => $this->year,
            'month' => $this->month,
            'term' => $this->term,
            'mark_type' => $this->mark_type,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'approve_status', $this->approve_status]);

        return $dataProvider;
    }
}
