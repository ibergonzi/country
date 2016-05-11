<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use frontend\models\InfracConceptos;
use frontend\models\InfracUnidades;

use frontend\models\Comentarios;
use yii\bootstrap\Modal;

use kartik\popover\PopoverX;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\InfracConceptosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conceptos de infracciones';
$this->params['breadcrumbs'][] = $this->title;

// esto se setea para que los comentarios tengan scrollbar
$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');	

use app\assets\ExportSelectorAsset;
ExportSelectorAsset::register($this);

$this->registerJs('
$(document).ready(function() {
    $("#modalcomentarionuevo").on("shown.bs.modal", function (e) {
		$("#comentarios-comentario").focus();
	});	

    $("#modalcomentarionuevo").on("hidden.bs.modal", function (e) {
		$("#gridIC").yiiGridView("applyFilter");
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
<div class="infrac-conceptos-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
    <?php
		$lbl2='';
		$pdfHeader=[
					'L'=>['content'=>\Yii::$app->params['lblName']],
					'C'=>['content'=>$this->title . $lbl2,
						  //'font-size' => 80,
						  'font-style'=>'B'
						  //'color'=> '#333333'
						],
					'R'=>['content'=>''],
				
			];
		$pdfFooter=[
			'L'=>['content'=>\Yii::$app->params['lblName2']],
			'C'=>['content'=>'página {PAGENO} de {nb}'],
			'R'=>['content'=>'Fecha:{DATE d/m/Y}'],
			]; 	
		$columns=[
            'id',
            'concepto',
            [
				'attribute'=>'es_multa',
				'value'=>function ($model) { return InfracConceptos::getSiNo($model->es_multa);},
				'filter'=>InfracConceptos::getSiNo()
            ],
            [
				'attribute'=>'dias_verif',
				'hAlign'=>'right',			
            ],            
            [
				'attribute'=>'multa_unidad',
				'value'=>'multaUnidad.unidad',
				'filter'=>InfracUnidades::getLista()
            ],
            [
				'attribute'=>'multa_precio',
				'format'=>['decimal',2],	
				'hAlign'=>'right',			
            ],            
            [
				'attribute'=>'multa_reincidencia',
				'value'=>function ($model) { return InfracConceptos::getSiNo($model->multa_reincidencia);},
				'filter'=>InfracConceptos::getSiNo()
            ],      
            [
				'attribute'=>'multa_reinc_porc',
				'format'=>['decimal',2],	
				'hAlign'=>'right',			
            ],              
            [
				'attribute'=>'multa_reinc_dias',
				'hAlign'=>'right',			
            ],              
            [
				'attribute'=>'multa_personas',
				'value'=>function ($model) { return InfracConceptos::getSiNo($model->multa_personas);},
				'filter'=>InfracConceptos::getSiNo()
            ],            
            [
				'attribute'=>'multa_personas_precio',
				'format'=>['decimal',2],	
				'hAlign'=>'right',			
            ],               

           ['class' => 'kartik\grid\ActionColumn',
             'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['create'], 
                                ['class' => 'btn-sm btn-primary',
                                 'title' => Yii::t('app', 'Nuevo concepto'),]),
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
						$url=Yii::$app->urlManager->createUrl(
								['comentarios/create-ajax',
									'modelName'=>$model->className(),
									'modelID'=>$model->id]);
					return $url;
				 }
				 if ($action === 'view') {
					$url=Yii::$app->urlManager->createUrl(
							['infrac-conceptos/view', 
							 'id' => $model->id
							]);
					return $url;
				 }	
		 					 

			  }
	  
	  
            ],
		];	
		if (\Yii::$app->user->can('exportarListaPersonas')) {        
			// contiene la selección inicial de columnas, es decir, todas
			// por ejemplo [0,1,2,3]
			$poSel=[];
			// contiene las descripciones de las columnas
			// por ejemplo [0=>'Portón', 1=>'Usuario',2=>'Fecha',3=>'Texto']
			$poItems=[];
			$i=-1;
			foreach ($columns as $c) {
				$i++;
				// si es un array busca la clave "attribute"
				if (is_array($c)) {
					foreach ($c as $key=>$value) {
						if ($key=='attribute') {
							$poSel[]=$i;
							$poItems[$i]=$searchModel->getAttributeLabel($value);
							break;
						}
					}
				} else {
					$poSel[]=$i;
					$poItems[$i]=$searchModel->getAttributeLabel($c);
				}
			}

			// tiene que estar fuera del Pjax
			echo PopoverX::widget([
				'options'=>['id'=>'popControl'],
				'placement' => PopoverX::ALIGN_RIGHT,
				'toggleButton' => ['label'=>'<i class="glyphicon glyphicon-list"></i> Cols.a exportar', 
									'class'=>'btn btn-default pull-left'],
				'header'=>'Elija las columnas a exportar',
				'size'=>'lg',
				//'content'=>Html::checkboxList('exportColumns', [0,1,2,3], [0=>'Portón', 1=>'Usuario',2=>'Fecha',3=>'Texto'],
				'content'=>Html::checkboxList('exportColumns', $poSel, $poItems,
					['class'=>'form-control','tag'=>false,//'separator'=>'<br/>'
					])											
			]);
			// para que no se encime con el summary del gridview	
			//echo '<div class="clearfix"></div>';	
		}		

		
		$contentToolbar=\nterms\pagesize\PageSize::widget([
			'defaultPageSize'=>\Yii::$app->params['infracConceptos.defaultPageSize'],
			'sizes'=>\Yii::$app->params['infracConceptos.sizes'],
			'label'=>'',
			'options'=>[
					'class'=>'btn btn-default',
					'title'=>'Cantidad de elementos por página',
				],
			]);		
		if (\Yii::$app->user->can('exportarListaPersonas')) {			
			$toolbar=['{export} ',['content'=>$contentToolbar],];
		} else {
			$toolbar=[['content'=>$contentToolbar]];
		}							    
    ?>    
    
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => 'select[name="per-page"]',  
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,],  
		        
        'options'=>['id'=>'gridIC'],
        'columns' => $columns,
		'condensed'=>true, 
		'layout'=>'&nbsp;{toolbar}{summary}{items}{pager}',
	
		'export' => [
			'label' => 'Exportar',
			'fontAwesome' => true,
		    'showConfirmAlert'=>false,	
		    'target'=>GridView::TARGET_BLANK,			
		],
		
		'toolbar' => $toolbar,
		
		'pager' => [
			'firstPageLabel' => true,
			'lastPageLabel' => true,
		],			
		
		'exportConfig' => [
			GridView::PDF => [
					'filename' => $this->title,
					'config' => [
							'destination' => 'D',
					
							'methods' => [
								'SetHeader' => [
									['odd' => $pdfHeader, 'even' => $pdfHeader]
								],
								'SetFooter' => [
									['odd' => $pdfFooter, 'even' => $pdfFooter]
								],
							],
							
							'options' => [
								'title' => $this->title,
								'subject' => '',
								'keywords' => '',							],
							'contentBefore'=>'',
							'contentAfter'=>''
					]			
			
			],
			GridView::EXCEL => [
					'filename' => $this->title,
					'config' => [
						'worksheet' => $this->title,
						'cssFile' => ''
							]
			],
			GridView::CSV => [
					'filename' => $this->title,
					//'alertMsg' => Yii::t('kvgrid', 'The CSV export file will be generated for download.'),
					//'options' => ['title' => Yii::t('kvgrid', 'Comma Separated Values')],
					//'mime' => 'application/csv',
					
					'config' => [
						'colDelimiter' => ";",
						'rowDelimiter' => "\r\n",
					]
								
			
			],
		],	        	
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
