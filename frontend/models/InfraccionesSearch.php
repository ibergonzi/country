<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Infracciones;

/**
 * InfraccionesSearch represents the model behind the search form about `frontend\models\Infracciones`.
 */
class InfraccionesSearch extends Infracciones
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_uf', 'id_vehiculo', 'id_persona', 'id_concepto', 'id_informante', 'notificado', 'verificado', 'multa_unidad', 'multa_pers_cant', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['fecha', 'hora', 'nro_acta', 'lugar', 'descripcion', 'fecha_verif', 'foto', 'created_at', 'updated_at', 'motivo_baja'], 'safe'],
            [['multa_monto', 'multa_pers_monto', 'multa_pers_total', 'multa_total'], 'number'],
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
    public function search($params)
    {
        $query = Infracciones::find();
        
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['infracciones.defaultPageSize'];         

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],            
            'sort' => ['defaultOrder' => ['id' => SORT_DESC,],
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
            'id' => $this->id,
            'id_uf' => $this->id_uf,
            'id_vehiculo' => $this->id_vehiculo,
            'id_persona' => $this->id_persona,
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'id_concepto' => $this->id_concepto,
            'id_informante' => $this->id_informante,
            'notificado' => $this->notificado,
            'fecha_verif' => $this->fecha_verif,
            'verificado' => $this->verificado,
            'multa_unidad' => $this->multa_unidad,
            'multa_monto' => str_replace(",", ".", $this->multa_monto),
            'multa_pers_cant' => $this->multa_pers_cant,
            'multa_pers_monto' => str_replace(",", ".", $this->multa_pers_monto),
            'multa_pers_total' => str_replace(",", ".", $this->multa_pers_total),
            'multa_total' => str_replace(",", ".", $this->multa_total),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'nro_acta', $this->nro_acta])
            ->andFilterWhere(['like', 'lugar', $this->lugar])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja]);

        return $dataProvider;
    }
}
