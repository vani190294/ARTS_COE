<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HallMaster;

/**
 * HallMasterSearch represents the model behind the search form about `app\models\HallMaster`.
 */
class HallMasterSearch extends HallMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_hall_master_id', 'created_by', 'updated_by'], 'integer'],
            [['hall_name', 'description', 'created_at', 'updated_at','hall_type_id'], 'safe'],
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
        $query = HallMaster::find();

        // add conditions that should always apply here
        $query->joinWith(['hallType']);
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
            'coe_hall_master_id' => $this->coe_hall_master_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'hall_name', $this->hall_name])
        ->andFilterWhere(['like', 'hallType.category_type', $this->hall_type_id])
            ->andFilterWhere(['like', 'coe_hall_master.description', $this->description]);

        return $dataProvider;
    }
}
