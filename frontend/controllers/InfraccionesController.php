<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Infracciones;
use frontend\models\InfraccionesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InfraccionesController implements the CRUD actions for Infracciones model.
 */
class InfraccionesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Infracciones models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InfraccionesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Infracciones model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Infracciones model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Infracciones();

        if ($model->load(Yii::$app->request->post())) {
			$model->fecha=$model->hora;			
			$ic=$model->concepto;
			if ($ic->es_multa) {
				$model->multa_unidad=$ic->multa_unidad; 
				if ($ic->multa_reincidencia) {
					$model->multa_monto=calculaReinc($model,$ic);
				} else {
					$model->multa_monto=$ic->multa_precio;
				}
				$model->multa_pers_monto=$ic->multa_personas_precio;
				$model->multa_pers_total=($model->multa_pers_cant > 0)?$model->multa_pers_cant*$ic->multa_personas_precio:0;
				$model->multa_total=$model->multa_monto + $model->multa_pers_total;
				$model->fecha_verif=null;
				$model->verificado=true;				
			} else {
				$model->multa_unidad=null;
				$model->multa_monto=0;
				$model->multa_pers_monto=0;
				$model->multa_pers_total=0;
				$model->multa_total=0;
				if ($ic->dias_verif == 0) {
					$model->fecha_verif=null;
					$model->verificado=true;
				} else {
					$model->fecha_verif=$model->fecha;
					$model->verificado=false;					
				} 
			}

			if ($model->save()) {			
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } 
        return $this->render('create', ['model' => $model,]);
     }
     
     
     
     private function calculaReinc($model,$ic) 
     {
		// si por error se coloca un valor que es menor que 1 dia, se devuelve la multa sin reincidencias 
		if ($ic->multa_reinc_dias <= 1) { return $ic->multa_precio); }
		
		// Se calcula la fecha desde la cual se cuentan las reincidencias (desde la fecha de la multa - multa_reinc_dias)
		$fecAtrasUnix=strtotime('-' . $ic->multa_reinc_dias . ' days', strtotime($model->fecha));
		$fecAtras=date('Y-m-d',$fecAtrasUnix);
		
		// cuenta todas las multas para la unidad funcional desde la fecha calculada
		$cantMultas=Infracciones::find()->where([
				'id_uf'=>$model->id_uf,
				'estado'=>Infracciones::ESTADO_ACTIVO,
				'id_concepto'=>$model->id_concepto
				])
				->andWhere('between','fecha',$fecAtras,$model->fecha)->count();
				
		// se suma tambien la que se está grabando		
		$cantMultas=$cantMultas + 1;
		
		$monto=$ic->multa_precio+ ($ic->multa_precio * (($cantMultas-1)*$ic->multa_reinc_porc) / 100);
	 
		return $monto;
	 }

    /**
     * Updates an existing Infracciones model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Infracciones model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Infracciones model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Infracciones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Infracciones::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
