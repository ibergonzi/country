<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Comentarios */

$this->title = 'Nuevo comentario';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Comentarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comentarios-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_formajax', [
        'model' => $model,
    ]) ?>

</div>
