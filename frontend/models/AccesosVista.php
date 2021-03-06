<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "accesos_vista".
 *
 * @property string $id
 * @property integer $id_persona
 * @property integer $ing_id_vehiculo
 * @property string $ing_fecha
 * @property string $ing_hora
 * @property integer $ing_id_porton
 * @property integer $ing_id_user
 * @property integer $egr_id_vehiculo
 * @property string $egr_fecha
 * @property string $egr_hora
 * @property integer $egr_id_porton
 * @property integer $egr_id_user
 * @property integer $id_concepto
 * @property string $motivo
 * @property string $control
 * @property integer $cant_acomp
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 * @property string $r_ing_usuario
 * @property string $r_egr_usuario
 * @property string $r_apellido
 * @property string $r_nombre
 * @property string $r_nombre2
 * @property string $r_nro_doc
 * @property string $r_ing_patente
 * @property string $r_ing_marca
 * @property string $r_ing_modelo
 * @property string $r_ing_color
 * @property string $r_egr_patente
 * @property string $r_egr_marca
 * @property string $r_egr_modelo
 * @property string $r_egr_color
 * @property string $desc_concepto
 */
class AccesosVista extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accesos_vista';
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
            [['id_persona', 'ing_id_vehiculo', 'ing_id_porton', 'ing_id_user', 'egr_id_vehiculo', 'egr_id_porton', 'egr_id_user', 'id_concepto', 'cant_acomp', 'created_by', 'updated_by', 'estado',  ], 'integer'],
            [['id_persona', 'ing_id_vehiculo', 'ing_fecha', 'ing_hora', 'ing_id_porton', 'ing_id_user', 'id_concepto', 'motivo', 'created_by', 'created_at', 'updated_by', 'updated_at', ], 'required'],
            [['ing_fecha', 'ing_hora', 'egr_fecha', 'egr_hora', 'created_at', 'updated_at','ing_id_llave','egr_id_llave','id_ufs'], 'safe'],
            [['motivo', 'motivo_baja', 'desc_concepto'], 'string', 'max' => 50],
            [['control'], 'string', 'max' => 200],
            [['r_ing_usuario', 'r_egr_usuario'], 'string', 'max' => 255],
            [['r_apellido', 'r_nombre', 'r_nombre2', ], 'string', 'max' => 45],
            [['r_nro_doc',], 'string', 'max' => 15],
            [['r_ing_patente', 'r_ing_color', 'r_egr_patente', 'r_egr_color'], 'string', 'max' => 10],
            [['r_ing_marca', 'r_egr_marca'], 'string', 'max' => 20],
            [['r_ing_modelo', 'r_egr_modelo'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'id_persona' => 'Persona',
            'ing_id_vehiculo' => 'Vehic.Ing.',
            'ing_fecha' => 'Fecha Ing.',
            'ing_hora' => 'H.Ing.',
            'ing_id_porton' => 'Porton Ing.',
            'ing_id_user' => 'Usuario Ing.',
            'ing_id_llave' => 'Llave Ing.',  
            'egr_id_llave' => 'Llave Egr.',                       
            'egr_id_vehiculo' => 'Vehic.Egr.',
            'egr_fecha' => 'Fec.Egr.',
            'egr_hora' => 'H.Egr.',
            'egr_id_porton' => 'Porton Egr.',
            'egr_id_user' => 'Usuario Egr.',
            'id_concepto' => 'Concepto',
            'motivo' => 'Motivo',
            'control' => 'Control',
            'cant_acomp' => 'Cant.Acomp.',
            'created_by' => 'Usuario alta',
            'created_at' => 'Fecha alta',
            'updated_by' => 'Usuario modif.',
            'updated_at' => 'Fecha modif.',
            'estado' => 'Estado',
            'motivo_baja' => 'Motivo Baja',
            'r_ing_usuario' => 'Usuario Ing.',
            'r_egr_usuario' => 'Usuario Egr.',
            'r_apellido' => 'Apellido',
            'r_nombre' => 'Nombre',
            'r_nombre2' => 'Nombre2',
            'r_nro_doc' => 'Nro Doc',
            'r_ing_patente' => 'Patente',
            'r_ing_marca' => 'Marca',
            'r_ing_modelo' => 'Modelo',
            'r_ing_color' => 'Color',
            'r_egr_patente' => 'Patente',
            'r_egr_marca' => 'Marca',
            'r_egr_modelo' => 'Modelo',
            'r_egr_color' => 'Color',
            'desc_concepto' => 'Concepto',
            'id_ufs'=>'U.F.',
        ];
    }
    
    public static function find()
    {
        return new AccesosVistaQuery(get_called_class());
    }    
}
/*
select `country`.`accesos`.`id` AS `id`,`country`.`accesos`.`id_persona` AS `id_persona`,
`country`.`accesos`.`ing_id_vehiculo` AS `ing_id_vehiculo`,
`country`.`accesos`.`ing_fecha` AS `ing_fecha`,`country`.`accesos`.`ing_hora` AS `ing_hora`,
`country`.`accesos`.`ing_id_porton` AS `ing_id_porton`,`country`.`accesos`.`ing_id_user` AS `ing_id_user`,
`country`.`accesos`.`ing_id_llave` AS `ing_id_llave`,
`country`.`accesos`.`egr_id_vehiculo` AS `egr_id_vehiculo`,`country`.`accesos`.`egr_fecha` AS `egr_fecha`,
`country`.`accesos`.`egr_hora` AS `egr_hora`,`country`.`accesos`.`egr_id_porton` AS `egr_id_porton`,
`country`.`accesos`.`egr_id_user` AS `egr_id_user`,
`country`.`accesos`.`egr_id_llave` AS `egr_id_llave`,
`country`.`accesos`.`id_concepto` AS `id_concepto`,
`country`.`accesos`.`motivo` AS `motivo`,`country`.`accesos`.`control` AS `control`,
`country`.`accesos`.`cant_acomp` AS `cant_acomp`,`country`.`accesos`.`created_by` AS `created_by`,
`country`.`accesos`.`created_at` AS `created_at`,`country`.`accesos`.`updated_by` AS `updated_by`,
`country`.`accesos`.`updated_at` AS `updated_at`,`country`.`accesos`.`estado` AS `estado`,
`country`.`accesos`.`motivo_baja` AS `motivo_baja`,
`uing`.`username` as r_ing_usuario,
`uegr`.`username` as r_egr_usuario,
`personas`.apellido as r_apellido,
`personas`.nombre as r_nombre,
`personas`.nombre2 as r_nombre2,
`personas`.nro_doc as r_nro_doc,
`ving`.patente as r_ing_patente,
`ving`.marca as r_ing_marca,
`ving`.modelo as r_ing_modelo,
`ving`.color as r_ing_color,
`vegr`.patente as r_egr_patente,
`vegr`.marca as r_egr_marca,
`vegr`.modelo as r_egr_modelo,
`vegr`.color as r_egr_color,
`accesos_conceptos`.concepto as desc_concepto
from `country`.`accesos` 
LEFT JOIN `user` `uing` ON `ing_id_user` = `uing`.`id` 
LEFT JOIN `user` `uegr` ON `egr_id_user` = `uegr`.`id` 
LEFT JOIN `personas` ON accesos.id_persona = `personas`.`id` 
LEFT JOIN `vehiculos` `ving` ON `ing_id_vehiculo` = `ving`.`id` 
LEFT JOIN `vehiculos` `vegr` ON `egr_id_vehiculo` = `vegr`.`id` 
LEFT JOIN `accesos_conceptos` ON `id_concepto` = `accesos_conceptos`.`id`
*/


