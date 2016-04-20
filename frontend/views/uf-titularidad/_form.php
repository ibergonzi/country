<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\Uf;
use frontend\models\UfTitularidad;
use frontend\models\UfTitularidadPersonas;
use yii\data\ActiveDataProvider;

use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;

use yii\bootstrap\Modal;
use kartik\datecontrol\DateControl;

use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidad */
/* @var $form yii\widgets\ActiveForm */
$this->registerCss('
.panel-heading {
  padding: 0px 5px;
  border-bottom: 1px solid transparent;
  border-top-left-radius: 2px;
  border-top-right-radius: 2px;
}
.table-condensed > thead > tr > th,
.table-condensed > tbody > tr > th,
.table-condensed > tfoot > tr > th,
.table-condensed > thead > tr > td,
.table-condensed > tbody > tr > td,
.table-condensed > tfoot > tr > td {
  padding: 1px;
}
');
?>

<div class="uf-titularidad-form">
    <?php

        $UfModel = Uf::findOne($model->id_uf);
        
        if (!empty($UfModel->ultUfTitularidad->id)) {
			
			$puedeCambiarTipoMovim=true;
 		
			$query=UfTitularidadPersonas::find()->joinWith('persona')
				->where(['uf_titularidad_id'=>$UfModel->ultUfTitularidad->id]);

			$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'sort' => ['defaultOrder' => ['tipo' => SORT_DESC,],
							'enableMultiSort'=>true,            
						  ],    
			]);					      
			
			$xDefecto=UfTitularidad::findOne($UfModel->ultUfTitularidad->id);
			$model->exp_telefono=$xDefecto->exp_telefono;
			$model->exp_direccion=$xDefecto->exp_direccion;
			$model->exp_localidad=$xDefecto->exp_localidad;
			$model->exp_email=$xDefecto->exp_email;                        
			
			echo GridView::widget([
				'dataProvider' => $dataProvider,
				'condensed'=>true,
				'layout'=>'{items}',
				//opciones validas solo para el gridview de kartik
				'panel'=>[
					'type'=>GridView::TYPE_INFO,
					'heading'=>'Titularidad actual sobre U.F.'.$model->id_uf,
					//'headingOptions'=>['class'=>'panel-heading'],
					'footer'=>false,
					'before'=>false,
					'after'=>false,
				],		
				'panelHeadingTemplate'=>'{heading}',			
				'resizableColumns'=>false,					
				'columns' => [
					//'id',
					//'uf_titularidad_id',
					[
						'attribute'=>'tipo',
						'value'=>function ($model) {return UfTitularidadPersonas::getTipos($model->tipo);},
					],
					//'tipo',
					'id_persona',
					'persona.apellido',
					'persona.nombre',
					'persona.nombre2',
					'persona.tipoDoc.desc_tipo_doc_abr',
					'persona.nro_doc',
					'observaciones',
					// 'created_by',
					// 'created_at',
					// 'updated_by',
					// 'updated_at',

					//['class' => 'yii\grid\ActionColumn'],
				],
			]); 
		} else {
			$puedeCambiarTipoMovim=false;
		}
    ?>

			<?php $form = ActiveForm::begin(); ?>

			<?php //$form->field($model, 'id_uf')->textInput() ?>

			<div class='row'>
				<div class='col-md-6'>
					<?= $form->field($model, 'tipo_movim')->dropDownList($model->listaMovimientos,['readonly' => !$puedeCambiarTipoMovim]) ?>
				</div>			
			
				<div class='col-md-3'>
					<?= $form->field($model, 'fec_desde')->widget(DateControl::classname(), [
						'type'=>DateControl::FORMAT_DATE,
					]) ?>
				</div>
				<div class='col-md-3'>
					<?= $form->field($model, 'fec_hasta')->widget(DateControl::classname(), [
						'type'=>DateControl::FORMAT_DATE,
					]) ?>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-3'>
					<?= $form->field($model, 'exp_telefono')->textInput(['maxlength' => true]) ?>
				</div>
				<div class='col-md-3'>
					<?= $form->field($model, 'exp_direccion')->textInput(['maxlength' => true]) ?>
				</div>
				<div class='col-md-3'>
					<?= $form->field($model, 'exp_localidad')->textInput(['maxlength' => true]) ?>
				</div>			
				<div class='col-md-3'>
					<?= $form->field($model, 'exp_email')->textInput(['maxlength' => true]) ?>
				</div>	
			</div>		
			
			<div class='row'>

					<?php //echo $form->field($titPers, 'tipo')->dropDownList($titPers->tipos)  ?>					

				<div class='col-md-6'>
					<?= $form->field($titPers, 'observaciones')->textInput(['maxlength' => true]) ?>
				</div>				
				<div class='col-md-6'>
					<?php
						// -------------------Selector de personas c/botón de alta ----------------------------------------
						//$personaDesc=$model->isNewRecord?'':Personas::formateaPersonaSelect2($model->id_persona,false);
						$personaDesc='';
						$personasUrl=Yii::$app->urlManager->createUrl(['personas/create-ajax']);
						$personasAddon = [
							'prepend'=>[
								'content'=>'<span class="glyphicon glyphicon-user" title="Buscar Personas"></span>',
							],
							'append' => [
								'content'=>
										Html::a('<span class="glyphicon glyphicon-plus-sign btn btn-primary"></span>',
										$personasUrl,
										['title' => Yii::t('app', 'Nueva Persona'),
										 'tabindex'=>-1,										
										 'onclick'=>'$.ajax({
											type     :"POST",
											cache    : false,
											url  : $(this).attr("href"),
											success  : function(response) {
														$("#divpersonanueva").html(response);
														$("#modalpersonanueva").modal("show");
														$("#personas-apellido").focus();
														}
										});
										return false;',
										]) 								
										,	
								'asButton' => true
							]
						];
						
						echo $form->field($titPers, 'id_persona')->widget(Select2::classname(), [
							'initValueText' => $personaDesc, 
							'options' => ['id'=>'selectorPersonas',
										  'placeholder' => 'Buscar por documento o nombre',
										  'title'=>'Buscar personas',
										 ],
							'addon'=>$personasAddon,
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
									var seleccion=$("#selectorPersonas:first").val(); 
									if (seleccion) {
										$.ajax({
											type   : "POST",cache  : false,
											url    : "add-lista?grupo=titpersonas&id=" + seleccion,
											success: function(r) {
													$("#divlistapersonas").html(r["titpersonas"]);
													$("#selectorPersonas").select2("val","");
												}
										});						
									}			
								}',
								'select2:unselecting'=>'function(e) {
									var seleccion=$("#selectorPersonas:first").val(); 
									if (seleccion) {
										$.ajax({
												type     : "POST",
												cache    : false,
												url      : "drop-lista?grupo=titpersonas&id=" + seleccion,
												success  : function(r) {
															$("#divlistapersonas").html(r["titpersonas"]);														
															}
										});						
									}			
								}'
							]							
						]);  
					?>						
				</div>
							
			</div>	


			<div class='row'>
				<div class='col-md-3'>
					<p>
						<?= Html::submitButton('Confirmar TODO lo realizado' , ['class' => 'btn btn-lg btn-primary',
						 //'style'=>'vertical-align:text-bottom;'
						]) ?>
					</p>					
				</div>	
				<?php ActiveForm::end(); ?>				
				<div class='col-md-9'>
					<div id="divlistapersonas">
						<?php echo isset($tmpListas['titpersonas'])?$tmpListas['titpersonas']:'' ?>
					</div>	
				</div>

			</div>
			
	<?php	
	// modal que se abre cuando se presiona el boton de agregar persona nueva
	Modal::begin(['id'=>'modalpersonanueva',
		'header'=>'<span class="btn-warning">&nbsp;Persona nueva&nbsp;</span>',
		'options'=>['class'=>'nofade'],
		]
		);
		echo '<div id="divpersonanueva"></div>';
	Modal::end();  
	?>		


</div>
