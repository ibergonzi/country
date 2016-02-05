<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "entradas".
 *
 * @property integer $id
 * @property integer $idpersonas_fk
 * @property integer $idvehiculos_fk
 * @property string $motivo
 *
 * @property Personas $idpersonasFk
 * @property Vehiculos $idvehiculosFk
 * @property Movimientos[] $movimientos
 */
class Entradas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entradas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpersonas_fk', 'idvehiculos_fk'], 'integer'],
            [['motivo'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'idpersonas_fk' => Yii::t('app', 'Idpersonas Fk'),
            'idvehiculos_fk' => Yii::t('app', 'Idvehiculos Fk'),
            'motivo' => Yii::t('app', 'Motivo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Persona::className(), ['id' => 'idpersonas_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehiculo()
    {
        return $this->hasOne(Vehiculos::className(), ['id' => 'idvehiculos_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovimientos()
    {
        return $this->hasMany(Movimientos::className(), ['entrada_id' => 'id']);
    }
}
