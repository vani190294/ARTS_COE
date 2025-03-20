<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CoeActivityMarks;

/**
 * CoeActivityMarksSearch represents the model behind the search form about `app\models\CoeActivityMarks`.
 */
class CoeActivityMarksSearch extends CoeActivityMarks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'batch', 'register_number','created_by', 'updated_by'], 'integer'],
            [['programme', 'subject_code', 'duration','created_at', 'updated_at'], 'safe'],
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
        $query = CoeActivityMarks::find();

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
            'id' => $this->id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'batch' => $this->batch,
            'register_number' => $this->register_number,
           // 'section' => $this->section,
        ]);

        $query->andFilterWhere(['like', 'programme', $this->programme])
            ->andFilterWhere(['like', 'subject_code', $this->subject_code])
            ->andFilterWhere(['like', 'duration', $this->duration]);

        return $dataProvider;
    }
}
