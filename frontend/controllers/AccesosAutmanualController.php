<?php

namespace frontend\controllers;

use Yii;
use frontend\models\AccesosAutmanual;
use frontend\models\AccesosAutmanualSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;

/**
 * AccesosAutmanualController implements the CRUD actions for AccesosAutmanual model.
 */
class AccesosAutmanualController extends Controller
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
                    'update' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create','update'],
                        'allow' => true,
                        'roles' => ['altaModificarAutManualAccesos'], 
                    ],     
                    [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['accederAutManualAccesos'], 
                    ],                         
                 ], // fin rules
             ], // fin access                            
        ];
    }

    /**
     * Lists all AccesosAutmanual models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccesosAutmanualSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccesosAutmanual model.
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
     * Creates a new AccesosAutmanual model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccesosAutmanual();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AccesosAutmanual model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		/*
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
        */
        $model = $this->findModel($id); 
        if ($model->estado==AccesosAutmanual::ESTADO_ABIERTO) {
			$model->estado=AccesosAutmanual::ESTADO_CERRADO;
		} else {
			$model->estado=AccesosAutmanual::ESTADO_ABIERTO;			
		}     
		$model->save();  
        return $this->redirect(['view', 'id' => $model->id]);		
    }

    /**
     * Deletes an existing AccesosAutmanual model.
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
     * Finds the AccesosAutmanual model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccesosAutmanual the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccesosAutmanual::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
