<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cur_curriculo_objetivo_integrador".
 *
 * @property int $id
 * @property int $curriculo_mec_nivel_id
 * @property string $codigo
 * @property string $detalle
 * @property bool $estado
 * @property int $orden
 *
 * @property CurriculoMecNiveles $curriculoMecNivel
 * @property KidsMicroObjetivos[] $kidsMicroObjetivos
 */
class CurCurriculoObjetivoIntegrador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cur_curriculo_objetivo_integrador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curriculo_mec_nivel_id', 'codigo', 'detalle'], 'required'],
            [['curriculo_mec_nivel_id', 'orden'], 'default', 'value' => null],
            [['curriculo_mec_nivel_id', 'orden'], 'integer'],
            [['detalle'], 'string'],
            [['estado'], 'boolean'],
            [['codigo'], 'string', 'max' => 10],
            [['codigo'], 'unique'],
            [['curriculo_mec_nivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMecNiveles::className(), 'targetAttribute' => ['curriculo_mec_nivel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'curriculo_mec_nivel_id' => 'Curriculo Mec Nivel ID',
            'codigo' => 'Codigo',
            'detalle' => 'Detalle',
            'estado' => 'Estado',
            'orden' => 'Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurriculoMecNivel()
    {
        return $this->hasOne(CurriculoMecNiveles::className(), ['id' => 'curriculo_mec_nivel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidsMicroObjetivos()
    {
        return $this->hasMany(KidsMicroObjetivos::className(), ['objetivo_id' => 'id']);
    }
}
