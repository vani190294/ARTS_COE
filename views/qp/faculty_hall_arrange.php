<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Categorytype;
use yii\db\Query;
use app\models\QpSetting;
use app\models\Batch;

echo Dialog::widget();
$this->title = 'Hall Invigilation';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<?php  if(empty($faculty_hall_data))
            {?>
<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  

   <div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12">                
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id' => 'fh_year','name' => 'fh_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'fh_month', 
                            'name' => 'fh_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'fh_date', 
                            'name' => 'fh_date',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Session ----', 
                            'id' => 'fh_session', 
                            'name' => 'fh_session',
                                                  
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]); ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2" id="fhboarddata">
            <label class="control-label">Hall Count</label><br>
            <input type="text" class="form-control" name="hall_count" id="hall_count" name="hall_count" readonly="readonly">
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2" id="fhboarddata">
           <?php echo $form->field($modelfa,'board')->widget(
                Select2::classname(), [
                    'data' => ['ARTS'=>'ARTS'],
                    'options' => [
                        'placeholder' => '----- Select Board ----',   
                        'id'=>'fhboard',                    
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Board'); 
            ?>  
        </div>


         

    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">

        <div class="col-xs-12 col-sm-2 col-lg-2" id="showdelete_fh" style="display: none;">
            <br>
            <div class="col-xs-12 col-sm-6 col-lg-6">
                <?= Html::submitButton('Show' ,['class' => 'btn btn-group btn-group-lg btn-success', 'id'=>'show_fh' , 'onclick'=>'js:spinner();']) ?> 
            </div>

            <div class="col-xs-12 col-sm-6 col-lg-6">
                <?php if(Yii::$app->user->getId()==1 || Yii::$app->user->getId()==13 || Yii::$app->user->getId()==18)
                { echo Html::Button('Delete Hall Arrange' ,['class' => 'btn btn-group btn-group-lg btn-warning', 'id'=>'delete_fh' , 'onclick'=>'js:spinner();']); } ?> 
            </div>
        </div>

    </div>

        <div class="col-xs-12 col-sm-12 col-lg-12" id="show_facultydetails">  
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-lg-12">
                    <div class="col-xs-12 col-sm-5 col-lg-5">
                        <form name="selection" method="post" onSubmit="return selectAll()">
                            <label class="control-label">Hall Faculty</label><br>
                        <select name="multi_halls" multiple size="10" id="from" class="form-control multi_selecthalls">
                            <option>-----Select ----</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-xs-12 text-center"> <br />
                        <a href="javascript:moveSelected('from', 'to');" id="fget" class="btn btn-success">&gt;</a>
                            <br><br>
                        <a href="javascript:moveSelected('to', 'from');" id="tget" class="btn btn-primary">&lt;</a>
                            <br><br>
                        <!--a href="javascript:moveAll('from', 'to');" id="ffget" class="btn btn-success">&gt;&gt;</a>
                            <br><br>            
                        <a href="javascript:moveAll('to', 'from');" id="ttget" class="btn btn-primary">&lt;&lt;</a!-->
                    </div>
                    <div class="col-xs-12 col-sm-5 col-lg-5">
                        <label class="control-label">Alloted Faculty</label><br>
                        <select multiple id="to" size="10" name="topics[]" class="form-control"></select>
                    </div>
                </div>   
            </div>
            <div class="row">
                    <br />
                    <div class="col-xs-12 col-sm-12 col-lg-12">
                        <div class="col-xs-12 col-sm-5 col-lg-5">
                            Available Faculty(s)
                            <input type="text" name="countFrom" id="countFrom" class="form-control" readonly>
                        </div>

                        <div class="col-xs-12 col-sm-2 col-lg-2" style="text-align: center;">
                            <br />
                            
                            
                        </div>

                        <div class="col-xs-12 col-sm-5 col-lg-5">
                            Alloted Faculty Count
                            <input type="text" name="countTo" id="countTo" class="form-control" readonly>
                        </div>
                    </div>
            </div>
            

            
            

            <div class="row">
                    <br />
                    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;">
                        <br />
                        <div class="btn-group" role="group" aria-label="Actions to be Perform">
                                <?= Html::submitButton('Save' ,['class' => 'btn btn-group btn-group-lg btn-success', 'id'=>'save_fh' , 'onclick'=>'js:getValues1();spinner();']) ?>  
                                
                                <?= Html::a("Cancel", Url::toRoute(['qp/faculty-hall-arrange']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                            
                        </div>

                       
                    </div>
                </div>
        </div>
    </div>
         <?= Html::textInput('hallName',"",['id'=>'hallName','type'=>"hidden"]); ?>
    <?= Html::textInput('hallCount',"",['id'=>'hall_cnt','type'=>"hidden"]); ?>

    <?= Html::textInput('hallName_rhs',"",['id'=>'hallName_rhs','type'=>"hidden"]); ?>
    <?= Html::textInput('hallCount_rhs',"",['id'=>'hall_cnt_rhs','type'=>"hidden"]); ?>
 
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>

 <?php }

            if(!empty($faculty_hall_data))
            {
                 
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['qp/qpfacultyhall-pdf'], [
                            'class' => 'pull-right btn btn-block btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                    ]);
                    $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['qp/qpfacultyhall-excel'], [
                                'class' => 'pull-right btn btn-block btn-warning',
                                'target' => '_blank',
                                'data-toggle' => 'tooltip',
                                'title' => 'Will open the generated PDF file in a new window'
                    ]);

                    $back = Html::a('Back to Arrange', ['qp/faculty-hall-arrange'], [
                                'class' => 'pull-right btn btn-block btn-warning',
                                'data-toggle' => 'tooltip',
                                'title' => 'Will open the generated PDF file in a new window'
                    ]);

                     echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $back. ' </div><div class="col-lg-8" ></div> <div class="col-lg-2" > ' . $print_pdf. " ".$print_excel . ' </div></div></div></div></div>';
                $html = "";
                $header = "";
                $body ="";
                $footer = "";                
                
                    $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>";
                    $header .= '<tr>
                                <td align="center">
                                    <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                </td>
                                <td colspan=2 align="center">
                                    <h3> 
                                      <center><b><font size="5px">' . $org_name . '</font></b></center>
                                        <center> <font size="3px">' . $org_address . '</font></center>
                                        <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                    </h3>
                                     <h4> Hall Invigilation - '.$_SESSION['get_examyear'].' End Semester Regular/Arrear Examinations </h4>
                                     <h4> '.$_SESSION['get_examsession'].' </h4>
                                </td>
                            <td align="center">  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                            </td>
                        </tr></table> ';

                    $header .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>                            
                            <th>Hall Superintendent</th>
                            <th>Department</th>

                            <th>Hall No.</th>
                            
                        </tr>

                        
                        <tbody>"; 

                    $sl=1;
                    foreach ($faculty_hall_data as  $value) 
                    { 
                   
                  // print_r($faculty_hall_data);exit;
                    
                        $body .='<tr>';
                        $body .='<td width="5%">'.$sl.'</td>';
                        $body .='<td>'.$value['faculty_name'].'</td>';
                        $body .='<td></td>';
                        $body .='<td width="10%">'.$value['hall_name'].'</td>';                        
                        
                        $body .='</tr>';
                                            
                         $sl++;

                    }
                    $body .='</tbody></table>';

                    if(!empty($rhsdata))
                    {

                    $body .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>S.No.</th>
                            <th>Reserve Hall Superintendent</th>
                            <th colspan=2>Signature</th>
                        </tr>
                        <tbody>"; 

                    $sl=1;
                    foreach ($rhsdata as  $value) 
                    { 
                   
                    
                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        $body .='<td>'.$value['faculty_name'].'</td>';
                        $body .='<td width="25%"></td>';
                        $body .='<td width="25%"></td>';
                        $body .='</tr>';
                                            
                         $sl++;

                    }
                    $body .='</tbody></table>';


                    }

                   

                    $body .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>Chief Superintendent</th>
                            <th>Signature</th>
                        </tr>
                        <tbody>";                    
                    
                        $body .='<tr>';
                        $body .='<td  align="center">'.$value['chieff'].'</td>';
                        $body .='<td width="40%"></td>';
                        $body .='</tr>';
                                            
                         $sl++;                   
                    $body .='</tbody></table>';

                   

                    $footer .='<table width="100%" style="overflow-x:auto;"  align="center" class="table table-striped ">
            
                    <tr height="100px"  >

                    <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="4"><br>                        
                       <br>
                        <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations").'</b> </div>
                      </td>
                    </tr></tbody></table>';

                    echo  $header.$body;
                     
                    if (isset($_SESSION['faculty_hall_data'])) 
                    {
                        unset($_SESSION['faculty_hall_data']);
                                            
                    }

                    $send_results = $header.$body.$footer;

                    $_SESSION['faculty_hall_data'] = $send_results;
                }
            
            ?>