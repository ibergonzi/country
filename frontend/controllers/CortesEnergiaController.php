<?php

namespace frontend\controllers;

use Yii;
use frontend\models\CortesEnergia;
use frontend\models\CortesEnergiaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\db\Expression;

/**
 * CortesEnergiaController implements the CRUD actions for CortesEnergia model.
 */
class CortesEnergiaController extends Controller
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
                        'roles' => ['borrarAcceso'], 
                    ],    
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['modificarCorte'], 
                    ],                                  
                    [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['accederConsCortes'], 
                    ],
                    [
                        'actions' => ['start-stop'],
                        'allow' => true,
                        'roles' => ['accederCortesStartStop'], 
                    ],

                 ], // fin rules
             ], // fin access            
        ];
    }

	public function actionStartStop()
	{
		$model=CortesEnergia::corteActivo();
		if (!$model) {$model = new CortesEnergia();}
		
	
		if (isset(Yii::$app->request->post()['accion'])) {
			if ($model->isNewRecord) {
				$model->hora_desde=new Expression('CURRENT_TIMESTAMP');
			} else {
				$model->hora_hasta=new Expression('CURRENT_TIMESTAMP');				
			}
			$model->estado=CortesEnergia::ESTADO_ACTIVO;
			$model->save();	
			return $this->redirect(['index']);		
		}		
        return $this->render('startstop', ['model' => $model,]);
		
	}

    /**
     * Lists all CortesEnergia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CortesEnergiaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    


    /**
     * Displays a single CortesEnergia model.
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
     * Creates a new CortesEnergia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CortesEnergia();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CortesEnergia model.
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
     * Deletes an existing CortesEnergia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
			$model->estado=CortesEnergia::ESTADO_BAJA;
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } else {
            return $this->render('delete', [
                'model' => $model,
            ]);
        }          
    }

    /**
     * Finds the CortesEnergia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CortesEnergia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CortesEnergia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
