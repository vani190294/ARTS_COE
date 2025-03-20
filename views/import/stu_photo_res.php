<table class="table table-striped table-bordered">
<?php
$ShowError = $_SESSION['importResults']['ShowError'];
$TotalUploadFiles = $_SESSION['importResults']['TotalUploadFiles'];
if(count($ShowError)>0 || $TotalUploadFiles>0){?>
<tr>
    <td colspan="2" align="center">
    <?php
      if(count($ShowError)>0){
          echo "<h3 style='color: #F39C12;'>".wordwrap(implode("\n",$ShowError),120)."</h3>";
      }else if($TotalUploadFiles>0){
          echo "<h3> ".$TotalUploadFiles . ' files Available in the Directory. </h3>';
      }
    ?>
    </td>
  </tr>
  <?php
  }
  unset($_SESSION['importResults']);
?>
</table>