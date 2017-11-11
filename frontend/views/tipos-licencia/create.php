<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\TiposLicencia */

$this->title = 'Create Tipos Licencia';
$this->params['breadcrumbs'][] = ['label' => 'Tipos Licencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipos-licencia-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
