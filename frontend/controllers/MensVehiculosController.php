<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MensVehiculos;
use frontend\models\MensVehiculosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
// para aplicar RBAC
use yii\filters\AccessControl;

/**
 * MensVehiculosController implements the CRUD actions for MensVehiculos model.
 */
class MensVehiculosController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			/*
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            */
            
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                     [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['accederMensVehiculos'], 
                    ],

                 ], // fin rules
            ], // fin access   
                         
        ];
    }

    /**
     * Lists all MensVehiculos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MensVehiculosSearch();
        
        // Trae inicialmente todos los registros activos
        $searchModel->estado = MensVehiculos::ESTADO_ACTIVO;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MensVehiculos model.
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
     * Creates a new MensVehiculos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MensVehiculos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MensVehiculos model.
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
     * Deletes an existing MensVehiculos model.
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
     * Finds the MensVehiculos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MensVehiculos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MensVehiculos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
