<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Regulation;

/**
 * RegulationSearch represents the model behind the search form about `app\models\Regulation`.
 */
class RegulationSearch extends Regulation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_regulation_id', 'coe_batch_id','regulation_year','grade_point','created_by', 'updated_by'], 'integer'],
            [['grade_name', 'created_at', 'updated_at'], 'safe'],
            [['grade_point_from', 'grade_point_to'], 'number'],
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
        $query = Regulation::find();
        $query->joinWith(['coeBatch']);
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
            'batch_name' => $this->coe_batch_id,
            'grade_point_from' => $this->grade_point_from,
            'grade_point_to' => $this->grade_point_to,
            'grade_point' => $this->grade_point,
        ]);

        $query->andFilterWhere(['like', 'regulation_year', $this->regulation_year])
            ->andFilterWhere(['like', 'grade_name', $this->grade_name]);

        return $dataProvider;
    }
}
