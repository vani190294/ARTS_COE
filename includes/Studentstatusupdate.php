<?php 
use yii\helpers\Url;
use yii\db\Query;
use app\models\Student;
use app\models\StuInfo;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\UpdateTracker;
$interate = 1; // Check only 1 time for Sheet Columns

foreach($sheetData as $k => $line)
{    
     $stu_columns=['A'=>'Batch','B'=>'Roll no','C'=>'Student Status','D'=>'Semester'];

    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D']];

    $mis_match=array_diff_assoc($stu_columns,$template_clumns);
    if($interate==1 && count($mis_match)!=0 && count($mis_match)>0)
    {   
        $misMatchingColumns = '';
        foreach ($mis_match as $key => $value) {
            $misMatchingColumns .= $key.", ";
        }
        $misMatchingColumns = trim($misMatchingColumns,", ");
        $misMatchingColumns = wordwrap($misMatchingColumns, 40, "<br />\n");
        Yii::$app->ShowFlashMessages->setMsg('Error',"<b style='font-size: 17px;'> Column (s) ".$misMatchingColumns." Are Not matching with our Original ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Template </b> Please use the Sample Template from the Download Link!!");

        return Yii::$app->response->redirect(Url::to(['import/index']));
    }
    else
    {
        break;
    }
    $interate +=5;    
}

unset($sheetData[1]);

foreach($sheetData as $k => $line)
{    
    $line = array_map('trim', $line);
    $student = new Student();

    if(!empty($line['A']) && !in_array(null, $line, true))
    { 
       
        $check_stu = Student::find()->where(['register_number'=>$line['B']])->one();
       $created_at = date("Y-m-d H:i:s");
        $createdBy = Yii::$app->user->getId(); 

        if(!empty($check_stu))
         {  
            $connection = Yii::$app->db;
            $stuMapId = StuInfo::findOne(['reg_num'=>$line['B']]);
            if((isset($line['B']) && !empty($line['B'])) && (isset($line['C']) && !empty($line['C'])) && (isset($line['D']) && !empty($line['D'])))
            {

                $det_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%".$line['C']."%'")->queryScalar();

                $command = $connection->createCommand('UPDATE coe_student_mapping SET status_category_type_id="'.$det_type.'",semester_detain="'.$line['D'].'",updated_by="'.$createdBy.'",updated_at="'.$created_at.'" WHERE coe_student_mapping_id="'.$stuMapId['stu_map_id'].'" ');
                $command->execute();
                $update_status = 1;

            }
                          
             
            if($update_status == 1)
            {
                $totalSuccess+=1;
                $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Updated']);                    
            }
            else
            {
                $totalSuccess+=1;
                $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Unable to Update']);
            }
            
         }
         else{    
            $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'NOT DATA FOUND']);
         } 
        

    } // Not empty of Batch & Other related ids 
    else
    {                    
        $dispResults[] = array_merge($line, 
            ['type' => 'E',  'message' => 'No data found']);
        Yii::$app->ShowFlashMessages->setMsg('Error',"We are Unable to Insert");
    }           
} // Foreach Ends Here  

$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>"studentstatusupdate"];
?>