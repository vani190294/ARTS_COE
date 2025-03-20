<?php

namespace app\Models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\Models\ElectiveCount;

/**
 * ElectiveCountSearch represents the model behind the search form about `app\Models\ElectiveCount`.
 */
class ElectiveCountSearch extends ElectiveCount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_ec_id', 'coe_regulation_id', 'elective_type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['degree_type', 'coe_dept_id', 'elective_count'], 'safe'],
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
        $query = ElectiveCount::find();

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
            'cur_ec_id' => $this->cur_ec_id,
            'coe_regulation_id' => $this->coe_regulation_id,
            'elective_type' => $this->elective_type,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'degree_type', $this->degree_type])
            ->andFilterWhere(['like', 'coe_dept_id', $this->coe_dept_id])
            ->andFilterWhere(['like', 'elective_count', $this->elective_count]);

        if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
        {
           $depts=Yii::$app->user->getDeptId();

            $coe_deptids = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_department WHERE  dept_map_id IN (".$depts.") AND dept_map_id!=''")->queryScalar();

            $query->Where(['OR',['=','coe_dept_id',Yii::$app->user->getDeptId()],['=','coe_dept_id',$coe_deptids]]);
        }

        $query->andFilterWhere(['=', 'elective_type', $_SESSION['elective_types']]);

        $query->OrderBy(['cur_ec_id'=>SORT_ASC]);

        return $dataProvider;
    }
}
