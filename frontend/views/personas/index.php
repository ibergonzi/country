<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\Pjax;

use frontend\models\Personas;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PersonasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Personas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personas-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php 
	Pjax::begin();
	//Yii::trace($searchModel);
?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
				'attribute'=>'id',
					'options'=>['style'=>'width:55px',], 
				
            ],
            'apellido',
            'nombre',
            'nombre2',
            [
				'attribute'=>'tipoDoc',
				'value'=>'tipoDoc.desc_tipo_doc_abr',
				'filter'=>$searchModel->listaTiposdoc,
            ],
            //'id_tipo_doc',
            'nro_doc',
            // 'foto',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            // 'motivo_baja',
            [
				'attribute'=>'estado',
                'value'=>function($data) {return Personas::getEstados($data->estado);},
                'filter'=>$searchModel->estados,
            ],           

           ['class' => 'yii\grid\ActionColumn',
             'header'=>Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['create'], 
                                ['class' => 'btn-sm btn-primary',
                                 'title' => Yii::t('app', 'Alta de persona'),]),

             'headerOptions'=>['style'=>'text-align:center'],   
             'options'=>['style'=>'width:70px'],         
            ],
        ],
    ]); 
    ?>

<?php Pjax::end(); ?>
</div>
