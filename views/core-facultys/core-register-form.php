<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\db\Query;
echo Dialog::widget();


/* @var $this yii\web\View */
/* @var $model app\models\CoreFacultys */

$this->title = 'Create Core Facultys Form';
$this->params['breadcrumbs'][] = ['label' => 'Core Facultys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="core-facultys-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-success">
        <div class="box-body"> 
        	<div class="col-xs-12 col-sm-12 col-lg-12">
	            <div class="col-xs-3 col-sm-3 col-lg-3">
	                <b>Course Code & Course Name</b>
	            </div>

	            <?php  $sectionname=array('1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E','6'=>'F');
	                for ($i=1; $i <=$no_of_section ; $i++)
	                { 
	                
	                ?>
	                    <div class="col-xs-2 col-sm-2 col-lg-2">
	                      <b>  Section <?= $sectionname[$i];?></b>
	                    </div>
	                <?php 
	                }
	                ?>
	                <br><br>
		   </div>	

		   
           	<?php

	           	foreach ($coresubject as $value) 
	           	{?>
	           		<div class="col-xs-12 col-sm-12 col-lg-12">
		           		<div class="col-xs-3 col-sm-3 col-lg-3">
			                <b><?= $value['subject_code'];?> & <?= $value['subject_name'];?></b>
			            </div>
				        <?php for ($i=1; $i <=$no_of_section ; $i++)
		                { 
		                	 $subject_code=$value['subject_code']."[]";
                    		$sid=$value['subject_code'].$i;
                    	?>
		                	<div class="col-xs-2 col-sm-2 col-lg-2">
		                      <?= $form->field($model, 'faculty_ids')->widget(
                                    Select2::classname(), [  
                                        'data' => $int_faculty,                      
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '-----Select----',
                                            'id' => $sid, 
                                            'name' => $subject_code, 
                                        ],
                                       'pluginOptions' => [
                                           'allowClear' => true,
                                        ],
                                    ])->label(false); ?>
		                    </div>

		                <?php 
			        	}

		           		?>
		           		<br>
		            </div>
	        <?php 
	        	}


           	?>


			    <div class="col-xs-12 col-sm-12 col-lg-12">
			            <div class="form-group">
			                <br>
			                <?= Html::submitButton('Save', ['class' => 'btn btn-success pull-right']) ?>
			            </div>
			    </div>


    </div>


    <?php ActiveForm::end(); ?>
</div>
