<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\widgets\ActiveForm; 
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExamTimetableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Timetables';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<div class="exam-timetable-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    </p>
<?php $visible = Yii::$app->user->can("/exam-timetable/view") || Yii::$app->user->can("/exam-timetable/update") ? true : false; ?>  


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
  echo Html::submitInput('Delete', ['class' => 'btn btn-block btn-danger','value'=>'delete', 'name'=>'delete', 'id'=>'exam_del_butt']);
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

            'exam_year',
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
                'attribute' => 'batch_name',
                'value' => 'coeBatchName.batch_name',
                    
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE),
                'attribute' => 'degree_code',
                'value' => 'coeDegreeName.degree_code',
                    
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
                'attribute' => 'programme_code',
                'value' => 'coeProgrammeName.programme_code',
                    
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Month",
                'attribute' => 'exam_month',
                'value' => 'examMonthRel.category_type',
                    
            ],
            [
                'label' =>" Semester",
                'attribute' => 'semester',
                'value' => 'subjectMapping.semester',
                    
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code",
                'attribute' => 'subject_code',
                'value' => 'wholeSemester.subject_code',
                    
            ],
            'exam_date:date',   
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION),
                'attribute' => 'exam_session',
                'value' => 'examSessionRel.category_type',
                    
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE),
                'attribute' => 'exam_type',
                'value' => 'examTypeRel.category_type',
                    
            ],
                   
            //'exam_term',
            'qp_code',
            'cover_number',          

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/exam-timetable/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/exam-timetable/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/exam-timetable/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
            [
                'class' => 'yii\grid\CheckboxColumn', 
                 'checkboxOptions' => function ($data) {
                return ['value'=> $data['coe_exam_timetable_id']];
            }],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

</div>