<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use kartik\icons\Icon;
Icon::map($this, Icon::FA);

/* @var $this yii\web\View */
/* @var $model frontend\models\Libro */

$this->title = 'Detalle de entrada de Libro de guardia';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Libro de guardia'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="libro-view">

    <h3><?= Html::encode($this->title) ?></h3>
	<?php
		if (!$pdf) {
			echo '<p>';
			echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['pdf', 'id' => $model->id], [
				'class' => 'btn btn-default',//'target'=>'_blank',
				]);					
			echo '</p>';	

		}	
	?>
	
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'texto',
            'idporton',
            'userCreatedBy.username',
            'created_at:datetime',
            'userUpdatedBy.username',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
