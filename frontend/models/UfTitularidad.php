<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "uf_titularidad".
 *
 * @property integer $id
 * @property integer $id_uf
 * @property integer $tipo_movim
 * @property string $fec_desde
 * @property string $fec_hasta
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property Uf $idUf
 * @property UfTitularidadPersonas[] $ufTitularidadPersonas
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_uf', 'tipo_movim', 'fec_desde', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['id_uf', 'tipo_movim', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['fec_desde', 'fec_hasta', 'created_at', 'updated_at'], 'safe'],
            [['motivo_baja'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_uf' => Yii::t('app', 'Id Uf'),
            'tipo_movim' => Yii::t('app', 'Tipo Movim'),
            'fec_desde' => Yii::t('app', 'Fec Desde'),
            'fec_hasta' => Yii::t('app', 'Fec Hasta'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'estado' => Yii::t('app', 'Estado'),
            'motivo_baja' => Yii::t('app', 'Motivo Baja'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUf()
    {
        return $this->hasOne(Uf::className(), ['id' => 'id_uf']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUfTitularidadPersonas()
    {
        return $this->hasMany(UfTitularidadPersonas::className(), ['uf_titularidad_id' => 'id']);
    }
}
