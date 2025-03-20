<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MarkEntry;

/**
 * MarkEntrySearch represents the model behind the search form about `app\models\MarkEntry`.
 */
class MarkEntrySearch extends MarkEntry
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_mark_entry_id', 'student_map_id', 'subject_map_id', 'category_type_id', 'category_type_id_marks', 'year', 'status_id', 'created_by', 'updated_by'], 'integer'],
            [['month', 'term', 'created_at', 'updated_at'], 'safe'],
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
        $query = MarkEntry::find();

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
            'coe_mark_entry_id' => $this->coe_mark_entry_id,
            'student_map_id' => $this->student_map_id,
            'subject_map_id' => $this->subject_map_id,
            'category_type_id' => $this->category_type_id,
            'category_type_id_marks' => $this->category_type_id_marks,
            'year' => $this->year,
            'status_id' => $this->status_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'term', $this->term]);

        return $dataProvider;
    }
}
