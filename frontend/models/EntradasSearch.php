<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Entradas;

/**
 * EntradasSearch represents the model behind the search form about `frontend\models\Entradas`.
 */
class EntradasSearch extends Entradas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idpersona', 'idvehiculo','idporton'], 'integer'],
            [['motivo'], 'safe'],
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
        $query = Entradas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idpersona' => $this->idpersona,
            'idvehiculo' => $this->idvehiculo,
            'idporton' => $this->idporton,
        ]);

        $query->andFilterWhere(['like', 'motivo', $this->motivo]);

        return $dataProvider;
    }
}