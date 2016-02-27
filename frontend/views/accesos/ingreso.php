<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

$this->title = Yii::t('app', 'Ingresos');
?>
<div class="accesos-ingreso">

	<div class='container'> 
	
		<div class='row'>

			<div id="col1" class="col-md-4">
				
				    <?= $this->render('_form', [
						'model' => $model,
					]) ?>
				
			</div><!-- fin div col1 -->

			<div id="col2" class="col-md-4">
			</div><!-- fin div col2 -->

			<div id="col3" class="col-md-4">
			</div><!-- fin div col3 -->


		</div>
	</div>




</div>
