<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
//use common\widgets\Alert;

use kartik\popover\PopoverX;
use common\models\User;


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
		/* Opcion de salir por defecto de yii2
        $menuItems[] = [
            'label' => 'Salir (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
        */
        $u=User::findOne(Yii::$app->user->getId());
        
		if (!empty($u->foto)) {
			$contenido=Html::img(Yii::$app->urlManager->createUrl('images/usuarios/'.$u->foto),
				['class'=>'img-thumbnail']);
		}
		else
        {
			$contenido=Html::img(Yii::$app->urlManager->createUrl('images/sinfoto.png'),['class'=>'img-thumbnail']);
		}

        $headerPopover='<p><i>Usuario: '. Yii::$app->user->identity->username.'</i></p>'.
			'<p><i>'. User::getRol(Yii::$app->user->getId())->description . '</i></p>';
		$userPopover = '<li class="dropdown"><div class="navbar-form">' . PopoverX::widget([
			'header' => $headerPopover,
			'placement' => PopoverX::ALIGN_BOTTOM_RIGHT,
			'size' => 'md',
			'content' => $contenido,
			'footer' => Html::a('Cerrar sesión &raquo;',['/site/logout'], 
							['data-method' => 'post','class'=>'btn btn-sm btn-success']),
			'toggleButton' => [
				'label' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-lock',]),
				'class'=>'btn btn-sm btn-default'
			]
		]) . '</div></li>';        
        $menuItems[] = $userPopover;
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        // para que muestre el popover
        'encodeLabels' => false
    ]);
    NavBar::end();
    ?>

    <div class="container" >
        <?php 
			
			echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			])
			 
        ?>		

        <?php //echo Alert::widget() ?>
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
<?php
if (!isset($this->params['focus']) ) {
    $this->registerJs('$(":input:not(:button,:hidden):enabled:visible:first").focus();',yii\web\View::POS_READY);
}
?>
<?php $this->endPage() ?>
