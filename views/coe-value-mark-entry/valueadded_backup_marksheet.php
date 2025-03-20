<?php 
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\Subjects;
use app\models\ConsolidateMarks;
use app\models\CoeBatDegReg;
use app\models\Batch;
use app\models\Degree;
use app\models\Programme;
$updated_by = Yii::$app->user->getId();
$this->title = Yii::t('app', 'Value Added Mark Statement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Marks'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->ShowFlashMessages->showFlashes(); 

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


if(isset($get_console_list) && !empty($get_console_list))
{       
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
       
    $supported_extensions = ConfigUtilities::ValidFileExtension(); 
    $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
    $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";

   
    $html = $body =$body1 =$body_view =$print_stu_data = "";
    $header = "";
    $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII']; 
    
    $body_view.='<table width="100%" border="1" style="font-weight:bold;">';
    $count=count($get_console_list);
    $i=1;
    foreach ($get_console_list as  $value) 
    {
        $body =$body1 ='';
        $files = glob($absolute_dire.$value['register_number'].".*"); 

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
        $app_month_name = ConfigUtilities::getMonthName($value['exam_month']);

        $body.='<div style="text-align:center; line-height: 2em;font-weight:bold;">';
        $body.='<div style="text-align: right; padding-top:50px; padding-right: 30px;"><img  class"img_print_dat" width="100px" height="100px" src='.$stu_photo.' alt='.$stu_photo.' Photo ></div>';
        $body.='<div style="font-style: italic; padding-top:125px; font-size:22px"><b>'.strtoupper($value["name"]).'<br>'.strtoupper($value["register_number"]).'</div>';
        $body.='<div style="font-style: italic; padding-top:100px; font-size:20px">'.strtoupper($value["subject_code"]).'<br>'.strtoupper($value["subject_name"]).'</b></div>';
       

        $wordcount=str_word_count($value["programme_name"]);

        if($wordcount==5 || $wordcount==4)
        {
            $explode = explode(" ", $value["programme_name"]);

            if($wordcount==5 || count($explode)==5)
            { 
                 $programme_name=$explode[0]." ".$explode[1]." ".$explode[2]."<br>".$explode[3]." ".$explode[4];
            }
            else
            {
                 $programme_name=$explode[0]." ".$explode[1]." ".$explode[2]."<br>".$explode[3];
            }

             $body1.='<div style="text-align:center;font-style: italic; padding-top:40px;font-size:17px;color:#af071e;"><b>'.$from_date.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$to_date.'</b></div>';
            $body1.='<div style="padding-left:10px;padding-top:110px"><table width="100%" border="0" style="font-weight:bold;text-align:center;color:red"><tr><td width="10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$semester_array[$semester].'</td><td style="padding-left:-15px !important;">'.strtoupper($value["degree_code"]).'</td><td style="padding-left:-20px !important;">'.strtoupper($programme_name).'</td><td style="padding-left:55px !important;">'.strtoupper($app_month_name).' '.$value['exam_year'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></div>';
             $body1.='<div style="padding-left:25px;padding-top:70px"><table width="100%" style="font-weight:bold;text-align:center;color:red" border="0"><tr><td>'.($value["ESE_max"]+$value["CIA_max"]).'</td><td>'.$value["total"].'</td><td>'.$value["grade_name"].'</td><td>'.$value["result"].'</td></tr></table></div>'; 
              $body1.='<div style="text-align: left;padding-left:240px; padding-top:30px;color:red;"><b>'.$publication_date.'</b></div>';  
        }
        else
        {
             $body1.='<div style="text-align:center;font-style: italic; padding-top:40px;font-size:17px;color:#af071e;"><b>'.$from_date.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$to_date.'</b></div>';
             $body1.='<div style="position: fixed !important; padding-left:10px;padding-top:115px"><table width="100%" border="0" style="font-weight:bold;text-align:center;color:red"><tr><td style="width:50px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$semester_array[$semester].'</td><td style="width:50px;">'.strtoupper($value["degree_code"]).'</td><td style="width:300px;">'.strtoupper($value["programme_name"]).'</td><td style="width:100px;">'.strtoupper($app_month_name).' '.$value['exam_year'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></div>';

            $body1.='<div style="padding-left:25px;padding-top:85px"><table width="100%" style="font-weight:bold;text-align:center;color:red" border="0"><tr><td>'.($value["ESE_max"]+$value["CIA_max"]).'</td><td>'.$value["total"].'</td><td>'.$value["grade_name"].'</td><td>'.$value["result"].'</td></tr></table></div>'; 
            $body1.='<div style="text-align: left;padding-left:240px; padding-top:30px;color:red;"><b>'.$publication_date.'</b></div>';  
        }

       

            
        $body1.='</div>';
        $print_stu_data.="<div style='position: fixed !important;color:#af071e;'>".$body."</div><div style='padding-top:90px;'>".$body1."</div>";
        if($i!=$count)
        {
         $print_stu_data.="<pagebreak>";
        }
        // for view
        $body_view.='<tr>';
        $body_view.='<td style="text-align:center;"><img  class"img_print_dat" width="100px" height="100px" src='.$stu_photo.' alt='.$stu_photo.' Photo ></td>';
        $body_view.='<td style="text-align:center;">'.strtoupper($value["name"]).'<br>'.strtoupper($value["register_number"]).'</td>';
        $body_view.='<td style="text-align:center;">'.strtoupper($value["subject_code"]).'<br>'.strtoupper($value["subject_name"]).'</td>';
        $body_view.='<td style="text-align:center;">Course held from '.$from_date.' to '.$to_date.'</td>';
         $body_view.='</tr><tr>';
        $body_view.='<td style="text-align:center;">Sem: '.$semester_array[$semester].'</td><td style="text-align:center;">'.strtoupper($value["degree_code"]).'</td><td style="text-align:center;">'.strtoupper($value["programme_name"]).'</td><td style="text-align:center;">'.strtoupper($app_month_name).' '.$value['exam_year'].'</td>';
         $body_view.='</tr><tr>';
        $body_view.='<td style="text-align:center;">'.($value["ESE_max"]+$value["CIA_max"]).'</td><td style="text-align:center;">'.$value["total"].'</td><td style="text-align:center;">'.$value["grade_name"].'</td><td style="text-align:center;">'.$value["result"].'</td>';
         $body_view.='</tr><tr>';
         $body_view.='<td colspan="4">Publication Date: '.$publication_date.'</td></tr>';
    $i++;
    }

    $body_view.='</table>';
     

    if(isset($_SESSION['get_valueadded_pdf'])){ unset($_SESSION['get_valueadded_pdf']);}
            $_SESSION['get_valueadded_pdf'] = $print_stu_data; 
            echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" >'.$body_view.'</div></div></div></div>'; 

}
else
{ 
    Yii::$app->ShowFlashMessages->setMsg('Error','No data Found');            
}

?>