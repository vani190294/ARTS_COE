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
/* @var $searchModel app\models\StudentMappingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT);
$this->params['breadcrumbs'][] = $this->title;

$visible = Yii::$app->user->can("/student/view") || Yii::$app->user->can("/student/update") ? true : false; ?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<div class="student-index">
<?php 

   
$form = ActiveForm::begin([
'id' => 'delete-student',
  'method' => 'POST',
  'enableAjaxValidation' => true,
   'fieldConfig' => [
                     'template' => "{label}{input}{error}",
                     ],
  ]);   ?>
<input type="hidden" name="finalString" id="finalString">

<?php ActiveForm::end(); ?>
<?php
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
        'attribute' => 'batch_name',
        'value' => 'coeBatchName.batch_name',
        'vAlign'=>'middle',
        'width'=>'190px',
        'format'=>'raw',
        'pageSummary' => true,

    ],
    [
        'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE),
        'attribute' => 'degree_code',
        'value' => 'coeDegreeName.degree_code',
        'pageSummary' => true,
            
    ],    
    [
        'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
        'attribute' => 'programme_code',
        'value' => 'coeProgrammeName.programme_code',
        'pageSummary' => true,
    ],
    [
        'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION),
        'attribute' => 'section_name',
        'value' => 'sectionname.section_name',
        'pageSummary' => true,
    ],
    [
        'label' =>'Name',
        'attribute' =>'name',
        'value' => 'name',
        'pageSummary' => true,
    ],
    [
        'label' =>'Register Number',
        'attribute' => 'register_number',
        'value' => 'register_number',
        'pageSummary' => true,
    ],
    [
        'label' =>"E-Mail",
        'attribute' => 'email_id',
        'value' => 'email_id',
        'pageSummary' => true,
    ],
    [
        'label' =>'Mobile Number',
        'attribute' => 'mobile_no', 
        'value' => 'mobile_no', 
        'pageSummary' => true,
    ],
          
    [
        'class' => 'app\components\CustomActionColumn',
        'header'=> 'Actions',
        'template' => '{view}{update}{delete}',
        'buttons' => [
            'view' => function ($url, $model) {
            return ((Yii::$app->user->can("/student/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
            },
            'update' => function ($url, $model) {
            return ((Yii::$app->user->can("/student/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
            },
            'delete' => function ($url, $model) {
            return ((Yii::$app->user->can("/student/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete',
                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                    ,'method' => 'post'],]) : '');
            }
            ],
    'visible' => $visible,
    ],
    [
        'class' => 'yii\grid\CheckboxColumn', 
         'checkboxOptions' => function ($data) {
        return ['value'=> $data['coe_student_id']];
    }],
    
    //['class' => 'yii\grid\ActionColumn'],
];

echo Dialog::widget();
$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT);
$this->params['breadcrumbs'][] = $this->title;
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
                  'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Info",
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
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Data </h3>',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), ['create'], ['class' => 'btn btn-success'])." &nbsp; ".Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset', ['index'], ['class' => 'btn btn-info'])." &nbsp; ".Html::a('<i class="glyphicon glyphicon-plus"></i> Download '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Info", ['student-bio-data'], ['class' => 'btn btn-warning'])." &nbsp; ",
        ],
      // set a label for default menu
      'export' => [
          'label' => 'Page',
          'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Reports",
          'fontAwesome' => true,
          'class' => 'hide_page_label',
         
      ],
    // the toolbar setting is default    
    'toolbar' => [        
        '{export}',
        ['content'=>
            Html::a('<i class="glyphicon glyphicon-plus"></i> '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), ['create'], [
                    'data-pjax'=>0, 
                    'class'=>'btn btn-success',
                    'title'=>'Add '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), 
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid']).
                Html::a('<i class="fa fa-trash"></i> Delete ', ['index'], ['data-pjax'=>0, 'class' => 'btn btn-danger btn-default','name'=>'delete', 'id'=>'stu_exam_del_butt', 'value'=>'delete','onclick'=>'submitFormStu(this.id)', 'title'=>'Delete '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)])
        ],

    ],
    // configure your GRID inbuilt export dropdown to include additional items
    'export' => [
        'fontAwesome' => true,
            'filename' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Reports",
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
