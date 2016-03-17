<?php

namespace frontend\models;

use Yii;

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
 * @property AccesosUf[] $accesosUfs
 * @property Accesos[] $idAccesos
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'loteo', 'manzana', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['id', 'loteo', 'manzana', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'loteo' => Yii::t('app', 'Loteo'),
            'manzana' => Yii::t('app', 'Manzana'),
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
    public function getAccesosUfs()
    {
        return $this->hasMany(AccesosUf::className(), ['id_uf' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesos()
    {
        return $this->hasMany(Accesos::className(), ['id' => 'id_acceso'])->viaTable('accesos_uf', ['id_uf' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUfTitularidad()
    {
        return $this->hasMany(UfTitularidad::className(), ['id_uf' => 'id']);
    }
}
