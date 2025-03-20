<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Scrutiny */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="scrutiny-form">
    <script>
        // document.addEventListener('contextmenu', event => event.preventDefault());
    </script>
    <div class="box box-primary">
        <div class="box-body">
            <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12 ">
                    <div class="box box-solid box-info col-xs-12 col-lg-12 no-padding">
                        <div class="box-header with-border">
                            <h4 class="box-title"> <i class="fa fa-info-circle"></i> <?php echo 'Personal Details'; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?php echo $form->field($model, 'designation')->widget(
                            Select2::classname(),
                            [
                                'data' => $model->designationData,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'class' => 'form-control',
                                ],
                            ]
                        );
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?php echo $form->field($model, 'department')->widget(
                            Select2::classname(),
                            [
                                'data' => $model->departmentData,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'class' => 'form-control',
                                ],
                            ]
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true, 'readonly' => !$model->isNewRecord, 'oncontextmenu'=> 'return false;']) ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly' => !$model->isNewRecord ,  'oncontextmenu'=> 'return false;']) ?>
                    </div>
                </div>
            </div>
            <!-- bank details -->
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12 ">
                    <div class="box box-solid box-info col-xs-12 col-lg-12 no-padding">
                        <div class="box-header with-border">
                            <h4 class="box-title"> <i class="fa fa-info-circle"></i> <?php echo 'Account Details'; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?= $form->field($model, 'bank_accno')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?= $form->field($model, 'bank_ifsc')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <?= $form->field($model, 'bank_branch')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-sm-9 col-lg-9 form-group">
                    </div>
                    <div class="btn-group col-lg-3 col-sm-3 float-right" role="group" aria-label="Actions to be Perform">
                        <?= Html::SubmitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        <?= Html::a("Reset", Url::toRoute(['scrutiny/create']), ['onClick' => "spinner();", 'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>