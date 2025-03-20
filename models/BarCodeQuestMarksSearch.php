<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BarCodeQuestMarks;

/**
 * BarCodeQuestMarksSearch represents the model behind the search form about `app\models\BarCodeQuestMarks`.
 */
class BarCodeQuestMarksSearch extends BarCodeQuestMarks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_bar_code_quest_marks_id', 'student_map_id', 'subject_map_id', 'dummy_number', 'year', 'month', 'question_no', 'question_no_marks', 'mark_type', 'term', 'created_by', 'updated_by'], 'integer'],
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
        $query = BarCodeQuestMarks::find();

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
            'coe_bar_code_quest_marks_id' => $this->coe_bar_code_quest_marks_id,
            'student_map_id' => $this->student_map_id,
            'subject_map_id' => $this->subject_map_id,
            'dummy_number' => $this->dummy_number,
            'year' => $this->year,
            'month' => $this->month,
            'question_no' => $this->question_no,
            'question_no_marks' => $this->question_no_marks,
            'mark_type' => $this->mark_type,
            'term' => $this->term,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
