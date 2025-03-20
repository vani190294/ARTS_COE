<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FrontpClg;

/**
 * FrontpClgSearch represents the model behind the search form about `app\models\FrontpClg`.
 */
class FrontpClgSearch extends FrontpClg
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['cur_fp_id', 'coe_regulation_id', 'mission_count', 'po_count', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            //[['degree_type', 'created_at', 'updated_at'], 'safe'],
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
        $query = FrontpClg::find();

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
            'cur_fp_id' => $this->cur_fp_id,
            'coe_regulation_id' => $this->coe_regulation_id,
            'mission_count' => $this->mission_count,
            'po_count' => $this->po_count,
            'approve_status' => $this->approve_status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'degree_type', $this->degree_type]);

        return $dataProvider;
    }
}
