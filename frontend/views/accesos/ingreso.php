<?php

use yii\helpers\Html;


use yii\widgets\ActiveForm;
use kartik\builder\TabularForm;


use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;

use yii\bootstrap\Modal;

use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

$this->title = Yii::t('app', 'Ingresos');

?>
<div class="accesos-ingreso">
						
	<div class='container'> 
	
		<div class='row'>

			<div id="col1" class="col-md-5">
				
				    <?php 
				   
						$form = ActiveForm::begin();

						// -------------------Selector de personas c/botón de alta ----------------------------------------
						$personaDesc=$model->isNewRecord?'':Personas::formateaPersonaSelect2($model->idpersona,false);
						$personasUrl=Yii::$app->urlManager->createUrl(['personas/create-ajax']);
						$personasAddon = [
							'append' => [
								'content'=>Html::a('<span 
										class="btn btn-primary">Nueva</span>', 
										$personasUrl,
										['title' => Yii::t('app', 'Nueva Persona'),
										 'onclick'=>'$.ajax({
											type     :"POST",
											cache    : false,
											url  : $(this).attr("href"),
											success  : function(response) {
														console.log(response);
														$("#divpersonanueva").html(response);
														$("#modalpersonanueva").modal("show")
														}
										});return false;',
										]),	
								'asButton' => true
							]
						];
						echo $form->field($model, 'id_persona')->widget(Select2::classname(), [
							'initValueText' => $personaDesc, 
							'options' => ['id'=>'selectorPersonas','placeholder' => '...'],
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
									var seleccion=$("#selectorPersonas:first"); 
									if (seleccion.val()) {
										$.ajax({
												type     : "POST",
												cache    : false,
												url      : "add-lista?grupo=personas&id=" + seleccion.val(),
												success  : function(response) {
															$("#divlistapersonas").html(response);														
															}
										});						
									}			
								}',
								'select2:unselecting'=>'function(e) {
									var seleccion=$("#selectorPersonas:first"); 
									if (seleccion.val()) {
										$.ajax({
												type     : "POST",
												cache    : false,
												url      : "drop-lista?grupo=personas&id=" + seleccion.val(),
												success  : function(response) {
															$("#divlistapersonas").html(response);														
															}
										});						
									}			
								}'
							]							
						]);  	

						

						// -------------------Selector de vehiculos c/botón de alta ----------------------------------------
						$vehiculoDesc=$model->isNewRecord?'':Vehiculos::formateaVehiculoSelect2($model->ing_id_vehiculo);
						$vehiculosUrl=Yii::$app->urlManager->createUrl(['vehiculos/create-ajax']);
						$vehiculosAddon = [
							'append' => [
								'content'=>Html::a('<span 
										class="btn btn-primary">Nuevo</span>', 
										$vehiculosUrl,
										['title' => Yii::t('app', 'Nuevo Vehiculo'),
										 'onclick'=>'$.ajax({
											type     :"POST",
											cache    : false,
											url  : $(this).attr("href"),
											success  : function(response) {
														console.log(response);
														$("#divvehiculonuevo").html(response);
														$("#modalvehiculonuevo").modal("show")
														}
										});return false;',
										]),	
								'asButton' => true
							]
						];
						echo $form->field($model, 'ing_id_vehiculo')->widget(Select2::classname(), [
							'initValueText' => $vehiculoDesc, 
							'options' => ['id'=>'selectorVehiculos','placeholder' => '...'],
							'addon'=>$vehiculosAddon,
							'pluginOptions' => [
								'allowClear' => true,
								'minimumInputLength' => 3,
								'ajax' => [
									'url' => \yii\helpers\Url::to(['vehiculos/vehiculoslist']),
									'dataType' => 'json',
									'data' => new JsExpression('function(params) { return {q:params.term}; }')
								],
								'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
								'templateResult' => new JsExpression('function(idvehiculo) { return idvehiculo.text; }'),
								'templateSelection' => new JsExpression('function (idvehiculo) { return idvehiculo.text; }'),
							],
							'pluginEvents' => [
								'change' => 'function(e) { 
									var seleccion=$("#selectorVehiculos:first"); 
									if (seleccion.val()) {
										$.ajax({
												type     : "POST",
												cache    : false,
												url      : "add-lista?grupo=vehiculos&id=" + seleccion.val(),
												success  : function(response) {
															$("#divlistavehiculos").html(response);														
															}
										});						
									}			
								}',
								'select2:unselecting'=>'function(e) {
									var seleccion=$("#selectorVehiculos:first"); 
									if (seleccion.val()) {
										$.ajax({
												type     : "POST",
												cache    : false,
												url      : "drop-lista?grupo=vehiculos&id=" + seleccion.val(),
												success  : function(response) {
															$("#divlistavehiculos").html(response);														
															}
										});						
									}			
								}'
							]							
						]);  	

						echo Html::submitButton();
						ActiveForm::end();			    
					?>
				
			</div><!-- fin div col1 -->

			<div id="col2" class="col-md-5">
				<div id="divlistapersonas"><?php echo isset($tmpListaPersonas)?$tmpListaPersonas:'' ?></div>
				<div id="divlistavehiculos"><?php echo isset($tmpListaVehiculos)?$tmpListaVehiculos:'' ?></div>				
				<?php
						/*
						Pjax::begin();
						echo Html::beginForm();
						echo TabularForm::widget([
							'dataProvider'=>$dataProvider,
							'formName'=>'kvTabForm',
						    'actionColumn'=>false,
						    'serialColumn'=>false,
						    'checkboxColumn'=>false,
							'attributes'=>[
								'id'=>['type'=>TabularForm::INPUT_HIDDEN_STATIC],
								'apellido'=>['type'=>TabularForm::INPUT_HIDDEN_STATIC],
								'nombre'=>['type'=>TabularForm::INPUT_HIDDEN_STATIC],
								'nombre2'=>['type'=>TabularForm::INPUT_HIDDEN_STATIC],
								'nro_doc'=>['type'=>TabularForm::INPUT_HIDDEN_STATIC]
							],
						]);
						echo Html::submitButton();
						echo Html::endForm();
						Pjax::end();	
						*/
?>

					
			</div><!-- fin div col2 -->

			<div id="col3" class="col-md-2">
				    <?php 
				    echo 'Columna 3';
						//$this->render('_form', ['model' => $model,]);

					?>

			</div><!-- fin div col3 -->


		</div>
	</div>

	<?php	
	Modal::begin(['id'=>'modalpersonanueva',
		'header'=>'<span class="btn-warning">&nbsp;Persona nueva&nbsp;</span>']);
		echo '<div id="divpersonanueva"></div>';
	Modal::end();    
	?>


</div>
