<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\TitularidadVista;

/**
 * TitularidadVistaSearch represents the model behind the search form about `frontend\models\TitularidadVista`.
 */
class TitularidadVistaSearch extends TitularidadVista
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'desc_movim_uf', 'fec_desde', 'fec_hasta', 'exp_telefono', 'exp_direccion', 'exp_localidad', 'exp_email', 'tipo', 'apellido', 'nombre', 'nombre2', 'desc_tipo_doc_abr', 'nro_doc', 'observaciones'], 'safe'],
            [['id_titularidad', 'id_uf', 'id_persona'], 'integer'],
            [['superficie', ], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($ultima=true,$params)
    {

		if ($ultima) {
			$query = TitularidadVista::find()->where(['ultima'=>true]);			
		} else {
			$query = TitularidadVista::find();
		}		
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['uftitularidad.defaultPageSize'];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],            
            'sort' => ['defaultOrder' => ['id' => SORT_ASC,],
						'enableMultiSort'=>true,            
                      ],             
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_titularidad' => $this->id_titularidad,
            'id_uf' => $this->id_uf,
            'fec_desde' => $this->fec_desde,
            'fec_hasta' => $this->fec_hasta,
            'id_persona' => $this->id_persona,
            'superficie' => $this->superficie,

        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'desc_movim_uf', $this->desc_movim_uf])
            ->andFilterWhere(['like', 'exp_telefono', $this->exp_telefono])
            ->andFilterWhere(['like', 'exp_direccion', $this->exp_direccion])
            ->andFilterWhere(['like', 'exp_localidad', $this->exp_localidad])
            ->andFilterWhere(['like', 'exp_email', $this->exp_email])
            ->andFilterWhere(['like', 'tipo', $this->tipo])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'nombre2', $this->nombre2])
            ->andFilterWhere(['like', 'desc_tipo_doc_abr', $this->desc_tipo_doc_abr])
            ->andFilterWhere(['like', 'nro_doc', $this->nro_doc])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
