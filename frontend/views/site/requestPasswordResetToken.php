<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\Alert;

$this->title = 'Solicitar cambio de clave de acceso';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('$("#passwordresetrequestform-email").focus()', yii\web\View::POS_READY);
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Especifique su dirección de correo. Se le enviará una página para cambiar su clave de acceso.</p>
    <p><i>Importante:</i> es posible que su servidor de correo identifique a este mail como "no seguro", por favor, 
    además de su casilla de entradas, revise también en "Spam".
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email') ?>

                <div class="form-group">
                    <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
