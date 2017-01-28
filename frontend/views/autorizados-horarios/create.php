<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AutorizadosHorarios */

$this->title = 'Create Autorizados Horarios';
$this->params['breadcrumbs'][] = ['label' => 'Autorizados Horarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="autorizados-horarios-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
