<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Honours;

/**
 * HonoursSearch represents the model behind the search form about `app\models\Honours`.
 */
class HonoursSearch extends Honours
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['cur_hon_id', 'batch_map_id', 'coe_regulation_id', 'coe_dept_id', 'honours_type', 'vertical_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            //[['degree_type', 'register_number', 'subject_code', 'created_at', 'updated_at'], 'safe'],
            [['semester','register_number','subject_code'], 'safe'],
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
        $query = Honours::find();

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
            'cur_hon_id' => $this->cur_hon_id,
            'batch_map_id' => $this->batch_map_id,
            'coe_regulation_id' => $this->coe_regulation_id,
            'coe_dept_id' => $this->coe_dept_id,
            'honours_type' => $this->honours_type,
            'vertical_id' => $this->vertical_id,
            'semester' => $this->semester,
            'approve_status' => $this->approve_status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'degree_type', $this->degree_type])
            ->andFilterWhere(['like', 'register_number', $this->register_number])
            ->andFilterWhere(['like', 'subject_code', $this->subject_code])->orderBy(['cur_hon_id' => SORT_DESC]);

        return $dataProvider;
    }
}
