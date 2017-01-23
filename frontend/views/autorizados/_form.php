<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;

use yii\bootstrap\Modal;

use frontend\models\Personas;
use frontend\models\Autorizantes;

use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $model frontend\models\Autorizados */
/* @var $form yii\widgets\ActiveForm */

//$this->registerJs('$("#selectorPersonasH").focus()', yii\web\View::POS_READY);
$this->registerJs('
$(document).ready(function() {
    $("#selectorPersonasH").select2("open");
})');
?>

<div class="autorizados-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    //= $form->field($model, 'id_persona')->textInput() 

		// -------------------Selector de personas c/botón de alta ----------------------------------------
		$personaDescHasta=empty($model->id_persona)?'':Personas::formateaPersonaSelect2($model->id_persona,false);

		$personasUrl=Yii::$app->urlManager->createUrl(['personas/create-ajax','selector'=>'selectorPersonasH']);
		$porID=Yii::$app->urlManager->createUrl(['accesos/busca-por-id','selector'=>'selectorPersonasH']);
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
		echo $form->field($model, 'id_persona')->label('Persona a autorizar')->widget(Select2::classname(), [
			'initValueText' => $personaDescHasta, 
			'options' => ['id'=>'selectorPersonasH',
						  'placeholder' => 'Buscar por documento o nombre (Indique la persona a autorizar)',
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
    
    ?>

    <?php
    //$form->field($model, 'id_autorizante')->textInput() 

		// -------------------Selector de autorizantes----------------------------------------
		$autorizanteDesc=empty($model->id_persona)?'':Personas::formateaPersonaSelect2($model->id_autorizante,false);
		//$autorizanteDesc='';

		$autorizantesAddon = [
			'prepend'=>[
				'content'=>Icon::show('key',['title'=>'Buscar Autorizantes'],Icon::FA)
			],
		];
		
		echo $form->field($model, 'id_autorizante')->label('Autorizante')->widget(Select2::classname(), [
			'name'=>'autorizanteSelect2',
			'initValueText' => $autorizanteDesc, 
			'options' => [
							'id'=>'selectorAutorizantes',
							'placeholder' => 'Buscar por UF o nombre',
							'title'=>'Buscar autorizantes',												
						 ],
			'addon'=>$autorizantesAddon,
			'pluginOptions' => [
				'allowClear' => true,
				'minimumInputLength' => 1,
				'ajax' => [
					'url' => \yii\helpers\Url::to(['autorizantes/apellidoslist']),
					'dataType' => 'json',
					'data' => new JsExpression('function(params) { return {q:params.term}; }')
				],
				'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
				'templateResult' => new JsExpression('function(idpersona) { return idpersona.text; }'),
				'templateSelection' => new JsExpression('function (idpersona) { return idpersona.text; }'),
			],
						
		]);  	    
    
    
    ?>

    <?php //= $form->field($model, 'id_uf')->textInput() ?>

    <?= $form->field($model, 'fec_desde')->textInput() ?>

    <?= $form->field($model, 'fec_hasta')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

   <?php 
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


</div>
