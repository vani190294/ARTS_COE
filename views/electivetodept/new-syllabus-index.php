<?php
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\widgets\ActiveForm; 
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel app\models\ElectiveSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible =Yii::$app->user->can("/elective-subject/delete-pec") || Yii::$app->user->can("/elective-subject/update-pec") ? true : false;

$this->title = 'Service Courses (New Syllabus) to Other Dept (One time Only)';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>

<div class="curriculum-subject-index">

    <h1><?= Html::encode($this->title) ?></h1>
   
    <p>
        <?= Html::a('Create', ['createcore-newsyllabus'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute' => 'coe_batch_id',
                'value' => 'batch.batch_name',
                'vAlign'=>'top',

            ],

            [
                'attribute' => 'Regulation',
                'value' => 'regulation.regulation_year',
                'vAlign'=>'top',

            ],
            
            
            'degree_type',
           
            'subject_code',
            'subject_name',
             
            [
                'attribute' => 'subject_type_id',
                'value' => 'subjecttype.category_type',

            ],
            
            [
                'attribute' => 'subject_category_type_id',
                'value' => 'subjectctype.category_type',

            ],

            
            [
                'attribute' => 'cur_vs_id',
                'value' => 'vertical.vertical_name',

            ],

            [
                'attribute' => 'coe_ltp_id',
                'value' => 'ltp.LTP',

            ],

            [
                'attribute' => 'Contact Hrs/week',
                'value' => 'ltp.contact_hrsperweek',

            ],

            [
                'attribute' => 'Credit Point',
                'value' => 'ltp.credit_point',

            ],
           
            
            'external_mark',
            'internal_mark',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{delete}',
                'buttons' => [
                    
                    'delete' => function ($url, $model) 
                    {
                         if($model->approve_status==0)
                        {
                            
                             return ((Yii::$app->user->can("/electivetodept/delete-corenewelective")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/electivetodept/delete-corenewelective','id'=>$model->coe_elective_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
