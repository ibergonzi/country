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
    
    const ESTADO_BAJA = 0;
	const ESTADO_ACTIVO = 1;    

	// devuelve lista de tipos documentos preparada para los dropDownList
	// se usa:  $form->field($model, 'id_tipo_doc')->dropDownList($model->listaTiposdoc)
	public static function getListaConceptos($todos=true)
	{
		if ($todos) {
			$opciones = self::find()->where(['estado'=>self::ESTADO_ACTIVO])->asArray()->all();
		} else {
			// se excluye el concepto 0 (sin entrada)
			$opciones = self::find()->where(['estado'=>self::ESTADO_ACTIVO])->andWhere(['<>','id',0])->asArray()->all();
		}
		return ArrayHelper::map($opciones, 'id', 'concepto');
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
            [['concepto', 'req_tarjeta', 'req_seguro'], 'required'],
            [['req_tarjeta', 'req_seguro'], 'integer'],
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
            'req_tarjeta' => Yii::t('app', 'Req Tarjeta'),
            'req_seguro' => Yii::t('app', 'Req Seguro'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesos()
    {
        return $this->hasMany(Accesos::className(), ['id_concepto' => 'id']);
    }
}
