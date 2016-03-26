<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
// puesto a mano para que no aparezca el mensaje en ingles
if ($name=='Forbidden (#403)') {$name='Acceso no permitido';}
if ($name=='Not Found (#404)') {$name='Página inexistente';}


$this->title = $name;
?>
<div class="site-error">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

</div>
