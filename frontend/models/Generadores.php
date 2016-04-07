<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "generadores".
 *
 * @property integer $id
 * @property string $descripcion
 * @property integer $activo
 *
 * @property CortesEnergiaGen[] $cortesEnergiaGens
 */
class Generadores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'generadores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'descripcion'], 'required'],
            [['id', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCortesEnergiaGens()
    {
        return $this->hasMany(CortesEnergiaGen::className(), ['id_generador' => 'id']);
    }
}
