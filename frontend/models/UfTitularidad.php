<?php

namespace frontend\models;

use Yii;

use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "uf_titularidad".
 *
 * @property integer $id
 * @property integer $id_uf
 * @property integer $tipo_movim
 * @property string $fec_desde
 * @property string $fec_hasta
 * @property string $exp_telefono
 * @property string $exp_direccion
 * @property string $exp_localidad
 * @property string $exp_email
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 * @property integer $ultima
 *
 * @property Uf $idUf
 * @property MovimUf $tipoMovim
 * @property UfTitularidadPersonas[] $ufTitularidadPersonas
 * @property Personas[] $idPersonas
 */
class UfTitularidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uf_titularidad';
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
    
	// devuelve lista de tipos documentos preparada para los dropDownList
	// se usa:  $form->field($model, 'id_tipo_doc')->dropDownList($model->listaMovimientos)
	public static function getListaMovimientos($todos=false)
	{
		if ($todos) {
			$opciones = MovimUf::find()->asArray()->all();
		} else {
			$opciones = MovimUf::find()->andWhere(['<>','migracion',1])->asArray()->all();			
		}

		return ArrayHelper::map($opciones, 'id', 'desc_movim_uf');
	}  
	

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_uf', 'tipo_movim', 'fec_desde', ], 'required'],
            [['id_uf', 'tipo_movim', 'created_by', 'updated_by', 'estado', 'ultima'], 'integer'],
            [['fec_desde', 'fec_hasta', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
            [['exp_telefono'], 'string', 'max' => 30],
            [['exp_direccion', 'exp_localidad'], 'string', 'max' => 60],
            [['exp_email'], 'string', 'max' => 255],
            [['motivo_baja'], 'string', 'max' => 50],
            [['id_uf'], 'exist', 'skipOnError' => true, 'targetClass' => Uf::className(), 'targetAttribute' => ['id_uf' => 'id']],
            [['tipo_movim'], 'exist', 'skipOnError' => true, 'targetClass' => MovimUf::className(), 'targetAttribute' => ['tipo_movim' => 'id']],
        ];
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_uf' => 'Id Uf',
            'tipo_movim' => 'Movimiento',
            'fec_desde' => 'Fec.Desde',
            'fec_hasta' => 'Fec.Hasta',
            'exp_telefono' => 'Teléf.(expensas)',
            'exp_direccion' => 'Direc.(expensas)',
            'exp_localidad' => 'Localidad (expensas)',
            'exp_email' => 'Mail (expensas)',
            'created_by' => Yii::t('app', 'Usuario alta'),
            'created_at' => Yii::t('app', 'Fecha alta'),
            'updated_by' => Yii::t('app', 'Usuario modif.'),
            'updated_at' => Yii::t('app', 'Fecha modif.'),
            'estado' => Yii::t('app', 'Estado'),            
            'motivo_baja' => Yii::t('app', 'Motivo Baja'),
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.', 
            'ultima' => 'Ultima',
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
    public function getTipoMovim()
    {
        return $this->hasOne(MovimUf::className(), ['id' => 'tipo_movim']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUfTitularidadPersonas()
    {
        return $this->hasMany(UfTitularidadPersonas::className(), ['uf_titularidad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasMany(Personas::className(), ['id' => 'id_persona'])->viaTable('uf_titularidad_personas', ['uf_titularidad_id' => 'id']);
    }
    
    public function getUserCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }    
    
    public function getUserUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }         
    
    /*
SELECT uf_titularidad.id as id_titularidad,`id_uf`, desc_movim_uf,`fec_desde`,`fec_hasta`,
`exp_telefono`,`exp_direccion`,`exp_localidad`,`exp_email`, uf_titularidad_personas.tipo,
personas.id as id_persona, personas.apellido,
personas.nombre,personas.nombre2,personas.nro_doc
FROM `uf_titularidad` join movim_uf on movim_uf.id=tipo_movim
join uf_titularidad_personas on uf_titularidad_personas.uf_titularidad_id=uf_titularidad.id
join personas on uf_titularidad_personas.id_persona=personas.id
     */
}
