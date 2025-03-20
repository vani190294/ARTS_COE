<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\models\Categorytype;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\helpers\Url;
echo Dialog::widget();
$this->title = 'Galley Arrangement Reports';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  

    <div class="col-xs-12 col-sm-12 col-lg-12">                
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month_add', 


                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($exam, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'exam_date_add', 

                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($exam, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Session ----', 
                            'id' => 'exam_session',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]); ?>
        </div>
        <br />
    	<div class="col-xs-12 col-sm-3 col-lg-3">
 			<?= Html::submitButton($model->isNewRecord ? 'Show' : 'Update', ['onClick'=>"spinner();",'class' => $model->isNewRecord ? 'btn btn-group-lg btn-group btn-success' : 'btn  btn-group-lg btn-group btn-primary']) ?>
            <?= Html::a("Reset", Url::toRoute(['coe-add-hall-allocat/galley-report']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

    	</div>
    
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
    	&nbsp;
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

<?php 
  
        if(isset($get_data) && !empty($get_data))
        {
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            /* 
            *   Already Defined Variables from the above included file
            *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
            *   use these variables for application
            *   use $file_content_available="Yes" for Content Status of the Organisation
            */
           
            if($file_content_available=="Yes")
            {
                $html = "";
                $header = "";
                $body ="";
                $footer = "";
                $prev_hall_name = "";
                $new_stu_flag=0;
                $print_stu_data ='';
                $get_page_count = count($get_data);
                $old_row = '';
                $print_qp_count = array();
                $print_qp_count= array_filter($print_qp_count);
                $old_seat_number = '';
                $i=0;
                
                foreach ($get_data as $value) 
                {
                    if($prev_hall_name!=$value['hall_name'])
                    {
                        $new_stu_flag=$new_stu_flag + 1;
                        if($new_stu_flag > 1) 
                        {
                                //print_r($new_stu_flag);
                                $footer .='
                                    <tr>
                                        <td class="reduce_qp_height" colspan=3> QP CODES</td>
                                        <td class="reduce_qp_height" colspan=3> TOTAL COUNT</td>
                                    </tr>';
                                if(isset($count_qpcode) && !empty($count_qpcode))
                                {   
                                    $coun=count($count_qpcode);
                                    $vals = array_count_values($count_qpcode);
                                    $total_qp = 0;
                                    foreach ($vals as $key => $qp_vals) {
                                        $footer .='<tr>
                                            <td  class="reduce_qp_height"  colspan=3> '.$key.' </td>
                                            <td  class="reduce_qp_height"  colspan=3> '.$qp_vals.' </td>
                                        </tr>'; 
                                        $total_qp +=$qp_vals;
                                    }
                                    $footer .='
                                    <tr>
                                        <td colspan=3> TOTAL </td>
                                        <td colspan=3> '.$total_qp.' </td>
                                    </tr>';
                                    $footer .='
                                    <tr>
                                        <td class="reduce_qp_height" colspan=4> REGISTER NUMBER OF THE ENROLLED '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' </td>
                                        <td class="reduce_qp_height" colspan=2> TOTAL '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)).' </td>
                                    </tr><tr> <td colspan=4> ';
                                    $ols_qp_code = '';
                                    $qp_array = array_unique($count_qpcode);
                                    sort($qp_array);
                                    for ($king=0; $king <count($qp_array) ; $king++) 
                                    { 
                                        if(isset($qp_array[$king]))
                                        {
                                            $reg_numbers=[];
                                            if($ols_qp_code!='')
                                            {
                                                $footer .="</td><td class='reduce_qp_height' colspan=2> &nbsp; </td></tr><tr> <td  class='reduce_qp_height' colspan=4> ";
                                            }
                                            $footer .="<b style='color: #2ba00b'>".$qp_array[$king].'</b>: ';  
                                            foreach ($enrolled_Stu as $qp_filter) 
                                            {
                                                if($qp_array[$king]==$qp_filter['qp_codes'])
                                                {
                                                    $reg_numbers[]=$qp_filter['register_number'];
                                                    
                                                }

                                            }
                                            sort($reg_numbers);
                                            for ($reg=0; $reg <count($reg_numbers) ; $reg++) 
                                            { 
                                                $footer .="<b style='color: #061fe2' >".$reg_numbers[$reg].'</b>, '; 
                                            }
                                            $ols_qp_code = $qp_array[$king];
                                        }
                                                                               
                                    }
                                  
                                   $footer .='</td><td colspan=2> &nbsp; </td>
                                    <tr>
                                        <td class="reduce_qp_height" colspan=4> R-Register Number   /  Q-Question Paper Code </td>
                                        <td class="reduce_qp_height" colspan=2> TOTAL : </td>
                                    </tr>
                                    <tr>
                                        <td colspan=6 >&nbsp; <br /><br /><br /></td>                    
                                    </tr>
                                    <tr>
                                        <td class="reduce_qp_height" colspan=3 >NAME & SIGNATURE OF THE HALL SUPERINTENDENT </td>    
                                        <td class="reduce_qp_height" colspan=3 >NAME & SIGNATURE OF THE Chief Superintendent / Controller of Examinations  </td>                    
                                    </tr>';
                                    $footer .='</table><pagebreak />'; 
                                    
                                }
                                $enrolled_Stu = array_filter(array());
                                $html = $header .$body.$footer; 
                                $print_stu_data .= $html;
                                $header = "";
                                $body ="";
                                $footer = "";
                                $new_stu_flag = 1;

                                unset($count_qpcode);
                                $count_qpcode = array_filter(array());
                        }
                        $_time = $org_email=='coe@skasc.ac.in' ? '(10.00 AM TO 01.00 PM)':'(9.30 AM TO 12.30 PM)';
                        $_time_an = $org_email=='coe@skasc.ac.in' ? '(02.00 PM TO 05.00 PM)':'(01.30 PM TO 04.30 PM)';
                        $header .="<table style='overflow:wrap; height: 700px;' >";
                        $header .= '<tr>
                                        <td colspan=6>
                                              <center><h2>'.strtoupper($org_name).'</h2></center>
                                              <center><h4>'.$org_address.'</h4></center>
                                               <center><h4>'.strtoupper($org_tagline).'</h4></center>
                                         </td>                          
                                    </tr>
                       
                        <tr>
                          <td colspan=6 align="center"> <h4>
                          ADDITIONAL CREDITS END SEMESTER EXAMINATIONS : '.strtoupper($value['month']." - ".$value['year']).' </h4>
                              <h4>GALLEY ARRANGEMENTS</h4>
                         </td>                          
                        </tr>
                       
                        <tr>
                          <td colspan=6 align="center"> <h4><b>
                              DATE OF EXAMINATION : '.date('d/m/Y',strtotime($value['exam_date']))." - ".$value['exam_session'].'  </b> </h4>
                              <h4><b> FN: '.$_time.' </b> <b> AN: '.$_time_an.' </b> </h4>
                         </td>                          
                        </tr>
                        <tr>
                         <td colspan=6 align="center"> <h2><b>
                             HALL NO: '.$value['hall_name'].'</b> </h2>
                         </td>                          
                        </tr>
                        
                        ';
                        if(isset($_SESSION['galley_arrange_ment_repo_date']))
                        {
                            unset($_SESSION['galley_arrange_ment_repo_date']);
                        }
                        $_SESSION['galley_arrange_ment_repo_date'] = date('d/m/Y',strtotime($value['exam_date']))." - ".$value['exam_session'];
                        if($old_row!=$value['row'])
                        {
                            $old_seat_number = $value['row_column'];
                            if($old_seat_number>=ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_GALLEY_HALL_COLUMN_SIZE) && $old_row!=$value['row'])
                            {
                                $body .='</tr><tr>';
                                
                            }
                            $count_qpcode[]=$value['qp_code'];
                            $count_regnum[]=$value['register_number'];
                            $enrolled_Stu[] = ['register_number'=>$value['register_number'],'qp_codes'=>$value['qp_code']];

                            if($old_row=='' || $old_row!=$value['row'])
                            {
                                $body .='<tr>';
                            }
                            $body .='<tr><td  valign="bottom" width=200px align="center"> 
                                            <p>R /'.$value['register_number'].'<br />
                                             Q /'.$value['qp_code'].'<br />
                                             <b style="font-size: 30px; font-weight: bold;">'.$value['seat_no'].'<br /></b> </p>
                                    </td> ';
                        }
                        else
                        {
                            $body .='<tr>';
                            $body .='<td valign="bottom" width=200px align="center"> 
                                            <p>R /'.$value['register_number'].'<br /> 
                                             Q /'.$value['qp_code'].'<br /> 
                                             <b style="font-size: 30px; font-weight: bold;">'.$value['seat_no'].'<br /></b> </p>
                                    </td> ';
                        }

                        

                    }
                    else
                    {

                        if($old_seat_number>=ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_GALLEY_HALL_COLUMN_SIZE) && $old_row!=$value['row'])
                        {
                            $body .='</tr><tr>';
                            
                        }
                        
                        $count_qpcode[]=$value['qp_code'];
                        $count_regnum[]=$value['register_number'];
                        $enrolled_Stu[] = ['register_number'=>$value['register_number'],'qp_codes'=>$value['qp_code']];
                        $body .='<td valign="bottom" width=200px align="center"> 
                                            <p>R /'.$value['register_number'].'<br /> 
                                             Q /'.$value['qp_code'].'<br /> 
                                             <b style="font-size: 30px; font-weight: bold;">'.$value['seat_no'].'<br /></b> </p>
                                    </td> ';
                        
                        $old_row=$value['row'];
                        $old_seat_number = $value['row_column'];
                        
                    } // Else Not the same hall name
                    $prev_hall_name=$value['hall_name'];
                } // For each Ends Here 

                //print_r($new_stu_flag);
                                $footer .='
                                    <tr>
                                        <td class="reduce_qp_height" colspan=3> QP CODES</td>
                                        <td class="reduce_qp_height" colspan=3> TOTAL COUNT</td>
                                    </tr>';
                                if(isset($count_qpcode) && !empty($count_qpcode))
                                {   
                                    $coun=count($count_qpcode);
                                    $vals = array_count_values($count_qpcode);
                                    $total_qp = 0;
                                    foreach ($vals as $key => $qp_vals) {
                                        $footer .='<tr>
                                            <td class="reduce_qp_height" colspan=3> '.$key.' </td>
                                            <td class="reduce_qp_height"  colspan=3> '.$qp_vals.' </td>
                                        </tr>'; 
                                        $total_qp +=$qp_vals;
                                    }
                                    $footer .='
                                    <tr>
                                        <td colspan=3> TOTAL </td>
                                        <td colspan=3> '.$total_qp.' </td>
                                    </tr>';
                                    $footer .='
                                    <tr>
                                        <td class="reduce_qp_height" colspan=4> REGISTER NUMBER OF THE ENROLLED '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' </td>
                                        <td class="reduce_qp_height" colspan=2> TOTAL '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)).' </td>
                                    </tr><tr> <td colspan=4> ';
                                    $ols_qp_code = '';
                                    $qp_array = array_unique($count_qpcode);
                                    sort($qp_array);
                                    for ($king=0; $king <count($qp_array) ; $king++) 
                                    { 
                                        if(isset($qp_array[$king]))
                                        {
                                            $reg_numbers=[];
                                            if($ols_qp_code!='')
                                            {
                                                $footer .="</td><td class='reduce_qp_height' colspan=2> &nbsp; </td></tr><tr> <td  class='reduce_qp_height'  colspan=4> ";
                                            }
                                            $footer .="<b style='color: #2ba00b'>".$qp_array[$king].'</b>: ';  
                                            foreach ($enrolled_Stu as $qp_filter) 
                                            {
                                                if($qp_array[$king]==$qp_filter['qp_codes'])
                                                {
                                                    $reg_numbers[]=$qp_filter['register_number'];
                                                    
                                                }

                                            }
                                            sort($reg_numbers);
                                            for ($reg=0; $reg <count($reg_numbers) ; $reg++) 
                                            { 
                                                $footer .="<b style='color: #061fe2' >".$reg_numbers[$reg].'</b>, '; 
                                            }
                                            $ols_qp_code = $qp_array[$king];
                                        }
                                                                               
                                    }
                                  
                                   $footer .='</td><td colspan=2> &nbsp; </td>
                                    <tr>
                                        <td class="reduce_qp_height" colspan=4> R-Register Number   /  Q-Question Paper Code </td>
                                        <td class="reduce_qp_height" colspan=2> TOTAL : </td>
                                    </tr>
                                    <tr>
                                        <td colspan=6 >&nbsp; <br /><br /><br /></td>                    
                                    </tr>
                                    <tr>
                                        <td class="reduce_qp_height" colspan=3 >NAME & SIGNATURE OF THE HALL SUPERINTENDENT </td>    
                                        <td class="reduce_qp_height" colspan=3 >NAME & SIGNATURE OF THE Chief Superintendent / Controller of Examinations  </td>                    
                                    </tr>';
                                    $footer .='</table>'; 
                                    
                                }
                                
                
                $html = $header .$body.$footer;
                $print_stu_data .=$html;
                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/hall-allocate/galley-arrangement-pdf'], [
                    'class'=>'pull-right btn btn-block btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/hall-allocate/excel-galley-arrangement'], [
                    'class'=>'pull-right btn btn-block btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                if(isset($_SESSION['galley_reports'])){ unset($_SESSION['galley_reports']);}
                $_SESSION['galley_reports'] = $print_stu_data;

                echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > '.$print_excel.$print_pdf.' </div><div class="col-lg-10" >'.$print_stu_data.'</div></div></div></div></div>';
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('error',"Not Found the Organisation Information" );
            }


        }

    ?>
