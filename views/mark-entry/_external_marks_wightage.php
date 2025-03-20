<?php

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\Degree;


extract($data);
$getBranchName = Degree::getCompleteDegreeName($batch_map_id);
$monthName = Categorytype::getMonthName($exam_month);
?>
    
    <table class="table table-bordered table-responsive table-striped table-dark table-info" >  
    <thead class="thead-dark">

    <tr class="p-3 mb-2 bg-dark text-white"  >
        <th style="text-align: center;" colspan="14" >
            <h2> EXTERNAL MARK CONVERSION DETAILS - <?php echo $getBranchName; ?> : <?php echo $year; ?> - <?php echo $monthName; ?> </h2>
        </th>
    </tr>  

    </thead>                  
    <tr class="table-danger">
        <th width="30px;">S.NO</th>  
        <th> REGISTER NUMBER </th>
        <th> <?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); ?> CODE </th>
        <th> <?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); ?> NAME </th>
        <th>CIA MAX</th>
        <th>ESE MAX</th>
        <th>CIA </th>
        <th>SEM FROM </th>
        <th>SEM TO </th>
        
        <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); ?> CODE COUNT </th>
        <th>PREV ESE TOTAL </th>
        <th>PREV AVERAGE</th>
        <th>WIGHTAGE MARKS</th>
        <th>TOTAL MARKS</th>
    </tr>

    <?php
        $increment = 1;
        foreach ($studentMarks as $value) 
        {
            echo '
                <tr class="table-danger">
                    <th width="30px;">'.$increment.'</th> 
                    <th>'.$value['studentMap']['studentRel']['register_number'].'</th>
                    <th>'.$value['subjectMap']['coeSubjects']['subject_code'].'</th>
                    <th>'.$value['subjectMap']['coeSubjects']['subject_name'].'</th>
                    <th>'.$value['subjectMap']['coeSubjects']['CIA_max'].'</th>
                    <th>'.$value['subjectMap']['coeSubjects']['ESE_max'].'</th>
                    <th>'.$value['current_cia_marks'].'</th>
                    <th>'.$value['prev_semester'].'</th>
                    <th>'.$value['current_semester'].'</th>

                    <th>'.$value['prev_subjects_count'].'</th>
                    <th>'.$value['prev_ese_total'].'</th>
                    <th>'.$value['prev_average'].'</th>
                    <th>'.$value['wightage_marks'].'</th>
                    <th>'.$value['out_of_100'].'</th>

                </tr>';
               // <th>'.$value['average'].'</th>
            $increment++;
        }
    ?>
    </table>