<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use kartik\datecontrol\DateControl;
use yii\widgets\Pjax;
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

use kartik\icons\Icon;
// ver iconos en http://fortawesome.github.io/Font-Awesome/icons/
Icon::map($this, Icon::FA);

$this->title = Yii::t('app', 'Accesos');
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

?>
<div class="accesos-index">

    
    <?php 

		if (\Yii::$app->session->get('accesosFecDesdeF')) {
			$lbl=Html::tag('span','',['class'=>'glyphicon glyphicon-warning-sign','style'=>'color:#FF8000']).
				'  '.
				'Filtro por fecha desde el ' . Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecDesdeF')) .
				' hasta el ' . Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecHastaF'));

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
		if (\Yii::$app->user->can('exportarConsAccesos')) {			
			// para evitar que la pagina se cuelgue cuando se le saca la paginación y hay muchos registros a mostrar
			$cant=$dataProvider->totalCount;
			if ( $cant <= \Yii::$app->params['max-rows-gridview'] ) {
				if ($cant <= $dataProvider->pagination->pageSize) {
					$toolbar=['{export}'];
				} else {
					$toolbar=['{export}','{toggleData}'];
				}
			} else {
				$toolbar=['{export}'];
			}
		} else {
			$toolbar='';
		}
		
		if (\Yii::$app->session->get('accesosFecDesdeF')) {
			$lbl2=' ('.Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecDesdeF')) .
						'-' . Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecHastaF')) . ')';
		} else {
			$lbl2='';
		}	
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
		$columns=[
       		['class' => 'kartik\grid\ActionColumn',
				 'header'=>'Acciones',
				 'headerOptions'=>['style'=>'text-align:center;width:70px'],   
				 'options'=>['style'=>'width:70px'],   
				 'template' => '{comentario} {mensajeP} {mensajeV}',  
				 'buttons' => [
					'comentario' => function ($url, $model) {
							$c=Comentarios::getComentariosByModelId('frontend\models\Accesos',$model->id_acceso);

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
										'modelID'=>$model->id_acceso]);
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
								 'id' => $model->id_acceso
								]);
						return $url;
					 }						 

				  }
		  
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
				 'options'=>['style'=>'width:275px;'],
				 'contentOptions'=>['style'=>'width:275px;'],   
				 'headerOptions'=>['style'=>'width:275px;'],          
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
            'r_apellido',
            'r_nombre',
            'r_nombre2',
            'r_nro_doc',
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
            'r_ing_marca',
            'r_ing_modelo',
            'r_ing_color',
            'ing_id_porton',
            'r_ing_usuario',
  		    [
				'attribute'=>'id_concepto',
				'value'=>'desc_concepto', 
				'filter'=>AccesosConceptos::getListaConceptos(true),
		    ],
            'motivo',		 
               
            [
				'attribute'=>'id_autorizante',
				'format' => 'raw',
				'value' => function ($model, $index, $widget) {
					return Html::a($model->id_autorizante, 
								Yii::$app->urlManager->createUrl(
										['personas/view', 
										 'id' => $model->id_autorizante
										]),
										['title' => 'Ver detalle de autorizante',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										]);
					},
            ],
            'r_aut_apellido',
            'r_aut_nombre',
            'r_aut_nombre2',

			/*		
			'r_aut_nro_doc',

			*/                     
            'id_uf',
		    [
				 'attribute'=>'egr_fecha',
				 //'options'=>['style'=>'width:420px',],             
				 'format'=>['date'],
				 'filter'=>MaskedInput::widget([
						'model' => $searchModel,
						'attribute'=>'egr_fecha',
						'mask' => '99/99/9999',
					]),				 
			],     
		    [
				 'attribute'=>'egr_hora',
				 //'options'=>['style'=>'width:420px',],             
				 'format'=>['time'],	
			],			    
            [
				'attribute'=>'egr_id_vehiculo',
				'format' => 'raw',
				'value' => function ($model, $index, $widget) {
					return Html::a($model->egr_id_vehiculo, 
								Yii::$app->urlManager->createUrl(
										['vehiculos/view', 
										 'id' => $model->egr_id_vehiculo
										]),
										['title' => 'Ver detalle del vehiculo',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										]);
					},
            ],
            'r_egr_patente',
            'r_egr_marca',
            'r_egr_modelo',
            'r_egr_color',
            'egr_id_porton',
            'r_egr_usuario',
            'control',
            [
				'attribute'=>'estado',
                'value'=>function($data) {return Accesos::getEstados($data->estado);},
                'filter'=>Accesos::getEstados(),
            ],
            'motivo_baja',

        ];	
		if (\Yii::$app->user->can('exportarConsAccesos')) {        
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

	Pjax::begin(['id' => 'grilla', 'timeout' => false ,
		'enablePushState' => false,
		'clientOptions' => ['method' => 'GET'] ]);    
		 
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options'=>['id'=>'gridAccesos'],
        // Para que muestre todo el gridview, solo aplicable a kartik, el de yii anda bien
        'containerOptions' => ['style'=>'overflow: visible'], 
		'condensed'=>false,
		'resizableColumns'=>false,
		//'floatHeader'=>true,	
		//'bordered'=>false,

		
		'layout'=>'&nbsp;{toolbar}{summary}{items}{pager}',
		
		'export' => [
			'label' => 'Exportar',
			'fontAwesome' => true,
		    'showConfirmAlert'=>false,	
		    'target'=>GridView::TARGET_BLANK,	
		    
		    //'target'=>GridView::TARGET_SELF,	
		    		
		],
		
		'toolbar' => $toolbar,
		
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
							'destination' => 'D',
							//'destination' => 'I',
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
								'subject' => '',
								'keywords' => '',
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
    Pjax::end();
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
