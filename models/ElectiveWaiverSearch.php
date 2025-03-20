<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ElectiveWaiver;

/**
 * ElectiveWaiverSearch represents the model behind the search form about `app\models\ElectiveWaiver`.
 */
class ElectiveWaiverSearch extends ElectiveWaiver
{
    /**
     * @inheritdoc
     */
    public $register_number;
    public function rules()
    {
        return [
            [['year'], 'integer'],
            [['student_map_id','removed_sub_map_id','waiver_reason', 'month', 'subject_codes'], 'safe'],
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
        $query = ElectiveWaiver::find();

        // add conditions that should always apply here
        $query->joinWith(['student','subjects','month0']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'total_studied' => $this->total_studied,
            'year' => $this->year,           
        ]);

        $query->andFilterWhere(['like', 'waiver_reason', $this->waiver_reason])
            ->andFilterWhere(['like', 'subject_codes', $this->subject_codes])
            ->andFilterWhere(['like', 'subject_code', $this->removed_sub_map_id])
            ->andFilterWhere(['like', 'description', $this->month])
            ->andFilterWhere(['like', 'register_number', $this->student_map_id]);

        return $dataProvider;
    }
}
