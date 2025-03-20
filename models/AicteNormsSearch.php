<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AicteNorms;

/**
 * AicteNormsSearch represents the model behind the search form about `app\models\AicteNorms`.
 */
class AicteNormsSearch extends AicteNorms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_an_id', 'coe_dept_id', 'aicte_norms', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['stream_name'], 'safe'],
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
        $query = AicteNorms::find();

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
            'cur_an_id' => $this->cur_an_id,
            'coe_dept_id' => $this->coe_dept_id,
            'aicte_norms' => $this->aicte_norms,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ])->andWhere(['!=','coe_dept_id',8]);

        $query->andFilterWhere(['like', 'stream_name', $this->stream_name]);

        return $dataProvider;
    }
}
