<?php 
use yii\helpers\Url;
use app\models\Batch;
use app\models\Student;
use app\models\BatDegReg;
use app\models\CoeBatDegReg;
use app\models\Categorytype;
use app\models\Degree;
use app\models\Subjects;
use app\models\SubjectsMapping;
use app\models\Programme;
use app\models\Regulation;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\UpdateTracker;

$interate = 1; // Check only 1 time for Sheet Columns

foreach($sheetData as $k => $line)
{    
     $subject_columns=['A'=>'Batch','B'=>'Degree','C'=>'Programme Code','D'=>'Course Code','E'=>'Semester','F'=>'Paper No',];

        $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E'],'F'=>$line['F']];

        $mis_match=array_diff_assoc($subject_columns,$template_clumns);

        if($interate==1 && count($mis_match)!=0 && count($mis_match)>0)
        {
            $misMatchingColumns = '';
            foreach ($mis_match as $key => $value) {
                $misMatchingColumns .= $key.", ";
            }
            
            $misMatchingColumns = trim($misMatchingColumns,', ');
            $misMatchingColumns = wordwrap($misMatchingColumns, 40, "<br />\n");
            Yii::$app->ShowFlashMessages->setMsg('Error',"<b style='font-size: 17px;'> Column (s) ".$misMatchingColumns." Are Not matching with our Original ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Template </b> Please use the Original Sample Template from the Download Link!!");
            return Yii::$app->response->redirect(Url::to(['import/index']));
        }
        else
        {
            break;
        }
    $interate +=5;        
}

unset($sheetData[1]);
$transaction = Yii::$app->db->beginTransaction();
foreach($sheetData as $k => $line)
{    

    $line = array_map('trim', $line);
    $subjects = new Subjects();
    $subject_mapping = new SubjectsMapping();
    $batch = isset($line['A'])?Batch::findOne(['batch_name'=>$line['A']]):"";
    $programme = isset($line['C'])?Programme::findOne(['programme_code'=>$line['C']]):"";
    $degree = isset($line['B'])?Degree::findOne(['degree_code'=>$line['B']]):"";
    
    if(!empty($batch) && !empty($programme) && !empty($degree) && !in_array(null, $line, true))
    { 
        $batchMapping = CoeBatDegReg::find()->where(['coe_programme_id'=>$programme->coe_programme_id,'coe_batch_id'=>$batch->coe_batch_id,'coe_degree_id'=>$degree->coe_degree_id])->one();
        $Subjects_check = $subjects::findOne(['subject_code'=>$line['D']]);               
        
            if(empty($batchMapping) && empty($Subjects_check))
            {
                $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'No Data Found']);
            }
            else
            {
                
                $subject_id = SubjectsMapping::findOne(['subject_id'=>$Subjects_check->coe_subjects_id,'batch_mapping_id'=>$batchMapping->coe_bat_deg_reg_id,'semester'=>$line['E']]); 
                        
                if(!empty($subject_id->subject_id))
                {   
					$ip_address = Yii::$app->params['ipAddress'];
                    $UpdateTracker = New UpdateTracker();
                    $UpdateTracker->subject_map_id = $subject_id->coe_subjects_mapping_id;
                    $UpdateTracker->updated_link_from = 'Import->Update Paper No';
                    $UpdateTracker->data_updated = 'PREVIOUS PAPER NO '.$subject_id['paper_no'].' NEW PAPER NO '.$line['F'];
                    $UpdateTracker->updated_ip_address = $ip_address;
                    $UpdateTracker->updated_by = $created_by;
                    $UpdateTracker->updated_at = $created_at;
                    $UpdateTracker->save();
                    unset($UpdateTracker);
                    $UpdateTracker = New UpdateTracker();

                    $connection = Yii::$app->db;
                    $command1 = $connection->createCommand('UPDATE coe_subjects_mapping SET paper_no="'.$line['F'].'",updated_by="'.$created_by.'",updated_at="'.$created_at.'" WHERE coe_subjects_mapping_id="'.$subject_id->coe_subjects_mapping_id.'" ');
                    if($command1->execute())
                     { 
                        $totalSuccess+=1;
                        $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Success']);
                     }
                     else{ 
                       
                        $dispResults[] = array_merge($line, ['type' => 'E', 'message' => 'Unable to Error']);
                     }                     
                 } // Not Empty of Subject Code 
                 else
                 {
                    $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'No Data Found']);
                 }

            } // If Batch Mapping Found then it will update data

    }// If already subject Available  
    else
    {                    
        $dispResults[] = array_merge($line, 
            ['type' => 'E',  'message' => 'Data submision is worng']);
        Yii::$app->ShowFlashMessages->setMsg('Error',"We are Unable to fetch the data with your submision.");
    }           
} // Foreach Ends Here  


try {
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
     $message = "Unknow Error";
 }
  $transaction->rollback(); 
 $dispResults[] = array_merge($line, ['type' => 'E',  'message' => $message]);
}

$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>'Update_paper'];
?>