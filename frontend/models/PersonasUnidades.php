<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "personas_unidades".
 *
 * @property integer $idpersona
 * @property integer $idunidad
 * @property string $fecha
 *
 * @property Personas $idpersona0
 * @property Unidades $idunidad0
 */
class PersonasUnidades extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'personas_unidades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpersona', 'idunidad', 'fecha'], 'required'],
            [['idpersona', 'idunidad'], 'integer'],
            [['fecha'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idpersona' => Yii::t('app', 'Idpersona'),
            'idunidad' => Yii::t('app', 'Idunidad'),
            'fecha' => Yii::t('app', 'Fecha'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasMany(Personas::className(), ['id' => 'idpersona']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnidades()
    {
        return $this->hasMany(Unidades::className(), ['id' => 'idunidad']);
    }
}
