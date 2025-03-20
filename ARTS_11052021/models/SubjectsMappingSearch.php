<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubjectsMapping;

/**
 * SubjectsMappingSearch represents the model behind the search form about `app\models\SubjectsMapping`.
 */
class SubjectsMappingSearch extends SubjectsMapping
{
    /**
     * @inheritdoc
     */
    public $batch_name,$degree_code,$programme_code,$coe_batch_id,$subject_code,$subject_name,$ESE_min,$ESE_max,$CIA_max;
    public function rules()
    {
        return [
            [['coe_subjects_mapping_id', 'batch_mapping_id', 'subject_id', 'ESE_max','ESE_min','CIA_max'], 'integer'],
            [['batch_name','degree_code','programme_code','coe_batch_id','subject_code','subject_name','semester','paper_type_id', 'subject_type_id', 'course_type_id', ], 'safe'],
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
        $query = SubjectsMapping::find();

        // add conditions that should always apply here
        $query->joinWith(['coeSubjects','coeBatch','coeDegree','coeProgramme','paperTypes','subjectTypes','courseTypes']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,'sort' => false, 
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'coe_subjects_mapping_id' => $this->coe_subjects_mapping_id,
            'batch_mapping_id' => $this->batch_mapping_id,
            'subject_id' => $this->subject_id,            
            'semester'  =>  $this->semester,
        ]);
        $query->andFilterWhere(['like', 'batch_name', $this->batch_name])
            ->andFilterWhere(['like', 'programme_code', $this->programme_code])
            ->andFilterWhere(['like', 'paper_type.category_type', $this->paper_type_id])
            ->andFilterWhere(['like', 'subject_type.category_type', $this->subject_type_id])
            ->andFilterWhere(['like', 'course_type.category_type', $this->course_type_id])
            ->andFilterWhere(['like', 'degree_code', $this->degree_code])
            ->andFilterWhere(['like', 'subject_code', $this->subject_code])
            ->andFilterWhere(['=', 'ESE_min', $this->ESE_min]) 
            ->andFilterWhere(['=', 'CIA_max', $this->CIA_max])           
            ->andFilterWhere(['=', 'ESE_max', $this->ESE_max])
            ->andFilterWhere(['like', 'subject_name', $this->subject_name]);
        return $dataProvider;
    }
}
