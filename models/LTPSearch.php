<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LTP;

/**
 * LTPSearch represents the model behind the search form about `app\models\LTP`.
 */
class LTPSearch extends LTP
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_ltp_id','L', 'T', 'P', 'created_by', 'updated_by'], 'integer'],
            [['contact_hrsperweek', 'credit_point'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = LTP::find();

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
            'coe_ltp_id' => $this->coe_ltp_id,
            'L' => $this->L,
            'T' => $this->T,
            'P' => $this->P,
            'contact_hrsperweek' => $this->contact_hrsperweek,
            'credit_point' => $this->credit_point,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->OrderBy(['coe_regulation_id'=>SORT_DESC]);
        return $dataProvider;
    }
}
