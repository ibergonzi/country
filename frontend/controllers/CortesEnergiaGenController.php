<?php

namespace frontend\controllers;

use Yii;
use frontend\models\CortesEnergiaGen;
use frontend\models\CortesEnergiaGenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\models\CortesEnergia;

/**
 * CortesEnergiaGenController implements the CRUD actions for CortesEnergiaGen model.
 */
class CortesEnergiaGenController extends Controller
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
            // usa los mismos permisos que CortesEnergiaController
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
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['accederCortesStartStop'], 
                    ],                    
                  ], // fin rules
             ], // fin access            
        ];
    }

    /**
     * Lists all CortesEnergiaGen models.
     * @return mixed
     */
    public function actionIndex($idParent)
    {
        $searchModel = new CortesEnergiaGenSearch();
        
       // agregado a mano, cuando se quiere filtrar por un valor por defecto 
        // (en este caso traer los registros de CortesEnergiaGen relacionados con el 
        // corte de energia que se eligio en la pagina anterior) se debe hacer asi:
        // crear un array (juntando lo que viene por request con un array vacio)
        // y modificar el valor que nos interesa a mano
        $q = array_merge([],Yii::$app->request->queryParams);
        $q["CortesEnergiaGenSearch"]["id_cortes_energia"] = $idParent ;
        $dataProvider = $searchModel->search($q);
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // obtengo los datos de la cabecera    
   		$parent= CortesEnergia::findOne($idParent);	        
        
         return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'parent'=>$parent
        ]);
    }

    /**
     * Displays a single CortesEnergiaGen model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model=$this->findModel($id);
		$parent= CortesEnergia::findOne($model->id_cortes_energia);			
        return $this->render('view', [
            'model' => $model,
            'parent'=> $parent
        ]);
    }

    /**
     * Creates a new CortesEnergiaGen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idParent)
    {
        $model = new CortesEnergiaGen();
        $model->id_cortes_energia=$idParent;
        // obtengo los datos de la cabecera    
   		$parent= CortesEnergia::findOne($idParent);	        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'parent'=>$parent
            ]);
        }
    }

    /**
     * Updates an existing CortesEnergiaGen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$parent= CortesEnergia::findOne($model->id_cortes_energia);	        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'parent'=> $parent
            ]);
        }
    }

    /**
     * Deletes an existing CortesEnergiaGen model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       
        $model = $this->findModel($id);
		$parent= CortesEnergia::findOne($model->id_cortes_energia);        
        
        if ($model->load(Yii::$app->request->post())) {
			$model->estado=CortesEnergiaGen::ESTADO_BAJA;
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } else {
            return $this->render('delete', [
                'model' => $model,
                'parent'=> $parent,
            ]);
        }       		        
    }

    /**
     * Finds the CortesEnergiaGen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CortesEnergiaGen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CortesEnergiaGen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
