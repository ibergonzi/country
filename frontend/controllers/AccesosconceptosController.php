<?php

namespace frontend\controllers;

use Yii;
use frontend\models\AccesosConceptos;
use frontend\models\AccesosConceptosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;

/**
 * AccesosconceptosController implements the CRUD actions for AccesosConceptos model.
 */
class AccesosconceptosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete','create','update'],
                        'allow' => true,
                        'roles' => ['modificarParametros'], 
                    ],                
                    [
                        'actions' => ['index','view',],
                        'allow' => true,
                        'roles' => ['accederParametros'],
                    ],
                 ], // fin rules
                
             ], // fin access               
        ];
    }

    /**
     * Lists all AccesosConceptos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccesosConceptosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccesosConceptos model.
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
     * Creates a new AccesosConceptos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccesosConceptos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AccesosConceptos model.
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
     * Deletes an existing AccesosConceptos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		/*
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
        */
       $model = $this->findModel($id);  
       $model->estado=($model->estado==AccesosConceptos::ESTADO_ACTIVO)?AccesosConceptos::ESTADO_INACTIVO:AccesosConceptos::ESTADO_ACTIVO;
       $model->save();
       return $this->redirect(['view', 'id' => $model->id]);              
    }

    /**
     * Finds the AccesosConceptos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccesosConceptos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccesosConceptos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
