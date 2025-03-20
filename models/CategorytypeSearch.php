<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Categorytype;

/**
 * CategorytypeSearch represents the model behind the search form about `app\models\Categorytype`.
 */
class CategorytypeSearch extends Categorytype
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_category_type_id', 'category_id', 'created_by', 'updated_by'], 'integer'],
            [['category_type', 'description', 'created_at', 'updated_at'], 'safe'],
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
        $query = Categorytype::find();

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
            'coe_category_type_id' => $this->coe_category_type_id,
            'category_id' => $this->category_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'category_type', $this->category_type])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
