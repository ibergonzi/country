<?php

namespace frontend\models;

use Yii;


use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "infrac_conceptos".
 *
 * @property integer $id
 * @property string $concepto
 * @property integer $es_multa
 * @property integer $dias_verif
 * @property integer $multa_unidad
 * @property double $multa_precio
 * @property integer $multa_reincidencia
 * @property double $multa_reinc_porc
 * @property integer $multa_reinc_dias
 * @property integer $multa_personas
 * @property double $multa_personas_precio
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property InfracUnidades $multaUnidad
 */
class InfracConceptos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'infrac_conceptos';
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
            $this->multa_precio = str_replace(",", ".", $this->multa_precio);
            $this->multa_reinc_porc = str_replace(",", ".", $this->multa_reinc_porc);
            $this->multa_personas_precio = str_replace(",", ".", $this->multa_personas_precio);                        
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
            [['concepto', 'es_multa', ], 'required'],        
            [['dias_verif',], 'required','when' => function($model) {
														return $model->es_multa == self::NO;
													},
										'whenClient' => "function (attribute, value) {
																return $('#infracconceptos-es_multa').val() == 0;
										}"],
            [['multa_precio', 'multa_reincidencia', 'multa_reinc_porc', 
				'multa_reinc_dias', 'multa_personas', 
				'multa_personas_precio', ], 'required','when' => function($model) {
																					return $model->es_multa == self::SI;
																					}, 
														'whenClient' => "function (attribute, value) {
																			return $('#infracconceptos-es_multa').val() == 1;
																	}"],
																					
            [['es_multa', 'dias_verif', 'multa_unidad', 'multa_reincidencia', 'multa_reinc_dias', 'multa_personas', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['multa_precio', 'multa_reinc_porc', 'multa_personas_precio'], 'number','numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
            [['created_by', 'created_at', 'updated_by', 'updated_at', 'estado'], 'safe'],
            [['concepto'], 'string', 'max' => 75],
            [['motivo_baja'], 'string', 'max' => 50],
            [['multa_unidad'], 'exist', 'skipOnError' => true, 
				'targetClass' => InfracUnidades::className(), 'targetAttribute' => ['multa_unidad' => 'id']],
			//[['multa_precio','multa_reincidencia','multa_reinc_porc','multa_reinc_dias','multa_personas','multa_personas_precio','dias_verif'], 'default', 'value' => 0],	
					
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'concepto' => 'Concepto',
            'es_multa' => 'Multa?',
            'dias_verif' => 'Dias Verif.',
            'multa_unidad' => 'Unidad',
            'multa_precio' => 'Precio',
            'multa_reincidencia' => 'Reincidencia?',
            'multa_reinc_porc' => 'Reinc.%',
            'multa_reinc_dias' => 'Reinc.Dias',
            'multa_personas' => 'Multa Personas?',
            'multa_personas_precio' => 'Precio x Pers.',
            'created_by' => Yii::t('app', 'Usuario alta'),
            'created_at' => Yii::t('app', 'Fecha alta'),
            'updated_by' => Yii::t('app', 'Usuario modif.'),
            'updated_at' => Yii::t('app', 'Fecha modif.'),
            'estado' => 'Estado',
            'motivo_baja' => 'Motivo Baja',
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',             
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMultaUnidad()
    {
        return $this->hasOne(InfracUnidades::className(), ['id' => 'multa_unidad']);
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
