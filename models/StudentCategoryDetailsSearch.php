<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StudentCategoryDetails;

/**
 * StudentCategoryDetailsSearch represents the model behind the search form about `app\models\StudentCategoryDetails`.
 */
class StudentCategoryDetailsSearch extends StudentCategoryDetails
{
    /**
     * @inheritdoc
     */
    public $register_number;
    public function rules()
    {
        return [
            [['coe_student_category_details_id', 'student_map_id', 'credit_point', 'CIA', 'ESE', 'total', 'grade_point', 'year', 'month', 'stu_status_id', 'created_by', 'updated_by'], 'integer'],
            [['old_clg_reg_no', 'subject_code', 'subject_name', 'result', 'grade_name', 'year_of_passing', 'created_at', 'updated_at','register_number'], 'safe'],
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
        $query = StudentCategoryDetails::find();
        $query->joinWith(['studentDetails']);
        // add conditions that should always apply here
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false, 
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'coe_student_category_details_id' => $this->coe_student_category_details_id,
            'student_map_id' => $this->student_map_id,
            'credit_point' => $this->credit_point,
            'CIA' => $this->CIA,
            'ESE' => $this->ESE,
            'total' => $this->total,
            'grade_point' => $this->grade_point,
            'year' => $this->year,
            'month' => $this->month,
            'stu_status_id' => $this->stu_status_id,
            //'created_by' => $this->created_by,
            //'created_at' => $this->created_at,
            //'updated_by' => $this->updated_by,
            //'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'old_clg_reg_no', $this->old_clg_reg_no])
                ->andFilterWhere(['like', 'register_number', $this->register_number])
            ->andFilterWhere(['like', 'subject_code', $this->subject_code])
            ->andFilterWhere(['like', 'subject_name', $this->subject_name])
            ->andFilterWhere(['like', 'result', $this->result])
            ->andFilterWhere(['like', 'grade_name', $this->grade_name])
            ->andFilterWhere(['like', 'year_of_passing', $this->year_of_passing]);

        return $dataProvider;
    }
}
