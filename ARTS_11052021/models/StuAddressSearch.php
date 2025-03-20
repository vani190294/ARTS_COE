<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StuAddress;

/**
 * StuAddressSearch represents the model behind the search form about `app\models\StuAddress`.
 */
class StuAddressSearch extends StuAddress
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_stu_address_id', 'stu_address_id'], 'integer'],
            [['current_address', 'current_city', 'current_state', 'current_country', 'current_pincode', 'permanant_address','permanant_city', 'permanant_state', 'permanant_country', 'permanant_pincode'], 'safe'],
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
        $query = StuAddress::find();

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
            'coe_stu_address_id' => $this->coe_stu_address_id,
            'stu_address_id' => $this->stu_address_id,
        ]);

        $query->andFilterWhere(['like', 'current_address', $this->current_address])
            ->andFilterWhere(['like', 'current_city', $this->current_city])
            ->andFilterWhere(['like', 'current_state', $this->current_state])
            ->andFilterWhere(['like', 'current_country', $this->current_country])
            ->andFilterWhere(['like', 'current_pincode', $this->current_pincode])
            ->andFilterWhere(['like', 'permanant_address', $this->permanant_address])
            ->andFilterWhere(['like', 'permanant_state', $this->permanant_state])
            ->andFilterWhere(['like', 'permanant_city', $this->permanant_city])
            ->andFilterWhere(['like', 'permanant_country', $this->permanant_country])
            ->andFilterWhere(['like', 'permanant_pincode', $this->permanant_pincode]);

        return $dataProvider;
    }
}
