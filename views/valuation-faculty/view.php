<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ValuationFaculty */

$this->title = $model->faculty_name;
$this->params['breadcrumbs'][] = ['label' => 'Valuation Faculty', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="valuation-faculty-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_val_faculty_id], ['class' => 'btn btn-primary']) ?>       
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'faculty_name',            
            'phone_no',
            'email',            
            'college_code',
            'faculty_designation',
            'faculty_board',
            'faculty_mode',
            'faculty_experience',
            'bank_accno',
            'bank_name',
            'bank_branch',
            'bank_ifsc',
            'out_session',
        ],
    ]) ?>

</div>
