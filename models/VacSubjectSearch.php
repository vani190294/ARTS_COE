<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VacSubject;

/**
 * VacSubjectSearch represents the model behind the search form about `app\models\VacSubject`.
 */
class VacSubjectSearch extends VacSubject
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_vac_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'subject_type_id', 'subject_category_type_id', 'approve_status', 'created_by', 'updated_by'], 'integer'],
            [['degree_type', 'subject_code', 'subject_name', 'created_at', 'updated_at'], 'safe'],
            [['course_hours'], 'number'],
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
        $query = VacSubject::find();

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
        //     'coe_vac_id' => $this->coe_vac_id,
        //     'coe_regulation_id' => $this->coe_regulation_id,
        //     'coe_dept_id' => $this->coe_dept_id,
        //     'semester' => $this->semester,
        //     'course_hours' => $this->course_hours,
        //     'subject_type_id' => $this->subject_type_id,
        //     'subject_category_type_id' => $this->subject_category_type_id,
        //     'approve_status' => $this->approve_status,
        //     'created_by' => $this->created_by,
        //     'created_at' => $this->created_at,
        //     'updated_by' => $this->updated_by,
        //     'updated_at' => $this->updated_at,
        // ]);

        // $query->andFilterWhere(['like', 'degree_type', $this->degree_type])
        //     ->andFilterWhere(['like', 'subject_code', $this->subject_code])
        //     ->andFilterWhere(['like', 'subject_name', $this->subject_name]);

        if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
        {
            $depts=Yii::$app->user->getDeptId();

            $coe_deptids = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_department WHERE  dept_map_id IN (".$depts.") AND dept_map_id!=''")->queryScalar();

            $query->Where(['OR',['=','coe_dept_id',Yii::$app->user->getDeptId()],['=','coe_dept_id',$coe_deptids]]);
          
        }
        else
        {
            // $query->Where([
            //     'subject_type' =>$params
            // ]);
        }

        return $dataProvider;
    }
}
