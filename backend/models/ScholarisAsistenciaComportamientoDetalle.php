<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_comportamiento_detalle".
 *
 * @property int $id
 * @property int $comportamiento_id
 * @property string $codigo
 * @property string $nombre
 * @property int $cantidad_descuento
 * @property string $punto_descuento
 * @property int $total_x_unidad
 * @property string $code_fj
 */
class ScholarisAsistenciaComportamientoDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_comportamiento_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comportamiento_id', 'codigo', 'nombre'], 'required'],
            [['comportamiento_id', 'cantidad_descuento', 'total_x_unidad'], 'default', 'value' => null],
            [['comportamiento_id', 'cantidad_descuento', 'total_x_unidad','limite'], 'integer'],
            [['punto_descuento'], 'number'],
            [['codigo'], 'string', 'max' => 5],
            [['activo'], 'boolean'],
            [['nombre'], 'string', 'max' => 150],
            [['code_fj'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comportamiento_id' => 'Comportamiento ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
            'cantidad_descuento' => 'Cantidad Descuento',
            'punto_descuento' => 'Punto Descuento',
            'total_x_unidad' => 'Total X Unidad',
            'code_fj' => 'Code Fj',
            'limite' => 'Limite',
            'activo' => 'Activo',
        ];
    }
    
    public function getComportamiento()
    {
        return $this->hasOne(ScholarisAsistenciaComportamiento::className(), ['id' => 'comportamiento_id']);
    }
}
