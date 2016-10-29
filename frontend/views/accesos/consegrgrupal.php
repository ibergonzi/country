<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use kartik\datecontrol\DateControl;

use yii\bootstrap\Collapse;

use frontend\models\Accesos;
use frontend\models\AccesosConceptos;
use frontend\models\AccesosVistaF;

use kartik\popover\PopoverX;

use yii\widgets\MaskedInput;

use yii\bootstrap\Modal;

use frontend\models\Mensajes;
use frontend\models\Comentarios;

$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AccesosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


if ($consulta) {
	$this->title = 'Personas adentro';
} else {
	$this->title = 'Egreso grupal';	
}
$this->params['breadcrumbs'][] = $this->title;
/*
$this->registerCss('
.table-condensed > thead > tr > th,
.table-condensed > tbody > tr > th,
.table-condensed > tfoot > tr > th,
.table-condensed > thead > tr > td,
.table-condensed > tbody > tr > td,
.table-condensed > tfoot > tr > td {
  padding: 2px;
}
');
*/

use app\assets\ExportSelectorAsset;
ExportSelectorAsset::register($this);


$this->registerJs('
$(document).ready(function() {
    $("#modalcomentarionuevo").on("shown.bs.modal", function (e) {
		$("#comentarios-comentario").focus();
	});	
    $("#modalmensaje").on("shown.bs.modal", function (e) {
		$("#mensajes-avisar_a").focus();
	});
    $("#modalmensaje").on("hidden.bs.modal", function (e) {
		$("#gridAccesos").yiiGridView("applyFilter");
	});	
    $("#modalcomentarionuevo").on("hidden.bs.modal", function (e) {
		$("#gridAccesos").yiiGridView("applyFilter");
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
<div class="accesos-index">

    
    <?php 
		if (\Yii::$app->session->get('accesosFecDesdeF')) {
			$lbl=Html::tag('span','',['class'=>'glyphicon glyphicon-warning-sign','style'=>'color:#FF8000']).
				'  '.
				'Filtro por fecha desde el ' . Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecDesdeF')) .
				' hasta el ' . Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecHastaF'));
				
				$searchModel->fecdesde=\Yii::$app->session->get('accesosFecDesdeF');
				$searchModel->fechasta=\Yii::$app->session->get('accesosFecHastaF');

		} else {
			$lbl='Filtrar por rango de fechas';
		}

		echo Collapse::widget([
		'encodeLabels'=>false,
		'items'=>[
				[
				'label'=> $lbl,
				'content'=>$this->render('_searchfec', ['model' => $searchModel]),
				]
			]
		]);

		
		if (\Yii::$app->session->get('accesosFecDesdeF')) {
			$lbl2=' ('.Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecDesdeF')) .
						'-' . Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecHastaF')) . ')';
		} else {
			$lbl2='';
		}	    
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
		
		// las columnas se definen fuera del gridview para poder extraer las etiquetas para el 
		// popover que define las columnas a exportar	
		
		if ($consulta) { 
			$header='Acciones';
		} else {
			$header=Html::button('<span class="glyphicon glyphicon-plus-sign"></span>',
							
							['class' => 'btn btn-lg btn-primary',
							 'title' => 'Efectuar egreso',
							 'data-pjax'=>'0',
							 'onclick'=>'var keys = $("#gridAccesos").yiiGridView("getSelectedRows");
										$.post({
										   url: "egreso-grupal", 
										   dataType: "json",
										   data: {keylist: keys},
										   success: function(data) {
												if (data.status === "success") {
													//$.pjax.reload({container:"#w0"});
													$("#gridAccesos").yiiGridView("applyFilter");
												} else {	
													alert("Hubo un error, intente de nuevo");  
												}
											}
										});'
							]
						 );
		}
		
		$columns=[
       		['class' => 'kartik\grid\ActionColumn',
				 'header'=>$header,
				 'headerOptions'=>['style'=>'text-align:center'],   
				 'template' => '{comentario} {mensajeP} {mensajeV}',  
				 'buttons' => [
					'comentario' => function ($url, $model) {
						$c=Comentarios::getComentariosByModelId('frontend\models\Accesos',$model->id);

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
					'mensajeP' => function ($url, $model) {	
							$c=Mensajes::getMensajesByModelId('frontend\models\Personas',$model->id_persona);

							if (!empty($c)) {
								$text='<span class="glyphicon glyphicon-user" style="color:#FF8000"></span>';
								$titl='Ver mensaje sobre la persona';
							} else {
								$text='<span class="glyphicon glyphicon-user"></span>';
								$titl='Ingresar nuevo mensaje sobre la persona';
							}								
							
							return Html::a($text, 
								$url,
							['title' => $titl,
							 'onclick'=>'$.ajax({
								type     :"POST",
								cache    : false,
								url  : $(this).attr("href"),
								success  : function(response) {
											$("#divmensaje").html(response);
											$("#modalmensaje").modal("show");
											}
							});return false;',
							]);							
					},		
					'mensajeV' => function ($url, $model) {	
							$c=Mensajes::getMensajesByModelId('frontend\models\Vehiculos',$model->ing_id_vehiculo);

							if (!empty($c)) {
								$text='<i class="fa fa-car" style="color:#FF8000"></i>';
								$titl='Ver mensaje sobre el vehiculo';
							} else {
								$text='<i class="fa fa-car"></i>';
								$titl='Ingresar nuevo mensaje sobre el vehiculo';
							}								
							
							return Html::a($text, 
								$url,
							['title' => $titl,
							 'onclick'=>'$.ajax({
								type     :"POST",
								cache    : false,
								url  : $(this).attr("href"),
								success  : function(response) {
											$("#divmensaje").html(response);
											$("#modalmensaje").modal("show");
											}
							});return false;',
							]);									
					},								
					'view' => function ($url, $model) {	
						return Html::a('<span class="glyphicon glyphicon-eye-open"',
							$url,
							[
								'title'=>'Ver detalle',
								'target'=>'_blank',
								'data-pjax'=>'0'
							]);
					    },		
					],	 				
					
				'urlCreator' => function ($action, $model, $key, $index) {
					 if ($action === 'comentario') {
							// en ComentariosController.CreateAjax se resuelve tanto el alta como la consulta de todos los comentarios
							// que ya tenga el acceso
							$url=Yii::$app->urlManager->createUrl(
									['comentarios/create-ajax',
										'modelName'=>'frontend\models\Accesos',
										'modelID'=>$model->id]);
						return $url;
					 }
					 if ($action === 'mensajeP') {
							$url=Yii::$app->urlManager->createUrl(
									['mensajes/create-ajax',
										'modelName'=>'frontend\models\Personas',
										'modelID'=>$model->id_persona]);	
							return $url;								 
					 }	
					 if ($action === 'mensajeV') {
							$url=Yii::$app->urlManager->createUrl(
									['mensajes/create-ajax',
										'modelName'=>'frontend\models\Vehiculos',
										'modelID'=>$model->ing_id_vehiculo]);	
							return $url;							 
					 }											 
					 if ($action === 'view') {
						$url=Yii::$app->urlManager->createUrl(
								['accesos/view', 
								 'id' => $model->id
								]);
						return $url;
					 }						 

				  }
		  
			], 		
			[
				'class' => '\kartik\grid\CheckboxColumn',
				'visible'=>!$consulta
			],			
            [
				'attribute'=>'id_acceso',
				'format' => 'raw',
				'value' => function ($model, $index, $widget) {
					return Html::a($model->id_acceso, 
								Yii::$app->urlManager->createUrl(
										['accesos/view', 
										 'id' => $model->id_acceso
										]),
										['title' => 'Ver detalle',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0',
										]);
					},
            ],
 		    [
				 'attribute'=>'ing_fecha',

				 'format'=>['date'],
				 'filter'=>MaskedInput::widget([
						'model' => $searchModel,
						'attribute'=>'ing_fecha',
						'mask' => '99/99/9999',
					]),
			],     
		    [
				 'attribute'=>'ing_hora',
				 //'options'=>['style'=>'width:420px',],             
				 'format'=>['time'],	
			],		           
            [
				'attribute'=>'id_persona',
				'format' => 'raw',
				'value' => function ($model, $index, $widget) {
					return Html::a($model->id_persona, 
								Yii::$app->urlManager->createUrl(
										['personas/view', 
										 'id' => $model->id_persona
										]),
										['title' => 'Ver detalle de persona',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										]);
					},
            ],
   		    [
				'attribute'=>'r_apellido',
				'noWrap'=>true,
		    ],
   		    [
				'attribute'=>            'r_nombre',
				'noWrap'=>true,
		    ],
   		    [
				'attribute'=>            'r_nombre2',
				'noWrap'=>true,
		    ],
            'r_nro_doc',
            
            'id_uf',
            
            [
				'attribute'=>'vto_seguro',
				'format'=>'date',
            ],            
            [
				'attribute'=>'ing_id_vehiculo',
				'format' => 'raw',
				'value' => function ($model, $index, $widget) {
					return Html::a($model->ing_id_vehiculo, 
								Yii::$app->urlManager->createUrl(
										['vehiculos/view', 
										 'id' => $model->ing_id_vehiculo
										]),
										['title' => 'Ver detalle del vehiculo',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										]);
					},
            ],
            'r_ing_patente',
   		    [
				'attribute'=>            'r_ing_marca',
				'noWrap'=>true,
		    ],
   		    [
				'attribute'=>            'r_ing_modelo',
				'noWrap'=>true,
		    ],
   		    [
				'attribute'=>            'r_ing_color',
				'noWrap'=>true,
		    ],
   		    [
				'attribute'=>'id_concepto',
				'value'=>'desc_concepto', 
				'filter'=>AccesosConceptos::getListaConceptos(false),
				'noWrap'=>true,
		    ],
   		    [
				'attribute'=>'motivo',
				'noWrap'=>true,
		    ],						 
   		    [
				'attribute'=>'control',
				'noWrap'=>true,
		    ],				

        ];	
		if ($consulta && \Yii::$app->user->can('exportarConsDentro')) {        
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

			//echo '<div class="clearfix"></div>';

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
		
		
		if ($consulta) {
			$dps=\Yii::$app->params['accesos.defaultPageSize'];
			$sz=\Yii::$app->params['accesos.sizes'];
		} else {
			$dps=\Yii::$app->params['accesosEgr.defaultPageSize'];
			$sz=\Yii::$app->params['accesosEgr.sizes'];						
		}
		
		$contentToolbar=\nterms\pagesize\PageSize::widget([
			'defaultPageSize'=>$dps,
			'sizes'=>$sz,
			'label'=>'',
			'options'=>[
					'class'=>'btn btn-default',
					'title'=>'Cantidad de elementos por página',
				],
			]);		
		if ($consulta && \Yii::$app->user->can('exportarConsDentro')) {			
			$toolbar=['{export} ',['content'=>$contentToolbar],];
		} else {
			$toolbar=[['content'=>$contentToolbar]];
		}				

	 
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options'=>['id'=>'gridAccesos'],
        
        'filterSelector' => 'select[name="per-page"]',  
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,],          
        
        // Para que muestre todo el gridview, solo aplicable a kartik, el de yii anda bien
        'containerOptions' => ['style'=>'overflow: visible'], 
		'condensed'=>true,
		
		'resizableColumns'=>false,

		
		//'persistResize'=>true,
		//'floatHeader'=>true,	
		//'bordered'=>false,

		
		'layout'=>'&nbsp;{toolbar}{summary}{items}{pager}',
		'export' => [
			'label' => 'Exportar',
			'fontAwesome' => true,
		    'showConfirmAlert'=>true,	
		    'target'=>GridView::TARGET_BLANK,			
		],
		
		'toolbar' => $toolbar,
		
		'pager' => [
			'firstPageLabel' => true,
			'lastPageLabel' => true,
		],			
		
		'exportConfig' => [
			GridView::PDF => [
					//'label' => Yii::t('kvgrid', 'PDF'),
					//'icon' => $isFa ? 'file-pdf-o' : 'floppy-disk',
					//'iconOptions' => ['class' => 'text-danger'],
					//'showHeader' => true,
					//'showPageSummary' => true,
					//'showFooter' => true,
					//'showCaption' => true,
					//'filename' => Yii::t('kvgrid', 'grid-export'),
					'filename' => $this->title,
					//'alertMsg' => Yii::t('kvgrid', 'The PDF export file will be generated for download.'),
					//'options' => ['title' => Yii::t('kvgrid', 'Portable Document Format')],
					//'mime' => 'application/pdf',
					'config' => [
							//'mode' => 'c',
							//'format' => 'A4-L',
							//'destination' => 'D',
							'destination' => 'I',
							//'marginTop' => 20,
							//'marginBottom' => 20,
							/*
							'cssInline' => '.kv-wrap{padding:20px;}' .
								'.kv-align-center{text-align:center;}' .
								'.kv-align-left{text-align:left;}' .
								'.kv-align-right{text-align:right;}' .
								'.kv-align-top{vertical-align:top!important;}' .
								'.kv-align-bottom{vertical-align:bottom!important;}' .
								'.kv-align-middle{vertical-align:middle!important;}' .
								'.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
								'.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
								'.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
							*/
							
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
								//'subject' => Yii::t('kvgrid', 'PDF export generated by kartik-v/yii2-grid extension'),
								//'keywords' => Yii::t('kvgrid', 'krajee, grid, export, yii2-grid, pdf')
							],
							'contentBefore'=>'',
							'contentAfter'=>''
					]			
			
			],
			GridView::EXCEL => [
					//'label' => Yii::t('kvgrid', 'Excel'),
					//'icon' => $isFa ? 'file-excel-o' : 'floppy-remove',
					//'iconOptions' => ['class' => 'text-success'],
					//'showHeader' => true,
					//'showPageSummary' => true,
					//'showFooter' => true,
					//'showCaption' => true,
					'filename' => $this->title,
					//'alertMsg' => Yii::t('kvgrid', 'The EXCEL export file will be generated for download.'),
					//'options' => ['title' => Yii::t('kvgrid', 'Microsoft Excel 95+')],
					//'mime' => 'application/vnd.ms-excel',
					'config' => [
						'worksheet' => $this->title,
						'cssFile' => ''
							]
			],
			GridView::CSV => [
			
					//'label' => Yii::t('kvgrid', 'CSV'),
					//'icon' => $isFa ? 'file-code-o' : 'floppy-open', 
					//'iconOptions' => ['class' => 'text-primary'],
					//'showHeader' => true,
					//'showPageSummary' => true,
					//'showFooter' => true,
					//'showCaption' => true,
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
		
        'columns' => $columns,
            

            
 
    ]); 
 	Modal::begin(['id'=>'modalcomentarionuevo',
		'header'=>'<span class="btn-warning">&nbsp;Comentarios&nbsp;</span>']);
		echo '<div id="divcomentarionuevo"></div>';
	Modal::end();  
	// modal para los mensajes
	Modal::begin(['id'=>'modalmensaje',
		'header'=>'<span class="btn-warning">&nbsp;Mensajes&nbsp;</span>',
		'options'=>['class'=>'nofade'],		
		]);
		echo '<div id="divmensaje"></div>';
	Modal::end();		  
	?>
</div>
