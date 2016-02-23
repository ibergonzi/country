<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "vehiculos_marcas".
 *
 * @property integer $id
 * @property string $desc_marca
 *
 * @property Vehiculos[] $vehiculos
 */
class VehiculosMarcas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehiculos_marcas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc_marca'], 'required'],
            [['desc_marca'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'desc_marca' => Yii::t('app', 'Marca'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehiculos()
    {
        return $this->hasMany(Vehiculos::className(), ['id_marca' => 'id']);
    }
}
