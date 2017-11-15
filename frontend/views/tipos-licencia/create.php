<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\TiposLicencia */

$this->title = 'Crear Tipo de Licencia de conducir';
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Licencia de conducir', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipos-licencia-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
