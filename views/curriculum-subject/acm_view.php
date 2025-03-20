<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Import;
use kartik\dialog\Dialog;
echo Dialog::widget();

$this->title = "ACM View";
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>
<br /><br />
<div class="import-index">
	<div class="box box-primary">
  		<div class="box-body">



<div class="row">

	 <div class="col-xs-12 col-sm-12 col-lg-12">

        <?php 

        $table='';

        if(!empty($acmdata))
        {
            $table.= '<table id="checkAllFeet" class="table table-responsive table-striped" align="center" >
            <thead>
            <tr>
            <td>Regulation</td>
            <td>Degree</td>
            <td>Version</td>
            <td>File</td>
            </tr>
            <thead>
            <tbody align="center">';

            foreach ($acmdata as $key => $value) 
            {
                # code...
            }

             $table.='</tbody></table>';
        }


        ?>
		
	 </div>
	
</div>


</div>

</div>
</div>
