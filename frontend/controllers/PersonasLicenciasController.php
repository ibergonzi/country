<?php

namespace frontend\controllers;

use Yii;
use frontend\models\PersonasLicencias;
use frontend\models\PersonasLicenciasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
// para aplicar RBAC
use yii\filters\AccessControl;

/**
 * PersonasLicenciasController implements the CRUD actions for PersonasLicencias model.
 */
class PersonasLicenciasController extends Controller
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
     * Lists all PersonasLicencias models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonasLicenciasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PersonasLicencias model.
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
     * Creates a new PersonasLicencias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PersonasLicencias();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PersonasLicencias model.
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
     * Deletes an existing PersonasLicencias model.
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
     * Finds the PersonasLicencias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PersonasLicencias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PersonasLicencias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
