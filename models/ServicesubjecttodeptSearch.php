<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Servicesubjecttodept;

/**
 * ServicesubjecttodeptSearch represents the model behind the search form about `app\models\Servicesubjecttodept`.
 */
class ServicesubjecttodeptSearch extends Servicesubjecttodept
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_servtodept_id', 'coe_cur_subid', 'semester', 'created_by', 'updated_by'], 'integer'],
            [['degree_type','coe_regulation_id','coe_dept_ids', 'created_at', 'updated_at'], 'safe'],
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
        $query = Servicesubjecttodept::find();

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
        // $query->andFilterWhere([
        //     'coe_servtodept_id' => $this->coe_servtodept_id,
        //     //'semester' => 1,'coe_regulation_id'=>95
        // ]);

        if($params==1)
        {
            $query->Where(['<>', 'semester', '0']);
        }
        else if($params==2)
        {
            $query->Where(['=', 'semester', 0]);
        }

        $query->andWhere(['=', 'coe_dept_id', 8]);
        //$query->GroupBY(['coe_dept_ids','coe_servtodept_id']);
         $query->OrderBY(['coe_regulation_id'=>SORT_DESC,'coe_servtodept_id'=>SORT_DESC]);
        return $dataProvider;
    }
}
