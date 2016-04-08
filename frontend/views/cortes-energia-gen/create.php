<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\CortesEnergiaGen */

$this->title = 'Alta de novedad';
$this->params['breadcrumbs'][] = ['label' => 'Cortes de energía', 'url' => ['cortes-energia/index']];
$this->params['breadcrumbs'][] = ['label' => 'Novedades de generadores', 'url' => ['cortes-energia-gen/index','idParent'=>$parent->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cortes-energia-gen-create">

    <h3><?= Html::encode($this->title . ' ' . 
		Yii::$app->formatter->asDatetime($parent->hora_desde) .
		' - '. 
		Yii::$app->formatter->asTime($parent->hora_hasta)) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
