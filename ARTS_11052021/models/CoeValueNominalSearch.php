<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CoeValueNominal;

/**
 * CoeValueNominalSearch represents the model behind the search form about `app\models\CoeValueNominal`.
 */
class CoeValueNominalSearch extends CoeValueNominal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_nominal_val_id', 'course_batch_mapping_id', 'coe_student_id', 'coe_subjects_id', 'semester', 'created_by', 'updated_by'], 'integer'],
            [['section_name', 'created_at', 'updated_at'], 'safe'],
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
        $query = CoeValueNominal::find();

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
            'coe_nominal_val_id' => $this->coe_nominal_val_id,
            'course_batch_mapping_id' => $this->course_batch_mapping_id,
            'coe_student_id' => $this->coe_student_id,
            'coe_subjects_id' => $this->coe_subjects_id,
            'semester' => $this->semester,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'section_name', $this->section_name]);

        return $dataProvider;
    }
}
