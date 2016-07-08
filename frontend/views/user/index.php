<?php
use common\models\User;

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\popover\PopoverX;

use yii\bootstrap\Modal;
use frontend\models\Comentarios;
$this->registerCss('.modal-body { max-height: calc(100vh - 210px);overflow-y: auto;}');

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Usuarios del sistema');
$this->params['breadcrumbs'][] = $this->title;

use app\assets\ExportSelectorAsset;
ExportSelectorAsset::register($this);

$this->registerJs('
$(document).ready(function() {
    $("#modalcomentarionuevo").on("shown.bs.modal", function (e) {
		$("#comentarios-comentario").focus();
	});	
    $("#modalcomentarionuevo").on("hidden.bs.modal", function (e) {
		$("#gridUsers").yiiGridView("applyFilter");
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
<div class="user-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

	<?php
		$columns=[
				'id',	
				'username',
			
			    [
					'attribute'=>'descRolUsuario',
					'value'=>'authAssignment.authItem.description',   
			    ],        
				[
					'attribute'=>'acceso_externo',
					'value'=>function ($model) { return User::getSiNo($model->acceso_externo);},
					'filter'=>User::getSiNo()
				],    			          
				[
					'attribute'=>'email',
					'format'=>'email',
				],				
				['class' => 'kartik\grid\ActionColumn',
				 'header'=>'',
				 'template' => '{view} {update} {delete} {comentario}',  
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
								['user/view', 
								 'id' => $model->id
								]);
						return $url;
					 }						 
					 if ($action === 'update') {
						$url=Yii::$app->urlManager->createUrl(
								['user/update', 
								 'id' => $model->id
								]);
						return $url;
					 }		
					 if ($action === 'delete') {
						$url=Yii::$app->urlManager->createUrl(
								['user/delete', 
								 'id' => $model->id
								]);
						return $url;
					 }										 			
				  }
		  
				], 
				
			];

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


		$contentToolbar=\nterms\pagesize\PageSize::widget([
			'defaultPageSize'=>\Yii::$app->params['user.defaultPageSize'],
			'sizes'=>\Yii::$app->params['user.sizes'],
			'label'=>'',
			'options'=>[
					'class'=>'btn btn-default',
					'title'=>'Cantidad de elementos por página',
				],
			]);		
		//if (\Yii::$app->user->can('exportarListaVehiculos')) {			
			$toolbar=['{export} ',['content'=>$contentToolbar],];
		//} else {
		//	$toolbar=[['content'=>$contentToolbar]];
		//}		
		

	?>
    <?php 

		echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        

        'filterSelector' => 'select[name="per-page"]',  
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,], 
		
		
        'options'=>['id'=>'gridUsers'],
        'columns' => $columns,		

		'condensed'=>true, 				
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
							'destination' => 'D',
							//'destination' => 'I',
							//'marginTop' => 20,
							//'marginBottom' => 20,

							//'cssInline' => '.kv-wrap{padding:20px;}' .
							//	'.kv-align-center{text-align:center;}' .
							//	'.kv-align-left{text-align:left;}' .
							//	'.kv-align-right{text-align:right;}' .
							//	'.kv-align-top{vertical-align:top!important;}' .
							//	'.kv-align-bottom{vertical-align:bottom!important;}' .
							//	'.kv-align-middle{vertical-align:middle!important;}' .
							//	'.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
							//	'.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
							//	'.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',

							
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
	

 
    ]); 

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
