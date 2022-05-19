<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pca_detalle".
 *
 * @property int $id
 * @property int $desagregacion_cabecera_id
 * @property string $tipo
 * @property string $codigo
 * @property string $contenido
 * @property bool $estado
 *
 * @property PlanificacionDesagregacionCabecera $desagregacionCabecera
 */
class PcaDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pca_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['desagregacion_cabecera_id', 'tipo', 'contenido'], 'required'],
            [['desagregacion_cabecera_id'], 'default', 'value' => null],
            [['desagregacion_cabecera_id'], 'integer'],
            [['contenido'], 'string'],
            [['estado'], 'boolean'],
            [['tipo'], 'string', 'max' => 40],
            [['codigo'], 'string', 'max' => 30],
            [['desagregacion_cabecera_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionDesagregacionCabecera::className(), 'targetAttribute' => ['desagregacion_cabecera_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'desagregacion_cabecera_id' => 'Desagregacion Cabecera ID',
            'tipo' => 'Tipo',
            'codigo' => 'Codigo',
            'contenido' => 'Contenido',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesagregacionCabecera()
    {
        return $this->hasOne(PlanificacionDesagregacionCabecera::className(), ['id' => 'desagregacion_cabecera_id']);
    }
}
