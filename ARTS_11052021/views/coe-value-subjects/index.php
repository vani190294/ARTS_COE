<?php

use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;


use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\helpers\Html;


$this->title = "Value Added Subject";
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("/coe-value-subjects/view") || Yii::$app->user->can("/coe-value-subjects/update") ? true : false; 


$gridColumns = [
    //['class' => 'yii\grid\SerialColumn'],

            
            // 
            //       'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
            //       'attribute' => 'batch_name',
            //       'value' => 'coeBatchName.batch_name',
            // ],
            // [
            //       'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE),
            //       'attribute' => 'degree_code',
            //       'value' => 'coeDegreeName.degree_code',
            // ],
            // [
            //       'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
            //       'attribute' => 'programme_code',
            //       'value' => 'coeProgrammeName.programme_code',
            // ],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code',
                  'attribute' => 'subject_code',
                  'value' => 'subject_code',
                  'vAlign'=>'middle',
                  'format'=>'raw',
            ],
            
            'subject_name',
            // [
            //       'label' => 'Sem',
            //       'attribute' => 'semester',
            //       'value' => 'semester',
            //       'vAlign'=>'middle',
            //       'format'=>'raw',
            // ],
            //'CIA_min',
            //'CIA_max',
            //'ESE_min',
            //'ESE_max',
            [
              'label' => 'Min Pass',
              'attribute' => 'total_minimum_pass',
              'value' => 'total_minimum_pass',
            ],
            [
              'label' => 'Total Marks',
              'attribute' => 'end_semester_exam_value_mark',
              'value' => 'end_semester_exam_value_mark',
            ],
            
            'credit_points',
            //'end_semester_exam_value_mark',
            'subject_fee',

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/coe-value-subjects/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/coe-value-subjects/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/coe-value-subjects/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
];

echo Dialog::widget();
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?>
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
                    'fontAwesome' => true,
                    'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Report",
                    'pjaxContainerId' => 'kv-pjax-container',
                    'dropdownOptions' => [
                        'label' => 'Full',
                        'class' => 'btn btn-default',
                        'itemsBefore' => [
                            '<li class="dropdown-header">Export All Data</li>',
                        ],
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

        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Data </h3>',
        ],
        // set a label for default menu
        'export' => [
            'label' => 'Page',
            'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Reports",
            'fontAwesome' => true,
            'class' => 'hide_page_label',
           
        ],
        // your toolbar can include the additional full export menu

        'toolbar' => [
            '{export}',
            $fullExportMenu,
            ['content'=>

                Html::a('<i class="glyphicon glyphicon-plus"></i> '.'coe-value-subjects', ['create'], [
                    'data-pjax'=>0, 
                    'class'=>'btn btn-success',
                    'title'=>'Add '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 
                ]). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['coe-value-subjects/index'], [
                    'data-pjax'=>0, 
                    'class' => 'btn btn-default', 
                    'title'=>'Reset'
                ])
            ],
        ]
    ]);

?>

</div>
    </div>
</div>
