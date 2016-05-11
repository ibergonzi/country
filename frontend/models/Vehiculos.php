<?php

namespace frontend\models;

use Yii;

use yii\helpers\ArrayHelper;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "vehiculos".
 *
 * @property integer $id
 * @property string $patente
 * @property integer $marca
 * @property string $modelo
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
			$this->marca=mb_strtoupper($this->marca);
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


	public static function getMarcasVehiculos() {
       $sql = "SELECT DISTINCT marca FROM vehiculos WHERE id NOT IN (:pie,:bici,:generico) ORDER BY marca";

       $command = \Yii::$app->db->createCommand($sql);
       $command->bindParam(":pie", \Yii::$app->params['sinVehiculo.id']);
       $command->bindParam(":bici", \Yii::$app->params['bicicleta.id']);
       $command->bindParam(":generico", \Yii::$app->params['generico.id']);       

       $result=$command->queryAll();
       
       $p=[];
	   foreach ($result as $k=>$v) $p[]=$v['marca'];

	   return $p;
    }	
    
    
	public static function getModelosVehiculos() {
       $sql = "SELECT DISTINCT modelo FROM vehiculos WHERE modelo IS NOT NULL ORDER BY modelo";

       $command = \Yii::$app->db->createCommand($sql);

       $result=$command->queryAll();
       
       $p=[];
	   foreach ($result as $k=>$v) $p[]=$v['modelo'];

	   return $p;
    }	
    
	public static function getColoresVehiculos() {
       $sql = "SELECT DISTINCT color FROM vehiculos WHERE color IS NOT NULL ORDER BY color";

       $command = \Yii::$app->db->createCommand($sql);

       $result=$command->queryAll();
       
       $p=[];
	   foreach ($result as $k=>$v) $p[]=$v['color'];

	   return $p;
    }	          



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['marca','patente'], 'required'],
            [['created_by', 'updated_by', 'estado'], 'integer'],
            [['created_at', 'updated_at','created_by','updated_by','modelo','color'], 'safe'],
            [['modelo'], 'string', 'max' => 30],
            [['marca'], 'string', 'max' => 20],
            [['patente', 'color'], 'string', 'max' => 10],
            [['motivo_baja'], 'string', 'max' => 50],
            [['patente'], 'unique'],
			[['modelo', 'patente', 'color','marca'], 'trim'],   
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
            'marca' => Yii::t('app', 'Marca'),
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

	public static function formateaVehiculoSelect2($id) 
	{
		$p=Vehiculos::findOne($id);
		$r=$p->patente .' '. $p->marca .' '.$p->modelo .' '. $p->color.  ' ('. $id . ')';
		return $r;
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

    public function getUltIngreso()
    {
        return $this->hasOne(Accesos::className(), ['ing_id_vehiculo' => 'id'])
        	->orderBy(['id' => SORT_DESC])->limit(1)->one();			
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
