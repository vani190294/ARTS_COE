<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MandatorySubcatSubjects;

/**
 * MandatorySubcatSubjectsSearch represents the model behind the search form about `app\models\MandatorySubcatSubjects`.
 */
class MandatorySubcatSubjectsSearch extends MandatorySubcatSubjects
{
    /**
     * @inheritdoc
     */
    public $batch_name,$subject_code;
    public function rules()
    {
        return [
            [['coe_batch_id','credit_points','man_subject_id' ], 'integer'],
            [['sub_cat_code','sub_cat_name','batch_name','subject_code'], 'safe'],
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
        $query = MandatorySubcatSubjects::find();

        // add conditions that should always apply here
        $query->joinWith(['courseBatchMapping as batchMapping','batch','manSubject']);
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
        //     'man_subject_id' => $this->man_subject_id,
        //     'batch_map_id' => $this->batch_map_id,
        //     'course_type_id' => $this->course_type_id,
        //     'paper_type_id' => $this->paper_type_id,
        //     'subject_type_id' => $this->subject_type_id,

            'credit_points' => $this->credit_points,
           
         ]);

        $query->andFilterWhere(['like', 'sub_cat_code', $this->sub_cat_code])
            ->andFilterWhere(['like', 'sub_cat_name', $this->sub_cat_name])
            ->andFilterWhere(['like', 'getBatchId.batch_name', $this->batch_name])
            ->andFilterWhere(['like', '`coe_mandatory_subjects`.subject_code', $this->subject_code]);

        return $dataProvider;
    }
}
