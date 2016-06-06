<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AccesosConceptos */

$this->title = Yii::t('app', 'Nuevo concepto de acceso');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Conceptos de accesos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesos-conceptos-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
