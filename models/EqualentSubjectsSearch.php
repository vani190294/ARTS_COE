<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EqualentSubjects;

/**
 * EqualentSubjectsSearch represents the model behind the search form about `app\models\EqualentSubjects`.
 */
class EqualentSubjectsSearch extends EqualentSubjects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_equalent_subjects_id', 'prev_stu_map_id', 'prev_sub_map_id', 'new_stu_map_id', 'new_sub_map_id', 'created_by', 'updated_by'], 'integer'],
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
        $query = EqualentSubjects::find();

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
            'coe_equalent_subjects_id' => $this->coe_equalent_subjects_id,
            'prev_stu_map_id' => $this->prev_stu_map_id,
            'prev_sub_map_id' => $this->prev_sub_map_id,
            'new_stu_map_id' => $this->new_stu_map_id,
            'new_sub_map_id' => $this->new_sub_map_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
