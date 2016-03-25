<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "accesos_autorizantes".
 *
 * @property integer $id_acceso
 * @property integer $id_persona
 * @property integer $id_uf
 *
 * @property Uf $idUf
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
            [['id_acceso', 'id_persona', 'id_uf'], 'required'],
            [['id_acceso', 'id_persona', 'id_uf'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_acceso' => Yii::t('app', 'Acceso'),
            'id_persona' => Yii::t('app', 'Persona'),
            'id_uf' => Yii::t('app', 'U.F.'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUf()
    {
        return $this->hasOne(Uf::className(), ['id' => 'id_uf']);
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
    public function getPersona()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_persona']);
    }
}
