<?php

namespace frontend\controllers;

use Yii;

use frontend\models\Autorizados;
use frontend\models\AutorizadosHorarios;
use frontend\models\AutorizadosHorariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
// para aplicar RBAC
use yii\filters\AccessControl;

/**
 * AutorizadosHorariosController implements the CRUD actions for AutorizadosHorarios model.
 */
class AutorizadosHorariosController extends Controller
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
            /*
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['borrar'], 
                    ],                
                    [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['acceder'], 
                    ],
                    [
                        'actions' => ['create','update'],
                        'allow' => true,
                        'roles' => ['altaModificar'], 
                    ],
                 ], // fin rules
            ], // fin access   
            */             
        ];
    }

    /**
     * Lists all AutorizadosHorarios models.
     * @return mixed
     */
    public function actionIndex($idParent)
    {
        $searchModel = new AutorizadosHorariosSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

       // agregado a mano, cuando se quiere filtrar por un valor por defecto 
        // (en este caso traer los registros de AutorizadosHorarios relacionados con la
        // autorizacion que se eligio en la pagina anterior) se debe hacer asi:
        // crear un array (juntando lo que viene por request con un array vacio)
        // y modificar el valor que nos interesa a mano
        $q = array_merge([],Yii::$app->request->queryParams);
        $q["AutorizadosHorariosSearch"]["id_autorizado"] = $idParent ;
        $dataProvider = $searchModel->search($q);

        // obtengo los datos de la cabecera    
   		$parent= Autorizados::findOne($idParent);	        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'parent'=>$parent,
        ]);
    }

    /**
     * Displays a single AutorizadosHorarios model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model=$this->findModel($id);
		$parent= Autorizados::findOne($model->id_autorizado);			
        return $this->render('view', [
            'model' => $model,
            'parent'=> $parent
        ]);        
    }

    /**
     * Creates a new AutorizadosHorarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idParent)
    {
        $model = new AutorizadosHorarios();
        $model->id_autorizado=$idParent;
        
        $parent=Autorizados::findOne($idParent);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'parent'=> $parent,
            ]);
        }
    }

    /**
     * Updates an existing AutorizadosHorarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $parent = Autorizados::findOne($model->id_autorizado);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'parent'=> $parent,
            ]);
        }
    }

    /**
     * Deletes an existing AutorizadosHorarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		$parent= Autorizados::findOne($model->id_autorizado);        
        
        if ($model->load(Yii::$app->request->post())) {
			$model->estado=Autorizados::ESTADO_BAJA;
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
     * Finds the AutorizadosHorarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AutorizadosHorarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AutorizadosHorarios::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
