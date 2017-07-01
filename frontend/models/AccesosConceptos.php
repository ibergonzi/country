<?php

namespace frontend\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "accesos_conceptos".
 *
 * @property integer $id
 * @property string $concepto
 * @property integer $req_tarjeta
 * @property integer $req_seguro
 * @property integer $req_seguro_vehic
 * @property integer $req_licencia 
 *
 * @property Accesos[] $accesos
 */
class AccesosConceptos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accesos_conceptos';
    }
    
    const ESTADO_INACTIVO = 0;
	const ESTADO_ACTIVO = 1;    
	const SIN_INGRESO = 0;

	// devuelve lista de tipos documentos preparada para los dropDownList
	// se usa:  $form->field($model, 'id_tipo_doc')->dropDownList($model->listaTiposdoc)
	public static function getListaConceptos($todos=true)
	{
		if ($todos) {
			$opciones = self::find()->where(['estado'=>self::ESTADO_ACTIVO])->asArray()->all();
		} else {
			// se excluye el concepto 0 (sin entrada)
			$opciones = self::find()->where(['estado'=>self::ESTADO_ACTIVO])->andWhere(['<>','id',self::SIN_INGRESO])->asArray()->all();
		}
		return ArrayHelper::map($opciones, 'id', 'concepto');
	}

	// funcion agregada a mano
	public static function getEstados($key=null)
	{
		$estados=[self::ESTADO_ACTIVO=>'Habilitado',self::ESTADO_INACTIVO=>'Deshabilitado'];
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
	
	// se graban los nombres en mayúsculas
    public function beforeSave($insert)
    {
			$this->concepto=mb_strtoupper($this->concepto);
 
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
            [['concepto', 'req_tarjeta', 'req_seguro','req_seguro_vehic','req_licencia'], 'required'],
            [['req_tarjeta', 'req_seguro','req_seguro_vehic','req_licencia'], 'integer'],
            [['concepto'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'concepto' => Yii::t('app', 'Concepto'),
            'req_tarjeta' => Yii::t('app', 'Requiere Tarjeta'),
            'req_seguro' => Yii::t('app', 'Requiere Seguro'),
            'req_seguro_vehic' => Yii::t('app', 'Requiere Seg.de Vehic.'),
            'req_licencia'=>Yii::t('app', 'Requiere Lic.Conduc.'),            
            'created_by' => Yii::t('app', 'Usuario alta'),
            'created_at' => Yii::t('app', 'Fecha alta'),
            'updated_by' => Yii::t('app', 'Usuario modif.'),
            'updated_at' => Yii::t('app', 'Fecha modif.'),
            'estado' => 'Estado',
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',              
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesos()
    {
        return $this->hasMany(Accesos::className(), ['id_concepto' => 'id']);
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
