<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use frontend\models\AccesosAutmanual;

use yii\bootstrap\Modal;
use frontend\models\Comentarios;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AccesosAutmanualSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Autorización de accesos manuales';
$this->params['breadcrumbs'][] = $this->title;

// esto se setea para que los comentarios tengan scrollbar
$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');	

$this->registerJs('
$(document).ready(function() {
    $("#modalcomentarionuevo").on("shown.bs.modal", function (e) {
		$("#comentarios-comentario").focus();
	});	
    $("#modalcomentarionuevo").on("hidden.bs.modal", function (e) {
		$("#gridInfracciones").yiiGridView("applyFilter");
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
<div class="accesos-autmanual-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <?php
		$columns=[
            'id',
            [
				'attribute'=>'hora_desde',
				'format'=>'datetime'
			],	
            [
				'attribute'=>'hora_hasta',
				'format'=>'datetime'
			],				
            [
				'attribute'=>'estado',
				'value'=>function ($model) { return AccesosAutmanual::getEstados($model->estado);},
				//'filter'=>AccesosAutmanual::getEstados()				
            ],
            ['class' => 'kartik\grid\ActionColumn',
             'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['create'], 
                                ['class' => 'btn-sm btn-primary',
                                 'title' => Yii::t('app', 'Alta de autorización'),]),
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
							['accesos-autmanual/view', 
							 'id' => $model->id
							]);
					return $url;
				 }						 

			  }
	  
	  
            ],		
		];
		echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => $columns,
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,],         
    ]); ?>
    
<?php	
	// para usar los comentarios en cualquier vista se incluyen:
	/*
	use yii\bootstrap\Modal;
	use frontend\models\Comentarios;
	$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');
	$this->registerJs('
	$(document).ready(function() {
		$("#modalcomentarionuevo").on("shown.bs.modal", function (e) {
			$("#comentarios-comentario").focus();
		});	
	});
	');
	*/
	Modal::begin(['id'=>'modalcomentarionuevo',
		'header'=>'<span class="btn-warning">&nbsp;Comentarios&nbsp;</span>']);
		echo '<div id="divcomentarionuevo"></div>';
	Modal::end();    

?>        
</div>
