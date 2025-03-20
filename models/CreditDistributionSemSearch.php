<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CreditDistributionSem;

/**
 * CreditDistributionSemSearch represents the model behind the search form about `app\models\CreditDistributionSem`.
 */
class CreditDistributionSemSearch extends CreditDistributionSem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_dist_id', 'coe_regulation_id', 'coe_dept_id', 'cur_stream_id', 'total_credit', 'created_by', 'updated_by'], 'integer'],
            [['degree_type', 'created_at', 'updated_at'], 'safe'],
            [['sem1', 'sem2', 'sem3', 'sem4', 'sem5', 'sem6', 'sem7', 'sem8'], 'number'],
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
        $query = CreditDistributionSem::find();

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
            $depts=Yii::$app->user->getDeptId();

            $coe_deptids = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_department WHERE  dept_map_id IN (".$depts.") AND dept_map_id!=''")->queryScalar();

            $query->Where(['OR',['=','coe_dept_id',Yii::$app->user->getDeptId()],['=','coe_dept_id',$coe_deptids]]);
        }

        // grid filtering conditions
        $query->groupBY('degree_type,coe_regulation_id,coe_dept_id');
        //print_r($query->createCommand()->getrawsql()); exit;
        return $dataProvider;
    }
}
