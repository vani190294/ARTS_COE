<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StudentMapping;

/**
 * StudentMappingSearch represents the model behind the search form about `app\models\StudentMapping`.
 */
class StudentMappingSearch extends StudentMapping
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_student_mapping_id', 'student_rel_id', 'course_batch_mapping_id', 'status_category_type_id', 'created_by', 'updated_by'], 'integer'],
            [['section_name', 'created_at', 'updated_at'], 'safe'],
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
        $query = StudentMapping::find();

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
            'coe_student_mapping_id' => $this->coe_student_mapping_id,
            'student_rel_id' => $this->student_rel_id,
            'course_batch_mapping_id' => $this->course_batch_mapping_id,
            'status_category_type_id' => $this->status_category_type_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'section_name', $this->section_name]);

        return $dataProvider;
    }
}
