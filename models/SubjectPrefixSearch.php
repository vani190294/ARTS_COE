<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubjectPrefix;

/**
 * SubjectPrefixSearch represents the model behind the search form about `app\models\SubjectPrefix`.
 */{

class SubjectPrefixSearch extends SubjectPrefix
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_prefix_id', 'coe_dept_id', 'created_by', 'updated_by'], 'integer'],
            [['prefix_name', 'created_at', 'updated_at'], 'safe'],
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
        $query = SubjectPrefix::find();

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
            'coe_prefix_id' => $this->coe_prefix_id,
            'coe_dept_id' => $this->coe_dept_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'prefix_name', $this->prefix_name]);

        return $dataProvider;
    }
}
