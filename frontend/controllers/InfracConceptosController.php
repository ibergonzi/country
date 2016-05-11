<?php

namespace frontend\controllers;

use Yii;
use frontend\models\InfracConceptos;
use frontend\models\InfracConceptosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InfracConceptosController implements the CRUD actions for InfracConceptos model.
 */
class InfracConceptosController extends Controller
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
     * Lists all InfracConceptos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InfracConceptosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InfracConceptos model.
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
     * Creates a new InfracConceptos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InfracConceptos();
        $model->estado=InfracConceptos::ESTADO_ACTIVO;
        

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->es_multa==InfracConceptos::NO) {
				$model->multa_precio=0;
				$model->multa_reincidencia=InfracConceptos::NO;
				$model->multa_reinc_porc=0;
				$model->multa_reinc_dias=0;
				$model->multa_personas=InfracConceptos::NO;
				$model->multa_personas_precio=0;	

			} else {
				$model->dias_verif=0;
			}
			$model->save(false);	
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing InfracConceptos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->es_multa==InfracConceptos::NO) {
				$model->multa_precio=0;
				$model->multa_reincidencia=InfracConceptos::NO;
				$model->multa_reinc_porc=0;
				$model->multa_reinc_dias=0;
				$model->multa_personas=InfracConceptos::NO;
				$model->multa_personas_precio=0;	

			} else {
				$model->dias_verif=0;
			}
			$model->save(false);				
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing InfracConceptos model.
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
     * Finds the InfracConceptos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InfracConceptos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InfracConceptos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
