<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AccesosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Accesos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesos-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'id_persona',
            'ing_id_vehiculo',
            'ing_fecha',
            'ing_hora',
            'ing_id_porton',
            //'ing_id_user',


 		    [
				'attribute'=>'descUsuarioIng',
				'value'=>'userIngreso.username',  
		    ],
           
            'egr_id_vehiculo',
            'egr_fecha',
            'egr_hora',
            'egr_id_porton',
            //'egr_id_user',
 		    [
				'attribute'=>'descUsuarioEgr',
				'value'=>'userEgreso.username',  
		    ],                
            'id_concepto',
            'motivo',
            'cant_acomp',
            //'created_by',
            //'created_at',
            //'updated_by',
            //'updated_at',
            'estado',
            'motivo_baja',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
