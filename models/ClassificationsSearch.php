<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Classifications;

/**
 * ClassificationsSearch represents the model behind the search form about `app\models\Classifications`.
 */
class ClassificationsSearch extends Classifications
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_classifications_id', 'regulation_year', 'created_by', 'updated_by'], 'integer'],
            [['percentage_from', 'percentage_to'], 'number'],
            [['grade_name', 'classification_text', 'created_at', 'updated_at'], 'safe'],
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
        $query = Classifications::find();

        // add conditions that should always apply here

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
            'regulation_year' => $this->regulation_year,
            'percentage_from' => $this->percentage_from,
            'percentage_to' => $this->percentage_to,
        ]);

        $query->andFilterWhere(['like', 'grade_name', $this->grade_name])
            ->andFilterWhere(['like', 'classification_text', $this->classification_text]);

        return $dataProvider;
    }
}
