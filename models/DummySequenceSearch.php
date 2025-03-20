<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DummySequence;

/**
 * DummySequenceSearch represents the model behind the search form about `app\models\DummySequence`.
 */
class DummySequenceSearch extends DummySequence
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'year',  'dummy_from', 'dummy_to'], 'integer'],
            [['subject_map_id', 'month'], 'safe'],
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
        $query = DummySequence::find();

        $query->joinWith(['subjectDet','monthName as t']);
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
            'year' => $this->year,
            'dummy_from' => $this->dummy_from,
            'dummy_to' => $this->dummy_to,           
        ]);
         $query->andFilterWhere(['like', 'subject_code', $this->subject_map_id])
               ->andFilterWhere(['like', 'description', $this->month]);

        return $dataProvider;
    }
}
