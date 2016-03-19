<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;


?>

<div class="ingpersonas-form">


	<?php
		echo '<div id="listboxPersonas1">';
		echo Html::checkboxList('listboxPersonas', $seleccion, ArrayHelper::map($personas,'id_persona','desc_persona'),
				['class'=>'form-control','tag'=>false,'separator'=>'<br/>']); 

		echo '</div>';
		$Url=Yii::$app->urlManager->createUrl(['accesos/add-lista-array','grupo'=>'egrpersonas','id'=>'']);
		echo '<br/>';
		echo '<div class="form-group">';
		echo Html::a('<span class="glyphicon glyphicon-plus-sign btn btn-primary"></span>',
										$Url,
										['title' => Yii::t('app', 'Aceptar'),
										 'id'=>'btnAddPersona',
										 'onclick'=>'
												var selected = [];
												$("#listboxPersonas1 :checkbox:checked").each(function() {
													selected.push($(this).val());
												});

												$.ajax({
													type     :"POST",
													cache    : false,
													url      : $(this).attr("href")+selected,
													success  : function(r) {
																$("#divlistapersonas").html(r["egrpersonas"]);
																$("#modalpersonas_vehiculo").modal("hide");
																}
													});
											
										
												return false;
											'
											,
										]);
		echo '</div>';
	?>


</div>
