<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExamTimetable;

/**
 * ExamTimetableSearch represents the model behind the search form about `app\models\ExamTimetable`.
 */
class ExamTimetableSearch extends ExamTimetable
{
    /**
     * @inheritdoc
     */
    public $semester,$batch_name,$programme_code,$degree_code,$subject_code;
    public function rules()
    {
        return [
            [['coe_exam_timetable_id', 'subject_mapping_id', 'created_by', 'updated_by'], 'integer'],
            [['exam_year','semester','exam_type', 'exam_term', 'qp_code', 'exam_date', 'exam_session', 'created_at', 'updated_at','exam_month','programme_code','degree_code','batch_name','subject_code'], 'safe'],
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
        $query = ExamTimetable::find()->orderBy('coe_exam_timetable_id DESC');
        $query->joinWith(['subjectMapping','wholeSemester','examTypeRel exam_type','examSessionRel exam_session_rel','examMonthRel exam_month_rel','coeProgramme','coeDegree','coeBatch']);
        //$query->joinWith(['semester']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'coe_exam_timetable_id' => $this->coe_exam_timetable_id,
            'subjectMapping.subject_mapping_id' => $this->subject_mapping_id,
            'semester' => $this->semester,
            'exam_date' => $this->exam_date,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'exam_type.category_type', $this->exam_type])
            ->andFilterWhere(['like', 'exam_year', $this->exam_year])
            ->andFilterWhere(['like', 'batch_name', $this->batch_name])
            ->andFilterWhere(['like', 'degree_code', $this->degree_code])
            ->andFilterWhere(['like', 'programme_code', $this->programme_code])
            ->andFilterWhere(['like', 'exam_month_rel.category_type', $this->exam_month])
            ->andFilterWhere(['like', 'exam_term', $this->exam_term])
            ->andFilterWhere(['like', 'subject_code', $this->subject_code])
            ->andFilterWhere(['like', 'qp_code', $this->qp_code])
            ->andFilterWhere(['like', 'exam_session_rel.category_type', $this->exam_session]);

        return $dataProvider;
    }
}
