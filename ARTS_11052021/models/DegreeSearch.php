<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Degree;

/**
 * DegreeSearch represents the model behind the search form about `app\models\Degree`.
 */
class DegreeSearch extends Degree
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_degree_id', 'created_by', 'updated_by','degree_total_years','degree_total_semesters'], 'integer'],
            [['degree_code', 'degree_name', 'degree_type', 'created_at', 'updated_at','degree_total_years','degree_total_semesters'], 'safe'],
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
        $query = Degree::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,'sort' => false, 
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'coe_degree_id' => $this->coe_degree_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'degree_code', $this->degree_code])
            ->andFilterWhere(['like', 'degree_name', $this->degree_name])
            ->andFilterWhere(['like', 'degree_total_years', $this->degree_total_years])
            ->andFilterWhere(['like', 'degree_total_semesters', $this->degree_total_semesters])
            ->andFilterWhere(['like', 'degree_type', $this->degree_type]);

        return $dataProvider;
    }
}
