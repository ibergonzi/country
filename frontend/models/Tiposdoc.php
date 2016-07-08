<?php

namespace frontend\models;

use Yii;



/**
 * This is the model class for table "tiposdoc".
 *
 * @property integer $id
 * @property string $desc_tipo_doc
 * @property string $desc_tipo_doc_abr
 * @property string $persona_fisica
 * 
 *
 * @property Personas[] $personas
 */
class Tiposdoc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tiposdoc';
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

    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'desc_tipo_doc', 'desc_tipo_doc_abr','persona_fisica'], 'required'],
            [['id'], 'integer'],
            [['desc_tipo_doc'], 'string', 'max' => 50],
            [['desc_tipo_doc_abr'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'desc_tipo_doc' => Yii::t('app', 'Descripción'),
            'desc_tipo_doc_abr' => Yii::t('app', 'T.Doc.'),
            'persona_fisica' => Yii::t('app', 'Pers.Física'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasMany(Personas::className(), ['id_tipo_doc' => 'id']);
    }
}
