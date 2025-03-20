<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HallAllocate;

/**
 * HallAllocateSearch represents the model behind the search form about `app\models\HallAllocate`.
 */
class HallAllocateIntSearch extends HallAllocate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coe_hall_allocate_id','exam_timetable_id','hall_master_id', 'year', 'row', 'row_column', 'seat_no', 'created_by', 'updated_by'], 'integer'],
            [['month','register_number', 'created_at', 'updated_at'], 'safe'],
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
        $query = HallAllocate::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,'sort' => false, 
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'coe_hall_allocate_id' => $this->coe_hall_allocate_id,
            'hall_master_id' => $this->hall_master_id,
            'year' => $this->year,           
            'row' => $this->row,
            'row_column' => $this->row_column,
            'seat_no' => $this->seat_no,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'month', $this->month])           
            ->andFilterWhere(['like', 'register_number', $this->register_number]);

        unset($_SESSION['exportData']);
        $_SESSION['exportData'] = $dataProvider;

        return $dataProvider;
    }

    public static function getExportData()
    {
        $data = [
                'data'=>$_SESSION['exportData'],
                'fileName'=>'QP_List',
                'title'=>'QP Report',
                'exportFile'=>'@app/views/hall-allocate/printqpexcel',
            ];

    return $data;
    }

    public function actionExportExcel($model)
        {
            $data = $model::getExportData();
            $type = 'Excel';

            $file = $this->renderPartial($data['exportFile'],
                    ['model'=>$data['data'],
                     'type'=>$type,
            ]);

            $fileName = $data['fileName'].'.xls';
            $options = ['mimeType'=>'application/vnd.ms-excel'];

            return Yii::$app->excel->exportExcel($file, $fileName, $options);

        }

}
