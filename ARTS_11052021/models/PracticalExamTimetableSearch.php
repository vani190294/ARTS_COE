<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PracticalExamTimetable;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * PracticalExamTimetableSearch represents the model behind the search form about `app\models\PracticalExamTimetable`.
 */
class PracticalExamTimetableSearch extends PracticalExamTimetable
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_practical_exam_timetable_id', 'batch_mapping_id', 'student_map_id', 'subject_map_id', 'exam_year', 'exam_month', 'mark_type', 'term', 'out_of_100', 'ESE', 'created_by', 'updated_by'], 'integer'],
            [['exam_date', 'exam_session', 'internal_examiner_name', 'external_examiner_name', 'approve_status', 'created_at', 'updated_at'], 'safe'],
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
        $query = PracticalExamTimetable::find();

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
            'coe_practical_exam_timetable_id' => $this->coe_practical_exam_timetable_id,
            'batch_mapping_id' => $this->batch_mapping_id,
            'student_map_id' => $this->student_map_id,
            'subject_map_id' => $this->subject_map_id,
            'exam_year' => $this->exam_year,
            'exam_month' => $this->exam_month,
            'mark_type' => $this->mark_type,
            'term' => $this->term,
            'exam_date' => $this->exam_date,
            'exam_session' => $this->exam_session,
            'out_of_100' => $this->out_of_100,
            'ESE' => $this->ESE,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'internal_examiner_name', $this->internal_examiner_name])
            ->andFilterWhere(['like', 'external_examiner_name', $this->external_examiner_name])
            ->andFilterWhere(['like', 'approve_status', $this->approve_status]);

        return $dataProvider;
    }
}
