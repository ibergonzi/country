<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Mensajes;

/**
 * MensajesSearch represents the model behind the search form about `\frontend\models\Mensajes`.
 */
class MensajesSearch extends Mensajes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'model_id', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['avisar_a', 'mensaje', 'model', 'created_at', 'updated_at'], 'safe'],
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
        $query = Mensajes::find();

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
            'model_id' => $this->model_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'avisar_a', $this->avisar_a])
            ->andFilterWhere(['like', 'mensaje', $this->mensaje])
            ->andFilterWhere(['like', 'model', $this->model]);

        return $dataProvider;
    }
}
