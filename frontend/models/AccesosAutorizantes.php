<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "accesos_autorizantes".
 *
 * @property integer $id_acceso
 * @property integer $id_autorizante
 *
 * @property Accesos $idAcceso
 * @property Personas $idAutorizante
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
            [['id_acceso', 'id_autorizante'], 'required'],
            [['id_acceso', 'id_autorizante'], 'integer'],
            [['id_acceso', 'id_autorizante'], 'unique', 'targetAttribute' => ['id_acceso', 'id_autorizante'], 'message' => 'The combination of Id Acceso and Id Autorizante has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_acceso' => Yii::t('app', 'Id Acceso'),
            'id_autorizante' => Yii::t('app', 'Id Autorizante'),
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
    public function getIdAutorizante()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_autorizante']);
    }
}
