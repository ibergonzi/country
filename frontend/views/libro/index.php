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
	/*
		// para evitar que la pagina se cuelgue cuando se le saca la paginación y hay muchos registros a mostrar
		if ($dataProvider->totalCount <= 100) {
			$toolbar=['{export}','{toggleData}'];
		} else {
			$toolbar=['{export}'];
		}
	*/	
	?>
	
	<?php
		// tiene que estar fuera del Pjax
		echo Html::checkboxButtonGroup(
			'exportColumns',[0,1,2,3],
			[0=>'Portón', 1=>'Usuario',2=>'Fecha',3=>'Texto'],
			['class'=>'pull-right button-group-sm',
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


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'id'=>'grilla',
        
        
		'panel'=>[
			'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> '.$this->title.'</h3>',
			'type'=>'default',
			//'before'=>'&nbsp;', // para que dibuje el toolbar (cuac)
		 ],
		 	
		'condensed'=>true, 

		// set a label for default menu
		
		'export' => [
			'label' => 'Página',
			'fontAwesome' => true,
		    'showConfirmAlert'=>false,				
		],
		
		'toolbar' => [
			'{export}',
			'{toggleData}'
		],
		
		'exportConfig' => [
			GridView::PDF => [],
			GridView::EXCEL => [],
			GridView::CSV => [],
		],		
		
		//'toggleDataContainer' => ['class' => 'btn-group-sm'],
		//'exportContainer' => ['class' => 'btn-group-sm'],	
		
		//'tableOptions'=>['class'=>'skip-export-txt'],   
        
        
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
