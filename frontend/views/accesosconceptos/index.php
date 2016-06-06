<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use frontend\models\AccesosConceptos;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AccesosConceptosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Conceptos de accesos');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
.kv-grid-loading {
    opacity: 0.5;
    background: #ffffff url("../images/loading.gif") top center no-repeat !important;
}
');

?>
<div class="accesos-conceptos-index">

    <h3><?= Html::encode($this->title) ?></h3>



    <?php
     $columns=[
            'id',
            'concepto',
            [
				'attribute'=>'req_tarjeta',
				'value'=>function ($model) { return AccesosConceptos::getSiNo($model->req_tarjeta);},
				'filter'=>AccesosConceptos::getSiNo()
            ],     
            [
				'attribute'=>'req_seguro',
				'value'=>function ($model) { return AccesosConceptos::getSiNo($model->req_seguro);},
				'filter'=>AccesosConceptos::getSiNo()
            ],   
            [
				'attribute'=>'estado',  
				'value'=>function ($model) { return AccesosConceptos::getEstados($model->estado);},
				'filter'=>AccesosConceptos::getEstados()				         
            ],   
           ['class' => 'kartik\grid\ActionColumn',
             'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['create'], 
                                ['class' => 'btn-sm btn-primary',
                                 'title' => Yii::t('app', 'Nuevo concepto'),]),
 			 'template' => '{view}', 
 			 ],     
     ];
     echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,],  
		        
        'options'=>['id'=>'gridConceptos'],
        'columns' => $columns,
		'condensed'=>true, 
    ]); ?>

</div>
