<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TitularidadVistaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Titularidad Vistas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="titularidad-vista-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Titularidad Vista', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //'id',
            //'id_titularidad',
            'id_uf',
            //'desc_movim_uf',
            [
				'attribute'=>'desc_movim_uf',
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
            'fec_desde',
            'fec_hasta',
            'exp_telefono',
            'exp_direccion',
            'exp_localidad',
            'exp_email:email',
            'tipo',
            'id_persona',
            'apellido',
            'nombre',
            'nombre2',
            'desc_tipo_doc_abr',
            'nro_doc',
            'superficie',
            'coeficiente',
            'observaciones',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
