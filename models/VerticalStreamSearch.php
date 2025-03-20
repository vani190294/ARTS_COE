<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VerticalStream;

/**
 * VerticalStreamSearch represents the model behind the search form about `app\models\VerticalStream`.
 */
class VerticalStreamSearch extends VerticalStream
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['cur_vs_id', 'coe_regulation_id', 'coe_dept_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['degree_type', 'vertical_name'], 'safe'],
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
        $query = VerticalStream::find();

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

        $query->Where(['=','vertical_type',$_SESSION['vstream']]);

        if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
        {
            $query->andWhere(['=','coe_dept_id',Yii::$app->user->getDeptId()]);
        }

         $query->andFilterWhere(['like', 'vertical_name', $this->vertical_name]);

         $query->OrderBy(['coe_regulation_id'=>SORT_ASC,'coe_dept_id' => SORT_ASC]);
         
        //echo $query->createCommand()->getrawsql(); exit;
        return $dataProvider;
    }
}
