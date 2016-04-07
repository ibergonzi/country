<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\CortesEnergia */

$this->title = 'Create Cortes Energia';
$this->params['breadcrumbs'][] = ['label' => 'Cortes Energias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cortes-energia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
