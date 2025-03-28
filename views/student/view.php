<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Student */
$visible = Yii::$app->user->can("/student/view") || Yii::$app->user->can("/student/update") ? true : false;
$visible_del = Yii::$app->user->can("/student/delete")  ? true : false; 
$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Name : ".$model->name;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>

<div style="padding-left: 1%;" class="student-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if($visible){ ?>
        <?= Html::a('Update', ['update', 'id' => $model->coe_student_id,], ['class' => 'btn btn-primary']) ?>
        <?php }  if($visible_del){ ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_student_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php } ?>
    </p>
</div>

<section class="content">

<div class="row">
        <div class="col-md-9">
          <div class="box box-solid">
           
            <!-- /.box-header -->
            <div class="box-body">
              <div class="box-group" id="accordion">                
                <div class="panel  box box-info">
                  <div class="box-header  with-border" role="tab" >
                    <div class="row">
                        <div class="col-md-10">
                             <h4 class="padding box-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                General Information
                              </a>                              
                            </h4>
                        </div>
                        
                    </div>
                    
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                      <table class="table table-responsive-xl table-responsive table-striped">
                        <tr>
                            <th><?= $model->getAttributeLabel('name') ?></th>
                            <td><?= Html::encode($model->name) ?></td>
                            <th><?= $model->getAttributeLabel('register_number') ?></th>
                            <td><?= Html::encode($model->register_number) ?></td>
                        </tr>
                        <tr>
                            <th><?= $model->getAttributeLabel('gender') ?></th>
                            <td><?= Html::encode($model->gender) ?></td>
                            <th><?= $model->getAttributeLabel('dob') ?></th>
                            <td><?= Html::encode(DATE('d-m-Y',strtotime($model->dob))) ?></td>
                        </tr>
                        <tr>
                            <th><?= $model->getAttributeLabel('religion') ?></th>
                            <td><?= Html::encode($model->religion) ?></td>
                            <th><?= $model->getAttributeLabel('nationality') ?></th>
                            <td><?= Html::encode($model->nationality) ?></td>
                        </tr>
                        <tr>
                            <th><?= $model->getAttributeLabel('caste') ?></th>
                            <td><?= Html::encode($model->caste) ?></td>
                            <th><?= $model->getAttributeLabel('sub_caste') ?></th>
                            <td><?= Html::encode($model->sub_caste) ?></td>
                        </tr>
                        <tr>
                            <th><?= $model->getAttributeLabel('bloodgroup') ?></th>
                            <td><?= Html::encode($model->bloodgroup) ?></td>
                            <th><?= $model->getAttributeLabel('email_id') ?></th>
                            <td><?= Html::encode($model->email_id) ?></td>
                        </tr>
                           <tr>
                            <th><?= $model->getAttributeLabel('admission_year') ?></th>
                            <td><?= Html::encode($model->admission_year) ?></td>
                            <th><?= $model->getAttributeLabel('admission_date') ?></th>
                            <td><?= Html::encode(DATE('d-m-Y',strtotime($model->admission_date))) ?></td>
                        </tr>
                          <tr>
                            <th><?= $model->getAttributeLabel('abc_number_id') ?></th>
                            <td><?= Html::encode($model->abc_number_id) ?></td>
                           
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-10">
                             <h4 class="padding box-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                Academic Information
                              </a>                            
                            </h4>
                        </div>
                       
                    </div>

                  </div>

                 

                  <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="box-body">
                      <table class="table table-responsive-xl table-responsive table-striped">
                        <tr>
                            <th><?= $stuMapping->getAttributeLabel('programme_name') ?></th>
                            <td><?= Html::encode($stuMapping->courseBatchMapping->coeProgramme->programme_name) ?></td>
                            <th><?= $stuMapping->getAttributeLabel('admission_category_type_id') ?></th>
                            <td><?= Html::encode($stuMapping->admissionCategoryType->category_type) ?></td>
                        </tr>
                        <tr>
                            <th><?= $stuMapping->getAttributeLabel('batch_name') ?></th>
                            <td><?= Html::encode($stuMapping->courseBatchMapping->coeBatch->batch_name) ?></td>
                            <th><?= $stuMapping->getAttributeLabel('degree_name') ?></th>
                            <td><?= Html::encode($stuMapping->courseBatchMapping->coeDegree->degree_name) ?></td>
                        </tr>
                        <tr>
                            <th><?= $stuMapping->getAttributeLabel('section_name') ?></th>
                            <td><?= Html::encode($stuMapping->section_name) ?></td>
                            <th><?= $stuMapping->getAttributeLabel('status_category_type_id') ?></th>
                            <td><?= Html::encode($stuMapping->statusCategoryType->category_type) ?></td>
                        </tr>            
                    </table>
                    </div>
                  </div>
                </div>
                <div class="panel box box-success">
                  <div class="box-header with-border">

                    <div class="row">
                        <div class="col-md-10">
                             <h4 class="padding box-title">
                               <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                Guardian Information
                              </a>                          
                            </h4>
                        </div>
                       
                    </div>

                  </div>
                  <div id="collapseThree" class="panel-collapse collapse">
                    <div class="box-body">
                      <table class="table table-responsive-xl table-responsive table-striped">
                        <tr>
                            <th><?= $guardian->getAttributeLabel('guardian_name') ?></th>
                            <td><?= Html::encode($guardian->guardian_name) ?></td>
                            <th><?= $guardian->getAttributeLabel('guardian_relation') ?></th>
                            <td><?= Html::encode($guardian->guardian_relation) ?></td>
                        </tr>
                        <tr>
                            <th><?= $guardian->getAttributeLabel('guardian_mobile_no') ?></th>
                            <td><?= Html::encode($guardian->guardian_mobile_no) ?></td>
                            <th><?= $guardian->getAttributeLabel('guardian_address') ?></th>
                            <td><?= Html::encode($guardian->guardian_address) ?></td>
                        </tr>
                        <tr>
                            <th><?= $guardian->getAttributeLabel('guardian_email') ?></th>
                            <td><?= Html::encode($guardian->guardian_email) ?></td>
                            <th><?= $guardian->getAttributeLabel('guardian_occupation') ?></th>
                            <td><?= Html::encode($guardian->guardian_occupation) ?></td>
                      
                  </tr>
                
<!---------
                        <tr>
                            <th><?= $guardian->getAttributeLabel('guardian_income') ?></th>
                            <td><?= Html::encode($guardian->guardian_income) ?></td>                
                        </tr>-------------------->
                      </table>
                    </div>
                  </div>
                </div>
                <div class="panel box box-warning">
                  <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-10">
                             <h4 class="padding box-title">
                               <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                Address Information
                              </a>                         
                            </h4>
                        </div>
                        
                    </div>
                    

                  </div>
                  <?php 
                  if(!empty($stuAddress))
                  {
                  ?>
                  <div id="collapseFour" class="panel-collapse collapse">
                    <div class="box-body">
                      <table class="table table-responsive-xl table-responsive table-striped">
                          <tr>
                            <th><?= $stuAddress->getAttributeLabel('current_city') ?></th>
                            <td><?= Html::encode($stuAddress->current_city) ?></td>
                            <th><?= $stuAddress->getAttributeLabel('current_address') ?></th>
                            <td><?= Html::encode($stuAddress->current_address) ?></td>
                        </tr>
                        <tr>
                            <th><?= $stuAddress->getAttributeLabel('current_state') ?></th>
                            <td><?= Html::encode($stuAddress->current_state) ?></td>
                            <th><?= $stuAddress->getAttributeLabel('current_country') ?></th>
                            <td><?= Html::encode($stuAddress->current_country) ?></td>
                        </tr>
                        <tr>
                            <th><?= $stuAddress->getAttributeLabel('current_pincode') ?></th>
                            <td><?= Html::encode($stuAddress->current_pincode) ?></td>
                            <th><?= $stuAddress->getAttributeLabel('permanant_city') ?></th>
                            <td><?= Html::encode($stuAddress->permanant_city) ?></td>
                        </tr>
                        <tr>
                            <th><?= $stuAddress->getAttributeLabel('permanant_address') ?></th>
                            <td><?= Html::encode($stuAddress->permanant_address) ?></td>
                            <th><?= $stuAddress->getAttributeLabel('permanant_state') ?></th>
                            <td><?= Html::encode($stuAddress->permanant_state) ?></td>
                        </tr>
                        <tr>
                            <th><?= $stuAddress->getAttributeLabel('permanant_country') ?></th>
                            <td><?= Html::encode($stuAddress->permanant_country) ?></td>
                            <th><?= $stuAddress->getAttributeLabel('permanant_pincode') ?></th>
                            <td><?= Html::encode($stuAddress->permanant_pincode) ?></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    
    <div class="col-lg-3 table-responsive  no-padding" style="margin-bottom:15px;text-align: center; ">

        <div class="col-md-12 padding text-center">
            

                <?php 
                    $supported_extensions = ConfigUtilities::ValidFileExtension();
                    $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
                    $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";
                    $files = glob($absolute_dire.$model->register_number.".*"); // Will find 2.JPG, 2.php, 2.gif
                    // Process through each file in the list
                    // and output its extension

                    if (count($files) > 0)
                    foreach ($files as $file)
                    {
                    $info = pathinfo($file);
                    $extension = ".".$info["extension"];
                    }
                    else
                    {
                    $extension="";
                    }
                    $photo_extension = !empty($supported_extensions)?ConfigUtilities::match($supported_extensions,$model->register_number.$extension):"";
                    $stu_photo = $photo_extension!="" ? $stu_directory.$model->register_number.".".$photo_extension:$stu_directory."stu_sample.jpg";
                ?>
                <div style="text-align: center;" class="col-md-12 box box-body box-header">
                    <?php
                        echo "<img style='margin-left: 18%;' class='img-responsive' width=200 height=180 src=".$stu_photo." alt='".$stu_photo." Photo' >";
                    ?>
                </div>
            
       
        </div>
        <table class="table table-striped">
            <tr>
                <th><?= $model->getAttributeLabel('name') ?></th>
                <td><?= Html::encode($model->name) ?></td>
            </tr>
            <tr>
                <th><?= $model->getAttributeLabel('register_number') ?></th>
                <td><?= Html::encode($model->register_number) ?></td>
            </tr>
            <tr>
                <th><?= $stuMapping->getAttributeLabel('batch_name') ?></th>
                <td><?= Html::encode($stuMapping->courseBatchMapping->coeBatch->batch_name) ?></td>
            </tr>
            
            <tr>
                <th><?= $stuMapping->getAttributeLabel('programme_name') ?></th>
                <td><?= Html::encode($stuMapping->courseBatchMapping->coeProgramme->programme_name) ?></td>
            </tr>           
            
            <tr>
                <th><?= $model->getAttributeLabel('mobile_no') ?></th>
                <td><?= Html::encode($model->mobile_no) ?></td>
            </tr>
            <tr>
                <th><?php echo 'Status'; ?></th>
                <td>
                    <?php if($stuMapping->statusCategoryType->category_type=='Detain' || $stuMapping->statusCategoryType->category_type=='Detain/Debar' || $stuMapping->statusCategoryType->category_type=='Discontinued') : ?>
                    <span class="label label-danger"><?php echo  'In-Active'; ?></span>
                    <?php else : ?>
                    <span class="label label-success"><?php echo 'Active'; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    

     </div> <!---End Row Div -->
</section>






    


