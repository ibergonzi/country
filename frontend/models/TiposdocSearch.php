<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Tiposdoc;

/**
 * TiposdocSearch represents the model behind the search form about `\frontend\models\Tiposdoc`.
 */
class TiposdocSearch extends Tiposdoc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['desc_tipo_doc', 'desc_tipo_doc_abr','persona_fisica'], 'safe'],
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
        $query = Tiposdoc::find();

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
            'persona_fisica'=>$this->persona_fisica
        ]);

        $query->andFilterWhere(['like', 'desc_tipo_doc', $this->desc_tipo_doc])
            ->andFilterWhere(['like', 'desc_tipo_doc_abr', $this->desc_tipo_doc_abr]);

        return $dataProvider;
    }
}
