<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\UfTitularidad;

/**
 * UfTitularidadSearch represents the model behind the search form about `frontend\models\UfTitularidad`.
 */
class UfTitularidadSearch extends UfTitularidad
{
	
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_uf', 'tipo_movim', 'created_by', 'updated_by', 'estado', 'ultima'], 'integer'],
            [['fec_desde', 'fec_hasta', 'exp_telefono', 'exp_direccion', 'exp_localidad', 'exp_email', 'created_at', 'updated_at', 'motivo_baja'], 'safe'],
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
			$query = UfTitularidad::find()->where(['ultima'=>true]);			
		} else {
			$query = UfTitularidad::find();
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

        // Agregado a mano, para que incluya el ordenamiento por tipo de documento
        $dataProvider->sort->attributes['tipoMovim'] = [
            'asc' => ['movim_uf.desc_movim_uf' => SORT_ASC],
            'desc' => ['movim_uf.desc_movim_uf' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_uf' => $this->id_uf,
            'tipo_movim' => $this->tipoMovim,
            'fec_desde' => $this->fec_desde,
            'fec_hasta' => $this->fec_hasta,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'estado' => $this->estado,
            'ultima' => $this->ultima,
        ]);

        $query->andFilterWhere(['like', 'exp_telefono', $this->exp_telefono])
            ->andFilterWhere(['like', 'exp_direccion', $this->exp_direccion])
            ->andFilterWhere(['like', 'exp_localidad', $this->exp_localidad])
            ->andFilterWhere(['like', 'exp_email', $this->exp_email])
            ->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja]);

        return $dataProvider;
    }
}
