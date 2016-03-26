<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\Alert;

$this->title = 'Solicitar cambio de clave de acceso';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
	<?php echo Alert::widget() ?>	
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Especifique su dirección de correo. Se le enviará una página para cambiar su clave de acceso.</p>

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
