<?php

namespace app\Models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\Models\DeptPso;

/**
 * DeptPsoSearch represents the model behind the search form about `app\Models\DeptPso`.
 */
class DeptPsoSearch extends DeptPso
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_vs_id', 'coe_regulation_id', 'no_of_pso', 'approve_status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['degree_type', 'coe_dept_id', 'pso_title'], 'safe'],
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
        $query = DeptPso::find();

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
            $query->andWhere(['=','coe_dept_id',Yii::$app->user->getDeptId()]);
        }

        return $dataProvider;
    }
}
