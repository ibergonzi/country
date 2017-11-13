<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;

use frontend\models\TiposLicencia;


use kartik\grid\GridView;

?>

<div class="vtoseguro-form">


	<?php
		//Yii::trace($vehiculos);
		//echo Html::input('input','fecseguro',$fec,['id'=>'fecseguro']); 
		//Yii::trace($fec);


		echo GridView::widget([
			'dataProvider' => $dp,
			'columns'=> ['vencimiento',
			],
			'layout'=>'{items}',			
		]);		
		echo '<p></p>';
		
		echo 'Tipo de licencia' . ' ' . Html::dropDownList('tipolic', null, TiposLicencia::getTiposLicenciaActivos(),['id'=>'tipolic']);		
		
		echo DateControl::widget([
			'name'=>'feclicencia', 
			//'value'=>date('Y-m-d'),
			'value'=>$fec,

			'type'=>DateControl::FORMAT_DATE,
			'options'=>[
				'options'=>[
				'id'=>'feclicencia'
					]
				]

		]);

		$Url=Yii::$app->urlManager->createUrl(['accesos/update-vto-licencia','idPersona'=>$idPersona,'feclicencia'=>'']);
		echo '<br/>';
		echo '<h4>Si no posee licencia: presionar el botón X y luego "Aceptar"</h4><br/>';	
		echo '<div class="form-group">';
		echo Html::a('<span class="btn btn-primary">Aceptar</span>',
										$Url,
										['title' => Yii::t('app', 'Aceptar'),
										 'id'=>'btnUpdSeguro',
										 'onclick'=>'
												var fecs=$("#feclicencia").val();
												var tl=$("#tipolic").val();
												//console.log("que hay:" + fecs);												
												if (fecs === "") {
													fecs="NADA";
												}												
												$.ajax({
														type     :"POST",
														cache    : false,
														url      : $(this).attr("href")+fecs+"&tipoLic="+tl,
														success  : function(r) {
																	$("#divlistapersonas").html(r["ingpersonas"]);
																	$("#modalupdseguro").modal("hide");
																	}
														});
													return false;
											'
											,
										]);
		echo '</div>';
	?>


</div>
