<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView" : "yii\\widgets\\ListView" ?>;

// necesario para comentarios
use yii\bootstrap\Modal;
use frontend\models\Comentarios;
// necesario para selector de campos para exportación
use kartik\popover\PopoverX;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

// scrollbar para el modal de comentarios
$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');	
// Asset con JS para el selector de campos para exportación
use app\assets\ExportSelectorAsset;
ExportSelectorAsset::register($this);
// Apertura y cierre de comentarios: acciones posteriores (foco al abrir y refresco de grilla al salir)
$this->registerJs('
$(document).ready(function() {
    $("#modalcomentarionuevo").on("shown.bs.modal", function (e) {
		$("#comentarios-comentario").focus();
	});	
    $("#modalcomentarionuevo").on("hidden.bs.modal", function (e) {
		$("#gridID").yiiGridView("applyFilter");
	});		
});
');
// Imagen personalizada cuando se procesa ajax
$this->registerCss('
.kv-grid-loading {
    opacity: 0.5;
    background: #ffffff url("../images/loading.gif") top center no-repeat !important;
}
');

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <h3><?= "<?= " ?>Html::encode($this->title) ?></h3>
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

<?= $generator->enablePjax ? '<?php Pjax::begin(); ?>' : '' ?>
<?php if ($generator->indexWidgetType === 'grid'): ?>
	<?="<?php \n" ?>
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
	
	$columns = [
	<?php
	$count = 0;
	if (($tableSchema = $generator->getTableSchema()) === false) {
		foreach ($generator->getColumnNames() as $name) {
			if (++$count < 6) {
				echo "            '" . $name . "',\n";
			} else {
				echo "            // '" . $name . "',\n";
			}
		}
	} else {
		foreach ($tableSchema->columns as $column) {
			$format = $generator->generateColumnFormat($column);
			if (++$count < 6) {
				echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
			} else {
				echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
			}
		}
	}
	?>	
           ['class' => 'kartik\grid\ActionColumn',
             'header'=>Html::a('<?= '<span class="glyphicon glyphicon-plus-sign"></span>'?>',
                                    ['create'], 
                                ['class' => 'btn-sm btn-primary',
                                 'title' => Yii::t('app', 'Nuevo'),]),
 			 'template' => '{view} {comentario}',      
			 'buttons' => [
				'comentario' => function ($url, $model) {
					$c=Comentarios::getComentariosByModelId($model->className(),$model->id);

					$text='<?= '<span class="glyphicon glyphicon-copyright-mark"'?>';
					if (!empty($c)) {
						$text.=' style="color:#FF8000"><?= '</span>'?>';
						$titl='Ingresar nuevo/Ver comentarios';
					} else {
						$text.='><?= '</span>'?>';
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
							['<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/view', 
							 'id' => $model->id
							]);
					return $url;
				 }	
			  }
            ],
	];

	
	// Armado de la selección de campos para exportar
	//if (\Yii::$app->user->can('PERMISOPARAEXPORTAR')) {        
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
			'toggleButton' => ['label'=>'<?= '<i class="glyphicon glyphicon-list"></i>' ?> Cols.a exportar', 
								'class'=>'btn btn-default pull-left'],
			'header'=>'Elija las columnas a exportar',
			'size'=>'lg',
			//'content'=>Html::checkboxList('exportColumns', [0,1,2,3], [0=>'Portón', 1=>'Usuario',2=>'Fecha',3=>'Texto'],
			'content'=>Html::checkboxList('exportColumns', $poSel, $poItems,
				['class'=>'form-control','tag'=>false,
				])											
		]);

	//}			
	
	// Definición de la cantidad de items a paginar
	$contentToolbar=\nterms\pagesize\PageSize::widget([
		//'defaultPageSize'=>\Yii::$app->params['REEMPLAZAR.defaultPageSize'],
		//'sizes'=>\Yii::$app->params['REEMPLAZAR.sizes'],
		'defaultPageSize'=>15,
		'sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50],		
		'label'=>'',
		'options'=>[
				'class'=>'btn btn-default',
				'title'=>'Cantidad de elementos por página',
			],
		]);	
	// Definición del toolbar		
	//if (\Yii::$app->user->can('PERMISOPARAEXPORTAR')) {			
		$toolbar=['{export} ',['content'=>$contentToolbar],];
	//} else {
	//	$toolbar=[['content'=>$contentToolbar]];
	//}	
	<?= "?>" ?>


    <?= "<?= " ?>GridView::widget([
        'options'=>['id'=>'gridID'],
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n" : ""; ?>
		'columns' => $columns,
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,], 

		'condensed'=>true, 

		'layout'=>'&nbsp;{toolbar}{summary}{items}{pager}',
		
        'filterSelector' => 'select[name="per-page"]',		
	
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
		 		
    ]); ?> 
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>
<?= $generator->enablePjax ? '<?php Pjax::end(); ?>' : '' ?>

<?= '<?php' ?>	

	Modal::begin(['id'=>'modalcomentarionuevo',
		'header'=>'<?= '<span class="btn-warning">&nbsp;Comentarios&nbsp;</span>'?>']);
		echo '<?= '<div id="divcomentarionuevo"></div>'?>';
	Modal::end();    
<?= '?>' ?>

</div>
