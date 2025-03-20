<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CurSyllabus;

/**
 * CurSyllabusSearch represents the model behind the search form about `app\models\CurSyllabus`.
 */
class CurSyllabusSearch extends CurSyllabus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_syllabus_id', 'subject_code', 'subject_type', 'web_reference1', 'web_reference2', 'web_reference3', 'online_reference1', 'online_reference2', 'approve_status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['course_objectives1', 'course_objectives2', 'course_objectives3', 'course_objectives4', 'course_objectives5', 'course_objectives6', 'course_outcomes1', 'course_outcomes2', 'course_outcomes3', 'course_outcomes4', 'course_outcomes5', 'course_outcomes6', 'rpt1', 'rpt2', 'rpt3', 'rpt4', 'rpt5', 'rpt6', 'cource_content_mod1', 'cource_content_mod2', 'cource_content_mod3', 'module_title1', 'module_title2', 'module_title3', 'text_book1', 'text_book2', 'text_book3', 'reference_book1', 'reference_book2', 'reference_book3'], 'safe'],
            [['module_hr1', 'module_hr2', 'module_hr3'], 'number'],
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
        $query = CurSyllabus::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,'sort' => false, 
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
        {
            $depts=Yii::$app->user->getDeptId();

            $coe_deptids = Yii::$app->db->createCommand("SELECT coe_dept_id FROM cur_department WHERE  dept_map_id IN (".$depts.") AND dept_map_id!=''")->queryScalar();

            $query->Where(['OR',['=','coe_dept_id',Yii::$app->user->getDeptId()],['=','coe_dept_id',$coe_deptids]]);
            
            // $query->Where([
            //     //'subject_type' =>$params
            // ])->andWhere(['=','coe_dept_id',Yii::$app->user->getDeptId()]);
        }
        else
        {
            if($_SESSION['servicesubject']==3)
            {
                $query->Where(['=','coe_dept_id',8]);
            }
             
        }

        $query->andFilterWhere(['like', 'subject_code', $this->subject_code]);

        return $dataProvider;
    }
}
