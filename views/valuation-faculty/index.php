<?php

use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\widgets\ActiveForm; 
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel app\models\ValuationFacultySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Valuation Faculty';
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("/valuation-faculty/view") || Yii::$app->user->can("/valuation-faculty/update") ? true : false; ?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 

<div class="valuation-faculty-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
<?php   $gridColumns = [
    //['class' => 'yii\grid\SerialColumn'],
    [
        'class' => 'kartik\grid\SerialColumn',
        'contentOptions' => ['class' => 'kartik-sheet-style'],
        'width' => '36px',
        'header' => 'Sno',
        
    ],
    [
        'attribute' => 'faculty_name',
        'value' => 'faculty_name',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],
     [
        'attribute' => 'phone_no',
        'value' => 'phone_no',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],
     [
        'attribute' => 'email',
        'value' => 'email',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],
    [
        'attribute' => 'faculty_designation',
        'value' => 'faculty_designation',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],
    [
        'attribute' => 'faculty_experience',
        'value' => 'faculty_experience',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],
    [
        'attribute' => 'college_code',
        'value' => 'college_code',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],

    [
        'attribute' => 'faculty_board',
        'value' => 'faculty_board',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],

    // [
    //     'attribute' => 'faculty_mode',
    //     'value' => 'faculty_mode',
    //     'vAlign'=>'middle',
    //     'width'=>'190px',
    //     'format'=>'raw',
    //     'pageSummary' => true,

    // ],

    [
        'attribute' => 'bank_accno',
        'value' => 'bank_accno',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],

     [
        'attribute' => 'bank_name',
        'value' => 'bank_name',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],

     [
         'attribute' => 'bank_ifsc',
        'value' => 'bank_ifsc',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],

     [
        'class' => 'app\components\CustomActionColumn',
        'header'=> 'Actions',
        'template' => '{view}{update}',
        'buttons' => [
            'view' => function ($url, $model) {
            return ((Yii::$app->user->can("/valuation-faculty/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
            },
            'update' => function ($url, $model) {
            return ((Yii::$app->user->can("/valuation-faculty/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
            },
            ],
    'visible' => $visible,
    ],

];

?>

 <div class="row">
        <div class="col-xs-12">

            <div class="col-lg-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 20px !important;" >
           
            
            <div class="col-xs-2 pull-right left-padding">    
                <?php 
                $fullExportMenu = ExportMenu::widget([
                  'dataProvider' => $dataProvider,
                  'columns' => $gridColumns,
                  'target' => ExportMenu::TARGET_BLANK,                  
                  'filename' => "Student Info",
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

    <div class="col-lg-12 col-sm-10 col-xs-10 left-padding" style="padding-top: 20px !important;">

<?php
$userid=Yii::$app->user->getId();


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
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i>Valuation Faculty </h3>',
        ],
      // set a label for default menu
      'export' => [
          'label' => 'Page',
          'filename' => "Faculty Reports",
          'fontAwesome' => true,
          'class' => 'hide_page_label',
         
      ],
    // the toolbar setting is default   


    'toolbar' => [        
        '{export}',
        ['content'=>
            Html::a('<i class="glyphicon glyphicon-plus"></i>Create Faculty', ['create'], [
                    'data-pjax'=>0, 
                    'class'=>'btn btn-success',
                    'title'=>'Add Faculty', 
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
               
        ],

    ],
    // configure your GRID inbuilt export dropdown to include additional items
    'export' => [
        'fontAwesome' => true,
            'filename' => "Faculty Reports",
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

</div>
