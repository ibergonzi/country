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
            [['id_uf', 'id_persona'], 'integer'],
            [['id_persona', 'id_uf'], 'unique', 'targetAttribute' => ['id_persona', 'id_uf'], 'message' => 'La combinación UF/Persona ya existe.'],
            [['id_uf'], 'exist', 'skipOnError' => true, 'targetClass' => Uf::className(), 'targetAttribute' => ['id_uf' => 'id']],
            [['id_persona'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['id_persona' => 'id']],
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
            'id_persona' => Yii::t('app', 'ID Persona'),
            'nombre2'=>'Nombre 2',
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
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLlaves()
    {
        return $this->hasMany(Llaves::className(), ['id_autorizante' => 'id']);
    }    
}
