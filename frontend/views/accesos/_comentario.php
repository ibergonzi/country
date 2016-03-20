<?php

use yii\helpers\Html;

$this->registerJs('
$("#comentario").keypress( function (e) {
		if (e.keyCode==13) {
			$("#btnUpdComentario").click();
		}
	}
	);'
,yii\web\View::POS_READY);


?>

<div class="vtoseguro-form">


	<?php
		//Yii::trace($vehiculos);
		echo Html::input('input','comentario',$comentario,['id'=>'comentario','class'=>'form-control']); 


		$Url=Yii::$app->urlManager->createUrl(['accesos/set-comentario','comentario'=>'']);
		echo '<br/>';
		echo '<div class="form-group">';
		echo Html::a('<span class="btn btn-primary">Aceptar</span>',
										$Url,
										['title' => Yii::t('app', 'Aceptar'),
										 'id'=>'btnUpdComentario',
										 'onclick'=>'
												var com=$("#comentario").val();
												
												if (com === null) {
													return false;
												} else {										 
													$.ajax({
														type     :"POST",
														cache    : false,
														url      : $(this).attr("href")+com,
														success  : function(r) {
																	$("#btnComentario").html(r);
																	$("#modalcomentarionuevo").modal("hide");
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
