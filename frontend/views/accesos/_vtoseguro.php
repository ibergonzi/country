<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;

?>

<div class="vtoseguro-form">


	<?php
		//Yii::trace($vehiculos);
		//echo Html::input('input','fecseguro',$fec,['id'=>'fecseguro']); 
		echo DateControl::widget([
			'name'=>'fecseguro', 
			'value'=>date('Y-m-d'),
			'type'=>DateControl::FORMAT_DATE,
			'options'=>[
				'options'=>[
				'id'=>'fecseguro'
					]
				]

		]);

		$Url=Yii::$app->urlManager->createUrl(['accesos/update-vto-seguro','idPersona'=>$idPersona,'fecseguro'=>'']);
		echo '<br/>';
		echo '<div class="form-group">';
		echo Html::a('<span class="btn btn-primary">Aceptar</span>',
										$Url,
										['title' => Yii::t('app', 'Aceptar'),
										 'id'=>'btnUpdSeguro',
										 'onclick'=>'
												var fecs=$("#fecseguro").val();
												//console.log(fecs);
												if (fecs === null) {
													return false;
												} else {										 
													$.ajax({
														type     :"POST",
														cache    : false,
														url      : $(this).attr("href")+fecs,
														success  : function(r) {
																	$("#divlistapersonas").html(r["personas"]);
																	$("#modalupdseguro").modal("hide");
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
