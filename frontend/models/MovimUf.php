<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "movim_uf".
 *
 * @property integer $id
 * @property string $desc_movim_uf
 * @property integer $cesion
 *
 * @property UfTitularidad[] $ufTitularidads
 */
class MovimUf extends \yii\db\ActiveRecord
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
        return 'movim_uf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'desc_movim_uf'], 'required'],
            [['id', 'cesion'], 'integer'],
            [['desc_movim_uf'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'desc_movim_uf' => 'Movimiento',
            'cesion' => 'Cesión',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUfTitularidad()
    {
        return $this->hasMany(UfTitularidad::className(), ['tipo_movim' => 'id']);
    }
}
