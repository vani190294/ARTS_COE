<?php
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
$semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII','13'=>'XIII','14'=>'XIV'];
?>
<table class="table subjects_table table-bordered table-responsive table-striped table-dark table-info" >  
    <thead class="thead-dark">

    <tr class="p-3 mb-2 bg-dark text-white"  >
        <th style="text-align: center;" colspan="17" >
            <h2> <?php echo $getBranchName.' SEMESTER-'.$semester_array[$semester]; ?> SUBJECT INFORMATION </h2>
        </th>
    </tr>  

    </thead>                  
    <tr class="table-danger">
        <th width="30px;">S.NO</th>  
        <th> <?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT); ?> CODE </th>
        <th> <?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT); ?> NAME </th>
        <th>CIA MAX</th>
        <th>ESE MAX</th>
        <th>ACTION</th>
    </tr>
    
    <?php
        $increment = 1;
        foreach ($data as $value) 
        {
            echo '<tr class="table-danger">
                    <th width="30px;">'.$increment.'</th> 
                    <th>'.$value['coeSubjects']['subject_code'].'</th>
                    <th>'.$value['coeSubjects']['subject_name'].'</th>
                    <th>'.$value['coeSubjects']['CIA_max'].'</th>
                    <th>'.$value['coeSubjects']['ESE_max'].'</th>
                    <th><input type="checkbox" name="selectedSubjects[]" checked=checked value="'.$value['coe_subjects_mapping_id'].'" /></th>
                </tr>';
               // <th>'.$value['average'].'</th>
            $increment++;
        }
    ?>
    </table>