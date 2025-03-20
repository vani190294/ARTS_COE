<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\dialog\Dialog;
use app\models\Categorytype;
use kartik\date\DatePicker;
use app\models\ExamTimetable;


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\AbsentEntry */
/* @var $form ActiveForm */
$this->title= "Consolidated Hall Invigilation";

$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?php echo $this->title; ?></h1>
<br /><br />
<div class="exam-timetable-absent">
<div class="box box-success">
<div class="box-body">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin(); ?>

<div class="row">
<div class="col-12">
    
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
       
       
         
   <div class="col-lg-2 col-sm-3"> 
        <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable/new-export-exam-timetable']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
    

  </div>
</div>
</div>
</div>
</div>
 <?php ActiveForm::end(); ?>

 <div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto !important;">
<?php 
    if(isset($consolidateddata) && !empty($consolidateddata))
    {

        $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['qp/consolidatedfacultyhall-pdf'], [
                            'class' => 'pull-right btn btn-block btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                    ]);
                    
        echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-10" ></div> <div class="col-lg-2" > ' . $print_pdf. ' </div></div></div></div></div><div class="col-xs-12" >';

        $board=array();
        $board=['CIVIL','CSE/IT','ECE','EEE','MCT','MECH','MBA','MATHS','PHYSICS','CHEMISTRY','ENGLISH'];

        
        $html = "";
        $header = "";
        $body ="";
        $footer = "";  

        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

        $header .="<table width='100%' style='overflow-x:auto !important;'  align='center' class='table table-striped '>";
        $header .= '<thead><tr>
                    <th align="center" style="border-right:0px !important;">
                        <img class="img-responsive" width="75" height="75" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                    </th>
                    <th colspan=10 align="center" style="border-right:0px !important; border-left:0px !important;">
                        <h4> 
                          <center><b><font size="5px">' . $org_name . '</font></b></center></h4>
                           <h6>   <center> ' . $org_address . '</center>
                           <center class="tag_line"><b>' . $org_tagline . '</b></center> </h6>
                        
                         <h5> <center>Consolidated Hall Invigilation - '.$_SESSION['get_examyear'].' End Semester Regular/Arrear Examinations </center></h5>
                    </th>
                <th align="center" style="border-left:0px !important;">  
                    <img width="75" height="75" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                </th>
            </tr> ';

       /*  $header .="
            <tr>
                <th>DATE & SESSION</th>";

        for ($i=0; $i <count($board) ; $i++) 
        { 
           
          $header .="<th>"; 
          $header .=$board[$i];
          $header .="</th>"; 
        }</tr>;*/

        $header .="</thead>
            <tbody style='overflow-x:auto !important;'>"; 

        foreach ($consolidateddata as $value) 
        {
            $body .='<tr>';
            $body .="<td><b>"; 
            $body .=date("d-m-Y",strtotime($value['exam_date']))."<br> & ".$value['category_type'];
            $body .="</b></td>"; 

            for ($i=0; $i <count($board) ; $i++) 
            { 
                

               $faculty_hall_data = Yii::$app->db->createCommand("SELECT hall_name,concat(C.faculty_name,'</b>, <br>(',C.faculty_designation,')') as faculty_name FROM coe_faculty_hall_arrange A JOIN coe_valuation_faculty C ON C.coe_val_faculty_id=A.faculty_id JOIN coe_hall_master D ON D.coe_hall_master_id=A.hall_master_id WHERE A.exam_date='" .date("Y-m-d",strtotime($value['exam_date'])) . "' AND A.exam_session='" . $value['exam_session'] . "' AND C.faculty_board='" . $board[$i] . "' GROUP BY A.faculty_id")->queryAll();

               $rhsdata = Yii::$app->db->createCommand("SELECT DISTINCT rhs,concat(C.faculty_name,'</b>, <br>(',C.faculty_designation,')') as faculty_name FROM coe_faculty_hall_arrange A  JOIN coe_valuation_faculty C ON C.coe_val_faculty_id=A.rhs WHERE A.exam_date='" .date("Y-m-d",strtotime($value['exam_date'])) . "' AND A.exam_session='" . $value['exam_session'] . "' AND C.faculty_board='" . $board[$i] . "' GROUP BY A.rhs")->queryAll();

               $board_flty='';

                $countday=count($faculty_hall_data)+count($rhsdata);
                $body .="<td><b>"; 
                $body .=$board[$i]."(".$countday.")";
                $body .="</b></td>";


            }

            $body .='</tr>';

             $body .='<tr>';
            $body .="<td></td>"; 

            for ($i=0; $i <count($board) ; $i++) 
            {                 

               $faculty_hall_data = Yii::$app->db->createCommand("SELECT hall_name,concat('<b>',C.faculty_name,'</b>, <br>(',C.faculty_designation,')') as faculty_name FROM coe_faculty_hall_arrange A JOIN coe_valuation_faculty C ON C.coe_val_faculty_id=A.faculty_id JOIN coe_hall_master D ON D.coe_hall_master_id=A.hall_master_id WHERE A.exam_date='" .date("Y-m-d",strtotime($value['exam_date'])) . "' AND A.exam_session='" . $value['exam_session'] . "' AND C.faculty_board='" . $board[$i] . "' GROUP BY A.faculty_id")->queryAll();

               $rhsdata = Yii::$app->db->createCommand("SELECT DISTINCT rhs,concat('<b>',C.faculty_name,'</b>, <br>(',C.faculty_designation,')') as faculty_name FROM coe_faculty_hall_arrange A  JOIN coe_valuation_faculty C ON C.coe_val_faculty_id=A.rhs WHERE A.exam_date='" .date("Y-m-d",strtotime($value['exam_date'])) . "' AND A.exam_session='" . $value['exam_session'] . "' AND C.faculty_board='" . $board[$i] . "' GROUP BY A.rhs")->queryAll();

               $board_flty='';

              foreach ($faculty_hall_data as $bdf) 
              {
                  $board_flty.=$bdf['faculty_name']."<br><br>";

              }

              foreach ($rhsdata as $rhsd) 
              {
                  $board_flty.=$rhsd['faculty_name']."<br><br>";

              }

              $body .="<td>".$board_flty."</b></td>";

            }

            $body .='</tr>';
        }

        
        $body .='</tbody></table>';

        $footer .='<table width="100%" style="overflow-x:auto; border:0px !important;">
            
                    <tr height="100px">

                    <td style="height: 50px; text-align: right; margin-right: 5px; border:0px !important;"> 
                    <br>                        
                       <br>
                        <div style="text-align: right; font-size: 14px; " ><b>'.strtoupper("Controller Of Examinations/Chief Superintendent").'</b> </div>
                      </td>
                    </tr></tbody></table>';


        echo $header.$body;

        if (isset($_SESSION['Consolidatedfacultyhalldata'])) 
        {
            unset($_SESSION['Consolidatedfacultyhalldata']);
                                
        }

        $send_results = $header.$body.$footer.'</div>';

        $_SESSION['Consolidatedfacultyhalldata'] = $send_results;
    }
?>    

</div>
