<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "entradas".
 *
 * @property integer $id
 * @property integer $idporton
 * @property integer $idpersona
 * @property integer $idvehiculo
 * @property string $motivo
 *
 * @property Personas $persona
 * @property Vehiculos $vehiculo
 * @property Portones $porton
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
            [['idporton'], 'required'],
            [['idporton', 'idpersona', 'idvehiculo'], 'integer'],
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
            'idporton' => Yii::t('app', 'Idporton'),
            'idpersona' => Yii::t('app', 'Idpersona'),
            'idvehiculo' => Yii::t('app', 'Idvehiculo'),
            'motivo' => Yii::t('app', 'Motivo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Personas::className(), ['id' => 'idpersona']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehiculo()
    {
        return $this->hasOne(Vehiculos::className(), ['id' => 'idvehiculo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPorton()
    {
        return $this->hasOne(Portones::className(), ['id' => 'idporton']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovimientos()
    {
        return $this->hasMany(Movimientos::className(), ['entrada_id' => 'id']);
    }
}
