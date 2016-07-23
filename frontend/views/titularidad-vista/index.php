<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use frontend\models\Uf;
use frontend\models\UfTitularidadPersonas;

use kartik\popover\PopoverX;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TitularidadVistaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expensas';
$this->params['breadcrumbs'][] = $this->title;


use app\assets\ExportSelectorAsset;
ExportSelectorAsset::register($this);
$this->registerCss('
.kv-grid-loading {
    opacity: 0.5;
    background: #ffffff url("../images/loading.gif") top center no-repeat !important;
}
');
?>
<div class="titularidad-vista-index">

    <h3><?php 
    	global $totSup;		
		$totSup=Uf::getSuperficieTotal();
		echo Html::encode($this->title .' ('. yii::$app->formatter->asDecimal($totSup,2).' m2)');
	?></h3>
	
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
            //'id',
            //'id_titularidad',
            //'id_uf',
            [
   				'attribute'=>'id_uf',
   				'group'=>true,
            ],
            //'desc_movim_uf',
            /*
            [
				'attribute'=>'desc_movim_uf',
				'group'=>true,
				'subGroupOf'=>0,
				'format' => 'raw',
				'value' => function ($model, $index, $widget) {
					return Html::a($model->desc_movim_uf, 
								Yii::$app->urlManager->createUrl(
										['uf-titularidad/view', 
										 'id' => $model->id_titularidad
										]),
										['title' => 'Ver detalle',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0',
										]);
					},				
            ],
            */
            /*
            [
				'attribute'=>'fec_desde',
				'group'=>true,
				'subGroupOf'=>0,
			],
            [
				'attribute'=>'fec_hasta',
				'group'=>true,
				'subGroupOf'=>0,
			],
			*/
            [
				'attribute'=>'superficie',
				'format'=>['decimal',2],	
				'hAlign'=>'right',
				'group'=>true,
				'subGroupOf'=>0,							
            ],
            [
				'class' => '\kartik\grid\FormulaColumn',
				'value' => function ($model, $key, $index, $widget) {
					if ($model->unidad_estado==0) {return null;}
					$p = compact('model', 'key', 'index');
					// Write your formula below
					global $totSup;
					return $widget->col(1, $p) / $totSup * 100;
				},
				'format'=>['decimal',3],
				'label'=>'Coeficiente',
				'hAlign'=>'right',
				'group'=>true,
				'subGroupOf'=>0,				
			 ],  
            [
				'attribute'=>'exp_telefono',
				'group'=>true,
				'subGroupOf'=>0,
			],
            [
				'attribute'=>'exp_direccion',
				'group'=>true,
				'subGroupOf'=>0,
			],
            [
				'attribute'=>'exp_localidad',
				'group'=>true,
				'subGroupOf'=>0,
			],
            [
				'attribute'=>'exp_email',
				'format'=>'email',
				'group'=>true,
				'subGroupOf'=>0,
			],			                       
			[
				'attribute'=>'tipo',
				'value'=>function ($model) {return UfTitularidadPersonas::getTipos($model->tipo);},
				'filter'=>UfTitularidadPersonas::getTipos(),
			],
            //'id_persona',
            'apellido',
            'nombre',
            'nombre2',
            'desc_tipo_doc_abr',
            'nro_doc',
            'observaciones',

            //['class' => 'yii\grid\ActionColumn'],
        ];
		if (\Yii::$app->user->can('exportarListaUf')) {        
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
			'defaultPageSize'=>\Yii::$app->params['uf.defaultPageSize'],
			'sizes'=>\Yii::$app->params['uf.sizes'],
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

		echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => 'select[name="per-page"]',  
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,],  
		        
        'options'=>['id'=>'gridExpensas'],
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

    ]); 
    ?>
</div>
