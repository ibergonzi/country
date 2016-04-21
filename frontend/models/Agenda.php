<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "agenda".
 *
 * @property integer $numero
 * @property string $nombre
 * @property string $direccion
 * @property string $localidad
 * @property string $cod_pos
 * @property string $provincia
 * @property string $pais
 * @property string $telefono
 * @property string $telefono1
 * @property string $telefono2
 * @property string $fax
 * @property string $telex
 * @property string $email
 * @property string $palabra
 * @property string $actividad
 */
class Agenda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agenda';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'direccion', 'localidad', 'provincia', 'pais', 'telefono', 'telefono1', 'telefono2', 'fax', 'email', 'palabra', 'actividad'], 'string', 'max' => 50],
            [['cod_pos'], 'string', 'max' => 10],
            [['telex'], 'string', 'max' => 15],
        ];
    }
    
	// se graban los nombres en mayúsculas
    public function beforeSave($insert)
    {

			$this->nombre=mb_strtoupper($this->nombre);
			$this->direccion=mb_strtoupper($this->direccion);
			$this->localidad=mb_strtoupper($this->localidad);
			$this->cod_pos=mb_strtoupper($this->cod_pos);
			$this->provincia=mb_strtoupper($this->provincia);
			$this->pais=mb_strtoupper($this->pais);
			$this->telefono=mb_strtoupper($this->telefono);
			$this->telefono1=mb_strtoupper($this->telefono1);
			$this->telefono2=mb_strtoupper($this->telefono2);
			$this->fax=mb_strtoupper($this->fax);
			$this->telex=mb_strtoupper($this->telex);			
			$this->email=mb_strtoupper($this->email);
			$this->palabra=mb_strtoupper($this->palabra);
			$this->actividad=mb_strtoupper($this->actividad);																																							
 
            parent::beforeSave($insert);
            return true;
    }        

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'numero' => 'Numero',
            'nombre' => 'Nombre',
            'direccion' => 'Dirección',
            'localidad' => 'Localidad',
            'cod_pos' => 'C.P.',
            'provincia' => 'Provincia',
            'pais' => 'Pais',
            'telefono' => 'Teléf.',
            'telefono1' => 'Teléf.1',
            'telefono2' => 'Teléf.2',
            'fax' => 'Fax',
            'telex' => 'Telex',
            'email' => 'Mail',
            'palabra' => 'Palabra',
            'actividad' => 'Actividad',
        ];
    }
}
