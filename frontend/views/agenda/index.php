<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AgendaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agenda';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
.kv-grid-loading {
    opacity: 0.5;
    background: #ffffff url("../images/loading.gif") top center no-repeat !important;
}
');
$this->registerCss('
.fade {
  opacity: 0;
  -webkit-transition: opacity 0s linear;
       -o-transition: opacity 0s linear;
          transition: opacity 0s linear;
}
.modal.fade .modal-dialog {
  -webkit-transition: -webkit-transform 0s ease-out;
       -o-transition:      -o-transform 0s ease-out;
          transition:         transform 0s ease-out;

}
.nofade {
   transition: none;
}
.panel-heading {
  padding: 0px 5px;
  border-bottom: 1px solid transparent;
  border-top-left-radius: 2px;
  border-top-right-radius: 2px;
}
.table-condensed > thead > tr > th,
.table-condensed > tbody > tr > th,
.table-condensed > tfoot > tr > th,
.table-condensed > thead > tr > td,
.table-condensed > tbody > tr > td,
.table-condensed > tfoot > tr > td {
  padding: 1px;
}
');
?>
<div class="agenda-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php
	
	$columns=[
            //'numero',
            'nombre',
            'direccion',
            'localidad',
            'cod_pos',
            'provincia',
            'pais',
            'telefono',
            'telefono1',
            'telefono2',
            'fax',
            //'telex',
            'email',
            'palabra',
            'actividad',

            ['class' => 'kartik\grid\ActionColumn',
             'template' => '{update} {delete}',
             'noWrap'=>true,
             'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['create'], 
                                ['class' => 'btn-sm btn-primary',
                                 'title' => Yii::t('app', 'Nuevo nombre'),]),             
            ],
        ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,],  
        // Para que muestre todo el gridview, solo aplicable a kartik, el de yii anda bien
        'containerOptions' => ['style'=>'overflow: visible'], 		        
        'options'=>['id'=>'gridAgenda'],
        'columns' => $columns,
		'condensed'=>true, 
		//'layout'=>'&nbsp;{toolbar}{summary}{items}{pager}',
		'pager' => [
			'firstPageLabel' => true,
			'lastPageLabel' => true,
		],			
    ]); ?>
</div>
