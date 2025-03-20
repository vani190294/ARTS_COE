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

$this->title = 'Practical Exam Timetables';
$this->params['breadcrumbs'][] = $this->title;
$checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
$visible = Yii::$app->user->can("/practical-exam-timetable-new/view") || Yii::$app->user->can("/practical-exam-timetable-new/update") ? true : false;
?>

<div class="practical-exam-timetable-index">
<?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('New', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php 

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
if(Yii::$app->user->getId()==1 || Yii::$app->user->getId()==11 || Yii::$app->user->getId()==15)
        {
  echo Html::submitInput('Delete', ['class' => 'btn btn-block btn-danger','value'=>'delete', 'name'=>'delete', 'id'=>'exam_del_butt']);
}
?>
</div>
<?php ActiveForm::end(); ?>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
                'attribute' => 'batch_name',
                'value' => 'batch.batch_name',
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE),
                'attribute' => 'degree_code',
                'value' => 'degree.degree_code',
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
                'attribute' => 'batch_mapping_id',
                'value' => 'programme.programme_code',
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
                'attribute' => 'student_map_id',
                'value' => 'student.register_number',
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
                'attribute' => 'mark_type',
                'value' => 'markType.description',
            ],            
            'exam_date',
            [
                'label' =>'Session',
                'attribute' => 'exam_session',
                'value' => 'examSess.description',
            ],
            
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                    return ((Yii::$app->user->can("/practical-exam-timetable-new/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                    },
                    'update' => function ($url, $model) {
                    return ((Yii::$app->user->can("/practical-exam-timetable-new/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                    },
                    'delete' => function ($url, $model) {
                    return ((Yii::$app->user->can("/practical-exam-timetable-new/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete',
                        'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                            ,'method' => 'post'],]) : '');
                    }
                    ],
            'visible' => $visible,
            ],
            [
                'class' => 'yii\grid\CheckboxColumn', 
                 'checkboxOptions' => function ($data) {
                return ['value'=> $data['coe_prac_exam_ttable_id']];
            }],
           

        ],
    ]); ?>
<?php Pjax::end(); ?></div>
