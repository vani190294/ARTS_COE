<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Guardian;

/**
 * GuardianSearch represents the model behind the search form about `app\models\Guardian`.
 */
class GuardianSearch extends Guardian
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_guardian_id', 'stu_guardian_id'], 'integer'],
            [['guardian_name', 'guardian_relation', 'guardian_mobile_no', 'guardian_address', 'guardian_email', 'guardian_occupation'], 'safe'],
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
        $query = Guardian::find();

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
            'coe_guardian_id' => $this->coe_guardian_id,
            'stu_guardian_id' => $this->stu_guardian_id,
        ]);

        $query->andFilterWhere(['like', 'guardian_name', $this->guardian_name])
            ->andFilterWhere(['like', 'guardian_relation', $this->guardian_relation])
            ->andFilterWhere(['like', 'guardian_mobile_no', $this->guardian_mobile_no])
            ->andFilterWhere(['like', 'guardian_address', $this->guardian_address])
            ->andFilterWhere(['like', 'guardian_email', $this->guardian_email])
            ->andFilterWhere(['like', 'guardian_occupation', $this->guardian_occupation]);

        return $dataProvider;
    }
}
