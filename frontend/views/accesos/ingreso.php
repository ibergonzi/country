<?php

use yii\helpers\Html;


use yii\widgets\ActiveForm;
use kartik\builder\TabularForm;


use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;

use yii\bootstrap\Modal;

use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

$this->title = Yii::t('app', 'Ingresos');

$js = 
<<<JS
$("#selectorPersonas").select2()
        .on("change", function(e) {
          console.log("change val=" + e.val);
        })
JS;
//$this->registerJs($js,yii\web\View::POS_READY);


?>
<div class="accesos-ingreso">
						
	<div class='container'> 
	
		<div class='row'>

			<div id="col1" class="col-md-5">
				
				    <?php 
				   
						$form = ActiveForm::begin();
						$personaDesc=$model->isNewRecord?'':Personas::formateaPersonaSelect2($model->idpersona,false);

						
						$url=Yii::$app->urlManager->createUrl(['personas/create-ajax']);

						$addon = [
							'append' => [
								'content'=>Html::a('<span 
										class="btn btn-primary">Nueva</span>', 
										$url,
										['title' => Yii::t('app', 'Nueva Persona'),
										 'onclick'=>'$.ajax({
											type     :"POST",
											cache    : false,
											url  : $(this).attr("href"),
											success  : function(response) {
														console.log(response);
														$("#divpersonanueva").html(response);
														$("#modalpersonanueva").modal("show")
														
														}
										});return false;',
										]),	
								'asButton' => true
							]
						];
							
						
							 
						echo $form->field($model, 'id_persona')->widget(Select2::classname(), [

							//'model' => $model,
							//'attribute' => 'idpersona',
							'initValueText' => $personaDesc, 
							'options' => ['id'=>'selectorPersonas','placeholder' => '...'],
							'addon'=>$addon,
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
									var seleccion=$("#selectorPersonas:first"); 
									console.log(seleccion.val());
									$.post("ingreso?nueva=" + seleccion.val(),$("#w1").serialize());
								}',
							]							
						]);  	
						echo Html::submitButton();
						ActiveForm::end();			    
					?>
				
			</div><!-- fin div col1 -->

			<div id="col2" class="col-md-5">
				<?php
						Pjax::begin();
						echo Html::beginForm();
						echo TabularForm::widget([
							'dataProvider'=>$dataProvider,
							'formName'=>'kvTabForm',
						    'actionColumn'=>false,
						    'serialColumn'=>false,
						    'checkboxColumn'=>false,
							'attributes'=>[
								'id'=>['type'=>TabularForm::INPUT_HIDDEN_STATIC],
								'apellido'=>['type'=>TabularForm::INPUT_HIDDEN_STATIC],
								'nombre'=>['type'=>TabularForm::INPUT_HIDDEN_STATIC],
								'nombre2'=>['type'=>TabularForm::INPUT_HIDDEN_STATIC],
								'nro_doc'=>['type'=>TabularForm::INPUT_HIDDEN_STATIC]
							],
						]);
						echo Html::submitButton();
						echo Html::endForm();
						Pjax::end();	
?>

					
			</div><!-- fin div col2 -->

			<div id="col3" class="col-md-2">
				    <?php 
				    echo 'Columna 3';
						//$this->render('_form', ['model' => $model,]);

					?>

			</div><!-- fin div col3 -->


		</div>
	</div>

	<?php	
	Modal::begin(['id'=>'modalpersonanueva',
		'header'=>'<span class="btn-warning">&nbsp;Persona nueva&nbsp;</span>']);
		echo '<div id="divpersonanueva"></div>';
	Modal::end();    
	?>


</div>
