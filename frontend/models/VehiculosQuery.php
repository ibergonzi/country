<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Vehiculos]].
 *
 * @see Vehiculos
 */
class VehiculosQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Vehiculos[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Vehiculos|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}