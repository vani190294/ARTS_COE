<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CoeValClaimAmt;

/**
 * CoeValClaimAmtSearch represents the model behind the search form about `app\models\CoeValClaimAmt`.
 */
class CoeValClaimAmtSearch extends CoeValClaimAmt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['claim_id', 'created_by', 'updated_by'], 'integer'],
            [['ug_amt', 'pg_amt', 'ta_amt_half_day', 'ta_amt_full_day'], 'number'],
            [['created_at', 'updated_at','exam_type'], 'safe'],
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
        $query = CoeValClaimAmt::find();

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
            'exam_type'  => $this->exam_type,
            'claim_id' => $this->claim_id,
            'ug_amt' => $this->ug_amt,
            'pg_amt' => $this->pg_amt,
            'ta_amt_half_day' => $this->ta_amt_half_day,
            'ta_amt_full_day' => $this->ta_amt_full_day,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
