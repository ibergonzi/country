<?php

namespace frontend\models;

use Yii;

use yii\helpers\ArrayHelper;
use \frontend\models\VehiculosMarcas;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "vehiculos".
 *
 * @property integer $id
 * @property integer $id_marca
 * @property string $modelo
 * @property string $patente
 * @property string $color
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property Accesos[] $accesos
 * @property Accesos[] $accesos0
 * @property VehiculosMarcas $idMarca
 */
class Vehiculos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehiculos';
    }

    const ESTADO_BAJA = 0;
	const ESTADO_ACTIVO = 1;
    

	// devuelve lista de tipos documentos preparada para los dropDownList
	// se usa:  $form->field($model, 'id_tipo_doc')->dropDownList($model->listaTiposdoc)
	public static function getListaMarcas()
	{
		$opciones = VehiculosMarcas::find()->asArray()->all();
		return ArrayHelper::map($opciones, 'id', 'desc_marca');
	}        
	
	
		
	// funcion agregada a mano
	public static function getEstados($key=null)
	{
		$estados=[self::ESTADO_ACTIVO=>'Activo',self::ESTADO_BAJA=>'Baja'];
	    if ($key !== null) {
			return $estados[$key];
		}
		return $estados;
	}	
	
	
	// se graban los nombres en mayúsculas
    public function beforeSave($insert)
    {
			$this->modelo=mb_strtoupper($this->modelo);
			$this->patente=mb_strtoupper($this->patente);
			$this->color=mb_strtoupper($this->color);						
 
            parent::beforeSave($insert);
            return true;
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
    public function rules()
    {
        return [
            [['modelo', 'patente', 'color'], 'required'],
            [['id_marca', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['created_at', 'updated_at','created_by','updated_by'], 'safe'],
            [['modelo'], 'string', 'max' => 30],
            [['patente', 'color'], 'string', 'max' => 10],
            [['motivo_baja'], 'string', 'max' => 50],
            [['patente'], 'unique'],
			[['modelo', 'patente', 'color'], 'trim'],   
			['estado','default','value'=>Vehiculos::ESTADO_ACTIVO],         
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_marca' => Yii::t('app', 'Marca'),
            'modelo' => Yii::t('app', 'Modelo'),
            'patente' => Yii::t('app', 'Patente'),
            'color' => Yii::t('app', 'Color'),
            'created_by' => Yii::t('app', 'Usuario alta'),
            'created_at' => Yii::t('app', 'Fecha alta'),
            'updated_by' => Yii::t('app', 'Usuario modif.'),
            'updated_at' => Yii::t('app', 'Fecha modif.'),
            'estado' => Yii::t('app', 'Estado'),
            'motivo_baja' => Yii::t('app', 'Motivo Baja'),
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',  
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesosEgresos()
    {
        return $this->hasMany(Accesos::className(), ['egr_id_vehiculo' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesosIngresos()
    {
        return $this->hasMany(Accesos::className(), ['ing_id_vehiculo' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehiculoMarca()
    {
        return $this->hasOne(VehiculosMarcas::className(), ['id' => 'id_marca']);
    }
    
    
    public function getUserCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }    
    
    public function getUserUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }         

    /**
     * @inheritdoc
     * @return VehiculosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VehiculosQuery(get_called_class());
    }
}
