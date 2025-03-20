<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Categorytype */

$this->title = $model->category_type;
$this->params['breadcrumbs'][] = ['label' => 'Categorytypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categorytype-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_category_type_id], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'coe_category_type_id',
            'category_id',
            //[
                //'label' => Yii::t('app','Course'),
                //'attribute' => 'category_id',
                //'value' => 'categoryName.category_name',
            //],
            'category_type',
            'description',
            /*'created_by',
            'created_at',
            'updated_by',
            'updated_at',*/
            [
                'attribute' => 'created_at',
                'value' => Yii::$app->formatter->asDateTime($model->created_at),
            ],
            [
                'attribute' => 'created_by',
                'value' => Yii::$app->user->identity->username,        
            ],
            [
                'attribute' => 'updated_at',
                'value' => ($model->updated_at == null) ? " - ": Yii::$app->formatter->asDateTime($model->updated_at),
            ],
            [
                'attribute' => 'updated_by',
                'value' => ($model->updated_by == null) ? " - ":Yii::$app->user->identity->username,
            ],
        ],
    ]) ?>

</div>
