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
/* @var $searchModel app\models\SubjectPrefixSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible = Yii::$app->user->can("/subject-prefix/update") ? true : false;
$this->title = 'Subject Prefix';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<div class="subject-prefix-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="col-lg-12">
    <p class="pull-right">
        <?= Html::a('Create Subject Prefix', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    </div>
    <div class="col-lg-12">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'coe_dept_id',
                'value' => 'dept.dept_code',
                'vAlign'=>'middle',

            ],
            'prefix_name',

           [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}',
                'buttons' => [
                    
                    'update' => function ($url, $model) {
                    return ((Yii::$app->user->can("/subject-prefix/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                    },
                    
                    ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
</div>
