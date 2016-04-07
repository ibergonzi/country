<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\CortesEnergiaGen */

$this->title = 'Create Cortes Energia Gen';
$this->params['breadcrumbs'][] = ['label' => 'Cortes Energia Gens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cortes-energia-gen-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
