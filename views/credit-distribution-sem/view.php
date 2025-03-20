<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\CreditDistribution */

$this->title = 'Credit Distributions View';
$this->params['breadcrumbs'][] = ['label' => 'Credit Distributions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-distribution-create">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;font-size: 16px;">
        <b><?php echo $regulation_year;?>
        Degree Type: <?php echo $deptdata['degree_type'];?> 
        Department: <?php echo $deptdata['dept_code'];?></b>
        <br><br>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;">

        <?php if($deptdata['degree_type']=='UG')
        {?>
        <table width="100%" class="table">
            <thead>
                <tr>
                    <th  style="text-align: center;">S.No.</th>
                    <th  style="text-align: center;">Stream</th>
                    <?php for ($i=1; $i <=8 ; $i++) 
                    { ?>
                        <th  style="text-align: center;">Sem <?= $i;?></th>
                    <?php } ?>
                    <th  style="text-align: center;">Total Credit</th>
                    <th  style="text-align: center;">AICTE Model Curriculum</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sl=1; $total_credit= $total_aicte_norms=0;
                foreach ($streamdata as $value) 
                {?>
                    <tr>
                    <td  style="text-align: center;"><?= $sl;?></td>
                    <td  style="text-align: center;"><?= $value['stream_name'];?></td>
                    <?php for ($s=1; $s <=8 ; $s++) 
                    { 
                        $name="sem".$s;
                        $values=$value[$name];
                        ?>
                    <td style="text-align: center;"><?= $values;?></td>
                    <?php } ?>
                    <td  style="text-align: center;"><?= $value['total_credit'];?></td>
                    <td  style="text-align: center;"><?= $value['aicte_norms'];?></td>
                    </tr>
                <?php $sl++;
                    $total_aicte_norms=$total_aicte_norms+$value['aicte_norms'];
                    $total_credit=$total_credit+$value['total_credit'];
                } ?>
                <tr>
                    <td colspan="2" align="right"><b>Total</b></td>
                    <?php for ($s=1; $s <=8 ; $s++) 
                    { 
                        $semtot=0;
                        $name="sem".$s;
                        foreach ($streamdata as $value) 
                        {
                            $semtot=$semtot+$value[$name];
                        }
                    ?>
                    <td style="text-align: center;"><?= $semtot;?></td>
                    <?php } ?>
                    <td><?= $total_credit; ?></td>
                    <td><?= $total_aicte_norms; ?></td>
                </tr>
            </tbody>
        </table>
        <?php }
         else if($deptdata['degree_type']=='MBA')
        {?>
        <table width="100%" class="table">
            <thead>
                <tr>
                    <th  style="text-align: center;">S.No.</th>
                    <th  style="text-align: center;">Stream</th>
                    <?php for ($i=1; $i <=4 ; $i++) 
                    { ?>
                        <th  style="text-align: center;">Sem <?= $i;?></th>
                    <?php } ?>
                    <th  style="text-align: center;">Total Credit</th>
                    <th  style="text-align: center;">AICTE Model Curriculum</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sl=1; $total_credit= $total_aicte_norms=0;
                foreach ($streamdata as $value) 
                {?>
                    <tr>
                    <td  style="text-align: center;"><?= $sl;?></td>
                    <td  style="text-align: center;"><?= $value['stream_name'];?></td>
                    <?php for ($s=1; $s <=4 ; $s++) 
                    { 
                        $name="sem".$s;
                        $values=$value[$name];
                        ?>
                    <td style="text-align: center;"><?= $values;?></td>
                    <?php } ?>
                    <td  style="text-align: center;"><?= $value['total_credit'];?></td>
                    <td  style="text-align: center;"><?= $value['aicte_norms'];?></td>
                    </tr>
                <?php $sl++;
                    $total_aicte_norms=$total_aicte_norms+$value['aicte_norms'];
                    $total_credit=$total_credit+$value['total_credit'];
                } ?>
                <tr>
                    <td colspan="2" align="right"><b>Total</b></td>
                    <?php for ($s=1; $s <=4 ; $s++) 
                    { 
                        $semtot=0;
                        $name="sem".$s;
                        foreach ($streamdata as $value) 
                        {
                            $semtot=$semtot+$value[$name];
                        }
                    ?>
                    <td style="text-align: center;"><?= $semtot;?></td>
                    <?php } ?>
                    <td><?= $total_credit; ?></td>
                    <td><?= $total_aicte_norms; ?></td>
                </tr>
            </tbody>
        </table>
        <?php }
         else if($deptdata['degree_type']=='PG')
        {?>
            <table width="100%" class="table">
            <thead>
                <tr>
                    <th  style="text-align: center;">S.No.</th>
                    <th  style="text-align: center;">Stream</th>
                    <?php for ($i=1; $i <=4 ; $i++) 
                    { ?>
                        <th  style="text-align: center;">Sem <?= $i;?></th>
                    <?php } ?>
                    <th  style="text-align: center;">Total Credit</th>
                    <th  style="text-align: center;">AICTE Model Curriculum</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sl=1; $total_credit= $total_aicte_norms=0;
                foreach ($streamdata as $value) 
                {?>
                    <tr>
                    <td  style="text-align: center;"><?= $sl;?></td>
                    <td  style="text-align: center;"><?= $value['stream_name'];?></td>
                    <?php for ($s=1; $s <=4 ; $s++) 
                    { 
                        $name="sem".$s;
                        $values=$value[$name];
                        ?>
                    <td style="text-align: center;"><?= $values;?></td>
                    <?php } ?>
                    <td  style="text-align: center;"><?= $value['total_credit'];?></td>
                    <td  style="text-align: center;"><?= $value['aicte_norms'];?></td>
                    </tr>
                <?php $sl++;
                    $total_aicte_norms=$total_aicte_norms+$value['aicte_norms'];
                    $total_credit=$total_credit+$value['total_credit'];
                } ?>
                <tr>
                    <td colspan="2" align="right"><b>Total</b></td>
                    <?php for ($s=1; $s <=4 ; $s++) 
                    { 
                        $semtot=0;
                        $name="sem".$s;
                        foreach ($streamdata as $value) 
                        {
                            $semtot=$semtot+$value[$name];
                        }
                    ?>
                    <td style="text-align: center;"><?= $semtot;?></td>
                    <?php } ?>
                    <td><?= $total_credit; ?></td>
                    <td><?= $total_aicte_norms; ?></td>
                </tr>
            </tbody>
        </table>
        <?php }?>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group pull-right">
            
            <?= Html::a("Back", Url::toRoute(['credit-distribution-sem/index']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
    

</div>
