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
/* @var $model app\models\Servicesubjecttodept */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servicesubjecttodept-form">
    <div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="col-xs-6 col-sm-6 col-lg-6">
            <?php if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
            {?>
                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?= Yii::$app->user->getDeptId();?>">

                <div class="col-md-6">
           
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id',                                     
                                    //'onchange'=>'getdeptsemester()',
                                    'value'=>$model->coe_regulation_id
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>

            <?php } else { ?>
                <div class="col-md-6">
           
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id',
                                    'value'=>$model->coe_regulation_id
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>

                <div class="col-md-6">
           
                    <?= $form->field($model, 'coe_dept_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getDepartmentdetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_dept_id',
                                    'name' => 'coe_dept_id',
                                    //'onchange'=>'getdeptsemester()',
                                    'value'=>$model->coe_dept_id
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>

            <?php } 
            ?>  

                <div id="course_objectives" style="display: block;">

                    <?php $cobj=0;
                        for ($i=1; $i <= 6; $i++) 
                        { 
                            $co='course_objectives'.$i;
                            if($model->$co!='')
                            {
                                 $cobj++;
                                 ?>
                            <div class="col-md-11">
                                <?= $form->field($model, $co)->textInput(['Autocomplete'=>"off",'name'=>'course_objectives[]']) ?>
                                                                  
                               
                            </div>
                            <?php }?>
                        <?php }

                        if($cobj<=6){?>

                    <div class="col-md-1"><br>
                        <input type="hidden" id="addcobj" value="<?= $cobj;?>">
                        <?= Html::Button('+', ['id'=>'cobj','class' => 'pull-right btn btn-primary','onClick'=>'additional_cobj()']) ?>
                    </div>

                      <div class="col-md-12" id="additional_cobj"></div>

                      <?php }

                      $cout=0;
                        for ($i=1; $i <= 6; $i++) 
                        { 
                            $co='course_outcomes'.$i;
                            $rpt='rpt'.$i;
                            if($model->$co!='')
                            {
                                 $cout++;
                                 ?>
                    <div class="col-md-8">
                        <?= $form->field($model, $co)->textarea(['Autocomplete'=>"off",'name'=>'course_outcomes[]']) ?>
                                       
                    </div>
                    
                    <div class="col-md-3">
                         <?= $form->field($model, $rpt)->widget(
                            Select2::classname(), [  
                                'data' => $model->getRptDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '----Select----',
                                    //'id' => 'rpt1',
                                    'name'=>'rpt[]'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>             
                    </div>

                     <?php }?>
                        <?php }

                        if($cout<6){?>
                    <div class="col-md-1">
                        <br>
                        <input type="hidden" id="addcout" value="<?= $cout;?>">
                        <?= Html::Button('+', ['id'=>'cout','class' => 'pull-right btn btn-primary','onClick'=>'additional_cout()']) ?>
                    </div>
               
                    <div id="additional_cout"></div>
                    <?php }?>
                </div>
            </div>
            <!-- divide -->
            <div class="col-xs-6 col-sm-6 col-lg-6">
                 
               <div class="col-md-12">

                <div class="col-md-6">
               <?php //echo $model->subject_code; exit;?>
                    <?= $form->field($model, 'subject_code')->widget(
                                Select2::classname(), [  
                                'data' => $model->getSubjectDetails(), 
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'subject_id',
                                    'value'=>$model->subject_code
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>
                 <div class="col-md-6">
                  <?php //echo $model->subject_code; exit;?>
                    <?= $form->field($model, 'prerequisties')->widget(
                            Select2::classname(), [  
                                'data' => $model->getPresubjectlist(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                  'multiple'=>'multiple',
                                    'placeholder' => '-----Select----',
                                    'id' => 'prerequisties',
                                    'name' => 'prerequisties[]',
                                    'value'=>explode(",", $model->prerequisties)
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>
              </div>
                <div class="col-md-12">
                <label class='control-label' style="padding-left: 14px;">Course Content</label>
                 <div id="course_objectives" style="display: block;">
                    <?php 
                      $cout=0; $mttt=0;
                        for ($i=1; $i <= 5; $i++) 
                        { 
                            $mt='module_title'.$i;
                            $mh='module_hr'.$i;
                            $ccm='cource_content_mod'.$i;
                             if($model->$mt!='')
                            {
                                 $cout++;
                                 $mttt++;
                                 ?>
                    <div class="col-md-9">
                        <?= $form->field($model, $mt)->textInput(['Autocomplete'=>"off",'name'=>'module_title[]']) ?>
                                       
                    </div>

                    <div class="col-md-2">
                          <?= $form->field($model, $mh)->textInput(['maxlength' => true,'Autocomplete'=>"off",'name'=>'module_hr[]']) ?>             
                    </div>
                     <div class="col-md-11">
                         <?= $form->field($model, $ccm)->textarea(['Autocomplete'=>"off",'name'=>'cource_content_mod[]','rows'=>6]) ?>           
                    </div>
                    <?php }?>
                    <?php }?>
                    <?php 
                        if($mttt==0){
                          $mt='module_title1';
                            $mh='module_hr1';
                            $ccm='cource_content_mod1';?>
                          <div class="col-md-9">
                            <?= $form->field($model, $mt)->textInput(['Autocomplete'=>"off",'name'=>'module_title[]']) ?>
                                       
                          </div>

                          <div class="col-md-2">
                                <?= $form->field($model, $mh)->textInput(['maxlength' => true,'Autocomplete'=>"off",'name'=>'module_hr[]']) ?>             
                          </div>
                           <div class="col-md-11">
                               <?= $form->field($model, $ccm)->textarea(['Autocomplete'=>"off",'name'=>'cource_content_mod[]','rows'=>6]) ?>           
                          </div>
                    <?php }?>
                     <?php 
                        if($cout<5){?>
                    <div class="col-md-1">
                        <br>
                        <input type="hidden" id="addcont" value="<?= $cout;?>">
                        <?= Html::Button('+', ['id'=>'cont','class' => 'pull-right btn btn-primary','onClick'=>'additional_cont()']) ?>
                    </div>
                     <?php }?>
                   
               
                    <div id="additional_cont"></div>               

                </div>
              </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="col-xs-6 col-sm-6 col-lg-6">
                     <?php 
                        $cout=0;
                        for ($i=1; $i <= 4; $i++) 
                        { 
                            $tb='text_book'.$i;
                             if($model->$tb!='')
                            { $cout++;?>
                              <div class="col-md-11">
                                  <?= $form->field($model, $tb)->textInput(['Autocomplete'=>"off",'name'=>'text_book[]']) ?>
                              </div>
                      <?php     
                           }
                        }
                        if($cout<4){?>
                    <div class="col-md-1"><br>
                        <input type="hidden" id="addtxtbook" value="<?= $cout;?>">
                        <?= Html::Button('+', ['id'=>'txtbook','class' => 'pull-right btn btn-primary','onClick'=>'additional_txtbook()']) ?>
                    </div>
                    <?php }?>
                      <div class="col-md-12" id="additional_txtbook"></div>
                    
                     <?php 
                        $cout=0;
                        for ($i=1; $i <= 3; $i++) 
                        { 
                            $wr='web_reference'.$i;
                             if($model->$wr!='')
                            { $cout++;?>
                    <div class="col-md-11">
                        <?= $form->field($model, $wr)->textInput(['Autocomplete'=>"off",'name'=>'web_reference[]']) ?>
                    </div>
                     <?php }?>
                      <?php }?>
                      <?php 
                        if($cout<3){?>
                    <div class="col-md-1"><br>
                        <input type="hidden" id="addwebbook" value="<?= $cout;?>">
                        <?= Html::Button('+', ['id'=>'webbook','class' => 'pull-right btn btn-primary','onClick'=>'additional_webbook()']) ?>
                    </div>
                     <?php }?>
                    <div class="col-md-12" id="additional_webbook"></div>
            </div>

            <div class="col-xs-6 col-sm-6 col-lg-6">

                     <?php 
                        $cout=0;
                        for ($i=1; $i <= 6; $i++) 
                        { 
                            $rb='reference_book'.$i;
                             if($model->$rb!='')
                            { $cout++;?>
                    <div class="col-md-11">
                        <?= $form->field($model, $rb)->textInput(['Autocomplete'=>"off",'name'=>'reference_book[]']) ?>
                    </div>
                    <?php }?>
                      <?php }?>
                      <?php 
                        if($cout<6){?>
                    <div class="col-md-1"><br>
                        <input type="hidden" id="addreferbook" value="<?= $cout;?>">
                        <?= Html::Button('+', ['id'=>'referbook','class' => 'pull-right btn btn-primary','onClick'=>'additional_referbook()']) ?>
                    </div>
                    <?php }?>
                      <div class="col-md-12" id="additional_referbook"></div>

                       <?php 
                        $cout=0;
                        for ($i=1; $i <= 2; $i++) 
                        { 
                            $or='online_reference'.$i;
                             if($model->$or!='')
                            { $cout++;?>
                    <div class="col-md-11">
                        <?= $form->field($model, $or)->textInput(['Autocomplete'=>"off",'name'=>'online_reference[]']) ?>
                    </div>

                    <?php }?>
                      <?php }?>
                      <?php 
                        if($cout<3){

                           if($cout==0)
                            {?>
                              <input type="hidden" name="online_reference">
                            <?php }?>
                    <div class="col-md-1"><br>
                        <input type="hidden" id="addonlinebook" value="<?= $cout;?>">
                        <?= Html::Button('+', ['id'=>'onlinebook','class' => 'pull-right btn btn-primary','onClick'=>'additional_onlinebook()']) ?>
                    </div>
                    <?php }?>
                      <div class="col-md-12" id="additional_onlinebook"></div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
                
            <div class="form-group pull-right"><br>
                <?= Html::submitButton('Next', ['id'=>'nextsyllabus','class' => 'btn btn-primary']) ?>
                 <?= Html::a("Cancel", Url::toRoute(['syllabus/index']), ['onClick'=>"spinner();",'class' => ' btn btn-warning']) ?>
            </div>
        </div>
    </div>    
    <?php ActiveForm::end(); ?>

</div>
</div>

</div>
