<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login' || Yii::$app->controller->action->id === 'signup') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?php echo Yii::getAlias('@web').'/images/favicon.ico'; ?>" />
    <link rel=icon type="image/ico" href="<?php echo Yii::getAlias('@web').'/images/favicon.ico'; ?>" />
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body onunload="spinneroff();" class="hold-transition <?= \dmstr\helpers\AdminLteHelper::skinClass() ?> layout-top-nav">
         <div id="waiting" style="position: absolute;top:220px;left:532px;width:40px;height:40px;z-index: 999;visibility: hidden;">
            <img alt="Waiting for response..." src="<?php echo Yii::getAlias('@web').'/images/loading.gif'; ?>" height="100" width="100" />
        </div>


    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'top_nav.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>

    <a href=""> 
        <div id="stop" class="scrollTop">
            <span>
                <i class="fa fa-hand-o-up" aria-hidden="true"></i>
            </span>
        </div>
    </a>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
