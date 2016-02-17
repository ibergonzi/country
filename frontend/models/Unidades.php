<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "unidades".
 *
 * @property integer $id
 * @property integer $lote
 * @property integer $manzana
 *
 * @property PersonasUnidades[] $personasUnidades
 * @property Personas[] $idpersonas
 */
class Unidades extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unidades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'lote', 'manzana'], 'required'],
            [['id', 'lote', 'manzana'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lote' => Yii::t('app', 'Lote'),
            'manzana' => Yii::t('app', 'Manzana'),
        ];
    }

	// Relación N a N via junction table, en este caso personas_unidades

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonasUnidades()
    {
        return $this->hasMany(PersonasUnidades::className(), ['idunidad' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasMany(Personas::className(), ['id' => 'idpersona'])
			->via('personasUnidades');
    }
}
