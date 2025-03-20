<?php
namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Scrutiny;
/**
 * ScrutinySearch represents the model behind the search form about `app\models\Scrutiny`.
 */
class ScrutinySearch extends Scrutiny
{
    /**
     * @inheritdoc
     */
    public $designation, $department;
    public function rules()
    {
        return [
            [['coe_scrutiny_id', 'phone_no', 'created_by', 'updated_by'], 'integer'],
            [['name', 'designation', 'department', 'email', 'bank_accno', 'bank_ifsc', 'bank_name', 
            'bank_branch', 'created_at', 'updated_at'], 'safe'],
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
        $query = Scrutiny::find();
        $query->joinWith(['designationName','departmentName']);
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,'sort' =>false,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'coe_scrutiny_id' => $this->coe_scrutiny_id,
            'phone_no' => $this->phone_no,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'designation.category_type', $this->designation])
            ->andFilterWhere(['like', 'department_name.dept_code', $this->department])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'bank_accno', $this->bank_accno])
            ->andFilterWhere(['like', 'bank_ifsc', $this->bank_ifsc])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'bank_branch', $this->bank_branch]);
        return $dataProvider;
    }
}
