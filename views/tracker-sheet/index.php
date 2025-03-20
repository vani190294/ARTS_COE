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
/* @var $searchModel app\models\CoeTrackingsheetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible = Yii::$app->user->can("/tracker-sheet/view") || Yii::$app->user->can("/tracker-sheet/update") ? true : false; 
$this->title = 'TRACKER SHEETS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-tracker-sheet-index">

<?php

    Yii::$app->ShowFlashMessages->showFlashes();
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $show_text = isset($org_name)?$org_name:"Sri Krishna Institutions";
    
?>  
 <div style="width: 100%" class="login-logo">
    <a href="<?php echo $org_web; ?>" target="_blank"><b><u><?php echo $show_text ?>!!!</b></u></a>
 </div>
 <h2><b><?= Html::encode($this->title) ?></b></h2>
    
   
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tracker Sheet', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            
            'task_tittle',
            'task_description:ntext',
            'date',
            'status',
            'developed_by',
            
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [

                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/tracker-sheet/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },

                    'update' => function ($url, $model) {
                        if($model->status=='Pending' || Yii::$app->user->getId()=='1')
                        {
                            return ((Yii::$app->user->can("/tracker-sheet/update")) ? Html::a('<span class="fa fa-edit increase_size"></span>', ['/tracker-sheet/update','id'=>$model->coe_ts_id], ['title' => 'Update',]) : '');
                        }
                        },
                  
                    'delete' => function ($url, $model) 
                    {
                        if($model->status=='Pending')
                        {
                             
                            return ((Yii::$app->user->can("/tracker-sheet/delete-task")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/tracker-sheet/delete-task','id'=>$model->coe_ts_id], ['title' => 'Delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
