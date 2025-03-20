<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Classifications */

$this->title =$model->regulation_year."-".$model->classification_text;
$this->params['breadcrumbs'][] = ['label' => 'Classifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classifications-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_classifications_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           
            'regulation_year',
            'percentage_from',
            'percentage_to',
            'grade_name',
            'classification_text',
           
        ],
    ]) ?>

</div>
