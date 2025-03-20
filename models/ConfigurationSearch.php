<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Configuration;

/**
 * ConfigurationSearch represents the model behind the search form about `app\modules\configuration\models\Configuration`.
 */
class ConfigurationSearch extends Configuration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_config_id', 'created_by', 'updated_by'], 'integer'],
            [['config_name','config_desc', 'config_value', 'created_at', 'updated_at'], 'safe'],
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
        $query = Configuration::find();

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
            'coe_config_id' => $this->coe_config_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'config_desc' => $this->config_desc,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'config_name', $this->config_name])
            ->andFilterWhere(['like', 'config_desc', $this->config_desc])
            ->andFilterWhere(['like', 'config_value', $this->config_value])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
