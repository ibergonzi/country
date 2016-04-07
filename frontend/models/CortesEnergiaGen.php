<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cortes_energia_gen".
 *
 * @property integer $id
 * @property integer $id_cortes_energia
 * @property integer $id_generador
 * @property string $hora_desde
 * @property string $hora_hasta
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property CortesEnergia $idCortesEnergia
 * @property Generadores $idGenerador
 */
class CortesEnergiaGen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cortes_energia_gen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_cortes_energia', 'id_generador', 'hora_desde', 'hora_hasta', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['id_cortes_energia', 'id_generador', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['hora_desde', 'hora_hasta', 'created_at', 'updated_at'], 'safe'],
            [['motivo_baja'], 'string', 'max' => 50],
            [['id_cortes_energia'], 'exist', 'skipOnError' => true, 'targetClass' => CortesEnergia::className(), 'targetAttribute' => ['id_cortes_energia' => 'id']],
            [['id_generador'], 'exist', 'skipOnError' => true, 'targetClass' => Generadores::className(), 'targetAttribute' => ['id_generador' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_cortes_energia' => 'Id Cortes Energia',
            'id_generador' => 'Id Generador',
            'hora_desde' => 'Hora Desde',
            'hora_hasta' => 'Hora Hasta',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'estado' => 'Estado',
            'motivo_baja' => 'Motivo Baja',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCortesEnergia()
    {
        return $this->hasOne(CortesEnergia::className(), ['id' => 'id_cortes_energia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGenerador()
    {
        return $this->hasOne(Generadores::className(), ['id' => 'id_generador']);
    }
}
