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

$this->title = "BOS View";
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>
<br /><br />
<div class="import-index">
	<div class="box box-primary">
  		<div class="box-body">

<?php Yii::$app->ShowFlashMessages->showFlashes();?> 

    <?php 
    $form = ActiveForm::begin([
				'options' => ['enctype' => 'multipart/form-data'],
			]); 
   $userid=Yii::$app->user->getId();
?>

<div class="row">

	 <div class="col-xs-12 col-sm-12 col-lg-12">
		<?php if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
            {?>

                <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','name'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'getdeptid();checksem2();']) ?>
                </div>

                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?php  echo Yii::$app->user->getDeptId(); ?>">
               
                <div class="col-md-2">
                
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

            <?php } else { ?>


            <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id' => 'degree_type','name'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'checksem2();']) ?>
            </div>
            
            <div class="col-md-2">
                
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
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>  


            <?php } ?>

            <div class="col-md-2">

                    <?= $form->field($model, 'semester')->widget(
                            Select2::classname(), [                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [                                    
                                    'placeholder' => '-----Select----',
                                    'id' => 'semester'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                   
            </div>

            <div class="col-sm-12 col-xs-2 col-lg-2">
			<div class="form-group"><br>
				<button onClick="spinner();" type="submit" class="btn btn-primary">Submit</button>
				<?= Html::a("Cancel", Url::toRoute(['curriculum-subject/bos-view']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
			</div>
		</div>
	 </div>
	
</div>

<?php if(!empty($checkbosdata))
{ 
	$regulation_year = Yii::$app->db->createCommand("SELECT regulation_year FROM coe_regulation WHERE coe_regulation_id=".$_POST['coe_regulation_id'])->queryScalar();

    if($_POST['degree_type']=='UG')
    {
        $roman = ['1-2'=>'I-II','3-4'=>'III-IV','5-8'=>'V-VIII','3-8'=>'III-VIII'];
    }
    if($_POST['degree_type']=='PG' || $_POST['degree_type']=='MBA')
    {
        $roman = ['1-4'=>'I-IV'];
    }

	$url= Url::home(true)."resources/curriculum_bos/";?>
<div id='change_student_text1' style="margin-top: 25px;" class="row">
    <div class="col-sm-12 col-xs-12 col-lg-12"> 
        <div class="col-xs-4 col-sm-4 col-lg-4">
            <h3><b>Regulation: <?= $regulation_year;?></b></h3>      
        </div> 
        <div class="col-xs-4 col-sm-4 col-lg-4">
            <h3><b>Semester: <?= $roman[$checkbosdata['semester']];?></b></h3>      
        </div> 
        <div class="col-xs-4 col-sm-4 col-lg-4">
            <h3><b>Date: <?= date("d-m-Y",strtotime($checkbosdata['bos_date']));?></b></h3>      
        </div>          	
        <div class="col-xs-12 col-sm-6 col-lg-6">
        <h3><b> BOS Mins</b></h3> 
        <?php if(!empty($checkbosdata['bos_min_file']))
			{ 
				?>
				<iframe src="<?php echo $url.$checkbosdata['bos_min_file'];?>" frameborder="1" width="800" height="745" style="overflow: auto"></iframe>	
			<?php }else{ ?>
				No Data
			<?php } ?>

        </div> 

        <div class="col-xs-12 col-sm-6 col-lg-6">                        
			<h3><b> BOS Attendance</b></h3>
			<?php if(!empty($checkbosdata['bos_attendance_file']))
			{ 
				?>
				<iframe src="<?php echo $url.$checkbosdata['bos_attendance_file'];?>" frameborder="1" width="800" height="745" style="overflow: auto"></iframe>	
			<?php }else{ ?>
				No Data
			<?php } ?>	
        </div> 
        
    </div>
</div>  

<?php } ?>

<?php ActiveForm::end(); ?>  
</div>

</div>
</div>
