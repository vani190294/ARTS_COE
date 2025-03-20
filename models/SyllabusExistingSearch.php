<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SyllabusExisting;

/**
 * CurSyllabusSearch represents the model behind the search form about `app\models\SyllabusExisting`.
 */
class SyllabusExistingSearch extends SyllabusExisting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['from_regulation_id', 'coe_dept_id', 'to_regulation_id', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['from_subject_code'], 'safe'],

            //[['created_at', 'updated_at','degree_type','from_subject_code', 'to_subject_code'], 'safe'],
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
        $query = SyllabusExisting::find();

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
                'mapping_type' =>$_SESSION['mapping']
            ])->andWhere(['=','coe_dept_id',Yii::$app->user->getDeptId()]);
        }
        else
        {
            $query->Where([
                'mapping_type' =>$_SESSION['mapping']
            ]);
        }

        $query->andFilterWhere(['like', 'from_subject_code', $this->from_subject_code]);

        return $dataProvider;
    }
}
