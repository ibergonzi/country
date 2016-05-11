<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\InfracConceptos */

$this->title = 'Crear concepto de infracciones';
$this->params['breadcrumbs'][] = ['label' => 'Conceptos de infracciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="infrac-conceptos-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
