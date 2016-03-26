<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\Alert;

$this->title = 'Cambio de clave de acceso';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('$("#resetpasswordform-password").focus()', yii\web\View::POS_READY);
?>
<div class="site-reset-password">
	<?php echo Alert::widget() ?>	
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Elija su nueva clave de acceso:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Aceptar', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
