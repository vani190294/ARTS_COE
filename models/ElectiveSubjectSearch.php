<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ElectiveSubject;

/**
 * ElectiveSubjectSearch represents the model behind the search form about `app\models\ElectiveSubject`.
 */
class ElectiveSubjectSearch extends ElectiveSubject
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['coe_elective_id', 'external_mark', 'internal_mark', 'coe_batch_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'coe_elective_option', 'coe_ltp_id', 'created_by', 'updated_by'], 'integer'],
            //[['degree_type', 'subject_code', 'subject_name', 'remarks', 'created_at', 'updated_at'], 'safe'],
            [['subject_code'], 'safe'],
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
        //echo $params; exit;
        $query = ElectiveSubject::find();

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
        if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
        {
            $depts=Yii::$app->user->getDeptId();

            $coe_deptids = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_department WHERE  dept_map_id IN (".$depts.") AND dept_map_id!=''")->queryScalar();

            $query->Where(['OR',['=','coe_dept_id',Yii::$app->user->getDeptId()],['=','coe_dept_id',$coe_deptids]]);

        }


        if($_SESSION['electsubject']==203 && $_SESSION['minor']==1)
        {
            $query->andWhere([
                'coe_elective_option' =>$_SESSION['electsubject']
            ]);
        }
        else if($_SESSION['electsubject']==200)
        {
            $query->andWhere(['service_paper' =>1]);

            $query->andWhere(['OR',['=','coe_elective_option',200],['=','coe_elective_option',202]]);
        }
        else if($_SESSION['electsubject']==203)
        {
            $query->andWhere(['service_paper' =>1]);

            $query->andWhere(['OR',['=','coe_elective_option',200],['=','coe_elective_option',202]]);
        }
        else
        {
            $query->andWhere([
                'coe_elective_option' =>$_SESSION['electsubject']
            ]);
        }
        
        $query->andFilterWhere(['like', 'subject_code', $this->subject_code]);
        $query->OrderBy(['coe_dept_id' => SORT_DESC,'coe_regulation_id'=>SORT_DESC]);

        return $dataProvider;
    }
}
