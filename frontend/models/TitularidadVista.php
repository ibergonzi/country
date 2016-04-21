<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "titularidad_vista".
 *
 * @property string $id
 * @property integer $id_titularidad
 * @property integer $id_uf
 * @property string $desc_movim_uf
 * @property string $fec_desde
 * @property string $fec_hasta
 * @property string $exp_telefono
 * @property string $exp_direccion
 * @property string $exp_localidad
 * @property string $exp_email
 * @property string $tipo
 * @property integer $id_persona
 * @property string $apellido
 * @property string $nombre
 * @property string $nombre2
 * @property string $desc_tipo_doc_abr
 * @property string $nro_doc
 * @property double $superficie

 */
class TitularidadVista extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'titularidad_vista';
    }
    
    	// creado a mano para que gii pueda crear controller y views
	public static function primaryKey()
    {     
        return ['id'];   
    }      

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'string'],
            [['id_titularidad', 'id_uf', 'id_persona'], 'integer'],
            [['id_uf', 'desc_movim_uf', 'fec_desde', 'tipo', 'apellido', 'nombre', 'desc_tipo_doc_abr', 'nro_doc', 'superficie', ], 'required'],
            [['fec_desde', 'fec_hasta'], 'safe'],
            [['superficie', ], 'number'],
            [['desc_movim_uf', 'exp_telefono'], 'string', 'max' => 30],
            [['exp_direccion', 'exp_localidad'], 'string', 'max' => 60],
            [['exp_email'], 'string', 'max' => 255],
            [['tipo'], 'string', 'max' => 1],
            [['apellido', 'nombre', 'nombre2'], 'string', 'max' => 45],
            [['desc_tipo_doc_abr'], 'string', 'max' => 4],
            [['nro_doc'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_titularidad' => 'Id Titularidad',
            'id_uf' => 'U.F.',
            'desc_movim_uf' => 'Movimiento',
            'fec_desde' => 'Desde',
            'fec_hasta' => 'Hasta',
            'exp_telefono' => 'Teléfono',
            'exp_direccion' => 'Dirección',
            'exp_localidad' => 'Localidad',
            'exp_email' => 'Mail',
            'tipo' => 'Tipo titularidad',
            'id_persona' => 'Persona',
            'apellido' => 'Apellido',
            'nombre' => 'Nombre',
            'nombre2' => '2 Nombre',
            'desc_tipo_doc_abr' => 'Tipo Doc.',
            'nro_doc' => 'Nro.Doc',
            'superficie' => 'Sup.m2.',
            'observaciones'=>'Observaciones'
        ];
    }
}
