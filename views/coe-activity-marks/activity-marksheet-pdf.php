<?php
use yii\db\Query;

$this->registerCssFile("@web/css/activitymarksheet.css");

$n=count($stu_data)-1;
$html='';
$loop=0;
foreach ($stu_data as $markvalue) 
{
	$header="<div class='acthead'>".$markvalue['name'].", ".$markvalue['register_number']."</div>";

	$fetch_query = new Query();      
    $fetch_query->select([ 'A.duration','A.subject_code','B.subject_name'])  
            ->from('coe_activity_marks as A')
            ->Where(['A.register_number' => $markvalue['student_map_id']])
             ->join('JOIN','coe_add_points as B','B.subject_code=A.subject_code')
            ->orderBy('A.subject_code');   
    // echo $fetch_query->createCommand()->getrawsql(); exit;     
    $sub_data = $fetch_query->createCommand()->queryAll();
    
    $table="<table border='0' width='100%'>";
    $table.="<tr>";
	$table.="<th align=left>CODE</th>";
	$table.="<th align=center>AICTE ACTIVITY POINT PROGRAMMES</th>";
	$table.="<th align=left>POINTS</th>";
	$table.="</tr>";
	$act_points=0;
    foreach ($sub_data as $subvalue) 
    {
    	$table.="<tr>";
    	$table.="<td>".$subvalue['subject_code']."</td>";
    	$table.="<td class='subject_name'>".$subvalue['subject_name']."</td>";
    	$table.="<td align=center>".$subvalue['duration']."</td>";
    	$table.="</tr>";
    	$act_points=$act_points+$subvalue['duration'];
    }
    $table.="<tr>";
	$table.="<td align=center colspan=3 class='act_earned'>TOTAL ACTIVITY POINTS EARNED: ".$act_points."</td>";
	$table.="</tr>";
    $table.="</table>";
    $table.="<tr>";
	$table.="<td align=center colspan=3 class='aicte_act'>**AICTE ACTIVITY POINTS EARNED WILL NOT BE CONSIDERED FOR CGPA CALCULATION**</td>";
	$table.="</tr>";
    $table.="</table>";
	$body="<div class='actbody'>".$table."</div>";

	$tablefooter="<table border='0'>";
	$tablefooter.="<tr>";
	$tablefooter.="<td class='degree_code top'>".$markvalue['degree_code']."</td>";
	$tablefooter.="<td class='programme_name top'>".$markvalue['programme_name']."</td>";

	if($markvalue['status_category_type_id']==7)
	{
		$batch_name=($markvalue['batch_name']+1)."-".($markvalue['batch_name']+4);
	}
	else if($markvalue['status_category_type_id']==6)
	{
		$batch_name=($markvalue['admission_year'])."-".($markvalue['batch_name']+4);
	}
	else
	{
		$batch_name=$markvalue['batch_name']."-".($markvalue['batch_name']+4);
	}
		
	$tablefooter.="<td class='batch_name top'>".$batch_name."</td>";	
	$tablefooter.="<td class='regulation_year top'>".$markvalue['regulation_year']."</td>";
	$tablefooter.="</tr>";
	$tablefooter.="</table>";

	$footer="<div class='actfooter'>".$tablefooter."</div>";



	$datefooter=" <htmlpagefooter name='firstpage'><p class='footerpage'>DATE: ".date("d/m/Y",strtotime($activity_print_date))."</htmlpagefooter><sethtmlpagefooter name='firstpage' page='O' value='on'/> ";

	if($loop<$n)
	{
		$datefooter.="<pagebreak>";
	}
	
	$html.=$header.$body.$footer.$datefooter;

	$loop++;
}
if (isset($_SESSION['activity_marksheetpdf']))
{
    unset($_SESSION['activity_marksheetpdf']);
}
$_SESSION['activity_marksheetpdf'] = $html;
echo $html;
?>