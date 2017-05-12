<?php

namespace frontend\models;

use Yii;

// agregados por mi, auditoria
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "mens_personas".
 *
 * @property integer $id
 * @property string $avisar_a
 * @property string $mensaje
 * @property integer $model_id
 * @property string $apellido
 * @property string $nombre
 * @property string $nombre2
 * @property string $nro_doc
 * @property integer $created_by
 * @property string $usuario_crea
 * @property string $created_at
 * @property integer $updated_by
 * @property string $usuario_borra
 * @property string $updated_at
 * @property integer $estado
 */
class MensPersonas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mens_personas';
    }
    
    const ESTADO_BAJA = 0;
	const ESTADO_ACTIVO = 1;    
	
	// funcion agregada a mano
	public static function getEstados($key=null)
	{
		$estados=[self::ESTADO_ACTIVO=>'Activo',self::ESTADO_BAJA=>'Baja'];
	    if ($key !== null) {
			return $estados[$key];
		}
		return $estados;
	}	    	
    
    	// creado a mano para que gii pueda crear controller y views
	public static function primaryKey()
    {     
        return ['id'];   
    }      

    // extiende los comportamientos de la clase para grabar datos de auditoría
    // agregado por mi
    public function behaviors()
    {
	  return [
		  [
			  'class' => BlameableBehavior::className(),
			  'createdByAttribute' => 'created_by',
			  'updatedByAttribute' => 'updated_by',
		  ],
		  [
			  'class' => TimestampBehavior::className(),
			  'createdAtAttribute' => 'created_at',
			  'updatedAtAttribute' => 'updated_at',                 
			  'value' => new Expression('CURRENT_TIMESTAMP')
		  ],

	  ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'model_id', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['avisar_a', 'mensaje', 'model_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['avisar_a'], 'string', 'max' => 50],
            [['mensaje'], 'string', 'max' => 500],
            [['apellido', 'nombre', 'nombre2'], 'string', 'max' => 45],
            [['nro_doc'], 'string', 'max' => 15],
            [['usuario_crea', 'usuario_borra'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'avisar_a' => 'Avisar a',
            'mensaje' => 'Mensaje',
            'model_id' => 'Persona ID',
            'apellido' => 'Apellido',
            'nombre' => 'Nombre',
            'nombre2' => 'Nombre2',
            'nro_doc' => 'Nro.Doc',
            'created_by' => 'Alta ID',
            'usuario_crea' => 'Alta Nombre',
            'created_at' => 'Creación',
            'updated_by' => 'Modif.ID',
            'usuario_borra' => 'Modif.Nombre',
            'updated_at' => 'Modificación',
            'estado' => 'Estado',
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',
        ];
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
