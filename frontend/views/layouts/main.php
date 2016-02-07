<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Barrio Miraflores',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems=[];
    if (Yii::$app->user->isGuest) {
		$menuItems[] = ['label' => 'Home', 'url' => ['/site/index']];		
		$menuItems[] = ['label' => 'Acerca de', 'url' => ['/site/about']];
		$menuItems[] = ['label' => 'Contacto', 'url' => ['/site/contact']];
        $menuItems[] = ['label' => 'Registrarse', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Ingresar', 'url' => ['/site/login']];
    } else {
		if (\Yii::$app->user->can('accederEntradas')) {
			if (\Yii::$app->session->get('porton')) {	
				$menuItems[] = ['label' => 'Entradas', 'url' => ['/entradas']];
			}
		}
		
		$menuItems[] = ['label' => 'Personas', 'url' => ['/persona']];	
			
		if (\Yii::$app->user->can('accederPorton')) {
			if (\Yii::$app->session->get('porton')) {	
				$menuItems[] = ['label' => 'Portón '.\Yii::$app->session->get('porton'), 
															'url' => ['/portones/elegir']];					
			} else {
				$menuItems[] = ['label' => 'Elegir portón', 'url' => ['/portones/elegir']];
			}
		}
		
        $menuItems[] = [
            'label' => 'Salir (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container" >
        <?php 
			
			echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			])
			 
        ?>		

        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Barrio Miraflores <?= date('Y') ?></p>


    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
