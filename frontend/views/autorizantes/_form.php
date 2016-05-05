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

<div class="autorizantes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_uf')->textInput() ?>

   
    
	<?php
		// -------------------Selector de personas c/botón de alta ----------------------------------------
		$personaDesc=empty($model->id_persona)?'':Personas::formateaPersonaSelect2($model->id_persona,false);
		//$personaDesc='';

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
		echo $form->field($model, 'id_persona')->label(false)->widget(Select2::classname(), [
			'initValueText' => $personaDesc, 
			'options' => ['id'=>'selectorPersonas',
						  'placeholder' => 'Buscar por documento o nombre',
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
			'pluginEvents' => [
				'change' => 'function(e) { 
					var seleccion=$("#selectorPersonas:first").val(); 
					if (seleccion) {
						$.ajax({
							type   : "POST",cache  : false,
							url    : "add-lista?grupo=egrpersonas&id=" + seleccion,
							success: function(r) {
									$("#divlistapersonas").html(r["egrpersonas"]);
									$("#selectorPersonas").select2("val","");
									$.ajax({
										type   : "POST", cache  : false,
										url    : "busca-vehiculos?grupo=egrpersonas&id_persona=" + seleccion,
										success: function(r) {
												if (r != "notFound") {
													$("#divvehiculos_persona").html(r);
													$("#modalvehiculos_persona").modal("show");
													$("#listboxVehiculos").focus();
												}
											}
									});	
																
								}
						});						
					}			
				}',
				'select2:unselecting'=>'function(e) {
					var seleccion=$("#selectorPersonas:first").val(); 
					if (seleccion) {
						$.ajax({
								type     : "POST",
								cache    : false,
								url      : "drop-lista?grupo=personas&id=" + seleccion,
								success  : function(r) {
											$("#divlistapersonas").html(r["personas"]);														
											}
						});						
					}			
				}'
			]							
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
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
