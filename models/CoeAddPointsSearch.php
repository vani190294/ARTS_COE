<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CoeAddPoints;

/**
 * CoeAddPointsSearch represents the model behind the search form about `app\models\CoeAddPoints`.
 */
class CoeAddPointsSearch extends CoeAddPoints
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at','subject_code', 'subject_name'], 'safe'],
           
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
        $query = CoeAddPoints::find();

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
            'id' => $this->id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'subject_code', $this->subject_code])
            ->andFilterWhere(['like', 'subject_name', $this->subject_name]);
           // ->andFilterWhere(['like', 'activity_points', $this->activity_points]);

        return $dataProvider;
    }
}
