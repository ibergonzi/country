<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\InfracUnidades */

$this->title = 'Crear Unidad de infracciones';
$this->params['breadcrumbs'][] = ['label' => 'Unidades de infracciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="infrac-unidades-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
