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
$this->title= "Consolidated Hall Invigilation Mail";

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
                            'id' => 'from_date', 
                            'name' => 'from_date',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label("From Date"); ?>
        </div>

       <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'to_date', 
                            'name' => 'to_date',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label("To Date"); ?>
        </div>
       
       
         
   <div class="col-lg-2 col-sm-3"> 
        <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['qp/consolidatedfacultyhallmail']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
    

  </div>
</div>


 <div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto !important;">
<?php 
    if(isset($consolidateddata) && !empty($consolidateddata))
    {

        $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['qp/hallcountreport-pdf'], [
                            'class' => 'pull-right btn btn-block btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                    ]);

        $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> Excel', ['qp/hallcountreportmail-excel'], [
                            'class' => 'pull-right btn btn-block btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated Excel file in a new window'
                    ]);
                    
        echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > </div><div class="col-lg-8" ></div> <div class="col-lg-2" > ' . $print_pdf.$print_excel. ' </div></div></div></div></div>';

        $board=array();
        $board=['CIVIL','CSE/IT','ECE','EEE','MCT','MECH','MBA','MATHS','PHYSICS','CHEMISTRY','ENGLISH'];

        
        $html = "";
        $head=$header = "";
        $body ="";
        $footer = "";  

        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

        
            $head .="<table width='100%' style='overflow-x:auto !important;'  align='center' class='table table-striped '>";
            $head .= '<thead><tr>
                        <th align="center" style="border-right:0px !important;">
                            <img class="img-responsive" width="75" height="75" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                        </th>
                        <th colspan=10 align="center" style="border-right:0px !important; border-left:0px !important;">
                            <h4> 
                              <center><b><font size="5px">' . $org_name . '</font></b></center></h4>
                               <h6>   <center> ' . $org_address . '</center>
                               <center class="tag_line"><b>' . $org_tagline . '</b></center> </h6>
                            
                             <h5> <center>Hall Invigilation Report - '.$_SESSION['get_examyear'].' End Semester Regular/Arrear Examinations </center></h5>
                        </th>
                    <th align="center" style="border-left:0px !important;">  
                        <img width="75" height="75" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                    </th>
                </tr> </thead></table>';

            $headercount=count($hall_date);

            $header .="<table width='100%' style='overflow-x:auto !important;'  align='center' class='table table-striped '>
                  <thead><tr>
                      <th>S.No.</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>

                      <th colspan=".$headercount.">Date & Session</th>
                    </tr></thead>
                <tbody style='overflow-x:auto !important;'>"; 
              $sl=1; $colspan=count($hall_date);
              $totalduty=0;
            foreach ($consolidateddata as $value) 
            {
                $body .='<tr>';
                $body .="<td>".$sl."</td>";
                $body .="<td>".strtoupper($value['faculty_name'])."</td>"; 
                
                $body .="<td>".$value['email']."</td>";

                $RHS = Yii::$app->db->createCommand("select count(DISTINCT rhs) from coe_faculty_hall_arrange where exam_date='" .$value['exam_date'] . "' AND rhs=".$value['faculty_id'])->queryScalar();

                $Invigilator = Yii::$app->db->createCommand("select count(faculty_id) from coe_faculty_hall_arrange where exam_date='" .$value['exam_date'] . "' AND faculty_id=".$value['faculty_id'])->queryScalar();

                $AUR = Yii::$app->db->createCommand("select count(DISTINCT aur) from coe_faculty_hall_arrange where exam_date='" .$value['exam_date'] . "' AND aur=".$value['faculty_id'])->queryScalar();

                $Chief = Yii::$app->db->createCommand("select count(DISTINCT chief) from coe_faculty_hall_arrange where exam_date='" .$value['exam_date'] . "' AND chief=".$value['faculty_id'])->queryScalar();

                if(($RHS)>0)
                {
                    $body .="<td>RHS</td>";
                }
                else if(($Invigilator)>0)
                {
                    $body .="<td>Invigilator</td>";
                }
                else if(($AUR)>0)
                {
                    $body .="<td>AUR</td>";
                }
                else if(($Chief)>0)
                {
                    $body .="<td>Chief</td>";
                }     

                $facultycount=0;
                foreach ($hall_date as $datevalue) 
                {
                    $hallduty = Yii::$app->db->createCommand("select count(faculty_id) from coe_faculty_hall_arrange where exam_date='" .$datevalue['exam_date'] . "' AND exam_session=36 AND faculty_id=".$value['faculty_id'])->queryScalar();
                    $hallduty1 = Yii::$app->db->createCommand("select count(faculty_id) from coe_faculty_hall_arrange where exam_date='" .$datevalue['exam_date'] . "' AND exam_session=37 AND faculty_id=".$value['faculty_id'])->queryScalar();

                    $rhs = Yii::$app->db->createCommand("select count(DISTINCT rhs) from coe_faculty_hall_arrange where exam_date='" .$datevalue['exam_date'] . "' AND exam_session=36 AND rhs=".$value['faculty_id'])->queryScalar(); 
                    $rhs1 = Yii::$app->db->createCommand("select count(DISTINCT rhs) from coe_faculty_hall_arrange where exam_date='" .$datevalue['exam_date'] . "' AND exam_session=37 AND rhs=".$value['faculty_id'])->queryScalar(); 

                    $aur = Yii::$app->db->createCommand("select count(DISTINCT aur) from coe_faculty_hall_arrange where exam_date='" .$datevalue['exam_date'] . "' AND exam_session=36 AND aur=".$value['faculty_id'])->queryScalar(); 
                    $aur1 = Yii::$app->db->createCommand("select count(DISTINCT aur) from coe_faculty_hall_arrange where exam_date='" .$datevalue['exam_date'] . "' AND exam_session=37 AND aur=".$value['faculty_id'])->queryScalar();

                    $chief = Yii::$app->db->createCommand("select count(DISTINCT chief) from coe_faculty_hall_arrange where exam_date='" .$datevalue['exam_date'] . "' AND exam_session=36 AND chief=".$value['faculty_id'])->queryScalar(); 
                    $chief1 = Yii::$app->db->createCommand("select count(DISTINCT chief) from coe_faculty_hall_arrange where exam_date='" .$datevalue['exam_date'] . "' AND exam_session=37 AND chief=".$value['faculty_id'])->queryScalar(); 

                    $duty=(($hallduty+$rhs+$aur+$chief)==0)?'':date("d-m-Y",strtotime($datevalue['exam_date']))." & FN";

                    $duty1=(($hallduty1+$rhs1+$aur1+$chief1)==0)?'':date("d-m-Y",strtotime($datevalue['exam_date']))." & AN";
                    
                    if($duty!='')
                    {
                        $body .="<td>".($duty)."</td>"; 
                    }
                    else if($duty1!='')
                    {
                        $body .="<td>".($duty1)."</td>"; 
                    }
                   
                   
                }      
                $body .='</tr>';
                $sl++;
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

        if (isset($_SESSION['hallcountreport'])) 
        {
            unset($_SESSION['hallcountreport']);
                                
        }

        $send_results = $header.$body.$footer;

        $_SESSION['hallcountreport'] = $head.$send_results;

        if (isset($_SESSION['hallcountreportxl'])) 
        {
            unset($_SESSION['hallcountreportxl']);
                                
        }

         $_SESSION['hallcountreportxl'] = $header.$body;
    }
?>    

</div>


</div>
</div>
</div>
 <?php ActiveForm::end(); ?>

