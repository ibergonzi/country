<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "uf".
 *
 * @property integer $id
 * @property integer $loteo
 * @property integer $manzana
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property AccesosAutorizantes[] $accesosAutorizantes
 * @property Autorizantes[] $autorizantes
 * @property UfTitularidad[] $ufTitularidads
 */
class Uf extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uf';
    }
    
    const ESTADO_BAJA = 0;
	const ESTADO_OCUPADO = 1;
	const ESTADO_VACIO = 2;
	const ESTADO_VACIO_FIDEI = 3;	
	const ESTADO_FOREST = 4;
    


	// funcion agregada a mano
	public static function getEstados($key=null)
	{
		$estados=[self::ESTADO_OCUPADO=>'Ocupado',self::ESTADO_VACIO=>'Vacío',
				  self::ESTADO_VACIO_FIDEI=>'Vacío/Fideic.',
				  self::ESTADO_FOREST=>'Forestación',self::ESTADO_BAJA=>'Baja'];
	    if ($key !== null) {
			return $estados[$key];
		}
		return $estados;
	}	
	
	public static function getEstadosModif()
	{
		$estados=[self::ESTADO_OCUPADO=>'Ocupado',self::ESTADO_VACIO=>'Vacío',
				  self::ESTADO_VACIO_FIDEI=>'Vacío/Fideic.',
				  self::ESTADO_FOREST=>'Forestación'];
		return $estados;
	}	   	   
	
	
	public static function getSuperficieTotal()
	{
		$query = (new \yii\db\Query())->from('uf')->where(['<>','estado',self::ESTADO_BAJA]);
		$sum = $query->sum('superficie');
		return $sum;
	}
	 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'loteo', 'manzana', ], 'required'],
            [['id', 'loteo', 'manzana', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
			[['superficie', ], 'number','numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],            
            [['motivo_baja'], 'string', 'max' => 50],
        ];
    }
    
	public function beforeSave($insert) 
	{
        if (parent::beforeSave($insert)) {
            $this->superficie = str_replace(",", ".", $this->superficie);
            return true;
        } else {
            return false;
        }
    }    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'loteo' => 'Loteo',
            'manzana' => 'Manzana',
            'superficie' => 'Superficie',
            'coeficiente' => 'Coeficiente',            
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
     * @return \yii\db\ActiveQuery
     */
    public function getAccesosAutorizantes()
    {
        return $this->hasMany(AccesosAutorizantes::className(), ['id_uf' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutorizantes()
    {
        return $this->hasMany(Autorizantes::className(), ['id_uf' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUfTitularidad()
    {
        return $this->hasMany(UfTitularidad::className(), ['id_uf' => 'id']);
    }
    


    public function getUltUfTitularidad()
    {
        return $this->hasOne(UfTitularidad::className(), ['id_uf' => 'id'])->where(['ultima'=>1]);
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
