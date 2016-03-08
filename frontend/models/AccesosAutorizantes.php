<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "accesos_autorizantes".
 *
 * @property integer $id_acceso
 * @property integer $id_persona
 *
 * @property Accesos $idAcceso
 * @property Personas $idPersona
 */
class AccesosAutorizantes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accesos_autorizantes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_acceso', 'id_persona'], 'required'],
            [['id_acceso', 'id_persona'], 'integer'],
            [['id_acceso', 'id_persona'], 'unique', 'targetAttribute' => ['id_acceso', 'id_persona'], 'message' => 'The combination of Id Acceso and Id Autorizante has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_acceso' => Yii::t('app', 'ID Acceso'),
            'id_persona' => Yii::t('app', 'Persona'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAcceso()
    {
        return $this->hasOne(Accesos::className(), ['id' => 'id_acceso']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPersona()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_persona']);
    }
}
