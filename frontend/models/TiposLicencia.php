<?php

namespace frontend\models;

use Yii;

use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "tipos_licencia".
 *
 * @property integer $id
 * @property string $desc_licencia
 * @property integer $activo
 *
 * @property PersonasLicencias[] $personasLicencias
 */
class TiposLicencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipos_licencia';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc_licencia'], 'required'],
            [['activo'], 'integer'],
            [['desc_licencia'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'desc_licencia' => 'Tipo de Licencia',
            'activo' => 'Activo',
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',
        ];
    }


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

    
	public static function getTiposLicenciaActivos()
	{
		$opciones = self::find()->where(['activo'=>1])->asArray()->all();
		return ArrayHelper::map($opciones, 'id', 'desc_licencia');
	}      

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonasLicencias()
    {
        return $this->hasMany(PersonasLicencias::className(), ['id_tipos_licencia' => 'id']);
    }

	// agregado por mi
    public function getUserCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }    
    
	// agregado por mi    
    public function getUserUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }          

}
