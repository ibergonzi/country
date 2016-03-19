<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;


?>

<div class="ingvehiculos-form">


	<?php
		//Yii::trace($vehiculos);
		echo Html::listBox('listboxVehiculos', $seleccion, ArrayHelper::map($vehiculos,'id_vehiculo','desc_vehiculo'),
				['id'=>'listboxVehiculos','class'=>'form-control']); 

		$Url=Yii::$app->urlManager->createUrl(['accesos/add-lista','grupo'=>'egrvehiculos','id'=>'']);
		echo '<br/>';
		echo '<div class="form-group">';
		echo Html::a('<span class="glyphicon glyphicon-plus-sign btn btn-primary"></span>',
										$Url,
										['title' => Yii::t('app', 'Aceptar'),
										 'id'=>'btnAddVehiculo',
										 'onclick'=>'
												var idVehic=$("#listboxVehiculos").val();
												if (idVehic === null) {
													return false;
												} else {
													$.ajax({
														type     :"POST",
														cache    : false,
														url      : $(this).attr("href")+idVehic,
														success  : function(r) {
																	$("#divlistavehiculos").html(r["egrvehiculos"]);
																	$("#modalvehiculos_persona").modal("hide");
																	}
														});
												}
												return false;
											'
											,
										]);
		echo '</div>';
	?>


</div>
