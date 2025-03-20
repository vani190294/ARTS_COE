<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Electivetodept */

$this->title = 'View';
$this->params['breadcrumbs'][] = ['label' => 'Elective to Depts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="electivetodept-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_electivetodept_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Assign Elective Subject', ['create'], ['class' => 'pull-right btn btn-success']) ?>
       
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'electivetype.category_type',
            'electivesubject.subject_code',
            'semester',
            'deptassignlist.depts',
        ],
    ]) ?>

</div>
