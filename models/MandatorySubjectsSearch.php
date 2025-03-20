<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MandatorySubjects;

/**
 * MandatorySubjectsSearch represents the model behind the search form about `app\models\MandatorySubjects`.
 */
class MandatorySubjectsSearch extends MandatorySubjects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_mandatory_subjects_id', 'CIA_max', 'total_minimum_pass', 'semester'], 'integer'],
            [['man_batch_id','batch_mapping_id','semester','subject_code', 'subject_name', 'created_at', 'updated_at'], 'safe'],
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
        $query = MandatorySubjects::find();
         $query->joinWith(['coeBatch','coeDegree','coeProgramme']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'sort' =>false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'CIA_max' => $this->CIA_max,           
            'total_minimum_pass' => $this->total_minimum_pass,
            'semester' => $this->semester,
        ]);

        $query->andFilterWhere(['like', 'subject_code', $this->subject_code])
            ->andFilterWhere(['like', 'coe_batch.batch_name', $this->man_batch_id])
            ->andFilterWhere(['like', 'coe_degree.degree_name', $this->batch_mapping_id])
            ->andFilterWhere(['like', 'subject_name', $this->subject_name]);

        return $dataProvider;
    }
}
