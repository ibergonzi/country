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
		echo Html::a('<span class="btn btn-primary">Aceptar</span>',
										$Url,
										['title' => Yii::t('app', 'Aceptar'),
										 'id'=>'btnPersonaPorId',
										 'onclick'=>'
												var idxpers=$("#idPersonaPorId").val();
												//alert(idxpers);
												if (idxpers === null) {
													return false;
												} else {										 
													$.ajax({
														type     :"POST",
														cache    : false,
														url      : $(this).attr("href")+idxpers,
														success  : function(response) {
																		if (response=="notFound") {
																			$("#idPersonaPorId").val("");
																		} else {
																			$("#modalporid").modal("hide");
																			var seleccion=$("#selectorPersonas");
																			var option = $("<option></option>").
																				 attr("selected", true).
																				 text(response).
																				 val(response);
																			option.appendTo(seleccion);
																			seleccion.trigger("change");



																		}
																	}
														});
													return false;
												}
											'
											,
										]);
								
		echo '</div>';
	?>

</div>
			
	
