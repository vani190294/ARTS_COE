<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CoreFacultys;

/**
 * CoreFacultysSearch represents the model behind the search form about `app\models\CoreFacultys`.
 */
class CoreFacultysSearch extends CoreFacultys
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['cur_cf_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            //[['degree_type', 'no_of_section', 'created_at', 'updated_at'], 'safe'],
            [['degree_type','semester','subject_code','no_of_section'], 'safe'],
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
        $query = CoreFacultys::find();

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
            'cur_cf_id' => $this->cur_cf_id,
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
            ->andFilterWhere(['like', 'no_of_section', $this->no_of_section]);

        return $dataProvider;
    }
}
