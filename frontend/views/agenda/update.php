<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Agenda */

$this->title = 'Modificación nombre';
$this->params['breadcrumbs'][] = ['label' => 'Agenda', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Modificación';
?>
<div class="agenda-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
