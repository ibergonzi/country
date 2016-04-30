<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Accesos]].
 *
 * @see Accesos
 */
class AccesosVistaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Accesos[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Accesos|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
