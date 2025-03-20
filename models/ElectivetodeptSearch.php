<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Electivetodept;

/**
 * ElectivetodeptSearch represents the model behind the search form about `app\models\Electivetodept`.
 */
class ElectivetodeptSearch extends Electivetodept
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['coe_elective_option','coe_subject_id','coe_electivetodept_id', 'semester', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at','subject_code'], 'safe'],//'coe_dept_ids', 
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
        $query = Electivetodept::find();

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
            $query->andWhere(['=','coe_dept_id',Yii::$app->user->getDeptId()]);
        }

        if($_SESSION['electiveoption']=='Exist')
        {

           $query->andWhere(['IN', 'subject_type_new', ['NEW','EXIST']]);
        }
        
        if($_SESSION['electiveoption']=='New')
        {

           $query->andWhere(['IN', 'subject_type_new', 'NEWSYLLABUS']);
        }

        $query->andFilterWhere(['like', 'subject_code', $this->subject_code]);

        $query->OrderBY(['coe_regulation_id'=>SORT_DESC,'coe_electivetodept_id'=>SORT_DESC]);

        return $dataProvider;
    }
}
