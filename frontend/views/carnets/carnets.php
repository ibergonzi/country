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

/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

$this->title = Yii::t('app', 'Generación de carnets');

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


?>
<h3>Generación de carnets</h3>
<div class="carnets-index">
						
	<div class='container'> 
	
		<div class='row'><!-- comienzo row -->

			<div id="col1" class="col-md-4"><!-- comienzo div col1 -->
				
				    <?php 
				   
						$form = ActiveForm::begin();

					
						// -------------------Selector de personas c/botón de alta ----------------------------------------
						//$personaDesc=$model->isNewRecord?'':Personas::formateaPersonaSelect2($model->id_persona,false);
						$personaDesc='';

						/*
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
						*/
						echo $form->field($model, 'id')->label(false)->widget(Select2::classname(), [
							'initValueText' => $personaDesc, 
							'options' => ['id'=>'selectorPersonas',
										  'placeholder' => 'Buscar por documento o nombre',
										  'title'=>'Buscar personas',
										 ],
							//'addon'=>$personasAddon,
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
											url    : "add-lista?grupo=crntpersonas&id=" + seleccion,
											success: function(r) {
													$("#divlistapersonas").html(r["crntpersonas"]);
													$("#selectorPersonas").select2("val","");
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
						echo Html::submitButton('Aceptar',['class' => 'btn btn-primary','id'=>'btnSubmit']);	

						ActiveForm::end();			    
					?>
				
			</div><!-- fin div col1 -->

			<div id="col2" class="col-md-8"><!-- comienzo div col2 -->
				<div id="divlistapersonas">
						<?php echo isset($tmpListas['crntpersonas'])?$tmpListas['crntpersonas']:'' ?>
				</div>

			</div><!-- fin div col2 -->

		</div><!-- fin row -->
	
		
	</div>



</div>
