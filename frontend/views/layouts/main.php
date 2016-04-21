<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
//use kartik\widgets\Alert;

use kartik\popover\PopoverX;
use common\models\User;
use frontend\models\CortesEnergia;

use kartik\icons\Icon;
Icon::map($this, Icon::FA);

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
		//$menuItems[] = ['label' => 'Inicio', 'url' => ['/site/index']];		
		//$menuItems[] = ['label' => 'Acerca de', 'url' => ['/site/about']];
		$menuItems[] = ['label' => 'Contacto', 'url' => ['/site/contact']];
        $menuItems[] = ['label' => 'Registrarse', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Ingresar', 'url' => ['/site/login']];
    } else {
		// Generador
		$corteActivo=CortesEnergia::corteActivo();
		if ($corteActivo) { 
			$menuItems[]=['label' => Html::tag('i', '', ['class' => 'fa fa-lg fa-cog fa-spin',]), 
						'url' => ['/cortes-energia/start-stop'], //'visible'=>\Yii::$app->user->can('accederPorton'),
						'options'=>['title'=>'El corte comenzó el '.Yii::$app->formatter->asDatetime($corteActivo->hora_desde)]];
			$itemCorte=	['label' => '<span class="fa fa-lg fa-cog fa-spin"></span>&nbsp; Terminar Corte', 
						'url' => ['/cortes-energia/start-stop'], 'visible'=>\Yii::$app->user->can('accederCortesStartStop')];			
		} else {		
			$itemCorte=	['label' => '<span class="fa fa-cog"></span>&nbsp;Empezar Corte', 
						'url' => ['/cortes-energia/start-stop'], 'visible'=>\Yii::$app->user->can('accederCortesStartStop')];					
		}	 		
		$menuItems[] = ['label' => 'Accesos', 
						'items' => [
							['label' => '<span class="glyphicon glyphicon-arrow-right"></span>&nbsp;Ingresos', 
								'url' => ['/accesos/ingreso'], 
								'visible'=>\Yii::$app->user->can('accederIngreso')],
							['label' => '<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Egresos', 
								'url' => ['/accesos/egreso'], 
								'visible'=>\Yii::$app->user->can('accederEgreso')],
							['label' => '<span class="fa fa-users"></span>&nbsp;Egreso Grupal', 
								'url' => ['/accesos/egreso-grupal'], 
								'visible'=>\Yii::$app->user->can('accederEgresoGrupal')],
							['label' => '<span class="glyphicon glyphicon-book"></span>&nbsp;Libro', 
								'url' => ['/libro/index'], 
								'visible'=>\Yii::$app->user->can('accederLibro')],
							'<li class="divider"></li>',
							'<li class="dropdown-header">Consultas</li>',
							['label' => 'Accesos', 
								'url' => ['/accesos/index'], 
								'visible'=>\Yii::$app->user->can('accederConsAccesos')],
							['label' => 'Personas adentro', 
								'url' => ['/accesos/cons-dentro'], 
								'visible'=>\Yii::$app->user->can('accederConsDentro')],
							['label' => 'Estadisticas', 
								'url' => ['/accesos/stats'], 
								'visible'=>\Yii::$app->user->can('accederStatsAccesos')],									
							['label' => 'Lista de Personas', 
								'url' => ['/personas/index'], 
								'visible'=>\Yii::$app->user->can('accederListaPersonas')],
							['label' => 'Lista de Vehiculos', 
								'url' => ['/vehiculos/index'], 
								'visible'=>\Yii::$app->user->can('accederListaVehiculos')],	
							['label' => 'Agenda', 
								'url' => ['/agenda/index'], 
								'visible'=>\Yii::$app->user->can('accederAgenda')],																
			] // fin items;
		]; // fin menuItems[]
		$menuItems[] = ['label' => 'Energia', 
						'items' => [
							$itemCorte,
							'<li class="divider"></li>',
							'<li class="dropdown-header">Consultas</li>',							
							['label' => 'Cortes de energía', 
								'url' => ['/cortes-energia/index'], 
								'visible'=>\Yii::$app->user->can('accederConsCortes')],
								
								
			] // fin items;
		]; // fin menuItems[]		

		$menuItems[] = ['label' => 'Unidades', 
						'items' => [
							['label' => 'Lista de U.F.', 
								'url' => ['/uf/index'], 
								'visible'=>\Yii::$app->user->can('accederListaUf')],		
							'<li class="divider"></li>',
							'<li class="dropdown-header">Consultas</li>',							
							['label' => 'Expensas', 
								'url' => ['/titularidad-vista/index'], 
								'visible'=>\Yii::$app->user->can('accederListaUf')],
								
								
			] // fin items;
		]; // fin menuItems[]	

		$menuItems[] = ['label' => 'Intendencia', 
						'items' => [
							['label' => 'Usuarios', 'url' => ['/user/index'], 'visible'=>\Yii::$app->user->can('accederUser')],
							['label' => 'Carnets', 'url' => ['/carnets/index'], 'visible'=>\Yii::$app->user->can('accederCarnets')],
							
			] // fin items;
		]; // fin menuItems[]		
		if (\Yii::$app->session->get('porton')) {	
			$menuItems[] = ['label' => 'Portón '.\Yii::$app->session->get('porton'), 
														'url' => ['/portones/elegir'], 'visible'=>\Yii::$app->user->can('accederPorton')];					
		} else {
			$menuItems[] = ['label' => 'Elegir portón', 'url' => ['/portones/elegir'], 'visible'=>\Yii::$app->user->can('accederPorton')];
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
							['data-method' => 'post','class'=>'btn btn-sm btn-primary']),
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

        <?php 
        // el alert se deberia aplicar en todas las paginas, excepto las que definen el parametro noAlerts, 
        // en principio son las paginas de ingresos y egresos
        if (!isset($this->params['noAlerts'])) {
			echo Alert::widget();
		} 
        ?>
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
/*
if (!isset($this->params['focus']) ) {
    $this->registerJs('$(":input:not(:button,:hidden):enabled:visible:first").focus();',yii\web\View::POS_READY);
}
*/
?>
<?php $this->endPage() ?>
