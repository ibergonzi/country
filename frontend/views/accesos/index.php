<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use kartik\datecontrol\DateControl;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;

use frontend\models\AccesosConceptos;
use frontend\models\AccesosVistaF;

use kartik\popover\PopoverX;

use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AccesosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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

?>
<div class="accesos-index">

    
    <?php 

		if (\Yii::$app->session->get('accesosFecDesde')) {
			$lbl=Html::tag('span','',['class'=>'glyphicon glyphicon-warning-sign','style'=>'color:#FF8000']).
				'  '.
				'Filtro por fecha desde el ' . Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecDesde')) .
				' hasta el ' . Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecHasta'));

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
		
		// para evitar que la pagina se cuelgue cuando se le saca la paginación y hay muchos registros a mostrar
		//if ($dataProvider->totalCount <= 200) {
			$toolbar=[
				'{export}',
				'{toggleData}'
			];
		//} else {
		//	$toolbar=['{export}'];
		//}
		
		

	
		
		if (\Yii::$app->session->get('accesosFecDesde')) {
			$lbl2=' ('.Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecDesde')) .
						'-' . Yii::$app->formatter->asDate(\Yii::$app->session->get('accesosFecHasta')) . ')';
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
		
		// las columnas se definen fuera del gridview para poder extraer las etiquetas para el 
		// popover que define las columnas a exportar	
		$columns=[
            'id_acceso',
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
            'id_persona',
            'r_apellido',
            'r_nombre',
            'r_nombre2',
            'r_nro_doc',
            'ing_id_vehiculo',
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
               
            'id_autorizante',
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
            'egr_id_vehiculo',
            'r_egr_patente',
            'r_egr_marca',
            'r_egr_modelo',
            'r_egr_color',
            'egr_id_porton',
            'r_egr_usuario',
            'control',
            'estado',
            'motivo_baja',
            ['class' => 'kartik\grid\ActionColumn'],
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


	Pjax::begin(['id' => 'grilla', 'timeout' => false ,
		'enablePushState' => false,
		'clientOptions' => ['method' => 'GET'] ]);    
		 
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        // Para que muestre todo el gridview, solo aplicable a kartik, el de yii anda bien
        'containerOptions' => ['style'=>'overflow: visible'], 
		'condensed'=>false,
		'resizableColumns'=>false,
		//'floatHeader'=>true,	
		//'bordered'=>false,

		
		'layout'=>'&nbsp;{toolbar}{summary}{items}{pager}',
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
		
        'columns' => $columns,
            

            
 
    ]); 
    Pjax::end();
	?>
</div>
