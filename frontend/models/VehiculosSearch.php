<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Vehiculos;

/**
 * VehiculosSearch represents the model behind the search form about `frontend\models\Vehiculos`.
 */
class VehiculosSearch extends Vehiculos
{
	
	public $marca;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_marca', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['modelo', 'patente', 'color', 'created_at', 'updated_at', 'motivo_baja','marca'], 'safe'],
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
        $query = Vehiculos::find()->joinWith('vehiculoMarca'); // se usa el nombre de la relación en Vehiculos

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                          'id' => SORT_DESC, 
                          ]
                      ],                  
        ]);
        
        // Agregado a mano, para que incluya el ordenamiento por tipo de documento
        $dataProvider->sort->attributes['marca'] = [
            'asc' => ['vehiculos_marcas.desc_marca' => SORT_ASC],
            'desc' => ['vehiculos_marcas.desc_marca' => SORT_DESC],
        ];        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'vehiculos.id' => $this->id,
            'id_marca' => $this->marca,  // se usa el campo agregado (viene con el valor en el filter) en vez de $this->id_marca,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'patente', $this->patente])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja]);

        return $dataProvider;
    }
}
