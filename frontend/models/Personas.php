<?php

namespace frontend\models;

use Yii;

use yii\helpers\ArrayHelper;
use \frontend\models\Tiposdoc;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "personas".
 *
 * @property integer $id
 * @property string $apellido
 * @property string $nombre
 * @property string $nombre2
 * @property integer $id_tipo_doc
 * @property string $nro_doc
 * @property string $foto
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property string $estado
 * @property string $motivo_baja
 * @property Tiposdoc $idTipoDoc
 */
class Personas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'personas';
    }
    
    const ESTADO_BAJA = 0;
	const ESTADO_ACTIVO = 1;
    

	// devuelve lista de tipos documentos preparada para los dropDownList
	// se usa:  $form->field($model, 'id_tipo_doc')->dropDownList($model->listaTiposdoc)
	public static function getListaTiposdoc()
	{
		$opciones = Tiposdoc::find()->asArray()->all();
		return ArrayHelper::map($opciones, 'id', 'desc_tipo_doc_abr');
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
			$this->apellido=mb_strtoupper($this->apellido);
			$this->nombre=mb_strtoupper($this->nombre);
			$this->nombre2=mb_strtoupper($this->nombre2);						
 
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
            [['apellido', 'nombre', 'id_tipo_doc', 'nro_doc'], 'required'],
            [['id_tipo_doc', 'created_by', 'updated_by','nro_doc'], 'integer'],
            [['created_at', 'updated_at','estado','motivo_baja','id'], 'safe'],
            [['apellido', 'nombre', 'nombre2'], 'string', 'max' => 45],
			[['apellido', 'nombre', 'nombre2'], 'trim'],             
 			[['foto'], 'file', 'extensions'=>'jpg, jpeg'],  
            [['motivo_baja'], 'string', 'max' => 50],
   			['nro_doc','unique','targetAttribute' => ['nro_doc','id_tipo_doc','estado']], 
			['estado','default','value'=>Personas::ESTADO_ACTIVO],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'apellido' => Yii::t('app', 'Apellido'),
            'nombre' => Yii::t('app', '1 Nombre'),
            'nombre2' => Yii::t('app', '2 Nombre'),
            'id_tipo_doc' => Yii::t('app', 'T.Doc.'),
            'nro_doc' => Yii::t('app', 'Nro.Doc.'),
            'foto' => Yii::t('app', 'Foto'),
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
    
    

	public static function formateaPersonaSelect2($id,$es_por_nro) 
	{
		$p=Personas::findOne($id);
		if ($es_por_nro) {
			$r=$p->nro_doc . ' ' . $p->apellido.' '.$p->nombre.' '.$p->nombre2.  ' ('. $id . ')';
		} else {
			$r=$p->apellido.' '.$p->nombre.' '.$p->nombre2. ' D:' . $p->nro_doc . ' ('. $id . ')';			
		}
		return $r;
	}    


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDoc()
    {
        return $this->hasOne(Tiposdoc::className(), ['id' => 'id_tipo_doc']);
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
