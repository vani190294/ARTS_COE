<?php
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubjectsMappingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT);
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("/subjects-mapping/view") || Yii::$app->user->can("/subjects-mapping/update") ? true : false; 

?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<div class="subjects-mapping-index">
<?php $form = ActiveForm::begin([
  'id' => 'delete-subs',
  'method' => 'POST',
  'enableAjaxValidation' => true,
    'fieldConfig' => [
      'template' => "{label}{input}{error}",
    ],
  ]); ?>



<?php 
$gridColumns = [
          //['class' => 'yii\grid\SerialColumn'],

            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
                  'attribute' => 'batch_name',
                  'value' => 'coeBatchName.batch_name',
                  'vAlign'=>'middle','hAlign'=>'center',
                  'format'=>'raw',
            ],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE),
                  'attribute' => 'degree_code',
                  'value' => 'coeDegreeName.degree_code',
                  'vAlign'=>'middle','hAlign'=>'center',
                  'format'=>'raw',
            ],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
                  'attribute' => 'programme_code',
                  'value' => 'coeProgrammeName.programme_code',
                  'vAlign'=>'middle',
                  'format'=>'raw',
                  'hAlign'=>'center',
            ],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code",
                  'attribute' => 'subject_code',
                  'value' => 'coeSubjects.subject_code',
                  'vAlign'=>'middle',
                  'format'=>'raw',
                  'hAlign'=>'center',
            ],
            [
                  'label' => "Sem",
                  'attribute' => 'semester',
                  'value' => 'semester',
                  'vAlign'=>'middle',
                  'format'=>'raw',
                  'hAlign'=>'center',
            ],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Name",
                  'attribute' => 'subject_name',
                  'value' => 'coeSubjects.subject_name',
                  'vAlign'=>'middle',
                  'format'=>'raw',
                  'hAlign'=>'center',
            ],
            [
                  'label' => "CIA MAX",
                  'attribute' => 'CIA_max',
                  'value' => 'coeSubjects.CIA_max',
                  'vAlign'=>'middle',
                  'format'=>'raw',
                  'hAlign'=>'center',
            ],
            [
                  'label' => "ESE MIN",
                  'attribute' => 'ESE_min',
                  'value' => 'coeSubjects.ESE_min',
                  'vAlign'=>'middle',
                  'format'=>'raw',
                  'hAlign'=>'center',
            ],
            [
                  'label' => "ESE MAX",
                  'attribute' => 'ESE_max',
                  'value' => 'coeSubjects.ESE_max',
                  'vAlign'=>'middle',
                  'format'=>'raw',
                  'hAlign'=>'center',
            ],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PAPER_TYPE),
                  'attribute' => 'paper_type_id',
                  'value' => 'paperTypes.category_type',
                  'vAlign'=>'middle',
                  'format'=>'raw',
                  'hAlign'=>'center',
            ],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT_TYPE),
                  'attribute' => 'subject_type_id',
                  'value' => 'subjectTypes.category_type',
                  'vAlign'=>'middle',
                  'format'=>'raw',
                  'hAlign'=>'center',
            ],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME_TYPE),
                  'attribute' => 'course_type_id',
                  'value' => 'courseTypes.category_type',
                  'vAlign'=>'middle',
                  'format'=>'raw',
                  'hAlign'=>'center',
            ],

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/subjects-mapping/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/subjects-mapping/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/subjects-mapping/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
           
];

?>

<div class="subjects-index">

     
    <div class="row">
        <div class="col-xs-12">

            <div class="col-lg-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 20px !important;">
           
            
            <div class="col-xs-2 pull-right left-padding">    
                <?php 
                $fullExportMenu = ExportMenu::widget([
                  'dataProvider' => $dataProvider,
                  'columns' => $gridColumns,
                  'target' => ExportMenu::TARGET_BLANK,
                  'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Info",
                  'fontAwesome' => true,
                  'asDropdown' => false, // this is important for this case so we just need to get a HTML list    
                  'dropdownOptions' => [
                      'label' => '<i class="glyphicon glyphicon-export"></i> Full'
                  ],
                  'exportConfig'=>[
                            ExportMenu::FORMAT_TEXT => false,
                            ExportMenu::FORMAT_HTML => false,

                            ExportMenu::FORMAT_CSV => [
                            'iconOptions' => ['class' => 'text-primary'],
                                'linkOptions' => [],
                                'mime' => 'application/csv',
                                'extension' => 'csv',
                                'writer' => 'CSV'
                            ],
                            ExportMenu::FORMAT_EXCEL => [
                                'font' => [
                                    'bold' => true,
                                    'color' => [
                                        'argb' => 'FFFFFFFF',
                                    ],
                                ],
                                'fill' => [
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => [
                                        'argb' => '#F0f0f0',
                                    ],
                                ],
                            ],
                            ExportMenu::FORMAT_EXCEL_X => [
                                'font' => [
                                    'bold' => true,
                                    'color' => [
                                        'argb' => 'FFFFFFFF',
                                    ],
                                ],
                                'fill' => [
                                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                    'startcolor' => [
                                        'argb' => 'FFA0A0A0',
                                    ],
                                    'endcolor' => [
                                        'argb' => 'FFFFFFFF',
                                    ],
                                ],
                            ],
                        ],
              ]);

               
                ?>
            </div> 
          </div>
            
            
    </div>

    <div class="col-xs-12">
    <div class="col-lg-12 col-sm-12 col-xs-12 left-padding" style="padding-top: 20px !important;">
    
<?php 

    echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'filterModel' => $searchModel,
    'containerOptions' => ['style' => 'overflow: auto'], 
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'toggleDataOptions' => ['minCount' => 30],
        'resizableColumns'=>true,
        'floatHeaderOptions'=>['scrollingTop'=>'50'],
        'hover'=>true,
        'pjax' => true,
        'pjaxSettings' => ['neverTimeout'=>true,

        'options' => ['id' => 'kv-pjax-container']],
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Library</h3>',
    ],
    'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Data </h3>',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), ['subjects/create'], ['class' => 'btn btn-success'])." &nbsp; ".Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset', ['index'], ['class' => 'btn btn-info']),
        ],
      // set a label for default menu
      'export' => [
          'label' => 'Page',
          'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Reports",
          'fontAwesome' => true,
          'class' => 'hide_page_label',
         
      ],
    // the toolbar setting is default
    'toolbar' => [
        '{export}',
        ['content'=>
            Html::a('<i class="glyphicon glyphicon-plus"></i> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), ['subjects/create'], [
                    'data-pjax'=>0, 
                    'class'=>'btn btn-success',
                    'title'=>'Add '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 
                ]) . ' '.
          Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['subjects-mapping/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
        ],
    ],
    // configure your GRID inbuilt export dropdown to include additional items
    'export' => [
        'fontAwesome' => true,
        'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Reports",
        'itemsAfter'=> [
            '<li role="presentation" class="divider"></li>',
            '<li class="dropdown-header">Export All Data</li>',
            $fullExportMenu
        ]
    ],
    'persistResize' => false,
]);

?>

</div>
    </div>
</div>

