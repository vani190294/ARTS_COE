<?php 
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\Categories;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?> 
<?php


if(isset($data) && !empty($data) && !empty($model->app_month))
{       
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        /* 
        *   Already Defined Variables from the above included file
        *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
        *   use these variables for application
        *   use $file_content_available="Yes" for Content Status of the Organisation
        */
        
        $supported_extensions = ConfigUtilities::ValidFileExtension(); 
        $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
        $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";
        
        if($file_content_available=="Yes")
        {

            $previous_subject_code= "";
            $previous_reg_number = "";
            $html = "";
            $header = "";
            $body ="";
            $footer = "";
            $print_register_number="";
            $new_stu_flag=0;
            $print_stu_data="";
            
            $month = is_integer($model->app_month)? Categorytype::find()->where(['coe_category_type_id'=>$model->app_month])->one():Categorytype::find()->where(['description'=>$model->app_month])->one();
            $app_month = $month['description'];

            $ug_fees_struc = Categories::findOne(['category_name'=>'Fees Structure UG']);
            //$ug_print = Categorytype::findOne(['category_id'=>$ug_fees_struc->coe_category_id]);
            $pg_fees_struc = Categories::findOne(['category_name'=>'Fees Structure PG']);
            //$pg_print = Categorytype::find()->where(['category_id'=>$ug_fees_struc->coe_category_id])->all();
            
            $theory_per_paper_ug = Categorytype::findOne(['category_id'=>$ug_fees_struc->coe_category_id,'category_type'=>'Theory PER PAPER']);
            $theory_per_paper_pg = Categorytype::findOne(['category_id'=>$pg_fees_struc->coe_category_id,'category_type'=>'Theory PER PAPER']);

            $prac_per_paper_ug = Categorytype::findOne(['category_id'=>$ug_fees_struc->coe_category_id,'category_type'=>'Practicals PER PAPER']);
            $prac_per_paper_pg = Categorytype::findOne(['category_id'=>$pg_fees_struc->coe_category_id,'category_type'=>'Practicals PER PAPER']);

            $engg_lab_ug = Categorytype::findOne(['category_id'=>$ug_fees_struc->coe_category_id,'category_type'=>'engg practice / phy sci lab']);
            $engg_lab_pg = Categorytype::findOne(['category_id'=>$pg_fees_struc->coe_category_id,'category_type'=>'engg practice / phy sci lab']);

            $project_viva_voce_ug = Categorytype::findOne(['category_id'=>$ug_fees_struc->coe_category_id,'category_type'=>'project viva voce']);
            $project_viva_voce_pg = Categorytype::findOne(['category_id'=>$pg_fees_struc->coe_category_id,'category_type'=>'project viva voce']);

            $regn_statement_ug = Categorytype::findOne(['category_id'=>$ug_fees_struc->coe_category_id,'category_type'=>'REGN AND STATEMENT OF GRADE']);

            $regn_statement_pg = Categorytype::findOne(['category_id'=>$pg_fees_struc->coe_category_id,'category_type'=>'REGN AND STATEMENT OF GRADE']);

            $training_ug = Categorytype::findOne(['category_id'=>$ug_fees_struc->coe_category_id,'category_type'=>'INTERNSHIP TRAINING']);
            $training_pg = Categorytype::findOne(['category_id'=>$pg_fees_struc->coe_category_id,'category_type'=>'INTERNSHIP TRAINING']);

            $late_fees_ug = Categorytype::findOne(['category_id'=>$ug_fees_struc->coe_category_id,'category_type'=>'Late Fees']);
            $late_fees_pg = Categorytype::findOne(['category_id'=>$pg_fees_struc->coe_category_id,'category_type'=>'Late Fees']);


            $append_data = "<tr>
                                <td style='border: none;' colspan=4>
                                    <table>
                                        <tr>
                                            <td>F</td>
                                            <td colspan=2>FIRST APPEARANCE</td>
                                            <td>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))."</td>
                                            <td>REGULAR</td>
                                            <td>ARREAR</td>
                                            <td>FEES</td>
                                        </tr>
                                        <tr>
                                            <td>R</td>
                                            <td colspan=2>RE-APPEARANCE</td>
                                            <td>THEORY</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>I</td>
                                            <td colspan=2>REAPPEARANCE TO IMPROVE PERFORMANCE</td>
                                            <td>PRACTICAL</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan=3>&nbsp;</td>
                                            <td colspan=3>PROJECT WORK & VIVA VOCE</td>
                                            <td >&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td>EXAMINATION FEES DETAILS</td>
                                            <td>UG</td>
                                            <td>PG</td>
                                            <td colspan=3>TECHNICAL SEMINAR</td>
                                            <td >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>".strtoupper($theory_per_paper_ug->category_type)."</td>
                                            <td>".$theory_per_paper_ug->description."</td>
                                            <td>".$theory_per_paper_pg->description."</td>
                                            <td colspan=3>INTERNSHIP TRAINING</td>
                                            <td >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>".strtoupper($prac_per_paper_ug->category_type)."</td>
                                            <td>".$prac_per_paper_ug->description."</td>
                                            <td>".$prac_per_paper_pg->description."</td>
                                            <td colspan=3>REGISTRATION AND STATEMENT FOR GRADE</td>
                                            <td >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>".strtoupper($engg_lab_ug->category_type)."</td>
                                            <td> ".$engg_lab_ug->description." </td>
                                            <td> ".$engg_lab_pg->description." </td>
                                            <td colspan=3>CONSOLIDATED STATEMENT FOR GRADE</td>
                                            <td >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>".strtoupper($project_viva_voce_ug->category_type)."</td>
                                            <td>".$project_viva_voce_ug->description."</td>
                                            <td>".$project_viva_voce_pg->description."</td>
                                            <td colspan=3>DEGREE AND PROV CERTIFICATE</td>
                                            <td >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>".strtoupper($regn_statement_ug->category_type)."</td>
                                            <td> ".$regn_statement_ug->description." </td>
                                            <td> ".$regn_statement_pg->description." </td>
                                            <td colspan=3>".strtoupper('miscellaneous')."</td>
                                            <td >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>".strtoupper($training_ug->category_type)."</td>
                                            <td>".$training_ug->description."</td>
                                            <td>".$training_pg->description."</td>
                                            <td colspan=3>LATE FEE</td>
                                            <td >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>".strtoupper($late_fees_ug->category_type)."</td>
                                            <td> ".$late_fees_ug->description." </td>
                                            <td> ".$late_fees_pg->description." </td>
                                            <td colspan=3>&nbsp;</td>
                                            <td >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td> &nbsp;</td>
                                            <td> &nbsp;</td>
                                            <td> &nbsp;</td>
                                            <td colspan=3>TOTAL AMOUNT</td>
                                            <td >&nbsp;</td>
                                        </tr>

                                    </table>
                                </td>
                               
                            </tr>";

            echo Html::a('<i class="fa fa-file-pdf-o"></i> Print Pdf', ['/student/print-application-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
            ]); 
            


            echo "<br /><br />";
            $open_div ='<div class="row"><div class="col-xs-12"><div class="col-xs-12 col-sm-1 col-lg-1">&nbsp;</div><div class="col-xs-12 col-sm-10 col-lg-10"><div class="col-xs-12 col-sm-1 col-lg-1"></div>';
            $close_div = "<br /><br /></div></div>";
            foreach ($data as  $value) {
                    
                    $files = glob($absolute_dire.$value['register_number'].".*"); // Will find 2.JPG, 2.php, 2.gif
                    // Process through each file in the list
                    // and output its extension

                    if (count($files) > 0)
                    foreach ($files as $file)
                     {
                        $info = pathinfo($file);
                        $extension = ".".$info["extension"];
                     }
                     else
                     {
                        $extension="";
                     }
                $photo_extension = ConfigUtilities::match($supported_extensions,$value['register_number'].$extension); 

                
             $stu_photo = $photo_extension!="" ? $stu_directory.$value['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg"; 
               $subject_fees = $value['subject_fee'];
                if($previous_reg_number!=$value['register_number'])
                {                    
                    $new_stu_flag=$new_stu_flag + 1;
                    if($new_stu_flag > 1) {
                            //print_r($new_stu_flag);
                            $html = $header .$body.$footer; 
                            $print_stu_data .= $html;
                            $header = "";
                            $body ="";
                            $footer = "";
                            $new_stu_flag = 1;
                    }   

                    $header .="<table align='center' class='table table-striped' >";
                    $header .= '<tr>
                    <td style="border: none;" colspan=4>
                    <table width="100%" align="center" border="0" >                    
                    <tr>
                      <td> 
                        <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=2 align="center"> 
                          <center><b><font size="6px">'.$org_name.'</font></b></center>
                          <center> <font size="3px">'.$org_address.'</font></center>
                          <center> Phone : <b>'.$org_phone.'</b></center>
                          <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                     </td>
                      <td align="center">  
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>
                    
                    </table></td></tr>';
                    $header .="<tr>
                    <td style='border: none; border-right: 1px solid #999;border-left: 1px solid #999;' colspan=4><b>APPLICATION FOR END SEMESTER EXAMINATIONS ".$model->app_year." / ".$app_month."</b></td></tr>
                    <tr>
                                <td colspan=2>
                                    Name of the ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." <br />
                                    <b>".$value['name']."</b>
                                </td>
                                <td>
                                    Semester <br />
                                    <b>".$value['semester']."</b>
                                </td>
                                <td>
                                    Register Number <br />
                                    <b>".$value['register_number']."</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=3> 
                                ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." : ".$value['degree_name']." <br />
                                ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." : ".$value['programme_name']."
                                </td>
                                <td>
                                    Date of Birth <br />
                                    ".$value['dob']."
                                </td>
                            </tr>
                            <tr>
                                <td colspan=3>
                                 Address : ".$value['current_address']." <br />
                                 City : ".$value['current_city']." <br />
                                 State : ".$value['current_state']." <br />
                                 Country : ".$value['current_country']." Pincode : ".$value['current_pincode']."

                                </td>
                                <td>
                                    <img class='img-responsive' width=120 height=120 src=".$stu_photo." alt='".$stu_photo." Photo' >
                                </td>
                            </tr>
                            <tr>
                                <td>Semester</td>
                                <td>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code </td>
                                <td colspan=2>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Name </td>
                            </tr>
                            ";


                            $footer .="<tr>
		                                <td colspan=2 style='text-align: center;' ><b>ABBREVIATIONS</b></td>
		                                <td colspan=2 style='text-align: center;' ><b>FEE PAYMENT PARTICULARS</b></td>
		                              </tr> ".$append_data."
                            <tr>
                                <td style='border: none;text-align: left;border-right: 1px solid #999; border-left: 1px solid #999;border-bottom: 1px solid #999;' colspan='3'>
                                    ".strtoupper('There by, I declare that the particulars submitted above are correct ')."
                                    <br /> STATION: <br /> DATE: <br />
                                    <p style='text-align: right !important;'>".strtoupper('Signature Of The Candidate')."</p>
                                </td>
                                <td style='border: none; text-align: right; border-right: 1px solid #999; border-bottom: 1px solid #999;' ><br /><br />
                                    ".strtoupper('Signature Of The HOD')."
                                </td>
                            </tr>
                            </table><pagebreak></pagebreak>";// Closing the Main Header Table

                            
                           
                            $body .="<tr>
                                <td>".$value['semester']."</td>
                                <td>".$value['subject_code']."</td>
                                <td colspan='2'>".$value['subject_name']."</td>
                            </tr>";

                            if(isset($data_1) && !empty($data_1))
                            {
                                foreach ($data_1 as $nominal) {
                                    if(ConfigUtilities::in_array_r($value['register_number'], $nominal))
                                    {
                                        if($value['register_number']==$nominal['register_number'])
                                        {
                                            $nominal_print_reg = $nominal['register_number'];
                                            $body .="<tr>
                                            <td>".$nominal['semester']."</td>
                                            <td>".$nominal['subject_code']."</td>
                                            <td colspan='2'>".$nominal['subject_name']."</td>
                                            </tr>"; 
                                        }
                                        
                                    }
                                }
                                
                            }
                }
                else{
                         
                        $body .="<tr>
                            <td>".$value['semester']."</td>
                            <td>".$value['subject_code']."</td>
                            <td colspan='2'>".$value['subject_name']."</td>
                        </tr>";
                        
                        if(isset($data_1) && !empty($data_1))
                        {
                            foreach ($data_1 as $nominal) {
                                if(ConfigUtilities::in_array_r($value['register_number'], $nominal))
                                {
                                   if($value['register_number']==$nominal['register_number'] && $nominal_print_reg!=$nominal['register_number'])
                                   {
                                        $nominal_print_reg = $nominal['register_number'];
                                        $body .="<tr>
                                        <td>".$nominal['semester']."</td>
                                        <td>".$nominal['subject_code']."</td>
                                        <td colspan='2'>".$nominal['subject_name']."</td>
                                        </tr>"; 
                                    } 
                                    
                                }
                            }
                            
                        }                            
                      
                }
                $previous_subject_code = $value['subject_code'];
                $previous_reg_number=$value['register_number']; 
                    
            }// End the foreach variable here
            
            $html = $header .$body.$footer;
            $print_stu_data .=$html;
            if(isset($_SESSION['student_application_date'])){ unset($_SESSION['student_application_date']);}
            $_SESSION['student_application_date'] = $print_stu_data;
            echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-1" >&nbsp;</div><div class="col-lg-9" >'.$print_stu_data.'</div><div class="col-lg-1" >&nbsp;</div></div></div></div></div>'; 
               
        }// If no Content found for Institution
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');            
        }
}
else
{ 
    Yii::$app->ShowFlashMessages->setMsg('Error','No data Found');            
}

?>