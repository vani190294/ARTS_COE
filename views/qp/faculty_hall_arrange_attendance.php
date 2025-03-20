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
$this->title = 'Hall Invigilation Attendance';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>

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
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <label class="control-label">Hall Count</label><br>
            <input type="text" class="form-control" name="hall_count" id="hall_count" name="hall_count" readonly="readonly">
        </div>

       

    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">

        <div class="col-xs-12 col-sm-4 col-lg-4">
            <br>
            
                <?= Html::submitButton('Show' ,['class' => 'btn btn-group btn-group-lg btn-success', 'id'=>'show_fh' , 'onclick'=>'js:spinner();']) ?> 

                 <?= Html::a("Resent", Url::toRoute(['qp/faculty-hall-attendance']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            
        
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

 <?php 
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

                   

                     echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > </div><div class="col-lg-8" ></div> <div class="col-lg-2" > ' . $print_pdf. " ".$print_excel . ' </div></div></div></div></div>';
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
                            <th>Hall Name</th>
                            <th>Hall Superintendent</th>
                            <th>Signature</th>
                        </tr>
                        <tbody>"; 

                    $sl=1;
                    foreach ($faculty_hall_data as  $value) 
                    { 
                   
                    
                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        $body .='<td>'.$value['hall_name'].'</td>';
                        $body .='<td>'.$value['faculty_name'].'</td>';
                        $body .='<td width="25%"></td>';
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
                            <th>Signature</th>
                        </tr>
                        <tbody>"; 

                    $sl=1;
                    foreach ($rhsdata as  $value) 
                    { 
                   
                    
                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        $body .='<td>'.$value['faculty_name'].'</td>';
                        $body .='<td width="25%"></td>';
                        $body .='</tr>';
                                            
                         $sl++;

                    }
                    $body .='</tbody></table>';


                    }

                    $body .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>Anna University Representative</th>
                            <th>Signature</th>
                        </tr>
                        <tbody>";                    
                    
                        $body .='<tr>';
                        $body .='<td  align="center">'.$faculty_hall_data[0]['aur'].'</td>';
                        $body .='<td width="25%"></td>';
                        $body .='</tr>';
                                            
                         $sl++;                   
                    $body .='</tbody></table>';

                    $body .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>Squad</th>
                            <th>Signature</th>
                        </tr>
                        <tbody>";                    
                    
                        $body .='<tr>';
                        $body .='<td  align="center"><br><br></td>';
                        $body .='<td width="25%"><br><br></td>';
                        $body .='</tr>';
                        $body .='<tr>';
                        $body .='<td  align="center"><br><br></td>';
                        $body .='<td width="25%"><br><br></td>';
                        $body .='</tr>';
                                            
                         $sl++;                   
                    $body .='</tbody></table>';

                    $footer .='<table width="100%" style="overflow-x:auto;"  align="center" class="table table-striped ">
            
                    <tr height="100px"  >

                    <td style="height: 50px; text-align: left;"  height="100px" colspan="4"><br>                        
                       <br>
                        <div style="text-align: left; font-size: 14px; " ><b>'.strtoupper("Deputy Controller Of Examinations").'</b> </div>
                      </td>
                    <td style="height: 50px; text-align: right;"  height="100px" colspan="4"><br>                        
                       <br>
                        <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations / Chief Superintendent").'</b> </div>
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