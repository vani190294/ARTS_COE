<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Student;
/**
 * StudentSearch represents the model behind the search form about `app\models\Student`.
 */
class StudentSearch extends Student 
{
    /**
     * @inheritdoc
     */
    public $batch_name,$degree_code,$programme_code,$section_name,$coe_batch_id;
    public function rules()
    {
        return [
            [['coe_student_id','coe_batch_id'], 'integer'],
            [['name', 'register_number', 'gender', 'dob', 'religion', 'nationality', 'caste', 'sub_caste', 'bloodgroup', 'email_id', 'admission_year', 'admission_date', 'mobile_no', 'admission_status','student_status','section_name','programme_code','batch_name','degree_code'], 'safe'],
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

        $query = Student::find()->where(['student_status' => 'Active'])->orderBy('register_number desc');
        $query->joinWith(['coeBatch','coeDegree','coeProgramme']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'sort' =>false,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'coe_student_id' => $this->coe_student_id,
            'dob' => $this->dob,
            'admission_year' => $this->admission_year,
            'admission_date' => $this->admission_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            
            ->andFilterWhere(['like', 'coe_student_mapping.section_name', $this->section_name])
            ->andFilterWhere(['like', 'programme_code', $this->programme_code])
            ->andFilterWhere(['like', 'degree_code', $this->degree_code])
            ->andFilterWhere(['like', 'batch_name', $this->batch_name])
            ->andFilterWhere(['like', 'register_number', $this->register_number])            
            ->andFilterWhere(['like', 'email_id', $this->email_id])
            ->andFilterWhere(['like', 'mobile_no', $this->mobile_no]);
             
        return $dataProvider;
    }
}
