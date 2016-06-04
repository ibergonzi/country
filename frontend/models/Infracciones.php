<?php

namespace frontend\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "infracciones".
 *
 * @property integer $id
 * @property integer $id_uf
 * @property integer $id_vehiculo
 * @property integer $id_persona
 * @property string $fecha
 * @property string $hora
 * @property string $nro_acta
 * @property string $lugar
 * @property integer $id_concepto
 * @property integer $id_informante
 * @property string $descripcion
 * @property integer $notificado
 * @property string $fecha_verif
 * @property integer $verificado
 * @property string $foto
 * @property integer $multa_unidad
 * @property integer $multa_fec_reinc
 * @property double $multa_monto
 * @property integer $multa_pers_cant
 * @property double $multa_pers_monto
 * @property double $multa_pers_total
 * @property double $multa_total
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property Uf $idUf
 * @property Vehiculos $idVehiculo
 * @property Personas $idPersona
 * @property Personas $idInformante
 * @property InfracUnidades $multaUnidad
 * @property InfracConceptos $idConcepto
 */
class Infracciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'infracciones';
    }
    
    const ESTADO_BAJA = 0;
	const ESTADO_ACTIVO = 1;
	
	public $cant;
	public $tot;
	
	// funcion agregada a mano
	public static function getEstados($key=null)
	{
		$estados=[self::ESTADO_ACTIVO=>'Activo',self::ESTADO_BAJA=>'Baja'];
	    if ($key !== null) {
			return $estados[$key];
		}
		return $estados;
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
	
    
    // extiende los comportamientos de la clase Personas para grabar datos de auditoría
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
    
	public function beforeSave($insert) 
	{
        if (parent::beforeSave($insert)) {
            $this->multa_monto = str_replace(",", ".", $this->multa_monto);
            $this->multa_pers_monto = str_replace(",", ".", $this->multa_pers_monto);
            $this->multa_pers_total = str_replace(",", ".", $this->multa_pers_total); 
            $this->multa_total = str_replace(",", ".", $this->multa_total); 
            return true;
        } else {
            return false;
        }
    }          

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_uf', 'id_vehiculo', 'id_persona', 'hora', 'lugar', 'id_concepto', 'id_informante', 
				'notificado', 'verificado', 'multa_fec_reinc', 'multa_monto', 'multa_pers_cant', 'multa_pers_monto', 'multa_pers_total', 
				'multa_total',], 'required'],
            [['id_uf', 'id_vehiculo', 'id_persona', 'id_concepto', 'id_informante', 'notificado', 'verificado', 
				'multa_unidad','multa_pers_cant', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['fecha', 'hora', 'fecha_verif', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
            [['multa_monto', 'multa_pers_monto', 'multa_pers_total', 'multa_total'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
            [['nro_acta'], 'string', 'max' => 10],
            [['lugar', 'descripcion', 'motivo_baja'], 'string', 'max' => 50],
 			[['foto'], 'file', 'extensions'=>'jpg, jpeg'],  
            [['id_uf'], 'exist', 'skipOnError' => true, 'targetClass' => Uf::className(), 'targetAttribute' => ['id_uf' => 'id']],
            [['id_vehiculo'], 'exist', 'skipOnError' => true, 'targetClass' => Vehiculos::className(), 'targetAttribute' => ['id_vehiculo' => 'id']],
            [['id_persona'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['id_persona' => 'id']],
            [['id_informante'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['id_informante' => 'id']],
            [['multa_unidad'], 'exist', 'skipOnError' => true, 'targetClass' => InfracUnidades::className(), 'targetAttribute' => ['multa_unidad' => 'id']],
            [['id_concepto'], 'exist', 'skipOnError' => true, 'targetClass' => InfracConceptos::className(), 'targetAttribute' => ['id_concepto' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Nro.Infr.',
            'id_uf' => 'U.F.',
            'id_vehiculo' => 'ID Vehiculo',
            'id_persona' => 'Infractor',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'nro_acta' => 'Nro.Acta',
            'lugar' => 'Lugar',
            'id_concepto' => 'Concepto',
            'id_informante' => 'Informante',
            'descripcion' => 'Descripción',
            'notificado' => 'Notificado',
            'fecha_verif' => 'Fec.Verif.',
            'verificado' => 'Verificado',
            'foto' => 'Foto',
            'multa_unidad' => 'Unidad',
            'multa_fec_reinc'=>'Fec.Reinc',
            'multa_monto' => 'Monto Base',
            'multa_pers_cant' => 'Cant.Personas',
            'multa_pers_monto' => 'Monto x Pers.',
            'multa_pers_total' => 'Monto Personas',
            'multa_total' => 'Multa Total',
            'created_by' => Yii::t('app', 'Usuario alta'),
            'created_at' => Yii::t('app', 'Fecha alta'),
            'updated_by' => Yii::t('app', 'Usuario modif.'),
            'updated_at' => Yii::t('app', 'Fecha modif.'),
            'estado' => 'Estado',
            'motivo_baja' => 'Motivo Baja',
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.', 
            // se usan en Rendic
            'cant'=>'Cant.del periodo',
            'tot'=>'Importe',
            // se usan en Index
            'rUnidad'=>'Unidad',
            'rConcepto'=>'Concepto'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUf()
    {
        return $this->hasOne(Uf::className(), ['id' => 'id_uf']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehiculo()
    {
        return $this->hasOne(Vehiculos::className(), ['id' => 'id_vehiculo']);
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
    public function getInformante()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_informante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMultaUnidad()
    {
        return $this->hasOne(InfracUnidades::className(), ['id' => 'multa_unidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConcepto()
    {
        return $this->hasOne(InfracConceptos::className(), ['id' => 'id_concepto']);
    }
    
    public function getUserCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }    
    
    public function getUserUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }         
}
