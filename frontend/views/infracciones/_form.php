<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\datecontrol\DateControl;

use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;

use yii\bootstrap\Modal;
use kartik\icons\Icon;

use frontend\models\Vehiculos;
use frontend\models\Personas;
use frontend\models\InfracConceptos;
use frontend\models\Infracciones;


/* @var $this yii\web\View */
/* @var $model frontend\models\Infracciones */
/* @var $form yii\widgets\ActiveForm */


$this->registerJs('
$(document).ready(function() {
    $("#infracciones-id_uf").on("change", function (e) {
			var seleccion=$("#infracciones-id_uf").val(); 
			if (seleccion) {
				$.ajax({
					type   : "POST",cache  : false,
					url    : "/uf/titularidad?id_uf=" + seleccion,
					success: function(r) {
							$("#divtitulares").html(r);  
						}  
					});
			}	
	});
});
');
$this->registerJs('$("#infracciones-nro_acta").focus()', yii\web\View::POS_READY);

$this->registerCss('
.panel-heading {
  padding: 0px 5px;
  border-bottom: 1px solid transparent;
  border-top-left-radius: 2px;
  border-top-right-radius: 2px;
}
.table-condensed > thead > tr > th,
.table-condensed > tbody > tr > th,
.table-condensed > tfoot > tr > th,
.table-condensed > thead > tr > td,
.table-condensed > tbody > tr > td,
.table-condensed > tfoot > tr > td {
  padding: 1px;
}
');

?>
<div class='container'>
	
	<div class='row'>

		<div class="col-md-7">

			<div class="infracciones-form">

				<?php $form = ActiveForm::begin(						
									// agregado a mano para hacer uploads
									['options' => ['enctype' => 'multipart/form-data']] ); ?>
				
				<?= $form->field($model, 'nro_acta')->textInput(['maxlength' => true]) ?>    
				
				<?= $form->field($model, 'hora')->widget(DateControl::classname(), [
					'type'=>DateControl::FORMAT_DATETIME,
					'displayFormat'=>'php:d/m/Y H:i'
				]) ?>    

				<?= $form->field($model, 'id_uf')->textInput() ?>
				
				<div id="divtitulares"></div>    

			   
				<?php
					// -------------------Selector de vehiculos sin botón de alta ----------------------------------------
					$vehiculoDesc=empty($model->id_vehiculo)?'':Vehiculos::formateaVehiculoSelect2($model->id_vehiculo);
					
					$vehiculosUrl=Yii::$app->urlManager->createUrl(['vehiculos/create-ajax']);
					$vehiculosAddon = [
						'prepend'=>[
							'content'=>Icon::show('car',['title'=>'Buscar Vehiculos'],Icon::FA)
						],						
					];
					echo $form->field($model, 'id_vehiculo')->label('Vehiculo')->widget(Select2::classname(), [
						'initValueText' => $vehiculoDesc, 
						'options' => [
										'id'=>'selectorVehiculos',
										'placeholder' => 'Buscar por patente o marca/modelo',
										'title'=>'Buscar vehiculos',											
									 ],
						'addon'=>$vehiculosAddon,
						'pluginOptions' => [
							'allowClear' => true,
							'minimumInputLength' => 2,
							'ajax' => [
								'url' => \yii\helpers\Url::to(['vehiculos/vehiculoslist']),
								'dataType' => 'json',
								'data' => new JsExpression('function(params) { return {q:params.term}; }')
							],
							'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
							'templateResult' => new JsExpression('function(idvehiculo) { return idvehiculo.text; }'),
							'templateSelection' => new JsExpression('function (idvehiculo) { return idvehiculo.text; }'),
						],

					]);  	    
				?>

				<?php
					// -------------------Selector de personas c/botón de alta ----------------------------------------
					$personaDesc=empty($model->id_persona)?'':Personas::formateaPersonaSelect2($model->id_persona,false);

					$personasUrl=Yii::$app->urlManager->createUrl(['personas/create-ajax','selector'=>'selectorPersonas']);
					$porID=Yii::$app->urlManager->createUrl(['accesos/busca-por-id','selector'=>'selectorPersonas']);
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
					echo $form->field($model, 'id_persona')->label('Infractor')->widget(Select2::classname(), [
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
					]);  		    
				
				?>

				<?= $form->field($model, 'lugar')->textInput(['maxlength' => true]) ?>

				<?php
					if (\Yii::$app->user->can('altaModificarMulta')) {				
						echo $form->field($model, 'id_concepto')->dropDownList(InfracConceptos::getLista());
					} else {
						echo $form->field($model, 'id_concepto')->dropDownList(InfracConceptos::getListaInfrac());
					}
				?>

				<?php
					// -------------------Selector de personas sin botón de alta ----------------------------------------
					$informanteDesc=empty($model->id_informante)?'':Personas::formateaPersonaSelect2($model->id_informante,false);

					$porID=Yii::$app->urlManager->createUrl(['accesos/busca-por-id','selector'=>'selectorInformantes']);
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
					echo $form->field($model, 'id_informante')->label('Informante')->widget(Select2::classname(), [
						'initValueText' => $informanteDesc, 
						'options' => ['id'=>'selectorInformantes',
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
					]);  		    
				
				?>    

				<?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

				<?= $form->field($model, 'notificado')->dropDownList(Infracciones::getSiNo()) ?>

				<?php //echo $form->field($model, 'fecha_verif')->textInput() ?>

				<?php //echo $form->field($model, 'verificado')->textInput() ?>

				<?php //echo $form->field($model, 'multa_unidad')->textInput() ?>

				<?php //echo $form->field($model, 'multa_monto')->textInput() ?>

				<?php echo $form->field($model, 'multa_pers_cant')->textInput()->hint('Si no hay personas involucradas en la infracción ingrese 0 (cero)') ?>

				<?php //echo $form->field($model, 'multa_pers_monto')->textInput() ?>

				<?php //echo $form->field($model, 'multa_pers_total')->textInput() ?>

				<?php //echo $form->field($model, 'multa_total')->textInput() ?>

				<?php //echo $form->field($model, 'foto')->textInput(['maxlength' => true]) ?>
				<?php
				if ($model->isNewRecord) echo $form->field($model, 'foto')->fileInput() ;
				if (!$model->isNewRecord) echo $form->field($model, 'foto')
												->hint($model->foto)
												->fileInput() ;
				?>    

				<div class="form-group">
					<?= Html::submitButton('Aceptar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>


				<?php 
					ActiveForm::end(); 
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
		</div>	

		<div class="col-md-5">

				<?php
					$sinImg=Yii::$app->urlManager->createUrl('images/sinmulta.jpg');
					if (!empty($model->foto)) {
						$imgFile=Yii::$app->urlManager->createUrl('images/multas/'.$model->foto);
						echo Html::img($imgFile,['class'=>'img-thumbnail pull-right','onerror'=>"this.src='$sinImg'"]);
					} else {
						echo Html::img($sinImg, ['class'=>'img-thumbnail pull-right']);
					}
				?>

		</div>	
	</div>
</div>					
