<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidadPersonas */

$this->title = 'Create Uf Titularidad Personas';
$this->params['breadcrumbs'][] = ['label' => 'Uf Titularidad Personas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uf-titularidad-personas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
