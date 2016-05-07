<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */
/* @var $form yii\widgets\ActiveForm */

// si se presiona enter en el input se dispara el click del botón
$this->registerJs('
$("#idPersonaPorId").keypress( function (e) {
		if (e.keyCode==13) {
			$("#btnPersonaPorId").click();
		}
	}
	);'
,yii\web\View::POS_READY);
?>			
<div class="personas-form">

	<?php
	
		echo Html::input('input','idPersonaPorId','',['id'=>'idPersonaPorId','class'=>'form-control']); 
		echo '<br/>';

		$Url=Yii::$app->urlManager->createUrl(['accesos/busca-persona-por-id','idPersonaPorId'=>'']);
		echo '<br/>';
		echo '<div class="form-group">';
		// si existe la persona en response viene el id y se dispara el evento change del select2
		
		// la variable $selector se especifica cuando se llama a actionBuscaPorId (por defecto es 'selectorPersonas') 
		// ver personas/change en donde se usa mas de un select2 para buscar personas
		echo Html::a('<span class="btn btn-primary">Aceptar</span>',
										$Url,
										['title' => Yii::t('app', 'Aceptar'),
										 'id'=>'btnPersonaPorId',
										 'onclick'=>"
												var idxpers=$('#idPersonaPorId').val();
												if (idxpers === null) {
													return false;
												} else {										 
													$.ajax({
														type     : 'POST',
														cache    : false,
														url      : $(this).attr('href')+idxpers,
														success  : function(result) {
																		if (result.message=='notFound') {
																			$('#idPersonaPorId').val('');
																		} else {
																			$('#modalporid').modal('hide');
																			var seleccion=$('#$selector');
																			var option = $('<option></option>').
																				 attr('selected', true).
																				 text(result.modelP['apellido']+' '+result.modelP['nombre']+' D:'+result.modelP['nro_doc']+' ('+result.modelP['id']+')').
																				 val(result.modelP['id']);
																			option.appendTo(seleccion);
																			seleccion.trigger('change');
																		}
																	}
														});
													return false;
												}
											"
											,
										]);
								
		echo '</div>';
	?>

</div>
			
	
