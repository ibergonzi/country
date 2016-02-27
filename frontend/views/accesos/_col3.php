<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

?>
<div class="accesos-col3">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_persona',
            'ing_id_vehiculo',
            'ing_fecha',
            'ing_hora',
            'ing_id_porton',
            'ing_id_user',
            'egr_id_vehiculo',
            'egr_fecha',
            'egr_hora',
            'egr_id_porton',
            'egr_id_user',
            'id_concepto',
            'motivo',
            'cant_acomp',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'estado',
            'motivo_baja',
        ],
    ]) ?>

</div>
