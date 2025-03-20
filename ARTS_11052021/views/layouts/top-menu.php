<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

$menuItems = [
    [
        'label' => Yii::t('app', 'Home'),
        'url' => ['/site/index']
    ],
    [
        'label' => Yii::t('app', 'Logout') . '(' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post']
    ]
];
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
$menuItemsMain = [
    [
        'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', 'Blog'),
        'url' => ['#'],
        'active' => false,
        'items' => [
            [
                'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', 'Catalog'),
                'url' => ['/blog/blog-catalog'],

            ],
            [
                'label' => '<i class="fa fa-user-md"></i> ' . Yii::t('app', 'Post'),
                'url' => ['/blog/blog-post'],
            ],
            [
                'label' => '<i class="fa fa-user-md"></i> ' . Yii::t('app', 'Comment'),
                'url' => ['/blog/blog-comment'],
            ],
            [
                'label' => '<i class="fa fa-user-md"></i> ' . Yii::t('app', 'Tag'),
                'url' => ['/blog/blog-tag'],
            ],
        ],
        'visible' => Yii::$app->user->can('readPost'),
    ],
    [
        'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', 'Cms'),
        'url' => ['#'],
        'active' => false,
        'items' => [
            [
                'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', 'Catalog'),
                'url' => ['/blog/default/blog-catalog'],
            ],
            [
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
            [
                'label' => '<i class="fa fa-user-md"></i> ' . Yii::t('app', 'Post'),
                'url' => ['/blog/default/blog-post'],
            ],
            [
                'label' => '<i class="fa fa-user-md"></i> ' . Yii::t('app', 'Comment'),
                'url' => ['/blog/default/blog-comment'],
            ],
            [
                'label' => '<i class="fa fa-user-md"></i> ' . Yii::t('app', 'Tag'),
                'url' => ['/blog/default/blog-tag'],
            ],
        ],
    ],
    [
        'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', 'System'),
        'url' => ['#'],
        'active' => false,
        //'visible' => Yii::$app->user->can('haha'),
        'items' => [
            [
                'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', 'User'),
                'url' => ['/user'],
            ],
            [
                'label' => '<i class="fa fa-lock"></i> ' . Yii::t('app', 'Role'),
                'url' => ['/role'],
            ],
        ],
    ],
];
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    'items' => $menuItemsMain,
    'encodeLabels' => false,
]);