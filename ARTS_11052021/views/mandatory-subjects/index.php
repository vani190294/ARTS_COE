<?php

use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\helpers\Html;


$visible = Yii::$app->user->can("/mandatory-subjects/view") || Yii::$app->user->can("/mandatory-subjects/update") ? true : false;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MandatorySubjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT);
$this->params['breadcrumbs'][] = $this->title;
echo Dialog::widget();

$gridColumns = [
    //['class' => 'yii\grid\SerialColumn'],
    [
        'class' => 'kartik\grid\SerialColumn',
        'contentOptions' => ['class' => 'kartik-sheet-style'],
        'width' => '36px',
        'header' => 'Sno',
    ],
    [
        'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
        'attribute' => 'man_batch_id',
        'value' => 'manBatch.batch_name',
        'pageSummary' => true,
    ],
    [
        'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE),
        'attribute' => 'batch_mapping_id',
        'value' => 'coeDegree.degree_code',
        'pageSummary' => true,
    ],
    [
        'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
        'attribute' => 'batch_mapping_id',
        'value' => 'coeProgramme.programme_code',
        'pageSummary' => true,
    ],
    [
        'label' =>"Semester",
        'attribute' => 'semester',
        'value' => 'semester',
        'pageSummary' => true,
    ],
    [
        'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE",
        'attribute' => 'subject_code',
        'value' => 'subject_code',
        'pageSummary' => true,
    ],
    [
        'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' NAME',
        'attribute' =>'subject_name',
        'value' => 'subject_name',
        'pageSummary' => true,
    ],
    [
        'label' =>"CIA MAX",
        'attribute' => 'CIA_max',
        'value' => 'CIA_max',
        'pageSummary' => true,
    ],
    [
        'label' =>'MINIMUM PASS',
        'attribute' => 'total_minimum_pass', 
        'value' => 'total_minimum_pass', 
        'pageSummary' => true,
    ],
    
    

       
    [
        'class' => 'app\components\CustomActionColumn',
        'header'=> 'Actions',
        'template' => '{view}{update}{delete}',
        'buttons' => [
            'view' => function ($url, $model) {
            return ((Yii::$app->user->can("/mandatory-subjects/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
            },
            'update' => function ($url, $model) {
            return ((Yii::$app->user->can("/mandatory-subjects/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
            },
            'delete' => function ($url, $model) {
            return ((Yii::$app->user->can("/mandatory-subjects/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete',
                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                    ,'method' => 'post'],]) : '');
            }
            ],
    'visible' => $visible,
    ],
    
    //['class' => 'yii\grid\ActionColumn'],
];

echo Dialog::widget();
$this->title = "Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT);
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div class="row">
<div class="col-xs-12">
    <div class="col-lg-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 20px !important;" >
    <div class="col-xs-2 pull-right left-padding">    
        <?php 
        $fullExportMenu = ExportMenu::widget([
          'dataProvider' => $dataProvider,
          'columns' => $gridColumns,
          'target' => ExportMenu::TARGET_BLANK,                  
          'filename' => "Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Info",
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
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Data </h3>',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), ['create'], ['class' => 'btn btn-success'])." &nbsp; ".Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset', ['index'], ['class' => 'btn btn-info'])." &nbsp; ".Html::a('<i class="glyphicon glyphicon-plus"></i> Download Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Info", ['mandatory-subjects-full'], ['class' => 'btn btn-warning'])." &nbsp; ",
        ],
      // set a label for default menu
      'export' => [
          'label' => 'Page',
          'filename' => "Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Reports",
          'fontAwesome' => true,
          'class' => 'hide_page_label',         
      ],
    // the toolbar setting is default    
    'toolbar' => [        
        '{export}',
        ['content'=>
            Html::a('<i class="glyphicon glyphicon-plus"></i> Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), ['create'], [
                    'data-pjax'=>0, 
                    'class'=>'btn btn-success',
                    'title'=>'Add Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
        ],

    ],
    // configure your GRID inbuilt export dropdown to include additional items
    'export' => [
        'fontAwesome' => true,
            'filename' => "Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Reports",
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
