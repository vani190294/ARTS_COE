<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Programme;

/**
 * ProgrammeSearch represents the model behind the search form about `app\models\Programme`.
 */
class ProgrammeSearch extends Programme
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		[['coe_programme_id', 'created_by', 'updated_by'], 'integer'],
		[['created_at', 'updated_at','programme_code','programme_name'], 'safe'],
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
        $query = Programme::find();

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
            'coe_programme_id' => $this->coe_programme_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'programme_code', $this->programme_code])
            ->andFilterWhere(['like', 'programme_name', $this->programme_name]);

        return $dataProvider;
    }
}
