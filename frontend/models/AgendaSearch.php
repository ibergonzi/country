<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Agenda;

/**
 * AgendaSearch represents the model behind the search form about `frontend\models\Agenda`.
 */
class AgendaSearch extends Agenda
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numero'], 'integer'],
            [['nombre', 'direccion', 'localidad', 'cod_pos', 'provincia', 'pais', 'telefono', 'telefono1', 'telefono2', 'fax', 'telex', 'email', 'palabra', 'actividad'], 'safe'],
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
        $query = Agenda::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['nombre' => SORT_ASC,],
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
            'numero' => $this->numero,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'localidad', $this->localidad])
            ->andFilterWhere(['like', 'cod_pos', $this->cod_pos])
            ->andFilterWhere(['like', 'provincia', $this->provincia])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'telefono1', $this->telefono1])
            ->andFilterWhere(['like', 'telefono2', $this->telefono2])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'telex', $this->telex])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'palabra', $this->palabra])
            ->andFilterWhere(['like', 'actividad', $this->actividad]);

        return $dataProvider;
    }
}
