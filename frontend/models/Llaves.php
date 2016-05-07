<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "llaves".
 *
 * @property string $id
 * @property integer $id_persona
 * @property integer $panico
 * @property integer $estado
 * @property integer $id_autorizante
 * @property integer $id_vehiculo
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property Accesos[] $accesos
 * @property Personas $idPersona
 * @property Autorizantes $idAutorizante
 * @property Vehiculos $idVehiculo
 */
class Llaves extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'llaves';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'panico', 'estado', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['id', 'id_persona', 'panico', 'estado', 'id_autorizante', 'id_vehiculo', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id_persona'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['id_persona' => 'id']],
            [['id_autorizante'], 'exist', 'skipOnError' => true, 'targetClass' => Autorizantes::className(), 'targetAttribute' => ['id_autorizante' => 'id']],
            [['id_vehiculo'], 'exist', 'skipOnError' => true, 'targetClass' => Vehiculos::className(), 'targetAttribute' => ['id_vehiculo' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_persona' => 'Id Persona',
            'panico' => 'Panico',
            'estado' => 'Estado',
            'id_autorizante' => 'Id Autorizante',
            'id_vehiculo' => 'Id Vehiculo',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesos()
    {
        return $this->hasMany(Accesos::className(), ['egr_id_llave' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPersona()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_persona']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAutorizante()
    {
        return $this->hasOne(Autorizantes::className(), ['id' => 'id_autorizante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdVehiculo()
    {
        return $this->hasOne(Vehiculos::className(), ['id' => 'id_vehiculo']);
    }
}
