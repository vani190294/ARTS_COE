<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;




?>
<div class="content-wrapper">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <!-- <h1>
                <?php
                if ($this->title !== null) {
                   // echo \yii\helpers\Html::encode($this->title);
                } else {
                   /* echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );*/
                    //echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1> -->
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<!--footer class="main-footer">
    
    <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="http://www.srikrishnaitech.com/" target="_blank" >SRI KRISHNA I-TECH</a>.</strong> All rights
    reserved.
</footer-->


<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>