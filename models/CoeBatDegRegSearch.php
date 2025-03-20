<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CoeBatDegReg;

/**
 * CoeBatDegRegSearch represents the model behind the search form about `app\models\CoeBatDegReg`.
 */
class CoeBatDegRegSearch extends CoeBatDegReg
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'coe_degree_id', 'coe_programme_id', 'coe_batch_id', 'no_of_section','regulation_year'], 'string'],
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
        $query = CoeBatDegReg::find();

        // add conditions that should always apply here
        $query->joinWith(['coeBatch','coeDegree','coeProgramme']);
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
            'degree_code' => $this->coe_degree_id,
            'programme_code' => $this->coe_programme_id,
            'batch_name' => $this->coe_batch_id,
            'regulation_year'=>$this->regulation_year,
            'no_of_section' => $this->no_of_section,
           
        ]);

        return $dataProvider;
    }
}
