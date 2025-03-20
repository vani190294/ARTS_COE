<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UpdateTracker;

/**
 * UpdateTrackerSearch represents the model behind the search form about `app\models\UpdateTracker`.
 */
class UpdateTrackerSearch extends UpdateTracker
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'exam_year', ], 'integer'],
            [['student_map_id', 'subject_map_id','exam_month',  'updated_by','updated_ip_address', 'updated_link_from', 'data_updated'], 'safe'],
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
        $query = UpdateTracker::find();
        $query->joinWith(['student','subject','categorytype','updatedBy as us']);
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
            'exam_year' => $this->exam_year,
        ]);

        $query->andFilterWhere(['like', 'updated_ip_address', $this->updated_ip_address])
            /*->andFilterWhere(['like', 'updated_link_from', $this->updated_link_from])*/
            ->andFilterWhere(['like', 'data_updated', $this->data_updated])
            ->andFilterWhere(['like', 'register_number', $this->student_map_id])
            ->andFilterWhere(['like', 'subject_code', $this->subject_map_id])
            ->andFilterWhere(['like', 'description', $this->exam_month])
            ->andFilterWhere(['like', 'us.username', $this->updated_by]);

        return $dataProvider;
    }
}
