<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransferCertificatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transfer Certificates';
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("transfer-certificates/view") || Yii::$app->user->can("transfer-certificates/update") ? true : false; 
?>
<div class="transfer-certificates-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php Yii::$app->ShowFlashMessages->showFlashes();?>

<?php Pjax::begin(); ?>    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'register_number',
            'parent_name',
            'nationality',
            'religion',
            'admission_date',
            'conduct_char',
           /* 
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ( (Yii::$app->user->can("transfer-certificates/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("transfer-certificates/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("transfer-certificates/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],*/
             ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
