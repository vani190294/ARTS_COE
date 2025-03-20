<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DummyNumbers;

/**
 * DummyNumbersSearch represents the model behind the search form about `app\models\DummyNumbers`.
 */
class DummyNumbersSearch extends DummyNumbers
{
    /**
     * @inheritdoc
     */
    public $register_number,$subject_code,$subject_name;
    public function rules()
    {
        return [
            [['coe_dummy_number_id', 'student_map_id', 'subject_map_id', 'year', 'month','created_by', 'dummy_number','updated_by'], 'integer'],
            [[ 'created_at', 'updated_at'], 'safe'],
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
        $query = DummyNumbers::find()->orderBy('dummy_number');
        
        // add conditions that should always apply here

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
            
            'student_map_id' => $this->student_map_id,
            'subject_map_id' => $this->subject_map_id,
            'year' => $this->year,
            'month' => $this->month,            
            
        ]);

        $query->andFilterWhere(['like', 'dummy_number', $this->dummy_number]);
        $query->andFilterWhere(['like', 'subjectDetails.subject_code', $this->subject_code]);
        $query->andFilterWhere(['like', 'studentDetails.register_number', $this->register_number]);


        return $dataProvider;
    }
}
