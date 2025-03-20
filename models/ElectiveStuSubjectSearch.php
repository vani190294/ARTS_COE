<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ElectiveStuSubject;

/**
 * ElectiveStuSubjectSearch represents the model behind the search form about `app\models\ElectiveStuSubject`.
 */
class ElectiveStuSubjectSearch extends ElectiveStuSubject
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['cur_erss_id', 'cur_ers_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'approve_status', 'created_by', 'updated_by'], 'integer'],
           // [['degree_type', 'coe_elective_option', 'elective_paper', 'subject_code', 'created_at', 'updated_at'], 'safe'],

            [['coe_regulation_id','degree_type','coe_dept_id','coe_elective_option','semester','subject_code'], 'safe'],
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
        $query = ElectiveStuSubject::find();
        $query->joinWith(['deptProgramme','deptRegulation','electivetype']);

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
            ])->andWhere(['=','cur_elective_stu_subject.coe_dept_id',Yii::$app->user->getDeptId()]);
        }        

        if($_SESSION['elecctive_nominal']=='MBA')
        {
            $query->andFilterWhere(['like', 'cur_elective_stu_subject.degree_type', 'MBA']);
            $query->andFilterWhere(['=', 'cur_elective_stu_subject.coe_dept_id', 26]);
        }
        else
        {
            $query->andFilterWhere(['!=', 'cur_elective_stu_subject.coe_dept_id', 26]);
        }

        $query->andFilterWhere(['like', 'category_type', $this->coe_elective_option]);
        $query->andFilterWhere(['like', 'cur_elective_stu_subject.degree_type', $this->degree_type]);
        $query->andFilterWhere(['like', 'regulation_year', $this->coe_regulation_id]);
        $query->andFilterWhere(['like', 'dept_code', $this->coe_dept_id]);
        $query->andFilterWhere(['like', 'semester', $this->semester]);
        $query->andFilterWhere(['like', 'subject_code', $this->subject_code])->orderBy(['cur_erss_id' => SORT_DESC]);

        return $dataProvider;
    }
}
