<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "autorizantes".
 *
 * @property integer $id_persona

 *
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
            [['id_persona'], 'required'],
            [['id_persona'], 'integer'],
            [['id_persona'], 'safe'],

       
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_persona' => Yii::t('app', 'Persona'),
        ];
    }

	public static function formateaAutorizanteSelect2($id,$es_por_nro) 
	{
		$p=Personas::findOne($id);
		if ($es_por_nro) {
			$r=$p->nro_doc . ' ' . $p->apellido.' '.$p->nombre.' '.$p->nombre2.  ' ('. $id . ')';
		} else {
			$r=$p->apellido.' '.$p->nombre.' '.$p->nombre2. ' D:' . $p->nro_doc . ' ('. $id . ')';			
		}
		return $r;
	}    


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPersona()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_persona']);
    }
}
