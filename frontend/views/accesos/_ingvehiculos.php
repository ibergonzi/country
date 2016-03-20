<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->registerJs('
$("#listboxVehiculos").keypress( function (e) {
		if (e.keyCode==13) {
			$("#btnAddVehiculo").click();
		}
	}
	);'
,yii\web\View::POS_READY);
?>	

<div class="ingvehiculos-form">


	<?php
		//Yii::trace($vehiculos);
		echo Html::listBox('listboxVehiculos', $seleccion, ArrayHelper::map($vehiculos,'id_vehiculo','desc_vehiculo'),
				['id'=>'listboxVehiculos','class'=>'form-control']); 

		$Url=Yii::$app->urlManager->createUrl(['accesos/add-lista','grupo'=>'ingvehiculos','id'=>'']);
		echo '<br/>';
		echo '<div class="form-group">';
		echo Html::a('Aceptar',
					$Url,
					['title' => Yii::t('app', 'Aceptar'),
					 'id'=>'btnAddVehiculo',
					 'class' => 'btn btn-primary',
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
												$("#modalvehiculos_persona").modal("hide");
												$("#divlistavehiculos").html(r["ingvehiculos"]);
												$("#btnSubmit").focus();
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
