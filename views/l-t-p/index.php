<?php
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\widgets\ActiveForm; 
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel app\models\LTPSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible = Yii::$app->user->can("/ltp/update") ? true : false;

$this->title = 'LTP Details';
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 

<div class="ltp-index">
     <h1><?= Html::encode($this->title) ?></h1>
     <div class="col-lg-12">
         <div class="col-lg-6">
           
           <p >
                <?= Html::a('Create LTP', ['create'], ['class' => 'btn btn-success']) ?>
           </p>
        </div>
        <div class="col-lg-6">
            <br>
            <p>
                <?= Html::a('Create LTP From Existing', ['create-existing'], ['class' => 'btn btn-success pull-right']) ?>
            </p>
        </div>

    </div>  <br><br><br><br>
<div class="box box-success">
<div class="box-body">
    
    <div class="col-lg-12">
         <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'coe_batch_id',
                'value' => 'batch.batch_name',
                'vAlign'=>'top',

            ],

            [
                'attribute' => 'Regulation',
                'value' => 'regulation.regulation_year',
                'vAlign'=>'top',

            ],
             
            'L',
            'T',
            'P',
             'contact_hrsperweek',
             'credit_point',
            [
                'attribute' => 'subject_type_id',
                'value' => 'subjecttype.category_type',
                'vAlign'=>'middle',

            ],
            
            [
                'attribute' => 'subject_category_type_id',
                'value' => 'subjectctype.category_type',
                'vAlign'=>'middle',

            ],
           

             [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}{delete}',
                'buttons' => [
                    
                    'update' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/ltp/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        }
                    },
                     'delete' => function ($url, $model) 
                     {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/ltp/delete")) ? Html::a('<span class="fa fa-remove increase_size"></span>', $url, ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>

    </div>
</div>
</div>
</div>