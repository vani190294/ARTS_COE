<?php
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Degree;
use app\models\Regulation;
use app\models\Categorytype;

extract($data);
$getBranchName = Degree::getCompleteDegreeName($batch_map_id);
$monthName = Categorytype::getMonthName($exam_month);
?>
    <table class="table table-bordered table-responsive table-striped table-dark table-info" >  
    <thead class="thead-dark">
    <tr class="p-3 mb-2 bg-dark text-white"  >
        <th style="text-align: center;" colspan="17" >
            <h2>MARKS CONVERSION FOR - <?php echo $getBranchName; ?> : <?php echo $year; ?> - <?php echo $monthName; ?> </h2>
        </th>
    </tr>  
    </thead>                  
    <tr class="table-danger">
        <th width="30px;">S.NO</th>  
        <th>REGISTER NUMBER </th>
        <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); ?> CODE </th>
        <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); ?> NAME </th>
        <th>CIA MAX</th>
        <th>ESE MAX</th>
        <th>CIA </th>
        <th>ESE </th>
        <th>WIGHTAGE MARKS</th>
        <th>TOTAL </th>
        <th>RESULT </th>
        <th>GRADE POINT</th>
        <th>GRADE NAME</th>
    </tr>
    <?php
        $increment = 1;
        foreach ($studentMarks as $value) 
        {
            $gradeDetails = Regulation::getGradeDetails($value);
            echo '<tr class="table-danger">
                    <th width="30px;">'.$increment.'</th> 
                    <th>'.$value['reg_num'].'</th>
                    <th>'.$value['subject_code'].'</th>
                    <th>'.$value['subject_name'].'</th>
                    <th>'.$value['CIA_max'].'</th>
                    <th>'.$value['ESE_max'].'</th>
                    <th>'.$value['CIA'].'</th>
                    <th>'.$gradeDetails['ese_marks'].'</th>
                    <th>'.$value['average'].'</th>
                    <th>'.$value['out_of_100'].'</th>
                    <th>'.$gradeDetails['result'].'</th>
                    <th>'.$gradeDetails['grade_point'].'</th>
                    <th>'.$gradeDetails['grade_name'].'</th>
                </tr>';
               // <th>'.$value['average'].'</th>
            $increment++;
        }
    ?>
    </table>