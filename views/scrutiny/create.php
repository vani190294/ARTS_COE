<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Scrutiny */
$this->title = 'Create Scrutiny';
$this->params['breadcrumbs'][] = ['label' => 'Scrutinies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scrutiny-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
