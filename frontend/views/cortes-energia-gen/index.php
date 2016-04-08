<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CortesEnergiaGenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Novedades de generadores';
$this->params['breadcrumbs'][] = ['label' => 'Cortes de energía', 'url' => ['cortes-energia/index']];
$this->params['breadcrumbs'][] = $this->title;

use yii\bootstrap\Modal;
use frontend\models\Comentarios;

// esto se setea para que los comentarios tengan scrollbar
$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');	
$this->registerJs('
$(document).ready(function() {
    $("#modalcomentarionuevo").on("shown.bs.modal", function (e) {
		$("#comentarios-comentario").focus();
	});	
    $("#modalcomentarionuevo").on("hidden.bs.modal", function (e) {
		$("#gridCEG").yiiGridView("applyFilter");
	});		
});
');
$this->registerCss('
.kv-grid-loading {
    opacity: 0.5;
    background: #ffffff url("../images/loading.gif") top center no-repeat !important;
}
');
?>
<div class="cortes-energia-gen-index">

    <h3><?= Html::encode($this->title. ' ' . 
		Yii::$app->formatter->asDatetime($parent->hora_desde) .
		' - '. 
		Yii::$app->formatter->asTime($parent->hora_hasta)) ?>
    </h3>
    <?php 

	$columns=[
            //'id',
            //'id_cortes_energia',
            [
				'attribute'=>'descripcion',
				'value'=>'generador.descripcion',
            ],            
			[
				 'attribute'=>'hora_desde',
				 'format'=>['datetime'],
			],     		
			[
				 'attribute'=>'hora_hasta',
				 'format'=>['datetime'],
			],     	
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            // 'estado',
            // 'motivo_baja',

           ['class' => 'kartik\grid\ActionColumn',
             'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['create','idParent'=>$parent->id], 
                                ['class' => 'btn-sm btn-primary',
                                 'title' => 'Alta de novedad',]),
 			 'template' => '{view} {comentario}',      
			 'buttons' => [
				'comentario' => function ($url, $model) {
					$c=Comentarios::getComentariosByModelId($model->className(),$model->id);

					$text='<span class="glyphicon glyphicon-copyright-mark"';
					if (!empty($c)) {
						$text.=' style="color:#FF8000"></span>';
						$titl='Ingresar nuevo/Ver comentarios';
					} else {
						$text.='></span>';
						$titl='Ingresar nuevo comentario';
					}	
					return Html::a($text, 
						$url,
						['title' => $titl,
						 'onclick'=>'$.ajax({
							type     :"POST",
							cache    : false,
							url  : $(this).attr("href"),
							success  : function(response) {
										$("#divcomentarionuevo").html(response);
										$("#modalcomentarionuevo").modal("show");
										
										}
						});return false;',
						]);							
					},
				
				],	 				
				
			'urlCreator' => function ($action, $model, $key, $index) {
				 if ($action === 'comentario') {
						// en ComentariosController.CreateAjax se resuelve tanto el alta como la consulta de todos los comentarios
						// que ya tenga la entrada del libro
						$url=Yii::$app->urlManager->createUrl(
								['comentarios/create-ajax',
									'modelName'=>$model->className(),
									'modelID'=>$model->id]);
					return $url;
				 }
	 
				 if ($action === 'view') {
					$url=Yii::$app->urlManager->createUrl(
							['cortes-energia-gen/view', 
							 'id' => $model->id
							]);
					return $url;
				 }						 

			  }
	  
	  
            ],
        ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $columns,
        'options'=>['id'=>'gridCEG'],
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,],         
    ]); 
    ?>
<?php	
	Modal::begin(['id'=>'modalcomentarionuevo',
		'header'=>'<span class="btn-warning">&nbsp;Comentarios&nbsp;</span>']);
		echo '<div id="divcomentarionuevo"></div>';
	Modal::end();    
?>    
</div>
