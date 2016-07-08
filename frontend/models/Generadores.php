<?php

namespace frontend\models;

use Yii;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "generadores".
 *
 * @property integer $id
 * @property string $descripcion
 * @property integer $activo
 *
 * @property CortesEnergiaGen[] $cortesEnergiaGens
 */
class Generadores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
     

	const SI = 1;
	const NO = 0;
	
	public static function getSiNo($key=null)
	{
		$estados=[self::NO=>'No',self::SI=>'Si'];
	    if ($key !== null) {
			return $estados[$key];
		}
		return $estados;
	}     
     
    public static function tableName()
    {
        return 'generadores';
    }

	public static function getGeneradoresActivos()
	{
		$opciones = self::find()->where(['activo'=>1])->asArray()->all();
		return ArrayHelper::map($opciones, 'id', 'descripcion');
	}  

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'descripcion'], 'required'],
            [['id', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripción',
            'activo' => 'Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCortesEnergiaGens()
    {
        return $this->hasMany(CortesEnergiaGen::className(), ['id_generador' => 'id']);
    }
}
