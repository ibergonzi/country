<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;

use yii\bootstrap\Modal;

use frontend\models\Personas;


/* @var $this yii\web\View */
/* @var $model frontend\models\Autorizantes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="personas-form">

    <?php $form = ActiveForm::begin(); ?>

   
	<?php
		echo $listaCambios;
	
		// -------------------Selector de personas sin botón de alta ----------------------------------------
		$personaDescDesde=empty($model->idPersonaDesde)?'':Personas::formateaPersonaSelect2($model->idPersonaDesde,false);

		$personasUrl=Yii::$app->urlManager->createUrl(['personas/create-ajax']);
		$porID=Yii::$app->urlManager->createUrl(['accesos/busca-por-id']);
		$personasAddon = [
			'prepend'=>[
				'content'=>'<span class="glyphicon glyphicon-user" title="Buscar Personas"></span>',
			],
			'append' => [
				'content'=>Html::a('<span class="glyphicon glyphicon-barcode btn btn-primary"></span>',
						$porID,
						['title' => Yii::t('app', 'Ingresa por ID'),
						 'tabindex'=>-1,										
						 'onclick'=>'$.ajax({
							type     :"POST",
							cache    : false,
							url  : $(this).attr("href"),
							success  : function(response) {
										$("#divporid").html(response);
										$("#modalporid").modal("show");
										$("#idPersonaPorId").focus();
										}
						});
						return false;',
						])										
						,	
				'asButton' => true
			]
		];
		echo $form->field($model, 'idPersonaDesde')->label(false)->widget(Select2::classname(), [
			'initValueText' => $personaDescDesde, 
			'options' => ['id'=>'selectorPersonas2',
						  'placeholder' => 'Buscar por documento o nombre (Indique la persona a reemplazar)',
						  'title'=>'Buscar personas',
						 ],
			'addon'=>$personasAddon,
			'pluginOptions' => [
				'allowClear' => true,
				'minimumInputLength' => 3,
				'ajax' => [
					'url' => \yii\helpers\Url::to(['personas/apellidoslist']),
					'dataType' => 'json',
					'data' => new JsExpression('function(params) { return {q:params.term}; }')
				],
				'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
				'templateResult' => new JsExpression('function(idpersona) { return idpersona.text; }'),
				'templateSelection' => new JsExpression('function (idpersona) { return idpersona.text; }'),
			],
		]);  			
	
	
	
		// -------------------Selector de personas c/botón de alta ----------------------------------------
		$personaDescHasta=empty($model->idPersonaHasta)?'':Personas::formateaPersonaSelect2($model->idPersonaHasta,false);

		$personasUrl=Yii::$app->urlManager->createUrl(['personas/create-ajax']);
		$porID=Yii::$app->urlManager->createUrl(['accesos/busca-por-id']);
		$personasAddon = [
			'prepend'=>[
				'content'=>'<span class="glyphicon glyphicon-user" title="Buscar Personas"></span>',
			],
			'append' => [
				'content'=>Html::a('<span class="glyphicon glyphicon-plus-sign btn btn-primary"></span>',
										$personasUrl,
										['title' => Yii::t('app', 'Nueva Persona'),
										 'tabindex'=>-1,										
										 'onclick'=>'$.ajax({
											type     :"POST",
											cache    : false,
											url  : $(this).attr("href"),
											success  : function(response) {
														$("#divpersonanueva").html(response);
														$("#modalpersonanueva").modal("show");
														$("#personas-apellido").focus();
														}
										});
										return false;',
										]) 
										. 
						Html::a('<span class="glyphicon glyphicon-barcode btn btn-primary"></span>',
						$porID,
						['title' => Yii::t('app', 'Ingresa por ID'),
						 'tabindex'=>-1,										
						 'onclick'=>'$.ajax({
							type     :"POST",
							cache    : false,
							url  : $(this).attr("href"),
							success  : function(response) {
										$("#divporid").html(response);
										$("#modalporid").modal("show");
										$("#idPersonaPorId").focus();
										}
						});
						return false;',
						])										
						,	
				'asButton' => true
			]
		];
		echo $form->field($model, 'idPersonaHasta')->label(false)->widget(Select2::classname(), [
			'initValueText' => $personaDescHasta, 
			'options' => ['id'=>'selectorPersonas',
						  'placeholder' => 'Buscar por documento o nombre (Indique la persona que reemplaza a la anterior)',
						  'title'=>'Buscar personas',
						 ],
			'addon'=>$personasAddon,
			'pluginOptions' => [
				'allowClear' => true,
				'minimumInputLength' => 3,
				'ajax' => [
					'url' => \yii\helpers\Url::to(['personas/apellidoslist']),
					'dataType' => 'json',
					'data' => new JsExpression('function(params) { return {q:params.term}; }')
				],
				'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
				'templateResult' => new JsExpression('function(idpersona) { return idpersona.text; }'),
				'templateSelection' => new JsExpression('function (idpersona) { return idpersona.text; }'),
			],
		]);  		
	
	// modal que se abre cuando se presiona el boton de agregar persona nueva
	Modal::begin(['id'=>'modalpersonanueva',
		'header'=>'<span class="btn-warning">&nbsp;Persona nueva&nbsp;</span>',
		'options'=>['class'=>'nofade'],
		]
		);
		echo '<div id="divpersonanueva"></div>';
	Modal::end();  
	// modal que se abre cuando se busca por ID o codigo de barras
	Modal::begin(['id'=>'modalporid',
		'header'=>'<span class="btn-warning">&nbsp;Busca persona por ID o código de barras&nbsp;</span>',
		'options'=>['class'=>'nofade'],
		//'clientOptions'=>['backdrop'=>'static','keyboard'=>false],		
		]);
		echo '<div id="divporid"></div>';
	Modal::end();  			
	
	?>

    <div class="form-group">
        <?= Html::submitButton('Aceptar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
