<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;
use kartik\builder\TabularForm;

use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;

use yii\bootstrap\Modal;
use yii\widgets\Pjax;

use common\models\User;
use frontend\models\AccesosConceptos;





use kartik\icons\Icon;
// ver iconos en http://fortawesome.github.io/Font-Awesome/icons/
Icon::map($this, Icon::FA);

/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

$this->title = Yii::t('app', 'Ingresos');

// Hace que la transición de los modals sea mas rapida
$this->registerCss('
.fade {
  opacity: 0;
  -webkit-transition: opacity 0s linear;
       -o-transition: opacity 0s linear;
          transition: opacity 0s linear;
}
.modal.fade .modal-dialog {
  -webkit-transition: -webkit-transform 0s ease-out;
       -o-transition:      -o-transform 0s ease-out;
          transition:         transform 0s ease-out;

}
.nofade {
   transition: none;
}
');
// Agrega scrollbars a los modals que se exceden de tamaño
$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');

// refresca los gridviews luego de que se cierra el modal de comentarios
	$js = 
<<<JS
	$('#modalcomentarionuevo').on('hidden.bs.modal', function (e) {
		$.ajax({
			type   : "POST",cache  : false,
			url    : "refresca-lista?grupo=personas",
			success: function(r) {
					$("#divlistapersonas").html(r);
				}
		});		
		$.ajax({
			type   : "POST",cache  : false,
			url    : "refresca-lista?grupo=vehiculos",
			success: function(r) {
					$("#divlistavehiculos").html(r);
				}
		});		

	})
JS;
$this->registerJs($js,yii\web\View::POS_READY);

?>
<div class="accesos-ingreso">
						
	<div class='container'> 
	
		<div class='row'>

			<div id="col1" class="col-md-4"><!-- comienzo div col1 -->
				
				    <?php 
				   
						$form = ActiveForm::begin();

						// -------------------Selector de personas c/botón de alta ----------------------------------------
						//$personaDesc=$model->isNewRecord?'':Personas::formateaPersonaSelect2($model->id_persona,false);
						$personaDesc='';

						$personasUrl=Yii::$app->urlManager->createUrl(['personas/create-ajax']);
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
														//console.log(response);
														$("#divpersonanueva").html(response);
														$("#modalpersonanueva").modal("show");
														}
										});return false;',
										]),	
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
											url    : "add-lista?grupo=personas&id=" + seleccion,
											success: function(r) {
													$("#divlistapersonas").html(r);
													$("#selectorPersonas").select2("val","");
													$.ajax({
														type   : "POST", cache  : false,
														url    : "busca-vehiculos?id_persona=" + seleccion,
														success: function(r) {
																if (r != "NADA") {
																	$("#divvehiculos_persona").html(r);
																	$("#modalvehiculos_persona").modal("show");
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
												success  : function(response) {
															$("#divlistapersonas").html(response);														
															}
										});						
									}			
								}'
							]							
						]);  	
						

						// -------------------Selector de vehiculos c/botón de alta ----------------------------------------
						//$vehiculoDesc=$model->isNewRecord?'':Vehiculos::formateaVehiculoSelect2($model->ing_id_vehiculo);
						$vehiculoDesc='';

						$vehiculosUrl=Yii::$app->urlManager->createUrl(['vehiculos/create-ajax']);
						$vehiculosAddon = [
							'prepend'=>[
								'content'=>Icon::show('car',['title'=>'Buscar Vehiculos'],Icon::FA)
							],						
							'append' => [
								'content'=>Html::a('<span class="glyphicon glyphicon-plus-sign btn btn-primary"></span>', 
										$vehiculosUrl,
										['title' => Yii::t('app', 'Nuevo Vehiculo'),
										 'tabindex'=>-1,
										 'onclick'=>'$.ajax({
											type     :"POST",
											cache    : false,
											url  : $(this).attr("href"),
											success  : function(response) {
														$("#divvehiculonuevo").html(response);
														$("#modalvehiculonuevo").modal("show");
														}
										});return false;',
										]),	
								'asButton' => true
							]
						];
						echo $form->field($model, 'ing_id_vehiculo')->label(false)->widget(Select2::classname(), [
							'initValueText' => $vehiculoDesc, 
							'options' => [
											'id'=>'selectorVehiculos',
											'placeholder' => 'Buscar por patente o marca/modelo',
										    'title'=>'Buscar vehiculos',											
										 ],
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
									var seleccion=$("#selectorVehiculos:first").val(); 
									if (seleccion) {
										$.ajax({
											type   : "POST", cache  : false,
											url    : "add-lista?grupo=vehiculos&id=" + seleccion,
											success: function(r) {
												$("#divlistavehiculos").html(r);	
												$("#selectorVehiculos").select2("val","");	
												if (seleccion > 2) { // si es 1 o 2 es bicicleta o caminando
													$.ajax({
														type   : "POST", cache  : false,
														url    : "busca-personas?id_vehiculo=" + seleccion,
														success: function(r) {
																if (r != "NADA") {
																	console.log(r);
																	$("#divpersonas_vehiculo").html(r);
																	$("#modalpersonas_vehiculo").modal("show");
																}
															}
													});		
												}																									
											}
										});	
									}			
								}',
								'select2:unselecting'=>'function(e) {
									var seleccion=$("#selectorVehiculos:first").val(); 
									if (seleccion) {
										$.ajax({
												type     : "POST",
												cache    : false,
												url      : "drop-lista?grupo=vehiculos&id=" + seleccion,
												success  : function(response) {
															$("#divlistavehiculos").html(response);														
															}
										});						
									}			
								}'
							]							
						]);  	
						
						
						// -------------------Selector de autorizantes----------------------------------------
						//$autorizanteDesc=$model->isNewRecord?'':Autorizantes::formateaPersonaSelect2($model->id_persona,false);
						$autorizanteDesc='';

						$autorizantesAddon = [
							'prepend'=>[
								'content'=>Icon::show('key',['title'=>'Buscar Autorizantes'],Icon::FA)
							],
						];
						echo Select2::widget([
							'name'=>'autorizanteSelect2',
							'initValueText' => $autorizanteDesc, 
							'options' => [
											'id'=>'selectorAutorizantes',
											'placeholder' => 'Buscar por documento o nombre',
										    'title'=>'Buscar autorizantes',												
										 ],
							'addon'=>$autorizantesAddon,
							'pluginOptions' => [
								'allowClear' => true,
								'minimumInputLength' => 3,
								'ajax' => [
									'url' => \yii\helpers\Url::to(['autorizantes/apellidoslist']),
									'dataType' => 'json',
									'data' => new JsExpression('function(params) { return {q:params.term}; }')
								],
								'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
								'templateResult' => new JsExpression('function(idpersona) { return idpersona.text; }'),
								'templateSelection' => new JsExpression('function (idpersona) { return idpersona.text; }'),
							],
							'pluginEvents' => [
								'change' => 'function(e) { 
									var seleccion=$("#selectorAutorizantes:first").val(); 
									if (seleccion) {
										$.ajax({
											type   : "POST",cache  : false,
											url    : "add-lista?grupo=autorizantes&id=" + seleccion,
											success: function(r) {
													$("#divlistaautorizantes").html(r);
													$("#selectorAutorizantes").select2("val","");
												}
										});						
									}			
								}',
								'select2:unselecting'=>'function(e) {
									var seleccion=$("#selectorAutorizantes:first").val(); 
									if (seleccion) {
										$.ajax({
												type     : "POST",
												cache    : false,
												url      : "drop-lista?grupo=autorizantes&id=" + seleccion,
												success  : function(response) {
															$("#divlistaautorizantes").html(response);														
															}
										});						
									}			
								}'
							]							
						]);  							
						
						echo $form->field($model, 'id_concepto')->dropDownList(AccesosConceptos::getListaConceptos());
						
						echo $form->field($model,'motivo')->textInput();		
						
						echo $form->field($model,'cant_acomp')->textInput();				

						echo Html::submitButton();
						ActiveForm::end();			    
					?>
				
			</div><!-- fin div col1 -->

			<div id="col2" class="col-md-6"><!-- comienzo div col2 -->
				<div id="divlistapersonas">
						<?php echo isset($tmpListaPersonas)?$tmpListaPersonas:'' ?>
				</div>
				<div id="divlistavehiculos">
						<?php echo isset($tmpListaVehiculos)?$tmpListaVehiculos:'' ?>
				</div>				
				<div id="divlistaautorizantes">
						<?php echo isset($tmpListaAutorizantes)?$tmpListaAutorizantes:'' ?>
				</div>				

			</div><!-- fin div col2 -->

			<div id="col3" class="col-md-2"><!-- comienzo div col3 -->
				    <?php 
					$u=User::findOne(Yii::$app->user->getId());
					
					if (!empty($u->foto)) {
						$contenido=Html::img(Yii::$app->urlManager->createUrl('images/usuarios/'.$u->foto),
							['class'=>'img-thumbnail']);
					}
					else
					{
						$contenido=Html::img(Yii::$app->urlManager->createUrl('images/sinfoto.png'),['class'=>'img-thumbnail']);
					}
					echo $contenido;
					echo '<p><i>Usuario: '. Yii::$app->user->identity->username.'</i></p>';	
					echo '<h4>Portón: '.$model->ing_id_porton.'</h4>';				
					?>

			</div><!-- fin div col3 -->


		</div>
	</div>

	<?php	
	// modal que se abre cuando se presiona el boton de agregar persona nueva
	Modal::begin(['id'=>'modalpersonanueva',
		'header'=>'<span class="btn-warning">&nbsp;Persona nueva&nbsp;</span>',
		'options'=>['class'=>'nofade']]);
		echo '<div id="divpersonanueva"></div>';
	Modal::end();  
	// modal que se abre cuando se presiona el boton de agregar vehiculos nuevo	
	Modal::begin(['id'=>'modalvehiculonuevo',
		'header'=>'<span class="btn-warning">&nbsp;Vehiculo nuevo&nbsp;</span>',
		'options'=>['class'=>'nofade']]);
		echo '<div id="divvehiculonuevo"></div>';
	Modal::end();  
	// modal que se abre cuando se agrega una persona a la lista de personas (trae los vehiculos utilizados por la persona)	
	Modal::begin(['id'=>'modalvehiculos_persona',
		'header'=>'<span class="btn-warning">&nbsp;Vehiculos&nbsp;</span>',
		'options'=>['class'=>'nofade']]);
		echo '<div id="divvehiculos_persona"></div>';
	Modal::end();  	
	// modal que se abre cuando se agrega un vehiculo a la lista de vehiculos (trae las personas que utilizaron el vehiculo)	
	Modal::begin(['id'=>'modalpersonas_vehiculo',
		'header'=>'<span class="btn-warning">&nbsp;Personas&nbsp;</span>',
		'options'=>['class'=>'nofade']]);
		echo '<div id="divpersonas_vehiculo"></div>';
	Modal::end();  		
	// modal para los comentarios o mensajes
	Modal::begin(['id'=>'modalcomentarionuevo',
		'header'=>'<span class="btn-warning">&nbsp;Mensajes/Comentarios&nbsp;</span>']);
		echo '<div id="divcomentarionuevo"></div>';
	Modal::end();  	
	
	  
	?>


</div>
