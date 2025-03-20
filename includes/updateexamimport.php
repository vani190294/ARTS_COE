<?php 
use yii\helpers\Url;
use app\models\Batch;
use app\models\BatDegReg;
use app\models\Categorytype;
use app\models\Degree;
use app\models\Subjects;
use app\models\SubjectsMapping;
use app\models\Programme;
use app\models\ExamTimetable;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\db\Query;

$interate = 1; // Check only 1 time for Sheet Columns

foreach($sheetData as $k => $line)
{ 
    $exam_columns=['A'=>'Batch','B'=>'Degree','C'=>'Programme Code','D'=>'Exam Year','E'=>'Month','F'=>'Exam Type','G'=>'Semester','H'=>'Date(MM-DD-YYYY)','I'=>'Session','J'=>'Term','K'=>'Subject Code','L'=>'Q.P. Code','M'=>'New Date(MM-DD-YYYY)','N'=>'New Session'];

    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E'],'F'=>$line['F'],'G'=>$line['G'],'H'=>$line['H'],'I'=>$line['I'],'J'=>$line['J'],'K'=>$line['K'],'L'=>$line['L'],'M'=>$line['M'],'N'=>$line['N']];

     $mis_match=array_diff_assoc($exam_columns,$template_clumns); 
//print_r($mis_match);
 //   exit;
    if($interate==1 && count($mis_match)!=0 && count($mis_match)>0)
    {
        $misMatchingColumns = '';
        foreach ($mis_match as $key => $value) {
            $misMatchingColumns .= $key.", ";
        }
        $misMatchingColumns = trim($misMatchingColumns,', ');
        $misMatchingColumns = wordwrap($misMatchingColumns, 10, "<br />\n");
        Yii::$app->ShowFlashMessages->setMsg('Error',"<b style='font-size: 17px;'> Column (s) ".$misMatchingColumns." Are Not matching with our Original ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Template </b> Please use the Original Sample Template from the Download Link!!");
        return Yii::$app->response->redirect(Url::to(['import/index']));
    }
    else
    {
        break;
    }
    $interate +=7;
    
}
unset($sheetData[1]);
$transaction = Yii::$app->db->beginTransaction(); 
foreach($sheetData as $k => $line)
{    
    $connection = Yii::$app->db;
    $line = array_map('trim', $line);
    $exam = new ExamTimetable();
    $batch = isset($line['A'])?Batch::findOne(['batch_name'=>$line['A']]):"";
    $degree =  isset($line['B'])?Degree::findOne(['degree_code'=>$line['B']]):"";
    $programme = isset($line['C'])?Programme::findOne(['programme_code'=>$line['C']]):"";
    $subject_mapping_id = '';
    //if(!empty($batch) && !empty($programme) && !empty($degree) && !in_array(null, $line, true))
    if(!empty($batch) && !empty($programme) && !empty($degree) && !empty($line['D']) && !empty($line['E']) && !empty($line['F']) && !empty($line['G']) && !empty($line['H']) && !empty($line['I']) && !empty($line['J']) && !empty($line['K']) && !empty($line['M']) && !empty($line['N']))
    { 
         $sem = $line['G'];
            $sub_id = $line['K'];

            $batchMapping = BatDegReg::find()->where(['coe_programme_id'=>$programme->coe_programme_id,'coe_batch_id'=>$batch->coe_batch_id,'coe_degree_id'=>$degree->coe_degree_id])->one(); 

            $subject = new Query();
            $subject->select("A.subject_code,B.coe_subjects_mapping_id,B.subject_type_id")
                ->from("coe_subjects A")
                ->join('JOIN','coe_subjects_mapping B','A.coe_subjects_id=B.subject_id')
                ->where(['A.subject_code'=>$sub_id,'B.semester'=>$sem,'batch_mapping_id'=>$batchMapping->coe_bat_deg_reg_id]);
            $sub_det = $subject->createCommand()->queryOne();
        
      
        if(!empty($sub_det))
        {
            $old_exam_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($line['H'])); 

             $old_session = Categorytype::find()->where(['category_type'=>$line['I']])->one();
                $old_session_data = $old_session['coe_category_type_id'];
          
            $exam = new Query();
            $exam->select("coe_exam_timetable_id")
                ->from("coe_exam_timetable")
                ->where(['subject_mapping_id'=>$sub_det['coe_subjects_mapping_id'],'exam_date'=> $old_exam_date,'exam_session'=>$old_session_data]);
            $exam_det = $exam->createCommand()->queryOne();
  
            if(!empty($exam_det))
            {
           
                $exam_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($line['M'])); 
                $qp_code = $line['L'];

                 $new_session = Categorytype::find()->where(['category_type'=>$line['N']])->one();
                $new_session_data = $new_session['coe_category_type_id'];

                $command1 = Yii::$app->db->createCommand('UPDATE coe_exam_timetable SET exam_date="'.$exam_date.'", qp_code="'.$line['L'].'", exam_session="'.$new_session_data.'",updated_by="'.$created_by.'",updated_at="'.$created_at.'" WHERE coe_exam_timetable_id="'.$exam_det['coe_exam_timetable_id'].'" ')->execute();

                if($command1)
                {
                    $val = Yii::$app->db->createCommand('update coe_dummy_number set exam_date ="'.$exam_date.'",exam_session="'.$new_session_data.'", updated_at="'.$created_at.'",updated_by="'.$created_by.'" where subject_map_id="'.$sub_det['coe_subjects_mapping_id'].'"')->execute();

                    if($val)
                    {
                        $totalSuccess+=1;
                        $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Success, Exam date and dummy number date are updated']);  
                    }
                    else
                    {
                        $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Exam date updated but dummy number date not updated']);
                    }

                    //$totalSuccess+=1;
                   // $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Success, Exam date updated']);    
                }
                else
                {                        
                    $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Unable to Import the File']);
                }
            }
            else
            {
                $dispResults[] = array_merge($line,['type' => 'E',  'message' => 'No Exam data Found']);
                Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
            }
        }
        else
        {
            $dispResults[] = array_merge($line,['type' => 'E',  'message' => 'No subject data Found']);
            Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
        }
         
    } // Not empty of Batch & Other related ids
    else
    {                    
        $dispResults[] = array_merge($line, 
            ['type' => 'E',  'message' => 'Wrong Data']);
        Yii::$app->ShowFlashMessages->setMsg('Error',"We are Unable to fetch the data with your submision.");
    }           
} // Foreach Ends Here  

try
{
    $transaction->commit();
}
catch(\Exception $e)
{
   if($e->getCode()=='23000')
   {
       $message = "Duplicate Entry";
   }
   else
   {
       $transaction->rollback(); 
       $message = "Error";
   }
   $dispResults[] = array_merge($line, ['type' => 'E',  'message' => $message]);
}

$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>'updateexam'];
?>