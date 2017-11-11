<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\PersonasLicencias */

$this->title = 'Create Personas Licencias';
$this->params['breadcrumbs'][] = ['label' => 'Personas Licencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personas-licencias-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
