<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ValuationFaculty;

/**
 * ValuationFacultySearch represents the model behind the search form about `app\models\ValuationFaculty`.
 */
class ValuationFacultySearch extends ValuationFaculty
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_val_faculty_id', 'faculty_experience', 'year', 'month', 'created_by', 'updated_by'], 'integer'],
            [['faculty_name', 'faculty_designation', 'faculty_board', 'faculty_mode', 'bank_accno', 'bank_name', 'bank_branch', 'bank_ifsc', 'phone_no', 'email', 'college_code', 'out_session', 'created_at', 'updated_at'], 'safe'],
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
        $query = ValuationFaculty::find();

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
            'coe_val_faculty_id' => $this->coe_val_faculty_id,
            'faculty_experience' => $this->faculty_experience,
            'year' => $this->year,
            'month' => $this->month,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'faculty_name', $this->faculty_name])
            ->andFilterWhere(['like', 'faculty_designation', $this->faculty_designation])
            ->andFilterWhere(['like', 'faculty_board', $this->faculty_board])
            ->andFilterWhere(['like', 'faculty_mode', $this->faculty_mode])
            ->andFilterWhere(['like', 'bank_accno', $this->bank_accno])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'bank_branch', $this->bank_branch])
            ->andFilterWhere(['like', 'bank_ifsc', $this->bank_ifsc])
            ->andFilterWhere(['like', 'phone_no', $this->phone_no])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'college_code', $this->college_code])
            ->andFilterWhere(['like', 'out_session', $this->out_session]);

        return $dataProvider;
    }
}
