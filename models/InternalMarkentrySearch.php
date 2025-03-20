<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InternalMarkentry;

/**
 * InternalMarkentrySearch represents the model behind the search form about `app\models\InternalMarkentry`.
 */
class InternalMarkentrySearch extends InternalMarkentry
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mark_entry_id', 'student_map_id', 'subject_map_id', 'category_type_id', 'mark_out_of', 'category_type_id_marks', 'year', 'month', 'term', 'mark_type', 'status_id', 'attendance_percentage', 'created_by', 'updated_by'], 'integer'],
            [['attendance_remarks', 'is_updated', 'created_at', 'updated_at'], 'safe'],
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
        $query = InternalMarkentry::find();

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
            'mark_entry_id' => $this->mark_entry_id,
            'student_map_id' => $this->student_map_id,
            'subject_map_id' => $this->subject_map_id,
            'category_type_id' => $this->category_type_id,
            'mark_out_of' => $this->mark_out_of,
            'category_type_id_marks' => $this->category_type_id_marks,
            'year' => $this->year,
            'month' => $this->month,
            'term' => $this->term,
            'mark_type' => $this->mark_type,
            'status_id' => $this->status_id,
            'attendance_percentage' => $this->attendance_percentage,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'attendance_remarks', $this->attendance_remarks])
            ->andFilterWhere(['like', 'is_updated', $this->is_updated]);

        return $dataProvider;
    }
}
