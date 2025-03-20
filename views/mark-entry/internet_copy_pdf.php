<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\MarkEntryMaster;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?>

<?php
	if(isset($internet_copy) && !empty($internet_copy))
	{		
?>
<div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
  				echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/internet-copy-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);

          echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-internet-copy-pdf','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));

         
            ?>
        </div>
</div>

<?php
	require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
  if($file_content_available=="Yes")
  {

  }
  else
  {
    Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');
  }

  if($org_email=='coe@skcet.in')
  {
    include('skcet_internet_copy.php');
  }
  else
  {
    include('other_internet_copy.php');
  }
}


  
?>