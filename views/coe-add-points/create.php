<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoeAddPoints */

?>
<div class="coe-add-points-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
