<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PracStuPerBatch;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/**
 * PracticalExamTimetableSearch represents the model behind the search form about `app\models\PracStuPerBatch`.
 */
class PracStuPerBatchSearch extends PracStuPerBatch
{
    /**
     * @inheritdoc
     */
    public $batch_name,$degree_code;
    public function rules()
    {
        return [
            [[ 'exam_year', 'stu_per_batch_count'], 'integer'],
            [['coe_batch_id', 'batch_mapping_id', 'subject_map_id', 'subject_code', 'exam_month', 'exam_type'], 'safe'],
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
        $query = PracStuPerBatch::find();
        $query->joinWith(['subject','batch','programme','month as month','markType as type']);
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

        $query->andFilterWhere(['like', 'subject_code', $this->subject_map_id])
              ->andFilterWhere(['like', 'batch_name', $this->batch_name])
              ->andFilterWhere(['like', 'stu_per_batch_count', $this->stu_per_batch_count])
              ->andFilterWhere(['like', 'programme_code', $this->batch_mapping_id])
              ->andFilterWhere(['like', 'month.description', $this->exam_month])
              ->andFilterWhere(['like', 'type.description', $this->exam_type]);

        return $dataProvider;
    }
}
