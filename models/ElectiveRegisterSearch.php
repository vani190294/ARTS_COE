<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ElectiveRegister;

/**
 * ElectiveRegisterSearch represents the model behind the search form about `app\models\ElectiveRegister`.
 */
class ElectiveRegisterSearch extends ElectiveRegister
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_elect_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['degree_type', 'pec_paper', 'oec_paper', 'eec_paper', 'mc_paper', 'created_at', 'updated_at'], 'safe'],
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
        $query = ElectiveRegister::find();

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

        if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
        {
            $query->Where([
            ])->andWhere(['=','coe_dept_id',Yii::$app->user->getDeptId()]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'cur_elect_id' => $this->cur_elect_id,
            'coe_regulation_id' => $this->coe_regulation_id,
            'coe_dept_id' => $this->coe_dept_id,
            'semester' => $this->semester,
            'approve_status' => $this->approve_status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'degree_type', $this->degree_type])
            ->andFilterWhere(['like', 'pec_paper', $this->pec_paper])
            ->andFilterWhere(['like', 'oec_paper', $this->oec_paper])
            ->andFilterWhere(['like', 'eec_paper', $this->eec_paper])
            ->andFilterWhere(['like', 'mc_paper', $this->mc_paper])->orderBy(['coe_regulation_id' => SORT_DESC]);

        return $dataProvider;
    }
}
