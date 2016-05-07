<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

// esta clase se usa para solicitar 2 ids de personas, no necesariamente tiene que ser un rango
class RangoPersonas extends Model
{
	public $idPersonaDesde;
	public $idPersonaHasta;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [['idPersonaDesde','idPersonaHasta',],'required'],
             [['idPersonaDesde'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['idPersonaDesde' => 'id']],
             [['idPersonaHasta'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['idPersonaHasta' => 'id']],             
        ];
    }



   public function attributeLabels()
    {
        return [
            'idPersonaDesde' => 'Persona',
            'idPersonaHasta' => 'Persona',
         ];
    }

}
