<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\widgets\ActiveForm; 
/* @var $this yii\web\View */
/* @var $searchModel app\models\PracticalExamTimetableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Practical Student Per Batch Count';
$this->params['breadcrumbs'][] = $this->title;
$checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
$visible = Yii::$app->user->can("/practical-exam-timetable-new/delete-stu-per-batch") || Yii::$app->user->can("/practical-exam-timetable-new/update-stu-per-batch") ? true : false;
?>

<div class="practical-exam-timetable-index">
<?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <h1><?= Html::encode($this->title) ?></h1>
   
    <p>
        <?= Html::a('New', ['create-stu-per-batch'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php 
$checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());

$form = ActiveForm::begin([
'id' => 'delete-exam',
  'method' => 'POST',
  'enableAjaxValidation' => true,
                                   'fieldConfig' => [
                                                     'template' => "{label}{input}{error}",
                                                     ],
  ]); ?>
<input type="hidden" name="finalString" id="finalString">
 <div class="pull-right">
<?php
if($checkAccess=='Yes')
        {
  //echo Html::submitInput('Delete', ['class' => 'btn btn-block btn-danger','value'=>'delete', 'name'=>'delete', 'id'=>'exam_del_butt']);
}
?>
</div>
<?php ActiveForm::end(); ?>

<?php Pjax::begin(); ?>    

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
                'attribute' => 'coe_batch_id',
                'value' => 'batch.batch_name',
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
                'attribute' => 'batch_mapping_id',
                'value' => 'programme.programme_code',
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT),
                'attribute' => 'subject_map_id',
                'value' => 'subject.subject_code',
            ],
            'exam_year',
            [
                'label' =>'Month',
                'attribute' => 'exam_month',
                'value' => 'month.description',
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Type',
                'attribute' => 'exam_type',
                'value' => 'markType.description',
            ],
            'stu_per_batch_count',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{delete}',
                'buttons' => [
                   
                    'update' => function ($url, $model) {

                    return ((Yii::$app->user->can("/practical-exam-timetable-new/update-stu-per-batch")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', ['/practical-exam-timetable-new/update-stu-per-batch','id'=>$model->coe_spb_id], ['title' => 'Update']) : '');
                    },
                    'delete' => function ($url, $model) {
                    return ((Yii::$app->user->can("/practical-exam-timetable-new/delete-stu-per-batch")) ? Html::a('<span class="fa fa-ban increase_size"></span>', ['/practical-exam-timetable-new/delete-stu-per-batch','id'=>$model->coe_spb_id], ['title' => 'Delete',
                        'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                            ,'method' => 'post'],]) : '');
                    }
                    ],
            'visible' => $visible,
            ],
            // [
            //     'class' => 'yii\grid\CheckboxColumn', 
            //      'checkboxOptions' => function ($data) {
            //     return ['value'=> $data['coe_prac_exam_ttable_id']];
            // }],
           

        ],
    ]); ?>
<?php Pjax::end(); ?></div>
