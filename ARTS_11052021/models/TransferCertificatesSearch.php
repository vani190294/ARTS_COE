<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransferCertificates;

/**
 * TransferCertificatesSearch represents the model behind the search form about `app\models\TransferCertificates`.
 */
class TransferCertificatesSearch extends TransferCertificates
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_transfer_certificates_id', 'created_by'], 'integer'],
            [['register_number', 'name', 'parent_name', 'dob', 'nationality', 'religion', 'community', 'caste', 'admission_date', 'class_studying', 'reason', 'is_qualified', 'conduct_char', 'date_of_tc', 'date_of_app_tc', 'date_of_left', 'serial_no', 'created_at'], 'safe'],
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
        $query = TransferCertificates::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'coe_transfer_certificates_id' => $this->coe_transfer_certificates_id,
            'dob' => $this->dob,
            'admission_date' => $this->admission_date,
            'date_of_tc' => $this->date_of_tc,
            'date_of_app_tc' => $this->date_of_app_tc,
            'date_of_left' => $this->date_of_left,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'register_number', $this->register_number])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'parent_name', $this->parent_name])
            ->andFilterWhere(['like', 'nationality', $this->nationality])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'community', $this->community])
            ->andFilterWhere(['like', 'caste', $this->caste])
            ->andFilterWhere(['like', 'class_studying', $this->class_studying])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'is_qualified', $this->is_qualified])
            ->andFilterWhere(['like', 'conduct_char', $this->conduct_char])
            ->andFilterWhere(['like', 'serial_no', $this->serial_no]);

        return $dataProvider;
    }
}
