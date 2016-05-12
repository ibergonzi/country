<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\InfracConceptos;
use frontend\models\InfracUnidades;

/* @var $this yii\web\View */
/* @var $model frontend\models\InfracConceptos */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('
$(document).ready(function() {
    $("#infracconceptos-es_multa").on("change", function (e) {
		if ($("#infracconceptos-es_multa").val()==0) {
			$("#infracconceptos-multa_unidad").attr("disabled", true);
			$("#infracconceptos-multa_precio").attr("readonly", "readonly");
			$("#infracconceptos-multa_reincidencia").attr("disabled", true);
			$("#infracconceptos-multa_reinc_porc").attr("readonly", "readonly");	
			$("#infracconceptos-multa_reinc_dias").attr("readonly", "readonly");
			$("#infracconceptos-multa_personas").attr("disabled", true);
			$("#infracconceptos-multa_personas_precio").attr("readonly", "readonly");
			
			$("#infracconceptos-dias_verif").removeAttr("readonly");																					
		} else {
			$("#infracconceptos-multa_unidad").removeAttr("disabled");
			$("#infracconceptos-multa_precio").removeAttr("readonly");
			$("#infracconceptos-multa_reincidencia").removeAttr("disabled");
			$("#infracconceptos-multa_reinc_porc").removeAttr("readonly");
			$("#infracconceptos-multa_reinc_dias").removeAttr("readonly");
			$("#infracconceptos-multa_personas").removeAttr("disabled");
			$("#infracconceptos-multa_personas_precio").removeAttr("readonly");	
			
			$("#infracconceptos-dias_verif").attr("readonly", "readonly");																				
			
		}
	});
});
');
$this->registerJs('$("#infracconceptos-concepto").focus()', yii\web\View::POS_READY);
?>

<div class="infrac-conceptos-form">

    <?php $form = ActiveForm::begin(); 
		$model->multa_precio = (!empty($model->multa_precio))?yii::$app->formatter->asDecimal($model->multa_precio,2):'0,00';    
 		$model->multa_reinc_porc = (!empty($model->multa_reinc_porc))?yii::$app->formatter->asDecimal($model->multa_reinc_porc,2):'0,00'; 
		$model->multa_personas_precio = (!empty($model->multa_personas_precio))?yii::$app->formatter->asDecimal($model->multa_personas_precio,2):'0,00'; 		   
    ?>

    <?= $form->field($model, 'concepto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'es_multa')->dropDownList(InfracConceptos::getSiNo())->hint('Si=Multa - No=Infracción') ?>

    <?= $form->field($model, 'dias_verif')->textInput(['readonly'=>($model->es_multa==InfracConceptos::SI)])
		->hint('Solo para infracciones, especifique cantidad de dias para hacer la verificación') ?>

    <?= $form->field($model, 'multa_unidad')->dropDownList(InfracUnidades::getLista(),
		['disabled'=>($model->es_multa==InfracConceptos::NO)])
		->hint('Especifique la moneda o el tipo de unidad a cobrar') ?>

    <?= $form->field($model, 'multa_precio')->textInput(['readonly'=>($model->es_multa==InfracConceptos::NO)])
		->hint('Solo multas: monto base') ?>

    <?= $form->field($model, 'multa_reincidencia')->dropDownList(InfracConceptos::getSiNo(),
		['disabled'=>($model->es_multa==InfracConceptos::NO)])
		->hint('Solo multas: Especifique si se calcula reincidencia') ?>

    <?= $form->field($model, 'multa_reinc_porc')->textInput(['readonly'=>($model->es_multa==InfracConceptos::NO)])
		->hint('Solo multas con reincidencia: % a calcular por cada reincidencia') ?>

    <?= $form->field($model, 'multa_reinc_dias')->textInput(['readonly'=>($model->es_multa==InfracConceptos::NO)])
		->hint('Solo multas con reincidencia: periodo para calcular reincidencias') ?>
    
    <?= $form->field($model, 'multa_personas')->dropDownList(InfracConceptos::getSiNo(),
		['disabled'=>($model->es_multa==InfracConceptos::NO)])
		->hint('Solo multas: Especifique si interviene la cantidad de personas en el cálculo') ?>

    <?= $form->field($model, 'multa_personas_precio')->textInput(['readonly'=>($model->es_multa==InfracConceptos::NO)])
		->hint('Solo multas con personas: monto por cada persona') ?>


    <div class="form-group">
        <?= Html::submitButton('Aceptar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
