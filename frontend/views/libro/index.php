<?php

use kartik\helpers\Html;
use kartik\grid\GridView;

use kartik\datecontrol\DateControl;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;

use yii\bootstrap\Modal;

use frontend\models\Comentarios;

use kartik\popover\PopoverX;

use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\LibroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Libro de guardia');
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
		$("#gridLibro").yiiGridView("applyFilter");
	});		
});
');
// se cambio el gif (está en web/images) y el center center por top center
$this->registerCss('
.kv-grid-loading {
    opacity: 0.5;
    background: #ffffff url("../images/loading.gif") top center no-repeat !important;
}
');	
?>

<div class="libro-index">

    <?php //echo Html::encode($this->title) ?>
 	
    <?php 

			if (\Yii::$app->session->get('libroFecDesde')) {
				$lbl=Html::tag('span','',['class'=>'glyphicon glyphicon-warning-sign','style'=>'color:#FF8000']).
					'  '.
					'Filtro por fecha desde el ' . Yii::$app->formatter->asDate(\Yii::$app->session->get('libroFecDesde')) .
					' hasta el ' . Yii::$app->formatter->asDate(\Yii::$app->session->get('libroFecHasta'));

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

		$contentToolbar=\nterms\pagesize\PageSize::widget([
			'defaultPageSize'=>\Yii::$app->params['libro.defaultPageSize'],
			'sizes'=>\Yii::$app->params['libro.sizes'],
			'label'=>'',
			'options'=>[
					'class'=>'btn btn-default',
					'title'=>'Cantidad de elementos por página',
				],
			]);
		if (\Yii::$app->user->can('exportarLibro')) {		
			$toolbar=['{export} ',['content'=>$contentToolbar],];					
		} else {
			$toolbar=[['content'=>$contentToolbar]];
		}
		
		

	if (\Yii::$app->session->get('libroFecDesde')) {
		$lbl2=' ('.Yii::$app->formatter->asDate(\Yii::$app->session->get('libroFecDesde')) .
					'-' . Yii::$app->formatter->asDate(\Yii::$app->session->get('libroFecHasta')) . ')';
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
			   [	'attribute'=>'idporton',
					'options'=>['style'=>'width:55px',], 
					//'hiddenFromExport'=>true,
					
			   ],        
			   [
					'attribute'=>'descUsuario',
					'value'=>'userCreatedBy.username',  
					'options'=>['style'=>'width:140px',],  

			   ],                   
			   [
					 'attribute'=>'created_at',
					 'options'=>['style'=>'width:160px',],             
					 'format'=>['datetime'],
					
					 'filter'=>MaskedInput::widget([
							'model' => $searchModel,
							'attribute'=>'created_at',
							'mask' => '99/99/9999',
						]),
					
				],   
				[         
					   'attribute'=>'texto',
					   'format'=>'text', 
					   'width'=>'500px',
				],
	 
				['class' => 'kartik\grid\ActionColumn',
				 'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
										['create'], 
									['class' => 'btn btn-sm btn-primary',
									 'title' => Yii::t('app', 'Agregar texto'),]),

				 'headerOptions'=>['style'=>'text-align:center'],   
				 'options'=>['style'=>'width:70px'],   
				 'template' => '{view}&nbsp;&nbsp;&nbsp;{comentario}',  
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
								['libro/view', 
								 'id' => $model->id
								]);
						return $url;
					 }						 

				  }
		  
				], 
        
        ]; //fin columns
        

        
		if (\Yii::$app->user->can('exportarLibro')) {
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
	
	
	/*		
	Pjax::begin(['id' => 'grilla', 'timeout' => false ,
		'enablePushState' => false,
		'clientOptions' => ['method' => 'GET'] ]); 
	*/
				
        //echo \nterms\pagesize\PageSize::widget(['defaultPageSize'=>5,'label'=>'']);	
        			//echo '<div class="clearfix"></div>';	
        	
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options'=>['id'=>'gridLibro'],	 	
		'condensed'=>true, 

        'filterSelector' => 'select[name="per-page"]',
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,],          

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
								'keywords' => '',							],
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
    ?>

<?php 
	//Pjax::end(); 
?>

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
