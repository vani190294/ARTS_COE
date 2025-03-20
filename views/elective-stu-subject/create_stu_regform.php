<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveStuSubject */

$this->title = 'Elective Course Student Registration';

$this->params['breadcrumbs'][] = 'Curriculum';
$this->params['breadcrumbs'][] = 'Elective Register';
$this->params['breadcrumbs'][] = ['label' => 'Elective Course Student Registration', 'url' => ['index']];
?>
<div class="elective-stu-subject-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>

<div class="box box-success">
<div class="box-body">
    <?php 
    $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject WHERE subject_code='".$checkelective['subject_code']."'")->queryScalar();

    if($subject_name=='')
    {
        $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject WHERE subject_code='".$checkelective['subject_code']."'")->queryScalar();
        
        if($subject_name=='')
        {
            $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code_new='".$checkelective['subject_code']."'")->queryScalar();

            if($subject_name=='')
            {
                $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code_new='".$checkelective['subject_code']."'")->queryScalar();
            }
        }
    }
    ?>
     <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="font-size: 16px; text-align: center;">
        Regulation:<b> <?= $regulationyear;?></b>
        Degree Type:<b> <?= $checksemdata['degree_type'];?> </b>
        Department: <b><?= $checksemdata['dept_code'];?></b>
        <br> <br>
        Subject Code: <b><?= $checkelective['subject_code'];?></b>
        Subject Name: <b><?= $subject_name;?> 
        </b>
        <br>
         <div id="createstureg111"></div>
        <br>
        <p style="color: red;">
            <input type="hidden" id="coe_elective_option" value="<?= $checkelective['coe_elective_option'];?>">
            <?php if($checksemdata['degree_type']!='PG' && $checkelective['coe_elective_option']!=191){?>
        	Note: Single Section per elective course minimum 15 student, after only save option enable<br>
        	More then One Section per elective course minimum 30 student, after only save option enable
        <?php }?>
        </p>
        <input type="hidden" id="degree_type" value="<?= $checksemdata['degree_type'];?>">
    </div>


   
    <div class="col-xs-12 col-sm-12 col-lg-12">
    	<?php $sectionid=1;
    	foreach ($getsection as $value) 
    	{
    		$regnums=$reg_num[$value['section_name']]; 
    		$checname='section'.$sectionid;
    		$checkstu='sectionstud'.$sectionid;
    		?>
    		<div class="col-xs-4 col-sm-4 col-lg-4">
    			<div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;">
    				<b>Section <?= $value['section_name'];?></b><br>
    			</div>
    			<div class="col-xs-12 col-sm-12 col-lg-12">
    				<div class="col-xs-12 col-sm-12 col-lg-12">
    				<label><input type="checkbox" id="<?= $checname;?>" onclick="checkallelectstu(<?= count($getsection);?>,<?= $sectionid;?>);"> All</label>
    				</div>
    				<?php 
				    	for ($i=0; $i <count($regnums) ; $i++) 
				    	{ ?>
				    	 	<div class="col-xs-12 col-sm-12 col-lg-12">
				    	 		<label>
				    	 		<input type="checkbox" name="register_number[]" class="<?= $checkstu;?>" onclick="checkelectstucount(<?= count($getsection);?>,<?= $sectionid;?>)" value="<?= $regnums[$i]['register_number'];?>">
				    	 		<?= $regnums[$i]['register_number'];?> - <?= $regnums[$i]['name'];?>
				    	 		</label>
				    	 	</div>
				    	<?php
				    	} 
				    	?>
    			</div>
    		</div>

    		<?php $sectionid++;
    	}
    		
    	?>
    </div>
     <div class="col-xs-12 col-sm-12 col-lg-12" style="display: none;" id="saveelectstudent">
        <div class="col-xs-9 col-sm-9 col-lg-9"></div>
            <div class="col-xs-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success pull-right','name'=>'saveelect']); ?>
                </div>
            </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>