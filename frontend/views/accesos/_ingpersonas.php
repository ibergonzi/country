<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
		
$this->registerJs('
$("#listboxPersonas1 :checkbox").keypress( function (e) {
		if (e.keyCode==13) {
			$("#btnAddPersona").click();
		}
	}
	);'
,yii\web\View::POS_READY);
?>			


<div class="ingpersonas-form">


	<?php
		echo '<div id="listboxPersonas1">';
		echo Html::checkboxList('listboxPersonas', $seleccion, ArrayHelper::map($personas,'id_persona','desc_persona'),
				['class'=>'form-control','tag'=>false,'separator'=>'<br/>']); 

		echo '</div>';
		$Url=Yii::$app->urlManager->createUrl(['accesos/add-lista-array','grupo'=>'ingpersonas','id'=>'']);
		echo '<br/>';
		echo '<div class="form-group">';
		echo Html::a('Aceptar',
					$Url,
					['title' => Yii::t('app', 'Aceptar'),
					 'id'=>'btnAddPersona',
					 'class' => 'btn btn-primary',
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
											$("#divlistapersonas").html(r["ingpersonas"]);
											$("#modalpersonas_vehiculo").modal("hide");
											if (r["ultIngr"] != "notFound") {
												$("#accesos-motivo").val(r["ultIngr"].motivo);
												$("#accesos-id_concepto").val(r["ultIngr"].id_concepto);
												$("#accesos-id_concepto").trigger("change");
												$("#accesos-cant_acomp").val(r["ultIngr"].cant_acomp);
												$("#divlistaautorizantes").html(r["ultIngr"].motivo_baja["autorizantes"]);
											} else {
												$("#accesos-motivo").val("");
												$("#accesos-id_concepto").val("");
												$("#accesos-cant_acomp").val("");
												$("#divlistaautorizantes").html("");
											}

																						
											$("#btnSubmit").focus();
											}
								});
						
					
							return false;
						'
						,
					]);
		echo '</div>';
	?>


</div>
