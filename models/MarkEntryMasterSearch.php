<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MarkEntryMaster;

/**
 * MarkEntryMasterSearch represents the model behind the search form about `app\models\MarkEntryMaster`.
 */
class MarkEntryMasterSearch extends MarkEntryMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'year','CIA', 'ESE', 'total'], 'integer'],
            [['month','term','mark_type','student_map_id', 'subject_map_id', 'result', 'grade_name'], 'safe'],
            [['grade_point'], 'number'],
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
        $query = MarkEntryMaster::find();
        $query->joinWith(['subjectDet','studentDet','term as t','markType as mt','monthName as m']);
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
            'year'=>$this->year,
            'CIA' => $this->CIA,
            'ESE' => $this->ESE,
            'total' => $this->total,
            'grade_point' => $this->grade_point,
            'grade_name'=>$this->grade_name,
            
        ]);

        $query->andFilterWhere(['like', 'result', $this->result])
              ->andFilterWhere(['like', 'register_number', $this->student_map_id])
              ->andFilterWhere(['like', 'm.description', $this->month])
              ->andFilterWhere(['like', 'mt.description', $this->mark_type])
              ->andFilterWhere(['like', 't.description', $this->term])
              ->andFilterWhere(['like', 'subject_code', $this->subject_map_id]);

        return $dataProvider;
    }
}
