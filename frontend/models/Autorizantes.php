<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "autorizantes".
 *
 * @property integer $id
 * @property integer $id_uf
 * @property integer $id_persona
 *
 * @property Uf $idUf
 * @property Personas $idPersona
 */
class Autorizantes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'autorizantes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_uf', 'id_persona'], 'required'],
            [['id_uf', 'id_persona'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_uf' => Yii::t('app', 'UF'),
            'id_persona' => Yii::t('app', 'Persona'),
        ];
    }
    
	public static function formateaAutorizanteSelect2($id,$es_por_nro) 
	{
		$aut=Autorizantes::findOne($id);
		//$p=Personas::findOne($id);
		if ($es_por_nro) {
			$r=$aut->id_uf . ' ' . $aut->persona->apellido.' '.$aut->persona->nombre.' '.$aut->persona->nombre2;
		} else {
			$r=$aut->persona->apellido.' '.$aut->persona->nombre.' '.$aut->persona->nombre2. ' ' . $aut->id_uf;			
		}
		return $r;
	}    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUf()
    {
        return $this->hasOne(Uf::className(), ['id' => 'id_uf']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_persona']);
    }
}
