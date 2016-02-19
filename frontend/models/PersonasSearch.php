<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Personas;

/**
 * PersonasSearch represents the model behind the search form about `\frontend\models\Personas`.
 */
class PersonasSearch extends Personas
{
	
	public $tipoDoc; // usa la descripción abreviada del tipo de documento
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_tipo_doc', 'created_by', 'updated_by'], 'integer'],
            [['apellido', 'nombre', 'nombre2', 'nro_doc', 'foto', 'created_at', 
				'updated_at', 'motivo_baja','estado','tipoDoc'], 'safe'],
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
        $query = Personas::find()->joinWith('tipoDoc'); // se usa el nombre de la relación en Personas

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                          'id' => SORT_DESC, 
                          ]
                      ],            
        ]);
        
        // Agregado a mano, para que incluya el ordenamiento por tipo de documento
        $dataProvider->sort->attributes['tipoDoc'] = [
            'asc' => ['tiposdoc.desc_tipo_doc_abr' => SORT_ASC],
            'desc' => ['tiposdoc.desc_tipo_doc_abr' => SORT_DESC],
        ];
        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_tipo_doc' => $this->tipoDoc, // se usa el campo agregado (viene con el valor en el filter) en vez de $this->id_tipo_doc,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'estado' => $this->estado,

            
        ]);

        $query->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'nombre2', $this->nombre2])
            ->andFilterWhere(['like', 'nro_doc', $this->nro_doc])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja])
            ;

        return $dataProvider;
    }
}
