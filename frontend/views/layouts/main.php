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

use app\assets\BarreraAsset;

use kartik\icons\Icon;
Icon::map($this, Icon::FA);

AppAsset::register($this);

// Solo para porteros logueados, cuando esté funcionando la llave, 
// si es llave de panico en vez de alertify.log(response) se deberia usar alertify.error(response)
if (!Yii::$app->user->isGuest) {
	$rolPortero=(User::getRol(Yii::$app->user->getId())->name == 'portero')?true:false;
	if ($rolPortero) {
		BarreraAsset::register($this);		
		
		$urlCtrlBarrera=Yii::$app->urlManager->createUrl(['accesos/ctrl-barrera']);
		// cada 5 segundos se llama a accesos/ctrl-barrera y el cartel queda visible 7 segundos
		$barrera = <<< JS
$(document).ready(function() {
	setInterval(function(){ $.ajax({type  : "POST",
									cache : false,
									url   : "$urlCtrlBarrera",
									success: function(response) {
												if (response!="") {
													alertify.set({ delay: 7000 });
													alertify.log(response);
												}
											}
									}); 
						  }, 5000
				);
});
JS;
		// activar cuando se implemente lo de las barreras: $this->registerJs($barrera);
	}
}


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
								'visible'=>\Yii::$app->user->can('accederIngreso')
							],
							['label' => '<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Egresos', 
								'url' => ['/accesos/egreso'], 
								'visible'=>\Yii::$app->user->can('accederEgreso')
							],
							['label' => '<span class="fa fa-users"></span>&nbsp;Egreso Grupal', 
								'url' => ['/accesos/egreso-grupal'], 
								'visible'=>\Yii::$app->user->can('accederEgresoGrupal')
							],
							['label' => '<span class="glyphicon glyphicon-book"></span>&nbsp;Libro', 
								'url' => ['/libro/index'], 
								'visible'=>\Yii::$app->user->can('accederLibro')
							],
							'<li class="divider"></li>',
							'<li class="dropdown-header">Consultas</li>',
							['label' => 'Accesos', 
								'url' => ['/accesos/index'], 
								'visible'=>\Yii::$app->user->can('accederConsAccesos')
							],
							['label' => 'Personas adentro', 
								'url' => ['/accesos/cons-dentro'], 
								'visible'=>\Yii::$app->user->can('accederConsDentro')
							],
							['label' => 'Estadisticas', 
								'url' => ['/accesos/stats'], 
								'visible'=>\Yii::$app->user->can('accederStatsAccesos')
							],									
							['label' => 'Lista de Personas', 
								'url' => ['/personas/index'], 
								'visible'=>\Yii::$app->user->can('accederListaPersonas')
							],
							['label' => 'Lista de Vehiculos', 
								'url' => ['/vehiculos/index'], 
								'visible'=>\Yii::$app->user->can('accederListaVehiculos')
							],	
							['label' => 'Agenda', 
								'url' => ['/agenda/index'], 
								'visible'=>\Yii::$app->user->can('accederAgenda')
							],																
			] // fin items;
		]; // fin menuItems[]
		
		$menuItems[] = ['label' => 'Infracciones', 
						'items' => [
							['label' => 'Infracciones/multas', 
								'url' => ['/infracciones/index'], 
								'visible'=>\Yii::$app->user->can('accederListaInfrac')
							],
							['label' => 'Informe de multas', 
								'url' => ['/infracciones/rendic-fechas'], 
								'visible'=>\Yii::$app->user->can('accederRendicMultas')
							],							
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

		$menuItems[] = ['label' => 'Parámetros', 
						'items' => [
							['label' => 'Autorizantes', 'url' => ['/autorizantes/index'], 
									'visible'=>\Yii::$app->user->can('accederListaAutorizantes')],						
							['label' => 'Usuarios', 'url' => ['/user/index'], 'visible'=>\Yii::$app->user->can('accederUser')],
							['label' => 'Carnets', 'url' => ['/carnets/index'], 'visible'=>\Yii::$app->user->can('accederCarnets')],
							['label' => 'Cambio Personas', 'url' => ['/personas/change'], 'visible'=>\Yii::$app->user->can('cambiarPersona')],
							'<li class="divider"></li>',							
							['label' => 'Infracciones: Conceptos', 'url' => ['/infrac-conceptos/index'], 'visible'=>\Yii::$app->user->can('accederParametros')],
							['label' => 'Infracciones: Unidades', 'url' => ['/infrac-unidades/index'], 'visible'=>\Yii::$app->user->can('accederParametros')],																					
							'<li class="divider"></li>',
							['label' => 'Autoriz.accesos manuales', 'url' => ['/accesos-autmanual/index'], 'visible'=>\Yii::$app->user->can('accederAutManualAccesos')],							
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
        
		$sinImg=Yii::$app->urlManager->createUrl('images/sinfoto.png');				
		if (!empty($u->foto)) {
			$imgFile=Yii::$app->urlManager->createUrl('images/usuarios/'.$u->foto);
			$contenido=Html::img($imgFile,['class'=>'img-thumbnail','onerror'=>"this.src='$sinImg'"]);
		} else {
			$contenido=Html::img($sinImg, ['class'=>'img-thumbnail']);
		}        
        
	
        $headerPopover='<p><i>Usuario: '. Yii::$app->user->identity->username.'</i></p>'.
			'<p><i>'. User::getRol(Yii::$app->user->getId())->description . '</i></p>'.
			'<p><i>IP: '. Yii::$app->request->userIp . '</i></p>';
		$userPopover = '<li class="dropdown"><div class="navbar-form">' . PopoverX::widget([
			'header' => $headerPopover,
			'placement' => PopoverX::ALIGN_BOTTOM,
			'type'=> Popoverx::TYPE_WARNING,
			//'size' => 'md',
			'content' => $contenido,
			'footer' => Html::a('Cerrar sesión &raquo;',['/site/logout'], 
							['data-method' => 'post','class'=>'btn btn-sm btn-warning']),
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
