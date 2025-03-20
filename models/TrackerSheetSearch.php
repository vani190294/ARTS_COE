<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TrackerSheet;

/**
 * CoeTrackerSheetSearch represents the model behind the search form about `app\models\CoeTrackerSheet`.
 */
class TrackerSheetSearch extends TrackerSheet
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_ts_id'], 'integer'],
            [['task_tittle', 'task_description', 'priority', 'date', 'task_type', 'remark', 'status','developed_by'], 'safe'],
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
        $query = TrackerSheet::find();

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
            'coe_ts_id' => $this->coe_ts_id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'task_tittle', $this->task_tittle])
            ->andFilterWhere(['like', 'task_description', $this->task_description])
            ->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'task_type', $this->task_type])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'developed_by', $this->developed_by]);
        $query->OrderBy('coe_ts_id DESC');
        return $dataProvider;
    }
}
