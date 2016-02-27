<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AccesosConceptos */

$this->title = Yii::t('app', 'Create Accesos Conceptos');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accesos Conceptos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesos-conceptos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
