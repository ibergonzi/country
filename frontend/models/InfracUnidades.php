<?php

namespace frontend\models;

use Yii;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "infrac_unidades".
 *
 * @property integer $id
 * @property string $unidad
 *
 * @property InfracConceptos[] $infracConceptos
 * @property Infracciones[] $infracciones
 */
class InfracUnidades extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'infrac_unidades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unidad'], 'required'],
            [['unidad'], 'string', 'max' => 5],
        ];
    }
    
	// devuelve lista de unidades de multas preparada para los dropDownList
	public static function getLista()
	{
		$opciones = self::find()->asArray()->all();
		return ArrayHelper::map($opciones, 'id', 'unidad');
	}            

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unidad' => 'Unidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfracConceptos()
    {
        return $this->hasMany(InfracConceptos::className(), ['multa_unidad' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfracciones()
    {
        return $this->hasMany(Infracciones::className(), ['multa_unidad' => 'id']);
    }
}
