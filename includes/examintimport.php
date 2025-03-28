<?php 
use yii\helpers\Url;
use app\models\Batch;
use app\models\BatDegReg;
use app\models\Categorytype;
use app\models\Degree;
use app\models\Subjects;
use app\models\SubjectsMapping;
use app\models\Programme;
use app\models\ExamTimetableInt;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

$interate = 1; // Check only 1 time for Sheet Columns

foreach($sheetData as $k => $line)
{ 
    $exam_columns=['A'=>'Batch','B'=>'Degree','C'=>'Programme','D'=>'Exam Year','E'=>'Month','F'=>'Semester','G'=>'Date(DD-MM-YYYY)','H'=>'Session','I'=>'Time Slot','J'=>'Internal Exam Type','K'=>'Subject Code','L'=>'Q.P. Code'];

    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E'],'F'=>$line['F'],'G'=>$line['G'],'H'=>$line['H'],'I'=>$line['I'],'J'=>$line['J'],'K'=>$line['K'],'L'=>$line['L']];

    $mis_match=array_diff_assoc($exam_columns,$template_clumns);
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

    $line = array_map('trim', $line);
    $exam = new ExamTimetableInt();
    $batch = isset($line['A'])?Batch::findOne(['batch_name'=>$line['A']]):"";
    $degree =  isset($line['B'])?Degree::findOne(['degree_code'=>$line['B']]):"";
    $programme = isset($line['C'])?Programme::findOne(['programme_code'=>$line['C']]):"";
    $subject_mapping_id = '';
    //if(!empty($batch) && !empty($programme) && !empty($degree) && !in_array(null, $line, true))
    if(!empty($batch) && !empty($programme) && !empty($degree) && !empty($line['D']) && !empty($line['E']) && !empty($line['F']) && !empty($line['G']) && !empty($line['H']) && !empty($line['I']) && !empty($line['J']) && !empty($line['K']))
    { 
        $batchMapping = BatDegReg::find()->where(['coe_programme_id'=>$programme->coe_programme_id,'coe_batch_id'=>$batch->coe_batch_id,'coe_degree_id'=>$degree->coe_degree_id])->one();   
        $subject = Subjects::find()->where(['subject_code'=>$line['K']])->all();   
		
        if(!empty($subject) && !empty($batchMapping))
		{
			foreach ($subject as $valuesss) 
			{
			   $subject_mapping_id = !empty($subject) && !empty($batchMapping)?SubjectsMapping::findOne(['batch_mapping_id'=>$batchMapping->coe_bat_deg_reg_id,'subject_id'=>$valuesss['coe_subjects_id'],'semester'=>$line['F']]):""; 
				if(!empty($subject_mapping_id))
				{
					break;
				}
			}
		}
        

        if(!empty($subject_mapping_id))
        {
            $exam_date_conveted = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($line['G'])); 
            $check_query = "SELECT * FROM coe_subjects_mapping as A JOIN coe_exam_timetable as B ON B.subject_mapping_id=A.coe_subjects_mapping_id WHERE A.batch_mapping_id='".$batchMapping->coe_bat_deg_reg_id."' AND B.exam_date='".$exam_date_conveted."' AND B.exam_session='".$line['H']."'";
            $insert_if_no_data = Yii::$app->db->createCommand($check_query)->queryAll();
            
            if(count($insert_if_no_data)>0)
            {
                $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Error']);
            }
            else if($line['A']>$batch['batch_name'])
            {
                $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'WRONG SUBMISSION']);
            }
            else
            {
                $cia1=!empty($line['J']) ? $line['J'] : '';
                $array= array('CIA1'=>'1','CIA2'=>'2','RetestCIA1'=>'3','MODEL'=>'5','RetestCIA2'=>'4');  
                $cia1 = strtoupper($cia1);
                $internal_number=$array[$cia1];

                $time_slot=Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type = '".$line['I']."'")->queryScalar();

                $exam_session=Yii::$app->db->createCommand("SELECT coe_category_type_id FROM coe_category_type WHERE category_type = '".$line['H']."' AND category_id=11")->queryScalar();
                
                if(!empty($time_slot))
                {
                    $exam->subject_mapping_id = $subject_mapping_id->coe_subjects_mapping_id;
                    $exam->exam_year = $line['D'];
                    $exam->exam_month = $this->valueReplace($line['E'], Categorytype::getCategoryId()); 
                    $exam->exam_date = $exam_date_conveted; 
                    $exam->exam_type = 27;
                    $exam->exam_term = 34;   
                    $exam->internal_number = $internal_number;
                    $exam->time_slot = $time_slot;         
                    $exam->exam_session = $exam_session;
                    $exam->qp_code = !empty($line['L']) ? $line['L'] : '';
                    $exam->created_at = $created_at;
                    $exam->created_by = $created_by;
                    $exam->updated_at = $created_at;
                    $exam->updated_by = $created_by;

                    $check_if_already_inserted = ExamTimetableInt::find()->where(['subject_mapping_id'=>$exam['subject_mapping_id'],'exam_year'=>$exam['exam_year'],'exam_month'=>$exam['exam_month'],'internal_number'=>$exam['internal_number'],'time_slot'=>$exam['time_slot'],'exam_session'=>$exam['exam_session']])->all();

                    if(!empty($check_if_already_inserted))
                    {
                        $dispResults[] = array_merge($line, ['type' => 'E',  'message' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Already Created']);
                    }
                    else
                    { 
                        
                        if($exam->save(false))
                        {
                            $totalSuccess+=1;
                            $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Success']);    
                        }
                        else
                        {                        
                            $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Unable to Import the File']);
                        }
                    }
                }
                else
                {
                    $dispResults[] = array_merge($line,['type' => 'E',  'message' => 'Time Slot Mis match Please Check']);            
                }
            }
        }
        else
        {
            $dispResults[] = array_merge($line,['type' => 'E',  'message' => 'No data Found']);
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

$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>'examint'];
?>