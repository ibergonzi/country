<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidad */

$this->title = 'Modif.mov.de titularidad';
$this->params['breadcrumbs'][] = ['label' => 'Unidades funcionales', 'url' => ['uf/index']];
$this->params['breadcrumbs'][] = ['label' => 'Movs.Titularidad U.F.'.$model->id_uf, 
									'url' => ['uf-titularidad/index','uf'=>$model->id_uf]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uf-titularidad-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_update', [
        'model' => $model,
    ]) ?>

</div>
