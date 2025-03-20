<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

   <?= Html::a('<span class="logo-lg"> Welcome to ' . Yii::$app->params['app_name']. ' </span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <!-- <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a> -->

        
        
        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

              

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo Yii::getAlias('@web').'/images/admin.png'; ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?php echo Yii::$app->user->identity->username ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?php echo Yii::getAlias('@web').'/images/admin.png'; ?>" class="img-circle"
                                 alt="User Image"/>
                            <p class="admin_name_display">
                                <?php //echo Yii::$app->user->identity->username ?>
                                <small>Last Login : <?php echo "Display Login Details"; ?> </small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Course</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Exam</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Marks</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">

                                <?php /*Html::a(
                                    'Profile',
                                    ['/site/user-dashboard'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                )*/ ?>

                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
