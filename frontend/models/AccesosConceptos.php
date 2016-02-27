<?php

namespace frontend\models;

use Yii;

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
