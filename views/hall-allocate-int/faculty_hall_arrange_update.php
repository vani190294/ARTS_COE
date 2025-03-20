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
$this->title = 'Hall Invigilation Internal Exam Update';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

     <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 

<?php  if(empty($faculty_hall_data))
            {?>

   <div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12">                
        <div class="col-xs-12 col-sm-2 col-lg-2">
             <?= $form->field($model1, 'year')->textInput(['value'=>date('Y'),'id' => 'intexam_year','name' => 'intexam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model1, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'intexam_month',
                            'name' => 'intexam_month'
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($exam, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'exam_date',     
                            'name' => 'exam_date'                      
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($exam, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Session ----', 
                            'id' => 'intexam_session', 
                            'name' => 'intexam_session'                          
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]); ?>
        </div>
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($exam,'time_slot')->widget(
                Select2::classname(), [
                    //'data' => $exam->Examtimeslot,
                    'options' => [
                        'placeholder' => '-----Select ----',
                        'id' => 'time_slot',
                        'name' => 'time_slot'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>

        <div class="col-xs-12 col-lg-2 col-sm-2">
            <?php echo $form->field($model1, 'internal_number')->widget(
            Select2::classname(), [
            'data' =>ConfigUtilities::internalNumbers(),
            'options' => [
                'placeholder' => '-----Select Internal Exam ----',
            ],
            'options' => [
                'placeholder' => '-----Select----',
                'class'=>'form-control',
                'id' => 'internal_number',
                'name' => 'internal_number'
            ],
            ]); 
            ?>
        </div>
        

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <br>
            <?= Html::submitButton('Show' ,['class' => 'btn btn-group btn-group-lg btn-success', 'onclick'=>'js:spinner();']) ?> 
           
           <?= Html::a("Reset", Url::toRoute(['hall-allocate-int/faculty-hall-arrange-update']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>

	    </div>

 
    

<?php }  if(!empty($faculty_hall_data))
            {?>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="display:none">
            <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [
                        'data' => $model->getMonth(),
                    ]) ?>
        </div>
    </div>

     <input type="hidden" id="exam_date" value="<?php echo $fh_date;?>">

      <input type="hidden" id="intexam_session" value="<?php echo $fh_session;?>">

        <?php  
                 
                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['hall-allocate-int/qpfacultyhallupdate-pdf'], [
                            'class' => 'pull-right btn btn-block btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                    ]);
                   

                    $back = Html::a('Back to Arrange', ['hall-allocate-int/faculty-hall-arrange-update'], [
                                'class' => 'pull-right btn btn-block btn-warning',
                                'data-toggle' => 'tooltip',
                                'title' => 'Will open the generated PDF file in a new window'
                    ]);

                     echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $back. ' </div><div class="col-lg-8" ></div> <div class="col-lg-2" > ' . $print_pdf. ' </div></div></div></div></div>';
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
                                     <h4> Hall Invigilation Internal Exam - '.$_SESSION['get_examyear'].' </h4>
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
                        </tr>
                        <tbody>"; 

                    $sl=1;
                    foreach ($faculty_hall_data as  $value) 
                    {                    
                        $select='';

                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        $body .='<td>'.$value['hall_name'].'</td>';
                        $select = '<select class="fhupdate" id="updatefh'.$value['fh_arrange_id'].'" onchange="updatefhdata1('.$value['fh_arrange_id'].',0);">
                                    <option value=""> Select </option>';

                        foreach ($intfaculty as $if) 
                        {
                            $selected=($if['coe_val_faculty_id']==$value['faculty_id'])?' selected':"";

                            $select .= "<option value='" . $if['coe_val_faculty_id'] ."'".$selected.">" . $if['faculty_name'] ." - ".$if['faculty_board']. "</option>";
                        }
                        $select .= '</select>';
                        $body .='<td>'.$select.'</td>';
                        $body .='</tr>';
                                            
                         $sl++;

                    }
                    $body .='</tbody></table>';



                     $body .="<table width='100%' style='overflow-x:auto;'  align='center' class='table table-striped '>
                        <tr>
                            <th>S.no.</th>
                            <th>Hall Name</th>
                            <th>Additional Hall Superintendent</th>
                        </tr>
                        <tbody>"; 
                    if(count($additional_staff)>0)
                    {
                        $sl=1;
                        foreach ($additional_staff as  $value) 
                        {                    
                            $selectt='';

                            $body .='<tr>';
                            $body .='<td>'.$sl.'</td>';
                            
                            $selectt = '<select class="fhupdate" name="addstfhall" id="addstfhall"  onchange="updatefhdata1('.$value['fh_arrange_id'].',1);">
                                        <option value=""> Select </option>';

                            foreach ($hallmaster as $af) 
                            {
                                $selected=($af['coe_hall_master_id']==$value['hall_master_id'])?' selected':"";

                                $selectt .= "<option value='" . $af['coe_hall_master_id'] ."'".$selected.">" . $af['hall_name'] . "</option>";
                            }
                            $selectt .= '</select>';
                            $body .='<td>'.$selectt.'</td>';

                            $select='';
                            $select = '<select class="fhupdate" name="addstf" id="addstf"  onchange="updatefhdata1('.$value['fh_arrange_id'].',1);">
                                        <option value=""> Select </option>';

                            foreach ($intfaculty as $if) 
                            {
                                $selected=($if['coe_val_faculty_id']==$value['faculty_id'])?' selected':"";

                                $select .= "<option value='" . $if['coe_val_faculty_id'] ."'".$selected.">" . $if['faculty_name'] ." - ".$if['faculty_board']. "</option>";
                            }
                            $select .= '</select>';
                            $body .='<td>'.$select.'</td>';
                            $body .='</tr>';
                                                
                             $sl++;

                        }
                    }
                    else 
                    {
                        $sl=1;
                        $selectt='';

                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        
                        $selectt = '<select class="fhupdate" name="addstfhall" id="addstfhall">
                                    <option value=""> Select </option>';

                        foreach ($hallmaster as $af) 
                        {
                            
                            $selectt .= "<option value='" . $af['coe_hall_master_id'] ."'>" . $af['hall_name'] . "</option>";
                        }
                        $selectt .= '</select>';
                        $body .='<td>'.$selectt.'</td>';

                        $select='';
                        $select = '<select class="fhupdate" name="addstf" id="addstf" onchange="updatefhdata1(1,1);">
                                    <option value=""> Select </option>';

                        foreach ($intfaculty as $if) 
                        {
                            
                            $select .= "<option value='" . $if['coe_val_faculty_id'] ."'>" . $if['faculty_name'] ." - ".$if['faculty_board']. "</option>";
                        }
                        $select .= '</select>';
                        $body .='<td>'.$select.'</td>';
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
                        </tr>
                        <tbody>"; 

                    $sl=1;
                    foreach ($rhsdata as  $value) 
                    { 
                                       
                        $body .='<tr>';
                        $body .='<td>'.$sl.'</td>';
                        $select = '<select class="fhupdate" id="updaterhs'.$value['rhs'].'" onchange="updaterhsdata1('.$value['rhs'].');">
                                    <option value=""> Select RHS </option>';

                        $selected='';                                    
                        foreach ($intfaculty as $if) 
                        {
                            if($if['coe_val_faculty_id']==$value['rhs']){$selected=$if['faculty_name'] ." - ".$if['faculty_board'];}

                            $select .= "<option value='" . $if['coe_val_faculty_id'] ."'".$selected.">" . $if['faculty_name'] ." - ".$if['faculty_board']. "</option>";
                        }
                        $select .= '</select>';
                        $body .='<td>'.$select.'<input class="form-control" type="text" id="rhsselected'.$value['rhs'].'" value="'.$selected.'" style="width: 35% !important;" readonly></td>';
                        $body .='</tr>';
                                            
                         $sl++;

                    }
                    $body .='</tbody></table>';
                    }
                    
                   
                    echo  $header.$body;
                     
                    if (isset($_SESSION['faculty_hall_data'])) 
                    {
                        unset($_SESSION['faculty_hall_data']);
                                            
                    }

                    $send_results = $header.$body.$footer;

                    $_SESSION['faculty_hall_data'] = $send_results;
                }
            
            ?>


<?php ActiveForm::end(); ?>
</div>
</div>
</div>