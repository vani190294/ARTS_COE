<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CoeTransferCredit;

/**
 * CoeTransferCreditSearch represents the model behind the search form about `app\models\CoeTransferCredit`.
 */
class CoeTransferCreditSearch extends CoeTransferCredit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_tc_id', 'student_map_id','subject_map_id', 'removed_sub_map_id', 'total_studied', 'year', 'month', 'created_by', 'updated_by'], 'integer'],
            [['waiver_reason', 'created_at', 'updated_at'], 'safe'],
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
        $query = CoeTransferCredit::find();

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
            'coe_tc_id' => $this->coe_tc_id,
            'student_map_id' => $this->student_map_id,
            'removed_sub_map_id' => $this->removed_sub_map_id,
            'total_studied' => $this->total_studied,
            'year' => $this->year,
            'month' => $this->month,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'waiver_reason', $this->waiver_reason])
            ->andFilterWhere(['like', 'subject_map_id', $this->subject_map_id]);

        return $dataProvider;
    }
}
