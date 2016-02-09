<?php

use yii\helpers\Html;
use yii\grid\GridView;

use kartik\datecontrol\DateControl;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\LibroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Libro de guardia');
$this->params['breadcrumbs'][] = $this->title;
		
?>
<div class="libro-index">

    <h2><?= Html::encode($this->title) ?></h2>
 	
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
	<?php Pjax::begin(['id' => 'grilla', 'timeout' => false ,'clientOptions' => ['method' => 'POST'] ]); ?>	

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           ['attribute'=>'idporton',
				'options'=>['style'=>'width:55px',], 
				
            ],        
            //'created_by',
           [
                'attribute'=>'descUsuario',
                'value'=>'userCreatedBy.username',  
                'options'=>['style'=>'width:140px',],  
           ],                   
 
            //'created_at',
            ['attribute'=>'created_at',
             'options'=>['style'=>'width:160px',],             
             'format'=>['datetime'],
            
             'filter' => DateControl::widget([
				'model'=>$searchModel,
				'attribute'=>'created_at',
				'type' =>DateControl::FORMAT_DATE,
                'options' => [
						'pluginEvents'=>[ 'clearDate'=>'function(e) { $.pjax.reload({container:"#grilla"});}',
										],	

					],
                
				])
				
            ],            
            'texto',
            // 'updated_by',
            // 'updated_at',

           ['class' => 'yii\grid\ActionColumn',
             'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['create'], 
                                ['class' => 'btn btn-sm btn-primary',
                                 'title' => Yii::t('app', 'Agregar texto'),]),

             'headerOptions'=>['style'=>'text-align:center'],   
             'options'=>['style'=>'width:70px'],   
             'template' => '{view} {comentario}',  
  			 'buttons' => [
				'comentario' => function ($url, $model) {
					//$c=Comentarios::getComentarioByModelId($model->className(),$model->id);
					$c=null;
					$text='<span class="glyphicon glyphicon-copyright-mark"';
					if ($c !== null) {
						$text.=' style="color:#FF8000"></span>';
						$titl='Ver/modificar comentario';
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
					//$c=Comentarios::getComentarioByModelId($model->className(),$model->id);
					$c=null;
					if ($c == null) {
						$url=Yii::$app->urlManager->createUrl(
								['comentarios/create-ajax',
									'modelName'=>$model->className(),
									'modelID'=>$model->id]);
					} else {
						$url=Yii::$app->urlManager->createUrl(
								['comentarios/update',
									'id'=>$c->id]);
					}			
					return $url;
				 }
						 

			  }
      
            ],
        ],
    ]); ?>

<?php Pjax::end(); ?>

<?php	
	Modal::begin(['id'=>'modalcomentarionuevo',
		'header'=>'<span class="btn-warning">&nbsp;Nuevo comentario&nbsp;</span>']);
		echo '<div id="divcomentarionuevo"></div>';
	Modal::end();    
?>


</div>
