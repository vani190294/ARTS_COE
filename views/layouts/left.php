<?php
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo Yii::getAlias('@web').'/images/admin.png'; ?>" class="img-circle" alt="User Image"/>
            </div>
            <div style="padding-top: 6.5%" class="pull-left info">
                <p><?php echo strtoupper(Yii::$app->user->identity->username); ?></p>
            </div>
        </div>
        <!-- /.search form -->

       <?php 
           // echo Configs::USER_RIGHTS; exit;
       ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'nav navbar-nav', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    ['label' => 'Home', 'icon' => 'home', 'url' => ['/site/index']],
                    ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME), 'icon' => 'cogs', 'url' => ['/configuration/index'],'visible' => Yii::$app->user->can('/configuration/index'),],
                    ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_USER), 'icon' => 'user-secret', 'url' => ['/rbac/default/index'],'visible' => Yii::$app->user->can('/rbac/default/index'),],
                    ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE), 'icon' => 'home', 'url' => ['/degree/index']],
                    ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME), 'icon' => 'home', 'url' => ['/programme/index']],
                    //['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                  /*  [
                        'label' => 'Same tools',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],*/
            ]
        ]) ?>

    </section>

</aside>
