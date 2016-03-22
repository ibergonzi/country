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

use kartik\widgets\Alert;
use kartik\widgets\SideNav;
use yii\helpers\Url;

use kartik\grid\GridView;






use kartik\icons\Icon;
// ver iconos en http://fortawesome.github.io/Font-Awesome/icons/
Icon::map($this, Icon::FA);

/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

$this->title = Yii::t('app', 'Egresos');

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
// Agrega scrollbars a los modals que se exceden de tamaño
$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');

// refresca los gridviews luego de que se cierra el modal de comentarios
	$js = 
<<<JS
	$('#modalcomentarionuevo').on('hidden.bs.modal', function (e) {
		$.ajax({
			type   : "POST",cache  : false,
			url    : "refresca-listas",
			success: function(r) {
					$("#divlistapersonas").html(r["personas"]);
					$("#divlistavehiculos").html(r["vehiculos"]);
				}
		});		

	})
JS;
$this->registerJs($js,yii\web\View::POS_READY);
// se registra en el document.ready porque no funcionaba con el POS_READY
$this->registerJs('
$(document).ready(function() {
    $("#selectorVehiculos").select2("open");
});
');
?>
<div class="accesos-egreso">
						
	<div class='container'> 
	
		<div class='row'><!-- comienzo row -->

			<div id="col1" class="col-md-4"><!-- comienzo div col1 -->
				
				    <?php 
				   
						$form = ActiveForm::begin();

						// -------------------Selector de vehiculos c/botón de alta ----------------------------------------
						//$vehiculoDesc=$model->isNewRecord?'':Vehiculos::formateaVehiculoSelect2($model->ing_id_vehiculo);
						$vehiculoDesc='';

						$vehiculosUrl=Yii::$app->urlManager->createUrl(['vehiculos/create-ajax']);
						$vehiculosAddon = [
							'prepend'=>[
								'content'=>Icon::show('car',['title'=>'Buscar Vehiculos'],Icon::FA)
							],						
						];
						echo $form->field($model, 'egr_id_vehiculo')->label(false)->widget(Select2::classname(), [
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
											url    : "add-lista?grupo=egrvehiculos&id=" + seleccion,
											success: function(r) {
												$("#divlistavehiculos").html(r["egrvehiculos"]);	
												$("#selectorVehiculos").select2("val","");	
												if (seleccion > 2) { // si es 1 o 2 es bicicleta o caminando
													$.ajax({
														type   : "POST", cache  : false,
														url    : "busca-personas?grupo=egrvehiculos&id_vehiculo=" + seleccion,
														success: function(r) {
																if (r != "notFound") {
																	$("#divpersonas_vehiculo").html(r);
																	$("#modalpersonas_vehiculo").modal("show");
																	$("#listboxPersonas1 :checkbox:first").focus();
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
												success  : function(r) {
															$("#divlistavehiculos").html(r["vehiculos"]);
															}
										});						
									}			
								}'
							]							
						]);  	
						
						// -------------------Selector de personas c/botón de alta ----------------------------------------
						//$personaDesc=$model->isNewRecord?'':Personas::formateaPersonaSelect2($model->id_persona,false);
						$personaDesc='';

						$personasUrl=Yii::$app->urlManager->createUrl(['personas/create-ajax']);
						$porID=Yii::$app->urlManager->createUrl(['accesos/busca-por-id']);
						$personasAddon = [
							'prepend'=>[
								'content'=>'<span class="glyphicon glyphicon-user" title="Buscar Personas"></span>',
							],
							'append' => [
								'content'=>
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
						
						echo $form->field($model,'control')->textInput();		
						
					?>
				
					<div class='row'>
						<div class="col-md-7">
							<?php
							echo Html::submitButton('Aceptar',['class' => 'btn btn-primary','id'=>'btnSubmit']);
							?>
						</div>
						<div class="col-md-5">
							<?php
							$url=Yii::$app->urlManager->createUrl(
									['accesos/pide-comentario']);
							$com=\Yii::$app->session->get('comentario');
							if (!empty($com) && $com !== '') {
								$cartel='<i class="glyphicon glyphicon-eye-open"></i> Comentario';
							} else {
								$cartel='Comentario';
							}									
							echo Html::a($cartel, 
								$url,
								['title' => 'Observación/comentario',
								 'class' => 'btn btn-default',
								 'id'=>'btnComentario',
								 'onclick'=>'$.ajax({
									type     :"POST",
									cache    : false,
									url  : $(this).attr("href"),
									success  : function(response) {
												$("#divcomentarionuevo").html(response);
												$("#modalcomentarionuevo").modal("show");
												$("#comentario").focus();
												}
								});return false;',
								]);											
							?>
						</div>
					</div>
					<?php
					ActiveForm::end();			    
					?>
				
			</div><!-- fin div col1 -->

			<div id="col2" class="col-md-6"><!-- comienzo div col2 -->
				<div id="divlistapersonas">
						<?php echo isset($tmpListas['egrpersonas'])?$tmpListas['egrpersonas']:'' ?>
				</div>
				<div id="divlistavehiculos">
						<?php echo isset($tmpListas['egrvehiculos'])?$tmpListas['egrvehiculos']:'' ?>
				</div>				

			</div><!-- fin div col2 -->

			<div id="col3" class="col-md-2"><!-- comienzo div col3 -->
				    <?php 
					foreach (\Yii::$app->session->getAllFlashes() as $keyM => $messageM) {
						if (is_array($messageM)) {
							$i=0;
							foreach ($messageM as $m) {
								$i=$i+1;
								echo Alert::widget([
									'type' => 'alert-'.$keyM,
									'body' => $m,
									'delay' => $i*7000,
								]);
							}
						} else {
							echo Alert::widget([
								'type' => 'alert-'.$keyM,
								'body' => $messageM,
								'delay' => 5000
								]);							
						}
					}				    
				    
				    
				    
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
					echo '<h4>Portón: '.\Yii::$app->session->get('porton').'</h4>';		
					
					echo SideNav::widget([
						'type' => 'danger',
						'encodeLabels' => false,
						'heading' => '',
						'items' => [
							['label' => 'Ingresos', 'icon' => 'arrow-right', 'url' => Url::to(['accesos/ingreso'])],
							['label' => 'Libro guardia', 'icon' => 'book', 'url' => Url::to(['libro/index']),
								],
						],
					]);  					

										
							
					?>

			</div><!-- fin div col3 -->


		</div><!-- fin row -->
		
		<div class='row'><!-- comienzo row -->
			<?php 
				/*
				echo GridView::widget([
					'dataProvider' => $dataProvider,
					'filterModel' => $searchModel,
					'columns' => [
						'id',
						'id_persona',
						'ing_id_vehiculo',
						'ing_fecha',
						'ing_hora',
						'ing_id_porton',
						'id_concepto',
						'motivo',

						['class' => 'yii\grid\ActionColumn'],
					],
				]);
				*/ 
			?>			
			
		</div><!-- fin row -->
		
		
	</div>

	<?php	
	// modal que se abre cuando se presiona el boton de agregar persona nueva
	Modal::begin(['id'=>'modalpersonanueva',
		'header'=>'<span class="btn-warning">&nbsp;Persona nueva&nbsp;</span>',
		'options'=>['class'=>'nofade'],
		]
		);
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
		'options'=>['class'=>'nofade'],
		'clientOptions'=>['backdrop'=>'static','keyboard'=>false],		
		]);
		echo '<div id="divvehiculos_persona"></div>';
	Modal::end();  	
	// modal que se abre cuando se agrega un vehiculo a la lista de vehiculos (trae las personas que utilizaron el vehiculo)	
	Modal::begin(['id'=>'modalpersonas_vehiculo',
		'header'=>'<span class="btn-warning">&nbsp;Personas&nbsp;</span>',
		'options'=>['class'=>'nofade'],
		'clientOptions'=>['backdrop'=>'static','keyboard'=>false],		
		]);
		echo '<div id="divpersonas_vehiculo"></div>';
	Modal::end();  		
	// modal para los comentarios o mensajes
	Modal::begin(['id'=>'modalcomentarionuevo',
		'header'=>'<span class="btn-warning">&nbsp;Mensajes/Comentarios&nbsp;</span>',
		'options'=>['class'=>'nofade'],		
		]);
		echo '<div id="divcomentarionuevo"></div>';
	Modal::end();  	
	// modal que se abre cuando se agrega un vehiculo a la lista de vehiculos (trae las personas que utilizaron el vehiculo)	
	Modal::begin(['id'=>'modalupdseguro',
		'header'=>'<span class="btn-warning">&nbsp;Vencimiento de seguro&nbsp;</span>',
		'options'=>['class'=>'nofade'],
		'clientOptions'=>['backdrop'=>'static','keyboard'=>false],		
		]);
		echo '<div id="divupdseguro"></div>';
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
