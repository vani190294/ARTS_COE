<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\CurSyllabus */

$this->title = 'Delete Syllabus Matrix';
$this->params['breadcrumbs'][] = ['label' => 'Syllabi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curriculum-subject-create">
	<h1><?= Html::encode($this->title) ?></h1>
 <div class="curriculum-subject-form">

	    <div class="box box-success">
			<div class="box-body"> 

				<div class="col-xs-12 col-sm-12 col-lg-12">
			    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
			    <div>&nbsp;</div>
	    		<?php $form = ActiveForm::begin(); ?>

	    		
		    		<div class="col-xs-12 col-sm-12 col-lg-12">

		    			
		        		 <div class="col-md-3">
	           
	                    	<?= $form->field($model, 'coe_regulation_id')->widget(
	                            Select2::classname(), [  
	                                'data' => $model->getRegulationDetails(),                      
	                                'theme' => Select2::THEME_BOOTSTRAP,
	                                'options' => [
	                                    'placeholder' => '-----Select----',
	                                    'id' => 'coe_regulation_id',
	                                    'name' => 'coe_regulation_id',                                     
	                                    
	                                ],
	                               'pluginOptions' => [
	                                   'allowClear' => true,
	                                ],
	                            ]) ?>
	                	</div>


	                	 <div class="col-md-3">
	           
	                    	<?= $form->field($model, 'coe_dept_id')->widget(
	                            Select2::classname(), [  
	                                'data' => $model->getDepartmentdetails(),                      
	                                'theme' => Select2::THEME_BOOTSTRAP,
	                                'options' => [
	                                    'placeholder' => '-----Select----',
	                                    'id' => 'coe_dept_id',
	                                    'name' => 'coe_dept_id',
	                                    'onchange'=>'getcoresubject1()'
	                                ],
	                               'pluginOptions' => [
	                                   'allowClear' => true,
	                                ],
	                            ])->label("Matrix to Dept.") ?>
	                	</div>


	                	 <div class="col-md-3">
	               
	                    	<?= $form->field($model, 'subject_code')->widget(
	                                Select2::classname(), [  
	                                'theme' => Select2::THEME_BOOTSTRAP,
	                                'options' => [
	                                    'placeholder' => '-----Select----',
	                                    'id' => 'subject_id',
	                                    'name' => 'subject_code',
	                                ],
	                               'pluginOptions' => [
	                                   'allowClear' => true,
	                                ],
	                            ]) ?>
	                	</div>

	                	<div class="col-xs-3 col-sm-3 col-lg-3">
	                
				            <div class="form-group pull-right"><br>
				                <?= Html::submitButton('Delete', ['class' => 'btn btn-primary']) ?>
				                 <?= Html::a("Cancel", Url::toRoute(['syllabus/index']), ['onClick'=>"spinner();",'class' => ' btn btn-warning']) ?>
				            </div>
				        </div>

		        	</div>

		       

		        <?php ActiveForm::end(); ?>
		    </div>
	        </div>
	     </div>
	</div>

</div>
