<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CurriculumSubject;

/**
 * CurriculumSubjectSearch represents the model behind the search form about `app\models\CurriculumSubject`.
 */
class CurriculumSubjectSearch extends CurriculumSubject
{
    /**
     * @inheritdoc
     */

     public $LTP,$regulation_year;
    public function rules()
    {
        return [
            //[['coe_cur_id', 'coe_batch_id', 'coe_regulation_id', 'coe_dept_id', 'semester', 'coe_ltp_id', 'external_mark', 'internal_mark', 'created_by', 'updated_by'], 'integer'],
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
        $query = CurriculumSubject::find();
        $query->joinWith(['regulation']);
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
        if($_SESSION['coresubject']!='otherindex' && $_SESSION['coresubject']!='All' && $_SESSION['coresubject']!='mc' && $_SESSION['coresubject']!='ac')
        {
            $depts=Yii::$app->user->getDeptId();

             if(!empty($depts))
            {
                $coe_deptids = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_department WHERE  dept_map_id IN (".$depts.") AND dept_map_id!=''")->queryScalar();

                $query->Where(['OR',['=','coe_dept_id',Yii::$app->user->getDeptId()],['=','coe_dept_id',$coe_deptids]]);

                $query->andFilterWhere(['<>','semester',0]);
            }
            else
            {
                $query->andFilterWhere(['=','coe_dept_id',8]);
                $query->andFilterWhere(['<>','semester',0]);
            }
        }
        else if($_SESSION['coresubject']=='otherindex')
        {            
            $depts=Yii::$app->user->getDeptId();
            //echo $_SESSION['coresubject']; exit;
            //$coe_deptids = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_department WHERE  dept_map_id IN (".$depts.") AND dept_map_id!=''")->queryScalar();

            //$query->Where(['OR',['=','coe_dept_id',Yii::$app->user->getDeptId()],['=','coe_dept_id',$coe_deptids]]);
            $query->Where(['=','coe_dept_id',0]);
        }
        else if($_SESSION['coresubject']=='mc')
        {
            $query->Where(['=','coe_dept_id',8])->andFilterWhere(['=','semester',0]);
        }
        else if($_SESSION['coresubject']=='ac')
        {
            $depts=Yii::$app->user->getDeptId();
            if($depts==0)
            {
                $query->Where(['=','degree_type','PG']);

                $query->andFilterWhere(['!=','coe_dept_id',8])->andFilterWhere(['=','semester',0]);
            }
            else
            {
                $query->Where(['=','degree_type','PG']);
                $coe_deptids = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_department WHERE  dept_map_id IN (".$depts.") AND dept_map_id!=''")->queryScalar();

                $query->Where(['OR',['=','coe_dept_id',Yii::$app->user->getDeptId()],['=','coe_dept_id',$coe_deptids]]);

                $query->andFilterWhere(['!=','coe_dept_id',8])->andFilterWhere(['=','semester',0]);
            }
            
        }
        else
        {
            $depts=Yii::$app->user->getDeptId();

            if(!empty($depts))
            {
                $coe_deptids = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_department WHERE  dept_map_id IN (".$depts.") AND dept_map_id!=''")->queryScalar();

                $query->Where(['OR',['=','coe_dept_id',Yii::$app->user->getDeptId()],['=','coe_dept_id',$coe_deptids]]);

                $query->andWhere(['!=','coe_dept_id',8]);
            }
            else
            {
                $query->andFilterWhere(['<>','semester',0]);
            }
            
        }

        if($_SESSION['coresubject']=='otherindex')
        {
            $query->andFilterWhere(['like', 'subject_code', 'ip']);
        }

        //$query->andFilterWhere(['like', 'regulation.regulation_year', $this->coe_regulation_id]);
        // $query->andFilterWhere(['like', 'degree_type', $this->degree_type]);
         $query->andFilterWhere(['like', 'subject_code', $this->subject_code]);
        // //$query->andFilterWhere(['like', 'ltp.LTP', $this->coe_ltp_id]);
        // $query->andFilterWhere(['like', 'external_mark', $this->external_mark]);
        // $query->andFilterWhere(['like', 'internal_mark', $this->internal_mark]);
        $query->OrderBy(['coe_dept_id' => SORT_DESC,'coe_regulation_id'=>SORT_DESC,'semester' => SORT_ASC]);
        //echo $query->createCommand()->getrawsql(); exit;
        return $dataProvider;
    }
}
