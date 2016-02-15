<?php

use kartik\helpers\Html;
use kartik\grid\GridView;

use kartik\datecontrol\DateControl;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;

use yii\bootstrap\Modal;

use frontend\models\Comentarios;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\LibroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Libro de guardia');
$this->params['breadcrumbs'][] = $this->title;

// esto se setea para que los comentarios tengan scrollbar
$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');	

use app\assets\ExportSelectorAsset;
ExportSelectorAsset::register($this);

	
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
		
		
    ?>


	<?php
	
		// para evitar que la pagina se cuelgue cuando se le saca la paginación y hay muchos registros a mostrar
		//if ($dataProvider->totalCount <= 200) {
			$toolbar=['{export}','{toggleData}'];
		//} else {
		//	$toolbar=['{export}'];
		//}
		
	?>
	
	<?php
		// tiene que estar fuera del Pjax
		echo Html::checkboxButtonGroup(
			'exportColumns',[0,1,2,3],
			[0=>'Portón', 1=>'Usuario',2=>'Fecha',3=>'Texto'],
			['class'=>'pull-right btn-group-sm',
			'title' => 'Elija las columnas a exportar',
			]
			);	
		// para que no se encime con el summary del gridview	
		echo '<div class="clearfix"></div>';
	?>	
	
	<?php 
		Pjax::begin(['id' => 'grilla', 'timeout' => false ,
		'enablePushState' => false,
		'clientOptions' => ['method' => 'GET'] ]); 
	?>	


    <?php
	if (\Yii::$app->session->get('libroFecDesde')) {
		$lbl2=' ('.Yii::$app->formatter->asDate(\Yii::$app->session->get('libroFecDesde')) .
					'-' . Yii::$app->formatter->asDate(\Yii::$app->session->get('libroFecHasta')) . ')';
	} else {
		$lbl2='';
	}	
    $pdfHeader=[
				'L'=>['content'=>'Barrio Miraflores'],
				'C'=>['content'=>$this->title . $lbl2,
					  //'font-size' => 80,
					  'font-style'=>'B'
					  //'color'=> '#333333'
					],
				'R'=>['content'=>''],
			
		];
    $pdfFooter=[
		'L'=>['content'=>'Funes Hills'],
		'C'=>['content'=>'página {PAGENO} de {nb}'],
		'R'=>['content'=>'Fecha:{DATE d/m/Y}'],
		]; 
		
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        
        
		'panel'=>[
			'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> '.$this->title.'</h3>',
			'type'=>'default',
			//'before'=>'&nbsp;', // para que dibuje el toolbar (cuac)
		 ],
		 	
		'condensed'=>true, 

		// set a label for default menu
		
		'export' => [
			'label' => 'Exportar',
			'fontAwesome' => false,
		    'showConfirmAlert'=>false,	
		    'target'=>GridView::TARGET_BLANK,			
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
       
        'columns' => [           
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
					
					 'filter' => DateControl::widget([
						'model'=>$searchModel,
						'attribute'=>'created_at',
						'type' =>DateControl::FORMAT_DATE,
						'options' => [
								'pluginEvents'=>[ 'clearDate'=>'function(e) { 
													$.pjax.reload({container:"#grilla"});
													}',
												],	

							],
						
						])
					
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
        
        ] //fin columns
        
        
    ]); 
    ?>

<?php 
	Pjax::end(); 
?>

<?php	
	// para usar los comentarios en cualquier vista se incluyen:
	/*
	use yii\bootstrap\Modal;
	use frontend\models\Comentarios;
	$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');
	*/
	Modal::begin(['id'=>'modalcomentarionuevo',
		'header'=>'<span class="btn-warning">&nbsp;Comentarios&nbsp;</span>']);
		echo '<div id="divcomentarionuevo"></div>';
	Modal::end();    
?>


</div>
