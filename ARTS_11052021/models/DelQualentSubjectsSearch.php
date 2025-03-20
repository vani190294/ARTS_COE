<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DelQualentSubjects;

/**
 * DelQualentSubjectsSearch represents the model behind the search form about `app\models\DelQualentSubjects`.
 */
class DelQualentSubjectsSearch extends DelQualentSubjects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_del_qualent_subjects_id', 'stu_map_id', 'sub_map_id', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
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
        $query = DelQualentSubjects::find();

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
            'coe_del_qualent_subjects_id' => $this->coe_del_qualent_subjects_id,
            'stu_map_id' => $this->stu_map_id,
            'sub_map_id' => $this->sub_map_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        return $dataProvider;
    }
}
