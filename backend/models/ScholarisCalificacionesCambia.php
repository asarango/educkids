<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_calificaciones_cambia".
 *
 * @property int $id
 * @property int $nota_id
 * @property string $fecha_modificacion
 * @property string $nota_saliente
 * @property string $nota_nueva
 * @property string $motivo
 * @property string $documento
 * @property string $aprobado_por
 * @property int $usuario_modifica
 */
class ScholarisCalificacionesCambia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_calificaciones_cambia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nota_id', 'fecha_modificacion', 'nota_saliente', 'nota_nueva', 'motivo', 'documento', 'aprobado_por', 'usuario_modifica'], 'required'],
            [['nota_id', 'usuario_modifica'], 'default', 'value' => null],
            [['nota_id', 'usuario_modifica'], 'integer'],
            [['fecha_modificacion'], 'safe'],
            [['nota_saliente', 'nota_nueva'], 'number'],
            [['motivo'], 'string', 'max' => 255],
            [['documento'], 'string', 'max' => 150],
            [['aprobado_por'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nota_id' => 'Nota ID',
            'fecha_modificacion' => 'Fecha Modificacion',
            'nota_saliente' => 'Nota Saliente',
            'nota_nueva' => 'Nota Nueva',
            'motivo' => 'Motivo',
            'documento' => 'Documento',
            'aprobado_por' => 'Aprobado Por',
            'usuario_modifica' => 'Usuario Modifica',
        ];
    }
}
