<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Autorizados;
use frontend\models\AutorizadosSearch;

use frontend\models\Autorizantes;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
// para aplicar RBAC
use yii\filters\AccessControl;

/**
 * AutorizadosController implements the CRUD actions for Autorizados model.
 */
class AutorizadosController extends Controller
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
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['borrarAutorizados'], 
                    ],                
                    [
                        'actions' => ['index','view','list'],
                        'allow' => true,
                        'roles' => ['accederAutorizados'], 
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['altaAutorizados'], 
                    ],
                 ], // fin rules
            ], // fin access   
                         
        ];
    }


	public function actionList($idPersona) 
	{
        return $this->renderAjax('list', [
            'idPersona' => $idPersona,
        ]);
	}

    /**
     * Lists all Autorizados models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AutorizadosSearch();
        $searchModel->estado=Autorizados::ESTADO_ACTIVO;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Autorizados model.
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
     * Creates a new Autorizados model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Autorizados();


        if ($model->load(Yii::$app->request->post()) ) {
			$aut=Autorizantes::findOne($model->id_autorizante);
			$model->id_uf=$aut->id_uf;
			$model->id_autorizante=$aut->id_persona;
			$model->estado=Autorizados::ESTADO_ACTIVO;

			
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				// si no pasa el validate, hay que setear el id_autorizante con un ID de autorizante
				// ver más arriba que el id_autorizante se pisa con un ID de persona
				$model->id_autorizante=$aut->id;
				return $this->render('create', ['model' => $model,]);
			}
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

    /**
     * Updates an existing Autorizados model.
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
     * Deletes an existing Autorizados model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
			$model->estado=Autorizados::ESTADO_BAJA;
			if ($model->save(false)) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } 
		return $this->render('delete', [
			'model' => $model,
		]);
               
    }

    /**
     * Finds the Autorizados model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Autorizados the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Autorizados::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
