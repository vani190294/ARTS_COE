<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

?>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-list-ul"></i> <?php echo 'Valuation Scrutiny'; ?></h3>

        <div class="pull-right box-tools">
            <button type="button" class="btn btn-success btn-sm" data-widget="remove" data-toggle="tooltip"
                    title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
    </div>
    
    <div class="box-body">
        <div class="row">
        <section class="content">
            <section class="col-lg-12 connectedSortable">

              <?php 

                $totalError = (count($importResults['dispResults'])-$importResults['totalSuccess']); ?>
                <?php $headerTr = $content = ''; $i = 1; ?>
                
                <?php if(!empty($importResults['totalSuccess'])) : ?>
                    <div class="alert alert-success">
                        <h4><i class="fa fa-check"></i> <?php 'Success!'; ?></h4>
                        <?= "{$importResults['totalSuccess']} Valuation Scrutiny importing success." ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($totalError)) : ?>
                    <div class="alert alert-danger">
                        <h4><i class="fa fa-ban"></i> <?php echo 'Error!'; ?></h4>
                        <?= "{$totalError}  Valuation Scrutiny importing error." ?>
                    </div>
                <?php endif; ?>
              
                <?php
                    $headerTr.= Html::tag('th', 'Sr No');
                    $headerTr.= Html::tag('th', "Name");
                    $headerTr.= Html::tag('th', 'Designation');
                    $headerTr.= Html::tag('th', 'Board');
                    $headerTr.= Html::tag('th', 'Password');
                    $headerTr.= Html::tag('th', 'Email');
                    $headerTr.= Html::tag('th', 'Status');
                    $headerTr.= Html::tag('th', 'Message');
                ?>
                <table style="overflow-x:auto;"  class="table table-bordered table-responsive bulk_edit_table table-hover" id="hall_import_results" >
                    <thead>
                        <?php echo Html::tag('tr', $headerTr, ['class' => 'active']) ?>
                    </thead>
                    <tbody>
                    
                    <?php 

                    foreach($importResults['dispResults'] as $line) {                       
                        $content = '';
                        $content.= Html::tag('td', $i++);
                        $content.= Html::tag('td', isset($line['A'])?$line['A']:"");
                        $content.= Html::tag('td', isset($line['B'])?$line['B']:""); 
                        $content.= Html::tag('td', isset($line['C'])?$line['C']:""); 
                        $content.= Html::tag('td', isset($line['D'])?$line['D']:""); 
                        $content.= Html::tag('td', isset($line['E'])?$line['E']:"");
                        $content.= Html::tag('td', ($line['type'] == 'E') ? 'ERROR' : 'SUCCESS'); //Status
                        $content.= Html::tag('td', $line['message']);  //Message
                                            
                        echo Html::tag('tr', $content, ['class' => ($line['type'] == 'E') ? 'danger' : 'success']); 
                        ?>  
                    <?php } ?> 
                    </tbody>
                </table>


        </section>
    </section>

        </div>
    </div><!--./box-body-->
</div><!--./box-->


<?php 

$this->registerJsFile(    
    '@web/style/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(
    '@web/style/bower_components/datatables.net/js/jquery.dataTables.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJs(<<<JS
    $(function () {
    $('#hall_import_results').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
       'scrollY': '200',
       "scrollX": true,
       "responsive": "true",
       "pageLength": "20",
       
       
    })
  })
JS
);

?>
