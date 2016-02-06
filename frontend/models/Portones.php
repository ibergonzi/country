<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "portones".
 *
 * @property integer $id
 * @property string $descripcion
 * @property integer $habilitado
 *
 * @property Entradas[] $entradas
 */
class Portones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'portones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'descripcion', 'habilitado'], 'required'],
            [['id', 'habilitado'], 'integer'],
            [['descripcion'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'descripcion' => Yii::t('app', 'Descripcion'),
            'habilitado' => Yii::t('app', 'Habilitado'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntradas()
    {
        return $this->hasMany(Entradas::className(), ['idporton' => 'id']);
    }
}
