<?php
namespace frontend\models;

use Yii;
use yii\base\Model;


class RangoFechas extends Model
{
	public $fecdesde;
	public $fechasta;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [['fecdesde','fechasta',],'safe'],
             [['fecdesde','fechasta',],'validaRangoFechas','skipOnEmpty' => false], 
        ];
    }

    public function validaRangoFechas($attribute, $params) 
    {
		if (empty($this->fecdesde) || empty($this->fechasta)) {
			if (empty($this->fecdesde)) {
				$this->addError('fecdesde','Debe ingresar un rango de fechas');return;
			}
			if (empty($this->fechasta)) {
				$this->addError('fechasta','Debe ingresar un rango de fechas');return;
			}
		}
		if (strtotime($this->fecdesde) > strtotime($this->fechasta)) {
			$this->addError('fechasta','Esta fecha no puede ser anterior a la otra fecha');return;
		}
	}

   public function attributeLabels()
    {
        return [
            'fecdesde' => 'Desde el',
            'fechasta' => 'Hasta el',
         ];
    }

}
