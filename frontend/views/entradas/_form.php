<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;

use frontend\models\Persona;

use yii\bootstrap\Modal;


/* @var $this yii\web\View */
/* @var $model frontend\models\Entradas */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="entradas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'idpersonas_fk')->textInput() ?>
   
<?php    
	$personaDesc = empty($model->idpersonas_fk) ? '' : $model->persona->apellido.' '.$model->persona->nombre;
	
	
	//$url=Yii::$app->urlManager->createUrl(['persona/create-ajax']);
	$url=Yii::$app->urlManager->createUrl(['persona/create-ajax']);

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
                                    $("#divpersonanueva").html(response);
                                    $("#modalpersonanueva").modal("show")
                                    
                                    }
                    });return false;',
                    ]),	
			'asButton' => true
		]
	];	
	 
	echo $form->field($model, 'idpersonas_fk')->widget(Select2::classname(), [
		
		'initValueText' => $personaDesc, // set the initial display text
		'options' => ['id'=>'selectorPersonas','placeholder' => '...'],
		'addon'=>$addon,
		'pluginOptions' => [
			'allowClear' => true,
			'minimumInputLength' => 3,
			'ajax' => [
				'url' => \yii\helpers\Url::to(['persona/apellidoslist']),
				'dataType' => 'json',
				'data' => new JsExpression('function(params) { return {q:params.term}; }')
			],
			'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
			'templateResult' => new JsExpression('function(id) { return id.text; }'),
			'templateSelection' => new JsExpression('function (id) { return id.text; }'),
		],
	]);  
?>  

    <?= $form->field($model, 'idvehiculos_fk')->textInput() ?>

    <?= $form->field($model, 'motivo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	<?php	
	Modal::begin(['id'=>'modalpersonanueva',
		'header'=>'<span class="btn-warning">&nbsp;Persona nueva&nbsp;</span>']);
		echo '<div id="divpersonanueva"></div>';
	Modal::end();    
	?>
    

</div>
