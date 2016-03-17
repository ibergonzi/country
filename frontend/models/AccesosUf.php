<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "accesos_uf".
 *
 * @property integer $id_acceso
 * @property integer $id_uf
 *
 * @property Uf $idUf
 * @property Accesos $idAcceso
 */
class AccesosUf extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accesos_uf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_acceso', 'id_uf'], 'required'],
            [['id_acceso', 'id_uf'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_acceso' => Yii::t('app', 'Id Acceso'),
            'id_uf' => Yii::t('app', 'Id Uf'),
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
}
