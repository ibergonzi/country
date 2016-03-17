<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "uf_titularidad_personas".
 *
 * @property integer $id
 * @property integer $uf_titularidad_id
 * @property integer $id_persona
 *
 * @property Personas $idPersona
 * @property UfTitularidad $ufTitularidad
 */
class UfTitularidadPersonas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uf_titularidad_personas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uf_titularidad_id', 'id_persona'], 'required'],
            [['uf_titularidad_id', 'id_persona'], 'integer'],
            [['id_persona', 'uf_titularidad_id'], 'unique', 'targetAttribute' => ['id_persona', 'uf_titularidad_id'], 'message' => 'The combination of Uf Titularidad ID and Id Persona has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uf_titularidad_id' => Yii::t('app', 'Uf Titularidad ID'),
            'id_persona' => Yii::t('app', 'Id Persona'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_persona']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUfTitularidad()
    {
        return $this->hasOne(UfTitularidad::className(), ['id' => 'uf_titularidad_id']);
    }
}
