
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\Models\ElectiveCount */

$this->title = 'Update EEC Count (Self Dept.) ' ;
$this->params['breadcrumbs'][] = ['label' => 'Elective Counts', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->cur_ec_id, 'url' => ['view', 'id' => $model->cur_ec_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="elective-count-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
